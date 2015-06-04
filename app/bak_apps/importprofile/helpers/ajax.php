<?php
define('AREA','A');
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require '../../../init.php';
require_once ('../importprofile.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	case 'getclass':
		$id_course = (isset($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_class = ImportProfileApp::getListClass($id_course, $id_group_field);
		break;
	case 'review':
		$response['status'] = 'danger';
		if(isset($_FILES['file'])){
			if ($_FILES['file']['error'] == 0) {
				# Khong co loi
				$file = $_FILES['file'];
				if (checkXLSFile($file)){
					if ($file['type'] == 'application/vnd.ms-excel') {
						$file_ext = '.xls';
					} else {
						$file_ext = '.xlsx';
					}
					$file_name = time();
					move_uploaded_file($_FILES['file']['tmp_name'], 'upload/'.$file_name.$file_ext);
					$response['status'] = 'success';
					$response['uploaded_file'] = $file_name.$file_ext;
					$response['data_list'] = readXLSFileContent('upload/'.$file_name.$file_ext);
				} else {
					$response['message'] = 'Error: File không đúng định dạng. Chỉ sử dụng file Excel (.xsl hoặc .xlsx) để import danh sách!';
				}
			}
			else{
				$response['message'] = 'Error: Có lỗi. Vui lòng tải lại trang và thử lại';
			}
		}
		else{
			$response['message'] = 'Phải chọn file để Import!!!';
		}
		echo json_encode($response);
		break;
	case 'import':
		$response['status'] = 'danger';
		$id_class = (isset($_POST['id_class']) && is_numeric($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		$uploaded_file = isset($_POST['uploaded_file']) ? trim($_POST['uploaded_file']) : "";
		if ($uploaded_file == "" || !is_file('upload/'.$uploaded_file)) {
			$response['message'] = 'Error: Chưa có danh sách. Chọn file dữ liệu trước rồi nhấn chọn "Xem trước dữ liêu" trước khi Import';
		}
		else{
			$flag = true;
			$data_list = readXLSFileContent('upload/'.$uploaded_file);
			foreach ($data_list as $item) {
				$result = ImportProfileApp::insertProfile($item, $id_class);
				if (!$result)
					$flag = false;
			}
			if ($flag) {
				$response['status'] = 'success';
				$response['message'] = 'Import dữ liệu thành công';
				unlink('upload/'.$uploaded_file);
			}
			else{
				$response['message'] = 'Error: Đã có lỗi trong quá trình import (Import lỗi hoặc không import được toàn bộ danh sách). Vui lòng kiểm tra lại và thử lại!';
			}
		}
		echo json_encode($response);
		break;
	default:
		break;
}

function checkXLSFile($file){
    // Check mime type
    $xlsfile = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if (in_array($file['type'], $xlsfile))
        return true;
    return false;
}

function readXLSFileContent($file){
    require(DIR_ROOT.DS.'libs/phpexcel/PHPExcel.php');
    $inputFileType = PHPExcel_IOFactory::identify($file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($file);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    $highestRow = $objWorksheet->getHighestRow();

    // Process data
    $returndata = array();
    $provices = ImportProfileApp::getListProvice();
    for ($i=2; $i <= $highestRow ; $i++) {
        $last_name = $objWorksheet->getCell('A'. $i)->getValue();
        $first_name = $objWorksheet->getCell('B'. $i)->getValue();
        $sex = $objWorksheet->getCell('C'. $i)->getValue();
        $birthday = $objWorksheet->getCell('D'. $i)->getValue();
        $birth_place = $objWorksheet->getCell('E'. $i)->getValue();
        $birth_place = ucwords(mb_strtolower($birth_place, 'UTF-8'));
            $birth_place = array_search($birth_place, $provices);
        $email = $objWorksheet->getCell('F'. $i)->getValue();
        $phone = $objWorksheet->getCell('G'. $i)->getValue();
        $id_card = $objWorksheet->getCell('H'. $i)->getValue();
        $note = $objWorksheet->getCell('I'. $i)->getValue();
        // Set key and convert
        $key = uConvert($first_name.' '.$last_name);
        $returndata[$key] = array('first_name'=>$first_name, 'last_name'=>$last_name, 'sex'=>$sex, 'birthday'=>$birthday, 'birth_place'=>$birth_place, 'email'=>$email, 'phone'=>$phone, 'id_card'=>$id_card, 'note'=>$note);
    }
    ksort($returndata);
    return $returndata;
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
