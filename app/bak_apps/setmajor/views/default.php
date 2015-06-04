<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/setmajor/css/dataTables.bootstrap.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/setmajor/js/plugins/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/setmajor/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<!-- FILTER -->
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

<!-- SETMAJOR PANEL -->
<div class="col-sm-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Phân chuyên ngành cho học viên</h4>
		</div>
		<div class="panel-body">
			<div class="message" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Chọn chuyên ngành</label>
					<div class="col-sm-4">
						<select name="" id="select_major" class="input-sm form-control" disabled>
							<option value="">-- Chọn chuyên ngành --</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<table class="table table-striped tbAutoScroll list-profile">
			<thead>
				<tr>
					<th>
						<div class="checkbox">
							<label for="">
								<input type="checkbox" id="checkAllBtn" aria-label="Check All">
							</label>
						</div>
					</th>
					<th>#</th>
					<th>HỌ VÀ TÊN</th>
					<th>MÃ HỌC VIÊN</th>
					<th>NGÀY SINH</th>
					<th>GIỚI TÍNH</th>
					<th>CHUYÊN NGÀNH</th>
				</tr>
			</thead>
			<tbody>
				<!-- LIST STUDENT HERE -->
			</tbody>
		</table>
		<div class="panel-footer">
			<button class="btn btn-xs btn-primary" id="setMajorBtn"><i class="glyphicon glyphicon-ok"></i> Phân chuyên ngành</button>
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
		multipleSelection: true,
		scrollY: $(window).height() - 300,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}

	});

	var ajaxUrl = "<?=AppObject::getBaseFile('app/setmajor/helpers/ajax.php')?>";

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
		getStudents(id_class);
	});

	var getStudents = function(id_class){
		$.post(ajaxUrl, {act: 'getstudent', id_class: id_class}, function(data) {
			var data = $.parseJSON(data);
			// DISPLAY LIST MAJOR
			$('.major_options').remove();
			$.each(data.majors, function(index, val) {
				$('#select_major').append($('<option>').text(val).attr('value', index).addClass('major_options'));
			});
			$('#select_major').prop('disabled', false);

			// DISPLAY LIST STUDENTS
			$('.list-profile tbody').html('');
			var i = 0;
			$.each(data.students, function(index, val){
				i++;
				var row = '<tr id="profile_'+ val.id_profile +'"><td><div class="checkbox '+ (val.id_major != 0 ? 'disabled' : '') +'"><label><input type="checkbox" id="checkbox_'+ val.id_profile +'" value="'+ (val.id_major > 0 ? 0 : val.id_profile) +'" '+ (val.id_major > 0 ? 'disabled' : '') +'></label></div></td><td><span class="indexSTT">'+ i +'</span></td><td>'+ val.last_name +' '+ val.first_name +'</td><td>'+ val.student_code +'</td><td>'+ val.birthday +'</td><td>'+ (val.sex == 1 ? 'Nam' : 'Nữ') +'</td><td>'+ (val.id_major > 0 ? data.majors[val.id_major] : '') +'</td></tr>';
				$('.list-profile tbody').append(row);
			})
		});
	}

	$(document)
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('change', '#checkAllBtn', function(){
		// Checkall Button Change
		$('.list-profile tbody input:checkbox:not(:disabled)').prop('checked', this.checked);
	})
	.on('change', '.list-profile tbody input:checkbox', function(){
		// Count Checkboxes
		var totalCheckboxes = $('.list-profile tbody input:checkbox').length;
		// Count Checkboxes checked
		var totalChecked = $('.list-profile tbody input:checkbox:checked').length;
		if(totalCheckboxes == totalChecked)
			$('#checkAllBtn').prop('checked', true);
		else
			$('#checkAllBtn').prop('checked', false);
	})
	.on('click', '#setMajorBtn', function(){
		var id_major = $('#select_major').val();
		var allVals = [];
		$('.list-profile tbody input:checkbox:checked').each(function(){
			allVals.push($(this).val());
		});
		$.post(ajaxUrl, {act: 'setmajor', id_major: id_major, profiles: allVals}, function(data) {
			var data = $.parseJSON(data);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message .alert-message').html(data.message);
			$('.message').show();
			if(data.status == 'success'){
				var id_class = $('#filter_class').val();
				getStudents(id_class);
			}
		});
	});
})(jQuery);
</script>