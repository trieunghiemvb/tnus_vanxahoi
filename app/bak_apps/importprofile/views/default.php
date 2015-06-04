<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/importprofile/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/importprofile/css/style.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/importprofile/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/importprofile/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<div class="col-sm-3">
	<div class="well">
		<from class="form" role="form" id="filter">
			<div class="form-group">
				<label for="" class="control-label">Khóa:</label>
				<select name="filter_course" id="filter_course" class="form-control input-sm filter">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->courses as $key => $value) { ?>
					<option value="<?=$key?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Ngành:</label>
				<select name="filter_group_field" id="filter_group_field" class="form-control input-sm filter">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->group_fields as $key => $value) { ?>
					<option value="<?=$key?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Lớp:</label>
				<select name="filter_class" id="filter_class" class="form-control input-sm">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->classes as $key => $value) { ?>
					<option value="<?=$key?>" class="class_options"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
		</from>
	</div>
</div>

<div class="col-sm-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Nhập danh sách học viên từ file Excel</h4>
		</div>
		<div class="panel-body">
			<div class="well">
				<div class="message" style="display:none;">
					<div class="alert alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<span class="alert-message"></span>
					</div>
				</div>
				<div class="form">
					<div class="form-group">
						<label for="" class="control-label">Chọn file import: (<a href="<?php echo AppObject::getBaseFile('app/importprofile/helpers/importTemplate.xlsx')?>">Tải file mẫu import học viên</a>)</label>
						<input type="file" name="import_file" id="import_file">
					</div>
					<button class="btn btn-xs btn-primary previewBtn">Xem trước danh sách</button>
				</div>
				<input type="hidden" id="uploaded_file" value="">
			</div>
		</div>
		<table class="table table-striped tbAutoScroll" id="datatable">
			<thead>
				<tr>
					<th>#</th>
					<th>Họ và tên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Nơi sinh</th>
					<th>Ghi chú</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<div class="panel-footer">
			<button class="btn btn-xs btn-primary" id="importBtn">Import danh sách</button>
		</div>
	</div>
</div>

<!-- Ajax process - @by Duyld2108 -->
<script type="text/javascript">
(function(){
	var list_provices = <?php echo json_encode($this->provices) ?>;
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

	var ajaxUrl = "<?=AppObject::getBaseFile('app/importprofile/helpers/ajax.php')?>";

	// Filter Process
	$('#filter')
	.on('change', '.filter', function() {
		var id_course = $('#filter_course').val();
		var id_group_field = $('#filter_group_field').val();
		$.post(ajaxUrl, {act: 'getclass', id_course: id_course, id_group_field: id_group_field}, function(data) {
			var data = $.parseJSON(data);
			$('.class_options').remove();
			$.each(data, function(index, val) {
				$('#filter_class').append($('<option>').text(val).attr('value', index).addClass('class_options'));
			});
		});
	});

	$(document)
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('click', '.previewBtn', function(){
		var file = $('#import_file').prop('files')[0];
		var form_data = new FormData();
		form_data.append('file', file);
		form_data.append('act', 'review');
		$.ajax({
			url: ajaxUrl,
			type: 'post',
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			success: function(response){
				var data = $.parseJSON(response);
				if (data.status == 'success') {
					$('#uploaded_file').val(data.uploaded_file);
					$('#datatable tbody').html('');
					var i = 1;
					$.each(data.data_list, function(index, val){
						var row = '<tr><td>'+ i +'</td><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ val.birthday +'</td><td>'+ (val.sex == 1 ? 'Nam' : 'Nữ') +'</td><td>'+ list_provices[val.birth_place] +'</td><td>'+ val.note +'</td></tr>';
						$('#datatable tbody').append(row);
						i++;
					})
				} else {
					$('.alert-message').html(data.message);
					$('.message .alert').addClass('alert-' + data.status);
					$('.message').show();
				}
			}
		})
	})
	.on('click', '#importBtn', function () {
		var id_class = $('#filter_class').val();
		var uploaded_file = $('#uploaded_file').val();
		$.post(ajaxUrl, {act: 'import', id_class: id_class, uploaded_file: uploaded_file}, function(data) {
			var data = $.parseJSON(data);
			$('.alert-message').html(data.message);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message').show();
			if (data.status == 'success') {
				$('#datatable tbody').html('');
				$('#uploaded_file').val('');
			}
		});
	});
})(jQuery);
</script>