<?php
/**
 * App quản lý chuyên ngành
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class Exam_beatApp extends AppObject {
    public $app_name="exam_beat";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Đánh phách - Chia túi bài chấm";
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
        $this->exams = self::getListExam();
        $this->group_field = self::getListGroupField();

        parent::display();
    }

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

    public static function getListExam($id_course = null){
        $db = new Database;
        $data = array();
        $table = 'examination';
        $condition = array('status' => array(1));
        if ($id_course != null && $id_course > 0)
        	$condition['id_course'] = array($id_course);
        $result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
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

    public static function getSubjectByIdExam($id_exam, $group_field = null){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'subject';
        $table2 = $prefix.'examination';
        $table3 = $prefix.'training_plan';
        $table4 = $prefix.'exam_list';
        $query = "SELECT s.id, s.name FROM {$table1} s, {$table2} e, {$table3} p, {$table4} el WHERE e.id_course = p.id_course AND e.term = p.term AND p.id_subject = s.id AND el.id_exam = e.id AND el.id_subject = s.id AND el.type = 1 AND e.id = :id_exam";
        if ($group_field != null && $group_field > 0)
            $query = "SELECT s.id, s.name FROM {$table1} s, {$table2} e, {$table3} p,  {$table4} el WHERE e.id_course = p.id_course AND e.term = p.term AND p.id_subject = s.id AND el.id_exam = e.id AND el.id_subject = s.id AND el.type = 1 AND e.id = :id_exam AND s.id_group_field = {$group_field}";
        $result = $db->queryAll($query, array(':id_exam' => $id_exam), PDO::FETCH_OBJ);
        $data = array();
        foreach ($result as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }

    public static function getExamInfo($id_exam, $id_subject){
        $db = new Database;
        $exam_list = $db->getRow('exam_list', '*', array('id_exam' => $id_exam, 'id_subject' => $id_subject), PDO::FETCH_OBJ);
        if (count($exam_list) > 0){
        	$table = 'exam_list_details';
            $result = $db->getRow($table, 'SUM(IF(status = 1, 1, 0)) AS valid, SUM(IF(status > 1, 1, 0)) AS invalid', array('id_exam_list' => array($exam_list->id)), PDO::FETCH_OBJ);
            return 'Tổng số thí sinh: ' . ($result->valid + $result->invalid) . ' (Vắng: '. $result->invalid .'). Tổng số bài thi: ' . $result->valid;
        }
        return false;
    }

    public static function getExamListDetail($id_exam, $id_subject, $ishtml = true){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'profile';
        $table2 = $prefix.'student';
        $table3 = $prefix.'exam_list_details';
        $table4 = $prefix.'exam_list';
        $table5 = $prefix.'class';
        $query = "SELECT s.student_code, p.first_name, p.last_name, ed.bag, ed.beat, ed.id_student FROM {$table1} p, {$table2} s, {$table3} ed, {$table4} el, {$table5} cl WHERE p.id = s.id_profile AND s.id = ed.id_student AND ed.id_exam_list = el.id AND s.id_class = cl.id AND ed.status = 1 AND el.id_exam = :id_exam AND el.id_subject = :id_subject";
        $result = $db->queryAll($query, array(':id_exam' => $id_exam, ':id_subject' => $id_subject), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        $i = 0;
        foreach ($result as $item) {
        	$i++;
            $html .= '
            <tr>
                <td>'. $i .'</td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->last_name . ' '. $item->first_name .'</td>
                <td>'. $item->bag . '</td>
                <td>'. $item->beat .'</td>
            </tr>
            ';
        }
        $html = preg_replace('/\s\s+/', '', $html);
        if ($ishtml)
            return str_replace(array("\r", "\n"), array("", ""), $html);
        else
            return $result;
    }

    public static function generateBeat($id_exam, $id_subject, $beat_start, $beat_step, $split_value){
    	$db = new Database;
    	$exam_list = $db->getRow('exam_list', '*', array('id_exam' => $id_exam, 'id_subject' => $id_subject), PDO::FETCH_OBJ);
    	$exam = $db->getRow('examination', '*', array('id' => $id_exam), PDO::FETCH_OBJ);
    	$course = $db->getRow('course', '*', array('id' => $exam->id_course), PDO::FETCH_OBJ);
    	$subject = $db->getRow('subject', '*', array('id' => $id_subject), PDO::FETCH_OBJ);
    	$bag_name = $subject->code . '/' . $course->course_code;
    	// Lấy danh sách học viên - Nhung hoc vien co trang thai binh thuong
    	$prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'profile';
        $table2 = $prefix.'student';
        $table3 = $prefix.'exam_list_details';
        $query = "SELECT p.first_name, p.last_name, s.student_code, ed.id_student FROM {$table1} p, {$table2} s, {$table3} ed WHERE p.id = s.id_profile AND s.id = ed.id_student AND (ed.beat IS NULL OR ed.beat = '') AND ed.id_exam_list = :id_exam_list AND ed.status = :status";
        $result = $db->queryAll($query, array(':id_exam_list' => $exam_list->id, ':status' => 1), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $count = count($result);

        if ($count > 0){
        	$beat_array = array();
	        for ($i=0; $i < $count ; $i++) {
	        	$beat_array[] = $i*$beat_step + $beat_start;
	        }
	        for ($_i=0; $_i < ceil(count($beat_array) / $split_value); $_i++) {
	        	$beats[$_i] = array_slice($beat_array, $_i*$split_value, $split_value);
	        	shuffle($beats[$_i]);
	        }
	    	$students = array();
	    	$j = 0;
	        for ($i=0; $i < $count ; $i++) {
	        	if ($i % $split_value == 0)
	        		$j++;
	        	$k = $i % $split_value;
	        	$student = $result[$i];
	        	$_res = $db->update('exam_list_details', array('beat' => $beats[$j-1][$k], 'bag' => $bag_name . '/' . $j), array('id_exam_list' => $exam_list->id, 'id_student' => $student->id_student));
	        	$students[] = array('name' => $student->first_name . ' ' . $student->last_name, 'code' => $student->student_code, 'id' => $student->id_student, 'beat' => $beats[$j-1][$k], 'bag' => $bag_name . '/' . $j);
	        }

	        $html = '';
	        $stt = 0;
	        foreach ($students as $item) {
	        	$stt++;
	            $html .= '
	            <tr>
	                <td>'. $stt .'</td>
	                <td>'. $item['code'] .'</td>
	                <td>'. $item['code'] .'</td>
	                <td>'. $item['name'] .'</td>
	                <td>'. $item['bag'] . '</td>
	                <td>'. $item['beat'] .'</td>
	            </tr>
	            ';
	        }
	        $html = preg_replace('/\s\s+/', '', $html);

	    	return $html;
        }
        else {
        	return false;
        }
    }

    private static function reSort($array, $field_sort){
        $tmp_data = array();
        foreach ($array as $item) {
            $key = '';
            if (is_array($field_sort)) {
                foreach ($field_sort as $_item) {
                    $key .= $item->$_item;
                }
            }
            else
                $key = $item->$field_sort;

            $key = self::uConvert($key);
            $tmp_data[$key] = $item;
        }
        ksort($tmp_data);
        return array_values($tmp_data);
    }

    private static function uConvert($input){
	    $maps = array(
	        'À' => 'Az1', 'Á' => 'Az2', 'Ả' => 'Az3', 'Ã' => 'Az4', 'Ạ' => 'Az5',
	        'Ă' => 'Azz0', 'Ằ' => 'Azz1', 'Ắ' => 'Azz2', 'Ẳ' => 'Azz3', 'Ẵ' => 'Azz4', 'Ặ' => 'Azz5',
	        'Â' => 'Azzz0', 'Ầ' => 'Azzz1', 'Ấ' => 'Azzz2', 'Ẩ' => 'Azzz3', 'Ẫ' => 'Azzz4', 'Ậ' => 'Azzz5',
	        'à' => 'az1', 'á' => 'az2', 'ả' => 'az3', 'ã' => 'az4', 'ạ' => 'az5',
	        'ă' => 'az0', 'ằ' => 'az1', 'ắ' => 'az2', 'ẳ' => 'az3', 'ẵ' => 'az4', 'ặ' => 'az5',
	        'â' => 'azz0', 'ầ' => 'azz1', 'ấ' => 'azz2', 'ẩ' => 'azz3', 'ẫ' => 'azz4', 'ậ' => 'azz5',
	        'Đ' => 'Dz1',
	        'đ' => 'dz1',
	        'È' => 'Ez1', 'É' => 'Ez2', 'Ẻ' => 'Ez3', 'Ẽ' => 'Ez4', 'Ẹ' => 'Ez5',
	        'Ê' => 'Ezz0', 'Ề' => 'Ezz1', 'Ế' => 'Ezz2', 'Ể' => 'Ezz3', 'Ễ' => 'Ezz4', 'Ệ' => 'Ezz5',
	        'è' => 'ez1', 'é' => 'ez2', 'ẻ' => 'ez3', 'ẽ' => 'ez4', 'ẹ' => 'ez5',
	        'ê' => 'ezz0', 'ề' => 'ezz1', 'ế' => 'ezz2', 'ể' => 'ezz3', 'ễ' => 'ezz4', 'ệ' => 'ezz5',
	        'Ò' => 'Oz1', 'Ó' => 'Oz2', 'Ỏ' => 'Oz3', 'Õ' => 'Oz4', 'Ọ' => 'Oz5',
	        'Ô' => 'Ozz0', 'Ồ' => 'Ozz1', 'Ố' => 'Ozz2', 'Ổ' => 'Ozz3', 'Ỗ' => 'Ozz4', 'Ộ' => 'Ozz5',
	        'Ơ' => 'Ozzz0', 'Ờ' => 'Ozzz1', 'Ớ' => 'Ozzz2', 'Ở' => 'Ozzz1', 'Ỡ' => 'Ozzz4', 'Ợ' => 'Ozzz5',
	        'ò' => 'oz1', 'ó' => 'oz2', 'ỏ' => 'oz3', 'õ' => 'oz4', 'ọ' => 'oz5',
	        'ô' => 'ozz0', 'ồ' => 'ozz1', 'ố' => 'ozz2', 'ổ' => 'ozz3', 'ỗ' => 'ozz4', 'ộ' => 'ozz5',
	        'ơ' => 'ozzz0', 'ờ' => 'ozzz1', 'ớ' => 'ozzz2', 'ở' => 'ozzz1', 'ỡ' => 'ozzz4', 'ợ' => 'ozzz5',
	        'Ù' => 'Uz1', 'Ú' => 'Uz2', 'Ủ' => 'Uz3', 'Ũ' => 'Uz4', 'Ụ' => 'Uz5',
	        'Ư' => 'Uzz0', 'Ừ' => 'Uzz1', 'Ứ' => 'Uzz2', 'Ử' => 'Uzz3', 'Ữ' => 'Uzz4', 'Ự' => 'Uzz5',
	        'ù' => 'uz1', 'ú' => 'uz2', 'ủ' => 'uz3', 'ũ' => 'uz4', 'ụ' => 'uz5',
	        'ư' => 'uzz0', 'ừ' => 'uzz1', 'ứ' => 'uzz2', 'ử' => 'uzz3', 'ữ' => 'uzz4', 'ự' => 'uzz5'
	    );

	    $keys = array_keys($maps);
	    $vals = array_values($maps);
	    $output = str_replace($keys, $vals, $input);
	    return $output;
	}
}
?>