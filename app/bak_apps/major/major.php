<?php
/**
 * App quản lý chuyên ngành
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

?>
<?php
class MajorApp extends AppObject {
    public $app_name="major";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Quản lý chuyên ngành";
    public $items=null;

    private static $table = 'major';

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
        MajorApp::views();

    }

    /**
     * Lây danh sách các ngành học
     * @author Duyld2108
     * @return array Mảng chứa thông tin các ngành học
     * Index là id ngành học; Value là tên ngành học
     */
    public static function listGroupField(){
        $db = new Database;
        $group_field = array();
        $table = 'group_field';
        $result = $db->getRows($table, '*', 'status = 1 AND id <> 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $group_field[$item->id] = $item->group_field_name;
        }
        return $group_field;
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
        $this->group_field = self::listGroupField();

        // Get list major
        $this->items=$this->getRows(self::$table, '*', 'status = 1', PDO::FETCH_OBJ);
        parent::display();
    }

    /**
     * Thêm mới thông tin chuyên ngành
     * @author Duyld2108
     * @param  array $data Dữ liệu cần thêm vào hệ thống
     * @return False nếu có lỗi; id ngành học nếu thành công
     */
    public static function addnewMajor($data){
        $db = new Database;
        $result = $db->insert(self::$table, $data);
        if ($result == true)
            return $db->lastId;
        else
            return false;
    }

    /**
     * Cập nhật thông tin chuyên ngành
     * @author Duyld2108
     * @param  array    $data Mảng dữ liệu với $key là tên cột trong db, $value là dữ liệu cần chỉnh sửa
     * @param  int      $id   Id của chuyên ngành cần chỉnh sửa
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function updateMajor($data,$id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }

    /**
     * Xóa thông tin chuyên ngành
     * @param  int  $id Id của chuyên ngành
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function deleteMajor($id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    /**
     * Ẩn thông tin chuyên ngành
     * @param  int  $id Id của chuyên ngành
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function hideMajor($id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, array('status' => '0'), $conditions);
    }
}
?>