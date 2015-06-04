<?php
define('AREA','A');
require '../../../init.php';
require_once ('../exam_beat.php');
include('../../../libs/phpexcel/PHPExcel.php');

$act = isset($_GET['act']) ? trim($_GET['act']) : "";
$id_exam = isset($_GET['exam']) ? intval($_GET['exam']) : 0;
$id_subject = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$db = new Database;

// Get data
$examination = $db->getRow('examination', '*', array('id' => $id_exam), PDO::FETCH_OBJ);
$id_course = $examination->id_course;
$term = $examination->term;
$exam_name = $examination->name;
$subject = $db->getRow('subject', '*', array('id' => $id_subject), PDO::FETCH_OBJ);
$subject_name = $subject->name ;
$course = $db->getRow('course', '*', array('id' => $id_course), PDO::FETCH_OBJ);
list($start_year, $end_year) = explode('_', $course->period);
$year = array();
while ($start_year + 1 <= $end_year){
	$next_year = $start_year + 1;
	$year[] = "{$start_year}_{$next_year}";
	$start_year++;
}
$study_year = $year[ceil($term / 2)-1];
$exam_list = $db->getRow('exam_list', '*', array('id_exam' => $examination->id, 'id_subject' => $id_subject), PDO::FETCH_OBJ);

# Get list student
$prefix = DB_TABLE_PREFIX;
$table1 = $prefix.'profile';
$table2 = $prefix.'student';
$table3 = $prefix.'exam_list_details';
$query = "SELECT p.first_name, p.last_name, s.student_code, ed.beat, ed.bag FROM {$table1} p, {$table2} s, {$table3} ed WHERE p.id = s.id_profile AND s.id = ed.id_student AND ed.id_exam_list = :id_exam_list AND ed.status = :status";
$result = $db->queryAll($query, array(':id_exam_list' => $exam_list->id, ':status' => 1), PDO::FETCH_OBJ);
$result = reSort($result, array('first_name', 'last_name'));

$inputFileType = 'Excel5';
switch ($act) {
	case 'beatform':
		# In bieu danh phach
		$inputFileName = 'xlstemplate/beatform.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
        $bag_name = '';
		// Add data to the template
		$sheet = $objPHPExcel->setActiveSheetIndexByName('layout');
		$sheet->setCellValue('C4', $exam_name);
		$sheet->setCellValue('C5', $subject_name);
		$sheet->setCellValue('C6', $study_year);
		$sheet->setCellValue('H6', $term);
		$sheet->setCellValue('E43', 'Thái Nguyên, ngày '.date('d').' tháng '.date('m').' năm '.date('Y'));

		// Dump data
		$index = 0;
		$col_stt = 'A';
		$col_sbd = 'B';
		$col_beat = 'C';
		$counter = 0;
		foreach ($result as $item) {
			if ($item->bag != $bag_name){
				$bag_name = $item->bag;
				$sheet_title = str_replace('/', '-', $bag_name);
				$newSheet = clone $objPHPExcel->getSheetByName('layout');
				$newSheet->setTitle($sheet_title);
				$index++;
				$objPHPExcel->addSheet($newSheet,$index);
				$sheet = $objPHPExcel->setActiveSheetIndexByName($sheet_title);
				$sheet->setCellValue('C7', $bag_name);
				$range = $db->getRow('exam_list_details', 'MAX(beat) AS max, MIN(beat) AS min', array('id_exam_list' => $exam_list->id, 'bag' => $bag_name), PDO::FETCH_OBJ);
				$sheet->setCellValue('C8', $range->min . '-' . $range->max);

				$stt = 0;
				$row = 10;
				$counter = 0;
			}
			$stt++;
			$row++;
			$counter++;
			if ($stt == 31){
				$row = 11;
				$col_stt = 'F';
				$col_sbd = 'G';
				$col_beat = 'H';
			}
			$sheet->setCellValue($col_stt.$row, $stt);
			$sheet->setCellValue($col_sbd.$row, $item->student_code);
			$sheet->setCellValue($col_beat.$row, $item->beat);
			$objRichText = new PHPExcel_RichText();
			$objRichText->createText('Số bài: ');
			$objBold = $objRichText->createTextRun($counter);
			$objBold->getFont()->setBold(true);
			$sheet->getCell('A43')->setValue($objRichText);
		}

		$objPHPExcel->setActiveSheetIndexByName('layout');
		$objPHPExcel->removeSheetByIndex(0);

		// Output file
		$outputFileType = 'Excel5';
		$outputFileName = 'HuongDanDanhPhach-'.$subject->code.'-'.$course->course_code.'.xls';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
		$objWriter->save('php://output');
		break;
	case 'label':
		# In nhan dan tui
		$inputFileName = 'xlstemplate/label.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
        $data = $db->queryAll("SELECT MIN(beat) AS minbeat, MAX(beat) as maxbeat, bag, count(beat) as total FROM {$table3} WHERE id_exam_list = :id_exam_list AND beat <> '' GROUP BY bag", array(':id_exam_list' => $exam_list->id), PDO::FETCH_OBJ);
        # Get curriculum and perect mark element from training_plan
		$training_plan = $db->getRow('training_plan', '*', array('id_course' => $id_course, 'term' => $term, 'id_subject' => $id_subject), PDO::FETCH_OBJ);
		// Add data to the template
		$sheet = $objPHPExcel->setActiveSheetIndexByName('layout');
		$sheet->setTitle($subject->code . '-' . $course->course_code);
		$sheet->setCellValue('A4', 'ĐỢT THI: ' . $exam_name);
		$sheet->setCellValue('A5', 'Học kỳ: ' . $term . ' - Năm học: ' . $study_year);
		$sheet->setCellValue('A6', 'Học phần: ' . $subject_name . ' ('.$subject->code.') _ ' .$training_plan->curriculum. ' tín chỉ');
		$sheet->setCellValue('A7', count($data) . ' túi bài chấm / ' . count($result) . ' bài thi');
		# Dump data
		$_row = 8;
		$styleArray = array(
				'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK)),
				'font' => array('bold' => true)
			);
		for ($i=0; $i < count($data); $i++) {
			$item = $data[$i];
			$row = $_row + ($i - ($i % 2) + 1);
			if ($i % 2 == 0)
				$col = 'A';
			else
				$col = 'C';
			$content = "Mã túi bài chấm: {$item->bag}\nSố bài thi: {$item->total}\nDải phách: {$item->minbeat} - {$item->maxbeat}\nQuy tắc đánh phách: Sinh phách ngẫu nhiên";
			$sheet->getStyle($col.$row, $content)->getAlignment()->setWrapText(true);
			$sheet->getStyle($col.$row, $content)->applyFromArray($styleArray);
			$sheet->getStyle($col.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setIndent(1);
			$sheet->setCellValue($col.$row, $content);
			$sheet->getRowDimension($row)->setRowHeight(80);
		}

		$sheet->setCellValue('C'.($row + 2), 'Thái Nguyên, ngày '.date('d').' tháng '.date('m').' năm '.date('Y'));
		$sheet->getStyle('C'.($row + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// Output file
		$outputFileType = 'Excel5';
		$outputFileName = 'Label-'.$subject->code.'-'.$course->course_code.'.xls';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
		$objWriter->save('php://output');
		break;
	case 'markform':
		# In bieu vao diem
		$inputFileName = 'xlstemplate/markform.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		# Get curriculum and perect mark element from training_plan
		$training_plan = $db->getRow('training_plan', '*', array('id_course' => $id_course, 'term' => $term, 'id_subject' => $id_subject), PDO::FETCH_OBJ);
		list($mark_element, $mark_exam) = explode('/', $training_plan->mark_formula);
		# Fill data to template
		$sheet = $objPHPExcel->setActiveSheetIndexByName('layout');
		$sheet->setCellValue('G3', 'Đợt thi: ' . $exam_name);
		$sheet->setCellValue('A5', 'Học kỳ: ' . $term . ' - Năm học: ' . $study_year);
		$sheet->setCellValue('B6', $subject_name);
		$sheet->setCellValue('C7', $training_plan->curriculum);
		$sheet->setCellValue('I7', $mark_element.'%');

		// Dump data
		$tmp_array = array();
		foreach ($result as $item) {
			$tmp_array[$item->bag][] = $item->beat;
		}
		ksort($tmp_array);
		$index = 0;
		foreach ($tmp_array as $key => $value) {
			$bag_name = $key;
			$sheet_title = str_replace('/', '-', $bag_name);
			$newSheet = clone $objPHPExcel->getSheetByName('layout');
			$newSheet->setTitle($sheet_title);
			$index++;
			$objPHPExcel->addSheet($newSheet,$index);
			$sheet = $objPHPExcel->setActiveSheetIndexByName($sheet_title);
			$sheet->setCellValue('A4', 'PHIẾU CHẤM THI TÚI: ' . $bag_name);

			$stt = 0;
			$row = 10;
			$col_stt = 'A';
			$col_beat = 'B';
			sort($value);
			for ($i=0; $i < count($value); $i++) {
				$stt++;
				$row++;
				if ($stt == 31){
					$row = 11;
					$col_stt = 'G';
					$col_beat = 'H';

				}
				$sheet->setCellValue($col_stt.$row, $stt);
				$sheet->setCellValue($col_beat.$row, $value[$i]);
			}
		}

		$objPHPExcel->setActiveSheetIndexByName('layout');
		$objPHPExcel->removeSheetByIndex(0);

		// Output file
		$outputFileType = 'Excel5';
		$outputFileName = 'BieuVaoDiem-'.$subject->code.'-'.$course->course_code.'.xls';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
		$objWriter->save('php://output');
		break;
	default:
		echo '<pre>';
		// Dump data
		$data = $db->queryAll("SELECT MIN(beat) AS minbeat, MAX(beat) as maxbeat, bag, count(beat) as total FROM {$table3} WHERE id_exam_list = :id_exam_list AND beat <> '' GROUP BY bag", array(':id_exam_list' => 1));
		print_r($data);
		echo '</pre>';
		break;
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