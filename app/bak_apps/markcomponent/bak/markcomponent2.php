<?php
/**
 * App nhập điểm thành phần
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
class MarkComponentApp extends AppObject {
    public $app_name="markcomponent";
    public $dir_layout="backend"; // thư mục chứa layout trong skin
    public $page_title = "Nhập điểm thành phần cho sinh viên";
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
        MarkComponentApp::views();

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
        // Get list Year
        $this->year = self::listYear();
        // Get list Term
        $this->term = self::listTerm();
        // Get list group_field
        $this->group_field = self::listGroup_field();
         // Get list Subject
         $this->subjects=self::listSubject();
         // Get list Class
         $this->classes=self::listClass();
        // Get list Profile
        $this->items=self::listMarkComponent();
		
        parent::display();
    }
   
	/**
     * function trả lại danh sách class
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listClassAjax($id_course="", $id_group_field="") {
        $db = new Database;
        $class = 'class';
        $where="";
		$classAjax = array();
        $condition = array('status' => array('1'));
        if(!empty($id_course) && is_numeric($id_course))
            $condition['id_course'] = array(intval($id_course));
        if(!empty($id_group_field) && is_numeric($id_group_field))
            $condition['id_group_field'] = array(intval($id_group_field));
        $result = $db->getRows($class, '*',$condition);
		foreach ($result as $item) {
            $classAjax[$item['id']] = $item['class_name'];
        }
		return $classAjax;
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
        $table_profile = 'profile';
		$profile = array();
		$profileAll = array();
        $condition = array('status' => array('1'), 'id' => array($id_profile));      
		$result_profile = $db->getRows($table_profile, '*', $condition);		
		foreach ($result_profile as $item) {
			$profile['name'] = $item['last_name'].' '.$item['first_name'];
			$profile['sex'] = ($item['sex'] == 1) ? 'Nam' : 'Nữ';
			$profile['birthday'] = $item['birthday'];
			$profile['email'] = $item['email'];
			$profile['phone'] = $item['phone'];
			$profile['birth_place'] = self::getProviceNameById($item['birth_place']);						
			 array_push($profileAll,$profile);
		} 		
		//print_r($profileAll);
		return $profileAll;
		
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
			
			$_arr = explode('_', $period['period']); //2008_2010
			$p1 = $_arr[0]+1;		//2009
			$year_1 = $_arr[0]."_".$p1;	//2008_2009
			$year_2 = $p1."_".$_arr[1];	//2009_2010
			$year_list = "'".$year_1."','".$year_2."'"; //2008_2009,2009_2010
		}
		if(!empty($year_list)){			
			$where= "`year` IN (".$year_list.")";	// lấy year trong list 2008_2009,2009_2010
		}
        $result = $db->getRows($table, '*',$where);
		if(is_array($result))
		{
			foreach ($result as $item) {
				$courses[$item['year']] = $item['year'];
			}
			arsort($courses);
		}
		else
		{
			$courses = "";
		}
        return $courses;
    }
	/**
     * function trả lại danh sách Term
     * @author HieuBD
     * @return <array> trả về danh sách
     */
    public static function listTerm($year = "") {
        $db = new Database;
        $term = array(); //$year = '2013';
        $course = 'class_subject';
        $condition = array('status' => array('1'));
		if(!empty($year))
            $condition['year'] = array($year);			
        $result = $db->getRows($course, '*',$condition);
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
    public static function listGroup_field($year ="", $term="") {
        $db = new Database;
        $db2 = new Database;
        $group_field = array(); //$year = '2014'; $term = '2';
        $tables = 'class_subject';
        $groupfield = 'group_field';
        $condition = array('status' => array('1'));
		if(!empty($year))
            $condition['year'] = array($year);
		if(!empty($term) && is_numeric($term))
            $condition['term'] = array(intval($term));
        $result = $db->getRows($tables, 'id_group_field',$condition);
        foreach ($result as $item) {
			$condition2 = array('status' => array('1'), 'id' => array($item['id_group_field']));
			$result2 = $db->getRows($groupfield, 'group_field_name',$condition2);
			foreach ($result2 as $item2) {
				$group_field[$item['id_group_field']] = $item2['group_field_name'];
			}
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

   
	/*
     * function trả lại danh sách profile
     * @author ANVT
	 * @param $id_class
     * @return <array> trả về danh sách
     */
    public static function listMarkComponent($id_class = "") {
        $db = new Database;
        $db2 = new Database;
        $db3 = new Database; //$id_class = 1;
        $markComponentAll = array();
        $markComponent = array();
        $table_profile = 'profile';
        $table_student = 'student';
        $table_class_subject_details = 'class_subject_details';		
        $condition = array();       
		if(!empty($id_class) && is_numeric($id_class))
            $condition['id_class_subject'] = array(intval($id_class));
		if(!empty( $condition)){
			$result = $db->getRows($table_class_subject_details, '*', $condition);
        }else {$result = $db->getRows($table_class_subject_details, '*');}
        
        foreach ($result as $item) {
			//reset value
			/* $markComponent['id'] = "";
			$markComponent['mark_component'] = "";
			$markComponent['name'] = "";
			$markComponent['sex'] = "";
			$markComponent['birthday'] = "";
			$markComponent['student_code'] = "";
			 *///set value
			$markComponent['id'] = $item['id_student'];
			$markComponent['mark_cc'] = $item['mark_cc'];			
			$markComponent['mark_kt'] = $item['mark_kt'];			
			$markComponent['mark_component'] = $item['mark_component'];			
			$markComponent['class'] = self::getClassNameByIdStudent($item['id_student']);			
			$condition2 = array('status' => array('1'), 'id' => array($item['id_student']));				
			$result_student = $db2->getRows($table_student, '*', $condition2);			
			foreach ($result_student as $item_student) {				
				$markComponent['student_code'] = $item_student['student_code'];	
				$id_profile = $item_student['id_profile'];				
			}	
			$condition3 = array('status' => array('1'), 'id' => array($id_profile));
			$result_profile = $db3->getRows($table_profile, '*', $condition3);
			foreach ($result_profile as $item_profile) {
				$markComponent['name'] = $item_profile['last_name'].' '.$item_profile['first_name'];
				$markComponent['sex'] = ($item_profile['sex'] == 1) ? 'Nam' : 'Nữ';
				$markComponent['birthday'] = $item_profile['birthday'];
				$markComponent['birth_place'] =	self::getProviceNameById($item_profile['birth_place']);
			}			

			//print_r($result_profile);			
			array_push($markComponentAll,$markComponent);
		}
		return $markComponentAll;
	}
}
?>