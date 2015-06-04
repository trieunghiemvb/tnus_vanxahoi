<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/course/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/class/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/course/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/course/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/course/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/course/js/scripts.js')?>"></script>
<div class="col-sm-3">
    <div class="well">
		Hướng dẫn sử dụng
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Quản lý khóa học</h4>
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
                            <input type="hidden" name="course_id" id="course_id" value="">
                            <input type="text" name="course_name" id="course_name" class="form-control input-sm" placeholder="Tên khóa học">
                        </td>
                        <td>
                            <input type="text" name="course_code" id="course_code" class="form-control input-sm" placeholder="Mã khóa học">
                        </td>
                        <td>
                            <input type="year" name="course_start" id="course_start" class="form-control input-sm" placeholder="Năm bắt đầu">
                        </td>
                        <td>
                            <input type="year" name="course_end" id="course_end" class="form-control input-sm" placeholder="Năm kết thúc">
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
                        <th>Têm khóa học</th>
                        <th>Mã khóa học</th>
                        <th>Năm bắt đầu</th>
                        <th>Năm kết thuc</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->items as $item) {
                        $i++;
                        list($course_start, $course_end) = explode('_', $item['period']);
                        ?>
                    <tr id="course_<?=$item['id']?>">
                        <td></td>
                        <td class="course_name"><?=$item['course_name']?></td>
                        <td class="course_code"><?=$item['course_code']?></td>
                        <td class="course_start"><?=$course_start?></td>
                        <td class="course_end"><?=$course_end?></td>
                        <td>
                            <input type="hidden" name="control_str" class="control_str" value="<?=$item['id']?>;<?=$item['course_name']?>;<?=$item['course_code']?>;<?=$course_start?>;<?=$course_end?>">
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

        var ajaxUrl = "<?=AppObject::getBaseFile('app/course/helpers/ajax.php')?>";

        $(document).on('click', '.btn', function(){
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });

        // Edit
        $('#datatable').on('click', '.editBtn', function() {
            var editData = $(this).parent('td').find('.control_str').val();
            var editData = editData.split(';');
            $('#course_id').val(editData[0]);
            $('#course_name').val(editData[1]);
            $('#course_code').val(editData[2]);
            $('#course_start').val(editData[3]);
            $('#course_end').val(editData[4]);
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
                        oTable.row('#course_' + id).remove().draw(false);
                        $('.alert-message').html('Xoá dữ liệu thành công');
                        $('.message .alert').addClass('alert-success');
                        $('.message').show();
                    };
                });
            }
        });

        // Addnew
        $('#action-table').on('click', '.addnewBtn', function() {
            var course_name = $('#course_name').val();
            var course_code = $('#course_code').val();
            var course_start = $('#course_start').val();
            var course_end = $('#course_end').val();

            $.post(ajaxUrl, {act: 'addnew', name: course_name, code: course_code, start: course_start, end: course_end}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    var row = '<tr id="course_' + tmp[0] + '"><td></td><td class="course_name">' + tmp[1] + '</td><td class="course_code">' + tmp[2] + '</td><td class="course_start">' + tmp[3] + '</td><td class="course_end">' + tmp[4] + '</td><td><input type="hidden" name="control_str" class="control_str" value="' + control_str + '"><button class="btn btn-xs btn-warning editBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> <button class="btn btn-xs btn-danger deleteBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-remove"></i> Xóa</button></td></tr>';
                    $('#datatable').dataTable().fnAddTr($(row)[0]);
                    $('#action-table').find('input, select').val("");
                }
            });
        });

        // Update
        $('#action-table').on('click', '.updateBtn', function() {
            var id = $('#course_id').val();
            var course_name = $('#course_name').val();
            var course_code = $('#course_code').val();
            var course_start = $('#course_start').val();
            var course_end = $('#course_end').val();
            $.post(ajaxUrl, {act: 'update', name: course_name, code: course_code, start: course_start, end: course_end, id: id}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if (data.status == 'success') {
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    $('#course_' + tmp[0] + ' .course_name').html(tmp[1]);
                    $('#course_' + tmp[0] + ' .course_code').html(tmp[2]);
                    $('#course_' + tmp[0] + ' .course_start').html(tmp[3]);
                    $('#course_' + tmp[0] + ' .course_end').html(tmp[4]);
                    $('#course_' + tmp[0] + ' .control_str').val(control_str);
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