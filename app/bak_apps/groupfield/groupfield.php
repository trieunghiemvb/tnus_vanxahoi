<?php
/**
 * App quản lý ngành học
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if ( !defined('AREA') ) {
    die('Access denied');
}

?>
<?php
class GroupFieldApp extends AppObject {
    public $app_name="groupfield";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Quản lý ngành học";
    public $items=null;

    private static $table = 'group_field';

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
        GroupFieldApp::views();

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

        // Get list group
		$condition = array('status' => array('1'), 'id' => array('1', '<>'));
        $this->items=$this->getRows(self::$table, '*', $condition);
        parent::display();
    }
	/* public static function checkExistData($data){		
		$this->items=$this->getRow(self::$table, 'id',$data);
		 foreach ($this->items as $item) {
			$items = $item['id'];
		 }
		return $items;
	} */
    /**
     * Thêm mới thông tin ngành học
     * @author Duyld2108
     * @param  array $data Dữ liệu cần thêm vào hệ thống
     * @return False nếu có lỗi; id ngành học nếu thành công
     */
    public static function addnewGroup($data){
        $db = new Database;
        $result = $db->insert(self::$table, $data);
        if ($result == true)
            return $db->lastId;
        else
            return false;
    }

    /**
     * Cập nhật thông tin ngành học
     * @author Duyld2108
     * @param  array    $data Mảng dữ liệu với $key là tên cột trong db, $value là dữ liệu cần chỉnh sửa
     * @param  int      $id   Id của ngành học cần chỉnh sửa
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function updateGroup($data,$id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }

    /**
     * Xóa thông tin ngành học
     * @param  int  $id Id của ngành học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function deleteGroup($id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    /**
     * Ẩn thông tin ngành học
     * @param  int  $id Id của ngành học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function hideGroup($id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->update(self::$table, array('status' => '0'), $conditions);
    }
}
?>