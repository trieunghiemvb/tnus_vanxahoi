<?php
define('AREA','A');
require '../../../init.php';
require_once ('../subjectsdict.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";

switch ($act) {
	case 'get_major':
		$id_group_field = isset($_POST['id_group_field']) && is_numeric($_POST['id_group_field']) ? intval($_POST['id_group_field']) : 0;

		$listMajors = SubjectsDictApp::getMajors($id_group_field);
		echo json_encode($listMajors);
		break;
	case 'addnew':
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : "";
		$group_field = isset($_POST['group_field']) && is_numeric($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$major = isset($_POST['major']) && is_numeric($_POST['major']) ? intval($_POST['major']) : null;
		$knowledge_block = isset($_POST['knowledge_block']) && is_numeric($_POST['knowledge_block']) ? intval($_POST['knowledge_block']) : 0;
		$curriculum = isset($_POST['curriculum']) && is_numeric($_POST['curriculum']) ? intval($_POST['curriculum']) : 0;

		$response['status'] = 'danger';
		$response['returndata'] = '';
		if (empty($code) || empty($name) || $group_field == 0 || $knowledge_block == 0 || $curriculum == 0) {
			$response['message'] = 'Đã có lỗi: Không được bỏ trống các trường thông tin bắt buộc';
		}
		else if ($major == null && ($knowledge_block == 4 || $knowledge_block == 5)) {
			$response['message'] = 'Phải chọn chuyên ngành nếu môn học thuộc khối kiến thức chuyên ngành';
		}
		else {
			$data = array(
				'code' => $code,
				'name' => $name,
				'name_en' => $name_en,
				'id_group_field' => $group_field,
				'id_major' => $major,
				'id_knowledge_block' => $knowledge_block,
				'curriculum' => $curriculum
			);
			$exists = SubjectsDictApp::subjectExists($data['code']);
			if (!$exists) {
				$state = SubjectsDictApp::addnewSubject($data);
				if (!$state) {
					$response['message'] = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
				}
				else {
					$response['status'] = 'success';
					$response['message'] = 'Thêm mới thành công';
					$response['returndata'] = $state.';'.implode(';', $data);
				}
			}
			else {
				$response['message'] = 'Đã có lỗi: Đã tồn tại mã môn học này trong CSDL';
			}
		}
		echo json_encode($response);
		break;
	case 'update':
		$id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
		$code = isset($_POST['code']) ? trim($_POST['code']) : "";
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : "";
		$group_field = isset($_POST['group_field']) && is_numeric($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$major = isset($_POST['major']) && is_numeric($_POST['major']) ? intval($_POST['major']) : null;
		$knowledge_block = isset($_POST['knowledge_block']) && is_numeric($_POST['knowledge_block']) ? intval($_POST['knowledge_block']) : 0;
		$curriculum = isset($_POST['curriculum']) && is_numeric($_POST['curriculum']) ? intval($_POST['curriculum']) : 0;

		$response['status'] = 'danger';
		$response['returndata'] = '';

		if ($id == 0) {
			$response['message'] = 'Dữ liệu không chính xác. Vui lòng tải lại trang và thử lại';
		}
		else if (empty($code) || empty($name) || $group_field == 0 || $knowledge_block == 0 || $curriculum == 0) {
			$response['message'] = 'Đã có lỗi: Không được bỏ trống các trường thông tin bắt buộc';
		}
		else if ($major == null && ($knowledge_block == 4 || $knowledge_block == 5)) {
			$response['message'] = 'Phải chọn chuyên ngành nếu môn học thuộc khối kiến thức chuyên ngành';
		}
		else {
			$data = array(
				'code' => $code,
				'name' => $name,
				'name_en' => $name_en,
				'id_group_field' => $group_field,
				'id_major' => $major,
				'id_knowledge_block' => $knowledge_block,
				'curriculum' => $curriculum
			);
			$exists = SubjectsDictApp::subjectExists($data['code'], $id);
			if (!$exists) {
				$state = SubjectsDictApp::updateSubject($data, $id);
				if (!$state) {
					$response['message'] = 'Đã có lỗi: Không thể cập nhật thông tin';
				}
				else {
					$response['status'] = 'success';
				$response['message'] = 'Cập nhật thành công';
				$response['returndata'] = $id.';'.implode(';', $data);
				}
			}
			else {
				$response['message'] = 'Đã có lỗi: Đã tồn tại mã môn học này trong CSDL';
			}
		}
		$response['debug'] = $knowledge_block;
		echo json_encode($response);
		break;
	case 'delete':
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if($id != 0)
			$state = SubjectsDictApp::deleteSubject($id);
		if($state)
			echo 'success';
		break;
	default:
		break;
}

?>