<?php
define('AREA','A');
require '../../../init.php';
require_once ('../transfer_class.php');
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
    // Load class stay
    case 'clstay':
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : "";
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : "";
        $status="";
        $message="";
        $returndata = "";
        $class = Transfer_classApp::listClass($id_course, $id_group_field);
        foreach ($class as $item) {
            $row=$item['id'].";".$item['class_name'];
            $returndata.=$row."#";
        }
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Load class stay
    case 'cltransfer':
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : "";
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : "";
        $status="";
        $message="";
        $returndata = "";
        $class = Transfer_classApp::listClass($id_course, $id_group_field);
        foreach ($class as $item) {
            $row=$item['id'].";".$item['class_name'];
            $returndata.=$row."#";
        }
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Load student in class_stay
    case 'loadstay':
        $id_class = isset($_POST['id_class']) ? intval($_POST['id_class']) : "";
        $status="";
        $message="";
        $returndata = "";
        $students = Transfer_classApp::getStudents($id_class);
        foreach ($students as $item) {
            $id_profile = $item['id_profile'];
            //echo "id_profile=".$id_profile;
            $profile = Transfer_classApp::getProfile($id_profile);
            //print_r($profile);
            $row=$item['id_profile'].";".$item['id_class'].";".$profile->last_name.";".$profile->first_name.";".$item['student_code'];
            $returndata.=$row."#";
        }
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Load student in class_transfer
    case 'loadtf':
        $id_class = isset($_POST['id_class']) ? intval($_POST['id_class']) : "";
        $status="";
        $message="";
        $returndata = "";
        $students = Transfer_classApp::getStudents($id_class);
        foreach ($students as $item) {
            $id_profile = $item['id_profile'];
            //echo "id_profile=".$id_profile;
            $profile = Transfer_classApp::getProfile($id_profile);
            //print_r($profile);
            $row=$item['id_profile'].";".$item['id_class'].";".$profile->last_name.";".$profile->first_name.";".$item['student_code'];
            $returndata.=$row."#";
        }
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // call ajax action transfer
    case 'transfer':
        $students_transfer = isset($_POST['students_transfer']) ? $_POST['students_transfer'] : "";
        $students_transfer = rtrim($students_transfer, '#');
        $id_class_stay = isset($_POST['id_class_stay']) ? intval($_POST['id_class_stay']) : "";
        $id_class_transfer = isset($_POST['id_class_transfer']) ? intval($_POST['id_class_transfer']) : "";
        $status="success";
        $message="Chuyển lớp thành công!";
        $class_transfered = "";
        $class_stay = "";
        //echo "students_transfer="+$students_transfer;
        if($students_transfer!="" && $id_class_transfer!="" && $id_class_stay!="") {
            //echo "students_transfer="+$students_transfer;
            $arr_students = explode(";", $students_transfer);
//            print_r($arr_students);
            // update student
            Transfer_classApp::transferClass($arr_students, $id_class_transfer);
            // return list student in class updated
            $students = Transfer_classApp::getStudents($id_class_transfer);
            foreach ($students as $item) {
                $id_profile = $item['id_profile'];
                //echo "id_profile=".$id_profile;
                $profile = Transfer_classApp::getProfile($id_profile);
                //print_r($profile);
                $row=$item['id_profile'].";".$item['id_class'].";".$profile->last_name.";".$profile->first_name.";".$item['student_code'];
                $class_transfered.=$row."#";
            }
            $class_transfered = rtrim($class_transfered, '#');
            // reload class stay
            $students = Transfer_classApp::getStudents($id_class_stay);
            foreach ($students as $item) {
                $id_profile = $item['id_profile'];
                //echo "id_profile=".$id_profile;
                $profile = Transfer_classApp::getProfile($id_profile);
                //print_r($profile);
                $row=$item['id_profile'].";".$item['id_class'].";".$profile->last_name.";".$profile->first_name.";".$item['student_code'];
                $class_stay.=$row."#";
            }
            $class_stay = rtrim($class_stay, '#');

        }else {
            $status="danger";
            $message="Chuyển lớp thất bại! <br/> Vui lòng kiểm tra lại thông tin chuyển lớp";
        }
        echo json_encode(array('status' => $status, 'message' => $message, 'classtransfered' => $class_transfered, 'classstay' => $class_stay));
        break;
    default:
        break;
}
?>
