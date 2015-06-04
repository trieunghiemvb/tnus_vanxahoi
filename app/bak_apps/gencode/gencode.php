<?php
if ( !defined('AREA') ) {
    die('Access denied');
}

/**
 * App đánh mã tự động cho học viên
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @since 18/12/2014
 */

class GencodeApp extends AppObject{
	public $app_name="gencode";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Đánh mã tự động cho học viên";
    public $limitstart;
    public $limit;
    public $total;
    public $where;
    public $numOfPage;
    public $query;
    public $search;
    public $pagination;
    public $loaditem;
    public $course;
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
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";

        // Get list group_field
        $this->courses = self::getListCourse();
        $this->group_fields = self::getListGroupField();
        $this->classes = self::getListClass();
        $this->provices = self::getListProvice();

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
        $result = $db->getRows($table, '*', 'status = 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->course_name;
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

    public static function getListProvice(){
        $db = new Database;
        $table = 'provice';
        $data = array();
        $condition = array('status' => array(1));
        $result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->code] = $item->name;
        }
        return $data;
    }

    /**
     * Lấy danh sách Lớp
     * @param int $course   Id của khoá học
     * @param int $group_field  Id của ngành
     * @return array Danh sách các lớp với $key là id lớp, $value là tên lớp
     */
    public static function getListClass($course = '', $group_field = ''){
        $db = new Database;
        $data = array();
        $table = 'class';
        $condition = array('status' => array('1'));
        if($course > 0 && is_numeric($course))
            $condition['id_course'] = array($course);
        if($group_field > 0 && is_numeric($group_field))
            $condition['id_group_field'] = array($group_field);
        $result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->class_name;
        }
        return $data;
    }

    public static function listStudenByFilter($course, $group_field = '', $havecode = true){
        $db = new Database;
        $table1 = DB_TABLE_PREFIX.'student';
        $table2 = DB_TABLE_PREFIX.'profile';
        $table3 = DB_TABLE_PREFIX.'class';
        $param = array(':id_course' => $course);
        $filter_class = '';
        if($group_field != '' && $group_field != null && $group_field > 0 && is_numeric($group_field)){
            $filter_class = ' AND id_group_field = :id_group_field';
            $param[':id_group_field'] = $group_field;
        }
        $sql = "SELECT * FROM {$table1} JOIN {$table2} ON {$table1}.id_profile = {$table2}.id WHERE id_class IN (SELECT id FROM {$table3} WHERE id_course = :id_course {$filter_class})";
        if (!$havecode) {
            $sql = "SELECT * FROM {$table1} JOIN {$table2} ON {$table1}.id_profile = {$table2}.id WHERE id_class IN (SELECT id FROM {$table3} WHERE id_course = :id_course {$filter_class}) AND student_code = ''";
        }
        $data = $db->queryAll($sql, $param, PDO::FETCH_OBJ);
        return $data;
    }

    public static function getStudenByClass($id_class, $havecode = true){
        $db = new Database;
        $table1 = "student";
        $columns1 = "id_profile, student_code";
        $table2 = "profile";
        $columns2 = "first_name, last_name, sex, birthday, birth_place, email, phone, id_card";
        $on = array('id_profile', 'id');
        $where = "tb1.status = 1 AND tb2.status = 1 AND tb1.id_class = ? ORDER BY first_name, last_name ASC";
        if (!$havecode) {
            $where = "tb1.status = 1 AND tb2.status = 1 AND tb1.id_class = ? AND tb1.student_code = '' ORDER BY first_name, last_name ASC";
        }
        $params = array($id_class);
        $result = $db->join2Table($table1, $columns1, $table2, $columns2, $on, $where, $params, PDO::FETCH_OBJ);
        return $result;
    }

    public static function setStudentCode($id_profile, $code){
        $db = new Database;
        $table = 'student';
        return $db->update($table, array('student_code' => $code), array('id_profile' => array($id_profile)));
    }
}

?>