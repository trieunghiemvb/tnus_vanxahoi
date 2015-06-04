<?php
/**
 * App quản lý chuyên ngành
 * @author Duyld2108 <duyld.dhkh@gmail.com>
 * @version 1.0
 */

if ( !defined('AREA') ) {
    die('Access denied');
}

class SubjectsDictApp extends AppObject {
    public $app_name="subjectsdict";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout
    public $page_title = "Từ điển môn học";
    public $items=null;

    private static $table = 'subject';

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
        $this->group_field = self::listGroupField();
        $this->majors = self::getMajors();
        $this->knowledge_blocks = self::listKnowledgeBlock();
        $this->subjects = self::listSubjects();

        parent::display();
    }

    /**
     * Lây danh sách các ngành học
     * @author Duyld2108
     * @return array Mảng chứa thông tin các ngành học
     * Index là id ngành học; Value là tên ngành học
     */
    public static function listGroupField(){
        $db = new Database;
        $group_field = array();
        $table = 'group_field';
        $result = $db->getRows($table, '*', 'status = 1 AND id <> 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $group_field[$item->id] = $item->group_field_name;
        }
        return $group_field;
    }

    /*
     * Lay danh sach chuyen nganh theo nganh hoc
     */
    public static function getMajors($id_group_field = null){
        $db = new Database;
        $data = array();
        $condition = array('status' => 1);
        if ($id_group_field) {
            $condition['id_group_field'] = $id_group_field;
        }
        $result = $db->getRows('major', '*', $condition, PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $data[$item->id] = $item->major_name;
        }
        return $data;
    }


    public static function listKnowledgeBlock(){
        $db = new Database;
        $knowledge_block = array();
        $table = 'knowledge_block';
        $result = $db->getRows($table, '*', 'status = 1', PDO::FETCH_OBJ);
        foreach ($result as $item) {
            $knowledge_block[$item->id] = $item->title;
        }
        return $knowledge_block;
    }

    public static function listSubjects(){
        $db = new Database;
        $result = $db->getRows(self::$table, '*', 'status = 1', PDO::FETCH_OBJ);
        return $result;
    }

    public static function addnewSubject($data){
        $db = new Database;
        $exists = $db->getRow(self::$table, '*', array('code' => $data['code']), PDO::FETCH_OBJ);
        if ($exists) {
            return 'exists';
        }
        else {
            $result = $db->insert(self::$table, $data);
            if ($result == true)
                return $db->lastId;
            else
                return false;
        }
    }

    public static function updateSubject($data, $id){
        $db = new Database;
        $exists = $db->countRows(self::$table, 'id', 'code = :code AND id <> :id', array(':code' => $data['code'], ':id' => $id));
        if ($exists != 0) {
            return "existed";
        }
        else {
            return $db->update(self::$table, $data, array('id' => $id));
        }
    }

    public static function deleteSubject($id){
        $db = new Database;
        $conditions = array('id' => array($id));
        return $db->delete(self::$table, $conditions);
    }

    public static function subjectExists($code, $id = 0){
        $db = new Database;
        $exists = $db->countRows(self::$table, 'id', 'code = :code AND id <> :id', array(':code' => $code, ':id' => $id));
        if ($exists == 0)
            return false;
        return true;
    }
}
?>