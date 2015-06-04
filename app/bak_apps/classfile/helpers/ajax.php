<?php
define('AREA','A');
require '../../../init.php';
require_once ('../classfile.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
	case 'getclass':
		$id_course = (isset($_POST['id_course'])) ? intval($_POST['id_course']) : "";
		$id_group_field = (isset($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_class = ClassfileApp::getListClass($id_course, $id_group_field);
		echo json_encode($list_class);
		break;
	case 'filterbyclass':
		$id_class = (isset($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		// Get List Majors by Class
		$response['majors'] = ClassfileApp::getMajorByClass($id_class);

		// Get list Students by Class
		$list_students = ClassfileApp::getStudenByClass($id_class);
		// Resort list with vietnamese alphabel
		$tmp_array = array();
		foreach ($list_students as $item) {
			$key = uConvert($item->first_name . ' ' . $item->last_name);
			$tmp_array[$key] = $item;
		}
		ksort($tmp_array);
		$response['students'] = array_values($tmp_array);
		echo json_encode($response);
		break;
	case 'update':
		$id_profile = (isset($_POST['id_profile']) && is_numeric($_POST['id_profile'])) ? intval($_POST['id_profile']) : 0;
		$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : "";
		$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : "";
		$sex = (isset($_POST['sex']) && is_numeric($_POST['sex'])) ? intval($_POST['sex']) : 0;
		$student_code = isset($_POST['student_code']) ? trim($_POST['student_code']) : "";
		$birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : "";
		$birth_place = (isset($_POST['birth_place']) && is_numeric($_POST['birth_place'])) ? intval($_POST['birth_place']) : 0;
		$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : "";
		$phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
		$id_card = isset($_POST['id_card']) ? trim($_POST['id_card']) : "";
		$id_major = (isset($_POST['id_major']) && is_numeric($_POST['id_major'])) ? intval($_POST['id_major']) : 0;

		$returndata = '';

		if ($id_profile == 0) {
			$status = 'danger';
			$message = 'Error: Đã có lỗi - Dữ liệu không chính xác. Vui lòng tải lại trang (F5) và thực hiện lại!';
		}
		else if (empty($first_name) || empty($last_name) || empty($birthday) || $birth_place == 0){
			$status = 'danger';
			$message = 'Error: Không được bỏ trống các trường thông tin bắt !';
		}
		else{
			$data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'sex' => $sex,
				'birthday' => $birthday,
				'birth_place' => $birth_place,
				'email' => $email,
				'phone' => $phone,
				'id_card' => $id_card
			);
			// Update student code in table _student
			ClassfileApp::updateStudentInfo($student_code, $id_major, $id_profile);
			// Update profile info into table _profile
			$state = ClassfileApp::updateProfile($data, $id_profile);
			if ($state) {
				$status = 'success';
				$message = 'Cập nhật dữ liệu thành công';
				$returndata = $id_profile.';'.implode(';', $data).';'.$student_code;
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	case 'addnew':
		$id_class = (isset($_POST['id_class']) && is_numeric($_POST['id_class'])) ? intval($_POST['id_class']) : 0;
		$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : "";
		$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : "";
		$sex = (isset($_POST['sex']) && is_numeric($_POST['sex'])) ? intval($_POST['sex']) : 0;
		$student_code = isset($_POST['student_code']) ? trim($_POST['student_code']) : "";
		$birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : "";
		$birth_place = (isset($_POST['birth_place']) && is_numeric($_POST['birth_place'])) ? intval($_POST['birth_place']) : 0;
		$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : "";
		$phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
		$id_card = isset($_POST['id_card']) ? trim($_POST['id_card']) : "";
		$id_major = (isset($_POST['id_major']) && is_numeric($_POST['id_major'])) ? intval($_POST['id_major']) : 0;

		$returndata = '';

		if (empty($first_name) || empty($last_name) || empty($birthday) || $birth_place == 0){
			$status = 'danger';
			$message = 'Error: Không được bỏ trống các trường thông tin bắt !';
		} else {
			$data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'sex' => $sex,
				'birthday' => $birthday,
				'birth_place' => $birth_place,
				'email' => $email,
				'phone' => $phone,
				'id_card' => $id_card
			);
			// Insert data into _profile
			$id_profile = ClassfileApp::addnewProfile($data);
			if ($id_profile) {
				$_data = array(
					'id_profile' => $id_profile,
					'id_class' => $id_class,
					'student_code' => $student_code,
					'id_major' => $id_major
				);
				$state = ClassfileApp::insertStudent($_data);
				if ($state) {
					$status = 'success';
					$message = 'Thêm mới thông tin học viên thành công';
					$returndata = $id_profile.';'.implode(';', $data).';'.$student_code;
				} else {
					$status = 'danger';
					$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại (1)';
				}
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại (2)';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
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
