<?php
/**
 * App in bảng điểm
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class PrintMarkApp extends AppObject {
    public $app_name="printmark";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "In bảng điểm";
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

    public static function getClasses($id_course, $id_group_field){
    	$db = new Database;
    	$table = 'class';
    	$condition = array('status' => 1, 'id_course' => $id_course);
    	if ($id_group_field > 0){
    		$condition['id_group_field'] = $id_group_field;
    	}
    	$result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
    	$html = '<option value="">-- Tất cả --</option>';
    	foreach ($result as $item) {
    		$html .= '<option value="'.$item->id.'" class="class_options">'.$item->class_name.'</option>';
    	}
    	return $html;
    }

    public static function courseInfo($id_course){
    	$db = new Database;
    	$table = 'course';
    	$result = $db->getRow($table, '*', array('status' => 1, 'id' => $id_course), PDO::FETCH_OBJ);
    	list($start_year, $end_year) = explode('_', $result->period);
    	$years = '<option value="">-- Tất cả --</option>';
    	$terms = '<option value="">-- Tất cả --</option>';
    	$i = 1;
		while ($start_year + 1 <= $end_year){
			$year = $start_year."_".($start_year + 1);
			$years .= '<option value="'.$year.'">'.$year.'</option>';
			$terms .= '<option value="'.$i.'" data-year="'.$year.'">'.$i.'</option>';
			$i++;
			$terms .= '<option value="'.$i.'" data-year="'.$year.'">'.$i.'</option>';
			$i++;
			$start_year++;
		}
		return array($years, $terms);
    }

    public static function getMajors($id_group_field){
    	$db = new Database;
    	$table = 'major';
    	$result = $db->getRows($table, '*', array('status' => 1, 'id_group_field' => $id_group_field), PDO::FETCH_OBJ);
    	$html = '<option value="">-- Tất cả --</option>';
    	foreach ($result as $item) {
    		$html .= '<option value="'.$item->id.'" class="class_options">'.$item->major_name.'</option>';
    	}
    	return $html;
    }

    public static function getListStudent($id_class){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $table1 = $prefix.'profile';
        $table2 = $prefix.'student';
        $table3 = $prefix.'major';
        $query = "SELECT p.last_name, p.first_name, p.birthday, s.id, s.student_code, m.major_name FROM {$table1} p, {$table2} s, {$table3} m WHERE s.id_profile = p.id AND s.id_major = m.id AND s.id_class = :id_class";
        $result = $db->queryAll($query, array(':id_class' => $id_class), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        $i = 1;
        foreach ($result as $item) {
            $html .= '
                <tr>
                    <td class="text-center">'.$i.'</td>
                    <td class="text-center">'.$item->student_code.'</td>
                    <td>'.$item->last_name.'</td>
                    <td>'.$item->first_name.'</td>
                    <td class="text-center">'.$item->birthday.'</td>
                    <td>'.$item->major_name.'</td>
                    <td class="text-center">
                        <div class="checkbox">
                            <label><input type="checkbox" class="print_student" name="" value="'.$item->id.'"></label>
                        </div>
                    </td>
                </tr>
            ';
            $i++;
        }
        return $html;
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