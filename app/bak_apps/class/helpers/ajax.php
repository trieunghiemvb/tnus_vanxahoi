<?php
define('AREA','A');
require '../../../init.php';
require_once ('../class.php');
$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
    // Load table
    case 'loadtable':
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : "";
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : "";
        $status="";
        $message="";
        $returndata = "";
        $items = ClassApp::listClass($id_course, $id_group_field);
        $course = ClassApp::listCourse();
        $group_field = ClassApp::listGroup_field();
        foreach ($items as $item) {
            $course_name = $course[$item['id_course']];
            $group_field_name = $group_field[$item['id_group_field']];
            $row=$item['id'].";".$item['class_name'].";".$item['class_name'].";".$item['class_code'].";".$item['id_course'].";".$item['id_group_field'].";".$course_name.";".$group_field_name;
            $returndata.=$row."#";
        }
        $returndata = rtrim($returndata, '#');
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Thêm mới dữ liệu
    case 'addnew':
        $class_name = isset($_POST['class_name']) ? trim($_POST['class_name']) : "";
        $class_name_en = isset($_POST['class_name_en']) ? trim($_POST['class_name_en']) : "";
        $class_code = isset($_POST['class_code']) ? trim($_POST['class_code']) : "";
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0;
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : 0;
        // Lấy danh sách course và group_field
        $course = ClassApp::listCourse();
        $group_field = ClassApp::listGroup_field();
        $course_name=$id_course!=0 ? $course[$id_course]:"";
        $group_field_name=$id_group_field!=0 ? $group_field[$id_group_field]:"";
        $returndata = '';
        if (empty($class_name) || empty($class_code) || $id_course == 0 || $id_group_field == 0) {
            $status = 'danger';
            $message = 'Không được bỏ trống các trường thông tin';
        }
        else {
            $data = array(
                    'class_name' => $class_name,
                    'class_name_en' => $class_name_en,
                    'class_code' => $class_code,
                    'id_course' => $id_course,
                    'id_group_field' => $id_group_field
            );
            $state = ClassApp::addnewClass($data);
            if ($state) {
                $status = 'success';
                $message = 'Thêm mới thành công';
                $returndata = $state.';'.$class_name.';'.$class_name_en.';'.$class_code.';'.$id_course.';'.$id_group_field.";".$course_name.";".$group_field_name;
            } else {
                $status = 'danger';
                $message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
            }
        }
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Cập nhật dữ liệu
    case 'update':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $class_name = isset($_POST['class_name']) ? trim($_POST['class_name']) : "";
        $class_name_en = isset($_POST['class_name_en']) ? trim($_POST['class_name_en']) : "";
        $class_code = isset($_POST['class_code']) ? trim($_POST['class_code']) : "";
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0;
        $id_group_field = isset($_POST['id_group_field']) ? intval($_POST['id_group_field']) : 0;
        // Lấy danh sách course và group_field
        $course = ClassApp::listCourse();
        $group_field = ClassApp::listGroup_field();
        $group_field = ClassApp::listGroup_field();
        $course_name=$id_course!=0 ? $course[$id_course]:"";
        $group_field_name=$id_group_field!=0 ? $group_field[$id_group_field]:"";
        $returndata = '';
        if($id == 0) {
            $status = 'danger';
            $message = 'Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
        }
        else if (empty($class_name) || empty($class_code) || $id_course == 0 || $id_group_field == 0) {
            $status = 'danger';
            $message = 'Không được bỏ trống các trường thông tin';
        }
        else {
            $data = array(
                    'class_name' => $class_name,
                    'class_name_en' => $class_name_en,
                    'class_code' => $class_code,
                    'id_course' => $id_course,
                    'id_group_field' => $id_group_field
            );
            $state = ClassApp::updateClass($data, $id);
            if ($state) {
                $status = 'success';
                $message = 'Chỉnh sửa dữ liệu thành công';
                $returndata = $id.';'.$class_name.';'.$class_name_en.';'.$class_code.';'.$id_course.';'.$id_group_field.";".$course_name.";".$group_field_name;
            } else {
                $status = 'danger';
                $message = 'Đã có lỗi trong quá trình cập nhật dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
            }
        }
        echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
        break;
    // Xóa dữ liệu
    case 'delete':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if($id != 0)
            $state = ClassApp::deleteClass($id);
        if($state)
            echo 'success';
        break;
    default:
        break;
}
?>
