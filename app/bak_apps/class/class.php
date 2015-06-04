<?php
/**
 * App quản lý khóa học
 * @author Hieubd <buiduchieuvnu@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if ( !defined('AREA') ) {
    die('Access denied');
}

?>
<?php
class ClassApp extends AppObject {
    public $app_name="class";
    public $dir_layout="backend"; // thư mục chứa layout trong skin
    public $page_title = "Quản lý lớp";
    public $Class;

    private static $table = 'class';

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
        ClassApp::views();

    }

    /**
     * Xuất view
     * @author Hieubd
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";


        // Get list course
        $this->course = self::listCourse();
        // Get list major
        $this->major = self::listMajor();
        // Get list group_field
        $this->group_field = self::listGroup_field();
        // Get list Class
        $this->items=self::listClass();
        //$this->items=$this->getRows(self::$table, '*', 'status = 1');
        parent::display();
    }

    /**
     * function trả lại danh sách class
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listClass($id_course="", $id_group_field="") {
        $db = new Database;
        $table = 'class';
        $where="";
        $condition = array('status' => array('1'));
        if(!empty($id_course) && is_numeric($id_course))
            $condition['id_course'] = array(intval($id_course));
        if(!empty($id_group_field) && is_numeric($id_group_field))
            $condition['id_group_field'] = array(intval($id_group_field));
        //$where=$id_course!=""?$where."id_course LIKE $id_course OR ":$where;
        //$where=$id_group_field!=""?$where."id_group_field LIKE $id_group_field OR ":$where;
        return $db->getRows($table, "*", $condition);
    }

    /**
     * function trả lại danh sách Course
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listCourse() {
        $db = new Database;
        $courses = array();
        $table = 'course';
        $condition = array('status' => array('1'));
        $result = $db->getRows($table, '*', $condition);
        foreach ($result as $item) {
            $courses[$item['id']] = $item['course_name'];
        }
        return $courses;
    }

    /**
     * function trả lại danh sách major
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listMajor() {
        $db = new Database;
        $major = array();
        $table = 'major';
        $condition = array('status' => array('1'));
        $result = $db->getRows($table, '*',$condition);
        if($result) {
            foreach ($result as $item) {
                $major[$item['id']] = $item['major_name'];
            }
            return $major;
        }
        return false;
    }

    /**
     * function trả lại danh sách major
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listGroup_field() {
        $db = new Database;
        $group_field = array();
        $table = 'group_field';
        $condition = array('status' => array('1'), 'id' => array('1', '<>'));
        $result = $db->getRows($table, '*', $condition);
        foreach ($result as $item) {
            $group_field[$item['id']] = $item['group_field_name'];
        }
        return $group_field;
    }

    /**
     * Thêm mới
     * @author Hieubd
     * @param  array $data Dữ liệu cần thêm vào hệ thống
     * @return False nếu có lỗi; id khóa học nếu thành công
     */
    public static function addnewClass($data) {
        $db = new Database;
        $result = $db->insert(self::$table, $data);
        if ($result == true)
            return $db->lastId;
        else
            return false;
    }

    /**
     * Cập nhật thông tin
     * @author Hieubd
     * @param  array    $data Mảng dữ liệu với $key là tên cột trong db, $value là dữ liệu cần chỉnh sửa
     * @param  int      $id   Id của khóa học cần chỉnh sửa
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function updateClass($data,$id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }

    /**
     * Xóa thông tin khóa học
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function deleteClass($id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    /**
     * Ẩn thông tin khóa học
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function hideClass($id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, array('status' => '0'), $conditions);
    }
}
?>