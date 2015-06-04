<?php
define('AREA','A');
require '../../../init.php';
require_once ('../exammain.php');

$act = isset($_POST['act']) ? substr(trim($_POST['act']),0,10) : "";
switch ($act) {
	// Thêm mới dữ liệu
	case 'addnew':
		$id_course = isset($_POST['id_course']) ? trim($_POST['id_course']) : "";
		$term = isset($_POST['term']) ? trim($_POST['term']) : "";
		$exam_name = isset($_POST['exam_name']) ? trim($_POST['exam_name']) : 0;
		
		$returndata = '';
		if (empty($id_course) || empty($term) || empty($exam_name)) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'id_course' => $id_course,
				'term' => $term,
				'name' => $exam_name,
				'status' => '1'
			);
			$state = ExamMainApp::addnewExam($data);
			if ($state) {
				$status = 'success';
				$message = 'Thêm mới thành công';	
				//$returndata = $state.';'.ExamMainApp::getValueByKey('course', 'course_name', array('id'=> $id_course)).';'.$term.';'.$exam_name;
				$dataTable = ExamMainApp::listExamList();			
				$i = 0;
				$html = "";
				foreach ($dataTable as $item) {
					$i++;
                       
					$html .=  "<tr id=\"exam_".$item['id_exam']."\">".
                        "<td>$i</td>".
                        "<td class=\"course_name\">".$item['course_name']."</td>".
                        "<td class=\"term\">".$item['term']."</td>".
                        "<td class=\"exam_name\">".$item['exam_name']."</td>".
                        "<td><input type=\"hidden\" name=\"control_str\" class=\"control_str\" value=\"".$item['id_exam'].";".$item['id_course'].";".$item['term'].";".$item['exam_name']."\">".
                            "<button class=\"btn btn-xs btn-warning editBtn\" data-id=\"".$item['id_exam']."\"><i class=\"glyphicon glyphicon-pencil\"></i> Sửa</button>".
                            "<button class=\"btn btn-xs btn-danger deleteBtn\" data-id=\"".$item['id_exam']."\"><i class=\"glyphicon glyphicon-remove\"></i> Xóa</button>".
                        "</td>".
                    "</tr>";
				}
				$returndata = $html;
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi: Không thể thêm mới thông tin. Vui lòng thử lại';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	// Cập nhật dữ liệu
	case 'update':
		$id = isset($_POST['id_exam']) ? intval($_POST['id_exam']) : 0;
		$id_course = isset($_POST['id_course']) ? trim($_POST['id_course']) : "";
		$term = isset($_POST['term']) ? trim($_POST['term']) : "";
		$exam_name = isset($_POST['exam_name']) ? trim($_POST['exam_name']) : 0;
		$returndata = '';
		if($id == 0){
			$status = 'danger';
			$message = 'Dữ liệu không chính xác. Nhấn F5 để tải lại trang và thử lại';
		}
		else if (empty($id) || empty($id_course) || empty($term) || empty($exam_name)) {
			$status = 'danger';
			$message = 'Không được bỏ trống các trường thông tin';
		}
		else{
			$data = array(
				'id_course' => $id_course,
				'term' => $term,
				'name' => $exam_name,
				'status' => '1'
			);
			
			$state = ExamMainApp::updateExam($data, $id);
			if ($state) {
				$status = 'success';
				$message = 'Cập nhật dữ liệu thành công';
				$dataTable = ExamMainApp::listExamList();			
				$i = 0;
				$html = "";
				foreach ($dataTable as $item) {
					$i++;
                       
					$html .=  "<tr id=\"exam_".$item['id_exam']."\">".
                        "<td>$i</td>".
                        "<td class=\"course_name\">".$item['course_name']."</td>".
                        "<td class=\"term\">".$item['term']."</td>".
                        "<td class=\"exam_name\">".$item['exam_name']."</td>".
                        "<td><input type=\"hidden\" name=\"control_str\" class=\"control_str\" value=\"".$item['id_exam'].";".$item['id_course'].";".$item['term'].";".$item['exam_name']."\">".
                            "<button class=\"btn btn-xs btn-warning editBtn\" data-id=\"".$item['id_exam']."\"><i class=\"glyphicon glyphicon-pencil\"></i> Sửa</button>".
                            "<button class=\"btn btn-xs btn-danger deleteBtn\" data-id=\"".$item['id_exam']."\"><i class=\"glyphicon glyphicon-remove\"></i> Xóa</button>".
                        "</td>".
                    "</tr>";
				}
				$returndata = $html;
			} else {
				$status = 'danger';
				$message = 'Đã có lỗi trong quá trình cập nhật dữ liệu. Cập nhật không thành công. Vui lòng thử lại!';
			}
		}
		echo json_encode(array('status' => $status, 'message' => $message, 'returndata' => $returndata));
		break;
	// Xóa dữ liệu
	case 'delete':
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if($id != 0)
			$state = ExamMainApp::hideExam($id);
		if($state)
			echo 'success';
		break;
	default:
		break;
}
?>
