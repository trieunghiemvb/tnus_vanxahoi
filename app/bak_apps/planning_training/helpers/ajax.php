<?php

define('AREA', 'A');
require '../../../init.php';
require_once ('../planning_training.php');
$act = isset($_POST['act']) ? substr(trim($_POST['act']), 0, 30) : "";
switch ($act) {
    case 'load_year':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $id_course = isset($str_data) ? intval($str_data) : "";
        $status = "";
        $message = "";
        $returndata = "";
        $arr_year = Planning_trainingApp::get_select_year($id_course);
        //print_r($arr_year);
        foreach ($arr_year as $item) {
            $returndata.=$item . ";";
        }
        $returndata = rtrim($returndata, ';');
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'returndata' => $returndata));
        break;

    case 'load_term':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_course = isset($arr_data[0]) ? $arr_data[0] : "";
        $year = isset($arr_data[1]) ? $arr_data[1] : "";
        $status = "";
        $message = "";
        $returndata = "";
        $arr_term = Planning_trainingApp::get_select_term($id_course, $year);
        foreach ($arr_term as $item) {
            $returndata.=$item . ";";
        }
        $returndata = rtrim($returndata, ';');
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'returndata' => $returndata));
        break;

    // Select group_field
    case 'load_major':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_group_field = isset($arr_data[0]) ? $arr_data[0] : "";
        $status = "";
        $message = "";
        // set $str_major
        $str_major = "";
        if ($id_group_field != "") {
            $arr_major = Planning_trainingApp::get_select_table('major', 'id', 'major_name', array(
                        'status' => '1',
                        'id_group_field' => $id_group_field));
        } else {
            $arr_major = Planning_trainingApp::get_select_table('major', 'id', 'major_name', array(
                        'status' => '1'));
        }
        //print_r($arr_major);
        foreach ($arr_major as $key => $value) {
            $row = $key . ";" . $value;
            $str_major.=$row . "#";
        }
        $str_major = rtrim($str_major, '#');

        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'str_major' => $str_major)
        );
        break;

    // Load general subject
    case 'load_sub_gen':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_group_field = isset($arr_data[0]) ? $arr_data[0] : "0";
        $id_course = isset($arr_data[1]) ? $arr_data[1] : "0";
        $id_major = isset($arr_data[2]) ? $arr_data[2] : "0";
        $status = "";
        $message = "";
        $str_subject = "";
        if ($id_course != "0") {
            $formula = Planning_trainingApp::get_default_formula_by_course($id_course);
            $per_element = $formula["element_percent"];
            $per_test = $formula["test_percent"];
            // Môn chung
            $arr_subject = Planning_trainingApp::get_subject_by_knowledge_block($id_course, $id_major, "1,6");
            foreach ($arr_subject as $item) {
                $row = $item['id'] . ";" . $item['code'] . ";" . $item['name'] . ";" . $item['curriculum'] . ";" . $per_element . ";" . $per_test;
                $str_subject.=$row . "#";
            }
            $str_subject = rtrim($str_subject, '#');
        }
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'str_subject' => $str_subject)
        );
        break;

    // Load subject by group_field
    case 'load_sub_base':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_group_field = isset($arr_data[0]) ? $arr_data[0] : "0";
        $id_course = isset($arr_data[1]) ? $arr_data[1] : "0";
        $id_major = isset($arr_data[2]) ? $arr_data[2] : "0";
        $status = "";
        $message = "";
        $str_subject = "";
        if ($id_course != "0" && $id_group_field != "0") {
            $formula = Planning_trainingApp::get_default_formula_by_course($id_course);
            $per_element = $formula["element_percent"];
            $per_test = $formula["test_percent"];
            $arr_subject = Planning_trainingApp::get_subject_by_group_field($id_group_field, $id_course, $id_major, '2,3');
            foreach ($arr_subject as $item) {
                $row = $item['id'] . ";" . $item['code'] . ";" . $item['name'] . ";" . $item['curriculum'] . ";" . $per_element . ";" . $per_test . ";" . $item['id_knowledge_block'];
                $str_subject.=$row . "#";
            }
            $str_subject = rtrim($str_subject, '#');
        }
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'str_subject' => $str_subject)
        );
        break;

    // Load subject by major
    case 'load_sub_major':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_group_field = isset($arr_data[0]) ? $arr_data[0] : "0";
        $id_course = isset($arr_data[1]) ? $arr_data[1] : "0";
        $id_major = isset($arr_data[2]) ? $arr_data[2] : "0";
        $status = "";
        $message = "";
        $str_subject = "";
        if ($id_course != "0" && $id_major != "0") {
            $formula = Planning_trainingApp::get_default_formula_by_course($id_course);
            $per_element = $formula["element_percent"];
            $per_test = $formula["test_percent"];
            $arr_subject = Planning_trainingApp::get_subject_by_major($id_course, $id_major, '4,5');
            foreach ($arr_subject as $item) {
                $row = $item['id'] . ";" . $item['code'] . ";" . $item['name'] . ";" . $item['curriculum'] . ";" . $per_element . ";" . $per_test . ";" . $item['id_knowledge_block'];
                $str_subject.=$row . "#";
            }
            $str_subject = rtrim($str_subject, '#');
        }
        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'str_subject' => $str_subject)
        );
        break;

    case 'load_plan':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_course = isset($arr_data[0]) ? $arr_data[0] : "";
        $id_major = isset($arr_data[1]) ? $arr_data[1] : "";
        //echo "id_major=".$id_major;
        $status = "";
        $message = "";
        $str_sub_term1 = "";
        $str_sub_term2 = "";
        $str_sub_term3 = "";
        $str_sub_term4 = "";
        $str_kn_major_sum = "0;0;0;0;0";
        $str_current_kn_major_sum = "0;0;0;0;0";
        if ($id_course != "" && $id_major != "") {
            $status = "success";
            $message = "Tải xong kế hoạch đào tạo.";
            $arr_term1 = Planning_trainingApp::get_training_plan($id_course, $id_major, 1);
            //print_r($arr_term1);
            $arr_term2 = Planning_trainingApp::get_training_plan($id_course, $id_major, 2);
            $arr_term3 = Planning_trainingApp::get_training_plan($id_course, $id_major, 3);
            $arr_term4 = Planning_trainingApp::get_training_plan($id_course, $id_major, 4);

            foreach ($arr_term1 as $item) {
                $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
                //print_r($subject);
                $cal = $item['caculate'] == 1 ? "Có" : "Không";
                $str_sub_term1.=$item['id_subject'] . ";" . $subject['name'] . ";" . $subject['code'] .
                        ";" . $item['curriculum'] . ";" . $cal . ";" . $item['mark_formula'] . ";" . $item['id'] . "#";
            }
            $str_sub_term1 = rtrim($str_sub_term1, '#');

            foreach ($arr_term2 as $item) {
                $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
                $cal = $item['caculate'] == 1 ? "Có" : "Không";
                $str_sub_term2.=$item['id_subject'] . ";" . $subject['name'] . ";" . $subject['code'] .
                        ";" . $item['curriculum'] . ";" . $cal . ";" . $item['mark_formula'] . ";" . $item['id'] . "#";
            }
            $str_sub_term2 = rtrim($str_sub_term2, '#');

            foreach ($arr_term3 as $item) {
                $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
                $cal = $item['caculate'] == 1 ? "Có" : "Không";
                $str_sub_term3.=$item['id_subject'] . ";" . $subject['name'] . ";" . $subject['code'] .
                        ";" . $item['curriculum'] . ";" . $cal . ";" . $item['mark_formula'] . ";" . $item['id'] . "#";
            }
            $str_sub_term3 = rtrim($str_sub_term3, '#');

            foreach ($arr_term4 as $item) {
                $subject = Planning_trainingApp::get_subject_by_id($item['id_subject']);
                $cal = $item['caculate'] == 1 ? "Có" : "Không";
                $str_sub_term4.=$item['id_subject'] . ";" . $subject['name'] . ";" . $subject['code'] .
                        ";" . $item['curriculum'] . ";" . $cal . ";" . $item['mark_formula'] . ";" . $item['id'] . "#";
            }
            $str_sub_term4 = rtrim($str_sub_term4, '#');

            // Thống kê khối kiến thức
            $r1 = Planning_trainingApp::get_sum_curriculum(1, $id_major); // Kiến thức chung
            $r2 = Planning_trainingApp::get_sum_curriculum(2, $id_major); // Kiến thức cơ sở bắt buộc
            $r3 = Planning_trainingApp::get_sum_curriculum(3, $id_major); // Kiến thức cơ sở tự chọn
            $r4 = Planning_trainingApp::get_sum_curriculum(4, $id_major); // Kiến thức chuyên ngành tự chọn
            $r5 = Planning_trainingApp::get_sum_curriculum(5, $id_major); // Kiến thức chuyên ngành bắt buộc
            $r6 = Planning_trainingApp::get_sum_curriculum(6, $id_major); // Luận văn
            $str_kn_major_sum = $r1['curriculum'] . ";" . $r2['curriculum'] . ";" . $r3['curriculum'] . ";" . $r4['curriculum'] . ";" . $r5['curriculum'] . ";" . $r6['curriculum'];
            $str_kn_major_sum = rtrim($str_kn_major_sum, ';');
            // Thống kê khối kiến thức hiện tại
            $r1 = Planning_trainingApp::get_current_sum_curriculum(1, $id_major); // Kiến thức chung
            $r2 = Planning_trainingApp::get_current_sum_curriculum(2, $id_major); // Kiến thức cơ sở bắt buộc
            $r3 = Planning_trainingApp::get_current_sum_curriculum(3, $id_major); // Kiến thức cơ sở tự chọn
            $r4 = Planning_trainingApp::get_current_sum_curriculum(4, $id_major); // Kiến thức chuyên ngành tự chọn
            $r5 = Planning_trainingApp::get_current_sum_curriculum(5, $id_major); // Kiến thức chuyên ngành bắt buộc
            $r6 = Planning_trainingApp::get_current_sum_curriculum(6, $id_major); // Luận văn
            $str_current_kn_major_sum = $r1['sum_curriculum'] . ";" . $r2['sum_curriculum'] . ";" . $r3['sum_curriculum'] . ";" . $r4['sum_curriculum'] . ";" . $r5['sum_curriculum'] . ";" . $r6['sum_curriculum'];
            $str_current_kn_major_sum = rtrim($str_current_kn_major_sum, ';');
        } else {
            $status = 'warning';
            $message = 'Chú ý: Để tải kế hoạch đào tạo phải chọn đủ khóa và chuyên ngành.';
        }

        echo json_encode(array(
            'status' => $status,
            'message' => $message,
            'str_sub_term1' => $str_sub_term1,
            'str_sub_term2' => $str_sub_term2,
            'str_sub_term3' => $str_sub_term3,
            'str_sub_term4' => $str_sub_term4,
            'str_kn_major_sum' => $str_kn_major_sum,
            'str_current_kn_major_sum' => $str_current_kn_major_sum,
        ));
        break;

    case 'add_plan':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_course = isset($arr_data[0]) ? $arr_data[0] : "";
        $id_major = isset($arr_data[1]) ? $arr_data[1] : "";
        $term = isset($arr_data[2]) ? $arr_data[2] : "";
        $str_sub_cur = isset($arr_data[3]) ? $arr_data[3] : "";
        $str_sub_cur = rtrim($str_sub_cur, '#');
        $arr_sub_cur = split('#', $str_sub_cur);

        foreach ($arr_sub_cur as $item) {
            $arr_sb = split('!', $item);
            $id_sub = $arr_sb[0];
            $curriculum = $arr_sb[1];
            $formula = $arr_sb[2] . "/" . $arr_sb[3];
            $calculate = $arr_sb[4];
            Planning_trainingApp::add_plan($id_course, $id_major, $term, $id_sub, $curriculum, $calculate, $formula);
        }
        $status = "success";
        $message = "Thêm thành công " . count($arr_sub_cur) . " môn vào kế hoạch đào tạo";

        echo json_encode(array(
            'status' => $status,
            'message' => $message));
        break;

    case 'inherit_plan':
        $str_data = isset($_POST['str_post_data']) ? trim($_POST['str_post_data']) : "";
        $arr_data = explode(";", $str_data);
        $id_course = isset($arr_data[0]) ? $arr_data[0] : "";
        $id_course_in = isset($arr_data[1]) ? $arr_data[1] : "";
        $id_major = isset($arr_data[2]) ? $arr_data[2] : "";
        $id_major_in = isset($arr_data[3]) ? $arr_data[3] : "";
        $term = isset($arr_data[2]) ? $arr_data[2] : "";
        if (Planning_trainingApp::inherit_plan($id_course, $id_major, $id_course_in, $id_major_in)) {
            $status = "success";
            $message = "Kế thừa thành công kế hoạch đào tạo!";
        } else {
            $status = "danger";
            $message = "Kế thừa thất bại kế hoạch đào tạo!";
        }
        echo json_encode(array(
            'status' => $status,
            'message' => $message));
        break;

    case 'del_plan':
        $id_plan = isset($_POST['str_post_data']) ? intval($_POST['str_post_data']) : 0;
        if ($id_plan != 0)
            $state = Planning_trainingApp::remove_plan($id_plan);
        if ($state) {
            $status = "success";
            $message = "Xóa thành công! ";
        }
        echo json_encode(array(
            'status' => $status,
            'message' => $message));
        break;

    default:
        break;
}
?>
