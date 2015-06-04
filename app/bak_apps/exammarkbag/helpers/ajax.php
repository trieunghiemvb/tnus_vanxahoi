<?php
define('AREA','A');
require '../../../init.php';
require_once ('../exammarkbag.php');
set_time_limit(36000);
$timezone  = +7; //(GMT +7:00) 
ini_set("memory_limit","1220M");
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
    // Load table
    case 'loadfilter':
        $type_filter = isset($_POST['type_filter']) ? $_POST['type_filter'] : "";
        $course = isset($_POST['course']) ? $_POST['course'] : "";
        $exam = isset($_POST['exam']) ? $_POST['exam'] : "";
        $subject = isset($_POST['subject']) ? $_POST['subject'] : "";
        $room = isset($_POST['room']) ? $_POST['room'] : "";
        $bag = isset($_POST['bag']) ? $_POST['bag'] : "";
        
		$exam_list = "";
		$subjects_list = "";
		$room_list = "";
		$bag_list = "";
		$exam_data = "";
		$subjects_data = "";
		$room_data = "";		
		$bag_data = "";		
		$exam_id_selected = "";		
		$status="";
        $message="";
        $returndata = "";
		if($type_filter == 'course')
		{
			$exam_list = ExamMarkBagApp::getExamNameListByIdCourse($course);						
		}else if($type_filter == 'exam')
		{
			$subjects_list = ExamMarkBagApp::getExamSubjectList($course, $exam);				
		}else if($type_filter == 'subject')
		{											
			$bag_list = ExamMarkBagApp::getExamBagList($course, $exam, $subject);			
		}	
		
		else if($type_filter == 'get_id_exam')
		{							
			$exam_id_selected = ExamMarkBagApp::getExamIdList($course, $exam, $subject);			
		}	
		
		if(is_array($exam_list))
		{			
			$exam_data .='<option value="0">-- Tất cả --</option>';
			foreach ($exam_list as $key => $value)
			{ 
				$exam_data .='<option value="'.$value.'">'.$value.'</option>';
			} 
			$returndata .= $exam_data;
		}
		if(is_array($subjects_list))
		{			
			$subjects_data .='<option value="0">-- Tất cả --</option>';
			foreach ($subjects_list as $key => $value)
			{ 
				$subjects_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $subjects_data;
		}
		if(is_array($bag_list) && !empty($bag_list) && !is_null($bag_list))
		//if(is_array($bag_list))

		{			
			$bag_data .='<option value="0">-- Tất cả --</option>';
			foreach ($bag_list as $key => $value)
			{ 
				if($key !=="")
				{
				$bag_data .='<option value="'.$key.'">'.$value.'</option>';
				}
			} 
			$returndata .= $bag_data;
		}
		if(!empty($exam_id_selected))
		{			
			$returndata .= $exam_id_selected;
		}
		
        $returndata = rtrim($returndata, ' ');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // tải danh sách thí sinh
    case 'loadtester':
		$id_exam = isset($_POST['id_exam']) ? $_POST['id_exam'] : "";
        $course = isset($_POST['course']) ? $_POST['course'] : "";
        $exam = isset($_POST['exam']) ? $_POST['exam'] : "";
        $subject = isset($_POST['subject']) ? $_POST['subject'] : "";
        $bag = isset($_POST['bag']) ? $_POST['bag'] : "";
        $testerList = ExamMarkBagApp::getExamTesterListByBag($course, $exam, $subject, $bag);
		$i = 0;
		$data = "";
		foreach ($testerList as $item )
		{
			$i++;
			$option_state = "";
			$data .= "<tr id=\"student_".$item['id_student']."\">";
			$data .= "<input type=\"hidden\" value=\"".$item['id_student']."\" id=\"student_i_".$i."\">";
			$data .= "<td class=\"number\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">".$i."</td>";
			$data .= "<td class=\"student_code\" rowspan=\"1\" colspan=\"1\">-------------</td>";
			//$data .= "<td class=\"student_code\" rowspan=\"1\" colspan=\"1\">".$item['student_code']."</td>";
			//$data .= "<td class=\"student_name\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">".$item['student_name']."</td>";
			//$data .= "<td class=\"student_name\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">-------------</td>";
			//$data .= "<td class=\"student_birthday\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">-------------</td>";
			//$data .= "<td class=\"student_birthday\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">".$item['birthday']."</td>";
			//$data .= "<td class=\"class\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">".$item['class']."</td>";
			//$data .= "<td class=\"class\" style=\"text-align:center;\" rowspan=\"1\" colspan=\"1\">-------------</td>";
			//$option_state = "<select name=\"filter_state_".$item['id_student']."\" id=\"filter_state_".$item['id_student']."\" class=\"form-control input-sm state_filter\">";
			//$option_state = "<input type=\"hidden\" name=\"filter_state_".$item['id_student']."\" id=\"filter_state_".$item['id_student']."\" value=\"".$item['status']."\"class=\"form-control input-sm state_filter\">";
			$option_state = "";
			
			if($item['status'] == '1'){
				$state_1 = "Bình thường";
				$option_state .= $state_1;
			}else if ($item['status'] == '2'){
				$state_2 =  'Cảnh cáo';
				$option_state .= $state_2;
			}
			else if ($item['status'] == '3'){
				$state_3 =  'Khiển trách';
				$option_state .= $state_3;
			}
			else if ($item['status'] == '4'){
				$state_4 =  'Vi phạm';
				$option_state .= $state_4;
			}
			else if ($item['status'] == '5'){
				$state_5 =  'Vắng';
				$option_state .= $state_5;
			}
			
			//$data .= "<td class=\"state\" style=\"width:121px; text-align:center;\" rowspan=\"1\" colspan=\"1\"><input type=\"hidden\" value=\"".$item['status']."\" id=\"state_student_".$item['id_student']."\">".$option_state."</td>";
			$data .= "<td class=\"state\"><input type=\"hidden\" style=\"text-align:center;\" value=\"".$item['status']."\" id=\"state_student_".$item['id_student']."\">".$option_state."</td>";
			$data .= "<td class=\"beat\" style=\"text-align:center;\">".$item['beat']."</td>";
			$data .= "<td class=\"mark_input\"><input type=\"text\" id=\"mark_input_".$item['id_student']."\" class=\"col-sm-12 formattedNumberField\" style=\"text-align:center;\" value=\"\"></td>";
			$data .= "<td class=\"mark_component\"><input type=\"text\" id=\"mark_exam_".$item['id_student']."\" class=\"col-sm-12\" style=\"text-align:center;\" value=\"\" disabled></td>";
			$data .= "</tr>";		
			//$data .= "<input type=\"hidden\" value=\"id_course=;\" id=\"exam_data\">";		
		}
		$returndata = $data;
		//$returndata = "hehee";
		$status = 'success';
		$message = 'Tải thông tin thành công';
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
// cập nhật trạng thái thi của thí sinh
    case 'updatmark':
        $id_course = isset($_POST['id_course']) ? trim($_POST['id_course']) : "";
        $id_exam = isset($_POST['id_exam']) ? trim($_POST['id_exam']) : "";
        $name_exam = isset($_POST['name_exam']) ? trim($_POST['name_exam']) : "";
        $id_subject = isset($_POST['id_subject']) ? trim($_POST['id_subject']) : "";
        $id_student = isset($_POST['id_student']) ? trim($_POST['id_student']) : "";
        $mark_exam = isset($_POST['mark_exam']) ? trim($_POST['mark_exam']) : "";
       // $room = isset($_POST['room']) ? trim($_POST['room']) : "";
        $bag = isset($_POST['bag']) ? trim($_POST['bag']) : "";
		
		$query_update = '';
		$returndata = '';
		$return_state = '';
		$state = false;
		$success = false;
		$db = new Database;
		$table_exam = 'examination';		
		$table_class_subject = 'class_subject';		
		$table_exam_list = 'exam_list';		
		$table_class_subject_detail = 'class_subject_details';	
		
		if($mark_exam ==""){
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể vào điểm của thí sinh. Vui lòng thử lại';
			$returndata = $id_student;
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break; 
		} 
		
		// lấy kỳ học
		$condition = array('id_course' => array($id_course), 'name' => array($name_exam));	
		$term = ExamMarkBagApp::getValueByKey($table_exam, 'term',$condition);		
		/* //get id_subject
		$condition = array('name' => array($id_subject), 'status' => array('1'));	
		$id_subject = ExamMarkBagApp::getValueByKey('subject', 'id',$condition);		
 */
		// lấy id class subject detail
		$condition = array('term' => array($term), 'id_course' => array($id_course), 'id_subject' => array($id_subject));	
		$result = $db->getRows($table_class_subject, '*', $condition);       
		 
		foreach ($result as $item) 
		{			
			//cập nhật điểm thi
			$data = array('mark_exam' => $mark_exam);
			$get_mark_check = "";
			$where = array('id_class_subject' => array($item['id']), 'id_student' => array($id_student));
			$result_mark = $db->update($table_class_subject_detail, $data, $where);			
			if($result_mark){
				$get_mark_check = ExamMarkBagApp::getValueByKey($table_class_subject_detail, 'mark_exam',$where);
				
				if($get_mark_check == $mark_exam){				
					$success = true;
					$return_state = $id_student;
				}
				else{
					$state = false;
					$return_state = $id_student;
				}
			}
			else{
				$state = false;
				$return_state = $id_student;
			} 		
		}	
		
		if ($success) {
			$status = 'success';
			$message = 'Vào điểm thành công';
			$returndata = $id_student;
		} else {
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể vào điểm của thí sinh. Vui lòng thử lại';
			$returndata = $id_student;
		} 
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;   
		default:
			break;
}
?>
