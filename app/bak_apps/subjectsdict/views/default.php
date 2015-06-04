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
<div class="col-sm-2">
	<div class="well">
		<form action="" class="form" role="form">
			<div class="form-group">
				<label for="" class="control-label">Lọc theo ngành:</label>
				<select name="filter_group" id="filter_group" class="form-control input-sm">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->group_field as $key => $value) { ?>
						<option value="<?=$value?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
		</form>
	</div>
</div>

<div class="col-sm-10">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Từ điển môn học</h4>
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
							<input type="hidden" name="subject_id" id="subject_id" value="">
							<input type="text" name="subject_code" id="subject_code" class="form-control input-sm" placeholder="Mã môn học">
						</td>
						<td><input type="text" name="subject_name" id="subject_name" class="form-control input-sm" placeholder="Tên môn học"></td>
						<td><input type="text" name="subject_name_en" id="subject_name_en" class="form-control input-sm" placeholder="Tên tiếng Anh"></td>
						<td>
							<select name="group_field" id="group_field" id="" class="form-control input-sm">
								<option value="1">-- Môn chung --</option>
								<?php foreach ($this->group_field as $key => $value) { ?>
									<option value="<?=$key?>"><?=$value?></option>
								<?php } ?>
							</select>
						</td>
						<td>
							<select name="major" id="major" id="" class="form-control input-sm" disabled>
								<option value="">-- Chuyên ngành --</option>
							</select>
						</td>
						<td>
							<select name="knowledge_block" id="knowledge_block" id="" class="form-control input-sm">
								<option value="">-- Khối kiến thức --</option>
								<?php foreach ($this->knowledge_blocks as $key => $value) { ?>
									<option value="<?=$key?>"><?=$value?></option>
								<?php } ?>
							</select>
						</td>
						<td class="col-sm-1"><input type="number" name="subject_curriculum" id="subject_curriculum" class="form-control input-sm" placeholder="Số TC"></td>
					</tr>
					</tr>
						<td colspan="6" class="text-center">
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
						<th>Mã môn học</th>
						<th>Tên môn học</th>
						<th>Tên tiếng Anh</th>
						<th>Ngành học</th>
						<th>Chuyên ngành</th>
						<th>Khối kiến thức</th>
						<th>Số TC</th>
						<th>Tác vụ</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->subjects as $item): ?>
					<tr id="subject_<?=$item->id?>">
						<td></td>
						<td class="s_code"><?=$item->code?></td>
						<td class="s_name"><?=$item->name?></td>
						<td class="s_name_en"><?=$item->name_en?></td>
						<td class="s_group_field"><?=$this->group_field[$item->id_group_field]?></td>
						<td class="s_major"><?=$this->majors[$item->id_major]?></td>
						<td class="s_knowledge_block"><?=$this->knowledge_blocks[$item->id_knowledge_block]?></td>
						<td class="s_curriculum"><?=$item->curriculum?></td>
						<td>
							<input type="hidden" class="control_str" value="<?=$item->id?>;<?=$item->code?>;<?=$item->name?>;<?=$item->name_en?>;<?=$item->id_group_field?>;<?=$item->id_major?>;<?=$item->id_knowledge_block?>;<?=$item->curriculum?>">
							<button class="btn btn-xs btn-warning editBtn" data-id="<?=$item->id?>"><i class="glyphicon glyphicon-pencil"></i> Sửa</button>
							<button class="btn btn-xs btn-danger deleteBtn" data-id="<?=$item->id?>"><i class="glyphicon glyphicon-remove"></i> Xóa</button>
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
(function(){
	var group_fields = <?php echo json_encode($this->group_field); ?>;
	var majors = <?php echo json_encode($this->majors); ?>;
	var knowledge_blocks = <?php echo json_encode($this->knowledge_blocks); ?>;

	var oTable = $('#datatable').DataTable({
		ordering: false,
		info: false,
		lengthChange: false,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}
	});

	var ajaxUrl = "<?=AppObject::getBaseFile('app/subjectsdict/helpers/ajax.php')?>";

	$(document)
	.on('click', '.btn', function(){
		$('.message .alert').removeClass('alert-success alert-danger');
		$('.message').hide();
	})
	.on('change', '#group_field', function(){
		// Get major by group_field
		getMajorByGroupField();
	})
	.on('click', '.editBtn', function() {
		var control_str = $(this).parent().find('.control_str').val();
		var tmp = control_str.split(';');
		$('#action-table #subject_id').val(tmp[0]);
		$('#action-table #subject_code').val(tmp[1]);
		$('#action-table #subject_name').val(tmp[2]);
		$('#action-table #subject_name_en').val(tmp[3]);
		$('#action-table #group_field').val(tmp[4]);
		getMajorByGroupField();
		$('#action-table #major').val(tmp[5]);
		$('#action-table #knowledge_block').val(tmp[6]);
		$('#action-table #subject_curriculum').val(tmp[7]);
		$('.addnewBtn').hide(); $('.updateBtn').show(); $('.cancelBtn').show();
	})
	.on('click', '.cancelBtn', function() {
		$('#action-table input').val('');
		$('.addnewBtn').show(); $('.updateBtn').hide(); $('.cancelBtn').hide();
	})
	.on('click', '.addnewBtn', function() {
		var code = $('#subject_code').val();
		var name = $('#subject_name').val();
		var name_en = $('#subject_name_en').val();
		var group_field = $('#group_field').val();
		var major = $('#major').val();
		var knowledge_block = $('#subject_type').val();
		var curriculum = $('#subject_curriculum').val();
		$.post(ajaxUrl, {act: 'addnew', code: code, name: name, name_en: name_en, group_field: group_field, major: major, knowledge_block: knowledge_block, curriculum: curriculum}, function(response) {
			var data = $.parseJSON(response);
			$('.alert').addClass('alert-' + data.status);
			$('.alert-message').html(data.message);
			$('.message').show();
			if (data.status == 'success') {
				var control_str = data.returndata;
				var val = control_str.split(';');
				var row = '<tr class="subject_'+ val[0] +'">' +
							'<td></td>' +
							'<td>'+ val[1] +'</td>' +
							'<td>'+ val[2] +'</td>' +
							'<td>'+ val[3] +'</td>' +
							'<td>'+ group_fields[val[4]] +'</td>' +
							'<td>'+ majors[val[5]] +'</td>' +
							'<td>'+ knowledge_blocks[val[6]] +'</td>' +
							'<td>'+ val[7] +'</td>' +
							'<td>' +
								'<input type="hidden" class="control_str" value="'+ control_str +'">' +
								'<button class="btn btn-xs btn-warning editBtn" data-id="'+ val[0] +'"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> ' +
								'<button class="btn btn-xs btn-danger deleteBtn" data-id="'+ val[0] +'"><i class="glyphicon glyphicon-remove"></i> Xóa</button>' +
							'</td>' +
						'</tr>';
				$('#datatable').dataTable().fnAddTr($(row)[0]);
				$('#action-table').find('input:text').val('');
			}
		});
	})
	.on('click', '.updateBtn', function() {
		var id = $('#subject_id').val();
		var code = $('#subject_code').val();
		var name = $('#subject_name').val();
		var name_en = $('#subject_name_en').val();
		var group_field = $('#group_field').val();
		var major = $('#major').val();
		var knowledge_block = $('#knowledge_block').val();
		var curriculum = $('#subject_curriculum').val();
		$.post(ajaxUrl, {act: 'update', code: code, name: name, name_en: name_en, group_field: group_field, major: major, knowledge_block: knowledge_block, curriculum: curriculum, id: id}, function(response) {
			var data = $.parseJSON(response);
			$('.alert').addClass('alert-' + data.status);
			$('.alert-message').html(data.message);
			$('.message').show();
			if (data.status == 'success') {
				var control_str = data.returndata;
				var val = control_str.split(';');
				var $this = $('#subject_' + val[0]);
				$this.find('.control_str').val(control_str);
				$this.find('.s_code').html(val[1]);
				$this.find('.s_name').html(val[2]);
				$this.find('.s_name_en').html(val[3]);
				$this.find('.s_group_field').html(group_fields[val[4]]);
				$this.find('.s_major').html(majors[val[5]]);
				$this.find('.s_knowledge_block').html(knowledge_blocks[val[6]]);
				$this.find('.s_curriculum').html(val[7]);
				$('#action-table').find('input').val('');
				$('.addnewBtn').show(); $('.updateBtn').hide(); $('.cancelBtn').hide();
			}
		});
	})
	.on('click', '.deleteBtn', function(event) {
		if(confirm("Bạn có chắc chắn xóa mục được chọn không? Lưu ý: Mục đã xóa không thể phục hồi!") == false)
			return false;
		else{
			var id = $(this).attr('data-id');
			$.post(ajaxUrl, {act: 'delete', id: id}, function(data) {
				if (data == 'success') {
					oTable.row('#subject_' + id).remove().draw(false);
					$('.alert-message').html('Xoá dữ liệu thành công');
					$('.message .alert').addClass('alert-success');
					$('.message').show();
				};
			});
		}
	})
	.on('click', '#filter_group', function(){
		filter = $('#filter_group option:selected').val();
		$('#datatable').dataTable().fnFilter(filter);
	});

	var getMajorByGroupField = function(){
		var id_group_field = $('#group_field').val();
		$('.major_options').remove();
		$.post(ajaxUrl, {act: 'get_major', id_group_field: id_group_field}, function(response){
			var data = $.parseJSON(response);
			if(data.length !== 0){
				$.each(data, function(index, val){
					$('#major').append($('<option>').text(val).attr('value', index).addClass('major_options'));
				});
				$('#major').prop('disabled', false);
			} else {
				$('#major').prop('disabled', true);
			}
		})
	}
})(jQuery);
</script>