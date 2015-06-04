<?php
if ( !defined('AREA') ) {
    die('Access denied');
}

/**
 * App quản lý hồ sơ sinh viên theo lớp
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @since 14/12/2014
 */

class ClassfileApp extends AppObject{
	public $app_name="classfile";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Quản lý hồ sơ học viên";
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

    /**
     * Lấy danh sách Lớp
     * @param int $course 	Id của khoá học
     * @param int $group_field 	Id của ngành
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

    public static function getListProvice(){
        $db = new Database;
        $table = 'provice';
        $data = array();
        $condition = array('status' => array(1));
        $result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

    public static function getClassInfo($id_class){
        $db = new Database;
        $table1 = 'class';
        $columns1 = 'class_name, class_code';
        $table2 = 'course';
        $columns2 = 'course_name, period';
        $on = array('id_course', 'id');
        $where = 'tb1.id = ?';
        $params = array($id_class);
        $result = $db->join2Table($table1, $columns1, $table2, $columns2, $on, $where, $params, PDO::FETCH_OBJ);
        return $result[0];
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

    public static function getStudenByClass($id_class){
        $db = new Database;
        $table1 = 'student';
        $columns1 = 'id_profile, student_code, id_major';
        $table2 = 'profile';
        $columns2 = 'first_name, last_name, sex, birthday, birth_place, email, phone, id_card';
        $on = array('id_profile', 'id');
        $where = 'tb1.status = 1 AND tb2.status = 1 AND tb1.id_class = ? ORDER BY first_name, last_name ASC';
        $params = array($id_class);
        $result = $db->join2Table($table1, $columns1, $table2, $columns2, $on, $where, $params, PDO::FETCH_OBJ);
        return $result;
    }

    public static function updateProfile($data, $id){
        $db = new Database;
        $table = 'profile';
        $condition = array('id' => array($id));
        return $db->update($table, $data, $condition);
    }

    public static function updateStudentInfo($code, $id_major, $id_profile){
        $db = new Database;
        $table = 'student';
        $data = array('student_code' => $code, 'id_major' => $id_major);
        $condition = array('id_profile' => array($id_profile));
        return $db->update($table, $data, $condition);
    }

    public static function addnewProfile($data){
        $db = new Database;
        $table = 'profile';
        $result = $db->insert($table, $data);
        if($result == true)
            return $db->lastId;
        else
            return false;
    }

    public static function insertStudent($data){
        $db = new Database;
        $table = 'student';
        return $db->insert($table, $data);
    }

    /**
     * Lấy danh sách các chuyên ngành theo lớp
     * @param int $id_class Id của lớp
     * @return array Mảng danh sách các chuyên ngành (phụ thuộc ngành của lớp hoc)
     */
    public static function getMajorByClass($id_class){
        $data = array();
        $db = new Database;
        $table1 = 'major';
        $columns1 = 'id, major_name';
        $table2 = 'class';
        $columns2 = 'id_course';
        $on = array('id_group_field', 'id_group_field');
        $where = 'tb1.status = 1 AND tb2.status = 1 AND tb2.id = ?';
        $params = array($id_class);
        $result = $db->join2Table($table1, $columns1, $table2, $columns2, $on, $where, $params, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->major_name;
        }
        return $data;
    }
}
?>