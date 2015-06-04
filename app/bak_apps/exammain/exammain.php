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

class ExamMainApp extends AppObject {

    public $app_name = "ExamMainApp";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "Quản lý đợt thi";
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
    private static $table = 'examination';

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
        ExamMainApp::views();
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
        //$this->items = $this->getRows(self::$table, '*');
		 // Get list Course
        $this->course = self::listCourse();
        $this->examlist = self::listExamList();
        parent::display();
    }
	public static function get_select_key_value($table, $value_field, $text_field, $where = "") {
        $db = new Database;
        $result = array();
        $data = $db->getRows($table, '*', $where);
        foreach ($data as $item) {
            $result[$item[$value_field]] = $item[$text_field];
        }
        return $result;
    }
	public static function getValueByKey($table, $key_field, $where = "") {
        $db = new Database;      
        $values = "";        
        $data = $db->getValue($table, $key_field,$where);        
		$values = $data[$key_field];        
        return $values;        
    }
	/*
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
	/*
     * function trả về danh sách các Đợt thi
     * @author ANVT
     * @return <array> trả về danh sách
     */
    public static function listExamList() {
        $db = new Database;
        $listExamList = array();
        $table = 'examination';
        $condition = array('status' => array('1')); //, 'ORDER BY `id_course` DESC'
        $result = $db->getRows($table, '*',$condition);
        foreach ($result as $item) {
            
            $examplist['id_course'] = $item['id_course'];
            $examplist['course_name'] = ExamMainApp::getValueByKey('course', 'course_name', array('id'=> $item['id_course']));
            $examplist['term'] = $item['term'];
            $examplist['exam_name'] = $item['name'];
			$examplist['id_exam'] = $item['id'];
			array_push($listExamList,$examplist);
        }
		$listExamList = ExamMainApp::subksort($listExamList, 'id_course', false);
        return $listExamList;
    }
	
	/*
	 * Thêm mới đợt thi
     * @author ANVT
     * @return <stting> trả về danh sách
     */
	 public static function addnewExam($data) {
        $db = new Database;
		//$table = 'examination';
        $result = $db->insert(self::$table, $data);
        if ($result == true) {
            return $db->lastId;
        } else {
            return false;
        }
    }
	/**
     * Cập nhật thông tin đợt thi
     * @author ANVT
     * @param  array    $data Mảng dữ liệu với $key là tên cột trong db, $value là dữ liệu cần chỉnh sửa
     * @param  int      $id   Id của khóa học cần chỉnh sửa
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function updateExam($data, $id) {
        $db = new Database;
		//$table = 'examination';
        $conditions = array('id' => array($id));
        return $db->update(self::$table, $data, $conditions);
    }

    /**
     * Xóa thông tin đợt thi
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function deleteExam($id) {
        $db = new Database;
		//$table = 'examination';
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    /**
     * Ẩn thông tin đợt thi
     * @param  int  $id Id của khóa học
     * @return bool     True nếu thành công; False nếu có lỗi
     */
    public static function hideExam($id) {
        $db = new Database;
		//$table = 'examination';
        $conditions = array('id' => array($id));
        return $db->update(self::$table, array('status' => '0'), $conditions);
    }
	/**
	* Sắp xếp giá trị trong mảng theo khóa của mảng con.
	* function to sort an array by the key of his sub-array.
	**/
	public static function subksort(&$array, $subkey, $sort_ascending=false) {
		if (count($array))
			$temp_array[key($array)] = array_shift($array);

		foreach($array as $key => $val){
			$offset = 0;
			$found = false;
			foreach($temp_array as $tmp_key => $tmp_val)
			{
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
				{
					$temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
												array($key => $val),
												array_slice($temp_array,$offset)
											  );
					$found = true;
				}
				$offset++;
			}
			if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
		}

		if ($sort_ascending) $array = array_reverse($temp_array);

		else $array = $temp_array;
		return $array;
	}
}

?>