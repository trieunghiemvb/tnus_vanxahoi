<?php
define('AREA','A');
require '../../../init.php';
require_once ('../classsubject.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,15) : "";
switch ($act) {
	case 'course_info':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : 0;
		$list_class = ClassSubjectApp::getCourseInfo($id_course);
		$response['status'] = 'danger';
		if($list_class != null){
			$response['status'] = 'success';
			$response['returndata'] = $list_class;
		}
		echo json_encode($response);
		break;
	// Lay danh sach mon hoc theo khoa, nganh, ki hoc
	case 'list_subject':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : 0;
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$term = (isset($_POST['term']) && is_numeric($_POST['term'])) ? intval($_POST['term']) : 0;
		$list_subjects = ClassSubjectApp::getListSubject($id_course, $term, $id_group_field);
		echo json_encode($list_subjects);
		break;
	// Lay danh sach lop theo khoa, nganh
	case 'listclass':
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : 0;
		$id_group_field = (isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field'])) ? intval($_POST['id_group_field']) : "";
		$list_class = ClassSubjectApp::getListClass($id_course, $id_group_field);
		echo json_encode($list_class);
		break;
	// Them moi lop hoc phan
	case 'add_class':
		$id_subject = isset($_POST['id_subject']) && is_numeric($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$year = isset($_POST['year']) ? trim($_POST['year']) : "";
		$term = isset($_POST['term']) && is_numeric($_POST['term']) ? intval($_POST['term']) : 0;
		$course = isset($_POST['course']) && is_numeric($_POST['course']) ? intval($_POST['course']) : 0;
		$response['status'] = 'alert-danger';
		if ($id_subject != 0 && $year != "" && $term != 0) {
			ClassSubjectApp::addClass($year, $term, $id_subject, $course);
			$list_classSubject = ClassSubjectApp::listClassSubject($year, $term, $id_subject);
			$response['status'] = 'success';
			$response['data'] = $list_classSubject;

		}
		else {
			$response['message'] = 'Phải chọn năm học, kì học và môn học trước khi tạo lớp';
		}
		echo json_encode($response);
		break;
	// Lay danh sach lop hoc phan theo nam, ki hoc va mon hoc
	case 'class_subject':
		$id_subject = isset($_POST['id_subject']) && is_numeric($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$year = isset($_POST['year']) ? trim($_POST['year']) : "";
		$term = isset($_POST['term']) && is_numeric($_POST['term']) ? intval($_POST['term']) : 0;
		if ($id_subject != 0 && $year != "" && $term != 0) {
			$list_classSubject = ClassSubjectApp::listClassSubject($year, $term, $id_subject);
			echo $list_classSubject;
		}
		break;
	// Lay danh sach chi tiet lop hoc phan va lop hanh chinh
	case 'class_detail':
		$id_classsubject = isset($_POST['id_classsubject']) && is_numeric($_POST['id_classsubject']) ? intval($_POST['id_classsubject']) : 0;
		$id_class = isset($_POST['id_class']) && is_numeric($_POST['id_class']) ? intval($_POST['id_class']) : 0;
		$year = isset($_POST['year']) ? trim($_POST['year']) : null;
		$term = isset($_POST['term']) && is_numeric($_POST['term']) ? intval($_POST['term']) : null;
		$id_subject = isset($_POST['id_subject']) && is_numeric($_POST['id_subject']) ? intval($_POST['id_subject']) : null;
		$response['class_subject'] = ClassSubjectApp::ClassSubjectDetail($id_classsubject);
		$response['class_detail'] = ClassSubjectApp::ClassDetail($id_class, $year, $term, $id_subject);
		echo json_encode($response);
		break;
	case 'add_student':
		$id_classsubject = isset($_POST['id_classsubject']) && is_numeric($_POST['id_classsubject']) ? intval($_POST['id_classsubject']) : 0;
		$profiles = isset($_POST['profiles']) && is_array($_POST['profiles']) ? $_POST['profiles'] : "";
		$response['status'] = 'danger';
		if ($profiles == "") {
			$response['message'] = 'Error: Phải lựa chọn danh sách học viên trước!';
		}
		elseif ($id_classsubject == 0) {
			$response['message'] = 'Error: Chưa lựa chọn lớp học phần';
		} else {
			$result = ClassSubjectApp::addStudent($id_classsubject, $profiles);
			if ($result) {
				$response['status'] = 'success';
				$response['message'] = 'Thêm học viên vào lớp thành công';
			}
			else
				$response['message'] = 'Error: Đã có lỗi trong quá trình thực hiện. Vui lòng thử lại';
		}
		echo json_encode($response);
		break;
	case 'remove_student':
		$id_classsubject = isset($_POST['id_classsubject']) && is_numeric($_POST['id_classsubject']) ? intval($_POST['id_classsubject']) : 0;
		$profiles = isset($_POST['profiles']) && is_array($_POST['profiles']) ? $_POST['profiles'] : "";
		$response['status'] = 'danger';
		if ($profiles == "") {
			$response['message'] = 'Error: Phải lựa chọn danh sách học viên trước!';
		}
		elseif ($id_classsubject == 0) {
			$response['message'] = 'Error: Chưa lựa chọn lớp học phần';
		} else {
			$result = ClassSubjectApp::removeStudent($id_classsubject, $profiles);
			if ($result) {
				$response['status'] = 'success';
				$response['message'] = 'Xoá học viên khỏi lớp học phần thành công';
			}
			else
				$response['message'] = 'Error: Đã có lỗi trong quá trình thực hiện. Vui lòng thử lại';
		}
		echo json_encode($response);
		break;
	default:
		break;
}

if (isset($_GET['test'])) {
	print_r(ClassSubjectApp::addStudent(1,array(10)));
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
