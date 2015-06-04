<?php
define('AREA','A');
require '../../../init.php';
require_once ('../phanlop.php');
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";
switch ($act) {
    // Load table
    case 'loadtable':
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : "";
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : "";
        $status="";
        $message="";
        $returndata = "";
        $items = PhanLopApp::listClass($id_course, $id_group_field);
        $course = PhanLopApp::listCourse();
        $group_field = PhanLopApp::listGroup_field();
	if(is_array($items)){
		foreach ($items as $item) {
			//$course_name = $course[$item['id_course']];
			//$group_field_name = $group_field[$item['id_group_field']];            
			if(isset($item['id'])){
				$row=$item['id'].";".$item['class_name'];
				$returndata.=$row."#";
			}
		}
	}        
	$returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Chuyển lớp
    case 'addstudent':
        $total_profile = isset($_POST['total_profile']) ? trim($_POST['total_profile']) : "";
        $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;
       
		$returndata = '';
		$return_state = '';
        if ($class_id == 0) {
            $status = 'danger';
            $message = 'Chưa chọn lớp';
        }
		else if (empty($total_profile)) {
            $status = 'danger';
            $message = 'Chưa chọn sinh viên cần phân lớp';
        }
        else {
			$id_profile_all = explode(",", $total_profile);
			for($i=0;$i<count($id_profile_all);$i++)
			{
				$id_profile = $id_profile_all[$i];
				$data = array(
                    'id_profile' => $id_profile,
                    'student_code' => '',
                    'id_class' => $class_id,
                    'status' => '1'
				);
				/* $db = new Database;
				$student = 'student';	
				$existStudent = false;
				$condition = array('status' => array('1'), 'id_profile' => $id_profile );
				$result_student = $db->getRows($student, 'id_class', $condition);
				foreach ($result_student as $item) {
					$existStudent = false;
					$id_check = isset($item['id_class']) ? $item['id_class'] : "0";
					if($id_check  <> "0"){					
						$existStudent = true;
						break;
					}
				}
				if(!$existStudent){
					$existStudent = false;
					$state = PhanLopApp::addnewStudent($data);
					$return_state .= $id_profile .';';
				} */	
					$state = PhanLopApp::addnewStudent($data);
					$return_state .= $id_profile .';';				
			}          
            if ($state) {
                $status = 'success';
                $message = 'Phân lớp thành công';
                $returndata = $return_state;
            } else {
                $status = 'danger';
                $message = 'Đã có lỗi: Không thể phân lớp. Vui lòng thử lại';
            }
        }
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
       
    default:
        break;
}
?>
