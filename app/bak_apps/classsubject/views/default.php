<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/classsubject/css/style.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/classsubject/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/classsubject/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>

<div class="col-sm-2">
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
				<label for="" class="control-label">Năm:</label>
				<select name="filter_year" id="filter_year" class="form-control input-sm filter">
					<option value="">-- Chọn năm --</option>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Kì:</label>
				<select name="filter_term" id="filter_term" class="form-control input-sm filter">
					<option value="">-- Chọn kì --</option>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Môn học:</label>
				<select name="filter_subject" id="filter_subject" class="form-control input-sm">
					<option value="">-- Tất cả --</option>
				</select>
			</div>
		</from>
	</div>
</div>

<div class="col-sm-2">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<strong>DANH SÁCH LỚP MÔN HỌC</strong>
		</div>
		<div class="panel-body">
			<button class="btn btn-success btn-xs" id="newClassSubject"><i class="glyphicon glyphicon-plus"></i> Thêm mới</button>
		</div>
		<table class="table table-striped" id="listClassSubject"></table>
	</div>
</div>

<div class="col-sm-8">
	<div class="form-inline text-center col-sm-12">
		<div class="form-group">
			<label for="">Chọn khóa:</label>
			<select name="" id="sel_course" class="input-sm form-control">
				<option value="">-- Tất cả --</option>
				<?php foreach ($this->courses as $key => $value) { ?>
				<option value="<?=$key?>"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="">Chọn lớp:</label>
			<select name="" id="sel_class" class="input-sm form-control">
				<option value="">-- Chọn Lớp --</option>
				<?php foreach ($this->classes as $key => $value): ?>
				<option value="<?=$key?>" class="sel_class"><?=$value?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="message col-sm-12" style="display: none;">
		<div class="alert alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<span class="alert-message"></span>
		</div>
	</div>

	<div class="col-sm-7">
		<div class="overlay" style="display: none">
			<div class="loading"></div>
		</div>
		<div class="panel">
			<div class="panel-heading text-center">
				<strong>DS LỚP HỌC PHẦN</strong>
			</div>
			<table class="table table-striped table-condensed form-inline tbAutoScroll" id="class_subject_students">
				<thead>
					<tr>
						<th><div class="checkbox"><label><input type="checkbox" class="checkAllBtn" data-id="sclass_members"></label></div></th>
						<th>Mã học viên</th>
						<th>Họ và tên</th>
						<th>Lớp</th>
					</tr>
				</thead>
				<tbody id="sclass_members"></tbody>
			</table>
			<div class="panel-footer">
				<button class="btn btn-xs btn-danger" id="removeStudentBtn"><i class="glyphicon glyphicon-trash"></i> Xoá học viên khỏi lớp</button>
			</div>
		</div>
	</div>

	<div class="col-sm-5">
		<div class="panel">
			<div class="panel-heading text-center">
				<strong>DS LỚP HÀNH CHÍNH</strong>
			</div>
			<table class="table table-striped table-condensed form-inline tbAutoScroll" id="class_students">
				<thead>
					<tr>
						<th><div class="checkbox"><label><input type="checkbox" class="checkAllBtn" data-id="class_members"></label></div></th>
						<th>Mã học viên</th>
						<th>Họ và tên</th>
					</tr>
				</thead>
				<tbody id="class_members"></tbody>
			</table>
			<div class="panel-footer">
				<button class="btn btn-xs btn-primary" id="addStudentBtn"><i class="glyphicon glyphicon-plus-sign"></i> Thêm học viên vào lớp</button>
			</div>
		</div>
	</div>
</div>

<script>
(function(){

	var ajaxUrl = "<?=AppObject::getBaseFile('app/classsubject/helpers/ajax.php')?>";
	courseInfo = new Array();

	$('.tbAutoScroll').DataTable({
		ordering: false,
		paging: false,
		info: false,
		searching: false,
		scrollY: $(window).height() - 250,
	});

	$(document)
	.on('click', '.btn', function(){
		$('.alert').removeClass('.alert-danger .alert-success');
		$('.message').hide();
	})
	.on('change', '#filter_course', function(){
		var id_course = $(this).val();
		var id_group_field = $('#filter_group_field').val();
		$('#filter_year').removeData('options');
		$.post(ajaxUrl, {act: 'course_info', id_course: id_course}, function(data){
			var data = $.parseJSON(data);
			if (data.status == 'success') {
				$('.year_options').remove();
				$('.term_options').remove();
				$.each(data.returndata, function(index, val){
					$('#filter_year').append($('<option>').text(index).attr('value', index).addClass('year_options'));
					$.each(val, function(_index, _val){
						$('#filter_term').append($('<option>').text('Học kì ' + _val).attr('value', _val).addClass('term_options ' + index));
					})
				});
				$('#filter_year option:nth-child(2)').prop('selected', true);
				$('#filter_term option:nth-child(2)').prop('selected', true);
				if($('#filter_year').data('options') == undefined){
					$('#filter_year').data('options', $('#filter_term option').clone());
				}
				var year = $('#filter_year').val();
				var options = $('#filter_year').data('options').filter('.' + year);
				$('#filter_term').html(options);
				getListSubject();
			}
			else {
				$('.year_options').remove();
				$('.term_options').remove();
				$('#filter_term').append($('<option>').text('-- Chọn kì --').attr('value', ''));
			}
			// 3rd Col filter
			if ($('#sel_class').val() == '') {
				$('#sel_course').val(id_course);
				listClass(id_course, id_group_field);
			}
		});
	})
	.on('change', '#filter_group_field', function() {
		var id_course = $('#filter_course').val();
		var id_group_field = $(this).val();
		listClass(id_course, id_group_field);
	})
	.on('change', '#filter_year', function(){
		if($(this).data('options') == undefined){
			$(this).data('options', $('#filter_term option').clone());
		}
		var year = $(this).val();
		var options = $(this).data('options').filter('.' + year);
		$('#filter_term').html(options);
		$('#filter_term').children(':first').prop('selected', true);
		getListSubject();
	}).
	on('change', '.filter', function() {
		getListSubject();
	})
	.on('change', '#filter_subject', function() {
		getListClassSubject();
	})

	// 2nd Col
	.on('click', '#newClassSubject', function() {
		var id_subject = $('#filter_subject').val();
		var year = $('#filter_year').val();
		var term = $('#filter_term').val();
		var course = $('#filter_course').val();
		$.post(ajaxUrl, {act: 'add_class', id_subject: id_subject, year: year, term: term, course: course}, function(response) {
			response = $.parseJSON(response);
			if (response.status == 'success') {
				$('#listClassSubject').html(response.data);
			}
			else{
				$('.alert').addClass(response.status);
				$('.alert-message').html(response.message);
				$('.message').show();
			}
		});
	})
	.on('click', '.class_subject', function() {
		var id_classsubject = $(this).data('id');
		$('.selected').removeClass('selected');
		$(this).addClass('selected');
		classSubjectDetail(id_classsubject);
	})

	// 3rd Col
	.on('change', '#sel_course', function() {
		var id_course = $(this).val();
		var id_group_field = $('#filter_group_field').val();
		listClass(id_course, id_group_field);
	})
	.on('change', '#sel_class', function() {
		var id_classsubject = $('.class_subject.selected').data('id');
		classSubjectDetail(id_classsubject);
	})

	// Checkall
	.on('change', '.checkAllBtn', function(){
		// Checkall Button Change
		var id = $(this).data('id');
		$('tbody#' + id +' input:checkbox:not(:disabled)').prop('checked', this.checked);
	})
	.on('change', 'input:checkbox', function(){
		// Count Checkboxes
		var id = $(this).parents('tbody').attr('id');
		var totalCheckboxes = $('tbody#'+ id +' input:checkbox').length;
		// Count Checkboxes checked
		var totalChecked = $('tbody#'+ id +' input:checkbox:checked').length;
		if(totalCheckboxes == totalChecked)
			$('[data-id="'+ id +'"]').prop('checked', true);
		else
			$('[data-id="'+ id +'"]').prop('checked', false);
	})

	// Add and Remove
	.on('click', '#addStudentBtn', function(){
		var id_classsubject = $('.class_subject.selected').data('id');
		var allVals = [];
		$('tbody#class_members input:checkbox:checked').each(function(){
			allVals.push($(this).val());
		});
		$.post(ajaxUrl, {act: 'add_student', id_classsubject: id_classsubject, profiles: allVals}, function(response) {
			var data = $.parseJSON(response);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message .alert-message').html(data.message);
			$('.message').show();

			classSubjectDetail(id_classsubject);
		});
	})
	.on('click', '#removeStudentBtn', function(){
		var id_classsubject = $('.class_subject.selected').data('id');
		var allVals = [];
		$('tbody#sclass_members input:checkbox:checked').each(function(){
			allVals.push($(this).val());
		});
		$.post(ajaxUrl, {act: 'remove_student', id_classsubject: id_classsubject, profiles: allVals}, function(response) {
			var data = $.parseJSON(response);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message .alert-message').html(data.message);
			$('.message').show();

			classSubjectDetail(id_classsubject);
		});
	});

	var getListSubject = function(){
		var id_course = $('#filter_course').val();
		var id_group_field = $('#filter_group_field').val();
		var term = $('#filter_term').val();
		$.post(ajaxUrl, {act: 'list_subject', id_course: id_course, id_group_field: id_group_field, term: term}, function(response){
			var data = $.parseJSON(response);
			$('.subject_options').remove();
			$.each(data, function(index, val) {
				$('#filter_subject').append($('<option>').text(val).attr('value', index).addClass('subject_options'));
			});
		});
	}

	var getListClassSubject = function(){
		var id_subject = $('#filter_subject').val();
		var year = $('#filter_year').val();	
		var term = $('#filter_term').val();
		$.post(ajaxUrl, {act: 'class_subject', id_subject: id_subject, year: year, term: term}, function(response) {
			$('#listClassSubject').html(response);
		});
	}

	var classSubjectDetail = function(id_classsubject){
		$('.overlay').show();
		var id_class = $('#sel_class').val();
		var year = $('#filter_year').val();
		var term = $('#filter_term').val();
		var id_subject = $('#filter_subject').val();
		$.post(ajaxUrl, {act: 'class_detail', id_classsubject: id_classsubject, id_class: id_class, year: year, term: term, id_subject: id_subject}, function(response) {
			response = $.parseJSON(response);
			$('#class_subject_students tbody').html(response.class_subject);
			$('#class_students tbody').html(response.class_detail);
			$('.checkAllBtn').prop('checked', false);
			$('.overlay').hide();
		});
	}

	var listClass = function(id_course, id_group_field){
		$.post(ajaxUrl, {act: 'listclass', id_course: id_course, id_group_field: id_group_field}, function(response) {
			var data = $.parseJSON(response);
			$('.sel_class').remove();
			$.each(data, function(index, val) {
				$('#sel_class').append($('<option>').text(val).attr('value', index).addClass('sel_class'));
			});
		});
	}
})(jQuery);
</script>