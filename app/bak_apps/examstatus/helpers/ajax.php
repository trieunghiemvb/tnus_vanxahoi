<?php
define('AREA','A');
require '../../../init.php';
require_once ('../examstatus.php');
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
        
		$exam_list = "";
		$subjects_list = "";
		$room_list = "";
		$exam_data = "";
		$subjects_data = "";
		$room_data = "";		
		$exam_id_selected = "";		
		$status="";
        $message="";
        $returndata = "";
		if($type_filter == 'course')
		{
			$exam_list = ExamStatusApp::getExamNameListByIdCourse($course);						
		}else if($type_filter == 'exam')
		{
			$subjects_list = ExamStatusApp::getExamSubjectList($course, $exam);				
		}else if($type_filter == 'subject')
		{							
			$room_list = ExamStatusApp::getExamRoomList($course, $exam, $subject);			
		}	
		else if($type_filter == 'get_id_exam')
		{							
			$exam_id_selected = ExamStatusApp::getExamIdList($course, $exam, $subject);			
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
		if(is_array($room_list))
		{			
			$room_data .='<option value="0">-- Tất cả --</option>';
			foreach ($room_list as $key => $value)
			{ 
				$room_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $room_data;
		}
		if(!empty($exam_id_selected))
		{			
			$returndata .= $exam_id_selected;
		}
		
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // cập nhật trạng thái
    case 'loadtester':
		$id_exam = isset($_POST['id_exam']) ? $_POST['id_exam'] : "";
        $room = isset($_POST['room']) ? $_POST['room'] : "";
        $testerList = ExamStatusApp::getExamTesterList($id_exam, $room);
		$i = 0;
		$data = "";
		foreach ($testerList as $item )
		{
			$i++;
			$option_state = "";
			$data .= "<tr id=\"student_".$item['id_student']."\">";
			$data .= "<input type=\"hidden\" value=\"".$item['id_student']."\" id=\"student_i_".$i."\">";
			$data .= "<td class=\"number\">".$i."</td>";
			$data .= "<td class=\"student_code\">".$item['student_code']."</td>";
			$data .= "<td class=\"student_name\">".$item['student_name']."</td>";
			$data .= "<td class=\"student_birthday\">".$item['birthday']."</td>";
			$data .= "<td class=\"student_sex\">".$item['sex']."</td>";
			$data .= "<td class=\"class\">".$item['class']."</td>";
			$option_state = "<select name=\"filter_state_".$item['id_student']."\" id=\"filter_state_".$item['id_student']."\" class=\"form-control input-sm state_filter\">";
			$state_1 = ($item['status'] == '1') ? 'selected' : '';
			$state_2 = ($item['status'] == '2') ? 'selected' : '';
			$state_3 = ($item['status'] == '3') ? 'selected' : '';
			$state_4 = ($item['status'] == '4') ? 'selected' : '';
			$state_5 = ($item['status'] == '5') ? 'selected' : '';
			$option_state .= "<option value=\"1\" ".$state_1.">Bình thường</option>";
			$option_state .= "<option value=\"2\" ".$state_2.">Cảnh cáo</option>";
			$option_state .= "<option value=\"3\" ".$state_3.">Khiển trách</option>";
			$option_state .= "<option value=\"4\" ".$state_4.">Vi phạm</option>";
			$option_state .= "<option value=\"5\" ".$state_5.">Vắng</option>";
			$option_state .= "</select>";
			$data .= "<td class=\"state\">".$option_state."</td>";
			$data .= "</tr>";		
		}
		$returndata = $data;
		//$returndata = "hehee";
		$status = 'success';
		$message = 'Tải thông tin thành công';
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
// cập nhật trạng thái thi của thí sinh
    case 'updatstate':
        $exam = isset($_POST['id_exam']) ? trim($_POST['id_exam']) : "";
        $id_student = isset($_POST['id_student']) ? trim($_POST['id_student']) : "";
        $room = isset($_POST['room']) ? trim($_POST['room']) : "";
        $status = isset($_POST['state']) ? trim($_POST['state']) : "";
       
		$returndata = '';
		$return_state = '';
		$db = new Database;
		$table = 'exam_list_details';		
		$state = false;	
		
		$data = array('status' => $status);
		$where = array('id_exam_list' => array($exam), 'id_student' => array($id_student), 'room' => array($room));
		$result_mark = $db->update($table, $data, $where);			
		if($result_mark){
			$state = true;
			$return_state .= $id_student .';';
		}else{
			$state = false;
			$return_state .= $id_student .';';
		} 
		if ($state) {
			$status = 'success';
			$message = 'Cập nhật trạng thái thi của thí sinh thành công';
			$returndata = $return_state;
		} else {
			$status = 'danger';
			$message = 'Đã có lỗi: Không thể cập nhật trạng thái thi của thí sinh. Vui lòng thử lại';
			$returndata = $return_state;
		} 
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;   
		default:
			break;
}
?>
