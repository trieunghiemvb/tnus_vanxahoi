<?php
//ini_set("memory_limit","512M");
set_time_limit(0);
define('AREA','A');
require '../../../init.php';
require_once ('../printmark.php');
include('../../../libs/phpexcel/PHPExcel.php');

// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
// $cacheSettings = array('memoryCacheSize' => '32MB');
// PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$act = isset($_POST['act']) ? trim($_POST['act']) : "";
$db = new Database;
$prefix = DB_TABLE_PREFIX;
$mark_4 = array('A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0);

$dt = new DateTime;

$styleBorder1 = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$styleBorder2 = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		),
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_HAIR
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		),
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$styleBorder3 = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_HAIR
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		),
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_NONE
		)
	)
);
$styleBorder4 = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);

$inputFileType = 'Excel5';

switch ($act) {
	case 'printcourse':
		# In bang diem toan khoa
		# Doc file excel template
		$inputFileName = 'xlstemplate/classmark.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);
		$sheetindex = 1;

		$id_class = isset($_POST['class']) ? intval($_POST['class']) : 0;
		$mark_select_type = isset($_POST['sel_type']) ? trim($_POST['sel_type']) : 'Max';
		if ($id_class > 0){
			# Lay thong tin lop hoc va khoa hoc
			$class_info = $db->query("SELECT c.class_name, c.id_course, cr.period FROM {$prefix}class c, {$prefix}course cr WHERE c.id_course = cr.id AND c.id = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			$title = "BẢNG ĐIỂM TỔNG HỢP TOÀN KHÓA - KHÓA HỌC " . $class_info->period;
			# Lay danh sach hoc vien theo lop
			$students = $db->queryAll("SELECT p.first_name, p.last_name, s.student_code, s.id, s.id_major FROM {$prefix}profile p, {$prefix}student s WHERE s.id_profile = p.id AND s.id_class = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			$students = reSort($students, array('first_name', 'last_name'));
			# Lay thong tin cac chuyen nganh theo lop
			$majors = $db->queryAll("SELECT * FROM {$prefix}major WHERE id IN (SELECT id_major FROM {$prefix}student WHERE id_class = :id_class)", array(':id_class' => $id_class), PDO::FETCH_OBJ);

			foreach ($majors as $item) {
				$major_name = $item->major_name;
				# Moi chuyen nganh tao mot sheet moi
				$newSheet = clone $objPHPExcel->getSheetByName('layout');
				$newSheet->setTitle(convertVi($major_name));
				$objPHPExcel->addSheet($newSheet, $sheetindex);
				$objPHPExcel->setActiveSheetIndex($sheetindex);
				$sheet = $objPHPExcel->getActiveSheet();
				# Set pagesize cho sheet
				$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
				# In danh sach hoc vien
				$member_row_start = 10;
				$title_row_start = $member_row_start - 2;
				$stt = 1;
				$student_order = array();
				foreach ($students as $student) {
					if ($student->id_major == $item->id){
						$sheet->setCellValue('A'.$member_row_start, $stt);
						$sheet->setCellValue('B'.$member_row_start, $student->student_code);
						$sheet->setCellValue('C'.$member_row_start, $student->last_name);
						$sheet->setCellValue('D'.$member_row_start, $student->first_name);
						$member_row_start++;
						$stt++;
						$student_order[] = $student->id;
					}
				}
				# Lay danh sach cac mon hoc tu khung chuong trinh dao tao theo khoa, chuyen nganh, ky hoc
				$subjects = $db->queryAll("SELECT su.name, tp.curriculum, su.id FROM {$prefix}training_plan tp, {$prefix}subject su, {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE su.id = tp.id_subject AND csd.id_class_subject = cs.id AND cs.id_subject = tp.id_subject AND tp.id_course = :id_course AND tp.id_major = :id_major GROUP BY su.id ORDER BY su.id ASC", array(':id_course' => $class_info->id_course, ':id_major' => $item->id), PDO::FETCH_OBJ);

				# Xuat du lieu mon hoc thanh cot tieu de
				$start_col = 4;
				$i = 0;
				$max_subject_lenght = 0;
				$total_curriculum = 0;
				$total_mark = array();
				foreach ($student_order as $item) {
					$total_mark[$item] = 0;
				}
				foreach ($subjects as $subject) {
					if (strlen($subject->name) > $max_subject_lenght){
						$max_subject_lenght = strlen($subject->name);
						$numrows = getRowCount($subject->name.' ('.$subject->curriculum.')');
						$sheet->getRowDimension($title_row_start)->setRowHeight($numrows * 12.75 + 2.25);
					}
					$sheet->mergeCellsByColumnAndRow($start_col + $i*3, $title_row_start, $start_col + $i*3 + 2, $title_row_start);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start, $subject->name . ' ('.$subject->curriculum.')');
					$sheet->getStyleByColumnAndRow($start_col + $i*3, $title_row_start)->getAlignment()->setWrapText(true);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start + 1, 'TP');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $title_row_start + 1, 'Thi');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $title_row_start + 1, 'TKHP');
					$total_curriculum += $subject->curriculum;
					# Lay chi tiet diem cua mon hoc
					if ($mark_select_type == 'Max')
						$marks = $db->queryAll("SELECT csd.id_student, csd.mark_component, csd.mark_exam, csd.mark_sumary FROM {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE cs.id = csd.id_class_subject AND cs.id_subject = :id_subject AND FIND_IN_SET(id_student, :list_student) ORDER BY csd.mark_sumary", array(':id_subject' => $subject->id, ':list_student' => implode(',', $student_order)), PDO::FETCH_OBJ);
					else
						$marks = $db->queryAll("SELECT csd.id_student, csd.mark_component, csd.mark_exam, csd.mark_sumary FROM {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE cs.id = csd.id_class_subject AND cs.id_subject = :id_subject AND FIND_IN_SET(id_student, :list_student) AND csd.count = 1", array(':id_subject' => $subject->id, ':list_student' => implode(',', $student_order)), PDO::FETCH_OBJ);

					$marks_detail = array();
					foreach ($marks as $mark) {
						$marks_detail[$mark->id_student] = array('tp' => $mark->mark_component, 'th' => $mark->mark_exam, 'tkhp' => $mark->mark_sumary);
					}
					# Dua chi tiet diem vao cac cot tuong ung;
					$mark_row_start = $title_row_start + 2;
					$j = 0;
					foreach ($student_order as $item) {
						$sheet->setCellValueByColumnAndRow($start_col + $i*3, $mark_row_start + $j, $marks_detail[$item]['tp']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $mark_row_start + $j, $marks_detail[$item]['th']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $mark_row_start + $j, $marks_detail[$item]['tkhp']);
						$total_mark[$item] = $total_mark[$item] + $marks_detail[$item]['tkhp']*$subject->curriculum;
						$j++;
					}

					$i++;
				}
				# Auto width
				foreach (range(4, PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn())) as $col) {
					$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($col))->setWidth(5.5);
				}
				# Them cot TKHP
				$max_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sheet->mergeCellsByColumnAndRow($max_col, $title_row_start, $max_col, $title_row_start + 1);
				$sheet->setCellValueByColumnAndRow($max_col, $title_row_start, 'TBCHT');
				$sheet->getColumnDimension($sheet->getHighestDataColumn())->setWidth(12);
				$j = 0;
				foreach ($student_order as $item) {
					$sheet->setCellValue($sheet->getHighestDataColumn().($mark_row_start + $j), ROUND($total_mark[$item] / $total_curriculum, 2));
					$j++;
				}
				# Centered o diem
				$sheet->getStyle('E2:' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				# Border title
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().($title_row_start + 1))->applyFromArray($styleBorder1);
				# Border lai tung dung du lieu
				for($i = $title_row_start + 2; $i <= $sheet->getHighestDataRow(); $i++){
					$sheet->duplicateStyleArray($styleBorder2, 'A'.$i.':B'.$i);
					$sheet->duplicateStyleArray($styleBorder3, 'C'.$i.':D'.$i);
					$sheet->duplicateStyleArray($styleBorder2, 'E'.$i.':'.$sheet->getHighestDataColumn().$i);
				}
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->applyFromArray($styleBorder4);
				# Dua cac thong tin tieu de
				$sheet->mergeCells('E1:' . $sheet->getHighestDataColumn().'1');
				$sheet->mergeCells('E2:' . $sheet->getHighestDataColumn().'2');
				$sheet->mergeCells('A4:' . $sheet->getHighestDataColumn().'4');
				$sheet->setCellValue('A4', $title);
				$sheet->setCellValue('A6', 'Lớp: ' . $class_info->class_name);
				$sheet->mergeCells('E6:' . $sheet->getHighestDataColumn().'6');
				$sheet->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->setCellValue('E6', 'Chuyên ngành: ' . $major_name);

				# Dua thong tin ngay va nguoi ky
				$sign_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sign_row = $sheet->getHighestDataRow() + 2;
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row, $sign_col - 1, $sign_row);
				$date_vn = "ngày ".$dt->format('d')." tháng ".$dt->format('m')." năm ".$dt->format('Y');
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row, "Thái Nguyên, ".$date_vn);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getFont()->setItalic(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row + 1, $sign_col - 1, $sign_row + 1);
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row + 1, 'PHÒNG ĐÀO TẠO');
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getFont()->setBold(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				# Tang sheetindex de su dung cho chuyen nganh sau
				$sheetindex++;
			}

			$objPHPExcel->setActiveSheetIndexByName('layout');
			$objPHPExcel->removeSheetByIndex(0);

			$outputFileType = 'Excel5';
			$outputFileName = 'BangDiem-ToanKhoa.xls';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
			$objWriter->save('php://output');
		}
		break;
	case 'printyear':
		# In bang diem theo nam hoc
		# Doc file excel template
		$inputFileName = 'xlstemplate/classmark.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);
		$sheetindex = 1;

		$id_class = 1;//isset($_POST['class']) ? intval($_POST['class']) : 0;
		$sel_year = '2008_2009'; isset($_POST['year']) ? intval($_POST['year']) : 0;
		$mark_select_type = isset($_POST['sel_type']) ? trim($_POST['sel_type']) : 'Max';
		if ($id_class > 0 && $sel_year != ""){
			# Lay thong tin lop hoc va khoa hoc
			$class_info = $db->query("SELECT c.class_name, c.id_course, cr.period FROM {$prefix}class c, {$prefix}course cr WHERE c.id_course = cr.id AND c.id = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			list($start_year, $end_year) = explode('_', $class_info->period);
			$years = array();
			while($start_year + 1 <= $end_year){
				$year = $start_year . '_' . ($start_year + 1);
				$years[] = $year;
				$start_year++;
			}
			$term = array_search($sel_year, $years);
			$term_list = array($term*2 + 1, $term*2 + 2);
			$title = "BẢNG ĐIỂM TỔNG HỢP NĂM HỌC: " . $sel_year . ' - KHÓA HỌC ' . $class_info->period;
			# Lay danh sach hoc vien theo lop
			$students = $db->queryAll("SELECT p.first_name, p.last_name, s.student_code, s.id, s.id_major FROM {$prefix}profile p, {$prefix}student s WHERE s.id_profile = p.id AND s.id_class = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			$students = reSort($students, array('first_name', 'last_name'));
			# Lay thong tin cac chuyen nganh theo lop
			$majors = $db->queryAll("SELECT * FROM {$prefix}major WHERE id IN (SELECT id_major FROM {$prefix}student WHERE id_class = :id_class)", array(':id_class' => $id_class), PDO::FETCH_OBJ);

			foreach ($majors as $item) {
				$major_name = $item->major_name;
				# Moi chuyen nganh tao mot sheet moi
				$newSheet = clone $objPHPExcel->getSheetByName('layout');
				$newSheet->setTitle(convertVi($major_name));
				$objPHPExcel->addSheet($newSheet, $sheetindex);
				$objPHPExcel->setActiveSheetIndex($sheetindex);
				$sheet = $objPHPExcel->getActiveSheet();
				# Set pagesize cho sheet
				$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
				# In danh sach hoc vien
				$member_row_start = 10;
				$title_row_start = $member_row_start - 2;
				$stt = 1;
				$student_order = array();
				foreach ($students as $student) {
					if ($student->id_major == $item->id){
						$sheet->setCellValue('A'.$member_row_start, $stt);
						$sheet->setCellValue('B'.$member_row_start, $student->student_code);
						$sheet->setCellValue('C'.$member_row_start, $student->last_name);
						$sheet->setCellValue('D'.$member_row_start, $student->first_name);
						$member_row_start++;
						$stt++;
						$student_order[] = $student->id;
					}
				}
				# Lay danh sach cac mon hoc tu khung chuong trinh dao tao theo khoa, chuyen nganh, ky hoc
				$subjects = $db->queryAll("SELECT su.name, tp.curriculum, su.id FROM {$prefix}training_plan tp, {$prefix}subject su, {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE su.id = tp.id_subject AND csd.id_class_subject = cs.id AND cs.id_subject = tp.id_subject AND tp.id_course = :id_course AND tp.id_major = :id_major AND FIND_IN_SET(tp.term, :term_list) GROUP BY su.id ORDER BY su.id ASC", array(':id_course' => $class_info->id_course, ':id_major' => $item->id, ':term_list' => implode(',', $term_list)), PDO::FETCH_OBJ);

				# Xuat du lieu mon hoc thanh cot tieu de
				$start_col = 4;
				$i = 0;
				$max_subject_lenght = 0;
				$total_curriculum = 0;
				$total_mark = array();
				foreach ($student_order as $item) {
					$total_mark[$item] = 0;
				}
				foreach ($subjects as $subject) {
					if (strlen($subject->name) > $max_subject_lenght){
						$max_subject_lenght = strlen($subject->name);
						$numrows = getRowCount($subject->name.' ('.$subject->curriculum.')');
						$sheet->getRowDimension($title_row_start)->setRowHeight($numrows * 12.75 + 2.25);
					}
					$sheet->mergeCellsByColumnAndRow($start_col + $i*3, $title_row_start, $start_col + $i*3 + 2, $title_row_start);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start, $subject->name . ' ('.$subject->curriculum.')');
					$sheet->getStyleByColumnAndRow($start_col + $i*3, $title_row_start)->getAlignment()->setWrapText(true);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start + 1, 'TP');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $title_row_start + 1, 'Thi');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $title_row_start + 1, 'TKHP');
					$total_curriculum += $subject->curriculum;
					# Lay chi tiet diem cua mon hoc
					if ($mark_select_type == 'Max')
						$marks = $db->queryAll("SELECT csd.id_student, csd.mark_component, csd.mark_exam, csd.mark_sumary FROM {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE cs.id = csd.id_class_subject AND cs.id_subject = :id_subject AND FIND_IN_SET(id_student, :list_student) ORDER BY csd.mark_sumary", array(':id_subject' => $subject->id, ':list_student' => implode(',', $student_order)), PDO::FETCH_OBJ);
					else
						$marks = $db->queryAll("SELECT csd.id_student, csd.mark_component, csd.mark_exam, csd.mark_sumary FROM {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE cs.id = csd.id_class_subject AND cs.id_subject = :id_subject AND FIND_IN_SET(id_student, :list_student) AND csd.count = 1", array(':id_subject' => $subject->id, ':list_student' => implode(',', $student_order)), PDO::FETCH_OBJ);

					$marks_detail = array();
					foreach ($marks as $mark) {
						$marks_detail[$mark->id_student] = array('tp' => $mark->mark_component, 'th' => $mark->mark_exam, 'tkhp' => $mark->mark_sumary);
					}
					# Dua chi tiet diem vao cac cot tuong ung;
					$mark_row_start = $title_row_start + 2;
					$j = 0;
					foreach ($student_order as $item) {
						$sheet->setCellValueByColumnAndRow($start_col + $i*3, $mark_row_start + $j, $marks_detail[$item]['tp']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $mark_row_start + $j, $marks_detail[$item]['th']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $mark_row_start + $j, $marks_detail[$item]['tkhp']);
						$total_mark[$item] = $total_mark[$item] + $marks_detail[$item]['tkhp']*$subject->curriculum;
						$j++;
					}

					$i++;
				}
				# Auto width
				foreach (range(4, PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn())) as $col) {
					$sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($col))->setWidth(5.5);
				}
				# Them cot TKHP
				$max_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sheet->mergeCellsByColumnAndRow($max_col, $title_row_start, $max_col, $title_row_start + 1);
				$sheet->setCellValueByColumnAndRow($max_col, $title_row_start, 'TBCHT');
				$sheet->getColumnDimension($sheet->getHighestDataColumn())->setWidth(12);
				$j = 0;
				foreach ($student_order as $item) {
					$sheet->setCellValue($sheet->getHighestDataColumn().($mark_row_start + $j), ROUND($total_mark[$item] / $total_curriculum, 2));
					$j++;
				}
				# Centered o diem
				$sheet->getStyle('E2:' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				# Border title
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().($title_row_start + 1))->applyFromArray($styleBorder1);
				# Border lai tung dung du lieu
				for($i = $title_row_start + 2; $i <= $sheet->getHighestDataRow(); $i++){
					$sheet->duplicateStyleArray($styleBorder2, 'A'.$i.':B'.$i);
					$sheet->duplicateStyleArray($styleBorder3, 'C'.$i.':D'.$i);
					$sheet->duplicateStyleArray($styleBorder2, 'E'.$i.':'.$sheet->getHighestDataColumn().$i);
				}
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->applyFromArray($styleBorder4);
				# Dua cac thong tin tieu de
				$sheet->mergeCells('E1:' . $sheet->getHighestDataColumn().'1');
				$sheet->mergeCells('E2:' . $sheet->getHighestDataColumn().'2');
				$sheet->mergeCells('A4:' . $sheet->getHighestDataColumn().'4');
				$sheet->setCellValue('A4', $title);
				$sheet->setCellValue('A6', 'Lớp: ' . $class_info->class_name);
				$sheet->mergeCells('E6:' . $sheet->getHighestDataColumn().'6');
				$sheet->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->setCellValue('E6', 'Chuyên ngành: ' . $major_name);

				# Dua thong tin ngay va nguoi ky
				$sign_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sign_row = $sheet->getHighestDataRow() + 2;
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row, $sign_col - 1, $sign_row);
				$date_vn = "ngày ".$dt->format('d')." tháng ".$dt->format('m')." năm ".$dt->format('Y');
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row, "Thái Nguyên, ".$date_vn);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getFont()->setItalic(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row + 1, $sign_col - 1, $sign_row + 1);
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row + 1, 'PHÒNG ĐÀO TẠO');
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getFont()->setBold(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				# Tang sheetindex de su dung cho chuyen nganh sau
				$sheetindex++;
			}

			$objPHPExcel->setActiveSheetIndexByName('layout');
			$objPHPExcel->removeSheetByIndex(0);

			$outputFileType = 'Excel5';
			$outputFileName = 'BangDiem-TheoNamHoc.xls';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
			$objWriter->save('php://output');
		}
		break;
	case 'printterm':
		# In bang diem theo hoc ky
		# Doc file excel template
		$inputFileName = 'xlstemplate/classmark.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);
		$sheetindex = 1;

		$id_class = isset($_POST['class']) ? intval($_POST['class']) : 0;
		$term = isset($_POST['term']) ? intval($_POST['term']) : 0;
		if ($id_class > 0 && $term > 0){
			# Lay thong tin lop hoc va khoa hoc
			$class_info = $db->query("SELECT c.class_name, c.id_course, cr.period FROM {$prefix}class c, {$prefix}course cr WHERE c.id_course = cr.id AND c.id = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			list($start_year, $end_year) = explode('_', $class_info->period);
			$years = array();
			while($start_year + 1 <= $end_year){
				$year = $start_year . '_' . ($start_year + 1);
				$years[] = $year;
				$start_year++;
			}
			$year_index = ($term - 1) / 2;
			$title = "BẢNG ĐIỂM TỔNG HỢP HỌC KỲ: " . $years[$year_index] . "_" . ($term - (floor(($term - 1) / 2) * 2));
			$title .= ' - KHÓA HỌC ' . $class_info->period;
			# Lay danh sach hoc vien theo lop
			$students = $db->queryAll("SELECT p.first_name, p.last_name, s.student_code, s.id, s.id_major FROM {$prefix}profile p, {$prefix}student s WHERE s.id_profile = p.id AND s.id_class = :id_class", array(':id_class' => $id_class), PDO::FETCH_OBJ);
			$students = reSort($students, array('first_name', 'last_name'));
			# Lay thong tin cac chuyen nganh theo lop
			$majors = $db->queryAll("SELECT * FROM {$prefix}major WHERE id IN (SELECT id_major FROM {$prefix}student WHERE id_class = :id_class)", array(':id_class' => $id_class), PDO::FETCH_OBJ);

			foreach ($majors as $item) {
				$major_name = $item->major_name;
				# Moi chuyen nganh tao mot sheet moi
				$newSheet = clone $objPHPExcel->getSheetByName('layout');
				$newSheet->setTitle(convertVi($major_name));
				$objPHPExcel->addSheet($newSheet, $sheetindex);
				$objPHPExcel->setActiveSheetIndex($sheetindex);
				$sheet = $objPHPExcel->getActiveSheet();
				# Set pagesize cho sheet
				$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
				# In danh sach hoc vien
				$member_row_start = 10;
				$title_row_start = $member_row_start - 2;
				$stt = 1;
				$student_order = array();
				foreach ($students as $student) {
					if ($student->id_major == $item->id){
						$sheet->setCellValue('A'.$member_row_start, $stt);
						$sheet->setCellValue('B'.$member_row_start, $student->student_code);
						$sheet->setCellValue('C'.$member_row_start, $student->last_name);
						$sheet->setCellValue('D'.$member_row_start, $student->first_name);
						$member_row_start++;
						$stt++;
						$student_order[] = $student->id;
					}
				}
				# Lay danh sach cac mon hoc tu khung chuong trinh dao tao theo khoa, chuyen nganh, ky hoc
				$subjects = $db->queryAll("SELECT su.name, tp.curriculum, su.id FROM {$prefix}training_plan tp, {$prefix}subject su WHERE su.id = tp.id_subject AND tp.id_course = :id_course AND tp.id_major = :id_major AND tp.term = :term ORDER BY su.id ASC", array(':id_course' => $class_info->id_course, ':id_major' => $item->id, ':term' => $term), PDO::FETCH_OBJ);
				# Xuat du lieu mon hoc thanh cot tieu de
				$start_col = 4;
				$i = 0;
				$max_subject_lenght = 0;
				$total_curriculum = 0;
				$total_mark = array();
				foreach ($student_order as $item) {
					$total_mark[$item] = 0;
				}
				foreach ($subjects as $subject) {
					if (strlen($subject->name) > $max_subject_lenght){
						$max_subject_lenght = strlen($subject->name);
						$numrows = getRowCount($subject->name.' ('.$subject->curriculum.')');
						$sheet->getRowDimension($title_row_start)->setRowHeight($numrows * 12.75 + 2.25);
					}
					$sheet->mergeCellsByColumnAndRow($start_col + $i*3, $title_row_start, $start_col + $i*3 + 2, $title_row_start);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start, $subject->name . ' ('.$subject->curriculum.')');
					$sheet->getStyleByColumnAndRow($start_col + $i*3, $title_row_start)->getAlignment()->setWrapText(true);
					$sheet->setCellValueByColumnAndRow($start_col + $i*3, $title_row_start + 1, 'TP');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $title_row_start + 1, 'Thi');
					$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $title_row_start + 1, 'TKHP');
					$total_curriculum += $subject->curriculum;
					# Lay chi tiet diem cua mon hoc
					$marks = $db->queryAll("SELECT * FROM {$prefix}class_subject_details csd, {$prefix}class_subject cs WHERE cs.id = csd.id_class_subject AND cs.id_subject = :id_subject AND FIND_IN_SET(id_student, :list_student)", array(':id_subject' => $subject->id, ':list_student' => implode(',', $student_order)), PDO::FETCH_OBJ);
					$marks_detail = array();
					foreach ($marks as $mark) {
						$marks_detail[$mark->id_student] = array('tp' => $mark->mark_component, 'th' => $mark->mark_exam, 'tkhp' => $mark->mark_sumary);
					}
					# Dua chi tiet diem vao cac cot tuong ung;
					$mark_row_start = $title_row_start + 2;
					$j = 0;
					foreach ($student_order as $item) {
						$sheet->setCellValueByColumnAndRow($start_col + $i*3, $mark_row_start + $j, $marks_detail[$item]['tp']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 1, $mark_row_start + $j, $marks_detail[$item]['th']);
						$sheet->setCellValueByColumnAndRow($start_col + $i*3 + 2, $mark_row_start + $j, $marks_detail[$item]['tkhp']);
						$total_mark[$item] = $total_mark[$item] + $marks_detail[$item]['tkhp']*$subject->curriculum;
						$j++;
					}

					$i++;
				}
				# Auto width
				foreach (range('E', $sheet->getHighestDataColumn()) as $col) {
					$sheet->getColumnDimension($col)->setWidth(5.5);
				}
				# Them cot TKHP
				$max_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sheet->mergeCellsByColumnAndRow($max_col, $title_row_start, $max_col, $title_row_start + 1);
				$sheet->setCellValueByColumnAndRow($max_col, $title_row_start, 'TBCHT');
				$sheet->getColumnDimension($sheet->getHighestDataColumn())->setWidth(12);
				$j = 0;
				foreach ($student_order as $item) {
					$sheet->setCellValue($sheet->getHighestDataColumn().($mark_row_start + $j), ROUND($total_mark[$item] / $total_curriculum, 2));
					$j++;
				}
				# Centered o diem
				$sheet->getStyle('E2:' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				# Border title
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().($title_row_start + 1))->applyFromArray($styleBorder1);
				# Border lai tung dung du lieu
				for($i = $title_row_start + 2; $i <= $sheet->getHighestDataRow(); $i++){
					$sheet->duplicateStyleArray($styleBorder2, 'A'.$i.':B'.$i);
					$sheet->duplicateStyleArray($styleBorder3, 'C'.$i.':D'.$i);
					$sheet->duplicateStyleArray($styleBorder2, 'E'.$i.':'.$sheet->getHighestDataColumn().$i);
				}
				$sheet->getStyle('A'.$title_row_start.':' . $sheet->getHighestDataColumn().$sheet->getHighestDataRow())->applyFromArray($styleBorder4);
				# Dua cac thong tin tieu de
				$sheet->mergeCells('E1:' . $sheet->getHighestDataColumn().'1');
				$sheet->mergeCells('E2:' . $sheet->getHighestDataColumn().'2');
				$sheet->mergeCells('A4:' . $sheet->getHighestDataColumn().'4');
				$sheet->setCellValue('A4', $title);
				$sheet->setCellValue('A6', 'Lớp: ' . $class_info->class_name);
				$sheet->mergeCells('E6:' . $sheet->getHighestDataColumn().'6');
				$sheet->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->setCellValue('E6', 'Chuyên ngành: ' . $major_name);

				# Dua thong tin ngay va nguoi ky
				$sign_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
				$sign_row = $sheet->getHighestDataRow() + 2;
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row, $sign_col - 1, $sign_row);
				$date_vn = "ngày ".$dt->format('d')." tháng ".$dt->format('m')." năm ".$dt->format('Y');
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row, "Thái Nguyên, ".$date_vn);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getFont()->setItalic(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sheet->mergeCellsByColumnAndRow($sign_col - 8, $sign_row + 1, $sign_col - 1, $sign_row + 1);
				$sheet->setCellValueByColumnAndRow($sign_col - 8, $sign_row + 1, 'PHÒNG ĐÀO TẠO');
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getFont()->setBold(true);
				$sheet->getStyleByColumnAndRow($sign_col - 8, $sign_row + 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				# Tang sheetindex de su dung cho chuyen nganh sau
				$sheetindex++;
			}

			$objPHPExcel->setActiveSheetIndexByName('layout');
			$objPHPExcel->removeSheetByIndex(0);

			$outputFileType = 'Excel5';
			$outputFileName = 'BangDiem-TheoKy.xls';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
			$objWriter->save('php://output');
		}
		break;
	case 'personal':
		$signer = isset($_POST['signer']) ? trim($_POST['signer']) : 1;
		# In bảng điểm cá nhân
		$students = isset($_POST['students']) ? $_POST['students'] : null;
		if ($students != null){
			# Doc file excel template
			$inputFileName = 'xlstemplate/marktable.xls';
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
			$sheetindex = 1;
			# In tung hoc vien
			foreach ($students as $student) {
				# Lay thong tin hoc vien
				$prefix = DB_TABLE_PREFIX;
				$table1 = $prefix.'profile';
				$table2 = $prefix.'student';
				$table3 = $prefix.'course';
				$table4 = $prefix.'group_field';
				$table5 = $prefix.'major';
				$table6 = $prefix.'class';
				$table7 = $prefix.'provice';
				$query = "SELECT CONCAT(p.last_name, ' ', p.first_name) AS fullname, p.birthday, p.sex, g.group_field_name, m.major_name, m.major_name_en, m.major_code, pr.name provice_name, c.period, s.student_code, c.id id_course, m.id id_major FROM {$table1} p, {$table2} s, {$table3} c, {$table4} g, {$table5} m, {$table6} cl, {$table7} pr WHERE p.id = s.id_profile AND s.id_class = cl.id AND s.id_major = m.id AND cl.id_course = c.id AND m.id_group_field = g.id AND p.birth_place = pr.id AND s.id = :id_student";
				$info = $db->query($query, array(':id_student' => $student), PDO::FETCH_OBJ);
				# Tao sheet moi - Clone sheet layout
				$newSheet = clone $objPHPExcel->getSheetByName('layout');
				$newSheet->setTitle($info->student_code);
				$objPHPExcel->addSheet($newSheet, $sheetindex);
				$objPHPExcel->setActiveSheetIndex($sheetindex);
				$sheet = $objPHPExcel->getActiveSheet();
				# Fill thong tin co ban cua hoc vien - Tieng Viet
				$sheet->setCellValue('C6', $info->fullname);
				$sheet->setCellValue('G6', ($info->sex == 1 ? 'Nam' : 'Nữ'));
				$sheet->setCellValue('C7', $info->birthday);
				$sheet->setCellValue('G7', $info->provice_name);
				$sheet->setCellValue('C8', $info->student_code);
				$sheet->setCellValue('C9', $info->major_name);
				$sheet->setCellValue('G9', str_replace('_', '-', $info->period));
				# Fill thong tin co ban cua hoc vien - English
				$sheet->setCellValue('L6', convertVi($info->fullname));
				$sheet->setCellValue('P6', ($info->sex == 1 ? 'Male' : 'Female'));
				$sheet->setCellValue('L7', $info->birthday);
				$sheet->setCellValue('P7', convertVi($info->provice_name));
				$sheet->setCellValue('L8', $info->student_code);
				$sheet->setCellValue('L9', $info->major_name_en);
				$sheet->setCellValue('P9', str_replace('_', '-', $info->period));
				# Lay chi tiet cac mon hoc de dua vao bang
				$_table1 = $prefix.'training_plan';
				$_table2 = $prefix.'class_subject';
				$_table3 = $prefix.'class_subject_details';
				$_table4 = $prefix.'subject';
				$_query = "SELECT s.name AS subject_name, s.name_en, tp.curriculum, tp.caculate, MAX(csd.mark_sumary) AS subject_mark FROM {$_table1} tp, {$_table2} cs, {$_table3} csd, {$_table4} s WHERE csd.id_class_subject = cs.id AND s.id = cs.id_subject AND cs.id_subject = tp.id_subject AND csd.id_student = :id_student AND tp.id_course = :id_course AND tp.id_major = :id_major GROUP BY s.id";
				$subjects = $db->queryAll($_query, array(':id_student' => $student, ':id_course' => $info->id_course, ':id_major' => $info->id_major), PDO::FETCH_OBJ);
				$start_row = 13;
				$i = 1;
				$total_curriculum = 0;
				$total_mark_4 = 0;
				$total_mark_10 = 0;
				$total_insert_rows = count($subjects) - 2;
				$sheet->insertNewRowBefore($start_row + 1, $total_insert_rows);
				$last_row = $start_row + $total_insert_rows;
				foreach ($subjects as $key => $value) {
					$active_row = $start_row + $key;
					$stt = $i + $key;
					# Bang diem tieng Viet
					$sheet->mergeCells("B{$active_row}:E{$active_row}");
					$sheet->setCellValue('A'.$active_row, $stt);
					$sheet->setCellValue('B'.$active_row, $value->subject_name);
					$sheet->setCellValue('F'.$active_row, $value->curriculum);
					$sheet->setCellValue('G'.$active_row, $value->subject_mark);
					$mark_word = ConvertMark($value->subject_mark);
					$sheet->setCellValue('H'.$active_row, $mark_4[$mark_word]);
					$sheet->setCellValue('I'.$active_row, $mark_word);
					if ($active_row <= $last_row)
						$sheet->duplicateStyleArray($styleBorder2, "A{$active_row}:I{$active_row}");
					# Bang diem tieng Anh
					$sheet->mergeCells("K{$active_row}:N{$active_row}");
					$sheet->setCellValue('J'.$active_row, $stt);
					$sheet->setCellValue('K'.$active_row, $value->name_en);
					$sheet->setCellValue('O'.$active_row, $value->curriculum);
					$sheet->setCellValue('P'.$active_row, $value->subject_mark);
					$mark_word = ConvertMark($value->subject_mark);
					$sheet->setCellValue('Q'.$active_row, $mark_4[$mark_word]);
					$sheet->setCellValue('R'.$active_row, $mark_word);
					if ($active_row <= $last_row)
						$sheet->duplicateStyleArray($styleBorder2, "J{$active_row}:R{$active_row}");
					# Tinh diem trung binh
					if ($value->caculate == 1) {
						$total_curriculum = $total_curriculum + $value->curriculum;
						$total_mark_10 = $total_mark_10 + $value->subject_mark*$value->curriculum;
						$total_mark_4 = $total_mark_4 + $mark_4[$mark_word]*$value->curriculum;
					}
				}
				$bottom_row = $start_row + $total_insert_rows + 3;
				# Vietnamese
				$sheet->setCellValue('D'. $bottom_row, ROUND($total_mark_10 / $total_curriculum, 2));
				$sheet->setCellValue('D'. ($bottom_row + 1), ROUND($total_mark_4 / $total_curriculum, 2));
				# English
				$sheet->setCellValue('M'. $bottom_row, ROUND($total_mark_10 / $total_curriculum, 2));
				$sheet->setCellValue('M'. ($bottom_row + 1), ROUND($total_mark_4 / $total_curriculum, 2));
				# Lay thong tin luan van
				$essay = $db->getRow('essay', '*', array('id_student' => $student), PDO::FETCH_OBJ);
				if ($essay){
					# Fill thong tin ngay bao ve
					# Vietnamese
					$sheet->setCellValue('D'. ($bottom_row + 2), $essay->date_protect);
					# English
					$essay_date = $dt->createFromFormat("d/m/Y", $essay->date_protect);
					$sheet->setCellValue('M'. ($bottom_row + 2), $essay_date->format("M jS, Y"));
					# Fill thong tin hoi dong
					# Vietnamese
					$sheet->setCellValue('G'. ($bottom_row + 2), $essay->mark);
					$sheet->setCellValue('C'. ($bottom_row + 3), $essay->vn_name);
					$sheet->setCellValue('C'. ($bottom_row + 6), $essay->chairman);
					$sheet->setCellValue('C'. ($bottom_row + 7), $essay->secretary);
					$sheet->setCellValue('C'. ($bottom_row + 8), $essay->critic_1);
					$sheet->setCellValue('C'. ($bottom_row + 9), $essay->critic_2);
					$sheet->setCellValue('C'. ($bottom_row + 10), $essay->member);
					# English
					$sheet->setCellValue('P'. ($bottom_row + 2), $essay->mark);
					$sheet->setCellValue('L'. ($bottom_row + 3), $essay->en_name);
					$sheet->setCellValue('L'. ($bottom_row + 6), convertAssessment($essay->chairman));
					$sheet->setCellValue('L'. ($bottom_row + 7), convertAssessment($essay->secretary));
					$sheet->setCellValue('L'. ($bottom_row + 8), convertAssessment($essay->critic_1));
					$sheet->setCellValue('L'. ($bottom_row + 9), convertAssessment($essay->critic_2));
					$sheet->setCellValue('L'. ($bottom_row + 10), convertAssessment($essay->member));
				}

				# Fill thong tin ngay cap bang
				$date_vn = "ngày ".$dt->format('d')." tháng ".$dt->format('m')." năm ".$dt->format('Y');
				$sheet->setCellValue('F'. ($bottom_row + 12), "Thái Nguyên, ".$date_vn);
				$date_en = $dt->format("F jS, Y");
				$sheet->setCellValue('O'. ($bottom_row + 12), "Thai Nguyen, ".$date_en);

				# Fill thong tin nguoi ky
				switch ($signer) {
					case 2:
						$sheet->setCellValue('F'. ($bottom_row + 13), 'KT. HIỆU TRƯỞNG');
						$sheet->setCellValue('F'. ($bottom_row + 14), 'PHÓ HIỆU TRƯỞNG');
						$sheet->setCellValue('F'. ($bottom_row + 19), 'PGT.TS. Lê Thị Thanh Nhàn');
						$sheet->setCellValue('O'. ($bottom_row + 13), 'VICE DIRECTOR');
						$sheet->setCellValue('O'. ($bottom_row + 18), convertAssessment('PGT.TS. Lê Thị Thanh Nhàn'));
						break;
					case 3:
						$sheet->setCellValue('F'. ($bottom_row + 13), 'HIỆU TRƯỞNG');
						$sheet->setCellValue('F'. ($bottom_row + 18), 'PGT.TS. Nông Quốc Chinh');
						$sheet->setCellValue('O'. ($bottom_row + 13), 'DIRECTOR');
						$sheet->setCellValue('O'. ($bottom_row + 18), convertAssessment('PGT.TS. Nông Quốc Chinh'));
						break;
					default:
						$sheet->setCellValue('F'. ($bottom_row + 13), 'TL. HIỆU TRƯỞNG');
						$sheet->setCellValue('F'. ($bottom_row + 14), 'TRƯỞNG PHÒNG ĐÀO TẠO');
						$sheet->setCellValue('F'. ($bottom_row + 19), 'PGS.TS. Trịnh Thanh Hải');
						$sheet->setCellValue('O'. ($bottom_row + 13), 'HEAD OF DEPT.TRAINNING');
						$sheet->setCellValue('O'. ($bottom_row + 18), convertAssessment('PGT.TS. Trịnh Thanh Hải'));
						break;
				}

				# Sau khi fill thong tin - Tang sheet index de dung cho hoc vien sau
				$sheetindex++;
			}
			$objPHPExcel->setActiveSheetIndexByName('layout');
			$objPHPExcel->removeSheetByIndex(0);

			$outputFileType = 'Excel5';
			$outputFileName = 'BangDiem-Canhan.xls';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
			$objWriter->save('php://output');
		}
		break;
	default:

		break;
}

function getRowCount($text, $width = 21){
	$rc = 0;
	$line = explode("\n", $text);
	foreach ($line as $source) {
		$rc += intval((strlen($source) / $width) + 1);
	}
	return $rc;
}

function ConvertMark($mark){
	if ($mark > 8.5)
		return 'A';
	else if ($mark > 7)
		return 'B';
	else if ($mark > 5.5)
		return 'C';
	else if ($mark > 4)
		return 'D';
	else
		return 'F';
}

function convertVi($str){
	$vn = array(
		'à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ằ', 'ắ', 'ẳ', 'ẵ', 'ặ', 'â', 'ầ', 'ấ', 'ẩ', 'ẫ', 'ậ',
		'đ',
		'è', 'é', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ề', 'ế', 'ể', 'ễ', 'ệ',
		'ì', 'í', 'ỉ', 'ĩ', 'ị',
		'ò', 'ó', 'ỏ', 'õ', 'ọ', 'ô', 'ồ', 'ố', 'ổ', 'ỗ', 'ộ', 'ơ', 'ờ', 'ớ', 'ở', 'ỡ', 'ợ',
		'ù', 'ú', 'ủ', 'ũ', 'ụ', 'ư', 'ừ', 'ứ', 'ử', 'ữ', 'ự',
		'ỳ', 'ý', 'ỷ', 'ỹ', 'ỵ',
		'À', 'Á', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ằ', 'Ắ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ầ', 'Ấ', 'Ẩ', 'Ẫ', 'Ậ',
		'Đ',
		'È', 'É', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ề', 'Ế', 'Ể', 'Ễ', 'Ệ',
		'Ì', 'Í', 'Ỉ', 'Ĩ', 'Ị',
		'Ò', 'Ó', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ồ', 'Ố', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ờ', 'Ớ', 'Ở', 'Ỡ', 'Ợ',
		'Ù', 'Ú', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ừ', 'Ứ', 'Ử', 'Ữ', 'Ự',
		'Ỳ', 'Ý', 'Ỷ', 'Ỹ', 'Ỵ',
	);
	$en = array(
		'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
		'd',
		'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
		'i', 'i', 'i', 'i', 'i',
		'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
		'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
		'y', 'y', 'y', 'y', 'y',
		'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
		'D',
		'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
		'I', 'I', 'I', 'I', 'I',
		'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
		'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
		'Y', 'Y', 'Y', 'Y', 'Y',
	);
	$str = str_replace($vn, $en, trim($str));
	$str = preg_replace('/\s+/', ' ', $str);

	return $str;
}

function convertAssessment($string){
	$string = convertVi($string);
	$_vn = array('PGS', 'GS', 'TSKH', 'TS');
	$_en = array('Assoc.Prof', 'Prof', 'Dr.Sc', 'Dr');
	$string = str_replace($_vn, $_en, $string);
	return $string;
}

function reSort($array, $field_sort){
    $tmp_data = array();
    foreach ($array as $item) {
        $key = '';
        if (is_array($field_sort)) {
            foreach ($field_sort as $_item) {
                $key .= $item->$_item;
            }
        }
        else
            $key = $item->$field_sort;

        $key = uConvert($key);
        $tmp_data[$key] = $item;
    }
    ksort($tmp_data);
    return array_values($tmp_data);
}

function uConvert($input){
    $maps = array(
        'À' => 'Az1', 'Á' => 'Az2', 'Ả' => 'Az3', 'Ã' => 'Az4', 'Ạ' => 'Az5',
        'Ă' => 'Azz0', 'Ằ' => 'Azz1', 'Ắ' => 'Azz2', 'Ẳ' => 'Azz3', 'Ẵ' => 'Azz4', 'Ặ' => 'Azz5',
        'Â' => 'Azzz0', 'Ầ' => 'Azzz1', 'Ấ' => 'Azzz2', 'Ẩ' => 'Azzz3', 'Ẫ' => 'Azzz4', 'Ậ' => 'Azzz5',
        'à' => 'az1', 'á' => 'az2', 'ả' => 'az3', 'ã' => 'az4', 'ạ' => 'az5',
        'ă' => 'az0', 'ằ' => 'az1', 'ắ' => 'az2', 'ẳ' => 'az3', 'ẵ' => 'az4', 'ặ' => 'az5',
        'â' => 'azz0', 'ầ' => 'azz1', 'ấ' => 'azz2', 'ẩ' => 'azz3', 'ẫ' => 'azz4', 'ậ' => 'azz5',
        'Đ' => 'Dz1',
        'đ' => 'dz1',
        'È' => 'Ez1', 'É' => 'Ez2', 'Ẻ' => 'Ez3', 'Ẽ' => 'Ez4', 'Ẹ' => 'Ez5',
        'Ê' => 'Ezz0', 'Ề' => 'Ezz1', 'Ế' => 'Ezz2', 'Ể' => 'Ezz3', 'Ễ' => 'Ezz4', 'Ệ' => 'Ezz5',
        'è' => 'ez1', 'é' => 'ez2', 'ẻ' => 'ez3', 'ẽ' => 'ez4', 'ẹ' => 'ez5',
        'ê' => 'ezz0', 'ề' => 'ezz1', 'ế' => 'ezz2', 'ể' => 'ezz3', 'ễ' => 'ezz4', 'ệ' => 'ezz5',
        'Ò' => 'Oz1', 'Ó' => 'Oz2', 'Ỏ' => 'Oz3', 'Õ' => 'Oz4', 'Ọ' => 'Oz5',
        'Ô' => 'Ozz0', 'Ồ' => 'Ozz1', 'Ố' => 'Ozz2', 'Ổ' => 'Ozz3', 'Ỗ' => 'Ozz4', 'Ộ' => 'Ozz5',
        'Ơ' => 'Ozzz0', 'Ờ' => 'Ozzz1', 'Ớ' => 'Ozzz2', 'Ở' => 'Ozzz1', 'Ỡ' => 'Ozzz4', 'Ợ' => 'Ozzz5',
        'ò' => 'oz1', 'ó' => 'oz2', 'ỏ' => 'oz3', 'õ' => 'oz4', 'ọ' => 'oz5',
        'ô' => 'ozz0', 'ồ' => 'ozz1', 'ố' => 'ozz2', 'ổ' => 'ozz3', 'ỗ' => 'ozz4', 'ộ' => 'ozz5',
        'ơ' => 'ozzz0', 'ờ' => 'ozzz1', 'ớ' => 'ozzz2', 'ở' => 'ozzz1', 'ỡ' => 'ozzz4', 'ợ' => 'ozzz5',
        'Ù' => 'Uz1', 'Ú' => 'Uz2', 'Ủ' => 'Uz3', 'Ũ' => 'Uz4', 'Ụ' => 'Uz5',
        'Ư' => 'Uzz0', 'Ừ' => 'Uzz1', 'Ứ' => 'Uzz2', 'Ử' => 'Uzz3', 'Ữ' => 'Uzz4', 'Ự' => 'Uzz5',
        'ù' => 'uz1', 'ú' => 'uz2', 'ủ' => 'uz3', 'ũ' => 'uz4', 'ụ' => 'uz5',
        'ư' => 'uzz0', 'ừ' => 'uzz1', 'ứ' => 'uzz2', 'ử' => 'uzz3', 'ữ' => 'uzz4', 'ự' => 'uzz5'
    );

    $keys = array_keys($maps);
    $vals = array_values($maps);
    $output = str_replace($keys, $vals, $input);
    return $output;
}
?>