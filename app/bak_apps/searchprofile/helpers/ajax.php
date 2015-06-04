<?php
define('AREA','A');
require '../../../init.php';
require_once ('../searchprofile.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";

$db = new Database;
$prefix = DB_TABLE_PREFIX;

switch ($act) {
	case 'changeCourse':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$html = '<ul>';
		$courseInfo = $db->getRow('course', '*', array('id' => $course), PDO::FETCH_OBJ);
		$html .= '<li>';
		$html .= '<span><i class="glyphicon glyphicon-folder-open"></i> '. $courseInfo->course_name .'</span>';
		$html .= '<ul>';
		$group_fields = $db->queryAll("SELECT * FROM {$prefix}group_field WHERE group_field_code <> 'NONE' AND status = 1 ORDER BY id ASC", array(), PDO::FETCH_OBJ);
		foreach ($group_fields as $item) {
			$html .= '<li><span><i class="glyphicon glyphicon-circle-arrow-right"></i> '.$item->group_field_name.'</span>';
			$classes = $db->queryAll("SELECT * FROM {$prefix}class WHERE id_course = :course AND id_group_field = :group_field AND status = 1", array(':course' => $course, ':group_field' => $item->id), PDO::FETCH_OBJ);
			if (!empty($classes)) {
				$html .= '<ul>';
				foreach ($classes as $class) {
					$html .= '<li><span class="classes" id="'.$class->id.'">'.$class->class_name.'</span>';
				}
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		$html .= '</li>';
		echo $html;
		break;
	case 'changeClass':
		$class = isset($_POST['id_class']) ? intval($_POST['id_class']) : 0;
		$html = '';
		$i = 1;
		if ($class > 0){
			$students = SearchProfileApp::getListStudent($class);
			$students = reSort($students, array('first_name', 'last_name'));
			foreach ($students as $student) {
				$html .= '<tr>
					<td>'.$i.'</td>
					<td>'.$student->student_code.'</td>
					<td>'.$student->last_name.' '.$student->first_name.'</td>
					<td>'.$student->birthday.'</td>
					<td>'.($student->sex == 1 ? 'Nam' : 'Nữ').'</td>
					<td>'.studentStatus($student->status).'</td>
					<td><button class="btn btn-xs btn-primary viewDetail" id="'.$student->id.'"><i class="glyphicon glyphicon-eye-open"></i> Xem</button></td>
				</tr>';
				$i++;
			}
		}
		echo $html;
	case 'viewDetail':
		$id_student = isset($_POST['student']) ? intval($_POST['student']) : 0;
		$html = '';
		if ($id_student > 0){
			$st = $prefix.'student';
			$pr = $prefix.'profile';
			$pv = $prefix.'provice';
			$cl = $prefix.'class';
			$cu = $prefix.'course';
			$gr = $prefix.'group_field';
			$mj = $prefix.'major';
			$query = "SELECT pr.first_name, pr.last_name, pr.birthday, pr.sex, pr.email, pr.phone, pr.id_card, st.student_code, pv.name, cl.class_name, mj.major_name, cu.course_name, cu.period, gr.group_field_name FROM {$st} st, {$pr} pr, {$pv} pv, {$cl} cl, {$cu} cu, {$gr} gr, {$mj} mj WHERE st.id_profile = pr.id AND st.id_class = cl.id AND st.id_major = mj.id AND pr.birth_place = pv.id AND cl.id_course = cu.id AND cl.id_group_field = gr.id AND st.id = :id_student";
			$result = $db->query($query, array(':id_student' => $id_student), PDO::FETCH_OBJ);
			$html = '
			<table class="table table-striped">
				<tbody>
					<tr>
						<th class="col-sm-2">Họ và tên:</th>
						<td class="col-sm-4">'.$result->last_name.' '.$result->first_name.'</td>
						<th class="col-sm-2">Mã học viên:</th>
						<td class="col-sm-4">'.$result->student_code.'</td>
					</tr>
					<tr>
						<th>Giới tính:</th>
						<td>'.($result->sex == 1 ? 'Nam' : 'Nữ').'</td>
						<th>Lớp:</th>
						<td>'.$result->class_name.'</td>
					</tr>
					<tr>
						<th>Ngày sinh:</th>
						<td>'.$result->birthday.'</td>
						<th>Khóa:</th>
						<td>'.$result->course_name.' ('.$result->period.')</td>
					</tr>
					<tr>
						<th>Nơi sinh:</th>
						<td>'.$result->name.'</td>
						<th>Ngành:</th>
						<td>'.$result->group_field_name.'</td>
					</tr>
					<tr>
						<th>Điện thoại:</th>
						<td>'.$result->phone.'</td>
						<th>Chuyên ngành:</th>
						<td>'.$result->major_name.'</td>
					</tr>
					<tr>
						<th>Email:</th>
						<td colspan="3">'.$result->email.'</td>
					</tr>
					<tr>
						<th>Số CMND:</th>
						<td colspan="3">'.$result->id_card.'</td>
					</tr>
				</tbody>
			</table>
			';
		}
		echo $html;
		break;
	default:
		break;
}
?>