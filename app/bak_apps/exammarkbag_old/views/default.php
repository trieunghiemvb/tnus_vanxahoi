<?php
if (!defined('AREA')) {
    die('Access denied');
}
set_time_limit(36000);
ini_set("memory_limit", "1220M");

function makeDate($str) {
    $str_array1 = explode(" ", $str);
    $str_array2 = explode("-", $str_array1[0]);
    $str_array3 = explode(":", $str_array1[1]);
    $str_return = $str_array2[2] . '/' . $str_array2[1] . '/' . $str_array2[0] . ' ';
    //$str_return .= $str_array3[0].':'.$str_array2[1];
    return $str_return;
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exammarkbag/css/dataTables.bootstrap.css') ?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exammarkbag/css/class_style.css') ?>">
<script src="<?php echo AppObject::getBaseFile('app/exammarkbag/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammarkbag/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammarkbag/js/fnAddTr.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammarkbag/js/scripts.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammarkbag/js/jquery.download.js') ?>"></script>
<style>
    .message2{

    }
</style>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Chọn phòng thi / Túi bài thi </h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="form_filter" >
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Khóa học:</label>
                    <div class="col-sm-8">
                        <select name="filter_course" id="filter_course" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->course as $key => $value) { ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
<?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Đợt thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_exam" id="filter_exam" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Môn thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_subject" id="filter_subject" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Phòng thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_room" id="filter_room" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>							
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Túi bài thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_bag" id="filter_bag" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>							
                        </select>
                    </div>
                </div>		
            </form>
            <div class="form-group">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <button class="btn btn-md btn-info btnSelect disabled"><i class="glyphicon glyphicon-arrow-right"></i> Chọn túi bài thi </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách thí sinh</h4>
        </div>
        <div class="panel-body"> 
            <div class="panel-exam"> 
                <div class="col-sm-6">
                    <label for="" class="col-sm-3 control-label">Khóa học:</label>
                    <div id="course_selected">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="" class="col-sm-3 control-label">Đợt thi:</label>
                    <div id="exam_selected">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="" class="col-sm-3 control-label">Môn thi:</label>
                    <div id="subject_selected">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="" class="col-sm-3 control-label">Phòng thi:</label>
                    <div id="room_selected">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="" class="col-sm-3 control-label">Túi bài thi:</label>
                    <div id="bag_selected">
                    </div>
                </div>
            </div>
            <table class="table table-striped tbAutoScroll" id="datatables">
                <thead>
                    <tr>

                        <th>#</th>
                        <th>Mã học viên</th>
                        <th style="width: 130px;">Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Lớp học</th>
                        <th>Tình trạng thi</th>
                        <th>Số phách</th>
                        <th>Điểm nhập vào</th>
                        <th>Điểm thi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="well">
            <div class="message2" style="display:none;">
                <div class="alert alert-dismissible" role="alert">
                    <button type="button" class="close bnt" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <span class="alert-message"></span>
                </div>
            </div>
            <table id="action-table" class="table">				
                <tr align="center">
                    <td colspan="5">
                        <input type="hidden" name="id_exam_list_str" id="id_exam_list_str" value="">
                        <input type="hidden" name="id_exam_room_str" id="id_exam_room_str" value="">
                        <input type="hidden" name="id_exam_bag_str" id="id_exam_bag_str" value="">						
                        <button class="btn btn-md btn-primary btnAddNew"><i class="glyphicon glyphicon-download-alt"></i> Lưu điểm  </button>
                        <button class="btn btn-md btn-warning btnReset"><i class="glyphicon glyphicon-refresh"></i> Tải lại </button>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Ajax xu ly: Edit by ANVT-->
<script type="text/javascript">
    $('.tbAutoScroll').DataTable({
        ordering: false,
        paging: false,
        info: false,
        searching: false,
        scrollY: $(window).height() - 410,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var index = iDisplayIndex + 1;
            $('td:eq(0)', nRow).html(index);
        }

    });
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function isFloat(evt) {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            alert('Please enter only no or float value');
            return false;
        }
        else {
            //if dot sign entered more than once then don't allow to enter dot sign again. 46 is the code for dot sign
            var parts = evt.srcElement.value.split('.');
            if (parts.length > 1 && charCode == 46)
                return false;
            return true;
        }
    }

    (function () {
        $('.btnAddNew').prop('disabled', true);
        $('.btnReset').prop('disabled', true);
        $('.panel-exam').hide();

        var ajaxUrl = "<?= AppObject::getBaseFile('app/exammarkbag/helpers/ajax.php') ?>";
        // xóa thông báo
        $(document).on('click', '.btn', function () {
            $('.message2 .alert').removeClass('alert-success alert-danger');
            $('.message2').hide();
            $('.message2').slideUp();
        });
        $(document).on('click', '.formattedNumberField', function () {
            $(this).select();
        });
// even key press
        $(document).on('keyup', '.formattedNumberField', function (event)
        {
            var kt = 'col-sm-12 formattedNumberField';
            if ($(this).attr('class') == kt) {
                var n = parseFloat($(this).val());
            }
            // dấu .
            if (event.keyCode == 190)
            {
                var kt = 'col-sm-12 formattedNumberField';
                if ($(this).attr('class') == kt)
                {
                    //do it
                    if ($(this).val().match(/\./g).length > 1)
                    {
                        if (!isNaN(parseFloat($(this).val())))
                        {
                            $(this).val(parseFloat($(this).val()));
                        }
                    }
                    else {
                        //nothing
                    }
                }
                else {
                    //nothing
                    event.preventDefault();
                }
            } else if (event.keyCode == 188) //dấu ,
            {
                event.preventDefault();
                var kt = 'col-sm-12 formattedNumberField';
                if ($(this).attr('class') == kt)
                {
                    if (!isNaN(parseFloat($(this).val()))) {
                        $(this).val(parseFloat($(this).val()));
                    } else {
                        $(this).val("");
                    }
                    $(".message2").show();
                    $(".message2").addClass('alert alert-danger');
                    $(".message2").html("Sử dụng dấu . để biểu diễn số thập phân.");
                }
            } else if (event.keyCode == 46 || event.keyCode == 8)
            {
                // Allow only backspace, delete, key right and key left
                // leave it empty.
                //var n = parseInt($(this).val().replace(/\D/g,''),10);
                if (n > 10)
                {
                    //event.preventDefault();
                    // Show message to the user
                    $(".message2").show();
                    $(".message2").addClass('alert alert-danger');
                    $(".message2").html("Điểm không được lớn hơn 10.");
                    $(this).css('color', "#FF0000");
                    $(this).parents('tr').css('background', '#000');
                    $(this).parents('tr').css('color', '#fff');
                    $('.btnAddNew').prop('disabled', true);
                }
                else {
                    $(this).css('color', "#000");
                    $(this).parents('tr').css('background', '#dff0d8');
                    $(this).parents('tr').css('color', '#000');
                    $('.btnAddNew').prop('disabled', false);
                    var i = $(this).parents('tr').find("td.number").text();
                    var id = $('#student_i_' + i).val();
                    var id_state = $('#state_student_' + id).val();
                    if (id_state == 1)
                    {
                        $('#mark_exam_' + id).val(n);
                    }
                    else if (id_state == 2) {
                        $('#mark_exam_' + id).val(n - n * 0.25);	// cảnh cáo bị trừ 25% số điểm						
                    }
                    else if (id_state == 3) {
                        $('#mark_exam_' + id).val(n - n * 0.5);		// khiển trách bị trừ 50% số điểm
                    }
                    else if (id_state == 4 || id_state == 5) {
                        $('#mark_exam_' + id).val('0');
                    }
                    if (n.toLocaleString() == 'NaN')
                    {
                        $('#mark_exam_' + id).val("");
                    }
                }
            }
            else if (event.keyCode == 9)
            {
                //Tab key
                event.preventDefault();
                var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                if (parseInt(inputs.eq(inputs.index(this) - 1).val()) > 10 || parseInt(inputs.eq(inputs.index(this) - 1).val()).toLocaleString() == 'NaN')
                {
                    inputs.eq(inputs.index(this) - 1).focus().select();
                }
                else {
                    $(this).focus().select();
                }
            }
            else if (event.keyCode == 37) {
                //left key			
                event.preventDefault();
                if (n > 10 || n.toLocaleString() == 'NaN') {
                    //nothing
                }
                else {
                    var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                    inputs.eq(inputs.index(this) - 1).focus().select();
                }
            }// up key
            else if (event.keyCode == 38) {
                event.preventDefault();
                if (n > 10 || n.toLocaleString() == 'NaN') {
                    //nothing
                }
                else {
                    var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                    inputs.eq(inputs.index(this) - 1).focus().select();
                }
            }//right key
            else if (event.keyCode == 39) {
                event.preventDefault();
                if (n > 10 || n.toLocaleString() == 'NaN') {
                    //nothing
                }
                else {
                    var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                    inputs.eq(inputs.index(this) + 1).focus().select();
                }
            }//down key
            else if (event.keyCode == 40) {
                event.preventDefault();
                if (n > 10 || n.toLocaleString() == 'NaN') {
                    //nothing
                }
                else {
                    var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                    inputs.eq(inputs.index(this) + 1).focus().select();
                }
            }//enter key
            else if (event.keyCode == 13) {
                event.preventDefault();
                if (n > 10 || n.toLocaleString() == 'NaN') {
                    //nothing
                }
                else {
                    var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
                    inputs.eq(inputs.index(this) + 1).focus().select();
                }
            }
            else if (event.keyCode < 48 || event.keyCode > 57) {
                event.preventDefault();
                // Show message to the user
                $(".message2").show();
                $(".message2").addClass('alert alert-danger');
                $(".message2").html("Chỉ nhập số.");
                var kt = 'col-sm-12 formattedNumberField';
                if ($(this).attr('class') == kt)
                {
                    if (!isNaN(parseFloat($(this).val()))) {
                        $(this).val(parseFloat($(this).val()));
                    } else {
                        $(this).val("");
                    }
                }
            }
            else {
                // A number is pressed so we hide the message
                $(".message2").hide();
                $(".message2 .alert-message").html("");
                $(".message2").removeClass('alert alert-danger');
                $('.message2').slideUp();
                var kt = 'col-sm-12 formattedNumberField';
                if ($(this).attr('class') == kt) {
                    var n = parseFloat($(this).val());
                    if (n.toLocaleString() == 'NaN') {
                        $(this).val("");
                        if (parseInt($(this).parents('tr').find('.number').html()) % 2) {
                            $(this).parents('tr').css('background', '#f9f9f9');
                            $(this).parents('tr').css('color', '#000');
                        } else {
                            $(this).parents('tr').css('background', '#fff');
                            $(this).parents('tr').css('color', '#000');
                        }
                    }
                    if (n > 10) {
                        // Show message to the user
                        $(".message2").show();
                        $(".message2").addClass('alert alert-danger');
                        $(".message2").html("Điểm không được lớn hơn 10.");
                        $(this).css('color', "#FF0000");
                        $('.btnAddNew').prop('disabled', true);
                        if (parseInt($(this).parents('tr').find('.number').html()) % 2) {
                            $(this).parents('tr').css('background', '#000');
                            $(this).parents('tr').css('color', '#fff');
                        } else {
                            $(this).parents('tr').css('background', '#000');
                            $(this).parents('tr').css('color', '#fff');
                        }
                    } else {
                        $(".message2").hide();
                        $(".message2 .alert-message").html("");
                        $(".message2").removeClass('alert alert-danger');
                        $('.message2').slideUp();
                        $(this).css('color', "#000");
                        $('.btnAddNew').prop('disabled', false);
                        $(this).val(n);
                        $(this).parents('tr').css('background', '#dff0d8');
                        $(this).parents('tr').css('color', '#000');
                        var i = $(this).parents('tr').find("td.number").text();
                        var id = $('#student_i_' + i).val();
                        var id_state = $('#state_student_' + id).val();
                        if (id_state == 1)
                        {
                            $('#mark_exam_' + id).val(n);
                        }
                        else if (id_state == 2) {
                            $('#mark_exam_' + id).val(n - n * 0.25);	// cảnh cáo bị trừ 25% số điểm						
                        }
                        else if (id_state == 3) {
                            $('#mark_exam_' + id).val(n - n * 0.5);		// khiển trách bị trừ 50% số điểm
                        }
                        else if (id_state == 4 || id_state == 5) {
                            $('#mark_exam_' + id).val('0');
                        }
                        if (n.toLocaleString() == 'NaN')
                        {
                            $('#mark_exam_' + id).val("");
                        }
                    }
                }
            }
        });

// Bắt sự kiện thay đổi điều kiện lọc trên #form_filter
        $('#form_filter').on('change', '.select_filter', function ()
        {
            $(".message2").hide();
            $(".message2").html("");
            $(".message2 .alert-message").html("");
            $(".message2").removeClass('alert alert-danger');
            $(".message2").removeClass('alert alert-success');
            $('.message2').slideUp();
            var type_filter = "";
            var course = $('#filter_course option:selected').val();
            var exam = $('#filter_exam option:selected').val();
            var subject = $('#filter_subject option:selected').val();
            var room = $('#filter_room option:selected').val();
            var bag = $('#filter_bag option:selected').val();

            if (this.id == 'filter_course') {
                type_filter = 'course';
            }
            if (this.id == 'filter_exam') {
                type_filter = 'exam';
            }
            if (this.id == 'filter_subject') {
                type_filter = 'subject';
            }
            if (this.id == 'filter_room') {
                type_filter = 'room';
            }
            if (this.id == 'filter_bag') {
                type_filter = 'bag';

                $.post(ajaxUrl, {act: 'loadfilter', type_filter: "get_id_exam", course: course, exam: exam, subject: subject, room: room}, function (data) {
                    var data = $.parseJSON(data);
                    var str_data = data.returndata;
                    if (str_data != '')
                    {
                        $('#id_exam_list_str').val(str_data);
                    }
                    else
                    {
                        $('#id_exam_list_str').val("");
                    }
                });

                if (bag == 0)
                {
                    $('.btnSelect').addClass('disabled');
                    $('#id_exam_list_str').val("");
                    $('#id_exam_room_str').val("");
                    $('#id_exam_bag_str').val("");
                }
                else
                {
                    $('.btnSelect').removeClass('disabled');
                    $('#id_exam_room_str').val(room);
                    $('#id_exam_bag_str').val(bag);
                }

            }

            $.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course: course, exam: exam, subject: subject, room: room, bag: bag}, function (data) {
                //alert(data);
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                if (type_filter == 'course')
                {
                    if (str_data != '')
                    {
                        $('#filter_exam').html(str_data);
                    } else
                    {
                        $('#filter_exam').html('<option value="0">-- Tất cả --</option>');

                    }
                    $('#filter_subject').html('<option value="0">-- Tất cả --</option>');
                    $('#filter_room').html('<option value="0">-- Tất cả --</option>');
                    $('#filter_bag').html('<option value="0">-- Tất cả --</option>');
                    $('.btnSelect').addClass('disabled');
                }
                if (type_filter == 'exam') {
                    if (str_data != '') {
                        $('#filter_subject').html(str_data);
                    } else
                    {
                        $('#filter_subject').html('<option value="0">-- Tất cả --</option>');
                    }
                    $('#filter_room').html('<option value="0">-- Tất cả --</option>');
                    $('#filter_bag').html('<option value="0">-- Tất cả --</option>');
                    $('.btnSelect').addClass('disabled');
                }
                if (type_filter == 'subject') {
                    if (str_data != '') {
                        $('#filter_room').html(str_data);
                    } else
                    {
                        $('#filter_room').html('<option value="0">-- Tất cả --</option>');
                        $('#filter_bag').html('<option value="0">-- Tất cả --</option>');
                    }
                    $('.btnSelect').addClass('disabled');
                }
                if (type_filter == 'room') {
                    if (str_data != '') {
                        $('#filter_bag').html(str_data);
                    } else
                    {
                        $('#filter_bag').html('<option value="0">-- Tất cả --</option>');
                    }
                    $('.btnSelect').addClass('disabled');
                }
            });

        });
// Event vào điểm thi của thí sinh
        $('#action-table').on('click', '.btnAddNew', function ()
        {
            var id_course = $('#filter_course option:selected').val();
            var name_exam = $('#filter_exam option:selected').val();
            var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// mã phòng được chọn
            var id_subject = $('#filter_subject option:selected').val();
            var id_room = $('#filter_room option:selected').val();
            var bag = $('#filter_bag option:selected').val();
            var success_list = 0;
            var error_list = 0;
            var totalsv = 0;
            $('#datatables tbody tr').each(function () {
                var thisis = $(this);
                var number = $(this).find("td.number").text();
                var student_id = $("#student_i_" + number).val();
                var mark_exam = $("#mark_exam_" + student_id).val();

                var postData = "Student:" + student_id + " Mark:" + mark_exam + " Course:" + id_course + " Exam Id: " + id_exam_list_hidden + " Exam Name: " + name_exam + " Subject: " + id_subject + " Room: " + id_room + " Room: " + id_room;
                //alert(postData);
                //chuyển trạng thái: khóa nút Cập nhật
                $('.btnAddNew').prop('disabled', true);
                $('.btnSelect').prop('disabled', true);
                $('.btnReset').prop('disabled', true);
                // cập nhật điểm thi của thí sinh
                $.post(ajaxUrl, {act: 'updatmark', id_student: student_id, mark_exam: mark_exam, id_course: id_course, name_exam: name_exam, id_exam: id_exam_list_hidden, id_subject: id_subject, room: id_room, bag: bag}, function (data) {
                    var data = $.parseJSON(data);
                    totalsv = totalsv + 1;
                    if (data.status == 'success') {
                        success_list = success_list + 1;
                        $("#mark_exam_" + student_id).css('background', "#ccc");
                        $("#mark_exam_" + student_id).prop('disabled', true);
                        var data_result = "Vào điểm thành công của " + success_list + "/" + totalsv + " thí sinh.";
                        $('.message2').html(data_result);
                        $('.alert-message').html(data_result);
                        $('.message2').addClass('alert-success');
                        $('.message2').addClass('alert');
                        $('.message2').show();
                    }
                    else if (data.status == 'danger')
                    {
                        $("#mark_exam_" + student_id).css('background', "#fff");
                        $("#mark_exam_" + student_id).prop('disabled', false);
                    }

                    console.log(postData + " Return: {" + student_id + "-" + data.status + "}");
                });
            });
            //$('.btnPrint').prop('disabled', false);
            //$('.btnAddNew').prop('disabled', false);
            $('.btnReset').prop('disabled', false);
            $('.btnSelect').prop('disabled', false);
        });
// Event chọn phòng thi		
        $('.btnSelect').click(function ()
        {
            var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// mã phòng được chọn
            var id_exam_room_hidden = $('#id_exam_room_str').val();		// số phòng được chọn
            var id_exam_bag_hidden = $('#id_exam_bag_str').val();		// số túi được chọn
            var id_exam_state_hidden = $('#id_exam_state_str').val(); 	// trạng thái danh sách
            var course = $('#filter_course option:selected').val();
            var exam = $('#filter_exam option:selected').val();
            var subject = $('#filter_subject option:selected').val();
            var room = $('#filter_room option:selected').val();
            var bag = $('#filter_bag option:selected').val();
            $('#course_selected').html(course);
            $('#exam_selected').html(exam);
            $('#subject_selected').html(subject);
            $('#room_selected').html(room);
            $('#bag_selected').html(bag);

            $('.panel-exam').slideDown();
            $('.panel-exam').addClass('panel-heading');
            $(".message2 .alert-message").html("");
            $('.btnSelect').prop('disabled', true);
            $('.btnReset').prop('disabled', true);
            // tải thông tin thí sinh
            $.post(ajaxUrl, {act: 'loadtester', id_exam: id_exam_list_hidden, room: id_exam_room_hidden, bag: id_exam_bag_hidden}, function (data)
            {
                //alert(data);					
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                // load lại table
                if (str_data != '') {
                    $('#datatables tbody').html(str_data);
                    $(".message2").show();
                    $(".message2").addClass('alert alert-success');
                    $(".message2").html("Danh sách đã được tải.");
                }
                $('.btnSelect').prop('disabled', false);
                $('.btnAddNew').prop('disabled', false);
                $('.btnReset').prop('disabled', false);
            });
        });
// Event tải lại danh sách thí sinh
        $('.btnReset').click(function ()
        {
            var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// đợt thi được chọn
            var id_exam_room_hidden = $('#id_exam_room_str').val();		// số phòng được chọn
            var id_exam_bag_hidden = $('#id_exam_bag_str').val();		// túi bài thi được chọn
            var id_exam_state_hidden = $('#id_exam_state_str').val(); 	// trạng thái danh sách
            $('.btnReset').prop('disabled', true);
            $('.btnSelect').prop('disabled', true);
            $.post(ajaxUrl, {act: 'loadtester', id_exam: id_exam_list_hidden, room: id_exam_room_hidden, bag: id_exam_bag_hidden}, function (data)
            {
                //alert(data);					
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                // load lại table
                if (str_data != '') {
                    $('#datatables tbody').html(str_data);
                    $(".message2").show();
                    $(".message2").addClass('alert alert-success');
                    $(".message2").html("Danh sách đã được tải.");
                }
                $('.btnSelect').prop('disabled', false);
                $('.btnAddNew').prop('disabled', false);
                $('.btnReset').prop('disabled', false);
            });
        });

    })(jQuery);

</script>
