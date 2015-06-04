<?php
define('AREA','A');
require '../../../init.php';
require_once ('../setmajor.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	case 'getclass':
		$id_course = (isset($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_class = SetMajorApp::getListClass($id_course, $id_group_field);
		echo json_encode($list_class);
		break;
	case 'getstudent':
		$id_class = (isset($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		// GET LIST MAJOR BY ID_CLASS
		$list_major = SetMajorApp::getMajorByClass($id_class);

		// GET LIST STUDENTS BY ID_CLASS
		$list_students = SetMajorApp::getStudenByClass($id_class);
		// Resort list with vietnamese alphabel
		$tmp_array = array();
		foreach ($list_students as $item) {
			$key = uConvert($item->first_name . ' ' . $item->last_name);
			$tmp_array[$key] = $item;
		}
		ksort($tmp_array);
		$list_students = array_values($tmp_array);
		echo json_encode(array('majors'=>$list_major, 'students'=>$list_students));
		break;
	case 'setmajor':
		$response['status'] = 'danger';
		$id_major = isset($_POST['id_major']) && is_numeric($_POST['id_major']) ? intval($_POST['id_major']) : 0;
		$profiles = isset($_POST['profiles']) && is_array($_POST['profiles']) ? $_POST['profiles'] : "";
		if ($profiles == "") {
			$response['message'] = 'Error: Phải lựa chọn danh sách học viên trước!';
		}
		elseif ($id_major == 0) {
			$response['message'] = 'Error: Chưa lựa chọn chuyên ngành';
		} else {
			$result = SetMajorApp::setMajor($id_major, $profiles);
			if ($result) {
				$response['status'] = 'success';
				$response['message'] = 'Phân chuyên ngành thành công';
			}
			else
				$response['message'] = 'Error: Đã có lỗi trong quá trình thực hiện. Vui lòng thử lại';
		}
		echo json_encode($response);
		break;
	default:
		break;
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