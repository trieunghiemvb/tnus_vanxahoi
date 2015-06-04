<?php
if (!defined('AREA')) {
    die('Access denied');
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/planning_training/css/dataTables.bootstrap.css') ?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/planning_training/css/style.css') ?>">
<script src="<?php echo AppObject::getBaseFile('app/planning_training/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/planning_training/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/planning_training/js/fnAddTr.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/planning_training/js/scripts.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/jquery.mask.min.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/jquery.download.js') ?>"></script>
<h1>Inherit</h1>
<!-- col-sm-4 -->
<div class="col-sm-2">
    <div class="well">
        <form class="form" role="form" id="form_filter" >
            <div class="form-group" >
                <label for="" class="control-label">Chọn khóa:</label>
                <select name="sel_course" id="sel_course" class="form-control input-sm sel_filter">
                    <option value="0">-Tất cả-</option>
                    <?php
                    foreach ($this->arr_course as $key => $value) {
                        ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" >
                <label for="" class="control-label">Chọn ngành học:</label>
                <select name="sel_group_field" id="sel_group_field" class="form-control input-sm sel_filter">
                    <?php
                    foreach ($this->arr_group_field as $key => $value) {
                        ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" >
                <label for="" class="control-label">Chọn chuyên ngành:</label>
                <select name="sel_major" id="sel_major" class="form-control input-sm sel_filter">
                    <option value="0">-Tất cả-</option>
                    <?php
                    foreach ($this->arr_major as $key => $value) {
                        ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" >
                <label for="" class="control-label">Chọn năm học:</label>
                <select name="sel_year" id="sel_year" class="form-control input-sm sel_filter">
                    <option value="">-Chọn năm-</option>
                </select>
            </div>
            <div class="form-group" >
                <label for="" class="control-label">Chọn học kỳ:</label>
                <select name="sel_term" id="sel_term" class="form-control input-sm sel_filter">
                    <?php
                    foreach ($this->arr_term as $key => $value) {
                        ?>
                        <option value="<?= $value ?>">Học kỳ <?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
    </div>
    <div class="well">
        <table id="action_table" class="table">
            <tbody>
                <tr align="center">
                    <td>
                        <button id="btnSave" class="btn btn-md btn-info btnSave"> Lưu <i class="glyphicon glyphicon-forward"></i></button>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <button id="btnPrint" class="btn btn-md btn-info btnPrint"> In kế hoạch đào tạo <i class="glyphicon glyphicon-forward"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="well">
        <table id="action_table" class="table">
            <tbody>
                <tr align="center">
                    <td>
                        <button id="btnSave" class="btn btn-md btn-info btnSave"> Lưu <i class="glyphicon glyphicon-forward"></i></button>
                    </td>
                </tr>
                <tr align="center">
                    <td>
                        <button id="btnPrint" class="btn btn-md btn-info btnPrint"> In kế hoạch đào tạo <i class="glyphicon glyphicon-forward"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- col-sm-4 -->
<div class="col-sm-5">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Chọn môn học cần thêm vào kế hoạch đào tạo</h4>
        </div>
        <div class="panel-body">
            <!--            Môn chung-->
            <div class="well">
                <div class="auto_scroll profile_scroll_gen" id="profile_scroll_gen">
                    <table id="list_subject_gen" class="table table-striped" data-toggle="table" data-height="100">
                        <thead>
                            <tr >
                                <th colspan="6" >MÔN CHUNG</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Mã môn</th>
                                <th>Tên môn</th>
                                <th>Số TC</th>
                                <th>Công thức (TP/Thi)</th>
                                <th>Tính điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Chọn ngành học để tải môn</td>
                            </tr>
<!--                            <tr>
                                <td>
                                    <input class='chk_subject' type='checkbox' id='chk_subject' name='chk_subject' value=''  onclick=''/>
                                </td>
                                <td>---</td>
                                <td>---</td>
                                <td><input class='txt_subject_curriculum' type='text' size="1" id='txt_subject_curriculum' name='txt_subject_curriculum' value=''/></td>
                                <td>
                                    <input class='txt_element' type='text' size="1" id='txt_element' name='txt_element' value=''/> : 
                                    <input class='txt_test' type='text' size="1" id='txt_test' name='txt_test' value='' disabled />
                                </td>
                                <td>
                                    <input class='chk_calculate' checked type='checkbox' id='chk_calculate' name='chk_calculate' value=''  onclick=''/>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
            <!--            Môn cơ sở ngành-->
            <div class="well">
                <div class="auto_scroll profile_scroll_base" id="profile_scroll_base">
                    <table id="list_subject_base" class="table table-striped" data-toggle="table" data-height="100">
                        <thead>
                            <tr>
                                <th colspan="6" >MÔN CƠ SỞ NGÀNH</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Mã môn</th>
                                <th>Tên môn</th>
                                <th>Số TC</th>
                                <th>Công thức (TP/Thi)</th>
                                <th>Loại</th>
                                <th>Tính điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Chọn chuyên ngành ngành học để tải môn</td>
                            </tr>
<!--                            <tr>
                                <td>
                                    <input class='chk_subject' type='checkbox' id='chk_subject' name='chk_subject' value=''  onclick=''/>
                                </td>
                                <td>---</td>
                                <td>---</td>
                                <td><input class='txt_subject_curriculum' type='text' size="1" id='txt_subject_curriculum' name='txt_subject_curriculum' value=''/></td>
                                <td>
                                    <input class='txt_element' type='text' size="1" id='txt_element' name='txt_element' value=''/> : 
                                    <input class='txt_test' type='text' size="1" id='txt_test' name='txt_test' value='' disabled />
                                </td>
                                <td>
                                    <input class='chk_calculate' checked type='checkbox' id='chk_calculate' name='chk_calculate' value=''  onclick=''/>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
            <!--            Môn chuyên theo chuyên ngành-->
            <div class="well"> 
                <div class="auto_scroll profile_scroll_gf" id="profile_scroll_gf">
                    <table id="list_subject_gf" class="table table-striped" data-toggle="table" data-height="300">
                        <thead>
                            <tr >
                                <th colspan="4" >MÔN CHUYÊN NGHÀNH</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Mã môn</th>
                                <th>Tên môn</th>
                                <th>Số TC</th>
                                <th>Công thức (TP:Thi)</th>
                                <th>Loại</th>
                                <th>Tính điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Chọn ngành học để tải môn</td>
                            </tr>
<!--                            <tr>
                                <td>
                                    <input class='chk_subject' type='checkbox' id='chk_subject' name='chk_subject' value=''  onclick=''/>
                                </td>
                                <td>---</td>
                                <td>---</td>
                                <td><input class='txt_subject_curriculum' type='text' size="1" id='txt_subject_curriculum' name='txt_subject_curriculum' value=''/></td>
                            </tr>
                        </tbody>-->
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
        </div>
    </div>
</div>
<!-- /.col-sm-4 -->
<!-- col-sm-4 -->
<div class="col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Kế hoạch đào tạo</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <div class="auto_scroll " id="">
                    <table id="list_subject_sumary" class="table table-striped" data-toggle="table" data-height="550">
                        <thead> 
                            <tr class="row_title">
                                <th colspan="3">Thống kê kế hoạch đào tạo</th>
                            </tr>
                            <tr>
                                <th>Khối kiến thức</th>
                                <th>Số TC tối thiểu</th>
                                <th>Số TC hiện tại</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
            <div class="well">
                <div class="auto_scroll profile_scroll_term" id="profile_scroll_term">
                    <table id="list_subject_term" class="table table-striped" data-toggle="table" data-height="550">
                        <thead> 
                            <tr>
                                <th>Mã môn</th>
                                <th>Tên môn</th>
                                <th>Số TC</th>
                                <th>CT điểm (TP/Thi)</th>
                                <th>Tính</th>
                                <th>Tác vụ</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= 4; $i++) {
                                ?>
                                <tr class="row_title"> 
                                    <th colspan="6" align='center'>HỌC KỲ <?= $i ?></th>
                                </tr>
                                <tr>
                                <tr>
                                    <td colspan="5">Chọn khóa học và  chuyên ngành để tải kế hoạch đào tạo</td>
                                </tr>
    <!--                                <tr>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td><button class="btn btn-xs btn-danger deleteBtn" data-id=""><i class="glyphicon glyphicon-remove"></i> Xóa</button></td> 
                                </tr>-->
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>

        </div>
    </div>
</div>
<!-- /.col-sm-5 -->
<!--  proccess scripts -->
<script type="text/javascript">
    function load_plan(id_course, id_major) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_course + ";" + id_major;
        $("#list_subject_term > tbody").html("");
        $("#list_subject_sumary > tbody").html("");
        $.post(ajaxUrl, {act: 'load_plan', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            // thống kê khung chương trình theo chuyên ngành
            var str_kn_major_sum = data.str_kn_major_sum !== "" ? data.str_kn_major_sum : "0;0;0;0;0;0";
            var row_kn_major_sum = str_kn_major_sum.split(';');
            var str_current_kn_major_sum = data.str_current_kn_major_sum !== "" ? data.str_current_kn_major_sum : "0;0;0;0;0;0";
            var row_current_kn_major_sum = str_current_kn_major_sum.split(';');
            var _r = "<tr>";
            _r += "<td>Kiến thức chung</td>";
            _r += "<td>" + row_kn_major_sum[0] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[0] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);
            var _r = "<tr>";
            _r += "<td>Kiến thức cơ sở bắt buộc</td>";
            _r += "<td>" + row_kn_major_sum[1] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[1] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);
            var _r = "<tr>";
            _r += "<td>Kiến thức cơ sở tự chọn</td>";
            _r += "<td>" + row_kn_major_sum[2] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[2] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);
            var _r = "<tr>";
            _r += "<td>Kiến thức chuyên ngành bắt buộc</td>";
            _r += "<td>" + row_kn_major_sum[3] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[3] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);
            var _r = "<tr>";
            _r += "<td>Kiến thức chuyên ngành tự chọn</td>";
            _r += "<td>" + row_kn_major_sum[4] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[4] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);
            var _r = "<tr>";
            _r += "<td>Luận văn tốt nghiệp</td>";
            _r += "<td>" + row_kn_major_sum[5] + "</td>";
            _r += "<td>" + row_current_kn_major_sum[5] + "</td>";
            _r += "</tr>";
            $('#list_subject_sumary > tbody').append(_r);

            var str_sub_term1 = data.str_sub_term1;
            var row_sub_term1 = str_sub_term1.split('#');
            var _r = "<tr class='row_title'><th colspan='6' >HỌC KỲ 1</th></tr>";
            $('#list_subject_term > tbody').append(_r);
            $.each(row_sub_term1, function (key, value) {
                var tmp = value.split(';');
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td>" + tmp[3] + "</td>" +
                            "<td>" + tmp[5] + "</td>" +
                            "<td>" + tmp[4] + "</td>" +
                            "<td><button value='" + tmp[6] + "' id='#btn_del_sub_" + tmp[6] + "'  class='btn btn-xs btn-danger deleteBtn' data-id='" + tmp[6] + "'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td> " +
                            "</tr>";
                    $('#list_subject_term > tbody').append(r);
                }
            });

            var str_sub_term2 = data.str_sub_term2;
            var row_sub_term2 = str_sub_term2.split('#');
            var _r = "<tr class='row_title'><th colspan='6' >HỌC KỲ 2</th></tr>";
            $('#list_subject_term > tbody').append(_r);
            $.each(row_sub_term2, function (key, value) {
                var tmp = value.split(';');
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td>" + tmp[3] + "</td>" +
                            "<td>" + tmp[5] + "</td>" +
                            "<td>" + tmp[4] + "</td>" +
                            "<td><button value='" + tmp[6] + "' id='#btn_del_sub_" + tmp[6] + "'  class='btn btn-xs btn-danger deleteBtn' data-id='" + tmp[6] + "'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td> " +
                            "</tr>";
                    $('#list_subject_term > tbody').append(r);
                }
            });

            // get str_sub_term3
            var str_sub_term3 = data.str_sub_term3;
            var row_sub_term3 = str_sub_term3.split(
                    '#');
            var _r = "<tr class='row_title'><th colspan='6' >HỌC KỲ 3</th></tr>";
            $(
                    '#list_subject_term > tbody').
                    append(
                            _r);
            $.each(
                    row_sub_term3,
                    function (
                            key,
                            value) {
                        var tmp = value.split(
                                ';');
                        if (tmp[0] !== null && tmp[0] !== "") {
                            var r = "<tr>" +
                                    "<td>" + tmp[1] + "</td>" +
                                    "<td>" + tmp[2] + "</td>" +
                                    "<td>" + tmp[3] + "</td>" +
                                    "<td>" + tmp[5] + "</td>" +
                                    "<td>" + tmp[4] + "</td>" +
                                    "<td><button value='" + tmp[6] + "' id='#btn_del_sub_" + tmp[6] + "'  class='btn btn-xs btn-danger deleteBtn' data-id='" + tmp[6] + "'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td> " +
                                    "</tr>";
                            $('#list_subject_term > tbody').append(r);
                        }
                    });

            // get str_sub_term4
            var str_sub_term4 = data.str_sub_term4;
            var row_sub_term4 = str_sub_term4.split(
                    '#');
            var _r = "<tr class='row_title'><th colspan='6' >HỌC KỲ 4</th></tr>";
            $('#list_subject_term > tbody').append(_r);
            $.each(
                    row_sub_term4,
                    function (key, value) {
                        var tmp = value.split(
                                ';');
                        if (tmp[0] !== null && tmp[0] !== "") {
                            var r = "<tr>" +
                                    "<td>" + tmp[1] + "</td>" +
                                    "<td>" + tmp[2] + "</td>" +
                                    "<td>" + tmp[3] + "</td>" +
                                    "<td>" + tmp[5] + "</td>" +
                                    "<td>" + tmp[4] + "</td>" +
                                    "<td><button value='" + tmp[6] + "' id='#btn_del_sub_" + tmp[6] + "'  class='btn btn-xs btn-danger deleteBtn' data-id='" + tmp[6] + "'><i class='glyphicon glyphicon-remove'></i> Xóa</button></td> " +
                                    "</tr>";
                            $('#list_subject_term > tbody').append(r);
                        }
                    });

        });
        return true;
    }

    function load_major(id_group_field) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_group_field + ";;";
        $.post(ajaxUrl, {act: 'load_major', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            // get major
            var str_major = data.str_major;
            var row_major = str_major.split(
                    '#');
            //console.log('row: '+ row);
            // xóa option của select sel_year trừ option đầu tiên
            $(
                    '#sel_major').
                    find(
                            'option:not(:first)').
                    remove();
            var i = 0;
            $.each(
                    row_major,
                    function (key, value) {
                        i++;
                        var tmp = value.split(';');
                        //console.log('tmp: '+ tmp);
                        if (tmp[0] !== null && tmp[0] !== "") {
                            $(
                                    '#sel_major').
                                    append(
                                            '<option value="' + tmp[0] + '">' + tmp[1] + '</option>');
                        }
                    });

        });
        return true;
    }

    function load_sub_gen(id_group_field, id_course, id_major) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_group_field + ";" + id_course + ";" + id_major;
        $.post(ajaxUrl, {act: 'load_sub_gen', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            // Môn chung
            // clear table
            $("#list_subject_gen > tbody").html("");
            var str_subject = data.str_subject;
            var row_subject = str_subject.split('#');
            $.each(row_subject, function (key, value) {
                var tmp = value.split(';');
                // tmp:0['id'] .1['code'] .2['name'] .3['curriculum'].4. $per_element.5. $per_test
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr id='row_" + tmp[0] + "' class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")'/>" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' onchange='val_curriculum(this.value, " + tmp[0] + ")'  /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")'  /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled />";
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_gen > tbody').append(r);
                }
            });

        });
        return true;
    }

    function load_sub_base(id_group_field, id_course, id_major) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_group_field + ";" + id_course + ";" + id_major;
        $.post(ajaxUrl, {act: 'load_sub_base', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            // Môn chung
            // clear table
            $("#list_subject_base > tbody").html("");
            var str_subject = data.str_subject;
            var row_subject = str_subject.split('#');
            $.each(row_subject, function (key, value) {
                var tmp = value.split(';');
                // tmp:0['id'] .1['code'] .2['name'] .3['curriculum'].4. $per_element.5. $per_test
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var chk_must = "Tự chọn";
                    //console.log("tmp[6]="+tmp[6]);
                    if (tmp[6] === '2') {
                        chk_must = "Bắt buộc";
                    }
                    var r = "<tr id='row_" + tmp[0] + "' class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")'/>" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' onchange='val_curriculum(this.value, " + tmp[0] + ")'  /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")'  /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled />";
                    r += "</td>";
                    r += "<td>";
                    r += chk_must;
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_base > tbody').append(r);
                }
            });
        });
        return true;
    }

    function load_sub_major(id_group_field, id_course, id_major) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_group_field + ";" + id_course + ";" + id_major;
        $.post(ajaxUrl, {act: 'load_sub_major', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            // Môn chung
            // clear table
            $("#list_subject_gf > tbody").html("");
            var str_subject = data.str_subject;
            var row_subject = str_subject.split('#');
            $.each(row_subject, function (key, value) {
                var tmp = value.split(';');
                // tmp:0['id'] .1['code'] .2['name'] .3['curriculum'].4. $per_element.5. $per_test
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var chk_must = "Tự chọn";
                    console.log("tmp[6]=" + tmp[6]);
                    if (tmp[6] === '4') {
                        chk_must = "Bắt buộc";
                    }
                    var r = "<tr id='row_" + tmp[0] + "' class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")'/>" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' onchange='val_curriculum(this.value, " + tmp[0] + ")'  /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")'  /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled />";
                    r += "</td>";
                    r += "<td>";
                    r += chk_must;
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_gf > tbody').append(r);
                }
            });
        });
        return true;
    }

    function load_sub(id_group_field, id_course, id_major) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_group_field + ";" + id_course + ";" + id_major;
        $.post(ajaxUrl, {act: 'load_sub', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            // Môn chung
            // clear table
            $("#list_subject_gen > tbody").html("");
            var str_subject_gen = data.str_subject_gen;
            var row_subject_gen = str_subject_gen.split('#');
            $.each(row_subject_gen, function (key, value) {
                var tmp = value.split(';');
                // tmp:0['id'] .1['code'] .2['name'] .3['curriculum'].4. $per_element.5. $per_test
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr id='row_" + tmp[0] + "' class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")'/>" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' onchange='val_curriculum(this.value, " + tmp[0] + ")'  /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")'  /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled />";
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_gen > tbody').append(r);
                }
            });

            // Môn theo ngành
            // Môn theo chuyên ngành
            // clear table
            $("#list_subject_base > tbody").html("");
            var str_subject_base = data.str_subject_base;
            var row_subject_base = str_subject_base.split('#');
            $.each(row_subject_base, function (key, value) {
                var tmp = value.split(';');
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr id='row_" + tmp[0] + "'  class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")' />" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")' /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled  />";
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_base > tbody').append(r);
                }
            });


            // Môn theo chuyên ngành
            // clear table
            $("#list_subject_gf > tbody").html("");
            var str_subject_gf = data.str_subject_gf;
            var row_subject_gf = str_subject_gf.split('#');
            $.each(row_subject_gf, function (key, value) {
                var tmp = value.split(';');
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "<tr id='row_" + tmp[0] + "'  class='row_sub' >" +
                            "<td align='center'>" +
                            "<input class='chk_subject' type='checkbox'  id='chk_subject_" + tmp[0] + "' name='chk_subject_" + tmp[0] + "' value='" + tmp[0] + "'  onclick='chk_selected(" + tmp[0] + ")' />" +
                            "</td>" +
                            "<td>" + tmp[1] + "</td>" +
                            "<td>" + tmp[2] + "</td>" +
                            "<td><input class='txt_curriculum' type='text' size='2' id='txt_curriculum_" + tmp[0] + "' name='txt_curriculum_" + tmp[0] + "' value='" + tmp[3] + "' /></td>";
                    r += "<td>";
                    r += "<input class='txt_element' type='text' size='1' id='txt_element_" + tmp[0] + "' name='txt_element_" + tmp[0] + "' value='" + tmp[4] + "' onchange='cal_formula(this.value, " + tmp[0] + ")' /> / ";
                    r += "<input class='txt_test' type='text' size='1' id='txt_test_" + tmp[0] + "' name='txt_test_" + tmp[0] + "' value='" + tmp[5] + "' disabled  />";
                    r += "</td>";
                    r += "<td>";
                    r += "<input class='chk_calculate' checked type='checkbox' id='chk_calculate_" + tmp[0] + "' name='chk_calculate_" + tmp[0] + "' value='1'  onclick=''/>";
                    r += "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_subject_gf > tbody').append(r);
                }
            });
        });
        return true;
    }

    function load_term(id_course, year) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_course + ";" + year;
        $.post(ajaxUrl, {act: 'load_term', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(
                    str_response_data);
            var str_data = data.returndata;
            var row = str_data.split(
                    ';');
            $(
                    '#sel_term').
                    find(
                            'option').
                    remove();
            var i = 0;
            $.each(
                    row,
                    function (
                            key,
                            value) {
                        i++;
                        var tmp = value.split(
                                ';');
                        //console.log('tmp: '+ tmp);
                        if (tmp[0] !== null && tmp[0] !== "") {
                            $(
                                    '#sel_term').
                                    append(
                                            '<option value="' + tmp[0] + '"> Học kỳ ' + tmp[0] + '</option>');
                        }
                    });
        });
        return true;
    }

    function load_year(id_course) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_course;
        $.post(ajaxUrl, {act: 'load_year', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(
                    str_response_data);
            var str_data = data.returndata;
            var row = str_data.split(';');
            //console.log('row: ' + row);
            // xóa option của select sel_year trừ option đầu tiên
            $('#sel_year').find('option:not(:first)').remove();
            var y1 = row[0] + "_" + parseInt(parseInt(row[0]) + 1);
            var y2 = parseInt(parseInt(row[0]) + 1) + "_" + row[1];
            $('#sel_year').append('<option value="' + row[0] + '">' + y1 + '</option>');
            $('#sel_year').append('<option value="' + row[1] + '">' + y2 + '</option>');
        });
        return true;
    }

    function add_plan(id_course, id_major, term, str_sub_cur) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_course + ";" + id_major + ";" + term + ";" + str_sub_cur;
        $.post(ajaxUrl, {act: 'add_plan', str_post_data: str_post_data},
        function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            noticeMeseage.show(data.message, data.status);
        });
        return true;
    }

    function del_plan(id_plan) {
        var ajaxUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/ajax.php') ?>";
        var str_post_data = id_plan;
        $.post(ajaxUrl, {act: 'del_plan', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            noticeMeseage.show(data.message, data.status);
        });
        return true;
    }

    // Function already
    (function () {

        // Event select course
        $('#form_filter').on('change', '#sel_course', function () {
            modalLoading.show();
            var loaded = false;
            var id_course = $('#form_filter #sel_course').val();
            var id_group_field = $('#form_filter #sel_group_field').val();
            var id_major = $('#form_filter #sel_major').val();
            loaded = load_year(id_course);
            loaded = load_plan(id_course, id_major);
            loaded = load_sub_gen(id_group_field, id_course, id_major);
            loaded = load_sub_base(id_group_field, id_course, id_major);
            loaded = load_sub_major(id_group_field, id_course, id_major);
            if (loaded)
                modalLoading.hide();
        });
        // Event select year
        $('#form_filter').on('change', '#sel_year', function () {
            modalLoading.show();
            var loaded = false;
            var id_course = $('#form_filter #sel_course').val();
            var year = $('#form_filter #sel_year').val();
            loaded = load_term(id_course, year);
            if (loaded)
                modalLoading.hide();
        });

        // Event select group_field
        $('#form_filter').on('change', '#sel_group_field', function () {
            modalLoading.show();
            var loaded = false;
            var id_group_field = $('#form_filter #sel_group_field').val();
            var id_course = $('#form_filter #sel_course').val();
            loaded = load_major(id_group_field);
            var id_major = $('#form_filter #sel_major').val();
            loaded = load_plan(id_course, id_major);
            loaded = load_sub_gen(id_group_field, id_course, id_major);
            loaded = load_sub_base(id_group_field, id_course, id_major);
            loaded = load_sub_major(id_group_field, id_course, id_major);
            if (loaded)
                modalLoading.hide();
        });

        // Event select major
        $('#form_filter').on('change', '#sel_major', function () {
            modalLoading.show();
            var loaded = false;
            var id_group_field = $('#form_filter #sel_group_field').val();
            var id_course = $('#form_filter #sel_course').val();
            var id_major = $('#form_filter #sel_major').val();
            loaded = load_plan(id_course, id_major);
            loaded = load_sub_gen(id_group_field, id_course, id_major);
            loaded = load_sub_base(id_group_field, id_course, id_major);
            loaded = load_sub_major(id_group_field, id_course, id_major);

            if (loaded)
                modalLoading.hide();

        });

        // Event click btnSave
        $('#action_table').on('click', '#btnSave', function () {
            var id_group_field = $('#form_filter #sel_group_field').val();
            var id_course = $('#form_filter #sel_course').val();
            var id_major = $('#form_filter #sel_major').val();
            var term = $('#form_filter #sel_term').val();
            var n = $(".chk_subject:checked").length;
            var str_sub_cur = "";// chuỗi lưu id_subject và curriculum
            var calculate = 1;
            if (n > 0) {
                $(".chk_subject:checked").each(function () {
                    if ($('#chk_calculate_' + $(this).val()).is(':checked')) {
                        calculate = 1;
                    } else {
                        calculate = 0;
                    }
                    str_sub_cur += $(this).val() + "!" + $('#txt_curriculum_' + $(this).val()).val() + "!" + $('#txt_element_' + $(this).val()).val() + "!" + $('#txt_test_' + $(this).val()).val() + "!" + calculate + "#";
                });
            }
            if (id_course !== '0' && id_major !== '0' && term !== '' && str_sub_cur !== '') {
                modalLoading.show();
                var loaded = false;
                loaded = add_plan(id_course, id_major, term, str_sub_cur);
                loaded = load_plan(id_course, id_major);
                loaded = load_sub_gen(id_group_field, id_course, id_major);
                loaded = load_sub_base(id_group_field, id_course, id_major);
                loaded = load_sub_major(id_group_field, id_course, id_major);
                if (loaded)
                    modalLoading.hide();
                noticeMeseage.show("Thêm thành công môn học vào kế hoạch đào tạo!", "success");
            } else {
                //console.log('insert_value= str_sub_cur=' + str_sub_cur + " / id_course=" + id_course + " / id_major=" + id_major + " / term=" + term);
                noticeMeseage.show('Lỗi: Thiếu thông tin, xin kiểm tra lại: Khóa học, chuyên ngành, học kỳ, môn học', 'danger');
            }
        });

        // Event click deleteBtn
        $('#list_subject_term').on('click', '.deleteBtn', function () {
            if (confirm("Bạn có chắc chắn xóa mục được chọn không? Lưu ý: Mục đã xóa không thể phục hồi!") === false)
                return false;
            else {
                modalLoading.show();
                var loaded = false;
                var id_plan = $(this).attr('data-id');
                loaded = del_plan(id_plan);
                var id_course = $('#form_filter #sel_course').val();
                var id_major = $('#form_filter #sel_major').val();
                var id_group_field = $('#form_filter #sel_group_field').val();
                loaded = load_plan(id_course, id_major);
                loaded = load_sub_gen(id_group_field, id_course, id_major);
                loaded = load_sub_base(id_group_field, id_course, id_major);
                loaded = load_sub_major(id_group_field, id_course, id_major);
                if (loaded)
                    modalLoading.hide();
                noticeMeseage.show("Xóa thành công!", "success");
            }
        });

        // Event click btnPrint
        $('#action_table').on('click', '#btnPrint', function () {
            var id_group_field = $('#form_filter #sel_group_field').val();
            var id_course = $('#form_filter #sel_course').val();
            var id_major = $('#form_filter #sel_major').val();
            var term = $('#form_filter #sel_term').val();
            var downloadUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/downloadPlan.php') ?>";
            if (id_course !== "0" && id_major !== "0")
                $.download(downloadUrl, "course=" + id_course + "&major=" + id_major, 'get');
            else
                alert('Kế hoạch trống!');
        });

    })(jQuery);

</script>
<!--  my scripts -->
<script type="text/javascript">

    // change bg color of row when selected
    function chk_selected(id) {
        //console.log('Running! id=' + $("#chk_subject_" + id).is(':checked'));
        if ($("#chk_subject_" + id).is(':checked'))
            $("#row_" + id).addClass('row_selected');
        else
            $("#row_" + id).removeClass('row_selected');
    }

    // validate formula
    function cal_formula(value, id) {
        //console.log('Onchange > Value='+ value + "; id=" +id);;
        if (value <= 50 && value >= 0 && !isNaN(value)) {
            $("#txt_test_" + id).val(100 - value);
        } else {
            //modalMesseage.show("Dữ liệu nhập vào không hợp lệ (TP là số nguyên, 0<= TP <=50 ). Vui lòng kiểm tra lại");
            dialogMesseage.show('Lỗi: ', "Dữ liệu nhập vào không hợp lệ (TP là số nguyên, 0<= TP <=50 ). Vui lòng kiểm tra lại");
            $("#txt_element_" + id).val(50);
            $("#txt_test_" + id).val(50);
        }
    }

    // validate curriculum
    function val_curriculum(value, id) {
        if (!isNaN(value) && value > 0) {
            $("#txt_curriculum_" + id).val(value);
        } else {
            dialogMesseage.show("Cảnh báo!", "Dữ liệu nhập vào không hợp lệ (Số TC là số nguyên, lớn hơn 0). Vui lòng kiểm tra lại");
            $("#txt_curriculum_" + id).val(3);
        }
    }

</script>
