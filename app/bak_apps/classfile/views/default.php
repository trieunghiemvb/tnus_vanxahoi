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
<script src="<?php echo AppObject::getBaseFile('app/classfile/js/jquery.download.js')?>"></script>
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

<!-- LIST PROFILES -->
<div class="col-sm-3">
	<div class="list-profile">
		<div class="panel panel-default">
			<div class="panel-heading">
				<button class="btn btn-xs btn-primary newProfile"><i class="glyphicon glyphicon-plus-sign"></i> Thêm mới</button>
			</div>
			<input type="hidden" id="id_class" value="">
			<table class="table table-striped tbAutoScroll">
				<thead>
					<tr>
						<th>STT</th>
						<th>HỌ ĐỆM</th>
						<th>TÊN</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="panel-footer">
				<button class="btn btn-xs btn-primary printProfile"><i class="glyphicon glyphicon-print"></i> In danh sách học viên</button>
			</div>
		</div>
	</div>
</div>

<!-- PROFILE DETAILS -->
<div class="col-sm-7">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4>Học viên: <span id="studen_name"></span></h4>
		</div>
		<div class="panel-body" id="profile_info">
			<div class="message" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
			<div class="form form-horizontal" role="form">
				<input type="hidden" id="id_profile" value="">
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Họ đệm: <span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" name="last_name" id="last_name" class="form-control input-sm" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Tên: <span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" name="first_name" id="first_name" class="form-control input-sm" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Giới tính: <span class="text-danger">*</span></label>
					<div class="col-sm-4">
						<select name="sex" id="sex" class="form-control input-sm">
							<option value="">-- Lựa chọn --</option>
							<option value="1">Nam</option>
							<option value="0">Nữ</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Mã học viên:</label>
					<div class="col-sm-4">
						<input type="text" name="student_code" id="student_code" class="form-control input-sm" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Ngày sinh: <span class="text-danger">*</span></label>
					<div class="col-sm-4">
						<div class="input-group input-append date datepicker">
							<input type="date" name="birthday" id="birthday" class="form-control input-sm" value="" placeholder="DD/MM/YYYY">
							<span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Nơi sinh: <span class="text-danger">*</span></label>
					<div class="col-sm-4">
						<select name="birth_place" id="birth_place" class="form-control input-sm">
							<option value="">-- Lựa chọn tỉnh --</option>
							<?php foreach ($this->provices as $key => $value): ?>
							<option value="<?=$key?>"><?=$value?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Điện thoại:</label>
					<div class="col-sm-6">
						<input type="text" name="phone" id="phone" class="form-control input-sm">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">eMail:</label>
					<div class="col-sm-6">
						<input type="text" name="email" id="email" class="form-control input-sm">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">CMND:</label>
					<div class="col-sm-6">
						<input type="text" name="id_card" id="id_card" class="form-control input-sm">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 form-label">Chuyên ngành:</label>
					<div class="col-sm-6">
						<select name="" id="id_major" class="input-sm form-control" disabled>
							<option value="0">-- Chọn chuyên ngành của học viên --</option>
						</select>
					</div>
					<div class="col-sm-4" id="changeMajorBtn" style="display:none;">
						<button class="btn btn-xs btn-info"><i class="glyphicon glyphicon-pencil"></i> Thay đổi chuyên ngành</button>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-9">
						<button type="submit" class="btn btn-success addnewBtn" style="display: none;"><i class="glyphicon glyphicon-upload"></i> Thêm mới</button>
						<button type="submit" class="btn btn-primary updateBtn" style="display: none;"><i class="glyphicon glyphicon-edit"></i> Cập nhật</button>
						<button type="submit" class="btn btn-danger cancelBtn" style="display: none;"><i class="glyphicon glyphicon-remove"></i> Hủy</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Ajax process - @by Duyld2108 -->
<script type="text/javascript">
(function(){
	$('.datepicker input').mask("00/00/0000", {placeholder: "__/__/____"});
	$('.tbAutoScroll').DataTable({
		ordering: false,
		paging: false,
		info: false,
		searching: false,
		scrollY: $(window).height() - 200,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}

	});

	var ajaxUrl = "<?=AppObject::getBaseFile('app/classfile/helpers/ajax.php')?>";

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
		$.post(ajaxUrl, {act: 'filterbyclass', id_class: id_class}, function(data) {
			var data = $.parseJSON(data);
			// Display list Major
			$('#profile_info #id_major .major_options').remove();
			$.each(data.majors, function(index, val) {
				$('#profile_info #id_major').append($('<option>').text(val).attr('value', index).addClass('major_options'));
			});
			// Display list Students
			$('.list-profile tbody').html('');
			var i = 0;
			$.each(data.students, function(index, val){
				i++;
				//Control String
				//ProfileID;First;Last;Sex;Birthday;BirthPlace;Email;Phone;IDCard;Code;Id_Major
				var control_str = val.id_profile +';'+ val.first_name +';'+ val.last_name +';'+ val.sex +';'+ val.birthday +';'+ val.birth_place +';'+ val.email +';'+ val.phone +';'+ val.id_card +';'+ val.student_code +';'+ val.id_major;
				var row = '<tr id="profile_'+ val.id_profile +'"><td><span class="indexSTT">'+ i +'</span><span style="display:none;" class="control_str">'+ control_str +'</span></td><td>'+ val.last_name +'</td><td>'+ val.first_name +'</td></tr>';
				$('.list-profile tbody').append(row);
			})
		});
	});



	$('.list-profile')
	.on('click', '.newProfile', function(){
		var id_class = $('#id_class').val();
		if (id_class != 0) {
			$('#profile_info').find('input, select').val("");
			$('#profile_info #id_major').prop('disabled', false);
			// Show buttons
			$('#profile_info #changeMajorBtn').hide();
			$('.addnewBtn').show();$('.updateBtn').hide();$('.cancelBtn').hide();
		}
		else{
			alert('Chưa chọn lớp! Phải lựa chọn lớp trước khi thêm mới hồ sơ.');
		}
	})
	.on('click', 'tr', function() {
		// Display info to edit
		var control_str = $(this).find('.control_str').html();
		control_str = control_str.replace(/null/g, '');
		data = control_str.split(';');
		$('#profile_info #id_profile').val(data[0]);
		$('#profile_info #first_name').val(data[1]);
		$('#profile_info #last_name').val(data[2]);
		$('#studen_name').html(data[2] + ' ' + data[1]);
		$('#profile_info #sex').val(data[3]);
		$('#profile_info #birthday').val(data[4]);
		$('#profile_info #birth_place').val(data[5]);
		$('#profile_info #email').val(data[6]);
		$('#profile_info #phone').val(data[7]);
		$('#profile_info #id_card').val(data[8]);
		$('#profile_info #student_code').val(data[9]);
		$('#profile_info #id_major').val(data[10]).prop('disabled', true);
		$('#profile_info #changeMajorBtn').show();
		// Show buttons
		$('.addnewBtn').hide();$('.updateBtn').show();$('.cancelBtn').show();
	})
	.on('click', '.printProfile', function() {
		var id_class = $('#id_class').val();
		var downloadUrl = "<?=AppObject::getBaseFile('app/classfile/helpers/studentDownload.php')?>";
		if($('.tbAutoScroll tbody tr').length >= 1)
			$.download(downloadUrl, 'class=' + id_class, 'get');
		else
			alert('Danh sách trống. Lớp chưa có học viên hoặc chưa chọn lớp!');
	});

	$(document)
	.on('click', '#changeMajorBtn', function() {
		$('#profile_info #id_major').val(data[10]).prop('disabled', false);
	})
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('click', '.cancelBtn', function(){
		$('#profile_info').find('input, select').val("");
		$('#profile_info #id_major').prop('disabled', true);
		// Show buttons
		$('#profile_info #changeMajorBtn').hide();
		$('.addnewBtn').hide();$('.updateBtn').hide();$('.cancelBtn').hide();
	})
	.on('click', '.updateBtn', function(){
		var id_profile = $('#profile_info #id_profile').val();
		var first_name = $('#profile_info #first_name').val();
		var last_name = $('#profile_info #last_name').val();
		var sex = $('#profile_info #sex').val();
		var student_code = $('#profile_info #student_code').val();
		var birthday = $('#profile_info #birthday').val();
		var birth_place = $('#profile_info #birth_place').val();
		var email = $('#profile_info #email').val();
		var phone = $('#profile_info #phone').val();
		var id_card = $('#profile_info #id_card').val();
		var id_major = $('#profile_info #id_major').val();

		$.post(ajaxUrl, {act: 'update', id_profile: id_profile, first_name: first_name, last_name: last_name, sex: sex, student_code: student_code, birthday: birthday, birth_place: birth_place, email: email, phone: phone, id_card: id_card, id_major: id_major}, function(data){
			data = $.parseJSON(data);data
			var control_str = data.returndata;
			$('.message .alert-message').html(data.message);
			$('.message .alert').addClass('alert-' + data.status);
			$('.message').show();
			if(data.status == 'sucess'){
				tmp = control_str.split(';');
				$('tr#profile_' + tmp[0] + ' .control_str').html(control_str);
				$('tr#profile_' + tmp[0] + ' td:nth-child(2)').html(tmp[2]);
				$('tr#profile_' + tmp[0] + ' td:nth-child(3)').html(tmp[1]);
			}
		})
	})
	.on('click', '.addnewBtn', function(){
		var id_class = $('#id_class').val();
		var first_name = $('#profile_info #first_name').val();
		var last_name = $('#profile_info #last_name').val();
		var sex = $('#profile_info #sex').val();
		var student_code = $('#profile_info #student_code').val();
		var birthday = $('#profile_info #birthday').val();
		var birth_place = $('#profile_info #birth_place').val();
		var email = $('#profile_info #email').val();
		var phone = $('#profile_info #phone').val();
		var id_card = $('#profile_info #id_card').val();

		if(id_class !== ''){
			$.post(ajaxUrl, {act: 'addnew', id_class: id_class, first_name: first_name, last_name: last_name, sex: sex, student_code: student_code, birthday: birthday, birth_place: birth_place, email: email, phone: phone, id_card: id_card}, function(data){
				data = $.parseJSON(data);
				var control_str = data.returndata;
				$('.message .alert-message').html(data.message);
				$('.message .alert').addClass('alert-' + data.status);
				$('.message').show();
				if(data.status == 'success'){
					var id_class = $('#id_class').val();
					$.post(ajaxUrl, {act: 'getstudent', id_class: id_class}, function(data) {
						var data = $.parseJSON(data);
						$('.list-profile tbody').html('');
						var i = 0;
						$.each(data, function(index, val){
							i++;
							//Control String
							//ProfileID;First;Last;Sex;Birthday;BirthPlace;Email;Phone;IDCard;Code;
							var control_str = val.id_profile +';'+ val.first_name +';'+ val.last_name +';'+ val.sex +';'+ val.birthday +';'+ val.birth_place +';'+ val.email +';'+ val.phone +';'+ val.id_card +';'+ val.student_code;
							var row = '<tr id="profile_'+ val.id_profile +'"><td><span class="indexSTT">'+ i +'</span><span style="display:none;" class="control_str">'+ control_str +'</span></td><td>'+ val.last_name +'</td><td>'+ val.first_name +'</td></tr>';
							$('.list-profile tbody').append(row);
						})
					});
					$('#profile_info').find('input, select').val("");
				}
			})
		} else {
			$('.message .alert-message').html('Đã có lỗi: Lựa chọn lớp trước!');
			$('.message .alert').addClass('alert-danger');
			$('.message').show();
		}
	});
})(jQuery);
</script>
