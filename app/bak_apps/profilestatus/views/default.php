<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/major/css/dataTables.bootstrap.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/major/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/major/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/major/js/plugins/dataTables/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/major/js/scripts.js')?>"></script>
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
			<h4>Cập nhật trạng thái học viên</h4>
		</div>
		<div class="panel-body">
			<div class="message" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
		</div>
		<table class="table table-striped tbAutoScroll list-profile" id="datatable">
			<thead>
				<tr>
					<th>#</th>
					<th>Họ và tên</th>
					<th>Mã học viên</th>
					<th>Ngày sinh</th>
					<th>Giới tính</th>
					<th>Trạng thái</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<div class="panel-footer">
			<button class="btn btn-sm btn-primary updateBtn">Cập nhật</button>
		</div>
	</div>
</div>

<!-- Ajax process - @by Duyld2108 -->
<script type="text/javascript">
(function(){
	$('.tbAutoScroll').DataTable({
		ordering: false,
		paging: false,
		info: false,
		searching: false,
		scrollY: $(window).height() - 250,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}

	});

	var ajaxUrl = "<?=AppObject::getBaseFile('app/profilestatus/helpers/ajax.php')?>";

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
	})
	.on('change', '#filter_class', function() {
		var id_class = $(this).val();
		$('#id_class').val(id_class);
		$.post(ajaxUrl, {act: 'getstudent', id_class: id_class}, function(data) {
			var data = $.parseJSON(data);
			$('.list-profile tbody').html('');
			var i = 0;
			$.each(data, function(index, val){
				i++;
				var row = '<tr id="profile_'+ val.id_profile +'"><td><span class="indexSTT">'+ i +'</span><input type="hidden" name="" value="'+ val.id_profile +'" class="id_profile"></td><td>'+ val.last_name +' '+ val.first_name +'</td><td>' + val.student_code + '</td><td>' + val.birthday + '</td><td>'+ (val.sex == 1 ? 'Nam' : 'Nữ') +'</td><td>' + buildStatus(val.status) + '</td></tr>';
				$('.list-profile tbody').append(row);
			})
		});
	});

	$(document)
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('click', '.updateBtn', function(){
		var postData = new Array();
		$('.list-profile tbody tr').each(function() {
			var id_profile = $(this).find('.id_profile').val();
			var status = $(this).find('.profile_status').val();
			postData.push(id_profile + ';' + status);
		});
		$.post(ajaxUrl, {act: 'update', postData: postData}, function(data) {
			data = $.parseJSON(data);
			$('.message .alert').removeClass('alert-success alert-danger');
			$('.message .alert').addClass('alert-' + data.status);
			$('.message .alert-message').html(data.message);
			$('.message').show();
			if (data.status == 'success') {
				var id_class = $('#filter_class').val();
				$.post(ajaxUrl, {act: 'getstudent', id_class: id_class}, function(data) {
					var data = $.parseJSON(data);
					$('.list-profile tbody').html('');
					var i = 0;
					$.each(data, function(index, val){
						i++;
						var row = '<tr id="profile_'+ val.id_profile +'"><td><span class="indexSTT">'+ i +'</span><input type="hidden" name="" value="'+ val.id_profile +'" class="id_profile"></td><td>'+ val.last_name +' '+ val.first_name +'</td><td>' + val.student_code + '</td><td>' + val.birthday + '</td><td>'+ (val.sex == 1 ? 'Nam' : 'Nữ') +'</td><td>' + buildStatus(val.status) + '</td></tr>';
						$('.list-profile tbody').append(row);
					})
				});
			}
		});
		console.log(postData);
	});

	var buildStatus = function(status){
		var select = '<select name="" class="form-control input-sm profile_status"><option value="1" '+ (status == 1 ? 'selected="selected"' : '') + '>Bình thường</option><option value="2" '+ (status == 2 ? 'selected="selected"' : '') + '>Bảo lưu</option><option value="3" '+ (status == 3 ? 'selected="selected"' : '') + '>Thôi học</option><option value="4" '+ (status == 4 ? 'selected="selected"' : '') + '>Xóa tên</option><option value="5" '+ (status == 5 ? 'selected="selected"' : '') + '>Tốt nghiệp</option></select>';
		return select;
	}
})(jQuery);
</script>