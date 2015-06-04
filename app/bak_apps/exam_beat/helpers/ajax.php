<?php
define('AREA','A');
require '../../../init.php';
require_once ('../exam_beat.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
	case 'getExams':
		$id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0;
		$response = Exam_beatApp::getListExam($id_course);
		echo json_encode($response);
		break;
	case 'getsubject':
		$id_exam = isset($_POST['examid']) ? intval($_POST['examid']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$subjects = Exam_beatApp::getSubjectByIdExam($id_exam, $group_field);
		echo json_encode($subjects);

		break;
	case 'subjectinfo':
		$id_exam = isset($_POST['examid']) ? intval($_POST['examid']) : 0;
		$id_subject = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
		$response = array('detail' => '', 'list_student' => '');

		if ($id_exam > 0 && $id_subject > 0){
			$response['detail'] = Exam_beatApp::getExamInfo($id_exam, $id_subject);
			$response['list_student'] = Exam_beatApp::getExamListDetail($id_exam, $id_subject);
		}
		echo json_encode($response);
		break;
	case 'genBeat':
		$id_exam = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_subject = isset($_POST['id_subject']) ? intval($_POST['id_subject']) : 0;
		$beat_start = isset($_POST['beat_start']) ? intval($_POST['beat_start']) : 0;
		$beat_step = isset($_POST['beat_step']) ? intval($_POST['beat_step']) : 0;
		$split_value = isset($_POST['split_value']) ? intval($_POST['split_value']) : 0;
		$response = array('message' => 'Đã có lỗi. Hãy kiểm tra lại', 'html' => '');
		if ($id_exam > 0 && $id_subject > 0 && $beat_start > 0 && $beat_step > 0 && $split_value){
			$result = Exam_beatApp::generateBeat($id_exam, $id_subject, $beat_start, $beat_step, $split_value);
			if ($result != false) {
				$response['message'] = 'Đã đánh phách thành công';
				$response['html'] = $result;
			}
		}
		echo json_encode($response);
		break;
	default:
		$debug = Exam_beatApp::generateBeat(1,1,3,3,3);
		echo '<pre>';
		print_r($debug);
		echo '</pre>';
		break;
}
?>
