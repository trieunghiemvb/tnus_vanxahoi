<?php
define('AREA','A');
require '../../../init.php';
require_once ('../classfile.php');
include('../../../libs/phpexcel/PHPExcel.php');

$id_class = ( isset($_GET['class']) && is_numeric($_GET['class']) ) ? intval($_GET['class']) : 0;

if($id_class != 0){
	$inputFileType = 'Excel5';
	$inputFileName = 'xlstemplate/listStudent.xls';
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);

	// Get Class info
	$classInfo = ClassfileApp::getClassInfo($id_class);

	// Get list student by Class id
	$list_students = ClassfileApp::getStudenByClass($id_class);
	$provices = ClassfileApp::getListProvice();
	// Resort list with vietnamese alphabel
	$tmp_array = array();
	foreach ($list_students as $item) {
		$key = uConvert($item->first_name . ' ' . $item->last_name);
		$tmp_array[$key] = $item;
	}
	ksort($tmp_array);
	$return = array_values($tmp_array);

	// Add data to the template
	$sheet = $objPHPExcel->getActiveSheet();
	$sheet->setCellValue('B6', $classInfo->class_name);
	$sheet->setCellValue('G6', $classInfo->period);
	$sheet->setCellValue('B7', $classInfo->course_name);

	// Dump data
	if (count($return) > 1) {
		$sheet->insertNewRowBefore(11, count($return) - 1);
	}
	$startRow = 10;
	for ($i=0; $i < count($return); $i++) {
		$stt = $i+1;
		$activeRow = $startRow + $i;
		$item = $return[$i];
		$sheet->setCellValue('A' . $activeRow, $stt);
		$sheet->setCellValue('B' . $activeRow, mb_strtoupper($item->student_code, 'UTF-8'));
		$sheet->setCellValue('C' . $activeRow, mb_strtoupper($item->last_name, 'UTF-8'));
		$sheet->setCellValue('D' . $activeRow, mb_strtoupper($item->first_name, 'UTF-8'));
		$sheet->setCellValue('E' . $activeRow, $item->birthday);
		$sheet->setCellValue('F' . $activeRow, $provices[$item->birth_place]);
		$sheet->setCellValue('G' . $activeRow, '')->setCellValue('H' . $activeRow, '');
		if ($item->sex == 1)
			$sheet->setCellValue('G' . $activeRow, 'Nam');
		else
			$sheet->setCellValue('H' . $activeRow, 'Nữ');
	}

	// Addition Info
	$sheet->setCellValue('A' . ($startRow + count($return) + 1), 'Tổng số học viên: ' . count($return));
	$time = time();
	$sheet->setCellValue('F' . ($startRow + count($return) + 1), 'Ngày ' . date('d', $time) . ' tháng ' . date('m', $time) . ' năm ' . date('Y', $time));

	// Output file
	$outputFileType = 'Excel5';
	$outputFileName = 'DanhSachSV-'.$classInfo->class_code.'.xls';
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
	$objWriter->save('php://output');
}
else{
	echo 'Error!';
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