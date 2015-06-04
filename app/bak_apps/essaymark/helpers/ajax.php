<?php
define('AREA','A');
require '../../../init.php';
require_once ('../essaymark.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,20) : "";

switch ($act) {
	case 'getClass':
		$course = isset($_POST['course']) ? intval($_POST['course']) : 0;
		$group_field = isset($_POST['group_field']) ? intval($_POST['group_field']) : 0;
		$html = '<option value="">-- Chọn lớp --</option>';
		if ($course > 0){
			$classes = EssayMarkApp::getListClass($course, $group_field);
			foreach ($classes as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		echo $html;
		break;
	case 'classDetails':
		$class = isset($_POST['id_class']) ? intval($_POST['id_class']) : 0;
		$html = '';
		if ($class > 0){
			$students = EssayMarkApp::getListStudent($class);
			if (is_array($students)){
				$students = reSort($students, array('first_name', 'last_name'));
				$i = 1;
				foreach ($students as $student) {
					$disabled = ($student->date_protect != "" && $student->mark != "") || $student->status == 2 ? 'disabled="disabled"' : '';
					$html .= '<tr data-id="'.$student->id.'">
						<td class="text-center">'.$i.'</td>
						<td class="text-center">'.$student->student_code.'</td>
						<td class="">'.$student->last_name.' '.$student->first_name.'</td>
						<td class="">'.$student->vn_name.'</td>
						<td class=""><input type="text" class="form-control input-sm" name="essay['.$student->id.'][date_protect]" value="'.$student->date_protect.'" '.$disabled.'></td>
						<td class=""><input type="text" class="form-control input-sm" name="essay['.$student->id.'][essay_mark]" value="'.$student->mark.'" '.$disabled.'></td>
					</tr>';
					$i++;
				}
			}
		}
		echo $html;
		break;
	case 'updateEssay':
		$essayInfo = isset($_POST['essay']) ? $_POST['essay'] : null;
		$flag = true;
		if ($essayInfo != null){
			$db = new Database;
			$table = DB_TABLE_PREFIX.'essay';
			$sql = "UPDATE {$table} SET mark = :mark, date_protect = :date_protect, status = :status WHERE id_student = :id_student AND status <> 2";
			foreach ($essayInfo as $key => $value) {
				$id_student = $key;
				$mark = $essayInfo[$id_student]['essay_mark'];
				$date_protect = $essayInfo[$id_student]['date_protect'];
				$status = 1;
				if ($mark > 6.5)
					$status = 2;
				$result = $db->query($sql, array(':mark' => $mark, ':date_protect' => $date_protect, ':status' => $status, ':id_student' => $id_student));
				if (!$result)
					$flag = false;
			}
		}
		if ($flag)
			echo '<div class="text-success">Cập nhật dữ liệu thành công</div>';
		else
			echo '<div class="text-danger">Cập nhật dữ liệu không thành công. Vui lòng kiểm tra lại</div>';
		break;
	default:
		break;
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