<?php

define('AREA', 'A');
require '../../../init.php';
require_once ('../markcomponent.php');
include('../../../libs/phpexcel/PHPExcel.php');

$id_course = ( isset($_GET['course']) && is_numeric($_GET['course']) ) ? intval($_GET['course']) : 0;
$year = ( isset($_GET['year'])) ? $_GET['year'] : "";
$term = ( isset($_GET['term'])) ? $_GET['term'] : "";
$id_subject = ( isset($_GET['subject']) ) ? $_GET['subject'] : "";
$id_class = ( isset($_GET['class_id']) && is_numeric($_GET['class_id']) ) ? intval($_GET['class_id']) : 0;

if ($id_class != 0) {
    $inputFileType = 'Excel5';
    $inputFileName = 'xlstemplate/markcomponent.xls';
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);

	$course_val = "";
	$year_val = "";
	$term_val = "";
	$subject_val = "";
	$class_val = "";
	$total_student = 0;
	//render data
		$db = new Database;
		$table = 'class_subject';
		$where = array('id' => $id_class);
		
		$class_subject_info = MarkComponentApp::getClassSubjectById($id_class);
		$year_val = str_replace('_', '-', $class_subject_info['year']);
		$term_val = $class_subject_info['term'];
		$class_val = $class_subject_info['name'];
		$subject_val = MarkComponentApp::getSubjectById($class_subject_info['id_subject']);
	//get total student in class
		$result = $db->getValue($table, 'count(*)', $where);
		$total_student = intval($result);		
	//get course value
		$result = MarkComponentApp::getCourseNameById($id_course);
		$course_val = str_replace('_', '-', $result);

    // Add data to the template
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle('BangDiem');
    $sheet->setCellValue('C6', $year_val);	// khóa học
    $sheet->setCellValue('C7', $subject_val);	// chuyên ngành
    $sheet->setCellValue('E6', $term_val);		// kỳ học
    $sheet->setCellValue('E7', $class_val);		// lớp
	$objPHPExcel->getProperties()->setCreator("ANVT");
	$objPHPExcel->getProperties()->setLastModifiedBy("DEVTEAM");
	$objPHPExcel->getProperties()->setTitle("Bảng điểm thường xuyên");
	$objPHPExcel->getProperties()->setSubject("Bảng ghi điểm thường xuyên xuất tự động bởi phần mềm.");
	$objPHPExcel->getProperties()->setDescription("Bảng điểm thường xuyên được xuất sau khi cán bộ nhập điểm vào hệ thống.");
	$objPHPExcel->getProperties()->setKeywords("mark, ANVT, DEVTEAM");
	
//	// Dump data
    $stt=0;
    $i=0;
    $total_row = 1;
    $startRow = 11;
    $endRow=$startRow;
    //if ($total_student > 1) $sheet->insertNewRowBefore($startRow+1, $total_student);
	//get mark
	$mark_list = MarkComponentApp::listMarkComponent($id_class);
	$total_row = count($mark_list);
	$sheet->insertNewRowBefore($startRow, $total_row - 1);
	foreach($mark_list as $item_mark){		
		$stt = $i + 1;
        $activeRow = $startRow + $i -1;
		
        $sheet->getRowDimension($activeRow)->setRowHeight(-1);
        $sheet->setCellValue('A' . $activeRow, $stt);
        $sheet->setCellValue('B' . $activeRow, mb_strtoupper($item_mark['student_code'], 'UTF-8'));
        $sheet->setCellValue('C' . $activeRow, $item_mark['name']);
        $sheet->setCellValue('D' . $activeRow, $item_mark['birthday']);
        $sheet->setCellValue('E' . $activeRow, $item_mark['class']);
        $sheet->setCellValue('F' . $activeRow, $item_mark['mark_cc']);
        $sheet->setCellValue('G' . $activeRow, $item_mark['mark_kt']);        
        $endRow++;
		$i++;
	}
 
   
	// Addition Info
	$sheet->setCellValue('C' . ($endRow ), $total_row);
	$time = time();
	$sheet->setCellValue('E' . ($endRow + 1), 'Ngày ' . date('d', $time) . ' tháng ' . date('m', $time) . ' năm ' . date('Y', $time));
    // Output file
    $outputFileType = 'Excel5';
    $outputFileName = 'DiemThuongXuyen-' . $class_val . '-'. str_replace('-', '_', $class_subject_info['year']) . '_' . $term_val . '.xls';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $outputFileName . '"');
    $objWriter->save('php://output');
	/* 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$excel_file = "/Excel/".time().".xls";
	$objWriter->save(_ROOTPATH.$excel_file);
	$d_file_name = "table.xls";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	$objWriter->setSheetIndex(0);
	$excel_file = "/Excel/".time().".pdf";
	$objWriter->save(_ROOTPATH.$excel_file,"A4W");
	$d_file_name = "table.pdf";
	*/
 } else {
    echo 'Error!';
}

?>