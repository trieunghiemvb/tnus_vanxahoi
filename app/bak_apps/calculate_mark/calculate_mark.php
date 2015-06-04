<?php

/**
 * App Tính điểm cho học viên
 * @author Hieubd <buiduchieuvnu@gmail.com>
 * @since 05/01/2015
 * @version 1.0
 */
session_start();
ob_start();

if (!defined('AREA')) {
    die('Access denied');
}
?>
<?php

class Calculate_markApp extends AppObject {

    public $app_name = "calculate_mark";
    public $dir_layout = "backend";
    public $page_title = "Tính điểm";

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
        Calculate_markApp::views();
    }

    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";
        $this->arr_group_field = self::get_select_table('group_field', 'id', 'group_field_name', array('status' => '1'));
        $this->arr_year = self::get_select_year();
        $this->arr_course = self::get_select_table('course', 'id', 'course_name', array('status' => '1'));
        $this->arr_term = array("1" => "Học kỳ 1", "2" => "Học kỳ 2", "3" => "Học kỳ 3", "4" => "Học kỳ 4");
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

    /**
     * Function trả về mảng 2 chiều chứa dữ liệu của bảng có index là field trong bảng
     * @param <string> $table tên bảng cần lấy dữ liệu. Ex: 'tnus_course'
     * @param <string> $index_field index phần tử của mảng. Ex: "id"
     * @param <string/array> $where điều kiện lọc. Ex: array('name'=>'Toán') hoặc " name = 'Toán' "
     * @return <array> mảng 2 chiều. Ex: mang[10]['name']
     */
    public static function get_table_index($table, $index_field, $where = "") {
        $db = new Database;
        $result = array();
        $data = $db->getRows($table, '*', $where);
        foreach ($data as $item) {
            $result[$item[$index_field]] = $item;
        }
        return $result;
    }

    /**
     * Function trả lại mảng chứa các năm học có lớp học phần, sắp xếp theo chiều giảm dần của năm
     * @param <int> $id_course id khóa học cần lấy ra năm
     * @return <array> Trả lại mảng 1 chiều chứa 2 năm của khóa đó. Ex: array(2014,2016) 
     */
    public static function get_select_year() {
        $db = new Database;
        $table = 'class_subject';
        $result = array();
        $where = " 1 ORDER BY year DESC ";
        $arr_year = $db->getRows($table, "year", $where);
        foreach ($arr_year as $item) {
            $result[$item["year"]] = $item["year"];
        }
        return $result;
    }

    /**
     * Hàm lấy select group_field trong class_subject dựa theo năm
     * @param varchar $year năm cần lấy. Ex: "2007_2008"
     * @return array
     */
    public static function get_sel_groupfield_by_year($year) {
        $db = new Database;
        $result = array();
        $where = array("year" => $year);
        $arr_id_group_field = $db->getRows("class_subject", "id_group_field", $where);
        //print_r($arr_id_group_field);
        $arr_group_field = self::get_select_table("group_field", "id", "group_field_name");
        //print_r($arr_group_field);
        foreach ($arr_id_group_field as $item) {
            $result[$item["id_group_field"]] = $arr_group_field[$item["id_group_field"]];
        }
        //print_r($result);
        return $result;
    }

    public static function get_sel_class_subject($year, $id_group_field) {
        $db = new Database;
        $table = 'class_subject';
        $result = array();
        $where = array("year" => $year, "id_group_field" => $id_group_field);
        $arr_class_subject = self::get_select_table($table, "id", "name", $where);
        return $arr_class_subject;
    }

    /**
     * Hàm lấy danh sách điểm học viên
     * @param type $id_class_subject
     * @return string
     */
    public static function get_student($id_class_subject) {
        //echo $id_class_subject;
        $db = new Database;
        $table = "class_subject_details";
        $where = array("id_class_subject" => $id_class_subject);
        $arr_st_cs = $db->getRows($table, "*", $where);
        //print_r($arr_st_cs);
        $arr_student = self::get_table_index("student", "id");
        //print_r($arr_student);
        $arr_profile = self::get_table_index("profile", "id");
        //print_r($arr_profile);
        $arr_class = self::get_table_index("class", "id");
        $arr_course = self::get_table_index("course", "id");
        $arr_subject = self::get_table_index("subject", "id");
        $arr_class_subject = self::get_table_index("class_subject", "id");
        $arr_class_subject_details = self::get_table_index("class_subject_details", "id_student", $where);
        $arr_result = array();
        foreach ($arr_st_cs as $st) {
            $id_student = $st["id_student"];
            $id_profile = $arr_student[$id_student]["id_profile"];
            $id_class = $arr_student[$id_student]["id_class"];
            $id_course = $arr_class[$id_class]["id_course"];
            $id_major = $arr_student[$id_student]["id_major"];
            $id_subject = $arr_class_subject[$id_class_subject]["id_subject"];
            $where = array("id_course" => array($id_course), "id_major" => array($id_major), "id_subject" => array($id_subject));
            $training_plan = $db->getRow("training_plan", "*", $where);
            $mark_formula = $training_plan["mark_formula"];
            $mark_component = $arr_class_subject_details[$id_student]["mark_component"];
            $mark_exam = $arr_class_subject_details[$id_student]["mark_exam"];
            $mark_sumary = $arr_class_subject_details[$id_student]["mark_sumary"];
            //print_r($training_plan);        
            $arr_result[$id_student] = array(
                "id" => $id_student,
                "student_code" => $arr_student[$id_student]["student_code"],
                "name" => $arr_profile[$id_profile]["last_name"] . " " . $arr_profile[$id_profile]["first_name"],
                "mark_formula" => $mark_formula,
                "mark_component" => $mark_component,
                "mark_exam" => $mark_exam,
                "mark_sumary" => $mark_sumary,
            );
        }
        //print_r($arr_result);
        return $arr_result;
    }

    public static function cal_mark_by_class_subject($id_class_subject) {
        //echo $id_class_subject;
        $db = new Database;
        $table = "class_subject_details";
        $where = array("id_class_subject" => $id_class_subject);
        $arr_st_cs = $db->getRows($table, "*", $where);
        //print_r($arr_st_cs);
        $arr_student = self::get_table_index("student", "id");
        //print_r($arr_student);
        $arr_profile = self::get_table_index("profile", "id");
        //print_r($arr_profile);
        $arr_class = self::get_table_index("class", "id");
        $arr_course = self::get_table_index("course", "id");
        $arr_subject = self::get_table_index("subject", "id");
        $arr_class_subject = self::get_table_index("class_subject", "id");
        $arr_class_subject_details = self::get_table_index("class_subject_details", "id_student", $where);
        $arr_result = array();
        foreach ($arr_st_cs as $st) {
            $id_student = $st["id_student"];
            $id_profile = $arr_student[$id_student]["id_profile"];
            $id_class = $arr_student[$id_student]["id_class"];
            $id_course = $arr_class[$id_class]["id_course"];
            $id_major = $arr_student[$id_student]["id_major"];
            $id_subject = $arr_class_subject[$id_class_subject]["id_subject"];
            $where = array("id_course" => array($id_course), "id_major" => array($id_major), "id_subject" => array($id_subject));
            $training_plan = $db->getRow("training_plan", "*", $where);
            $mark_formula = $training_plan["mark_formula"];
            $mark_component = $arr_class_subject_details[$id_student]["mark_component"];
            $mark_exam = $arr_class_subject_details[$id_student]["mark_exam"];
            $arr_mf = explode("/", $mark_formula);
            $p_com = $arr_mf[0];
            $mark_sumary = round(($mark_component * $p_com + $mark_exam * (100 - $p_com)) / 100, 2);
            //echo $mark_sumary." | ";
            self::update_mark_sumary($id_class_subject, $id_student, $mark_sumary);
        }
        //print_r($arr_result);
        return true;
    }

    public static function update_mark_sumary($id_class_subject, $id_student, $mark_sumary) {
        $db = new Database;
        $where = array("id_class_subject" => $id_class_subject, "id_student" => $id_student);
        return $db->update("class_subject_details", array("mark_sumary" => $mark_sumary), $where);
    }

//    ++++++++++++++++++++++ Tính điểm theo khóa học, học kỳ hành chính +++++++++++++++++++++++++++
    /**
     * Function trả lại mảng năm học của khóa
     * @param <int> $id_course
     * @return <array> mảng chứa năm học. Ex: Array ( [0] => 2007_2008 [1] => 2009_2010 ) 
     */
    static function get_arr_year_by_course($id_course) {
        $_result = array();
        $db = new Database;
        $table = 'course';
        $where = array("id" => array($id_course));
        $_course = $db->getRow($table, "period", $where);
        $_period = $_course["period"];
        $arr_year = explode("_", $_period);
        $_result[0] = $arr_year[0] . "_" . ($arr_year[0] + 1);
        $_result[1] = $arr_year[1] . "_" . ($arr_year[1] + 1);
        return $_result;
    }

    /**
     * Function trả lại danh sách môn theo khóa học và học kỳ
     * @param <int> $id_course
     * @param <int> $term
     * @return <array> mảng danh sách id_subject. Ex: Array ( [0] => Array ( [id_subject] => 1 ) [1] => Array ( [id_subject] => 2 )) 
     */
    static function get_arr_id_subject($id_course, $term) {
        $db = new Database;
        $table = 'training_plan';
        $where = array("id_course" => array($id_course), "term" => array($term));
        return $db->getRowsDistinct($table, "id_subject", $where);
    }

    static function get_arr_class_subject($id_subject, $year) {
        $db = new Database;
        $table = "class_subject";
        $where = array("id_subject" => array($id_subject), "year" => array($year));
        return $db->getRowsDistinct($table, "id, code, name", $where);
    }

    /**
     * Function trả lại danh sách id lớp học phần the id môn học và năm học
     * @param <int> $id_subject
     * @param <string> $year Ex: "2007_2008"
     * @return <array> Ex: Array ( [0] => Array ( [id] => 1 ) [1] => Array ( [id] => 2 )) 
     */
    static function get_arr_class_subject_by_id_course_term($id_course, $term) {
        $arr_year = self::get_arr_year_by_course($id_course);
        $arr_id_subject = self::get_arr_id_subject($id_course, $term);
        if ($arr_year != null && $arr_id_subject != null) {
            $db = new Database;
            $table = "class_subject";
            $where1 = " year IN (";
            foreach ($arr_year as $year) {
                $where1 .= " '" . $year . "',";
            }
            $where1 = rtrim($where1, ',');
            $where1 .= ")";
            $where2 = " id_subject IN (";
            foreach ($arr_id_subject as $id_subject) {
                $where2 .= $id_subject["id_subject"] . ",";
            }
            $where2 = rtrim($where2, ',');
            $where2 .= ")";
            $where = $where1 . "  AND " . $where2;
            return $db->getRowsDistinct($table, "id, code, name", $where);
        } else {
            return null;
        }
    }

    /**
     * Function trả lại danh sách chi tiết học viên theo id lớp học phần
     * @param <int> $id_class_subject 
     * @return <array> Kết quả trả về mảng chi tiết sv. Ex: Array ( [0] => Array ( [id_class_subject] => 12 [id_student] => 10 [count] => [mark_component] => 6.00 [mark_exam] => 7.50 [mark_sumary] => 7.05 [note] => )
     */
    function get_arr_student_mark_sum_by_id_class_subject($id_class_subject) {
        $db = new Database;
        $table = "class_subject_details";
        $where = array("id_class_subject" => array($id_class_subject));
        return $db->getRows($table, "*", $where);
    }

    /**
     * Func cập nhật điểm tổng kết môn của học viên theo id lớp, id học viên
     * @param type $id_class_subject
     * @param type $id_student
     * @param type $mark_sumary
     * @return type
     */
    function update_mark_sumary_by_student($id_class_subject, $id_student) {
        $db = new Database;
        $table = "class_subject_details";
        $where = array("id_class_subject" => $id_class_subject, "id_student" => $id_student);
        $_arr_student = $db->getRows($table, "*", $where);
        $mark_component = $_arr_student["mark_component"];
        $mark_exam = $_arr_student["mark_exam"];

        return $db->update("class_subject_details", array("mark_sumary" => $mark_sumary), $where);
    }

    // Chưa test
    public static function cal_mark_by_id_course_term($id_course, $term) {
        $arr_class_subject = self::get_arr_class_subject_by_id_course_term($id_course, $term);
        foreach ($arr_class_subject as $class_subject) {
            self::cal_mark_by_class_subject($class_subject["id"]);
        }
        return true;
    }

//    -------------------------- Tính điểm tổng kết --------------------------------
    public static function get_arr_group_field() {
        $db = new Database;
        return $db->getRows("group_field", "*");
    }

    /**
     * Lấy danh sách lớp theo id khóa học
     * @param type $id_course
     * @return type
     */
    public static function get_arr_class_by_id_course($id_course) {
        $db = new Database;
        $table = "class";
        $where = array("id_course" => array($id_course));
        return $db->getRows($table, "*", $where);
    }

   //    App: calculate_mark 
//    Test case: Tính điểm tổng kết  
    /**
     * Hàm trả lại danh sách sinh viên trong lớp hành chính
     * @param int $id_class id lớp hành chính
     * @return mảng 2 chiều tất cả thông tin sinh viên
     * Tested
     */
    public static function get_arr_student_by_class($id_class) {
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'student';
        $table2 = DB_TABLE_PREFIX . 'profile';
        $table3 = DB_TABLE_PREFIX . 'major';
        $sql = "SELECT
                $table1.id,
                $table1.final_mark,
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
//        print_r($arr_subject[8]);
        for ($i = 0; $i < count($arr_subject)-1; $i++) {
            for ($j = $i + 1; $j < count($arr_subject); $j++) {
                // Trùng môn + sv => giữ lại lần học cuối
//                if ($arr_subject[$i]["id_student"] == $arr_subject[$j]["id_student"] && $arr_subject[$i]["id_subject"] == $arr_subject[$j]["id_subject"]) {
//                    if ($arr_subject[$i]["count"] > $arr_subject[$j]["count"]) {
//                        unset($arr_subject[$j]);
//                    } else {
//                        unset($arr_subject[$i]);
//                    }
//                }
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
    
    public static function save_mark_final($id_student) {
        $db = new Database();
        $arr_st_subject = self::get_arr_subject_by_student($id_student);
        $total_cur = 0;
        $total_mark = 0;
        foreach ($arr_st_subject as $value) {
            $total_cur += intval($value["curriculum"]);
            $total_mark += floatval($value["mark_sumary"]) * intval($value["curriculum"]);
        }
        $mark = round($total_mark / $total_cur, 2);
        $db->update("student", array("final_mark"=>$mark), array("id"=>$id_student));
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

}
