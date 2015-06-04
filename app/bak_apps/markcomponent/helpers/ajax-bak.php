<?php
define('AREA','A');
require '../../../init.php';
require_once ('../markcomponent.php');
set_time_limit(36000);
$timezone  = +7; //(GMT +7:00) 
ini_set("memory_limit","1220M");
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
    // Load table
    case 'loadfilter':
        $type_filter = isset($_POST['type_filter']) ? $_POST['type_filter'] : "";
        $course = isset($_POST['course']) ? $_POST['course'] : "";
        $year = isset($_POST['year']) ? $_POST['year'] : "";
        $term = isset($_POST['term']) ? $_POST['term'] : "";
        $id_group_field = isset($_POST['group_field']) ? $_POST['group_field'] : "";
        $id_subject = isset($_POST['subject']) ? $_POST['subject'] : "";
        $id_class = isset($_POST['id_class']) ? $_POST['id_class'] : 0;
		$year_list = "";
		$year_data = "";
        $term_list = "";
		$term_data = "";
		$group_field_list = "";
		$group_field_data = "";
		$subjects_list = "";
		$subjects_data = "";
		$classes_list = "";
		$class_data = "";
		$profile_list = "";
		$status="";
        $message="";
        $returndata = "";
		if($type_filter == 'course'){
			$year_list = MarkComponentApp::listYear($course);			
			$term_list = "";
			$subjects_list = "";
			$group_field_list = "";
			$profile_list = "";
			$classes_list = "";			
		}else if($type_filter == 'year'){
			$term_list = MarkComponentApp::listTerm($year);				
			$year_list = "";
			$subjects_list = "";
			$group_field_list = "";
			$classes_list = "";
			$profile_list = "";
			
		}else if($type_filter == 'term')
		{			
			$group_field_list = MarkComponentApp::listGroup_field($year, $term);
			$subjects_list = MarkComponentApp::listSubject($year, $term, $id_group_field);
			$classes_list = MarkComponentApp::listClass($year, $term, $id_group_field, $id_subject);		
			$year_list = "";
			$term_list = "";
			$profile_list = "";
		}else if($type_filter == 'group'){			
			$subjects_list = MarkComponentApp::listSubject($year, $term, $id_group_field);
			$classes_list = MarkComponentApp::listClass($year, $term, $id_group_field, $id_subject);
			$year_list = "";
			$term_list = "";
			$group_field_list = "";
			$profile_list = "";
		}else if($type_filter == 'subject'){			
			$classes_list = MarkComponentApp::listClass($year, $term, $id_group_field, $id_subject);
			$year_list = "";
			$term_list = "";
			$group_field_list = "";
			$subjects_list = "";
			$profile_list = "";
		}else if($type_filter == 'profile'){
			if($id_class <> 0){
				$profile_list = MarkComponentApp::listMarkComponent($id_class);
			}else{
			
			}	
		}		
		
		if(is_array($year_list))
		{
			
			$year_data .='<option value="0">-- Tất cả --</option>';
			foreach ($year_list as $key => $value)
			{ 
				$year_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $year_data.'#';
		}
		else
		{
			if($type_filter == 'course')
			{
				$year_data .='<option value="">-- Tất cả --</option>';
				$returndata .= '<option value="">-- Tất cả --</option>#<option value="">-- Tất cả --</option>#<option value="">-- Tất cả --</option>#<option value="">-- Tất cả --</option>#<option value="">-- Tất cả --</option>##';
				$year_list = "";
				$term_list = "";
				$group_field_list = "";
				$subjects_list = "";
				$profile_list = "";
			}
			else
			{
				$returndata .= '#';
			}
		}
		if(is_array($term_list)){			
			$term_data .='<option value="0">-- Tất cả --</option>';			
			foreach ($term_list as $key => $value){ 
				$term_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $term_data.'#';
		}else{
			$returndata .= '#';
		}
		if(is_array($group_field_list)){			
			$group_field_data .='<option value="0">-- Tất cả --</option>';
			foreach ($group_field_list as $key => $value){ 
				$group_field_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $group_field_data.'#';
		}else{
			
			$returndata .= '#';
		}
		if(is_array($subjects_list)){
			$subjects_data .='<option value="0">-- Tất cả --</option>';
			foreach ($subjects_list as $key => $value){ 
				$subjects_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $subjects_data.'#';
		}else{
			$returndata .= '#';
		}
		if(is_array($classes_list)){
			$class_data .='<option value="0">-- Tất cả --</option>';
			foreach ($classes_list as $key => $value){ 
				$class_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $class_data.'#';
		}else{
			$returndata .= '#';
		}
		if(is_array($profile_list)){
			$profile_data = "";
			$i = 0;			
			foreach ($profile_list as $item){ 
				$i++;
				$cc = is_null($item['mark_cc']) ? "" : $item['mark_cc'];
				$kt = is_null($item['mark_kt']) ? "" : $item['mark_kt'];
				$profile_data .="<tr id=\"profile_".$item['id']."\">";
				$profile_data .="<input type=\"hidden\" id=\"profile_i_".$i."\" value=\"".$item['id']."\">";
				$profile_data .="<td class=\"number\">".$i."</td>";
				$profile_data .="<td class=\"profile_code\">".$item['student_code']."</td>";
				$profile_data .="<td class=\"profile_name\">".$item['name']."</td>";
				$profile_data .="<td class=\"profile_birthday\">".$item['birthday']."</td>";
				$profile_data .="<td class=\"profile_sex\">".$item['sex']."</td>";
				$profile_data .="<td class=\"profile_mark\"><input type=\"text\" class=\"profile_mark_cc formattedNumberField\" id=\"profile_mark_cc_".$item['id']."\" value=\"".$cc."\"></td>";
				$profile_data .="<td class=\"profile_mark\"><input type=\"text\" class=\"profile_mark_kt formattedNumberField\" id=\"profile_mark_kt_".$item['id']."\" value=\"".$kt."\"></td>";				 
				$profile_data .= '</tr>';
			} 
			$profile_data .= '<input type="hidden" id="max_profile" value="'.$i.'">';
			$returndata .= $profile_data;
		}else{
			$returndata .= '';
		}
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // cập nhật điểm
    case 'updatemark':
        $id_class_subject = isset($_POST['class_id']) ? trim($_POST['class_id']) : "";
        $id_profile = isset($_POST['id_profile']) ? intval($_POST['id_profile']) : 0;
        $mark_cc = isset($_POST['mark_cc']) ? $_POST['mark_cc'] : "";
        $mark_kt = isset($_POST['mark_kt']) ? $_POST['mark_kt'] : "";
        $mark_form = isset($_POST['mark_form']) ? $_POST['mark_form'] : 1;
       
		$returndata = '';
		$return_state = '';
		$db = new Database;
		$table_class_subject_details = 'class_subject_details';		
		$state = false;	
		if($mark_cc >10){
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể cập nhật điểm. Vui lòng thử lại';
			$returndata = "cc";
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;		
		}
		if($mark_kt >10){
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể cập nhật điểm. Vui lòng thử lại';
			$returndata = "kt";
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;		
		} 
		if($mark_form == 2){
			$mark_component = ($mark_cc +  $mark_kt)/ $mark_form;
			$data = array('mark_cc' => $mark_cc, 'mark_kt' => $mark_kt, 'mark_component' => $mark_component);
		}else{
			if($mark_cc == "" && $mark_kt !==""){
				$mark_component = $mark_kt;
				$data = array('mark_kt' => $mark_kt, 'mark_component' => $mark_component);
			}else{
				$mark_component = $mark_cc;
				$data = array('mark_cc' => $mark_cc, 'mark_component' => $mark_component);
			}
			
		} 
		
		$where = array('id_class_subject' => array($id_class_subject), 'id_student' => array($id_profile));
		$result_mark = $db->update($table_class_subject_details, $data, $where);			
		if($result_mark){
			$state = true;
			$return_state .= $id_profile .';';
		}else{
			$state = false;
			$return_state .= $id_profile .';';
		} 
		if ($state) {
			$status = 'success';
			$message = 'Vào điểm thành công';
			$returndata = $return_state;
		} else {
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể cập nhật điểm. Vui lòng thử lại';
			$returndata = $return_state;
		} 
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;
		   
		default:
			break;
}
?>
