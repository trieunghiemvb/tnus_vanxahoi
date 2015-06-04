<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exammain/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/class/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/exammain/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammain/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammain/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exammain/js/scripts.js')?>"></script>
<style type="text/css">
.hightline{
	background: none repeat scroll 0 0 #c2c2c2;
}
</style>
<div class="col-sm-3">
    <div class="well">
		Hướng dẫn sử dụng
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Quản lý Đợt thi</h4>
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
                    <tr> <input type="hidden" name="id_exam_txt" id="id_exam_txt" value="">
                        <td>
                             <select name="filter_course" id="filter_course" class="form-control input-sm select_filter">
								<option value="">-- Khóa học --</option>
								<?php foreach ($this->course as $key => $value) { ?>
								<option value="<?=$key?>"><?=$value?></option>
									<?php } ?>
							</select>
                        </td>
                        <td>
                            <select name="filter_term" id="filter_term" class="form-control input-sm select_filter">
								<option value="">-- Học kỳ --</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
                        </td>
                        <td>
                            <input type="text" name="exam_name_txt" id="exam_name_txt" class="form-control input-sm" placeholder="Tên đợt thi">
                        </td>
                        <td>
                            <button class="btn btn-xs btn-info addnewBtn"><i class="glyphicon glyphicon-plus-sign"></i> Thêm mới</button>
                            <button class="btn btn-xs btn-primary updateBtn" style="display:none;"><i class="glyphicon glyphicon-upload"></i> Cập nhật</button>
                            <button class="btn btn-xs btn-danger cancelBtn" style="display:none;"><i class="glyphicon glyphicon-remove"></i> Hủy</button>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table table-striped tbAutoScroll" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khóa học</th>
                        <th>Kỳ học</th>
                        <th>Tên đợt thi</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    
					<?php
						$i = 0;
						foreach ($this->examlist as $item) {
							$i++;
                       
					?>
                    <tr id="exam_<?=$item['id_exam']?>">
                        <td><?=$i?></td>
                        <td class="course_name"><?=$item['course_name']?></td>
                        <td class="term"><?=$item['term']?></td>
                        <td class="exam_name"><?=$item['exam_name']?></td>
                        <td>
                            <input type="hidden" name="control_str" class="control_str" value="<?=$item['id_exam']?>;<?=$item['id_course']?>;<?=$item['term']?>;<?=$item['exam_name']?>">
                            <button class="btn btn-xs btn-warning editBtn" data-id="<?=$item['id_exam']?>"><i class="glyphicon glyphicon-pencil"></i> Sửa</button>
                            <button class="btn btn-xs btn-danger deleteBtn" data-id="<?=$item['id_exam']?>"><i class="glyphicon glyphicon-remove"></i> Xóa</button>
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
        
		$('.tbAutoScroll').DataTable({
			ordering: false,
			paging: false,
			info: false,
			searching: false,
			scrollY: $(window).height() - 350,
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
				var index = iDisplayIndex +1;
				$('td:eq(0)',nRow).html(index);
			}

		});

        var ajaxUrl = "<?=AppObject::getBaseFile('app/exammain/helpers/ajax.php')?>";

        $(document).on('click', '.btn', function(){
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });

        // Edit
        $('#datatable').on('click', '.editBtn', function() {
            var editData = $(this).parent('td').find('.control_str').val();
            var editData = editData.split(';');
            $('#id_exam_txt').val(editData[0]);
            $('#id_exam').val(editData[0]);
            $('#filter_course').val(editData[1]);	//course
            $('#filter_term').val(editData[2]);		//term
            $('#exam_name_txt').val(editData[3]);	//exam name
			$('#filter_course').prop('disabled', true);
			$('#filter_term').prop('disabled', true);
            $('.addnewBtn').hide();$('.updateBtn').show();$('.cancelBtn').show();
			var id = $(this).attr('data-id');
			var id = 1;
			$('#datatable').DataTable().row('#exam_' + id).addClass('hightline');
        });

        // Delete
        $('#datatable').on('click', '.deleteBtn', function() {
            if(confirm("Bạn có chắc chắn xóa mục được chọn không? Lưu ý: Mục đã xóa không thể phục hồi!") == false)
                return false;
            else{
                var id = $(this).attr('data-id');
                $.post(ajaxUrl, {act: 'delete', id: id}, function(data) {
                    if (data == 'success') {
                        $('#datatable').DataTable().row('#exam_' + id).remove().draw(false);
                        $('.alert-message').html('Xoá dữ liệu thành công');
                        $('.message .alert').addClass('alert-success');
                        $('.message').show();
                    };
                });
            }
        });

        // Addnew
        $('#action-table').on('click', '.addnewBtn', function() {
            var id_course = $('#filter_course').val();
            var term = $('#filter_term').val();
            var exam_name = $('#exam_name_txt').val();
			
            $.post(ajaxUrl, {act: 'addnew', id_course: id_course, term: term, exam_name: exam_name}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                   var html = data.returndata;                   
					$('#datatable > tbody').empty();
					$('#datatable').dataTable().append(html);
                    $('#action-table').find('input, select').val("");
                }
            });
        });

        // Update
        $('#action-table').on('click', '.updateBtn', function() {
            var id_exam = $('#id_exam_txt').val();
			var id_course = $('#filter_course').val();
            var term = $('#filter_term').val();
            var exam_name = $('#exam_name_txt').val();
			
            $.post(ajaxUrl, {act: 'update', id_exam: id_exam, id_course: id_course, term: term, exam_name: exam_name}, function(data) {
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                   var html = data.returndata;                   
					$('#datatable > tbody').empty();
					$('#datatable').dataTable().append(html);
                    $('#action-table').find('input, select').val("");
					$('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();                                  
                }
				$('#filter_course').prop('disabled', false);
				$('#filter_term').prop('disabled', false);
            });
        });

        // Cancel
        $('#action-table').on('click', '.cancelBtn', function() {
            $('#action-table').find('input, select').val("");
			$('#filter_course').prop('disabled', false);
			$('#filter_term').prop('disabled', false);
            $('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();
        });

        $('#filter_group').on('click', function(){
            filter = $('#filter_group option:selected').val();
            $('#datatable').dataTable().fnFilter(filter);
        })
    })(jQuery);
</script>