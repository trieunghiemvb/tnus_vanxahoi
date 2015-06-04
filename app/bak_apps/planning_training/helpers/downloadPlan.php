<?php

define('AREA', 'A');
require '../../../init.php';
require_once ('../planning_training.php');
include('../../../libs/phpexcel/PHPExcel.php');

$id_course = ( isset($_GET['course']) && is_numeric($_GET['course']) ) ? intval($_GET['course']) : 0;
$id_major = ( isset($_GET['major']) && is_numeric($_GET['major']) ) ? intval($_GET['major']) : 0;

if ($id_course != 0 && $id_major != 0) {
    $inputFileType = 'Excel5';
    $inputFileName = 'xlstemplate/traningplan_temp.xls';
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);

    // Set Course Infor
    $course = Planning_trainingApp::get_select_table('course', 'id', 'course_name');
    $course_name = $course[$id_course];
    $major = Planning_trainingApp::get_select_table('major', 'id', 'major_name');
    $major_name = $major[$id_major];
    $arr_year = Planning_trainingApp::get_select_year($id_course);
    $period = $arr_year[0] . "_" . $arr_year[1];

    // Get plan
    $arr_term1 = Planning_trainingApp::get_training_plan($id_course, $id_major, 1);
    //print_r($arr_term1);
    $arr_term2 = Planning_trainingApp::get_training_plan($id_course, $id_major, 2);
    $arr_term3 = Planning_trainingApp::get_training_plan($id_course, $id_major, 3);
    $arr_term4 = Planning_trainingApp::get_training_plan($id_course, $id_major, 4);

    // Add data to the template
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setCellValue('C6', $course_name);
    $sheet->setCellValue('C7', $major_name);
    $sheet->setCellValue('F6', $period);
//	// Dump data
    $total_cur=0;
    $startRow = 11;
    $endRow=$startRow;
    $insert_row_num=count($arr_term1)+ count($arr_term2)+count($arr_term3)+ count($arr_term4)+1;
    if (count($arr_term1) > 1)		$sheet->insertNewRowBefore($startRow+1,count($arr_term1)-1);
    for ($i = 0; $i < count($arr_term1); $i++) {
        $stt = $i + 1;
        $item = $arr_term1[$i];
        $activeRow = $startRow + $i;
        $sheet->getRowDimension($activeRow)->setRowHeight(-1);
        $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
        $sheet->setCellValue('A' . $activeRow, $stt);
        $sheet->setCellValue('B' . $activeRow, mb_strtoupper($subject['code'], 'UTF-8'));
        $sheet->setCellValue('C' . $activeRow, $subject['name']);
        $sheet->setCellValue('D' . $activeRow, mb_strtoupper($item['curriculum'], 'UTF-8'));
        $sheet->setCellValue('E' . $activeRow, mb_strtoupper($item['mark_formula'], 'UTF-8'));
        $total_cur+=intval($item['curriculum']);
        $endRow++;
    }
    $startRow=$endRow+3;
    $endRow=$startRow;	if (count($arr_term2) > 1)
		$sheet->insertNewRowBefore($startRow+1,count($arr_term2)-1);
    for ($i = 0; $i < count($arr_term2); $i++) {
        $stt = $i + 1;
        $item = $arr_term2[$i];
        $activeRow = $startRow + $i;
        $sheet->getRowDimension($activeRow)->setRowHeight(-1);
        $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
        $sheet->setCellValue('A' . $activeRow, $stt);
        $sheet->setCellValue('B' . $activeRow, mb_strtoupper($subject['code'], 'UTF-8'));
        $sheet->setCellValue('C' . $activeRow, $subject['name']);
        $sheet->setCellValue('D' . $activeRow, mb_strtoupper($item['curriculum'], 'UTF-8'));
        $sheet->setCellValue('E' . $activeRow, mb_strtoupper($item['mark_formula'], 'UTF-8'));
        $total_cur+=intval($item['curriculum']);
        $endRow++;
    }

    $startRow=$endRow+3;
    $endRow=$startRow;	if (count($arr_term3) > 1)
		$sheet->insertNewRowBefore($startRow+1,count($arr_term3)-1);
    for ($i = 0; $i < count($arr_term3); $i++) {
        $stt = $i + 1;
        $item = $arr_term3[$i];
        $activeRow = $startRow + $i;
        $sheet->getRowDimension($activeRow)->setRowHeight(-1);
        $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
        $sheet->setCellValue('A' . $activeRow, $stt);
        $sheet->setCellValue('B' . $activeRow, mb_strtoupper($subject['code'], 'UTF-8'));
        $sheet->setCellValue('C' . $activeRow, $subject['name']);
        $sheet->setCellValue('D' . $activeRow, mb_strtoupper($item['curriculum'], 'UTF-8'));
        $sheet->setCellValue('E' . $activeRow, mb_strtoupper($item['mark_formula'], 'UTF-8'));
        $total_cur+=intval($item['curriculum']);
        $endRow++;
    }
    

    $startRow=$endRow+3;
    $endRow=$startRow;
    $i=0;	if (count($arr_term4) > 1)
		$sheet->insertNewRowBefore($startRow+1,count($arr_term4)-1);
    foreach ($arr_term4 as $item) {
        $activeRow = $startRow + $i;
        $i++;
        $stt = $i;
        $sheet->getRowDimension($activeRow)->setRowHeight(-1);
        $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
        $sheet->setCellValue('A' . $activeRow, $stt);
        $sheet->setCellValue('B' . $activeRow, mb_strtoupper($subject['code'], 'UTF-8'));
        $sheet->setCellValue('C' . $activeRow, $subject['name']);
        $sheet->setCellValue('D' . $activeRow, mb_strtoupper($item['curriculum'], 'UTF-8'));
        $sheet->setCellValue('E' . $activeRow, mb_strtoupper($item['mark_formula'], 'UTF-8'));
        $total_cur+=intval($item['curriculum']);
        $endRow++;
    }
	// Addition Info
	$sheet->setCellValue('A' . ($endRow + 2), 'Tổng số tín chỉ: ' . $total_cur);
	$time = time();
	$sheet->setCellValue('D' . ($endRow + 2), 'Ngày ' . date('d', $time) . ' tháng ' . date('m', $time) . ' năm ' . date('Y', $time));
        $sheet->setCellValue('D' . ($endRow + 3), "Người lập");
    // Output file
    $outputFileType = 'Excel5';
    $outputFileName = 'KeHoachDaoTao-' . $course_name . '-' . $major_name . '.xls';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $outputFileName . '"');
    $objWriter->save('php://output');
} else {
    echo 'Error!';
}

?>