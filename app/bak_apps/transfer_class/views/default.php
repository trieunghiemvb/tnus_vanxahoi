<?php  
if ( !defined('AREA') ) {
    die('Access denied');
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/transfer_class/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/transfer_class/css/style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/transfer_class/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/transfer_class/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/transfer_class/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/transfer_class/js/scripts.js')?>"></script>

<!-- class_stay -->
<div class="col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Chọn lớp cần chuyển</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <form class="form-horizontal" role="form" id="form_filter_class_stay" >
                    <div class="form-group" >
                        <label for="" class="col-sm-2 control-label">Khóa:</label>
                        <div class="col-sm-4">
                            <select name="filter_course_stay" id="filter_course_stay" class="form-control input-sm select_filter">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->course as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </div>
                        <label for="" class="col-sm-2 control-label">Ngành:</label>
                        <div class="col-sm-4">
                            <select name="filter_group_field_stay" id="filter_group_field_stay" class="form-control input-sm select_filter">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->group_field as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Lớp:</label>
                        <div class="col-sm-10">
                            <select name="filter_class_stay" id="filter_class_stay" class="form-control input-sm">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->class as $item) { ?>
                                <option value="<?=$item['id']?>"><?=$item['class_name']?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="auto_scroll profile_scroll" id="profile_scroll">
                <table id="list_students_stay" class="table table-striped" data-toggle="table" data-height="550">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th align="center"><input type='checkbox' id="chk_all_stay" name='chk_all_stay' /></th>
                            <th>Mã học viên</th>
                            <th>Tên</th>
                            <th>Họ đệm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">Chọn lớp để tải danh sách sinh viên</td>
                        </tr>

                    </tbody>
                </table>
            </div><!-- /.auto_scroll -->
        </div>
    </div>
</div>
<!-- /.col-sm-5 -->

<!-- action -->
<div class="col-sm-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Tác vụ</h4>
        </div>
        <div class="panel-body">
            <table id="action-table" class="table">
                <tbody>
                    <tr align="center">
                        <td>
                            <button id="btnTransfer" class="btn btn-md btn-info btnTransfer"> Chuyển lớp <i class="glyphicon glyphicon-forward"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </div>
    <div class="well">
        <div class="message" style="display:none;">
            <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <span class="alert-message"></span>
            </div>
        </div>
    </div>
</div>
<!-- /.col-sm-2 -->

<!-- class_transfer -->
<div class="col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Chọn lớp chuyển tới</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <form class="form-horizontal" role="form" id="form_filter_class_transfer" >
                    <div class="form-group" >
                        <label for="" class="col-sm-2 control-label">Khóa:</label>
                        <div class="col-sm-4">
                            <select name="filter_course_transfer" id="filter_course_transfer" class="form-control input-sm select_filter">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->course as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </div>
                        <label for="" class="col-sm-2 control-label">Ngành:</label>
                        <div class="col-sm-4">
                            <select name="filter_group_field_transfer" id="filter_group_field_transfer" class="form-control input-sm select_filter">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->group_field as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Lớp:</label>
                        <div class="col-sm-10">
                            <select name="filter_class_transfer" id="filter_class_transfer" class="form-control input-sm">
                                <option value="">-- Tất cả --</option>
                                <?php foreach ($this->class as $item) { ?>
                                <option value="<?=$item['id']?>"><?=$item['class_name']?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="auto_scroll profile_scroll" id="profile_scroll">
                <table id="list_students_transfer" class="table table-striped" data-toggle="table" data-height="550">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã học viên</th>
                            <th>Tên</th>
                            <th>Họ đệm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">Chọn lớp để tải danh sách sinh viên</td>
                        </tr>

                    </tbody>
                </table>
            </div><!-- /.auto_scroll -->
        </div>
    </div>
</div>
<!-- /.col-sm-5 -->

<script type="text/javascript">

    (function(){

        var ajaxUrl = "<?=AppObject::getBaseFile('app/transfer_class/helpers/ajax.php')?>"; 

        // Bắt sự kiện thay đổi điều kiện lọc trên #form_filter_class_stay
        $('#form_filter_class_stay').on('change','.select_filter', function(){
            //console.log("Running! -- ");
            var id_course = $('#form_filter_class_stay #filter_course_stay').val();
            var id_group_field = $('#form_filter_class_stay #filter_group_field_stay').val();
            //console.log("id_course_stay -- " + id_course + " id_group_field_stay -- " + id_group_field);
            $.post(ajaxUrl, {act: 'clstay', id_course:id_course, id_group_field:id_group_field}, function(data) {
                //console.log("data -- " + data);
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                // xóa option của select class trừ option đầu tiên
                $('#filter_class_stay').find('option:not(:first)').remove();
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        $('#filter_class_stay').append('<option value="'+tmp[0]+'">'+tmp[1]+'</option>');
                    }
                })
            });
        })

        // Bắt sự kiện thay đổi điều kiện lọc trên #form_filter_class_transfer
        $('#form_filter_class_transfer').on('change','.select_filter', function(){
            //console.log("Running! -- ");
            var id_course = $('#form_filter_class_transfer #filter_course_transfer').val();
            var id_group_field = $('#form_filter_class_transfer #filter_group_field_transfer').val();
            //console.log("id_course_transfer -- " + id_course + " id_group_field_transfer -- " + id_group_field);
            $.post(ajaxUrl, {act: 'cltransfer', id_course:id_course, id_group_field:id_group_field}, function(data) {
                //console.log("data -- " + data);
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                // xóa option của select class trừ option đầu tiên
                $('#filter_class_transfer').find('option:not(:first)').remove();
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        $('#filter_class_transfer').append('<option value="'+tmp[0]+'">'+tmp[1]+'</option>');
                    }
                })
            });
        })

        // Bắt sự kiện chọn class trên select #filter_class_stay
        $('#form_filter_class_stay').on('change','#filter_class_stay', function(){
            // return when not select class_filter
            if ($('#filter_class_stay').val()=="") return;
            //console.log("Running! -- ");
            var id_class = $('#filter_class_stay').val();
            // xóa trống bảng
            $("#list_students_stay > tbody").html("");
            //console.log("id_class=" + id_class);
            $.post(ajaxUrl, {act: 'loadstay', id_class:id_class}, function(data) {
                //console.log("data -- " + data);
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        var r="<tr id='row_stay_"+tmp[0]+"' >"+
                            "<td>"+i+"</td>"+
                            "<td align='center'>"+
                            "<input class='chk_stay' type='checkbox' id='chk_stay_"+tmp[0]+"' name='chk_stay_"+tmp[0]+"' value='"+tmp[0]+"'  onclick='sl_student("+tmp[0]+")' />"+
                            "</td>"+
                            "<td>"+tmp[4]+"</td>"+
                            "<td>"+tmp[3]+"</td>"+
                            "<td>"+tmp[2]+"</td>"+
                            "</tr>";
                        //console.log('row: '+ r);
                        $('#list_students_stay > tbody').append(r);
                    }
                })
            });
        })

        // Bắt sự kiện chọn class trên select #filter_class_transfer
        $('#form_filter_class_transfer').on('change','#filter_class_transfer', function(){
            // return when not select class_filter
            if ($('#filter_class_transfer').val()=="") return;
            //console.log("Running! -- ");
            var id_class = $('#filter_class_transfer').val();
            // xóa trống bảng
            $("#list_students_transfer > tbody").html("");
            //console.log("id_class=" + id_class);
            $.post(ajaxUrl, {act: 'loadtf', id_class:id_class}, function(data) {
                //console.log("data -- " + data);
                var data = $.parseJSON(data);
                var str_data = data.returndata;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        var r="<tr >"+
                            "<td>"+i+"</td>"+
                            "<td>"+tmp[4]+"</td>"+
                            "<td>"+tmp[3]+"</td>"+
                            "<td>"+tmp[2]+"</td>"+
                            "</tr>";
                        //console.log('row: '+ r);
                        $('#list_students_transfer > tbody').append(r);
                    }
                })
            });
        })

        // Check all select #form_filter_class_transfer
        $('#chk_all_stay').click(function(event) {
            //console.log('Running!');
            if(this.checked) {
                $('.chk_stay').each(function() {
                    //console.log('value='+this.value);
                    this.checked = true;
                });
            }else{
                $('.chk_stay').each(function() {
                    this.checked = false;
                });
            }
        });

        // Check all select #form_filter_class_transfer
        $('#chk_all_transfer').click(function(event) {
            //console.log('Running!');
            if(this.checked) {
                $('.chk_transfer').each(function() {
                    this.checked = true;
                });
            }else{
                $('.chk_transfer').each(function() {
                    this.checked = false;
                });
            }
        });

        // do when #btnTransfer clicked
        $("#btnTransfer").click(function() {
            //console.log('Running btnTransfer!');
            var students_transfer = '';
            var n = $(".chk_stay:checked").length;
            console.log('Students Num='+n);
            if (n > 0){
                $(".chk_stay:checked").each(function(){
                    students_transfer = students_transfer + $(this).val() + ";";
                });
            }
            //students_transfer.substr(0, students_transfer.length-1);
            console.log('students_transfer='+students_transfer);
            var id_class_transfer=$("#filter_class_transfer").val();
            var id_class_stay=$("#filter_class_stay").val();
            // xóa trống bảng
            $("#list_students_transfer > tbody").html("");
            $("#list_students_stay > tbody").html("");

            $.post(ajaxUrl, {act: 'transfer', students_transfer:students_transfer, id_class_transfer:id_class_transfer, id_class_stay:id_class_stay}, function(data) {
                console.log("data response:" + data);
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();

                // reload list students in class transfered
                var str_data = data.classtransfered;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        var r="<tr >"+
                            "<td>"+i+"</td>"+
                            "<td>"+tmp[4]+"</td>"+
                            "<td>"+tmp[3]+"</td>"+
                            "<td>"+tmp[2]+"</td>"+
                            "</tr>";
                        //console.log('row: '+ r);
                        $('#list_students_transfer > tbody').append(r);
                    }
                })

                // reload list students in class stay
                var str_data = data.classstay;
                var row = str_data.split('#');
                //console.log('row: '+ row);
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    //console.log('tmp: '+ tmp);
                    if(tmp[0]!=null&&tmp[0]!=""){
                        var r="<tr id='row_stay_"+tmp[0]+"' >"+
                            "<td>"+i+"</td>"+
                            "<td align='center'>"+
                            "<input class='chk_stay' type='checkbox' id='chk_stay_"+tmp[0]+"' name='chk_stay_"+tmp[0]+"' value='"+tmp[0]+"'  onclick='sl_student("+tmp[0]+")' />"+
                            "</td>"+
                            "<td>"+tmp[4]+"</td>"+
                            "<td>"+tmp[3]+"</td>"+
                            "<td>"+tmp[2]+"</td>"+
                            "</tr>";
                        //console.log('row: '+ r);
                        $('#list_students_stay > tbody').append(r);
                    }
                })
            });
        });

    })(jQuery);

    function sl_student(id){
        console.log('Running! id='+ $("#chk_stay_"+id).is(':checked'));
        if($("#chk_stay_"+id).is(':checked'))
            $("#row_stay_"+id).addClass('row_selected');
        else
            $("#row_stay_"+id).removeClass('row_selected');
    }
</script>