<?php
if ( !defined('AREA') ) {
    die('Access denied');


}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/groupfield/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/class/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/scripts.js')?>"></script>
<div class="col-sm-3">
    <div class="well">
		Hướng dẫn sử dụng
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Quản lý ngành học</h4>
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
                            <input type="hidden" name="group_field_id" id="group_field_id" value="">
                            <input type="text" name="group_field_name" id="group_field_name" class="form-control input-sm" placeholder="Tên ngành học">
                        </td>
                        <td>
                            <input type="text" name="group_field_name_en" id="group_field_name_en" class="form-control input-sm" placeholder="Tên tiếng Anh">
                        </td>
                        <td>
                            <input type="year" name="group_field_code" id="group_field_code" class="form-control input-sm" placeholder="Mã ngành học">
                        </td>                        
                        <td>
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
                        <th>Tên ngành học</th>
                        <th>Tên tiếng Anh</th>
                        <th>Mã ngành học</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->items as $item) {
                        $i++;
                        ?>
                    <tr id="group_field_<?=$item['id']?>">
                        <td></td>
                        <td class="group_field_name"><?=$item['group_field_name']?></td>
                        <td class="group_field_name_en"><?=$item['group_field_name_en']?></td>
                        <td class="group_field_code"><?=$item['group_field_code']?></td>
                        <td>
                            <input type="hidden" name="control_str" class="control_str" value="<?=$item['id']?>;<?=$item['group_field_name']?>;<?=$item['group_field_name_en']?>;<?=$item['group_field_code']?>">
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
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
            }
        });

        var ajaxUrl = "<?=AppObject::getBaseFile('app/groupfield/helpers/ajax.php')?>";

        $(document).on('click', '.btn', function(){
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });

        // Edit
        $('#datatable').on('click', '.editBtn', function() {
            var editData = $(this).parent('td').find('.control_str').val();
            var editData = editData.split(';');
            $('#group_field_id').val(editData[0]);
            $('#group_field_name').focus();
            $('#group_field_name').val(editData[1]);
            $('#group_field_name_en').val(editData[2]);
            $('#group_field_code').val(editData[3]);
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
                        oTable.row('#group_field_' + id).remove().draw(false);
                       $('#datatable').dataTable().fnFilter('');
                        $('.alert-message').html('Xoá dữ liệu thành công');
                        $('.message .alert').addClass('alert-success');
                        $('.message').show();
                    };
                });
            }
        });

        // Addnew
        $('#action-table').on('click', '.addnewBtn', function() {
            var name = $('#group_field_name').val();
            var value = $('#group_field_name_en').val();
            var code = $('#group_field_code').val();

            $.post(ajaxUrl, {act: 'addnew', name: name, code: code, value: value}, function(data) {			
				var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    var row = '<tr id="group_field_' + tmp[0] + '"><td></td><td class="group_field_name">' + tmp[1] + '</td><td class="group_field_name_en">' + tmp[2] + '</td><td class="group_field_code">' + tmp[3] + '</td><td><input type="hidden" name="control_str" class="control_str" value="' + control_str + '"><button class="btn btn-xs btn-warning editBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> <button class="btn btn-xs btn-danger deleteBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-remove"></i> Xóa</button></td></tr>';
                    $('#datatable').dataTable().fnAddTr($(row)[0]);
                    $('#action-table').find('input, select').val("");
                    $('#datatable').dataTable().fnFilter('');
                }
            });
        });

        // Update
        $('#action-table').on('click', '.updateBtn', function() {
            var id = $('#group_field_id').val();
           var name = $('#group_field_name').val();
            var value = $('#group_field_name_en').val();
            var code = $('#group_field_code').val();
            $.post(ajaxUrl, {act: 'update', name: name, code: code, value: value, id: id}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if (data.status == 'success') {
                    var control_str = data.returndata;
					
                    var tmp = control_str.split(';');
                    $('#group_field_' + tmp[0] + ' .group_field_name').html(tmp[1]);
                    $('#group_field_' + tmp[0] + ' .group_field_name_en').html(tmp[2]);
                    $('#group_field_' + tmp[0] + ' .group_field_code').html(tmp[3]);
                    $('#group_field_' + tmp[0] + ' .control_str').val(control_str);
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

        $('#filter_group').on('click', function(){
            filter = $('#filter_group option:selected').val();
            $('#datatable').dataTable().fnFilter(filter);
        })
    })(jQuery);
</script>