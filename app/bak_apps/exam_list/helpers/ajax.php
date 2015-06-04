<?php
define('AREA','A');
require '../../../init.php';
require_once ('../exam_list.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,22) : "";
switch ($act) {
	case 'getsubject':
		$id_exam = isset($_POST['examid']) ? intval($_POST['examid']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$subjects = Exam_listApp::getSubjectByIdExam($id_exam, $group_field);
		echo json_encode($subjects);

		break;
	case 'subjectinfo':
		$id_exam = isset($_POST['examid']) ? intval($_POST['examid']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$id_subject = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
		$response = array('type' => 0, 'class_subject' => '', 'list_student' => '');

		if ($id_exam > 0 && $id_subject > 0){
			$exam_info = Exam_listApp::getExamInfo($id_exam, $id_subject);
			if ($exam_info->type != 0)
				$response['type'] = $exam_info->type;

			$list_class_subject = Exam_listApp::getListSubjectClass($id_exam, $id_subject, $group_field);
			$html = "";
			foreach ($list_class_subject as $key => $value) {
				$html .= '<tr><td class="class_subject" data-id="'.$key.'">'.$value.'</td></tr>';
			}
			$response['class_subject'] = $html;
		}
		echo json_encode($response);
		break;
	case 'update_exam_type':
		$id_exam = isset($_POST['examid']) ? intval($_POST['examid']) : 0;
		$id_subject = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
		$exam_type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		if ($id_exam > 0 && $id_subject > 0){
			$result = Exam_listApp::updateExamInfo($id_exam, $id_subject, $exam_type);
			if ($result)
				echo 'Cập nhật hình thức thi thành công!';
		}
		break;
	case 'classSubject_detail':
		$id_class_subject = isset($_POST['class_subject']) ? intval($_POST['class_subject']) : 0;
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		if ($id_class_subject > 0 && $id_exam > 0 && $id_subject > 0){
			$result = Exam_listApp::getClassSubjectDetail($id_class_subject, $id_exam, $id_subject);
			echo $result;
		}
		break;
	case 'add_student':
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$profiles = isset($_POST['profiles']) && is_array($_POST['profiles']) ? $_POST['profiles'] : "";
		if ($profiles == "") {
			echo 'Error: Phải lựa chọn danh sách học viên trước!';
		}
		elseif ($id_exam == 0 || $id_subject == 0) {
			echo 'Error: Chưa lựa chọn đợt thi hoặc môn học';
		} else {
			$result = Exam_listApp::addStudent($id_exam, $id_subject, $profiles);
			if ($result) {
				echo 'Thêm thành công';
			}
			else
				echo 'Đã có lỗi trong quá trình thêm. Vui lòng thử lại';
		}
		break;
	case 'remove_student':
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$profiles = isset($_POST['profiles']) && is_array($_POST['profiles']) ? $_POST['profiles'] : "";
		if ($profiles == "") {
			echo 'Error: Phải lựa chọn danh sách học viên trước!';
		}
		elseif ($id_exam == 0 || $id_subject == 0) {
			echo 'Error: Chưa lựa chọn đợt thi hoặc môn học';
		} else {
			$result = Exam_listApp::removeStudent($id_exam, $id_subject, $profiles);
			if ($result) {
				echo 'Xóa thành công';
			}
			else
				echo 'Đã có lỗi trong quá trình xóa. Vui lòng thử lại';
		}
		break;
	case 'getExamListDetail':
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$response = '';
		if ($id_exam > 0 && $id_subject > 0){
			$response = Exam_listApp::getExamListDetail($id_exam, $id_subject);
		}
		echo $response;
		break;
	case 'getClassSubjectDetail':
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$id_class_subject = isset($_POST['id_class_subject']) ? intval($_POST['id_class_subject']) : 0;
		$response = '';
		if ($id_exam > 0 && $id_subject > 0 && $id_class_subject > 0){
			$response = Exam_listApp::getClassSubjectDetail($id_class_subject, $id_exam, $id_subject);
		}
		echo $response;
		break;
	default:
		break;
}
?>
