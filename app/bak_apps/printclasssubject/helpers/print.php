<?php
set_time_limit(0);
define('AREA','A');
require '../../../init.php';
require_once ('../printclasssubject.php');
include('../../../libs/phpexcel/PHPExcel.php');

$template = isset($_POST['template']) ? trim($_POST['template']) : 'ds_ghidiem';

$classes = isset($_POST['class']) ? $_POST['class'] : null;
if ($classes) {
	# Global variables
	$db = new Database;
	$prefix = DB_TABLE_PREFIX;
	$dt = new DateTime;
	$cs = $prefix.'class_subject';
	$csd = $prefix.'class_subject_details';
	$st = $prefix.'student';
	$pr = $prefix.'profile';
	$su = $prefix.'subject';
	# Excel Variables
	$styleBorder = array(
		'borders' => array(
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
	$inputFileType = 'Excel5';
	$inputFileName = 'xlstemplate/'.$template.'.xls';
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);
	$sheetindex = 1;
	foreach ($classes as $id_class) {
		# Lay thong tin lop, mon hoc, ky hoc
		$query = "SELECT su.code AS subject_code, su.name AS subject_name, cs.name AS class_name, cs.year, cs.term FROM {$cs} cs, {$su} su WHERE cs.id_subject = su.id AND cs.id = :id_class_subject";
		$class = $db->query($query, array(':id_class_subject' => $id_class), PDO::FETCH_OBJ);
		# Tao sheet moi - Clone sheet layout
		$sheetTitle = str_replace($class->subject_name, $class->subject_code, $class->class_name);
		$newSheet = clone $objPHPExcel->getSheetByName('layout');
		$newSheet->setTitle($sheetTitle);
		$objPHPExcel->addSheet($newSheet, $sheetindex);
		$objPHPExcel->setActiveSheetIndex($sheetindex);
		$sheet = $objPHPExcel->getActiveSheet();
		# Dua thong tin mon hoc vao cac o tuong ung
		$sheet->setCellValue('C5', $class->subject_name . ' ('.$class->subject_code.')');
		$sheet->setCellValue('C6', $class->class_name);
		$sheet->setCellValue('C7', $class->year);
		$sheet->setCellValue('E7', $class->term);
		if ($template == 'ds_ghidiem'){
			$start_row = 14;
			$date_cell = 'D18';
			$count_cell = 'C16';
			$file_name = 'DanhSach_GhiDiem';
		} else if ($template == 'ds_diemdanh'){
			$start_row = 11;
			$date_cell = 'D15';
			$count_cell = 'C13';
			$file_name = 'DanhSach_DiemDanh';
		}
		# Fill thong tin ngay/thang
		$print_date = "Thái Nguyên, ngày ".$dt->format('d').' tháng '.$dt->format('m').' năm '.$dt->format('Y');
		$sheet->setCellValue($date_cell, $print_date);
		# Lay danh sach hoc vien trong lop
		$query = "SELECT pr.first_name, pr.last_name, pr.birthday, st.student_code FROM {$pr} pr, {$st} st, {$csd} csd WHERE pr.id = st.id_profile AND st.id = csd.id_student AND csd.id_class_subject = :id_class_subject";
		$students = $db->queryAll($query, array(':id_class_subject' => $id_class), PDO::FETCH_OBJ);
		$students = reSort($students, array('first_name', 'last_name'));
		# Dua thong tin hoc vien vao danh sach
		$sheet->setCellValue($count_cell, sprintf("%02d", count($students)) . ' học viên');
		$stt = 1;
		$i = 0;
		# Chen du dong de dien thong tin hoc vien
		$sheet->insertNewRowBefore($start_row + 1, count($students) - 2);
		foreach ($students as $student) {
			$sheet->setCellValue('A'.($start_row + $i), $stt);
			$sheet->setCellValue('B'.($start_row + $i), $student->student_code);
			$sheet->setCellValue('C'.($start_row + $i), $student->last_name . ' ' . $student->first_name);
			$sheet->setCellValue('D'.($start_row + $i), $student->birthday);
			$sheet->getStyle('A'.($start_row + $i).':'.$sheet->getHighestDataColumn().($start_row + $i))->applyFromArray($styleBorder);
			$i++; $stt++;
		}
		# Tang sheetindex, su dung cho lop sau
		$sheetindex++;
	}

	$objPHPExcel->setActiveSheetIndexByName('layout');
	$objPHPExcel->removeSheetByIndex(0);

	# Xuat file excel
	$outputFileType = 'Excel5';
	$outputFileName = $file_name.'.xls';
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
	$objWriter->save('php://output');
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