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
		$test_percent = isset($_POST['test_percent']) ? trim($_POST['test_percent']) : "";
                $essay_mark = isset($_POST['essay_mark']) ? trim($_POST['essay_mark']) : "";

		$returndata = '';

		if ($id_formula == 0 || MarkFormulaApp::checkCourse($id_course) == false) {
			$status = 'danger';
			$message = 'Error: Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($element_percent) || empty($test_percent) || empty($essay_mark)) {
			$status = 'danger';
			$message = 'Error: Không được bỏ trống phần trăm các loại điểm';
		}
		else if ( ($element_percent + $test_percent) != 100 ) {
			$status = 'danger';
			$message = 'Error: Tổng phần trăm các loại điểm phải là 100%';
		}
		else {
			$data = array(
				'id_course' => $id_course,
				'element_percent' => $element_percent,
				'test_percent' => $test_percent,
                                'essay_mark' => $essay_mark
			);
			// Update data
			$state = MarkFormulaApp::updateFormular($data, $id_formula);
			if ($state) {
				$status = 'success';
				$message = 'Chỉnh sửa dữ liệu thành công';
				$returndata = $id_formula.';'.implode(';', $data);
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi trong quá trình cập nhật dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	default:
		# code...
		break;
}

?>