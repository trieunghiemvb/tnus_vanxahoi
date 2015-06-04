<?php
/**
 * App quản lý công thức tính điểm
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class MarkFormulaApp extends AppObject {
    public $app_name="markformula";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Công thức tính điểm môn học";

    private static $table = 'mark_formula';

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

         // Get list formular
        $this->items = $this->getRows(self::$table, '*', 'status = 1', PDO::FETCH_OBJ);
        $this->courses = self::getListCourse();

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
     * Kiểm tra xem khóa học đã tồn tại trong bảng tham số công thức tính điểm hay không
     */
    public static function checkCourse($id_course){
        $db = new Database;
        $condition = array('id_course' => array($id_course));
        $result = $db->getRows(self::$table, 'id', $condition, PDO::FETCH_OBJ);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    public static function updateFormular($data, $id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }
}
?>