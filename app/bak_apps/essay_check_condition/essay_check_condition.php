<?php

/**
 * App chia phòng thi
 * @author Hieubd <buiduchieuvnu@gmail.com>
 * @since 25/03/2015
 * @version 1.0
 */
session_start();
ob_start();
if (!defined('AREA')) {
    die('Access denied');
}
?>
<?php

class Essay_check_conditionApp extends AppObject {

    public $app_name = "essay_check_condition";
    public $dir_layout = "backend";
    public $page_title = "Xét điều kiện bảo vệ luận văn";

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
        self::views();
    }

    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";
        // Bind data to select
        $this->arr_course = self::get_select_table('course', 'id', 'course_name', array('status' => '1'));
        $this->arr_goup_field = self::get_select_table('group_field', 'id', 'group_field_name');
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

    // Lấy danh sách sinh viên trong lớp
    public static function get_arr_student($id_class) {
        $db = new Database();
        $table1 = DB_TABLE_PREFIX . 'student';
        $table2 = DB_TABLE_PREFIX . 'profile';
        $table3 = DB_TABLE_PREFIX . 'major';
        $sql = "SELECT
                $table1.student_code,
                $table2.id,
                $table2.first_name,
                $table2.last_name,
                $table2.birthday,
                $table3.major_name,
                $table1.id,
                $table1.essay_stt
                FROM
                tnus_class
                INNER JOIN $table1 ON $table1.id_class = tnus_class.id
                INNER JOIN $table2 ON $table1.id_profile = $table2.id
                INNER JOIN $table3 ON $table1.id_major = $table3.id
                WHERE
                tnus_class.id = :id_class";
        $arr_data = $db->queryAll($sql, array(":id_class" => $id_class));
        return self::reSort($arr_data, array('first_name', 'last_name'));
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

//    ------------- Kiểm tra điều kiện bảo vệ luận văn ------------------
    private static function get_arr_training_plan($id_course, $id_major) {
        $db = new Database();
        $table1 = DB_TABLE_PREFIX . 'training_plan';
        $sql = "SELECT * FROM $table1
                WHERE $table1.id_course = :id_course AND $table1.id_major = :id_major ";
        return $db->queryAll($sql, array(":id_course" => $id_course, ":id_major" => $id_major));
    }

    // Lấy danh sách các học phần của sinh viên
    private static function get_arr_student_subject($id_student) {
        $db = new Database();
        $table1 = DB_TABLE_PREFIX . 'class_subject_details';
        $table2 = DB_TABLE_PREFIX . 'class_subject';
        $sql = "SELECT
                    $table1.id_student,$table1.mark_sumary,$table1.count,
                    $table2.id AS id_class_subject,$table2.id_subject 
                    FROM $table1 INNER JOIN $table2 ON $table1.id_class_subject = $table2.id
                    WHERE $table1.id_student = :id_student ";

        return $db->queryAll($sql, array(":id_student" => $id_student));
    }

    // Lấy thông tin khóa học và chuyên ngành của sinh viên
    private static function get_student_course_major($id_student) {
        $arr_rs = array();
        $db = new Database();
        $table1 = DB_TABLE_PREFIX . 'student';
        $table2 = DB_TABLE_PREFIX . 'class';
        $sql = "SELECT $table1.*, $table2.id_course
                 FROM $table1
                 INNER JOIN $table2 ON $table1.id_class = $table2.id
                 WHERE
                 $table1.id = :id_student";
        $arr_data = $db->queryAll($sql, array(":id_student" => $id_student));
        foreach ($arr_data as $value) {
            $arr_rs["id_student"] = $value["id"];
            $arr_rs["id_course"] = $value["id_course"];
            $arr_rs["id_major"] = $value["id_major"];
        }
        return $arr_rs;
    }

//Kiểm tra điều kiện bảo vệ luận văn
    public static function check_condition_essay($id_student) {
//        echo "ID: $id_student";
        $arr_rs = array("value" => 1, "messeage" => "Đủ điều kiện bảo vệ luận văn.");
        $arr_st_c_m = self::get_student_course_major($id_student);
        $arr_training_plan = self::get_arr_training_plan($arr_st_c_m['id_course'], $arr_st_c_m['id_major']);
        $arr_subject = self::get_arr_student_subject($id_student);

//        print_r($arr_st_c_m);
        //return $arr_st_c_m;
//        return $arr_subject;
//        return $arr_training_plan;
        foreach ($arr_training_plan as $value1) {
            $id_subject = $value1["id_subject"];
//            echo "<br/> $id_subject";
            $found_sub = false;
            foreach ($arr_subject as $value2) {
//                echo $id_subject . " vs " . $value2["id_subject"] . " <br/>";
                if ($value2["id_subject"] == $id_subject) {
//                    echo "found!";
                    $found_sub = true;
                }
                if (doubleval($value2["mark_sumary"] < 5.0)) {
                    //echo "# Điểm: " . doubleval($value2["mark_sumary"]);
                    return array("value" => 0, "messeage" => "Điểm trung bình môn < 5.0. (Môn: $id_subject)");
                }
            }
            if (!$found_sub) {
                return array("value" => 0, "messeage" => "Chưa hoàn thành kế hoạch đào tạo. Môn: $id_subject");
            }
        }
        return array("value" => 1, "messeage" => "Đủ điều kiện bảo vệ luận văn.");
    }
    // Lưu trạng thái điều kiện luận văn
    public static function save_essay_status($id_student){
        $db = new Database();
        $arr_status = self::check_condition_essay($id_student);
        if($arr_status["value"]){
            $db->update("student", array("essay_stt"=>2), "id=$id_student");
        }else{
            $db->update("student", array("essay_stt"=>1), "id=$id_student");
        }
        
    }
}
