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

class SearchProfileApp extends AppObject {

    public $app_name = "searchprofile";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "Tra cứu hồ sơ";

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

    /**
     * Xuất view
     * @author Duyld2108
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";

        if (isset($_POST['search_kw']) && trim($_POST['search_kw']) != "") {
            $db = new Database;
            $prefix = DB_TABLE_PREFIX;
            $st = $prefix.'student';
            $pr = $prefix.'profile';
            $keyword = trim($_POST['search_kw']);
            $query = "SELECT pr.first_name, pr.last_name, pr.birthday, pr.sex, st.status, st.id, st.student_code FROM {$st} st, {$pr} pr WHERE st.id_profile = pr.id AND (st.student_code = :keyword OR CONCAT_WS(' ', pr.last_name, pr.first_name) LIKE :name)";
            $result = $db->queryAll($query, array(':keyword' => $keyword, ':name' => "%$keyword%"), PDO::FETCH_OBJ);
            $result = reSort($result, array('first_name', 'last_name'));
            foreach ($result as $item) {
                $item->status = studentStatus($item->status);
            }
            $this->students = $result;
        }

        // Get list group_field
        $this->courses = self::getListCourse();

        parent::display();
    }

    /**
     * Lấy danh sách Khoá học
     * @return array Danh sách khoá học với $key là id khoá học, $value là tên khoá học
     */
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

    # Lay danh sach hoc vien theo id_class
    public static function getListStudent($id_class){
        $db = new Database;
        $prefix = DB_TABLE_PREFIX;
        $st = $prefix.'student';
        $pr = $prefix.'profile';
        $query = "SELECT pr.first_name, pr.last_name, pr.birthday, pr.sex, st.status, st.id, st.student_code FROM {$st} st, {$pr} pr WHERE st.id_profile = pr.id AND st.id_class = :id_class";
        $result = $db->queryAll($query, array(':id_class' => $id_class), PDO::FETCH_OBJ);
        return $result;
    }
}

function studentStatus($status){
    switch ($status) {
        case 2:
            return 'Bảo lưu';
            break;
        case 3:
            return 'Thôi học';
            break;
        case 4:
            return 'Xóa tên';
            break;
        case 5:
            return 'Tốt nghiệp';
            break;
        default:
            return 'Bình thường';
            break;
    }
}

function reSort($array, $field_sort){
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

        $key = uConvert($key);
        $tmp_data[$key] = $item;
    }
    ksort($tmp_data);
    return array_values($tmp_data);
}

function uConvert($input){
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

?>