<?php
define('AREA','A');
require '../../../init.php';
require_once ('../major.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	// Thêm mới dữ liệu
	case 'addnew':
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$tranning_type = isset($_POST['tranning_type']) ? intval($_POST['tranning_type']) : 0;
		$returndata = '';
		if (empty($name) || empty($code) || $group_field == 0 || $tranning_type == 0) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'major_name' => $name,
				'major_name_en' => $name_en,
				'major_code' => $code,
				'id_group_field' => $group_field,
				'tranning_type' => $tranning_type
			);
			$state = MajorApp::addnewMajor($data);
			if ($state) {
				$status = 'success';
				$message = 'Thêm mới thành công';
				$returndata = $state.';'.implode(';', $data);
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
		$name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$tranning_type = isset($_POST['tranning_type']) ? intval($_POST['tranning_type']) : 0;
		$returndata = '';
		if($id == 0){
			$status = 'danger';
			$message = 'Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($name) || empty($code) || $group_field == 0 || $tranning_type == 0){
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'major_name' => $name,
				'major_name_en' => $name_en,
				'major_code' => $code,
				'id_group_field' => $group_field,
				'tranning_type' => $tranning_type
			);
			$state = MajorApp::updateMajor($data, $id);
			if ($state) {
				$status = 'success';
				$message = 'Chỉnh sửa dữ liệu thành công';
				$returndata = $id.';'.implode(';', $data);
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
			$state = MajorApp::deleteMajor($id);
		if($state)
			echo 'success';
		break;
	default:
		break;
}
?>
