<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/classfile/css/style.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/classfile/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/plugins/dataTables/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/jquery.mask.min.js')?>"></script>
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

<!-- LIST PROFILE -->
<div class="col-sm-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Đánh mã học viên tự động</h4>
		</div>
		<div class="panel-body">
			<div class="message" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form form-horizontal">
					<div class="form-group">
						<label for="" class="col-sm-3 control-label">Tiền tố:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="codePrefix">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-3 control-label">Độ rộng:</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" id="codeLength">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-3 control-label">Bắt đầu:</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" id="codeStart">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<p><button class="btn btn-warning btnGenCodeAll">Đánh mã lại toàn bộ</button></p>
				<p><button class="btn btn-primary btnGenCodeNone">Đánh mã những học viên chưa có mã</button></p>
			</div>
		</div>
		<table class="table table-striped tbAutoScroll listStudent">
			<thead>
				<tr>
					<th>Họ và tên</th>
					<th>Giới tính</th>
					<th>Ngày sinh</th>
					<th>Nơi sinh</th>
					<th>Mã học viên</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
(function(){
	var list_provice = <?php echo json_encode($this->provices); ?>;
	var sex = ['Nữ', 'Nam'];
	$('.datepicker input').mask("00/00/0000", {placeholder: "__/__/____"});
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

	var ajaxUrl = "<?=AppObject::getBaseFile('app/gencode/helpers/ajax.php')?>";

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
			})
		});
		$.post(ajaxUrl, {act: 'filterstudent', id_course: id_course, id_group_field: id_group_field}, function(data) {
			var data = $.parseJSON(data);
			$('.listStudent tbody').html('');
			$.each(data, function(index, val) {
				var row = '<tr><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ sex[val.sex] +'</td><td>'+ val.birthday +'</td><td>'+ list_provice[val.birth_place] +'</td><td>'+ val.student_code +'</td></tr>';
				$('.listStudent tbody').append(row);
			});
		});
	})
	.on('change', '#filter_class', function() {
		var id_class = $(this).val();
		$('#id_class').val(id_class);
		$.post(ajaxUrl, {act: 'getstudent', id_class: id_class}, function(data) {
			var data = $.parseJSON(data);
			$('.listStudent tbody').html('');
			$.each(data, function(index, val) {
				var row = '<tr><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ sex[val.sex] +'</td><td>'+ val.birthday +'</td><td>'+ list_provice[val.birth_place] +'</td><td>'+ val.student_code +'</td></tr>';
				$('.listStudent tbody').append(row);
			})
		});
	});

	$(document)
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('click', '.btnGenCodeAll', function() {
		var id_course = $('#filter_course').val();
		var id_group_field = $('#filter_group_field').val();
		var id_class = $('#filter_class').val();
		var codePrefix = $('#codePrefix').val();
		var codeLength = $('#codeLength').val();
		var codeStart = $('#codeStart').val();
		$.post(ajaxUrl, {act: 'gencodeall', id_course: id_course, id_group_field: id_group_field, id_class: id_class, codePrefix: codePrefix, codeLength: codeLength, codeStart: codeStart}, function(data) {
			var data = $.parseJSON(data);
			$('.message .alert-message').html(data.message);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message').show();
			if(data.status == 'success'){
				$('.listStudent tbody').html('');
				$.each(data.returndata, function(index, val) {
					var row = '<tr><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ sex[val.sex] +'</td><td>'+ val.birthday +'</td><td>'+ list_provice[val.birth_place] +'</td><td>'+ val.student_code +'</td></tr>';
					$('.listStudent tbody').append(row);
				});
			}
		});
	})
	.on('click', '.btnGenCodeNone', function() {
		var id_course = $('#filter_course').val();
		var id_group_field = $('#filter_group_field').val();
		var id_class = $('#filter_class').val();
		var codePrefix = $('#codePrefix').val();
		var codeLength = $('#codeLength').val();
		var codeStart = $('#codeStart').val();
		$.post(ajaxUrl, {act: 'gencodenone', id_course: id_course, id_group_field: id_group_field, id_class: id_class, codePrefix: codePrefix, codeLength: codeLength, codeStart: codeStart}, function(data) {
			var data = $.parseJSON(data);
			$('.message .alert-message').html(data.message);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message').show();
			if(data.status == 'success'){
				$('.listStudent tbody').html('');
				$.each(data.returndata, function(index, val) {
					var row = '<tr><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ sex[val.sex] +'</td><td>'+ val.birthday +'</td><td>'+ list_provice[val.birth_place] +'</td><td>'+ val.student_code +'</td></tr>';
					$('.listStudent tbody').append(row);
				});
			}
		});
	});
})(jQuery);
</script>
