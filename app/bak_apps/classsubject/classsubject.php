<?php
if ( !defined('AREA') ) {
    die('Access denied');
}

/**
 * App quản lý lớp môn học
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @since 14/12/2014
 */

class ClassSubjectApp extends AppObject{
	public $app_name="classsubject";
    public $dir_layout="backend";
    public $page_title = "Lập kế hoạch lớp môn học";
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
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";

        $this->courses = self::getListCourse();
        $this->group_fields = self::getListGroupField();
        $this->classes = self::getListClass();

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

    public static function getCourseInfo($id_course){
        $db = new Database;
        $data = array();
        $table = 'course';
        $result = $db->getRow($table, '*', array('id' => $id_course), PDO::FETCH_OBJ);
        if (null != $result) {
            list($start, $end) = explode('_', $result->period);
            $i=0;
            while($start < $end){
                $data[$start . '_' . ($start + 1)] = array(++$i, ++$i);
                $start++;
            }
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

    /**
     * Lấy danh sách môn học theo khóa, ngành và học kì
     */
    public static function getListSubject($id_course, $term, $id_group_field = null){
        $db = new Database;
        $data = array();
        $table1 = 'training_plan';
        $column1 = 'id_subject';
        $table2 = 'subject';
        $column2 = 'code, name';
        $join = array('id_subject', 'id');
        $condition = 'tb1.id_course = :id_course AND tb1.term = :term';
        $params = array(':id_course' => $id_course, ':term' => $term);
        if ($id_group_field != null) {
            $condition .= ' AND tb2.id_group_field = :id_group_field';
             $params[':id_group_field'] = $id_group_field;
        }
        $result = $db->join2Table($table1, $column1, $table2, $column2, $join, $condition, $params, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id_subject] = $item->name;
        }
        return $data;
    }

    /**
     * Lay danh sach lop
     */
    public static function getListClass($course = '', $group_field = ''){
        $db = new Database;
        $data = array();
        $table = 'class';
        $condition = array('status' => array('1'));
        if($course > 0 && is_numeric($course))
            $condition['id_course'] = array($course);
        if($group_field > 0 && is_numeric($group_field))
            $condition['id_group_field'] = array($group_field);
        $result = $db->getRows($table, '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->class_name;
        }
        return $data;
    }

    public static function addClass($year, $term, $id_subject, $course){
        $db = new Database;
        $subject = $db->getRow('subject', '*', array('id' => $id_subject), PDO::FETCH_OBJ);
        $exists_class = $db->countRows('class_subject', 'id', 'year = :year AND term = :term AND id_subject = :id_subject', array(':year' => $year, ':term' => $term, ':id_subject' => $id_subject));
        $exists_class = $exists_class + 1;
        $newClass_id = sprintf("%02d", $exists_class);
        $data = array(
            'code' => $subject->code . '_L' . $newClass_id,
            'name' => $subject->name . '_L' . $newClass_id,
            'year' => $year,
            'term' => $term,
            'id_course' => $course,
            'id_subject' => $id_subject
        );
        $db->insert('class_subject', $data);
    }

    public static function listClassSubject($year, $term, $id_subject){
        $db = new Database;
        $result = $db->getRows('class_subject', '*', array('year' => $year, 'term' => $term, 'id_subject' => $id_subject), PDO::FETCH_OBJ);
        $html = '';
        foreach ($result as $item) {
            $html .= '<tr><td class="class_subject" data-id="'. $item->id .'">'. $item->name .'</td></tr>';
        }
        if ($html)
            return $html;
        else
            return '<tr><td>Chưa có lớp môn học. Hãy tạo mới</td></tr>';
    }

    /**
     * Lay danh sach lop hoc phan
     * @param [type] $id_classsubject [description]
     */
    public static function ClassSubjectDetail($id_classsubject){
        $db = new Database;
        $table1 = DB_TABLE_PREFIX . 'profile';
        $table2 = DB_TABLE_PREFIX . 'student';
        $table3 = DB_TABLE_PREFIX . 'class_subject_details';
        $table4 = DB_TABLE_PREFIX . 'class';
        $query = "SELECT tb1.last_name, tb1.first_name, tb2.student_code, tb3.id_student, tb4.class_name FROM $table1 tb1, $table2 tb2, $table3 tb3, $table4 tb4 WHERE tb1.id = tb2.id_profile AND tb2.id = tb3.id_student AND tb2.id_class = tb4.id AND tb3.id_class_subject = :id_classsubject";
        $result = $db->queryAll($query, array(':id_classsubject' => $id_classsubject), PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        foreach ($result as $item) {
            $html .= '
            <tr>
                <td><div class="checkbox"><label><input type="checkbox" value="'. $item->id_student .'"></label></div></td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->last_name . ' ' . $item->first_name .'</td>
                <td>'. $item->class_name .'</td>
            </tr>
            ';
        }
        $html = preg_replace('/\s\s+/', '', $html);
        return str_replace(array("\r", "\n"), array("", ""), $html);
    }

    /**
     * Lay danh sach lop, loai bo nhung sinh vien da co trong lop hoc phan
     * @param [type] $id_class [description]
     */
    public static function ClassDetail($id_class, $year = null, $term = null, $id_subject = null){
        $db = new Database;
        // GET LIST id_student BY id_classsubject
        $_tb1 = 'class_subject_details';
        $_cln1 = 'id_student';
        $_tb2 = 'class_subject';
        $_cln2 = 'id';
        $_on = array('id_class_subject', 'id');
        $_condition = 'tb2.year = :year AND tb2.term = :term AND tb2.id_subject = :id_subject';
        $_param = array(':year' => $year, ':term' => $term, ':id_subject' => $id_subject);
        $_result = $db->join2Table($_tb1, $_cln1, $_tb2, $_cln2, $_on, $_condition, $_param, PDO::FETCH_OBJ);
        // $_result = $db->getRows('class_subject_details', 'id_student', array('id_class_subject' => $id_classsubject), PDO::FETCH_OBJ);
        $tmp = array();
        foreach ($_result as $item) {
            $tmp[] = $item->id_student;
        }
        // GET LIST student BY id_class WHO NOT IN class_subject
        $table1 = 'profile';
        $column1 = 'last_name, first_name';
        $table2 = 'student';
        $column2 = 'id, student_code';
        $on = array('id', 'id_profile');
        $condition = 'tb2.id_class = :id_class';
        if (!empty($tmp))
            $condition .= ' AND tb2.id NOT IN ('. implode(',', $tmp) .')';
        $param = array(':id_class' => $id_class);
        $result = $db->join2Table($table1, $column1, $table2, $column2, $on, $condition, $param, PDO::FETCH_OBJ);
        $result = self::reSort($result, array('first_name', 'last_name'));
        $html = '';
        foreach ($result as $item) {
            $html .= '
            <tr>
                <td><div class="checkbox"><label><input type="checkbox" value="'. $item->id .'"></label></div></td>
                <td>'. $item->student_code .'</td>
                <td>'. $item->last_name .' '. $item->first_name .'</td>
            </tr>
            ';
        }
        $html = preg_replace('/\s\s+/', '', $html);
        return str_replace(array("\r", "\n"), array("", ""), $html);
    }

    /*
     * Them hoc vien vao lop hoc phan
     */
    public static function addStudent($id_classsubject, $profiles){
        $db = new Database;
        # Lay id mon hoc theo id lop mon hoc
        $_res = $db->getValue('class_subject', 'id_subject', array('id' => $id_classsubject), PDO::FETCH_OBJ);
        $id_subject = $_res->id_subject;
        # Lay danh sach tat ca cac lop hoc phan theo id mon hoc
        $list = array();
        $res = $db->getRows('class_subject', 'id', array('id_subject' => $id_subject), PDO::FETCH_OBJ);
        for ($i=0; $i < count($res) ; $i++) { 
            $list[] = ':list'.$i;
            $params[':list'.$i] = $res[$i]->id;
        }
        foreach ($profiles as $id_student) {
            # Dem so lan hoc cua sinh vien nay
            $param = $params;
            $param[':id_student'] = $id_student;
            $count = $db->countRows('class_subject_details', '*', 'id_student = :id_student AND id_class_subject IN ('. implode(',', $list) .')', $param);

            # Them hoc vien vao lop mon hoc
            $data = array('id_class_subject' => $id_classsubject, 'id_student' => $id_student, 'count' => $count + 1);
            $result = $db->insert('class_subject_details', $data);
            unset($param);
            unset($data);
            if (!$result)
                return false;
        }
        return true;
    }

    /*
     * Xoa hoc vien khoi lop mon hoc
     */
    public static function removeStudent($id_classsubject, $profiles) {
        $db = new Database;
        $params = array(':id_classsubject' => $id_classsubject);
        $list = array();
        for ($i=0; $i < count($profiles) ; $i++) {
            $list[] = ':list'.$i;
            $params[':list'.$i] = $profiles[$i];
        }
        $query = "DELETE FROM ".DB_TABLE_PREFIX."class_subject_details WHERE id_class_subject = :id_classsubject AND id_student IN (". implode(',', $list).")";
        return $db->executesql($query, $params);
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

            $key = uConvert($key);
            $tmp_data[$key] = $item;
        }
        ksort($tmp_data);
        return $tmp_data;
    }
}
?>