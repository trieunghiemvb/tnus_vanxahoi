<?php
define('AREA','A');
require '../../../init.php';
require_once ('../trainingform.php');
set_time_limit(36000);
$timezone  = +7; //(GMT +7:00) 
ini_set("memory_limit","1220M");
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
    // Load table
    case 'loadfilter':
        $type_filter = isset($_POST['type_filter']) ? $_POST['type_filter'] : "";
        $id_course = isset($_POST['course']) ? $_POST['course'] : "";
        $id_group_field = isset($_POST['group_field']) ? $_POST['group_field'] : "";
        $id_subject = isset($_POST['subject']) ? $_POST['subject'] : "";
        $id_knowledge = isset($_POST['knowledge']) ? $_POST['knowledge'] : "";
		$group_field_list = "";
		$major_list = "";
		$knowledge_list = "";
		$curriculum = "";	//số tín chỉ
		$training_list = "";
		$status="";
        $message="";
        $returndata = "";
		
		if($type_filter == 'course' && $id_course <> "" )
		{
			$training_form = TrainingFormApp::listTrainingForm($id_course);
			$course_name = TrainingFormApp::getValueByKey('course', 'course_name', array('status' => array('1'), 'id' => array($id_course)));
			$status = 'success';
			$message = 'Bạn đã chọn '.$course_name;
			if(!empty($training_form))
			{
				foreach ($training_form as $item)
				{ 
					$returndata .= "<tr><td>".$course_name."</td><td>".$item['major_name']."</td><td>".$item['knowledge_block_name']. "</td><td>".$item['curriculum']."</td></tr>";
				}
			}
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;
		}
		elseif($type_filter == 'group')
		{
			$major_list = TrainingFormApp::listMajor($id_group_field);
			$group_field_name = TrainingFormApp::getValueByKey('group_field', 'group_field_name', array('status' => array('1'), 'id' => array($id_group_field)));
			$major_data = "";
			$major_data .='<option value="">-- Tất cả --</option>';
			foreach ($major_list as $key => $value){ 
				$major_data .='<option value="'.$key.'">'.$value.'</option>';
			} 
			$returndata .= $major_data;
			$status = 'success';
			$message = 'Bạn đã chọn nhóm ngành '.$group_field_name;
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;
		}elseif($type_filter == 'knowledge')
		{
			if($id_course == "")
			{
				$status = 'error';
				$message = 'Chưa chọn khóa học';
				echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
				break;
			}
			if($id_subject == "")
			{
				$status = 'error';
				$message = 'Chưa chọn chuyên ngành';
				echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
				break;
			}
			if($id_knowledge == "")
			{
				$status = 'error';
				$message = 'Chưa chọn khối kiến thức';
				echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
				break;
			}
			
			if($id_course <> "" && $id_subject <> "" && $id_knowledge <> "" )
			{
				$curriculum = TrainingFormApp::getValueByKey('trainning_form', 'curriculum', array('id_course' => array($id_course), 'id_major' => array($id_subject), 'id_knowledge_block' => array($id_knowledge)));
				$returndata = $curriculum;
			}else{
				$returndata = '';
			}	
		}else{
			$returndata = '';
		}
        $returndata = rtrim($returndata, '');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // cập nhật / thêm khung chương trình
	
    case 'upgrade':
		$id_course = isset($_POST['course']) ? $_POST['course'] : "";
		$id_course_upgrade = isset($_POST['course_upgrade']) ? $_POST['course_upgrade'] : "";
		$db = new Database;	
		$return_state = "";
		$returndata = "";
		$status="";
        $message="";
		if(!empty($id_course) && !empty($id_course_upgrade) && $id_course_upgrade !== $id_course)
		{
			
			$training_form_check = TrainingFormApp::listTrainingForm($id_course_upgrade);
			if(count($training_form_check) !== 0)
			{
				foreach ($training_form_check as $item)
				{ 
					$curriculum_check = TrainingFormApp::getValueByKey('trainning_form', 'curriculum', array('id_course' => array($id_course), 'id_major' => array($item['id_major']), 'id_knowledge_block' => array($item['id_knowledge_block'])));
					if($curriculum_check <> "")
					{
						$data = array('curriculum' => $item['curriculum'],'essay_mark' => $item['essay_mark']);
						$where = array('id_course' => array($id_course), 'id_major' => array($item['id_major']), 'id_knowledge_block' => array($item['id_knowledge_block']));
						$result_form = $db->update('trainning_form', $data, $where);
					}else{				
						$data = array('id_course' => $id_course, 'id_major' => $item['id_major'], 'id_knowledge_block' => $item['id_knowledge_block'], 'curriculum' => $item['curriculum'], 'essay_mark' => $item['essay_mark'], 'status' => '1');
						$where ="";
						$result_form = $db->insert('trainning_form', $data, $where);
					}
				}
				$status = 'success';
				$message = 'Thao tác thành công!';
				$returndata = "";
			}
			else
			{
				$status = 'error';
				$message = 'Khóa học được kế thừa chưa được xây dựng khung chương trình đào tạo!';
				echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
				break;
			}
			// get training form
			$training_form = TrainingFormApp::listTrainingForm($id_course);
			$course_name = TrainingFormApp::getValueByKey('course', 'course_name', array('status' => array('1'), 'id' => array($id_course)));
			
			if(!empty($training_form))
			{
				$training_form = TrainingFormApp::listTrainingForm($id_course);
				$course_name = TrainingFormApp::getValueByKey('course', 'course_name', array('status' => array('1'), 'id' => array($id_course)));				
				if(!empty($training_form))
				{
					foreach ($training_form as $item)
					{ 
						$returndata .= "<tr><td>".$course_name."</td><td>".$item['major_name']."</td><td>".$item['knowledge_block_name']. "</td><td>".$item['curriculum']."</td></tr>";
					}
				}
			}
		}
		else
		{
			$status = 'error';
			$message = 'Kiểm tra lại khóa học được kế thừa và khóa học cần xây dựng khung chương trình đào tạo.';
			$returndata = "";
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
    case 'updateform':
		$id_course = isset($_POST['course']) ? $_POST['course'] : "";
        $id_group_field = isset($_POST['group_field']) ? $_POST['group_field'] : "";
        $id_subject = isset($_POST['subject']) ? $_POST['subject'] : "";
        $id_knowledge = isset($_POST['knowledge']) ? $_POST['knowledge'] : "";
        $curriculum = isset($_POST['curriculum']) ? $_POST['curriculum'] : "";
		$return_state = "";
		$returndata = "";
		$status="";
        $message="";
		$db = new Database;					
		if($id_course <> "" && $id_subject <> "" && $id_knowledge <> "" )
		{
			$curriculum_check = TrainingFormApp::getValueByKey('trainning_form', 'curriculum', array('id_course' => array($id_course), 'id_major' => array($id_subject), 'id_knowledge_block' => array($id_knowledge)));
			if($curriculum_check <> "")
			{
				$data = array('curriculum' => $curriculum);
				$where = array('id_course' => array($id_course), 'id_major' => array($id_subject), 'id_knowledge_block' => array($id_knowledge));
				$result_form = $db->update('trainning_form', $data, $where);
			}else{				
				$data = array('id_course' => $id_course, 'id_major' => $id_subject, 'id_knowledge_block' => $id_knowledge, 'curriculum' => $curriculum, 'status' => '1');
				$where ="";
				$result_form = $db->insert('trainning_form', $data, $where);
			}
			// get training form
			$training_form = TrainingFormApp::listTrainingForm($id_course);
			$course_name = TrainingFormApp::getValueByKey('course', 'course_name', array('status' => array('1'), 'id' => array($id_course)));
			$status = 'success';
			$message = 'Bạn đã chọn '.$course_name;
			if(!empty($training_form))
			{
				$training_form = TrainingFormApp::listTrainingForm($id_course);
				$course_name = TrainingFormApp::getValueByKey('course', 'course_name', array('status' => array('1'), 'id' => array($id_course)));
				$status = 'success';
				$message = 'Bạn đã chọn '.$course_name;
				if(!empty($training_form))
				{
					foreach ($training_form as $item)
					{ 
						$returndata .= "<tr><td>".$course_name."</td><td>".$item['major_name']."</td><td>".$item['knowledge_block_name']. "</td><td>".$item['curriculum']."</td></tr>";
					}
				}
			}		
		}
		if($result_form)
		{
			$state = true;
			$return_state .= $returndata;
		}
		else
		{
			$state = false;
			$return_state .= '';
		} 
		if ($state) 
		{
			$status = 'success';
			$message = 'Lưu thành công';
			$returndata = $return_state;
		} 
		else 
		{
			$status = 'danger';
			$message = 'Đã có lỗi. Vui lòng thử lại';
			$returndata = $return_state;
		} 
			echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
			break;
		   
		default:
			break;
}
?>
