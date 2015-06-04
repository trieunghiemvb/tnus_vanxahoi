<?php
define('AREA','A');
require '../../../init.php';
require_once ('../printclasssubject.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";

switch ($act) {
	case 'courseInfo':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$courseInfo = PrintClassSubjectApp::getCourseInfo($course);
		$years = '<option value="">-- Lựa chọn --</option>'; $terms = '<option value="">-- Lựa chọn --</option>';
		foreach ($courseInfo as $key => $value) {
			$years .= '<option value="'.$key.'" data-year="'.$key.'">'.$key.'</option>';
			foreach ($value as $item) {
				$terms .= '<option value="'.$item.'" data-filter="'.$key.'">'.$item.'</option>';
			}
		}
		echo json_encode(array('years' => $years, 'terms' => $terms));
		break;
	case 'getSubject':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : null;
		$term = isset($_POST['term']) ? intval($_POST['term']) : 0;
		$subjects = PrintClassSubjectApp::getListSubject($course, $term, $group_field);
		$html = '<option value="">-- Lựa chọn --</option>';
		foreach ($subjects as $key => $value) {
			$html .= '<option value="'.$key.'">'.$value.'</option>';
		}
		echo $html;
		break;
	case 'getClass':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$subject = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
		$term = isset($_POST['term']) ? intval($_POST['term']) : 0;
		$classes = PrintClassSubjectApp::listClassSubject($course, $term, $subject);
		$html = '';
		$i = 0;
        foreach ($classes as $item) {
        	$i++;
            $html .= '<tr>
            	<td class="text-center">'. $i .'</td>
            	<td>'.$item->name.'</td>
            	<td class="text-center">'.$item->total.'</td>
            	<td class="text-center">
            		<div class="checkbox">
            			<label>
            				<input type="checkbox" class="sel_class" value="'.$item->id.'">
            			</label>
            		</div>
            	</td>
            </tr>';
        }
		echo $html;
		break;
	default:
		echo '<pre>';
		$res = PrintMarkApp::courseInfo(1);
		print_r($res);
		echo '</pre>';
		break;
}
?>