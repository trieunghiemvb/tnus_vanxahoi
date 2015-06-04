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
class Transfer_classApp extends AppObject {
    public $app_name="transfer_class";
    public $dir_layout="backend"; // thư mục chứa layout trong skin
    public $page_title = "Chuyển lớp học viên";
    public $group_field=null;
    public $class=null;
    public $items=null;
    public $limitstart;
    public $limit;
    public $total;
    public $where;
    public $numOfPage;
    public $query;
    public $search;
    public $pagination;
    public $loaditem;
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
        Transfer_classApp::views();

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
        // Get list group_field
        $this->group_field = self::listGroup_field();
        // Get list Class
        $this->class=self::listClass();
        //$this->items=$this->getRows(self::$table, '*', 'status = 1');
        parent::display();
    }

    /**
     * fucntion trả về danh sách lớp lọc theo id_course và id_group_field
     * @param <int> $id_course mặc định = ""
     * @param <int> $id_group_field mặc định =""
     * @return <array> mảng chứa các lớp đã lọc
     */
    public static function listClass($id_course="", $id_group_field="") {
        $db = new Database;
        $class = array();
        $table = 'class';
        $condition = array('status' => array('1'));
        if(!empty($id_course) && is_numeric($id_course))
            $condition['id_course'] = array(intval($id_course));
        if(!empty($id_group_field) && is_numeric($id_group_field))
            $condition['id_group_field'] = array(intval($id_group_field));
        $class = $db->getRows($table, '*',$condition);
        return $class;
    }

    /**
     * function lấy danh sách sinh viên theo id_class
     * @param <int> $id_class
     * @return <array> trả về danh sách sinh viên theo id_class
     * @author HieuBD
     */
    public static function getStudents($id_class="") {
        $db = new Database;
        $students = array();
        $table = 'student';
        $condition = array('status' => array('1'));
        if(!empty($id_class) && is_numeric($id_class))
            $condition['id_class'] = array(intval($id_class));
        $students = $db->getRows($table, '*',$condition);
        //print_r($students);
        return $students;
    }

    public static function getProfile($id="") {
        $db = new Database;
        $profiles = array();
        $table = 'profile';
        $condition = array('status' => array('1'));
        if(!empty($id) && is_numeric($id))
            $condition = array('id' => array(intval($id)));
        $profiles = $db->getRow($table, '*',$condition,PDO::FETCH_OBJ);
        return $profiles;
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
        $result = $db->getRows($table, '*',$condition);
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
    public static function listGroup_field() {
        $db = new Database;
        $group_field = array();
        $table = 'group_field';
        $condition = array('status' => array('1'));
        $result = $db->getRows($table, '*',$condition);
        foreach ($result as $item) {
            $group_field[$item['id']] = $item['group_field_name'];
        }
        return $group_field;
    }

    
    public static function transferClass($arr_students,$id_class) {
        $db = new Database;
        $table="student";
//        print_r($arr_students);
        foreach ($arr_students as $student) {
            $data=array("id_class"=>$id_class);
            $where=array("id_profile"=>array($student));
            $db->update($table, $data, $where);
        }
        return true;
    }
}
?>