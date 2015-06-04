<?php
/**
 * App in bảng điểm
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class PrintClassSubjectApp extends AppObject {
    public $app_name="printclasssubject";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "In danh sách lớp học phần";
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
        $this->courses = self::getListCourse();
        $this->group_field = self::getListGroupField();

        parent::display();
    }

    /**
     * Lấy danh sách Khoá học
     * @return array Danh sách khoá học với $key là id khoá học, $value là tên khoá học
     */
    public static function getListCourse(){
        $db = new Database;
        $data = array();
        $table = 'course';
        $result = $db->getRows($table, '*', 'status = 1 ORDER BY period DESC', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->course_name;
        }
        return $data;
    }

    public static function getCourseInfo($id_course){
        $db = new Database;
        $data = array();
        $table = 'course';
        $result = $db->getRow($table, '*', array('id' => $id_course), PDO::FETCH_OBJ);
        if (null != $result) {
            list($start, $end) = explode('_', $result->period);
            $i=0;
            while($start < $end){
                $data[$start . '_' . ($start + 1)] = array(++$i, ++$i);
                $start++;
            }
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

    /**
     * Lấy danh sách môn học theo khóa, ngành và học kì
     */
    public static function getListSubject($id_course, $term, $id_group_field = null){
        $db = new Database;
        $data = array();
        $table1 = 'training_plan';
        $column1 = 'id_subject';
        $table2 = 'subject';
        $column2 = 'code, name';
        $join = array('id_subject', 'id');
        $condition = 'tb1.id_course = :id_course AND tb1.term = :term';
        $params = array(':id_course' => $id_course, ':term' => $term);
        if ($id_group_field != null) {
            $condition .= ' AND tb2.id_group_field = :id_group_field';
             $params[':id_group_field'] = $id_group_field;
        }
        $result = $db->join2Table($table1, $column1, $table2, $column2, $join, $condition, $params, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id_subject] = $item->name;
        }
        return $data;
    }

    /**
     * Lay danh sach lop hoc phan theo khoa, ky va mon hoc
     */
    public static function listClassSubject($course, $term, $id_subject){
        $db = new Database;
        $table1 = DB_TABLE_PREFIX.'class_subject';
        $table2 = DB_TABLE_PREFIX.'class_subject_details';
        $query = "SELECT cs.id, cs.name, COUNT(csd.id_student) total FROM {$table1} cs, {$table2} csd WHERE cs.id = csd.id_class_subject AND cs.id_course = :id_course AND cs.term = :term AND cs.id_subject = :id_subject";
        $result = $db->queryAll($query, array(':id_course' => $course, ':term' => $term, ':id_subject' => $id_subject), PDO::FETCH_OBJ);
        return $result;
    }

    /**
     * Lay danh sach lop hoc phan
     * @param [type] $id_classsubject [description]
     */
    public static function ClassSubjectDetail($id_classsubject){
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'profile';
        $table2 = DB_TABLE_PREFIX . 'student';
        $table3 = DB_TABLE_PREFIX . 'class_subject_details';
        $table4 = DB_TABLE_PREFIX . 'class';
        $query = "SELECT tb1.last_name, tb1.first_name, tb2.student_code, tb3.id_student, tb4.class_name FROM $table1 tb1, $table2 tb2, $table3 tb3, $table4 tb4 WHERE tb1.id = tb2.id_profile AND tb2.id = tb3.id_student AND tb2.id_class = tb4.id AND tb3.id_class_subject = :id_classsubject";
        $result = $db->queryAll($query, array(':id_classsubject' => $id_classsubject), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        foreach ($result as $item) {
            $html .= '
            <tr>
                <td><div class="checkbox"><label><input type="checkbox" value="'. $item->id_student .'"></label></div></td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->last_name . ' ' . $item->first_name .'</td>
                <td>'. $item->class_name .'</td>
            </tr>
            ';
        }
        $html = preg_replace('/\s\s+/', '', $html);
        return str_replace(array("\r", "\n"), array("", ""), $html);
    }
}
?>