<?php
define('AREA','A');
require '../../../init.php';
require_once ('../markformula.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	case 'update':
		$id_formula = (isset($_POST['id_formula']) && is_numeric($_POST['id_formula'])) ? intval($_POST['id_formula']) : 0;
		$id_course = (isset($_POST['id_course']) && is_numeric($_POST['id_course'])) ? intval($_POST['id_course']) : 0;
		$element_percent = isset($_POST['element_percent']) ? trim($_POST['element_percent']) : "";

		$response['status'] = 'danger';
		$response['returndata'] = '';

		if ($id_formula == 0 || MarkFormulaApp::checkCourse($id_course) == false) {
			$response['message'] = 'Error: Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($element_percent) || $element_percent < 0) {
			$response['message'] = 'Error: Không được bỏ trống phần trăm các loại điểm';
		}
		else if ($element_percent > 50) {
			$response['message'] = 'Error: Tỉ trọng điểm thành phần tối đa là 50%';
		}
		else {
			$test_percent = 100 - $element_percent;
			$data = array(
				'id_course' => $id_course,
				'element_percent' => $element_percent,
				'test_percent' => $test_percent
			);
			// Update data
			$state = MarkFormulaApp::updateFormular($data, $id_formula);
			if ($state) {
				$response['status'] = 'success';
				$response['message'] = 'Chỉnh sửa dữ liệu thành công';
				$response['returndata'] = $id_formula.';'.implode(';', $data);
			} else {
				$response['message'] = 'Đã có lỗi trong quá trình cập nhật dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
			}
		}
		echo json_encode($response);
		break;
	default:
		break;
}

?>