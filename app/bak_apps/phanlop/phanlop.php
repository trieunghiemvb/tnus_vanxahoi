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
class PhanLopApp extends AppObject {
    public $app_name="phanlop";
    public $dir_layout="backend"; // thư mục chứa layout trong skin
    public $page_title = "Phân lớp cho sinh viên";
    public $group_field=null;
    public $major=null;
    public $items=null;
    public $classAjax=null;
    public $classes=null;
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

    private static $table = 'student';

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
        PhanLopApp::views();

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
        // Get list Class Ajax
        $this->classAjax=self::listClassAjax();
        // Get list Class
         $this->classes=self::listClass();
        // Get list Profile
        $this->items=self::listProfile();
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
        $class = 'class';
        $where="";
		$classes = array();
        $condition = array('status' => array('1'));
        if(!empty($id_course) && is_numeric($id_course))
            $condition['id_course'] = array(intval($id_course));
        if(!empty($id_group_field) && is_numeric($id_group_field))
            $condition['id_group_field'] = array(intval($id_group_field));
        //$where=$id_course!=""?$where."id_course LIKE $id_course OR ":$where;
        //$where=$id_group_field!=""?$where."id_group_field LIKE $id_group_field OR ":$where;
        $result = $db->getRows($class, '*',$condition);
	if(is_array($result)){
		foreach ($result as $item) {
			$classes2['id'] = $item['id'];
			$classes2['class_name'] = $item['class_name'];

		}
		if(!in_array($classes2,$classes)){
			array_push($classes,$classes2);
		}	
	}
	return $classes;
    }
/**
     * function trả lại danh sách class
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listClassAjax($id_course="", $id_group_field="") {
        $db = new Database;
        $class = 'class';
        $where="";
		$classAjax = array();
        $condition = array('status' => array('1'));
        if(!empty($id_course) && is_numeric($id_course))
            $condition['id_course'] = array(intval($id_course));
        if(!empty($id_group_field) && is_numeric($id_group_field))
            $condition['id_group_field'] = array(intval($id_group_field));
        //$where=$id_course!=""?$where."id_course LIKE $id_course OR ":$where;
        //$where=$id_group_field!=""?$where."id_group_field LIKE $id_group_field OR ":$where;
        $result = $db->getRows($class, '*',$condition);
		foreach ($result as $item) {
            $classAjax[$item['id']] = $item['class_name'];
        }
		return $classAjax;
    }
    /**
     * function trả lại danh sách Course
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listCourse() {
        $db = new Database;
        $courses = array();
        $course = 'course';
        $condition = array('status' => array('1'));
        $result = $db->getRows($course, '*',$condition);
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
        $tables = 'major';
        $condition = array('status' => array('1'));
        $result = $db->getRows($tables, '*',$condition);
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
        $tables = 'group_field';
        $condition = array('status' => array('1'));
        $result = $db->getRows($tables, '*',$condition);
        foreach ($result as $item) {
            $group_field[$item['id']] = $item['group_field_name'];
        }
        return $group_field;
    }
	
/**
     * function trả lại danh sách profile
     * @author ANVT
     * @return <array> trả về danh sách
     */
    public static function listProfile() {
        $db = new Database;
        $profileAll = array();
        $profile = array();
        $tables = 'profile';
        $student = 'student';
        $province = 'provice';	
        $condition = array('status' => array('1'));
        $result = $db->getRows($tables, '*', $condition);
        $result_student = $db->getRows($student, 'id_profile', $condition);
        foreach ($result as $item) {
			$existClass = false;
			foreach ($result_student as $item_student) {
				$id_check = isset($item_student['id_profile']) ? $item_student['id_profile'] : "0";
				if($item['id'] == $id_check){
					$existClass = true;
					break;
				}				
			}
			if (!$existClass){
				$profile = array();
				$existClass = false;
				$profile['id'] = $item['id'];
				$profile['first_name'] = $item['first_name'];
				$profile['last_name'] = $item['last_name'];
				$profile['birthday'] = $item['birthday'];
				$profile['sex'] = ($item['sex'] == 1) ? 'Nam' : 'Nữ';
				$db2 = new Database;
				$condition = array('status' => array('1'), 'code' => array($item['birth_place']));
       				$result_province = $db2->getRows($province , 'name', $condition);
				 foreach ($result_province as $item_province) {
					$profile['birth_place'] = $item_province['name'];
				}

				$profile['note'] = $item['note'];			
				if(!in_array($profile,$profileAll)){
					array_push($profileAll,$profile);
				}
			}				
		}
        return $profileAll;
    }
	

    /**
     * Thêm mới
     * @author Hieubd
     * @param  array $data Dữ liệu cần thêm vào hệ thống
     * @return False nếu có lỗi; id khóa học nếu thành công
     */
    public static function addnewStudent($data) {
        $db = new Database;
        $result = $db->insert(self::$table, $data);
        if ($result == true)
            //return $db->lastId;
			return $result;
        else
            return false;
    }

   
}
?>