<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/exam_beat/css/app.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/exam_beat/js/jquery.download.js')?>"></script>
<div class="col-sm-12">
	<div class="form-inline filters">
		<div class="form-group">
			<label for="" class="control-label">Khóa học</label>
			<select name="filter_course" id="filter_course" class="form-control input-sm">
				<option value="">-- Tất cả --</option>
				<?php foreach ($this->courses as $key => $value) { ?>
				<option value="<?=$key?>"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Chọn đợt thi</label>
			<select name="filter_exam" id="filter_exam" class="form-control input-sm filter">
				<option value="">-- Chọn đợt thi --</option>
				<?php foreach ($this->exams as $key => $value) { ?>
				<option value="<?=$key?>" class="exam_options"><?=$value?></option>
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
	<p><strong style="color:#f00;">Quy tắc đánh phách: Đánh ngẫu nhiên - Không đánh phách thí sinh vắng mặt</strong></p>
</div>
<div class="col-sm-12">
	<div class="panel panel-primary">
		<div class="panel-heading"><strong>Đánh phách và chia túi bài chấm</strong></div>
		<div class="panel-body">
			<div class="col-sm-12" id="examListCounter">Chi tiết danh sách thi: <span></span></div>
			<div class="col-sm-4">
				<div class="form-horizontal">
					<div class="form-group">
						<label for="" class="control-label col-sm-4">Số phách bắt đầu:</label>
						<div class="col-sm-6">
							<input type="number" class="form-control input-sm" id="beat_start" value="3">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-sm-4">Bước phách:</label>
						<div class="col-sm-6">
							<input type="number" class="form-control input-sm" id="beat_step" value="1">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-horizontal">
					<div class="form-group">
						<label for="" class="control-label col-sm-6">Số bài thi / túi bài chấm:</label>
						<div class="col-sm-6">
							<input type="number" class="form-control input-sm" id="split_value" value="30">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-6 col-sm-6">
							<button class="btn btn-sm btn-success" id="makeBeatBtn">Đánh phách và chia túi bài chấm</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 text-right">
				<div class="btn-group btn-group-sm" role="group" aria-label="...">
					<a type="button" class="btn btn-default printBtn" id="beatform">In biểu phách</a>
					<a type="button" class="btn btn-default printBtn" id="label">In nhãn dán túi bài chấm</a>
					<a type="button" class="btn btn-default printBtn" id="markform">In biểu vào điểm</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="panel panel-primary">
		<div class="panel-heading"><strong>Danh sách thí sinh có bài thi</strong></div>
		<table class="table table-striped table-condensed table-bordered tbAutoScroll">
			<thead>
				<tr>
					<th>STT</div></th>
					<th>Mã học viên</th>
					<th>Số báo danh</th>
					<th>Họ và tên</th>
					<th>Túi bài chấm</th>
					<th>Số phách</th>
				</tr>
			</thead>
			<tbody id="listStudentDetails"></tbody>
		</table>
	</div>
</div>


<script type="text/javascript">
	(function(){
		var list_group_field = <?php echo json_encode($this->group_field); ?>;

		// $('.tbAutoScroll').DataTable({
		// 	ordering: false,
		// 	paging: false,
		// 	info: false,
		// 	searching: false,
		// 	scrollY: $(window).height() - 280,
		// });

		var ajaxUrl = "<?=AppObject::getBaseFile('app/exam_beat/helpers/ajax.php')?>";

		$('#filter_course').on('change', function(){
			var id_course = $(this).val();
			$.post(ajaxUrl, {act: 'getExams', id_course: id_course}, function(response) {
				var data = $.parseJSON(response);
				$('#filter_exam .exam_options').remove();
				$.each(data, function(index, val) {
					 $('#filter_exam').append($('<option>').text(val).attr('value', index).addClass('exam_options'));
				});
			});
		});

		$('.filters').on('change', '.filter', function() {
			var id_exam = $('#filter_exam').val();
			var group_field = $('#filter_group_field').val();
			$.post(ajaxUrl, {act: 'getsubject', examid: id_exam, group_field: group_field}, function(response) {
				var data = $.parseJSON(response);
				// Display list Subject
				$('#filter_subject .subject_options').remove();
				$.each(data, function(index, val) {
					$('#filter_subject').append($('<option>').text(val).attr('value', index).addClass('subject_options'));
				});
			});
		});

		$('#filter_subject').on('change', function() {
			var id_exam = $('#filter_exam').val();
			var id_subject = $(this).val();
			$('#listStudentDetails').html('');
			$.post(ajaxUrl, {act: 'subjectinfo', examid: id_exam, subject: id_subject}, function(response) {
				var data = $.parseJSON(response);
				// Exam type
				$('#examListCounter span').html(data.detail);
				$('#listStudentDetails').html(data.list_student);
			});
		});

		$('#makeBeatBtn').on('click', function(event) {
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			var beat_start = $('#beat_start').val();
			var beat_step = $('#beat_step').val();
			var split_value = $('#split_value').val();
			$.post(ajaxUrl, {act: 'genBeat', id_exam: id_exam, id_subject: id_subject, beat_start: beat_start, beat_step: beat_step, split_value: split_value}, function(response) {
				var data = $.parseJSON(response);
				if (data.html)
					$('#listStudentDetails').html(data.html);
			});
		});

		$('.printBtn').on('click', function() {
			var downloadUrl = "<?=AppObject::getBaseFile('app/exam_beat/helpers/print.php')?>";
			var act = $(this).attr('id');
			var id_exam = $('#filter_exam').val();
			var id_subject = $('#filter_subject').val();
			$.download(downloadUrl, 'act=' + act + '&exam=' + id_exam + '&subject=' + id_subject, 'get');
		});
	})(jQuery);
</script>