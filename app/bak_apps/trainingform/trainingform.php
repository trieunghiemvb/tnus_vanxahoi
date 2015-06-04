<?php
/**
 * App quản lý khung chương trình học
 * @author ANVT <anvt.dhkh@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if ( !defined('AREA') ) {
    die('Access denied');
}
set_time_limit(36000);
$timezone  = +7; //(GMT +7:00) 
ini_set("memory_limit","1220M");
?>
<?php
class TrainingFormApp extends AppObject {
    public $app_name="trainingform";
    public $dir_layout="backend"; // thư mục chứa layout trong skin
    public $page_title = "Quản lý khung chương trình học";
    public $group_field=null;
    public $major=null;
    public $items=null;
    public $classAjax=null;
    public $classes=null;
    public $limitstart;
    public $limit;
    public $total;
    public $where;
    public $numOfPage;
    public $query;
    public $search;
    public $pagination;
    public $loaditem;
    public $Class;

    private static $table_class_subject_details = 'class_subject_details';
    private static $table_class_subject = 'class_subject';
    private static $table = 'student';

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
        TrainingFormApp::views();

    }

    /**
     * Xuất view
     * @author Hieubd
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";


        // Get list Course
        $this->course = self::listCourse();

        // Get list group_field
        $this->group_field = self::listGroup_field();
         // Get list Subject
         $this->subjects=self::listMajor();
         // Get list Knowledge
         $this->knowledge=self::listKnowledge();
        		
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
    public static function get_select_key_value($table, $value_field, $text_field, $where = "") {
        $db = new Database;
        $result = array();
        $data = $db->getRows($table, '*', $where);
        foreach ($data as $item) {
            $result[$item[$value_field]] = $item[$text_field];
        }
        return $result;
    }
	public static function getValueByKey($table, $key_field, $where = "") {
        $db = new Database;      
        $values = "";        
        $data = $db->getValue($table, $key_field,$where);        
		$values = $data[$key_field];        
        return $values;        
    }

	/*
     * function trả lại danh sách Course
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listCourse() {
        $db = new Database;
        $courses = array();
        $table = 'course';
        $condition = array('status' => array('1'));
        $result = $db->getRows($table, '*',$condition);
        foreach ($result as $item) {
            $courses[$item['id']] = $item['course_name'];
        }
        return $courses;
    }
	/*
     * function trả lại tên Course theo Id
     * @author ANVT
     * @return <String> trả về tên của Course
     */
    public static function getCourseNameById($id_course) {
        $db = new Database;
        $courses = "";
        $table = 'course';
        $condition = array('status' => array('1'), 'id' => array($id_course));
        $result = $db->getValue($table, 'course_name',$condition);        
		$courses = $result['course_name'];        
        return $courses;
    }
	/*
     * function trả lại tên Course theo Id
     * @author ANVT
     * @return <String> trả về period của Course
     */
    public static function getCoursePeriodById($id_course) {
        $db = new Database;
        $period = "";
        $table = 'course';
        $condition = array('status' => array('1'), 'id' => array($id_course));
        $result = $db->getValue($table, 'period',$condition);        
		$period = $result['period'];        
        return $period;
    }
    /*
     * function trả lại tên Class theo Id
     * @author ANVT
     * @return <String> trả về Name của Class
     */
    public static function getClassNameById($id) {
        $db = new Database;
        $class_name = "";
        $table = 'class';
        $condition = array('status' => array('1'), 'id' => array($id));
        $result = $db->getValue($table, 'class_name',$condition);        
		$class_name = $result['class_name'];        
        return $class_name;
    }
	/*
     * function trả lại tên Subject theo Id
     * @author ANVT
     * @return <String> trả về Name của Subject
     */
    public static function getSubjectById($id) {
        $db = new Database;
        $subject_name = "";
        $table = 'subject';
        $condition = array('status' => array('1'), 'id' => array($id));
        $result = $db->getValue($table, 'name',$condition);        
		$subject_name = $result['name'];        
        return $subject_name;
    }
	/*
     * function trả lại tên Class theo Id Course, Id Group
     * @author ANVT
     * @return <String> trả về Name của Class
     */
    public static function getClassNameByIdCourse($id_course, $id_group_field) {
        $db = new Database;
        $class_name = "";
        $table = 'class';
        $condition = array('status' => array('1'), 'id_course' => array($id_course), 'id_group_field' => array($id_group_field));
        $result = $db->getValue($table, 'class_name',$condition);        
		$class_name = $result['class_name'];        
        return $class_name;
    }
	/*
     * function trả lại tên Major theo Id
     * @author ANVT
     * @return <String> trả về Name của Major
     */
    public static function getMajorNameById($id_major) {
		$db = new Database;
        $major_name = "";
        $table = 'major';
        $condition = array('status' => array('1'), 'id' => array($id_major));
        $result = $db->getValue($table, 'major_name',$condition);        
		$major_name = $result['major_name'];          
        return $major_name;
    }
	/*
     * function trả lại tên Major theo Id Student
     * @author ANVT
     * @return <String> trả về Name của Major
     */
    public static function getMajorNameByIdStudent($id_student) {
		$db = new Database;
        $id_major = "";
        $major_name = "";
        $table = 'student';
        $condition = array('status' => array('1'), 'id' => array($id_student));
        $result = $db->getValue($table, 'id_major',$condition);        
		$id_major = $result['id_major'];  
        $major_name = self::getMajorNameById($id_major);
        return $major_name;
    }
	/*
     * function trả lại tên Provice theo Id Provice
     * @author ANVT
     * @return <String> trả về Name của Provice
     */
    public static function getProviceNameById($id_provice) {
		$db = new Database;
        $provice_name = "";
        $table = 'provice';
        $condition = array('status' => array('1'), 'id' => array($id_provice));
        $result = $db->getValue($table, 'name',$condition);        
		$provice_name = $result['name'];
        return $provice_name;
    }
	/*
     * function trả lại tên Class theo Id Student
     * @author ANVT
     * @return <String> trả về Name của Class
     */
    public static function getClassNameByIdStudent($id_student) {
        $db = new Database;
        $id_class = "";
        $class_name = "";
        $table = 'student';
        $condition = array('status' => array('1'), 'id' => array($id_student));
        $result = $db->getValue($table, 'id_class',$condition);        
		$id_class = $result['id_class'];   		
		$class_name = self::getClassNameById($id_class);
		return $class_name;
    }
	/*
     * function trả lại tên Student Code theo Id Student
     * @author ANVT
     * @return <String> trả về Student Code của Student
     */
    public static function getStudentCodeById($id_student) {
        $db = new Database;
        $student_code = "";
        $table = 'student';
        $condition = array('status' => array('1'), 'id' => array($id_student));
        $result = $db->getValue($table, 'student_code',$condition);        
		$student_code = $result['student_code'];   		
		return $student_code;
    }
	/*
     * function trả lại tên Profile Id theo Id Student
     * @author ANVT
     * @return <String> trả về Profile Id của Student
     */
    public static function getProfileIdByIdStudent($id_student) {
        $db = new Database;
        $id_profile = "";
        $table = 'student';
        $condition = array('status' => array('1'), 'id' => array($id_student));
        $result = $db->getValue($table, 'id_profile',$condition);        
		$id_profile = $result['id_profile'];   		
		return $id_profile;
    }
    /*
     * function trả lại tên Profile theo Id 
     * @author ANVT
     * @return <array> trả về Profile của Student
     */
    public static function getProfileById($id_profile) {
        $db = new Database;
        $table = 'profile';
		$profile = array();
        $condition = array('status' => array('1'), 'id' => array($id_profile));      
		$result_profile = $db->getRows($table, '*', $condition);
		foreach ($result_profile as $item_profile) {
			$profile['name'] = $item_profile['last_name'].' '.$item_profile['first_name'];
			$profile['sex'] = ($item_profile['sex'] == 1) ? 'Nam' : 'Nữ';
			$profile['birthday'] = $item_profile['birthday'];
			$profile['email'] = $item_profile['email'];
			$profile['phone'] = $item_profile['phone'];
			$profile['birth_place'] = self::getProviceNameById($item_profile['birth_place']);
		}
		   		
		return $profile;
    }
    /*
     * function trả lại tên Class Subject theo Id 
     * @author ANVT
     * @return <array> trả về ClassSubject
     */
    public static function getClassSubjectById($id_class_subject) {
        $db = new Database;
        $table = 'class_subject';
		$class_subject = array();
        $condition = array('status' => array('1'), 'id' => array($id_class_subject));      
		$result_class_subject = $db->getRows($table, '*', $condition);
		foreach ($result_class_subject as $item_class_subject) {
			$class_subject['code'] = $item_class_subject['code'];
			$class_subject['name'] = $item_class_subject['name'];
			$class_subject['year'] = $item_class_subject['year'];
			$class_subject['term'] = $item_class_subject['term'];
			$class_subject['id_group_field'] = $item_class_subject['id_group_field'];
			$class_subject['id_subject'] = $item_class_subject['id_subject'];			
		}
		   		
		return $class_subject;
    }
    /*
     * function trả lại danh sách Year
     * @author ANVT
	 * @param $id_course =""
     * @return <array> trả về danh sách
     */
    public static function listYear($id_course = "") {
        $db = new Database;
        $courses = array();
        $table = 'class_subject';
		$year_list = "";
        $condition = array('status' => array('1'));
		if(!empty($id_course)){
            $db = new Database;
			$course = 'course';
			$where = array('id' => $id_course);
			$period = $db->getValue($course, 'period', $where);
			
			$_arr = split('_', $period['period']);
			$p1 = $_arr[0]+1;
			$year_1 = $_arr[0]."_".$p1;
			$year_2 = $p1."_".$_arr[1];
			$year_list = "'".$year_1."','".$year_2."'";
		}
		if(!empty($year_list)){			
			$where= "`year` IN (".$year_list.")";
		}
        $result = $db->getRows($table, '*',$where);
        foreach ($result as $item) {
            $courses[$item['year']] = $item['year'];
        }
		arsort($courses);
        return $courses;
    }
	/**
     * function trả lại danh sách Knowledge
     * @author HieuBD
     * @return <array> trả về danh sách Knowledge
     */
    public static function listKnowledge() {
        $db = new Database;
        $knowledge = array(); 
        $table = 'knowledge_block	';
        $condition = array('status' => array('1'));
			
        $result = $db->getRows($table, '*',$condition);
        foreach ($result as $item) {
            $knowledge[$item['id']] = $item['title'];
        }
		ksort($knowledge);
        return $knowledge;
    }
		/*
     * function trả lại danh sách các chuyên ngành
     * @author ANVT
     * @return <String> trả về danh sách các chuyên ngành
     */
    public static function listMajor($id_group_field = "") {
		$db = new Database;
        $major_name = array();
        $table = 'major';
        $condition = array('status' => array('1'));
		if(!empty($id_group_field))
            $condition =array('id_group_field' => array($id_group_field), 'status' => array('1'));		
        $result = $db->getRows($table, '*', $condition);        
		foreach ($result as $item) {
            $major_name[$item['id']] = $item['major_name'];
        }
        ksort($major_name);
        return $major_name;
    }
	/**
     * function trả lại danh sách Term
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listTerm($year = "") {
        $db = new Database;
        $term = array(); //$year = '2013';
        $table = 'class_subject';
        $condition = array('status' => array('1'));
		if(!empty($year))
            $condition['year'] = array($year);			
        $result = $db->getRows($table, '*',$condition);
        foreach ($result as $item) {
            $term[$item['term']] = $item['term'];
        }
		ksort($term);
        return $term;
    }
	 
	 /*
     * function trả lại danh sách Group field
	 * @author ANVT
	 * @param $year ="", $term=""
     * @return <array> trả về danh sách
     */
    public static function listGroup_field() {
        $db = new Database;
        $group_field = array(); 
        $table = 'group_field';
        $condition = array('status' => array('1'));
		$result = $db->getRows($table, '*',$condition);
		foreach ($result as $item) {
			$group_field[$item['id']] = $item['group_field_name'];
		}
        
		ksort($group_field);
        return $group_field;
    }
	
	/*	
     * function trả lại danh sách Subject
     * @author ANVT
	 * @param $year ="", $term="", $group_field=""
     * @return <array> trả về danh sách
     */
    public static function listSubject($year ="", $term="", $group_field="") {
        $db = new Database;
        $db2 = new Database;
        $subjects = array();
        $class_subject = 'class_subject';
        $subject = 'subject';
        $condition = array('status' => array('1'));
        if(!empty($year))
            $condition['year'] = array($year);
		if(!empty($term) && is_numeric($term))
            $condition['term'] = array(intval($term));
		if(!empty($group_field) && is_numeric($group_field))
            $condition['id_group_field'] = array(intval($group_field));
        $result = $db->getRows($class_subject, 'id_subject',$condition);        
        foreach ($result as $item) {
           // $subjects[$item['id_subject']] = $item['id_subject'];
			$condition2 = array('status' => array('1'), 'id' => array($item['id_subject']));
			$result2 = $db2->getRows($subject, 'name',$condition2);
			foreach ($result2 as $item2) {
				$subjects[$item['id_subject']] = $item2['name'];
			}
        }
		asort($subjects);
        return $subjects;
    }
	/**
     * function trả lại danh sách Class
     * @author ANVT
	 * @param $year ="", $term="", $group_field="", $subject=""
     * @return <array> trả về danh sách
     */
    public static function listClass($year ="", $term="", $group_field="", $subject="") {
        $db = new Database;
        $subjects = array();
        $class_subject = 'class_subject';
        $condition = array('status' => array('1'));
        if(!empty($year))
            $condition['year'] = array($year);
		if(!empty($term) && is_numeric($term))
            $condition['term'] = array(intval($term));
		if(!empty($group_field) && is_numeric($group_field))
            $condition['id_group_field'] = array(intval($group_field));
        if(!empty($subject) && is_numeric($subject))
            $condition['id_subject'] = array(intval($subject));
        $result = $db->getRows($class_subject, '*',$condition);        
        foreach ($result as $item) {
            $subjects[$item['id']] = $item['name'];			
        }
		asort($subjects);
        return $subjects;
    }
	/**
     * function trả lại danh sách Trainning Form theo khóa
     * @author ANVT
	 * @param $year
     * @return <array> trả về danh sách
     */
   public static function listTrainingForm($id_course) {
        $db = new Database;
        $training_form_data = array();
        $trainingForm = array();
        $table = 'trainning_form';
        $condition = array('status' => array('1'), 'id_course' => array($id_course));
		$result = $db->getRows($table, '*', $condition);
		foreach ($result as $item) 
		{
			$training_form_data['id'] = $item['id'];
			$training_form_data['id_major'] = $item['id_major'];
			$training_form_data['id_knowledge_block'] = $item['id_knowledge_block'];
			$training_form_data['curriculum'] = $item['curriculum'];
			$training_form_data['essay_mark'] = $item['essay_mark'];
			$training_form_data['major_name'] = self::getValueByKey('major', 'major_name',array('status' => array('1'), 'id' => array($item['id_major'])));
			$training_form_data['knowledge_block_name'] = self::getValueByKey('knowledge_block', 'title',array('status' => array('1'), 'id' => array($item['id_knowledge_block'])));			
			array_push($trainingForm,$training_form_data);
		}
		//print_f($trainingForm);
		return $trainingForm;
	}
}
?>