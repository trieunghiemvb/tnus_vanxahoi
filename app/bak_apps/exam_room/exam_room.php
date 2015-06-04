<?php

/**
 * App chia phòng thi
 * @author Hieubd <buiduchieuvnu@gmail.com>
 * @since 19/03/2015
 * @version 1.0
 */
session_start();
ob_start();
if (!defined('AREA')) {
    die('Access denied');
}
?>
<?php

class Exam_roomApp extends AppObject {

    public $app_name = "exam_room";
    public $dir_layout = "backend";
    public $page_title = "Application chia phòng thi";
    public $arr_course = null;
    public $arr_group_field = null;
    public $arr_major = null;
    public $arr_term = array(1, 2, 3, 4);
    public $arr_year = 0;
    private static $table = 'class';

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
        Exam_roomApp::views();
    }

    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";
        // Bind data to select
        $this->arr_course = self::get_select_table('course', 'id', 'course_name', array('status' => '1'));
        $this->arr_building = self::get_select_table('building', 'id', 'name');
        $this->arr_all_room = self::get_all_room();
        parent::display();
    }

    /**
     * Function trả lại mảng select dữ liệu từ bảng phục vụ cho bind dữ liệu vào select
     * @param <string> $table tên bảng cần lấy dữ liệu. Ex: 'tnus_course'
     * @param <string> $value_field Tên cột chứa giá trị. Ex: 'id'
     * @param <string> $text_field Tên cột chứa text hiển thị. Ex: 'name'
     * @param <array> $where Điều kiện lọc dữ liệu. Ex: $where: array('name'=>'Toán')
     * @return <array> Trả lại dạng mảng 2 chiều có dạng item['id']=name
     * @author HieuBD <buiduchieuvnu@gmail.com>
     */
    public static function get_select_table($table, $value_field, $text_field, $where = "") {
        $db = new Database;
        $result = array();
        $data = $db->getRows($table, '*', $where);
        foreach ($data as $item) {
            $result[$item[$value_field]] = $item[$text_field];
        }
        return $result;
    }

    public static function get_exam_by_id_course($id_course) {
        $db = new Database;
        $table = 'course';
        $where = array('id' => $id_course);
        return $db->getRows($table, "*", $where);
    }

    public static function get_subject_by_id_exam($id_exam) {
        $db = new Database;
        $result = array();
        $sql = "SELECT id, `name` from tnus_subject WHERE id in(select id_subject from tnus_exam_list WHERE id_exam=:id_exam)";
        $arr_subject = $db->queryAll($sql, array(":id_exam" => $id_exam));
        foreach ($arr_subject as $item) {
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }

    public static function get_all_room() {
        $st = "";
        $db = new Database;
        $sql = "SELECT id, `name` from tnus_room;";
        $arr_building_room = $db->queryAll($sql);
        foreach ($arr_building_room as $item) {
            $st .=$item['id'] . ";" . $item['name'] . "#";
        }
        return $st;
    }

    public static function get_building_room($id_building) {
        $db = new Database;
        $result = array();
        $sql = "SELECT id, `name` from tnus_room WHERE id_building=:id_building";
        $arr_building_room = $db->queryAll($sql, array(":id_building" => $id_building));
        foreach ($arr_building_room as $item) {
            $result[$item['id']] = $item['name'];
        }
        return $result;
    }

    public static function get_address($id_building, $id_room) {
        $db = new Database;
        $room_name = $db->getValue("room", 'name', array("id" => $id_room));
        $building_name = $db->getValue("building", 'name', array("id" => $id_building));
//         echo $room_name.", ".$building_name;
        return $room_name['name'] . ", " . $building_name['name'];
    }

    public static function get_exam_list($id_exam, $id_subject) {
        $db = new Database;
        $result = array();
        $sql = "SELECT
                tnus_subject.`name` AS sub_name,
                tnus_examination.`name` AS exam_name,
                tnus_exam_list.id AS id_exam_list
                FROM
                tnus_subject
                INNER JOIN tnus_exam_list ON tnus_exam_list.id_subject = tnus_subject.id
                INNER JOIN tnus_examination ON tnus_exam_list.id_exam = tnus_examination.id
                WHERE
                tnus_subject.id IN (select id_subject from tnus_exam_list) AND
                tnus_exam_list.id_exam = :id_exam AND
                tnus_exam_list.id_subject = :id_subject";
        $arr_result = $db->queryAll($sql, array(":id_exam" => $id_exam, ":id_subject" => $id_subject));
        foreach ($arr_result as $item) {
            $result[$item['id_exam_list']] = $item['exam_name'] . " - " . $item['sub_name'];
        }
        return $result;
    }

    public static function get_exam_info($id_exam_list) {
        $db = new Database;
        $result = array();
        $sql = "SELECT COUNT(id_student) AS count FROM tnus_exam_list_details WHERE id_exam_list= :id_exam_list;";
        $arr_data = $db->query($sql, array(":id_exam_list" => $id_exam_list));
        $result['count'] = $arr_data['count'];
        $sql = "SELECT
                tnus_subject.`name` AS sub_name,
                tnus_examination.`name` AS exam_name,
                tnus_exam_list.id AS id_exam_list,
                tnus_exam_list.`type` AS `type`
                FROM
                tnus_subject
                INNER JOIN tnus_exam_list ON tnus_exam_list.id_subject = tnus_subject.id
                INNER JOIN tnus_examination ON tnus_exam_list.id_exam = tnus_examination.id
                WHERE
                tnus_subject.id IN (select id_subject from tnus_exam_list) AND
                tnus_exam_list.id = :id_exam_list";
        $arr_data = $db->query($sql, array(":id_exam_list" => $id_exam_list));
        $result['exam_name'] = $arr_data['exam_name'];
        $result['sub_name'] = $arr_data['sub_name'];
        $result['type'] = $arr_data['type'];
//        print_r($result);
        return $result;
    }

    // Lấy danh sách sinh viên theo danh sách thi sắp xếp theo thứ tự tên - họ
    private static function get_arr_student($id_exam_list) {
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'exam_list_details';
        $table2 = DB_TABLE_PREFIX . 'student';
        $table3 = DB_TABLE_PREFIX . 'profile';
        $table4 = DB_TABLE_PREFIX . 'exam_list';
        $sql = "SELECT
                $table1.id_student,
                $table1.room,
                $table2.student_code,
                $table3.last_name,
                $table3.first_name,
                $table3.birthday,
                $table1.id_exam_list
                FROM $table4
                INNER JOIN $table1 ON $table1.id_exam_list = $table4.id
                INNER JOIN $table2 ON $table1.id_student = $table2.id
                INNER JOIN $table3 ON $table2.id_profile = $table3.id
                WHERE id_exam_list = :id_exam_list;";
        $arr_result = $db->queryAll($sql, array(":id_exam_list" => $id_exam_list));
        return self::reSort($arr_result, array('first_name', 'last_name'));
    }

    // Lấy danh sách sinh viên trong các phòng thi của danh sách thi
    public static function get_arr_room($id_exam_list) {
        $arr_room = array();
        $arr_list = self::get_arr_student($id_exam_list);
        //print_r($arr_list);
        $max_room = 0;
        for ($i = 0; $i < count($arr_list); $i++) {
            $max_room = $max_room < $arr_list[$i]['room'] ? $arr_list[$i]['room'] : $max_room;
        }
        for ($i = 1; $i <= $max_room; $i++) {
            $start = 0;
            for ($j = 0; $j < count($arr_list); $j++) {
                if ($arr_list[$j]['room'] == $i) {
                    $arr_room[$i][$start] = $arr_list[$j];
                }
                $start++;
            }
        }
        //  print_r($arr_room);
        return $arr_room;
    }

    // Lấy danh sách sinh viên trong các phòng thi
    public static function get_arr_room_list($id_exam_list, $num_room) {
        $arr_list = self::get_arr_student($id_exam_list);
        // print_r($arr_list);
        $arr_room = array();
        $count = count($arr_list);
        $spr = intval($count / $num_room);
        $odd = $count % $num_room;
//        echo "count=$count; spr=$spr; odd=$odd";
        $start = 0;
        for ($i = 1; $i <= $num_room; $i++) {
            $tmp = 0;
            if ($i <= $odd) {
                $end = $start + $spr + 1;
            } else {
                $end = $start + $spr;
            }
//            echo "#end=$end";
            for ($j = $start; $j < $end; $j++) {
                $arr_room[$i][$tmp] = $arr_list[$j];
                $tmp++;
            }
            $start+= $tmp;
        }
//        print_r($arr_room);
        return $arr_room;
    }

    // Hàm lấy danh sách phòng thi
    public static function get_arr_room_detail($id_exam_list, $num_room, $room) {
        $arr_list_room = self::get_arr_room_list($id_exam_list, $num_room);
        return $arr_list_room[$room];
    }

    private static function clear_room_add_details($id_exam_list) {
        $db = new Database();
        return $db->delete("exam_room_details", array('id_exam_list' => $id_exam_list));
    }
    
    private static function save_room_add_details($id_exam_list, $id_room, $room) {
        $db = new Database();
//        return $db->insert('exam_room_details', array('room' => $room, 'id_exam_list' => $id_exam_list, 'id_room' => $id_room));
        if ($id_exam_list == "" && $id_room == "" && $room == "") {
            echo "Null: $id_exam_list, $id_room, $room";
            return false;
        }
        return $db->insert('exam_room_details', array('room' => $room, 'id_exam_list' => $id_exam_list, 'id_room' => $id_room));
    }

    private static function save_room_details($id_exam_list, $id_student, $room) {
        $db = new Database();
        return $db->update('exam_list_details', array('room' => $room), array('id_exam_list' => $id_exam_list, 'id_student' => $id_student));
    }

    public static function save_room($id_exam_list, $arr_data, $arr_add) {
        $arr_student_list = self::get_arr_student($id_exam_list);
        //print_r($arr_student_list);
        $db = new Database;
//        echo "Count=" . count($arr_add);
        $start = 0;
        self::clear_room_add_details($id_exam_list);
        for ($i = 0; $i < count($arr_add); $i++) {
//            echo "Save: $id_exam_list, $arr_add[$i], $i";
            self::save_room_add_details($id_exam_list, $arr_add[$i], $i+1);
        }
        for ($i = 1; $i <= count($arr_data); $i++) {
            for ($j = 0; $j < $arr_data[$i - 1]; $j++) {
                if (!self::save_room_details($id_exam_list, $arr_student_list[$start]['id_student'], $i)) {
                    return false;
                }
                $start++;
            }
        }
        return true;
    }

    // Xắp xếp lại danh sách học viên
    private static function uConvert($input) {
        $maps = array(
            'À' => 'Az1', 'Á' => 'Az2', 'Ả' => 'Az3', 'Ã' => 'Az4', 'Ạ' => 'Az5',
            'Ă' => 'Azz0', 'Ằ' => 'Azz1', 'Ắ' => 'Azz2', 'Ẳ' => 'Azz3', 'Ẵ' => 'Azz4', 'Ặ' => 'Azz5',
            'Â' => 'Azzz0', 'Ầ' => 'Azzz1', 'Ấ' => 'Azzz2', 'Ẩ' => 'Azzz3', 'Ẫ' => 'Azzz4', 'Ậ' => 'Azzz5',
            'à' => 'az1', 'á' => 'az2', 'ả' => 'az3', 'ã' => 'az4', 'ạ' => 'az5',
            'ă' => 'az0', 'ằ' => 'az1', 'ắ' => 'az2', 'ẳ' => 'az3', 'ẵ' => 'az4', 'ặ' => 'az5',
            'â' => 'azz0', 'ầ' => 'azz1', 'ấ' => 'azz2', 'ẩ' => 'azz3', 'ẫ' => 'azz4', 'ậ' => 'azz5',
            'Đ' => 'Dz1', 'đ' => 'dz1',
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

    private static function reSort($array, $field_sort) {
        $arr_result = array();
        $tmp_data = array();
        foreach ($array as $item) {
            $key = '';
            if (is_array($field_sort)) {
                foreach ($field_sort as $_item) {
                    $key .= $item[$_item];
                }
            } else
                $key = $item[$field_sort];
            $key = self::uConvert($key);
//            if (function_exists("uConvert")) {
//                $key = self::uConvert($key);
//            }
            $tmp_data[$key] = $item;
        }
        ksort($tmp_data);
        $i = 0;
        foreach ($tmp_data as $key => $value) {
            $arr_result[$i] = $value;
            $i++;
        }
        return $arr_result;
    }

    // -------------- In danh sách phòng --------------
    public static function get_subject_name($id_exam_list) {
        $db = new Database();
        $table1 = DB_TABLE_PREFIX . 'subject';
        $table2 = DB_TABLE_PREFIX . 'exam_list';
        $sql = "SELECT
                $table1.`name`
                FROM
                tnus_exam_list
                INNER JOIN $table1 ON $table2.id_subject = $table1.id
                WHERE
                $table2.id = $id_exam_list";
        $arr_result = $db->query($sql, array(":id_exam_list" => $id_exam_list));
        return $arr_result['name'];
    }

    public static function get_exam_address($id_exam_list, $num_room) {
        $db = new Database();
        $id_room = $db->getValue("exam_room_details", "id_room", array("id_exam_list" => $id_exam_list, "room" => $num_room));
        return $id_room["id_room"];
    }

}
