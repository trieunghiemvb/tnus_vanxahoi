<?php

/**
 * App kiểm tra function
 * @author HieuBD <buiduchieuvnu@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if (!defined('AREA')) {
    die('Access denied');
}
?>
<?php

class TestApp extends AppObject {

    public $app_name = "test";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "App test";
    public $testCotent = "";

    public function __construct() {
        parent::__construct();
    }

    public function display() {
        $task = "";
        if (isset($_REQUEST['task'])) {
            $task = $_REQUEST['task'];
        }
        TestApp::views();
    }

    /**
     * Xuất view
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";
//        $this->testCotent = TestApp::get_arr_student_by_class(1);
//        $this->testCotent = TestApp::get_arr_subject_by_student(1);
//        $this->testCotent = TestApp::get_curriculum_calculate(1, 1);
//        $this->testCotent = TestApp::get_arr_subject_by_student(1);
//        $this->testCotent = TestApp::get_mark_final(1);
        $this->testCotent = TestApp::save_room_details(1, 1,1);
        parent::display();
    }
    
    private static function save_room_details($id_exam_list, $id_room, $room) {
        $db = new Database();
        $db->delete("exam_room_details", array('id_exam_list' => $id_exam_list, 'room' => $room));
        return $db->insert('exam_room_details', array('room' => $room, 'id_exam_list' => $id_exam_list, 'id_room' => $id_room));
    }
    
    public static function get_address($id_exam_list, $num_room){
        $db = new Database();
        $id_room = $db->getValue("exam_room_details", "id_room", array("id_exam_list"=>$id_exam_list,"room"=>$num_room));
        return $id_room["id_room"];
    }

    public static function inherit_plan($id_course, $id_major, $id_course_in, $id_major_in) {
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . "training_plan";
        $sql = "SELECT * FROM $table1 WHERE id_course=:id_course AND id_major=:id_major";
        $arr_plan = $db->queryAll($sql, array(":id_course" => $id_course, ":id_major" => $id_major));
        $arr_plan_in = $db->queryAll($sql, array(":id_course" => $id_course_in, ":id_major" => $id_major_in));
//        print_r($arr_plan_in);
        $sql = "";
        if (empty($arr_plan_in)) {
            foreach ($arr_plan as $key => $item) {
                $sql .= " INSERT INTO `$table1`(`id_course`, `id_major`, `term`, `id_subject`, `curriculum`, `caculate`, `mark_formula`) "
                        . " VALUES ($id_course_in,$id_major_in," . $item['term'] . "," . $item['id_subject'] . "," . $item['curriculum'] . "," . $item['caculate'] . ", '" . $item['mark_formula'] . "' ); ";
            }
        }
        if ($sql != "") {
            $db->query($sql);
            return "success";
        }
        return "false";
    }

//    App: calculate_mark 
//    Test case: Tính điểm tổng kết  
    /**
     * Hàm trả lại danh sách sinh viên trong lớp hành chính
     * @param int $id_class id lớp hành chính
     * @return mảng 2 chiều tất cả thông tin sinh viên
     * Tested
     */
    private static function get_arr_student_by_class($id_class) {
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'student';
        $table2 = DB_TABLE_PREFIX . 'profile';
        $table3 = DB_TABLE_PREFIX . 'major';
        $sql = "SELECT
                $table1.id,
                $table1.student_code,
                $table2.last_name,
                $table2.first_name,
                $table2.birthday,
                $table3.major_name
                FROM
                $table1
                INNER JOIN $table2 ON $table1.id_profile = $table2.id
                INNER JOIN $table3 ON $table1.id_major = $table3.id
                WHERE
                $table1.id_class = :id_class ";
        $arr_data = $db->queryAll($sql, array(":id_class" => $id_class));
        return self::reSort($arr_data, array('first_name', 'last_name'));
    }

    /**
     * Hàm trả về danh sách môn học của sinh viên, đã loại môn trùng, môn không tính điểm
     * @param type $id_student
     * @return type
     * Tested
     */
    private static function get_arr_subject_by_student($id_student) {
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'class_subject_details';
        $table2 = DB_TABLE_PREFIX . 'class_subject';
        $sql = "SELECT
                $table1.id_student,
                $table2.id_subject,
                $table1.count,
                $table1.mark_sumary
                FROM
                $table1
                INNER JOIN $table2 ON $table1.id_class_subject = $table2.id
                WHERE
                $table1.id_student = :id_student";
        $arr_subject = $db->queryAll($sql, array(":id_student" => $id_student));
//        return $arr_subject;
        for ($i = 0; $i < count($arr_subject); $i++) {
            for ($j = $i + 1; $j < count($arr_subject) - 1; $j++) {
                // Trùng môn + sv => giữ lại lần học cuối
                if ($arr_subject[$i]["id_student"] == $arr_subject[$j]["id_student"] && $arr_subject[$i]["id_subject"] == $arr_subject[$j]["id_subject"]) {
                    if ($arr_subject[$i]["count"] > $arr_subject[$j]["count"]) {
                        unset($arr_subject[$j]);
                    } else {
                        unset($arr_subject[$i]);
                    }
                }
            }
        }
//        return $arr_subject;
        $arr_st_subject = array();
        $i = 0;
        foreach ($arr_subject as $value) {
            $arr_cc = self::get_curriculum_calculate($id_student, $value["id_subject"]);
            // Loại bỏ trường hợp môn không nằm trong kế hoạch đào tạo, không tính điểm
            if ($arr_cc["curriculum"] != null && $arr_cc["caculate"] != null && $arr_cc["caculate"] != 0) {
                $arr_st_subject[$i]["id_student"] = $value["id_student"];
                $arr_st_subject[$i]["id_subject"] = $value["id_subject"];
                $arr_st_subject[$i]["count"] = $value["count"];
                $arr_st_subject[$i]["mark_sumary"] = $value["mark_sumary"];
                $arr_st_subject[$i]["curriculum"] = $arr_cc["curriculum"];
                $arr_st_subject[$i]["caculate"] = $arr_cc["caculate"];
            }
            $i++;
        }
        return $arr_st_subject;
    }

    /**
     * Hàm trả về số tín chỉ và tính điểm của sinh viên, môn học
     * @param type $id_student
     * @param type $id_subject
     * @return type
     * Tested
     */
    private static function get_curriculum_calculate($id_student, $id_subject) {
        $db = new Database;
        $arr_cm = self::get_student_course_major($id_student);
        $table1 = DB_TABLE_PREFIX . 'training_plan';
        $sql = "SELECT
                $table1.curriculum,
                $table1.caculate
                FROM
                $table1
                WHERE
                $table1.id_subject = :id_subject AND
                $table1.id_course = :id_course AND
                $table1.id_major = :id_major ";
        $arr_cur_cal = $db->query($sql, array(":id_subject" => $id_subject, ":id_course" => $arr_cm["id_course"], ":id_major" => $arr_cm["id_major"]));
        return $arr_cur_cal;
    }

    /**
     * Hàm trả về điểm tổng kết của sinh viên
     * @param type $id_student
     * @return type
     * Tested
     */
    public static function get_mark_final($id_student) {
        $arr_st_subject = self::get_arr_subject_by_student($id_student);
        $total_cur = 0;
        $total_mark = 0;
        foreach ($arr_st_subject as $value) {
            $total_cur += intval($value["curriculum"]);
            $total_mark += floatval($value["mark_sumary"]) * intval($value["curriculum"]);
//            echo "<p>" . $value["curriculum"] . " | " . $value["mark_sumary"] . "</p>";
        }
//        echo "<p>" . $total_cur . " | " . $total_mark . "</p>";
        return round($total_mark / $total_cur, 2);
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

//    #App: essay_check_condition 
//    Test case: kiểm tra điều kiện bảo vệ luận văn
//    private static function get_arr_training_plan($id_course, $id_major) {
//        $db = new Database();
//        $table1 = DB_TABLE_PREFIX . 'training_plan';
//        $sql = "SELECT * FROM $table1
//                WHERE $table1.id_course = :id_course AND $table1.id_major = :id_major ";
//        return $db->queryAll($sql, array(":id_course" => $id_course, ":id_major" => $id_major));
//    }
//
//    // Lấy danh sách các học phần của sinh viên
//    private static function get_arr_student_subject($id_student) {
//        $db = new Database();
//        $table1 = DB_TABLE_PREFIX . 'class_subject_details';
//        $table2 = DB_TABLE_PREFIX . 'class_subject';
//        $sql = "SELECT
//                    $table1.id_student,$table1.mark_sumary,$table1.count,
//                    $table2.id AS id_class_subject,$table2.id_subject 
//                    FROM $table1 INNER JOIN $table2 ON $table1.id_class_subject = $table2.id
//                    WHERE $table1.id_student = :id_student ";
//
//        return $db->queryAll($sql, array(":id_student" => $id_student));
//    }
//
//    // Lấy thông tin khóa học và chuyên ngành của sinh viên
//    private static function get_student_course_major($id_student) {
//        $arr_rs = array();
//        $db = new Database();
//        $table1 = DB_TABLE_PREFIX . 'student';
//        $table2 = DB_TABLE_PREFIX . 'class';
//        $sql = "SELECT $table1.*, $table2.id_course
//                 FROM $table1
//                 INNER JOIN $table2 ON $table1.id_class = $table2.id
//                 WHERE
//                 $table1.id = :id_student";
//        $arr_data = $db->queryAll($sql, array(":id_student" => $id_student));
//        foreach ($arr_data as $value) {
//            $arr_rs["id_student"] = $value["id"];
//            $arr_rs["id_course"] = $value["id_course"];
//            $arr_rs["id_major"] = $value["id_major"];
//        }
//        return $arr_rs;
//    }
//
////Kiểm tra điều kiện bảo vệ luận văn
//    public static function check_condition_essay($id_student) {
//        $arr_rs = array("value" => 1, "messeage" => "Đủ điều kiện bảo vệ luận văn.");
//        $arr_st_c_m = self::get_student_course_major($id_student);
//        $arr_training_plan = self::get_arr_training_plan($arr_st_c_m['id_course'], $arr_st_c_m['id_major']);
//        $arr_subject = self::get_arr_student_subject($id_student);
//        //return $arr_st_c_m;
////        return $arr_subject;
////        return $arr_training_plan;
//        foreach ($arr_training_plan as $value1) {
//            $id_subject = $value1["id_subject"];
////            echo "<br/> $id_subject";
//            $found_sub = false;
//            foreach ($arr_subject as $value2) {
////                echo $id_subject . " vs " . $value2["id_subject"] . " <br/>";
//                if ($value2["id_subject"] == $id_subject) {
////                    echo "found!";
//                    $found_sub = true;
//                }
//                if (doubleval($value2["mark_sumary"] < 5.0)) {
//                    echo "# Điểm: " . doubleval($value2["mark_sumary"]);
//                    return array("value" => 0, "messeage" => "Điểm trung bình môn < 5.0. (Môn: $id_subject)");
//                }
//            }
//            if (!$found_sub) {
//                return array("value" => 0, "messeage" => "Chưa hoàn thành kế hoạch đào tạo. Môn: $id_subject");
//            }
//        }
//
//        return array("value" => 1, "messeage" => "Đủ điều kiện bảo vệ luận văn.");
//    }
//
//    // Xắp xếp lại danh sách học viên
//    private static function uConvert($input) {
//        $maps = array(
//            'À' => 'Az1', 'Á' => 'Az2', 'Ả' => 'Az3', 'Ã' => 'Az4', 'Ạ' => 'Az5',
//            'Ă' => 'Azz0', 'Ằ' => 'Azz1', 'Ắ' => 'Azz2', 'Ẳ' => 'Azz3', 'Ẵ' => 'Azz4', 'Ặ' => 'Azz5',
//            'Â' => 'Azzz0', 'Ầ' => 'Azzz1', 'Ấ' => 'Azzz2', 'Ẩ' => 'Azzz3', 'Ẫ' => 'Azzz4', 'Ậ' => 'Azzz5',
//            'à' => 'az1', 'á' => 'az2', 'ả' => 'az3', 'ã' => 'az4', 'ạ' => 'az5',
//            'ă' => 'az0', 'ằ' => 'az1', 'ắ' => 'az2', 'ẳ' => 'az3', 'ẵ' => 'az4', 'ặ' => 'az5',
//            'â' => 'azz0', 'ầ' => 'azz1', 'ấ' => 'azz2', 'ẩ' => 'azz3', 'ẫ' => 'azz4', 'ậ' => 'azz5',
//            'Đ' => 'Dz1', 'đ' => 'dz1',
//            'È' => 'Ez1', 'É' => 'Ez2', 'Ẻ' => 'Ez3', 'Ẽ' => 'Ez4', 'Ẹ' => 'Ez5',
//            'Ê' => 'Ezz0', 'Ề' => 'Ezz1', 'Ế' => 'Ezz2', 'Ể' => 'Ezz3', 'Ễ' => 'Ezz4', 'Ệ' => 'Ezz5',
//            'è' => 'ez1', 'é' => 'ez2', 'ẻ' => 'ez3', 'ẽ' => 'ez4', 'ẹ' => 'ez5',
//            'ê' => 'ezz0', 'ề' => 'ezz1', 'ế' => 'ezz2', 'ể' => 'ezz3', 'ễ' => 'ezz4', 'ệ' => 'ezz5',
//            'Ò' => 'Oz1', 'Ó' => 'Oz2', 'Ỏ' => 'Oz3', 'Õ' => 'Oz4', 'Ọ' => 'Oz5',
//            'Ô' => 'Ozz0', 'Ồ' => 'Ozz1', 'Ố' => 'Ozz2', 'Ổ' => 'Ozz3', 'Ỗ' => 'Ozz4', 'Ộ' => 'Ozz5',
//            'Ơ' => 'Ozzz0', 'Ờ' => 'Ozzz1', 'Ớ' => 'Ozzz2', 'Ở' => 'Ozzz1', 'Ỡ' => 'Ozzz4', 'Ợ' => 'Ozzz5',
//            'ò' => 'oz1', 'ó' => 'oz2', 'ỏ' => 'oz3', 'õ' => 'oz4', 'ọ' => 'oz5',
//            'ô' => 'ozz0', 'ồ' => 'ozz1', 'ố' => 'ozz2', 'ổ' => 'ozz3', 'ỗ' => 'ozz4', 'ộ' => 'ozz5',
//            'ơ' => 'ozzz0', 'ờ' => 'ozzz1', 'ớ' => 'ozzz2', 'ở' => 'ozzz1', 'ỡ' => 'ozzz4', 'ợ' => 'ozzz5',
//            'Ù' => 'Uz1', 'Ú' => 'Uz2', 'Ủ' => 'Uz3', 'Ũ' => 'Uz4', 'Ụ' => 'Uz5',
//            'Ư' => 'Uzz0', 'Ừ' => 'Uzz1', 'Ứ' => 'Uzz2', 'Ử' => 'Uzz3', 'Ữ' => 'Uzz4', 'Ự' => 'Uzz5',
//            'ù' => 'uz1', 'ú' => 'uz2', 'ủ' => 'uz3', 'ũ' => 'uz4', 'ụ' => 'uz5',
//            'ư' => 'uzz0', 'ừ' => 'uzz1', 'ứ' => 'uzz2', 'ử' => 'uzz3', 'ữ' => 'uzz4', 'ự' => 'uzz5'
//        );
//        $keys = array_keys($maps);
//        $vals = array_values($maps);
//        $output = str_replace($keys, $vals, $input);
//        return $output;
//    }
//
//    private static function reSort($array, $field_sort) {
//        $arr_result = array();
//        $tmp_data = array();
//        foreach ($array as $item) {
//            $key = '';
//            if (is_array($field_sort)) {
//                foreach ($field_sort as $_item) {
//                    $key .= $item[$_item];
//                }
//            } else
//                $key = $item[$field_sort];
//            $key = self::uConvert($key);
////            if (function_exists("uConvert")) {
////                $key = self::uConvert($key);
////            }
//            $tmp_data[$key] = $item;
//        }
//        ksort($tmp_data);
//        $i = 0;
//        foreach ($tmp_data as $key => $value) {
//            $arr_result[$i] = $value;
//            $i++;
//        }
//        return $arr_result;
//    }
}

?>