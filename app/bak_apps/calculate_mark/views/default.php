<?php 
if (!defined('AREA')) {
    die('Access denied');
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/calculate_mark/css/dataTables.bootstrap.css') ?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/calculate_mark/css/style.css') ?>">
<script src="<?php echo AppObject::getBaseFile('app/calculate_mark/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/calculate_mark/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/calculate_mark/js/fnAddTr.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/calculate_mark/js/scripts.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/calculate_mark/js/jquery.download.js') ?>"></script>
<!-- col-sm-3 -->
<div class="col-sm-3">
    <div class="panel-group" id="accordion" role="tablist" >
        <!--Tính điểm theo lớp học phần-->
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a class="collapseOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Tính điểm theo lớp học phần
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <form class="form" role="form" id="form_filter" >
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn năm học:</label>
                            <select name="sel_year" id="sel_year" class="form-control input-sm">
                                <option value="0">- Chọn năm -</option>
                                <?php
                                foreach ($this->arr_year as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>  
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn ngành học:</label>
                            <select name="sel_group_field" id="sel_group_field" class="form-control input-sm sel_filter type_1 type_2">
                                <?php
                                foreach ($this->arr_group_field as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn lớp học phần:</label>
                            <select name="sel_class_subject" id="sel_class_subject" class="form-control input-sm sel_filter type_1">
                                <option value="0">- Chọn lớp học phần -</option>
                            </select>
                        </div>
                    </form>
                </div>
                <table id="action_table_1" class="table">
                    <tbody>
                        <tr align="center">
                            <td>
                                <button id="btnCalculate" class="btn btn-xs btn-info btnCalculate" disabled="true"> Tính điểm <i class="glyphicon glyphicon-forward"></i></button>
                            </td>
                        </tr>
                        <tr align="center">
                            <td>
                                <button id="btnPrint" class="btn btn-xs btn-warning btnPrint" disabled="true"> In điểm <i class="glyphicon glyphicon-forward"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <!--Tính điểm Khóa học-->
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a class="collapseTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        Tính điểm theo Khóa học > Học kỳ
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <form class="form" role="form" id="form_filter_2" >
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn khóa học:</label>
                            <select name="sel_course" id="sel_course" class="form-control input-sm">
                                <option value="0">- Chọn khóa -</option>
                                <?php
                                foreach ($this->arr_course as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>  
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn học kỳ:</label>
                            <select name="sel_term" id="sel_term" class="form-control input-sm">
                                <option value="0">- Chọn học kỳ -</option>
                                <?php
                                foreach ($this->arr_term as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>  
                            </select>
                        </div>
                    </form>
                </div>
                <table id="action_table_2" class="table">
                    <tbody>
                        <tr align="center">
                            <td>
                                <button id="btnCaculateCourse" class="btn btn-xs btn-info btnCaculateCourse" > Tính điểm <i class="glyphicon glyphicon-forward"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--Tính điểm tổng kết-->        
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a data-toggle="collapse" class="collapseThree" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        Tính điểm tổng kết toàn khóa học
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <form class="form" role="form" id="form_filter_3" >
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn khóa học:</label>
                            <select id="sel_t3_course" class="form-control input-sm">
                                <option value="0">- Chọn khóa -</option>
                                <?php
                                foreach ($this->arr_course as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>  
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn ngành học:</label>
                            <select id="sel_t3_group_field" class="form-control input-sm">
                                <option value="0">- Chọn ngành -</option>
                                <?php
                                foreach ($this->arr_group_field as $key => $value) {
                                    ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="" class="control-label">Chọn lớp:</label>
                            <select id="sel_t3_class" class="form-control input-sm">
                                <option value="0">- Chọn lớp -</option>
                            </select>
                        </div>
                    </form>
                    <table id="action_table_3" class="table">
                        <tbody>
                            <tr align="center">
                                <td>
                                    <button id="btnCalculateSumary" class="btn btn-xs btn-info" disabled="true"> Tính điểm <i class="glyphicon glyphicon-forward"></i></button>
                                </td>
                                <td>
                                    <button id="btnSaveSumary" class="btn btn-xs btn-warning" disabled="true"> Lưu điểm <i class="glyphicon glyphicon-forward"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.col-sm-3 -->
<!-- col-sm-9 -->
<div class="col-sm-9">
    <!--Table danh sách điểm học viên-->
    <div id="table_student" class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách điểm học viên</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <div class="auto_scroll profile_scroll_gen" id="profile_scroll_gen">
                    <table id="list_student" class="table table-striped" data-toggle="table" data-height="100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã HV</th>
                                <th>Họ và tên</th>
                                <th>Công thức điểm</th>
                                <th>Điểm thành phần</th>
                                <th>Điểm thi</th>
                                <th>Điểm tổng kết môn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Chọn lớp học phần để tải danh sách điểm</td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
        </div>
    </div>
    <!--Table danh sách lớp học phần-->
    <div id="table_class_subject" class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách lớp học phần cần tính điểm</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <div class="auto_scroll profile_scroll_gen" id="profile_scroll_gen">
                    <table id="list_class_subject" class="table table-striped" data-toggle="table" data-height="100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã lớp</th>
                                <th>Tên lớp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">Chọn thông tin để tải danh sách lớp</td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.auto_scroll -->
            </div>
        </div>
    </div>
    <!--Table danh sách lớp hành chính-->
    <div id="table_class" class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách lớp học hành chính</h4>
        </div>
        <div class="panel-body">
            <table id="tbl_list_student" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã học viên</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Chuyên ngành</th>
                        <th>Điểm tổng kết</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.col-sm-9 -->
<!--  app functions -->
<script type="text/javascript">
    // disable all select
    function firtLoad() {
        $(".sel_filter").prop('disabled', 'disabled');
        $("#table_student").hide();
        $("#table_class_subject").hide();
        $("#table_class").hide();

    }
    function enable(item) {
        $(item).prop('disabled', false);
    }
    function disable(item) {
        $(item).prop('disabled', true);
    }
    function load_page() {
        validate_select();
        disable("#sel_class");
    }
    function enable(item) {
        $(item).prop('disabled', false);
    }
    function disable(item) {
        $(item).prop('disabled', true);
    }
    function validate_t3_select() {
//        console.log("Running!");
        var id_course = $("#sel_t3_course").val();
        var id_group_field = $("#sel_t3_group_field").val();
        var id_class = $("#sel_t3_class").val();
        if (id_course == '0' || id_group_field == '0') {
            disable("#sel_t3_class");
        } else {
            enable("#sel_t3_class");
        }
        if (id_class == '0') {
            disable("#btnCalculateSumary");
            disable("#btnSaveSumary");
        } else {
            enable("#btnCalculateSumary");
            enable("#btnSaveSumary");
        }


    }
</script>
<!--Ajax function--> 
<script type="text/javascript">
    var ajaxUrl = "<?= AppObject::getBaseFile('app/calculate_mark/helpers/ajax.php') ?>";
    // load year
    function loadYear() {
        var str_post_data = "";
        $.post(ajaxUrl, {act: 'load_year', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row = str_data.split(';');
            // clear sel_year
            $('#sel_year').find('option:not(:first)').remove();
            for (year in row) {
                $('#sel_year').append('<option value="' + row[year] + '">' + row[year] + '</option>');
            }
        });
        enable("#sel_year");
    }
    // load group_field
    function loadGroupField(year) {
        var str_post_data = year;
        $.post(ajaxUrl, {act: 'load_groupfield', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row_gf = str_data.split('#');
            $('#sel_group_field').find('option:not(:first)').remove();
            $.each(row_gf, function (key, value) {
                var tmp = value.split(';');
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    $('#sel_group_field').append('<option value="' + tmp[0] + '">' + tmp[1] + '</option>');
                }
            });
            enable("#sel_group_field");
        });
    }
    // load class_subject
    function load_class_subject(year, id_group_field) {
        var str_post_data = year + ";" + id_group_field;
        $.post(ajaxUrl, {act: 'load_cs', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row_cs = str_data.split('#');
            $('#sel_class_subject').find('option:not(:first)').remove();
            $.each(row_cs, function (key, value) {
                var tmp = value.split(';');
                //console.log('tmp: '+ tmp);
                if (tmp[0] !== null && tmp[0] !== "") {
                    $('#sel_class_subject').append('<option value="' + tmp[0] + '">' + tmp[1] + '</option>');
                }
            });
            enable("#sel_class_subject");
        });
    }
    // load student info
    function load_student(id_class_subject) {
        var str_post_data = id_class_subject;
        $.post(ajaxUrl, {act: 'load_student', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row_cs = str_data.split('#');
            //console.log(row_cs);
            var i = 0;
            $("#list_student > tbody").html("");
            $.each(row_cs, function (key, value) {
                i++;
                var tmp = value.split(';');
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "";
                    r += "<tr id='row_" + tmp[0] + "' class='row_st' >";
                    r += "<td align='center'>" + i + "</td>";
                    r += "<td>" + tmp[1] + "</td>";
                    r += "<td>" + tmp[2] + "</td>";
                    r += "<td>" + tmp[3] + "</td>";
                    r += "<td>" + tmp[4] + "</td>";
                    r += "<td>" + tmp[5] + "</td>";
                    r += "<td>" + tmp[6] + "</td>";
                    r += "</tr>";
                    //console.log('row: '+ r);
                    $('#list_student > tbody').append(r);
                }
            });
            enable("#btnCalculate");
            enable("#btnPrint");
        });
    }
    // Cal mark for class_subject
    function cal_mark(id_class_subject) {
        var str_post_data = id_class_subject;
        $.post(ajaxUrl, {act: 'cal_mark', str_post_data: str_post_data}, function (str_response_data) {
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var str_status = data.status;
            var str_messeage = data.messeage;
            load_student(id_class_subject);
            noticeMeseage.show(str_messeage, str_status);
        });
    }
    // Tính điểm theo cách 2
    // load list class_subject by course, term
    function load_class_subject_type_2(id_course, term) {
        if (id_course !== "0" && term !== "0") {
            modalLoading.show();
            var str_post_data = id_course + ";" + term;
            $.post(ajaxUrl, {act: 'load_cs_2', str_post_data: str_post_data}, function (str_response_data) {
                var data = $.parseJSON(str_response_data);
                var str_data = data.returndata;
                var row_cs = str_data.split('#');
                //console.log(row_cs);
                var i = 0;
                $("#list_class_subject > tbody").html("");
                $.each(row_cs, function (key, value) {
                    i++;
                    var tmp = value.split(';');
                    if (tmp[0] !== null && tmp[0] !== "") {
                        var r = "";
                        r += "<tr id='row_" + tmp[0] + "' class='row_cs' >";
                        r += "<td align='center'>" + i + "</td>";
                        r += "<td>" + tmp[0] + "</td>";
                        r += "<td>" + tmp[1] + "</td>";
                        r += "</tr>";
                        //console.log('row: '+ r);
                        $('#list_class_subject > tbody').append(r);
                    }
                });
                modalLoading.hide();
            });
        }
    }
    // Cal mark for course\term
    function cal_mark_by_course_term(id_course, term) {
        console.log("course: " + id_course + "; term: " + term);
        if (id_course !== "0" && term !== "0") {
            modalLoading.show();
            var str_post_data = id_course + ";" + term;
            $.post(ajaxUrl, {act: 'cal_mark_by_course_term', str_post_data: str_post_data}, function (str_response_data) {
                var data = $.parseJSON(str_response_data);
                var str_data = data.returndata;
                var str_status = data.status;
                var str_messeage = data.messeage;
                noticeMeseage.show(str_messeage, str_status);
                modalLoading.hide();
            });
        } else {
            modalMesseage.show("Vui lòng chọn khóa học và học kỳ để tiến hành tính điểm.");
        }
    }

    // Tính điểm theo cách 3
    // tải danh sách lớp hành chính
    function t3_load_class(id_course, id_group_field) {
        if (id_course !== "0" && id_group_field !== "0") {
            var str_post_data = id_course + ";" + id_group_field;
            $.post(ajaxUrl, {act: 'load_t3_class', str_post_data: str_post_data}, function (str_response_data) {
                //console.log("str_response_data=" + str_response_data);
                var data = $.parseJSON(str_response_data);
                var str_data = data.returndata;
                var row = str_data.split('#');
                $('#sel_t3_class').find('option:not(:first)').remove();
                $.each(row, function (key, value) {
                    var tmp = value.split(';');
                    if (tmp[0] !== null && tmp[0] !== "") {
                        $('#sel_t3_class').append('<option value="' + tmp[0] + '">' + tmp[1] + '</option>');
                    }
                });
                enable("#sel_t3_class");
            });
        }
    }
    // Tải danh sách sinh viên
    function t3_load_student(id_class) {
        var str_post_data = id_class;
        $.post(ajaxUrl, {act: 'load_t3_student', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row = str_data.split('#');
            $('#tbl_list_student > tbody').html("");
            var n = 0;
            $.each(row, function (key, value) {
                var tmp = value.split(';');
                n++;
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "";
                    r += "<tr>";
                    r += "<td>" + n + "</td>";
                    r += "<td>" + tmp[0] + "</td>";
                    r += " <td>" + tmp[3] + " " + tmp[2] + "</td>";
                    r += "<td>" + tmp[4] + "</td>";
                    r += "<td>" + tmp[5] + "</td>";
                    r += "<td id='stt_" + tmp[1] + "'>" + tmp[6] + "</td>";
                    r += " </tr>";
                    //console.log('row: '+ r);
                    $('#tbl_list_student > tbody').append(r);
                }
            });
        });
    }
    // Tính điểm tổng kết
    function cal_t3_mark(id_class) {
        var str_post_data = id_class;
        modalLoading.show();
        $.post(ajaxUrl, {act: 'cal_t3_mark', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            var row = str_data.split('#');
            $('#tbl_list_student > tbody').html("");
            var n = 0;
            $.each(row, function (key, value) {
                var tmp = value.split(';');
                n++;
                if (tmp[0] !== null && tmp[0] !== "") {
                    var r = "";
                    r += "<tr>";
                    r += "<td>" + n + "</td>";
                    r += "<td>" + tmp[0] + "</td>";
                    r += " <td>" + tmp[3] + " " + tmp[2] + "</td>";
                    r += "<td>" + tmp[4] + "</td>";
                    r += "<td>" + tmp[5] + "</td>";
                    r += "<td id='stt_" + tmp[1] + "'>" + tmp[6] + "</td>";
                    r += " </tr>";
                    //console.log('row: '+ r);
                    $('#tbl_list_student > tbody').append(r);
                }
            });
            modalLoading.hide();
        });
    }
    // Lưu điểm tổng kết
    function save_t3_mark(id_class) {
        var str_post_data = id_class;
        modalLoading.show();
        $.post(ajaxUrl, {act: 'save_t3_mark', str_post_data: str_post_data}, function (str_response_data) {
            //console.log("str_response_data=" + str_response_data);
            var data = $.parseJSON(str_response_data);
            var str_data = data.returndata;
            noticeMeseage.show("Lưu thành công danh sách điểm!", "success");
            modalLoading.hide();
        });
    }
</script>
<!--  proccess scripts -->
<script type="text/javascript">
    // Function already
    (function () {
        firtLoad();
//------------------- Type 1 ---------------------
//        Event collapseOne click
        $(".collapseOne").click(function () {
            $("#table_student").show();
            $("#table_class_subject").hide();
            $("#table_class").hide();
        });
// Event khi chọn năm
        $('#form_filter').on('change', '#sel_year', function () {
            var year = $("#sel_year").val();
            if (year !== "0") {
                loadGroupField(year);
            } else {
                disable("#sel_group_field");
                disable("#sel_class_subject");
            }
        });
        // Event khi chọn ngành học
        $('#form_filter').on('change', '#sel_group_field', function () {
            var id_group_field = $("#sel_group_field").val();
            var year = $("#sel_year").val();
            if (year !== "0") {
                load_class_subject(year, id_group_field);
            } else {
                disable("#sel_group_field");
                disable("#sel_class_subject");
            }
        });
        // Event khi chọn lớp học phần
        $('#form_filter').on('change', '#sel_class_subject', function () {
            var id_class_subject = $("#sel_class_subject").val();
            if (id_class_subject !== "0") {
                modalLoading.show();
                load_student(id_class_subject);
                modalLoading.hide();
            } else {
                $("#list_student > tbody").html("");
                disable("#btnCalculate");
                disable("#btnPrint");
            }
        });
        // Event tính điểm
        $('#action_table_1').on('click', '#btnCalculate', function () {
            //console.log("running!");
            var id_class_subject = $("#sel_class_subject").val();
            if (id_class_subject !== "0") {
                modalLoading.show();
                cal_mark(id_class_subject);
                modalLoading.hide();
            }
        });
        // Event click btnPrint
        $('#action_table_1').on('click', '#btnPrint', function () {
            var id_class_subject = $("#sel_class_subject").val();
            var downloadUrl = "<?= AppObject::getBaseFile('app/planning_training/helpers/downloadPlan.php') ?>";
            if (id_class_subject !== "0") {
                $.download(downloadUrl, "id_class_subject=" + id_class_subject, 'get');
            } else {
                $("#list_student > tbody").html("");
                disable("#btnCalculate");
                disable("#btnPrint");
            }
        });
//------------------- Type 2 ---------------------
// Event collapse 
        $(".collapseTwo").click(function () {
//            console.log("Running!");
            $("#table_student").hide();
            $("#table_class").hide();
            $("#table_class_subject").show();
        });
// Event select course
        $('#form_filter_2').on('change', '#sel_course', function () {
//            console.log("running!");
            var id_course = $("#sel_course").val();
            var term = $("#sel_term").val();
            load_class_subject_type_2(id_course, term);
        });
        // Event select term
        $('#form_filter_2').on('change', '#sel_term', function () {
            var id_course = $("#sel_course").val();
            var term = $("#sel_term").val();
            load_class_subject_type_2(id_course, term);
        });
        // Event calculate Mark for Course
        $('#action_table_2').on('click', '#btnCaculateCourse', function () {
            //console.log("running!");
            var id_course = $("#sel_course").val();
            var term = $("#sel_term").val();
            cal_mark_by_course_term(id_course, term)
        });

//------------------- Type 3 ---------------------  
        $(".collapseThree").click(function () {
//            console.log("Running!");
            $("#table_student").hide();
            $("#table_class_subject").hide();
            $("#table_class").show();
            validate_t3_select();
        });
        // Event select course
        $('#form_filter_3').on('change', '#sel_course_sumary', function () {
            var id_course = $("#sel_course_sumary").val();
            load_class(id_course);
            validate_t3_select();
        });

        $('#form_filter_3').on('change', '#sel_t3_course', function () {
//            console.log("running!");
            var id_course = $("#sel_t3_course").val();
            var id_group_field = $("#sel_t3_group_field").val();
            t3_load_class(id_course, id_group_field);
            validate_t3_select();
        });

        $('#form_filter_3').on('change', '#sel_t3_group_field', function () {
//            console.log("running!");
            var id_course = $("#sel_t3_course").val();
            var id_group_field = $("#sel_t3_group_field").val();
            t3_load_class(id_course, id_group_field);
            validate_t3_select();
        });
        // Chọn lớp 
        $('#form_filter_3').on('change', '#sel_t3_class', function () {
//            console.log("running!");
            var id_class = $("#sel_t3_course").val();
            if (id_class != '0') {
                t3_load_student(id_class);
                validate_t3_select();
            }
        });
        // Click tính điểm
        $('#action_table_3').on('click', '#btnCalculateSumary', function () {
            //console.log("running!");
            var id_class = $("#sel_t3_class").val();
            if (id_class != '0') {
                cal_t3_mark(id_class);
                validate_t3_select();
            }
        });

        // Click lưu điểm
        $('#action_table_3').on('click', '#btnSaveSumary', function () {
            //console.log("running!");
            var id_class = $("#sel_t3_class").val();
            if (id_class != '0') {
                save_t3_mark(id_class);
                validate_t3_select();
            }
        });

    })(jQuery);
</script>
