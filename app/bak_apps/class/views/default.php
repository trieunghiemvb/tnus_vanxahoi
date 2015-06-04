<?php 
if ( !defined('AREA') ) {
    die('Access denied');
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/class/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/class/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/class/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/class/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/class/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/class/js/scripts.js')?>"></script>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Lọc danh sách lớp</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="form_filter" >
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Khóa:</label>
                    <div class="col-sm-8">
                        <select name="filter_course" id="filter_course" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->course as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Ngành:</label>
                    <div class="col-sm-8">
                        <select name="filter_group_field" id="filter_group_field" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->group_field as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Quản lý lớp</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <div class="message" style="display:none;">
                    <div class="alert alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <span class="alert-message"></span>
                    </div>
                </div>
                <table id="action-table" class="table">
                    <tr>
                        <td>
                            <input type="hidden" name="class_id" id="class_id" value="">
                            <input type="text" name="class_name" id="class_name" class="form-control input-sm" placeholder="Tên lớp tiếng Việt">
                        </td>
                        <td>
                            <input type="text" name="class_name_en" id="class_name_en" class="form-control input-sm" placeholder="Tên lớp tiếng Anh">
                        </td>
                        <td>
                            <input type="text" name="class_code" id="class_code" class="form-control input-sm" placeholder="Mã lớp">
                        </td>
                        <td>
                            <select name="id_course" id="id_course" id="" class="form-control input-sm">
                                <option value="">-- Khóa học --</option>
                                <?php foreach ($this->course as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="id_group_field" id="id_group_field" id="" class="form-control input-sm">
                                <option value="">-- Ngành --</option>
                                <?php foreach ($this->group_field as $key => $value) { ?>
                                <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr align="center">
                        <td colspan="5">
                            <button class="btn btn-xs btn-info addnewBtn"><i class="glyphicon glyphicon-plus-sign"></i> Thêm mới</button>
                            <button class="btn btn-xs btn-primary updateBtn" style="display:none;"><i class="glyphicon glyphicon-upload"></i> Cập nhật</button>
                            <button class="btn btn-xs btn-danger cancelBtn" style="display:none;"><i class="glyphicon glyphicon-remove"></i> Hủy</button>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên lớp</th>
                        <th>Mã lớp</th>
                        <th>Khóa học</th>
                        <th>Ngành</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->items as $item) {
                        $i++;
                        ?>
                    <tr id="class_<?=$item['id']?>">
                        <td class="number"><?=$i?></td>
                        <td class="class_name"><?=$item['class_name']?></td>
                        <td class="class_code"><?=$item['class_code']?></td>
                        <td class="id_course"><?=$this->course[$item['id_course']]?></td>
                        <td class="id_group_field"><?=$this->group_field[$item['id_group_field']]?></td>
                        <td>
                            <input type="hidden" name="control_str" class="control_str" 
                                   value="<?=$item['id']?>;<?=$item['class_name']?>;<?=$item['class_name_en']?>;<?=$item['class_code']?>;<?=$item['id_course']?>;<?=$item['id_group_field']?>">
                            <button class="btn btn-xs btn-warning editBtn" data-id="<?=$item['id']?>"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> 
                            <button class="btn btn-xs btn-danger deleteBtn" data-id="<?=$item['id']?>"><i class="glyphicon glyphicon-remove"></i> Xóa</button>
                        </td>
                    </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ajax xu ly: Made by Duyld2108-->
<script type="text/javascript">

    (function(){
        var oTable = $('#datatable').DataTable({
            ordering: false,
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                //                var index = iDisplayIndex +1;
                //                $('td:eq(0)',nRow).html(index);
            }
        });

        var ajaxUrl = "<?=AppObject::getBaseFile('app/class/helpers/ajax.php')?>";

        $(document).on('click', '.btn', function(){
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });

        // Edit
        $('#datatable').on('click', '.editBtn', function() {

            var editData = $(this).parent('td').find('.control_str').val();
            var editData = editData.split(';');
            $('#class_id').val(editData[0]);
            $('#class_name').val(editData[1]);
            $('#class_name_en').val(editData[2]);
            $('#class_code').val(editData[3]);
            $('#id_course').val(editData[4]);
            $('#id_group_field').val(editData[5]);
            $('.addnewBtn').hide();$('.updateBtn').show();$('.cancelBtn').show();
        });

        // Delete
        $('#datatable').on('click', '.deleteBtn', function() {
            if(confirm("Bạn có chắc chắn xóa mục được chọn không? Lưu ý: Mục đã xóa không thể phục hồi!") == false)
                return false;
            else{
                var id = $(this).attr('data-id');
                $.post(ajaxUrl, {act: 'delete', id: id}, function(data) {
                    if (data == 'success') {
                        oTable.row('#class_' + id).remove().draw(false);
                        $('.alert-message').html('Xoá dữ liệu thành công');
                        $('.message .alert').addClass('alert-success');
                        $('.message').show();
                    };
                });
            }
        });

        // Addnew
        $('#action-table').on('click', '.addnewBtn', function() {
            var class_name = $('#class_name').val();
            var class_name_en = $('#class_name_en').val();
            var class_code = $('#class_code').val();
            var id_course = $('#id_course').val();
            var id_group_field = $('#id_group_field').val();

            $.post(ajaxUrl, {act: 'addnew', class_name: class_name,class_name_en: class_name_en, class_code: class_code, id_course:id_course, id_group_field:id_group_field}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    var row = '<tr id="class_' + tmp[0] + '">'+
                        '<td></td>'+
                        '<td class="class_name">' + tmp[1] + '</td>'+
                        '<td class="class_code">' + tmp[3] + '</td>'+
                        '<td class="id_course">' + tmp[6] + '</td>'+
                        '<td class="id_group_field">' + tmp[7] + '</td>'+
                        '<td><input type="hidden" name="control_str" class="control_str" value="' + control_str + '">'+
                        '<button class="btn btn-xs btn-warning editBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> '+
                        ' <button class="btn btn-xs btn-danger deleteBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-remove"></i> Xóa</button></td>'+
                        '</tr>';
                    $('#datatable').dataTable().fnAddTr($(row)[0]);
                    $('#action-table').find('input, select').val("");
                }
            });
        });

        // Update
        $('#action-table').on('click', '.updateBtn', function() {
            var id = $('#class_id').val();
            var class_name = $('#class_name').val();
            var class_name_en = $('#class_name_en').val();
            var class_code = $('#class_code').val();
            var id_course = $('#id_course').val();
            var id_group_field = $('#id_group_field').val();
            $.post(ajaxUrl, {act: 'update', class_name: class_name,class_name_en: class_name_en, class_code: class_code, id_course:id_course, id_group_field:id_group_field, id: id}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if (data.status == 'success') {
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    $('#class_' + tmp[0] + ' .class_name').html(tmp[1]);
                    $('#class_' + tmp[0] + ' .class_code').html(tmp[3]);
                    $('#class_' + tmp[0] + ' .id_course').html(tmp[6]);
                    $('#class_' + tmp[0] + ' .id_group_field').html(tmp[7]);
                    $('#class_' + tmp[0] + ' .control_str').val(control_str);
                    $('#action-table').find('input, select').val("");
                    $('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();
                }
            });
        });

        // Cancel
        $('#action-table').on('click', '.cancelBtn', function() {
            $('#action-table').find('input, select').val("");
            $('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();
        });

        // Bắt sự kiện thay đổi điều kiện lọc trên #form_filter
        $('#form_filter').on('change','.select_filter', function(){
            $('#str_filter').val($('#filter_course').val()+";"+$('#filter_group_field').val()+";"+$('#filter_group_field').val())
            var id_course = $('#filter_course').val();
            var id_group_field = $('#filter_group_field').val();
            $.post(ajaxUrl, {act: 'loadtable', id_course:id_course, id_group_field:id_group_field}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();

                var str_data = data.returndata;
                var row = str_data.split('#');
                // Xóa toàn bộ dòng và refresh datatable
                $('#datatable').DataTable().clear().draw();
                var i=0;
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    if(tmp[0]!=null&&tmp[0]!=""){
                        r = '<tr id="class_' + tmp[0] + '">'+
                            '<td class="number">'+i+'</td>'+
                            '<td class="class_name">' + tmp[1] + '</td>'+
                            '<td class="class_code">' + tmp[3] + '</td>'+
                            '<td class="id_course">' + tmp[6] + '</td>'+
                            '<td class="id_group_field">' + tmp[7] + '</td>'+
                            '<td><input type="hidden" name="control_str" class="control_str" value="' + value + '">'+
                            '<button class="btn btn-xs btn-warning editBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> '+
                            '<button class="btn btn-xs btn-danger deleteBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-remove"></i> Xóa</button></td>'+
                            '</tr>';
                        $('#datatable').dataTable().fnAddTr($(r)[0]);
                    }else
                        $('#datatable').DataTable().draw();
                })
            });
        })
    })(jQuery);
</script>