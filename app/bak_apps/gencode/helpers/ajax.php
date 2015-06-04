<?php
define('AREA','A');
require '../../../init.php';
require_once ('../gencode.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,15) : "";
switch ($act) {
	case 'getclass':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_class = GencodeApp::getListClass($id_course, $id_group_field);
		echo json_encode($list_class);
		break;
	// Get list student by Course and Group_field
	case 'filterstudent':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_students = GencodeApp::listStudenByFilter($id_course, $id_group_field);
		// Resort
		$tmp_array = array();
		foreach ($list_students as $item) {
			$key = uConvert($item->first_name . ' ' . $item->last_name);
			$tmp_array[$key] = $item;
		}
		ksort($tmp_array);
		$return = array_values($tmp_array);
		echo json_encode($return);
		break;
	// Get list students by Class
	case 'getstudent':
		$id_class = (isset($_POST['id_class']) && is_numeric($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		$list_students = GencodeApp::getStudenByClass($id_class);
		// Resort list with vietnamese alphabel
		$tmp_array = array();
		foreach ($list_students as $item) {
			$key = uConvert($item->first_name . ' ' . $item->last_name);
			$tmp_array[$key] = $item;
		}
		ksort($tmp_array);
		$return = array_values($tmp_array);
		echo json_encode($return);
		break;
	case 'gencodeall':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$id_class = (isset($_POST['id_class']) && is_numeric($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		$codePrefix = isset($_POST['codePrefix']) ? trim($_POST['codePrefix']) : "";
		$codeLength = (isset($_POST['codeLength']) && is_numeric($_POST['codeLength'])) ? intval($_POST['codeLength']) : 0;
		$codeStart = (isset($_POST['codeStart']) && is_numeric($_POST['codeStart'])) ? intval($_POST['codeStart']) : 0;

		$return = '';

		if (empty($codePrefix) || $codeLength == 0 || $codeStart == 0) {
			$status = 'danger';
			$message = 'Nhập thông tin tiền tố, độ rộng và số bắt đầu trước khi tiến hành đánh mã';
		}
		elseif ($id_course == 0 && $id_class == 0) {
			$status = 'danger';
			$message = 'Phải lựa chọn Khóa - Ngành hoặc Lớp trước';
		}
		else{
			if($id_class != 0){
				// Danh ma theo lop
				$list_students = GencodeApp::getStudenByClass($id_class);
				// Resort list with vietnamese alphabel
				$tmp_array = array();
				foreach ($list_students as $item) {
					$key = uConvert($item->first_name . ' ' . $item->last_name);
					$tmp_array[$key] = $item;
				}
				ksort($tmp_array);
				$return = array_values($tmp_array);
				// Gencode
				for ($i=0; $i < count($return); $i++) {
					$code = $codePrefix.substr(str_repeat('0', $codeLength).($codeStart + $i), -$codeLength);
					GencodeApp::setStudentCode($return[$i]->id_profile, $code);
					$return[$i]->student_code = $code;
				}
				$status = 'success';
				$message = 'Đánh mã thành công';
			}
			else{
				// Danh ma theo khoa va nganh
				$list_students = GencodeApp::listStudenByFilter($id_course, $id_group_field);
				// Resort list with vietnamese alphabel
				$tmp_array = array();
				foreach ($list_students as $item) {
					$key = uConvert($item->first_name . ' ' . $item->last_name);
					$tmp_array[$key] = $item;
				}
				ksort($tmp_array);
				$return = array_values($tmp_array);
				// Gencode
				for ($i=0; $i < count($return); $i++) {
					$code = $codePrefix.substr(str_repeat('0', $codeLength).($codeStart + $i), -$codeLength);
					GencodeApp::setStudentCode($return[$i]->id_profile, $code);
					$return[$i]->student_code = $code;
				}
				$status = 'success';
				$message = 'Đánh mã thành công';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $return));
		break;
	case 'gencodenone':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$id_class = (isset($_POST['id_class']) && is_numeric($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		$codePrefix = isset($_POST['codePrefix']) ? trim($_POST['codePrefix']) : "";
		$codeLength = (isset($_POST['codeLength']) && is_numeric($_POST['codeLength'])) ? intval($_POST['codeLength']) : 0;
		$codeStart = (isset($_POST['codeStart']) && is_numeric($_POST['codeStart'])) ? intval($_POST['codeStart']) : 0;

		$return = '';

		if (empty($codePrefix) || $codeLength == 0 || $codeStart == 0) {
			$status = 'danger';
			$message = 'Nhập thông tin tiền tố, độ rộng và số bắt đầu trước khi tiến hành đánh mã';
		}
		elseif ($id_course == 0 && $id_class == 0) {
			$status = 'danger';
			$message = 'Phải lựa chọn Khóa - Ngành hoặc Lớp trước';
		}
		else{
			if($id_class != 0){
				// Danh ma theo lop - Nhung sinh vien chua co ma
				$_list_students = GencodeApp::getStudenByClass($id_class, false);
				if (!empty($_list_students)) {
					// Resort list with vietnamese alphabel
					$_tmp_array = array();
					foreach ($_list_students as $item) {
						$key = uConvert($item->first_name . ' ' . $item->last_name);
						$_tmp_array[$key] = $item;
					}
					ksort($_tmp_array);
					$_return = array_values($_tmp_array);
					// Gencode
					for ($i=0; $i < count($_return); $i++) {
						$code = $codePrefix.substr(str_repeat('0', $codeLength).($codeStart + $i), -$codeLength);
						GencodeApp::setStudentCode($_return[$i]->id_profile, $code);
					}

					// Lay danh sach moi va hien thi ra trinh duyet
					$list_students = GencodeApp::getStudenByClass($id_class);
					// Resort list with vietnamese alphabel
					$tmp_array = array();
					foreach ($list_students as $item) {
						$key = uConvert($item->first_name . ' ' . $item->last_name);
						$tmp_array[$key] = $item;
					}
					ksort($tmp_array);
					$return = array_values($tmp_array);

					$status = 'success';
					$message = 'Đánh mã thành công';
				}
				else {
					$status = 'danger';
					$message = 'Không có học viên nào chưa đánh mã!';
				}
			}
			else{
				// Danh ma theo khoa va nganh
				$_list_students = GencodeApp::listStudenByFilter($id_course, $id_group_field, false);
				if (!empty($_list_students)) {
					// Resort list with vietnamese alphabel
					$_tmp_array = array();
					foreach ($_list_students as $item) {
						$key = uConvert($item->first_name . ' ' . $item->last_name);
						$_tmp_array[$key] = $item;
					}
					ksort($_tmp_array);
					$_return = array_values($_tmp_array);
					// Gencode
					for ($i=0; $i < count($_return); $i++) {
						$code = $codePrefix.substr(str_repeat('0', $codeLength).($codeStart + $i), -$codeLength);
						GencodeApp::setStudentCode($_return[$i]->id_profile, $code);
					}

					// Lay danh sach moi va hien thi ra trinh duyet
					$list_students = GencodeApp::listStudenByFilter($id_course, $id_group_field);
					// Resort list with vietnamese alphabel
					$tmp_array = array();
					foreach ($list_students as $item) {
						$key = uConvert($item->first_name . ' ' . $item->last_name);
						$tmp_array[$key] = $item;
					}
					ksort($tmp_array);
					$return = array_values($tmp_array);

					$status = 'success';
					$message = 'Đánh mã thành công';
				}
				else {
					$status = 'danger';
					$message = 'Không có học viên nào chưa đánh mã!';
				}
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $return));
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
