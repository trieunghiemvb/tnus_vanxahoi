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
?>
<?php

class CourseApp extends AppObject {

    public $app_name = "course";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "Quản lý khóa học";
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
    public $items = null;
    private static $table = 'course';

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
        CourseApp::views();
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

        // Get list course
        $this->items = $this->getRows(self::$table, '*');
        parent::display();
    }

    /**
     * Thêm mới thông tin khóa học
     * @author Duyld2108
     * @param  array $data Dữ liệu cần thêm vào hệ thống
     * @return False nếu có lỗi; id khóa học nếu thành công
     */
    public static function addnewCourse($data) {
        $db = new Database;
        $result = $db->insert(self::$table, $data);
        if ($result == true) {
            return $db->lastId;
        } else {
            return false;
        }
    }

    /**
     * Cập nhật thông tin khóa học
     * @author Duyld2108
     * @param  array    $data Mảng dữ liệu với $key là tên cột trong db, $value là dữ liệu cần chỉnh sửa
     * @param  int      $id   Id của khóa học cần chỉnh sửa
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function updateCourse($data, $id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }

    /**
     * Xóa thông tin khóa học
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function deleteCourse($id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    /**
     * Ẩn thông tin khóa học
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function hideCourse($id) {
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, array('status' => '0'), $conditions);
    }

    /**
     * Thêm công thức tính điểm cho khóa học, lấy công thức của khóa trước đó nạp vào khóa hiện tại
     * @param  [type] $id_course [description]
     */
    public static function insertFormular($id_course) {
        $db = new Database;
        // Lấy id_course khóa gần nhất
        $sql = "SELECT max(id_course) AS last_course FROM tnus_mark_formula WHERE id_course < :id_course ";
        $parram = array(":id_course" => $id_course);
        $result = $db->query($sql, $parram);
        $_mk = $db->getRow("mark_formula", "*", array("id_course" => $result["last_course"]));
        $data = array(
            "id_course" => $id_course,
            "element_percent" => $_mk["element_percent"],
            "test_percent" => $_mk["test_percent"],
            "essay_mark" => $_mk["essay_mark"]);
        $table = 'mark_formula';
        return $db->insert($table, $data);
    }
}

?>