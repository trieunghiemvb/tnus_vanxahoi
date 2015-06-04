<?php
define('AREA','A');
require '../../../init.php';
require_once ('../groupfield.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	// Thêm mới dữ liệu
	case 'addnew':
		$id = isset($_POST['id']) ? trim($_POST['id']) : "";
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$value = isset($_POST['value']) ? trim($_POST['value']) : "";
		$returndata = '';
		if (empty($name) || empty($code) || empty($value)) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'group_field_name' => $name,
				'group_field_name_en' => $value,
				'group_field_code' => $code
			);
			$db = new Database;
			$tables = "group_field";
			$existGroupField = false;
			$condition = array('group_field_code' => $code );
			$result_tables = $db->getRows($tables, 'id', $condition);
			foreach ($result_tables as $item) {
				$existGroupField = false;
				$id = isset($item['id']) ? $item['id'] : "0";
				if($id  <> "0"){					
					$existStudent = true;
					break;
				}
			}
			if(!$existGroupField){
				$existGroupField = false;
				$state = GroupFieldApp::addnewGroup($data);
				$id = GroupFieldApp::getLastIdInsert($tables);
			}else{
				$state = GroupFieldApp::updateGroup($data, $id);
			}
			
			if ($state) {
				$status = 'success';
				$message = 'Thêm mới thành công';				
				$returndata = $id.';'.$name.';'.$value.';'.$code.';';
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	// Cập nhật dữ liệu
	case 'update':
		$id = isset($_POST['id']) ? trim($_POST['id']) : "";
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$value = isset($_POST['value']) ? trim($_POST['value']) : "";
		$returndata = '';
		if($id == 0){
			$status = 'danger';
			$message = 'Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($name) || empty($code) || empty($value)) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'group_field_name' => $name,
				'group_field_name_en' => $value,
				'group_field_code' => $code
			);
			$db = new Database;
			$tables = "group_field";
			$existGroupField = false;
			$condition = array('group_field_code' => $code );
			$result_tables = $db->getRows($tables, 'id', $condition);
			foreach ($result_tables as $item) {
				$existGroupField = false;
				$id_check = isset($item['id']) ? $item['id'] : "0";
				if($id_check  == $id){
					$state = GroupFieldApp::updateGroup($data, $id);
					$existStudent = true;
					break;
				}
			}
			if(!$existGroupField){
				$existGroupField = false;
				//$state = GroupFieldApp::addnewGroup($data);
				//$id = GroupFieldApp::getLastIdInsert($tables);
				
			}else{
				$status = 'danger';
				$message = 'Mã ngành đã có trong cơ sở dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
			}
			if ($state) {
				$status = 'success';
				$message = 'Cập nhật dữ liệu thành công';
				$returndata = $id.';'.$name.';'.$value.';'.$code.';';
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
			$state = GroupFieldApp::hideGroup($id);
		if($state)
			echo 'success';
		break;
	default:
		break;
}
?>
