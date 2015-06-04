<?php
/**
 * App quản lý chuyên ngành
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class Exam_listApp extends AppObject {
    public $app_name="exam_list";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Lập danh sách thi";
    public $items=null;

    public function __construct() {

        if(empty($_SESSION["auth"]["id_user"])) {
            header("Location: ".INDEX); /* Redirect browser */
            exit;
        }

        parent::__construct();
    }

    public function display() {
        $task="";
        if(isset($_REQUEST['task'])) {
            $task=$_REQUEST['task'];
        }
        self::views();

    }

    /**
     * Xuất view
     * @author Duyld2108
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";

        // Get list group_field
        $this->exams = self::getListExam();
        $this->group_field = self::getListGroupField();

        parent::display();
    }

    public static function getListExam(){
        $db = new Database;
        $data = array();
        $table = 'examination';
        $result = $db->getRows($table, '*', 'status = 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

    /**
     * Lấy danh sách Ngành học
     * @return array Danh sách ngành học với $key là id ngành học, $value là tên ngành học
     */
    public static function getListGroupField(){
        $db = new Database;
        $data = array();
        $table = 'group_field';
        $result = $db->getRows($table, '*', 'status = 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->group_field_name;
        }
        return $data;
    }

    public static function getSubjectByIdExam($id_exam, $group_field = null){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'subject';
        $table2 = $prefix.'examination';
        $table3 = $prefix.'training_plan';
        $query = "SELECT s.id, s.name FROM {$table1} s, {$table2} e, {$table3} p WHERE e.id_course = p.id_course AND e.term = p.term AND p.id_subject = s.id AND e.id = :id_exam";
        if ($group_field != null && $group_field > 0)
            $query = "SELECT s.id, s.name FROM {$table1} s, {$table2} e, {$table3} p WHERE e.id_course = p.id_course AND e.term = p.term AND p.id_subject = s.id AND e.id = :id_exam AND s.id_group_field = {$group_field}";
        $result = $db->queryAll($query, array(':id_exam' => $id_exam), PDO::FETCH_OBJ);
        $data = array();
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

    public static function getExamInfo($id_exam, $id_subject){
        $db = new Database;
        $table = 'exam_list';
        $exists = $db->countRows($table, 'id', 'id_exam = :id_exam AND id_subject = :id_subject', array(':id_exam' => $id_exam, ':id_subject' => $id_subject));
        if (!$exists){
            $result = $db->insert($table, array('id_exam' => $id_exam, 'id_subject' => $id_subject, 'type' => 0));
            return true;
        }
        else{
            $result = $db->getRow($table, 'id, type', array('id_exam' => array($id_exam), 'id_subject' => array($id_subject)), PDO::FETCH_OBJ);
            return $result;
        }
    }

    public static function updateExamInfo($id_exam, $id_subject, $type){
        $db = new Database;
        $table = 'exam_list';
        $exists = $db->countRows($table, 'id', 'id_exam = :id_exam AND id_subject = :id_subject', array(':id_exam' => $id_exam, ':id_subject' => $id_subject));
        $result = $db->update($table, array('type' => $type), array('id_exam' => array($id_exam), 'id_subject' => array($id_subject)));
        return $result;
    }

    public static function getListSubjectClass($id_exam, $id_subject, $id_group_field = null){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'class_subject';
        $table2 = $prefix.'exam_list';
        $table3 = $prefix.'examination';
        $query = "SELECT c.id, c.code, c.name FROM {$table1} c, {$table2} l, {$table3} e WHERE e.id = l.id_exam AND e.id_course = c.id_course AND c.id_subject = l.id_subject AND e.id = :id_exam AND l.id_subject = :id_subject";
        if ($id_group_field != null && $id_group_field > 0)
            $query = "SELECT c.id, c.code, c.name FROM {$table1} c, {$table2} l, {$table3} e WHERE e.id = l.id_exam AND e.id_course = c.id_course AND c.id_subject = l.id_subject AND e.id = :id_exam AND l.id_subject = :id_subject AND c.id_group_field = {$id_group_field}";
        $result = $db->queryAll($query, array(':id_exam' => $id_exam, ':id_subject' => $id_subject), PDO::FETCH_OBJ);
        $data = array();
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

    public static function getClassSubjectDetail($id_class_subject, $id_exam, $id_subject, $ishtml = true){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'profile';
        $table2 = $prefix.'student';
        $table3 = $prefix.'class_subject_details';
        $table4 = $prefix.'class';
        $query = "SELECT p.first_name, p.last_name, s.student_code, c.id_student, cl.class_name FROM {$table1} p, {$table2} s, {$table3} c, {$table4} cl WHERE p.id = s.id_profile AND s.id = c.id_student AND s.id_class = cl.id AND c.id_class_subject = :id_class_subject";
        $result = $db->queryAll($query, array(':id_class_subject' => $id_class_subject), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        if ($ishtml){
            $examListDetails = self::getExamListDetail($id_exam, $id_subject, false);
            $tmp_data = array();
            foreach ($examListDetails as $item) {
                $tmp_data[] = $item->id_student;
            }
            $html = '';
            foreach ($result as $item) {
                $html .= '
                <tr>
                    <td><div class="checkbox"><label><input type="checkbox" value="'. $item->id_student .'" '.(in_array($item->id_student, $tmp_data) ? 'disabled' : '').'></label></div></td>
                    <td>'. $item->student_code .'</td>
                    <td>'. $item->last_name .'</td>
                    <td>'. $item->first_name .'</td>
                    <td>'. $item->class_name .'</td>
                </tr>
                ';
            }
            $html = preg_replace('/\s\s+/', '', $html);
            return str_replace(array("\r", "\n"), array("", ""), $html);
        }
        else
            return $result;
    }

    public static function getExamListDetail($id_exam, $id_subject, $ishtml = true){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'profile';
        $table2 = $prefix.'student';
        $table3 = $prefix.'exam_list_details';
        $table4 = $prefix.'exam_list';
        $table5 = $prefix.'class';
        $query = "SELECT s.student_code, p.first_name, p.last_name, cl.class_name, ed.id_student FROM {$table1} p, {$table2} s, {$table3} ed, {$table4} el, {$table5} cl WHERE p.id = s.id_profile AND s.id = ed.id_student AND ed.id_exam_list = el.id AND s.id_class = cl.id AND el.id_exam = :id_exam AND el.id_subject = :id_subject";
        $result = $db->queryAll($query, array(':id_exam' => $id_exam, ':id_subject' => $id_subject), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        foreach ($result as $item) {
            $html .= '
            <tr>
                <td><div class="checkbox"><label><input type="checkbox" value="'. $item->id_student .'"></label></div></td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->last_name . '</td>
                <td>'. $item->first_name .'</td>
                <td>'. $item->class_name .'</td>
            </tr>
            ';
        }
        $html = preg_replace('/\s\s+/', '', $html);
        if ($ishtml)
            return str_replace(array("\r", "\n"), array("", ""), $html);
        else
            return $result;
    }

    public static function addStudent($id_exam, $id_subject, $profiles){
        if (is_array($profiles) && $id_exam > 0 && $id_subject > 0) {
            $exam_info = self::getExamInfo($id_exam, $id_subject);
            $id_exam_list = $exam_info->id;
            $insert_query = array();
            foreach ($profiles as $item) {
                $insert_query[] = "({$id_exam_list}, {$item})";
            }
            $insert_query = implode(',', $insert_query);
            $db = new Database;
            $prefix = DB_TABLE_PREFIX;
            $table = $prefix.'exam_list_details';
            $query = "INSERT INTO {$table} (id_exam_list, id_student) VALUES " . $insert_query;
            $result = $db->query($query);
            return $result;
        } else
            return false;
    }

    public static function removeStudent($id_exam, $id_subject, $profiles){
        if (is_array($profiles) && $id_exam > 0 && $id_subject > 0) {
            $exam_info = self::getExamInfo($id_exam, $id_subject);
            $id_exam_list = $exam_info->id;
            $db = new Database;
            $prefix = DB_TABLE_PREFIX;
            $table = $prefix.'exam_list_details';
            $query = "DELETE FROM {$table} WHERE id_exam_list = :id_exam_list AND id_student IN (".implode(',', $profiles).")";
            $result = $db->query($query, array(':id_exam_list' => $id_exam_list));
            return $result;
        } else
            return false;
    }

    private static function reSort($array, $field_sort){
        $tmp_data = array();
        foreach ($array as $item) {
            $key = '';
            if (is_array($field_sort)) {
                foreach ($field_sort as $_item) {
                    $key .= $item->$_item;
                }
            }
            else
                $key = $item->$field_sort;

            $key = self::uConvert($key);
            $tmp_data[$key] = $item;
        }
        ksort($tmp_data);
        return array_values($tmp_data);
    }

    private static function uConvert($input){
    $maps = array(
        'À' => 'Az1', 'Á' => 'Az2', 'Ả' => 'Az3', 'Ã' => 'Az4', 'Ạ' => 'Az5',
        'Ă' => 'Azz0', 'Ằ' => 'Azz1', 'Ắ' => 'Azz2', 'Ẳ' => 'Azz3', 'Ẵ' => 'Azz4', 'Ặ' => 'Azz5',
        'Â' => 'Azzz0', 'Ầ' => 'Azzz1', 'Ấ' => 'Azzz2', 'Ẩ' => 'Azzz3', 'Ẫ' => 'Azzz4', 'Ậ' => 'Azzz5',
        'à' => 'az1', 'á' => 'az2', 'ả' => 'az3', 'ã' => 'az4', 'ạ' => 'az5',
        'ă' => 'az0', 'ằ' => 'az1', 'ắ' => 'az2', 'ẳ' => 'az3', 'ẵ' => 'az4', 'ặ' => 'az5',
        'â' => 'azz0', 'ầ' => 'azz1', 'ấ' => 'azz2', 'ẩ' => 'azz3', 'ẫ' => 'azz4', 'ậ' => 'azz5',
        'Đ' => 'Dz1',
        'đ' => 'dz1',
        'È' => 'Ez1', 'É' => 'Ez2', 'Ẻ' => 'Ez3', 'Ẽ' => 'Ez4', 'Ẹ' => 'Ez5',
        'Ê' => 'Ezz0', 'Ề' => 'Ezz1', 'Ế' => 'Ezz2', 'Ể' => 'Ezz3', 'Ễ' => 'Ezz4', 'Ệ' => 'Ezz5',
        'è' => 'ez1', 'é' => 'ez2', 'ẻ' => 'ez3', 'ẽ' => 'ez4', 'ẹ' => 'ez5',
        'ê' => 'ezz0', 'ề' => 'ezz1', 'ế' => 'ezz2', 'ể' => 'ezz3', 'ễ' => 'ezz4', 'ệ' => 'ezz5',
        'Ò' => 'Oz1', 'Ó' => 'Oz2', 'Ỏ' => 'Oz3', 'Õ' => 'Oz4', 'Ọ' => 'Oz5',
        'Ô' => 'Ozz0', 'Ồ' => 'Ozz1', 'Ố' => 'Ozz2', 'Ổ' => 'Ozz3', 'Ỗ' => 'Ozz4', 'Ộ' => 'Ozz5',
        'Ơ' => 'Ozzz0', 'Ờ' => 'Ozzz1', 'Ớ' => 'Ozzz2', 'Ở' => 'Ozzz1', 'Ỡ' => 'Ozzz4', 'Ợ' => 'Ozzz5',
        'ò' => 'oz1', 'ó' => 'oz2', 'ỏ' => 'oz3', 'õ' => 'oz4', 'ọ' => 'oz5',
        'ô' => 'ozz0', 'ồ' => 'ozz1', 'ố' => 'ozz2', 'ổ' => 'ozz3', 'ỗ' => 'ozz4', 'ộ' => 'ozz5',
        'ơ' => 'ozzz0', 'ờ' => 'ozzz1', 'ớ' => 'ozzz2', 'ở' => 'ozzz1', 'ỡ' => 'ozzz4', 'ợ' => 'ozzz5',
        'Ù' => 'Uz1', 'Ú' => 'Uz2', 'Ủ' => 'Uz3', 'Ũ' => 'Uz4', 'Ụ' => 'Uz5',
        'Ư' => 'Uzz0', 'Ừ' => 'Uzz1', 'Ứ' => 'Uzz2', 'Ử' => 'Uzz3', 'Ữ' => 'Uzz4', 'Ự' => 'Uzz5',
        'ù' => 'uz1', 'ú' => 'uz2', 'ủ' => 'uz3', 'ũ' => 'uz4', 'ụ' => 'uz5',
        'ư' => 'uzz0', 'ừ' => 'uzz1', 'ứ' => 'uzz2', 'ử' => 'uzz3', 'ữ' => 'uzz4', 'ự' => 'uzz5'
    );

    $keys = array_keys($maps);
    $vals = array_values($maps);
    $output = str_replace($keys, $vals, $input);
    return $output;
}
}
?>