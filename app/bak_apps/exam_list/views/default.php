<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exam_list/css/app.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exam_list/css/dataTables.bootstrap.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/exam_list/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/exam_list/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<div class="col-sm-12">
	<div class="form-inline filters">
		<div class="form-group">
			<label for="" class="control-label">Chọn đợt thi</label>
			<select name="filter_exam" id="filter_exam" class="form-control input-sm filter">
				<option value="">-- Chọn đợt thi --</option>
				<?php foreach ($this->exams as $key => $value) { ?>
				<option value="<?=$key?>"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Ngành học</label>
			<select name="filter_group_field" id="filter_group_field" class="form-control input-sm filter">
				<option value="">-- Tất cả --</option>
				<?php foreach ($this->group_field as $key => $value) { ?>
				<option value="<?=$key?>"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Chọn môn học</label>
			<select name="filter_subject" id="filter_subject" class="form-control input-sm">
				<option value="">-- Tất cả --</option>
			</select>
		</div>
	</div>
</div>
<div class="col-sm-3">
	<div class="panel panel-success">
		<div class="panel-heading">Thông báo</div>
		<div class="panel-body">
			<div id="app_message" style="font-weight:bold; color: #f00; display:none;"></div>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Hình thức thi</div>
		<div class="panel-body">
			<span class="exam_type_mess"></span>
			<div class="radio">
				<label>
					<input type="radio" name="exam_type" id="exam_type_0" value="0" checked>
					Chưa xác định
					</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="exam_type" id="exam_type_1" value="1">
					Viết
					</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="exam_type" id="exam_type_2" value="2">
					Vấn đáp
					</label>
			</div>
			<button class="btn btn-xs btn-success" id="update_exam_type">Cập nhật hình thức thi</button>
		</div>
	</div>
	<div class="panel panel-info" id="">
		<div class="panel-heading">Các lớp học phần</div>
		<table class="table table-striped" id="listClassSubject"></table>
	</div>
</div>
<div class="col-sm-9">
	<div class="col-sm-6">
		<div class="panel panel-danger">
			<div class="panel-heading">Danh sách lớp học phần</div>
			<table class="table table-striped table-condensed form-inline tbAutoScroll">
				<thead>
					<tr>
						<th><div class="checkbox"><label><input type="checkbox" class="checkAllBtn" data-id="listClassSubject_details"></label></div></th>
						<th>Mã học viên</th>
						<th>Họ đêm</th>
						<th>Tên</th>
						<th>Lớp</th>
					</tr>
				</thead>
				<tbody id="listClassSubject_details"></tbody>
			</table>
			<div class="panel-footer">
				<button class="btn btn-sm btn-primary" id="addToList"><i class="glyphicon glyphicon-plus-sign"></i> Thêm học viên vào danh sách</button>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">Danh sách thí sinh</div>
			<table class="table table-striped table-condensed form-inline tbAutoScroll">
				<thead>
					<tr>
						<th><div class="checkbox"><label><input type="checkbox" class="checkAllBtn" data-id="listExam_details"></label></div></th>
						<th>Mã học viên</th>
						<th>Họ đêm</th>
						<th>Tên</th>
						<th>Lớp</th>
					</tr>
				</thead>
				<tbody id="listExam_details"></tbody>
			</table>
			<div class="panel-footer">
				<button class="btn btn-sm btn-danger" id="removeFromList"><i class="glyphicon glyphicon-trash"></i> Xóa học viên khỏi danh sách</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	(function(){
		var list_group_field = <?php echo json_encode($this->group_field); ?>;

		$('.tbAutoScroll').DataTable({
			ordering: false,
			paging: false,
			info: false,
			searching: false,
			scrollY: $(window).height() - 280,
		});

		var ajaxUrl = "<?=AppObject::getBaseFile('app/exam_list/helpers/ajax.php')?>";

		$('.filters').on('change', '.filter', function() {
			var id_exam = $('#filter_exam').val();
			var group_field = $('#filter_group_field').val();
			$.post(ajaxUrl, {act: 'getsubject', examid: id_exam, group_field: group_field}, function(data) {
				var data = $.parseJSON(data);
				// Display list Subject
				$('#filter_subject .subject_options').remove();
				$.each(data, function(index, val) {
					$('#filter_subject').append($('<option>').text(val).attr('value', index).addClass('subject_options'));
				});
			});
		});

		$('#filter_subject').on('change', function() {
			var id_exam = $('#filter_exam').val();
			var group_field = $('#filter_group_field').val();
			var id_subject = $(this).val();
			$.post(ajaxUrl, {act: 'subjectinfo', examid: id_exam, subject: id_subject, group_field: group_field}, function(data) {
				var data = $.parseJSON(data);
				// Exam type
				$('#exam_type_' + data.type).prop('checked', true);
				$('#listClassSubject').html(data.class_subject);
				$('#listClassSubject_details').html('');
				getExamListDetail();
			});
		});

		$('#update_exam_type').on('click', function() {
			$('.exam_type_mess').html('');
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			var exam_type = $('input[name=exam_type]:checked').val();
			$.post(ajaxUrl, {act: 'update_exam_type', examid: id_exam, subject: id_subject, type: exam_type}, function(data) {
				$('#app_message').html(data).fadeIn(500).delay(2000).fadeOut(500);
			});
		});

		$(document).on('click', '.class_subject', function() {
			$('.class_subject').removeClass('selected');
			$(this).addClass('selected');
			getClassSubjectDetail();
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
		.on('click', '#addToList', function() {
			var allVals = [];
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			$('tbody#listClassSubject_details input:checkbox:checked').each(function(){
				allVals.push($(this).val());
			});
			$.post(ajaxUrl, {act: 'add_student', id_exam: id_exam, id_subject: id_subject, profiles: allVals}, function(response) {
				$('#app_message').html(response).fadeIn(500).delay(1500).fadeOut(500);
				$('.checkAllBtn').prop('checked', false);
				if (response){
					getExamListDetail();
					getClassSubjectDetail();
				}
			});
		})
		.on('click', '#removeFromList', function() {
			var allVals = [];
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			$('tbody#listExam_details input:checkbox:checked').each(function(){
				allVals.push($(this).val());
			});
			$.post(ajaxUrl, {act: 'remove_student', id_exam: id_exam, id_subject: id_subject, profiles: allVals}, function(response) {
				$('#app_message').html(response).fadeIn(500).delay(1500).fadeOut(500);
				$('.checkAllBtn').prop('checked', false);
				if (response){
					getExamListDetail();
					getClassSubjectDetail();
				}
			});
		});

		var getExamListDetail = function(){
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			$.post(ajaxUrl, {act: 'getExamListDetail', id_exam: id_exam, id_subject: id_subject}, function(data){
				$('#listExam_details').html(data);
			});
		}

		var getClassSubjectDetail = function(){
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			var id_class_subject = $('.class_subject.selected').data('id');
			$.post(ajaxUrl, {act: 'getClassSubjectDetail', id_class_subject: id_class_subject, id_exam: id_exam, id_subject: id_subject}, function(data){
				$('#listClassSubject_details').html(data);
			});
		}
	})(jQuery);
</script>