<?php
define('AREA','A');
require '../../../init.php';
require_once ('../printmark.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";

switch ($act) {
	case 'filterCourse':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$reponse = array('classes' => '', 'majors' => '', 'years' => '', 'terms' => '');
		if ($course > 0){
			$reponse['classes'] = PrintMarkApp::getClasses($course, $group_field);
			list($reponse['years'], $reponse['terms']) = PrintMarkApp::courseInfo($course);
			$reponse['majors'] = PrintMarkApp::getMajors($group_field);
		}
		echo json_encode($reponse);
		break;
	case 'listStudent':
		$id_class = isset($_POST['id_class']) ? intval($_POST['id_class']) : 0;
		$reponse = '';
		if ($id_class > 0){
			$reponse .= PrintMarkApp::getListStudent($id_class);
		}
		echo $reponse;
		break;
	default:
		echo '<pre>';
		$res = PrintMarkApp::courseInfo(1);
		print_r($res);
		echo '</pre>';
		break;
}
?>