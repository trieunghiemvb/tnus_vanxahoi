<?php
define('AREA','A');
require '../../../init.php';
require_once ('../course.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	// Thêm mới dữ liệu
	case 'addnew':
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
		$end = isset($_POST['end']) ? intval($_POST['end']) : 0;
		$returndata = '';
		if (empty($name) || empty($code) || $start == 0 || $end == 0) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'course_name' => $name,
				'course_code' => $code,
				'period' => $start . '_' . $end
			);
			$state = CourseApp::addnewCourse($data);
			if ($state) {
				$status = 'success';
				$message = 'Thêm mới thành công';
				CourseApp::insertFormular($state);
				$returndata = $state.';'.$name.';'.$code.';'.$start.';'.$end;
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	// Cập nhật dữ liệu
	case 'update':
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
		$end = isset($_POST['end']) ? intval($_POST['end']) : 0;
		$returndata = '';
		if($id == 0){
			$status = 'danger';
			$message = 'Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($name) || empty($code) || $start == 0 || $end == 0) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'course_name' => $name,
				'course_code' => $code,
				'period' => $start . '_' . $end
			);
			$state = CourseApp::updateCourse($data, $id);
			if ($state) {
				$status = 'success';
				$message = 'Chỉnh sửa dữ liệu thành công';
				$returndata = $id.';'.$name.';'.$code.';'.$start.';'.$end;
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi trong quá trình cập nhật dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	// Xóa dữ liệu
	case 'delete':
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if($id != 0)
			$state = CourseApp::deleteCourse($id);
		if($state)
			echo 'success';
		break;
	default:
		break;
}
?>
