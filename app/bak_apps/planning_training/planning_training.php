<?php

/**
 * App Lập kế hoạch dào tạo
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

class Planning_trainingApp extends AppObject {

    public $app_name = "planning_training";
    public $dir_layout = "backend";
    public $page_title = "Lập kế hoạch đào tạo";
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
        Planning_trainingApp::views();
    }

    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";


        // Bind data to select
        $this->arr_course = self::get_select_table('course', 'id', 'course_name', array(
                    'status' => '1'));
        $this->arr_group_field = self::get_select_table('group_field', 'id', 'group_field_name', array(
                    'status' => '1'));
        $this->arr_major = self::get_select_table('major', 'id', 'major_name', array('status' => '1'));
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
     * Function trả lại mảng chứa năm học của khóa
     * @param <int> $id_course id khóa học cần lấy ra năm
     * @return <array> Trả lại mảng 1 chiều chứa 2 năm của khóa đó. Ex: array(2014,2016) 
     */
    public static function get_select_year($id_course) {
        if ($id_course !== '0' && $id_course != "") {
            $db = new Database;
            $table = 'course';
            $where = array('id' => $id_course);
            $period = $db->getValue($table, 'period', $where);
            $_arr = explode('_', $period['period']);
            return $_arr;
        }
        return "";
    }

    /**
     * Hàm trả lại học kỳ của khóa dựa theo năm
     * @param type $id_course
     * @param type $year
     * @return type
     */
    public static function get_select_term($id_course, $year = 0) {
        $db = new Database;
        $table = 'course';
        $where = array(
            'id' => $id_course);
        $period = $db->getValue($table, 'period', $where);
        $_arr = split('_', $period['period']);
        if ($year == $_arr[0]) {
            return array(1, 2);
        } else if ($year == $_arr[1]) {
            return array(3, 4);
        }
        return array(1, 2, 3, 4);
    }

    /**
     * Function lấy danh sách môn theo id_group_field
     * @param type $id_group_field
     * @return type
     */
    public static function get_subject($id_group_field, $id_course = '', $id_major = '') {
        $db = new Database;
        $table = 'subject';
        if ($id_course != '' && $id_major != '') {
            $where = " id_group_field=$id_group_field AND id"
                    . " NOT IN (SELECT id_subject FROM tnus_training_plan "
                    . "WHERE id_course=$id_course AND id_major=$id_major )";
            return $db->getRows($table, "*", $where);
        } else {
            $where = array('id_group_field' => $id_group_field);
            return $db->getRows($table, "*", $where);
        }
    }

    /**
     * Function lấy danh sách môn theo ngành
     * @param type $id_group_field
     * @param type $id_course
     * @param type $id_major
     * @return type
     */
    public static function get_subject_by_group_field($id_group_field, $id_course, $id_major, $id_knowledge_block = '0') {
        $db = new Database;
        $table = 'subject';
        $where = " id_group_field=$id_group_field AND id_knowledge_block IN ($id_knowledge_block) AND id"
                . " NOT IN (SELECT id_subject FROM tnus_training_plan "
                . "WHERE id_course=$id_course AND id_major=$id_major )";
        return $db->getRows($table, "*", $where);
    }

    /**
     * Function lấy danh sách môn theo chuyên ngành và không nằm trong danh sách môn đã chọn
     * @param type $id_course
     * @param type $id_major
     * @return type
     */
    public static function get_subject_by_major($id_course = '0', $id_major = '0', $id_knowledge_block = '0') {
        $db = new Database;
        $table = 'subject';
        $where = " id_major=$id_major AND id_knowledge_block IN ($id_knowledge_block) AND id"
                . " NOT IN (SELECT id_subject FROM tnus_training_plan "
                . "WHERE id_course=$id_course AND id_major=$id_major )";
        return $db->getRows($table, "*", $where);
    }

    public static function get_subject_by_knowledge_block($id_course = "0", $id_major = "0", $id_knowledge_block = '0') {
        $db = new Database;
        $table = 'subject';
        $where = " id_knowledge_block IN ($id_knowledge_block)  AND id"
                . " NOT IN (SELECT id_subject FROM tnus_training_plan "
                . "WHERE id_course=$id_course AND id_major=$id_major )";
        return $db->getRows($table, "*", $where);
    }

    public static function get_subject_by_id($id) {
        $db = new Database;
        $table = 'subject';
        $where = array('id' => $id);
        return $db->getRow($table, "*", $where);
    }

    /**
     * Function lấy công thức tính điểm mặc định theo khóa học
     * @param type $id_course
     * @return type
     */
    public static function get_default_formula_by_course($id_course) {
        $db = new Database;
        $table = 'mark_formula';
        $where = array('id_course' => $id_course);
        return $db->getRow($table, "*", $where);
    }

    /**
     * Function lấy số tín chỉ theo khối kiến thức của chuyên ngành
     * @param type $id_kn
     * @param type $id_major
     * @return type
     */
    public static function get_sum_curriculum($id_kn, $id_major) {
        $db = new Database;
        $table = 'trainning_form';
        $where['id_knowledge_block'] = array($id_kn);
        $where['id_major'] = array($id_major);
        return $db->getRow($table, "*", $where);
    }

    public static function get_current_sum_curriculum($id_kn, $id_major) {
        $db = new Database;
        $str_sql = "SELECT SUM(tnus_training_plan.curriculum) AS sum_curriculum
                    FROM tnus_training_plan INNER JOIN tnus_subject 
                    ON tnus_training_plan.id_subject = tnus_subject.id
                    WHERE id_knowledge_block=" . $id_kn . " AND tnus_training_plan.id_major=" . $id_major;
        return $db->query($str_sql);
    }

    /**
     * Function lấy kế hoạch đào tạo theo khóa, chuyên ngành, học kỳ
     * @param type $id_course
     * @param type $id_major
     * @param type $term
     */
    public static function get_training_plan($id_course, $id_major, $term) {
        $db = new Database;
        $table = 'training_plan';
        $where = array('id_course' => $id_course);
        $where['id_major'] = array($id_major);
        $where['term'] = array($term);
        //$arr = $db->getRows($table,"*", $where);
        return $db->getRows($table, "*", $where);
        //print_r($arr);
    }

    public static function add_plan($id_course, $id_major, $term, $id_subject, $curriculum, $caculate = '1', $formula = "30;70") {
        $db = new Database;
        $table = 'training_plan';
        $data = array('id_course' => $id_course, 'id_major' => $id_major,
            'term' => $term, 'id_subject' => $id_subject,
            'curriculum' => $curriculum, 'caculate' => $caculate, 'mark_formula' => $formula);
        return $db->insert($table, $data);
    }

    public static function remove_plan($id_plan) {
        $db = new Database;
        $table = 'training_plan';
        $where = ['id' => $id_plan];
        return $db->delete($table, $where);
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
            return true;
        }
        return false;
    }
    

}
