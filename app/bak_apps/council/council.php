<?php

/**
 * App quản lý khóa học
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if (!defined('AREA')) {
    die('Access denied');
}

class CouncilApp extends AppObject {

    public $app_name = "council";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "Lập hội đồng bảo vệ Luận văn";

    public function __construct() {

        if (empty($_SESSION["auth"]["id_user"])) {
            header("Location: " . INDEX); /* Redirect browser */
            exit;
        }

        parent::__construct();
    }

    public function display() {
        $task = "";
        if (isset($_REQUEST['task'])) {
            $task = $_REQUEST['task'];
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

    # Lay danh sach lop hoc theo khoa, nganh
    public static function getListClass($course, $group_field = null){
    	$db = new Database;
    	$condition = array('status' => 1, 'id_course' => $course);
    	if ($group_field)
    		$condition['id_group_field'] = $group_field;
    	$result = $db->getRows('class', '*', $condition, PDO::FETCH_OBJ);
    	$data = array();
    	foreach ($result as $item) {
    		$data[$item->id] = $item->class_name;
    	}
    	return $data;
    }

    # Lay danh sach hoc vien theo id_class
    public static function getListStudent($id_class){
    	$db = new Database;
    	$prefix = DB_TABLE_PREFIX;
    	$st = $prefix.'student';
    	$pr = $prefix.'profile';
    	$query = "SELECT pr.first_name, pr.last_name, st.id FROM {$st} st, {$pr} pr WHERE st.id_profile = pr.id AND st.id_class = :id_class AND st.essay_stt = 2";
    	$result = $db->queryAll($query, array(':id_class' => $id_class), PDO::FETCH_OBJ);
    	return $result;
    }

    # Lấy thông tin chi tiet ve hoi dong
    public static function getEssayInfo($id_student){
    	$db = new Database;
    	# Kiem tra xem da co hoc vien nay trong ds hoi dong chua
    	$exists = $db->countRows('essay', '*', 'id_student = :id_student', array(':id_student' => $id_student));
    	if (!$exists)
    		$db->insert('essay', array('id_student' => $id_student));
    	# Lay thong tin
    	$essay = $db->getRow('essay', '*', array('id_student' => $id_student), PDO::FETCH_OBJ);
    	return $essay;
    }
}

?>