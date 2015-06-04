<?php

define('AREA', 'A');
require '../../../init.php';
require_once ('../exam_room.php');
include('../../../libs/phpexcel/PHPExcel.php');

$id_exam_list = ( isset($_GET['id_exam_list']) && is_numeric($_GET['id_exam_list']) ) ? intval($_GET['id_exam_list']) : 0;
//$id_building = ( isset($_GET['id_building']) && is_numeric($_GET['id_building']) ) ? intval($_GET['id_building']) : 0;
//$id_room = ( isset($_GET['id_room']) && is_numeric($_GET['id_room']) ) ? intval($_GET['id_room']) : 0;
$type = ( isset($_GET['type']) && is_numeric($_GET['type']) ) ? intval($_GET['type']) : 0;
$excel_temp = $type==0?"exam_room_temp_vd.xls":"exam_room_temp_tl.xls";
$exam_date= isset($_GET['exam_date'])? strval($_GET['exam_date']):"";
$exam_duration= isset($_GET['exam_duration'])? strval($_GET['exam_duration']):"0";
if ($id_exam_list != 0) {

    $inputFileType = 'Excel5';
    $inputFileName = "xlstemplate/$excel_temp";
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
    $sheet_temp = $objPHPExcel->getActiveSheet();
    $objPHPExcel->setActiveSheetIndex(0); // Set id sheet template về 0
    // Lấy danh sách phòng thi
    $arr_room = Exam_roomApp::get_arr_room($id_exam_list);
    $num_room = count($arr_room);
    //print_r($arr_room);
    $subject_name = Exam_roomApp::get_subject_name($id_exam_list);
    $address = Exam_roomApp::get_select_table("room", "id", "name");
//    echo $address. "| $id_building - $id_room";
    // Tạo ra các sheet tương ứng với phòng thi
    for ($i = 1; $i <= $num_room; $i++) {
        $id_room = Exam_roomApp::get_exam_address($id_exam_list, $i);
        $room_add = $address[$id_room];
        $new_sheet = clone $sheet_temp; // Copy sheet template
        $new_sheet->setTitle("Phòng $i"); // Đặt title cho sheet vừa copy
        $sheetId = $i;
        $objPHPExcel->addSheet($new_sheet, $sheetId); // set ID và thêm sheet mới vào book
//        $sheet = $objPHPExcel->getActiveSheet(); // Select sheet hiện tại
        $new_sheet->setCellValue('D5', $i);
        $new_sheet->setCellValue('D6', $room_add);
        $new_sheet->setCellValue('C7', $subject_name);
        $new_sheet->setCellValue('C8', $exam_date);
        $new_sheet->setCellValue('G7', $exam_duration." phút");
        // Thêm sinh viên vào danh sách
        $start_row = 11;
        $arr_student = $arr_room[$i];
        //print_r($arr_student);
        $stt = 1;
        $new_sheet->insertNewRowBefore($start_row+1,count($arr_student));
        foreach ($arr_student as $item) {
            $activeRow = $start_row;
            $new_sheet->getRowDimension($activeRow)->setRowHeight(-1);
            $new_sheet->setCellValue('A' . $activeRow, $stt);
            $new_sheet->setCellValue('B' . $activeRow, mb_strtoupper($item['student_code'], 'UTF-8'));
            $new_sheet->setCellValue('C' . $activeRow, $item['last_name']. " ". $item['first_name']);
            $new_sheet->setCellValue('D' . $activeRow, $item['birthday']);
            $start_row++;
            $stt++;
        }
    }
    $objPHPExcel->removeSheetByIndex(0); // Remove sheet template
    // Output file
    $outputFileType = 'Excel5';
    $outputFileName = 'tnus-sdh-ds-thi' . $subject_name . '.xls';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $outputFileName . '"');
    $objWriter->save('php://output'); 
} else {
    echo 'Error!';
}
?>