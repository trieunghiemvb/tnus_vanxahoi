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
		<form action="" class="form-horizontal" role="form">
			<div class="form-group">
				<label for="" class="col-sm-4 control-label">Lọc theo ngành:</label>
				<div class="col-sm-8">
					<select name="filter_group" id="filter_group" class="form-control input-sm">
						<option value="">-- Tất cả --</option>
						<?php foreach ($this->group_field as $key => $value) { ?>
							<option value="<?=$value?>"><?=$value?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="col-sm-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Quản lý chuyên ngành</h4>
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
							<input type="hidden" name="major_id" id="major_id" value="">
							<input type="text" name="major_name" id="major_name" class="form-control input-sm" placeholder="Tên chuyên ngành">
						</td>
						<td><input type="text" name="major_name_en" id="major_name_en" class="form-control input-sm" placeholder="Tên tiếng Anh"></td>
						<td><input type="text" name="major_code" id="major_code" class="form-control input-sm" placeholder="Mã chuyên ngành"></td>
						<td>
							<select name="group_type" id="group_type" id="" class="form-control input-sm">
								<option value="">-- Ngành học --</option>
								<?php foreach ($this->group_field as $key => $value) { ?>
									<option value="<?=$key?>"><?=$value?></option>
								<?php } ?>
							</select>
						</td>
						<td>
							<select name="tranning_type" id="tranning_type" class="form-control input-sm">
								<option value="">-- Hệ đào tạo --</option>
								<option value="1">Thạc sĩ</option>
								<option value="2">Tiến sĩ</option>
							</select>
						</td>
						<td>
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
						<th>Tên chuyên ngành</th>
						<th>Tên tiếng Anh</th>
						<th>Mã chuyên ngành</th>
						<th>Ngành học</th>
						<th>Hệ đào tạo</th>
						<th>Tác vụ</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($this->items as $item) {
						$i++;
					?>
					<tr id="major_<?=$item->id?>">
						<td></td>
						<td class="major_name"><?=$item->major_name?></td>
						<td class="major_name_en"><?=$item->major_name_en?></td>
						<td class="major_code"><?=$item->major_code?></td>
						<td class="id_group_field"><?=$this->group_field[$item->id_group_field]?></td>
						<td class="tranning_type"><?=($item->tranning_type == 1)?'Thạc sĩ':'Tiến sĩ'?></td>
						<td>
							<input type="hidden" name="control_str" class="control_str" value="<?=$item->id?>;<?=$item->major_name?>;<?=$item->major_name_en?>;<?=$item->major_code?>;<?=$item->id_group_field?>;<?=$item->tranning_type?>">
							<button class="btn btn-xs btn-warning editBtn" data-id="<?=$item->id?>"><i class="glyphicon glyphicon-pencil"></i> Sửa</button>
							<button class="btn btn-xs btn-danger deleteBtn" data-id="<?=$item->id?>"><i class="glyphicon glyphicon-remove"></i> Xóa</button>
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
		var list_group_field = <?php echo json_encode($this->group_field); ?>;

		var oTable = $('#datatable').DataTable({
			ordering: false,
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
				var index = iDisplayIndex +1;
				$('td:eq(0)',nRow).html(index);
			}
		});

		var ajaxUrl = "<?=AppObject::getBaseFile('app/major/helpers/ajax.php')?>";

		$(document).on('click', '.btn', function(){
			$('.message .alert').removeClass('alert-success alert-danger');
			$('.message').hide();
		});

		// Edit
		$('#datatable').on('click', '.editBtn', function() {
			var editData = $(this).parent('td').find('.control_str').val();
			var editData = editData.split(';');
			$('#major_id').val(editData[0]);
			$('#major_name').val(editData[1]);
			$('#major_name_en').val(editData[2]);
			$('#major_code').val(editData[3]);
			$('#group_type').val(editData[4]);
			$('#tranning_type').val(editData[5]);
			$('.addnewBtn').hide();$('.updateBtn').show();$('.cancelBtn').show();
		});

		// Delete
		$('#datatable').on('click', '.deleteBtn', function() {
			if(confirm("Bạn có chắc chắn xóa mục được chọn không? Lưu ý: Mục đã xóa không thể phục hồi!") == false)
				return false;
			else{
				var id = $(this).attr('data-id');
				$.post(ajaxUrl, {act: 'delete', id: id}, function(data) {
					if (data == 'success') {
						oTable.row('#major_' + id).remove().draw(false);
						$('.alert-message').html('Xoá dữ liệu thành công');
						$('.message .alert').addClass('alert-success');
						$('.message').show();
					};
				});
			}
		});

		// Addnew
		$('#action-table').on('click', '.addnewBtn', function() {
			var major_name = $('#major_name').val();
			var major_name_en = $('#major_name_en').val();
			var major_code = $('#major_code').val();
			var id_group_field = $('#group_type').val();
			var tranning_type = $('#tranning_type').val();

			$.post(ajaxUrl, {act: 'addnew', name: major_name, name_en: major_name_en, code: major_code, group_field: id_group_field, tranning_type: tranning_type}, function(data) {
				var data = $.parseJSON(data);
				$('.alert-message').html(data.message);
				$('.message .alert').addClass('alert-' + data.status);
				$('.message').show();
				if(data.status == 'success'){
					var control_str = data.returndata;
				 	var tmp = control_str.split(';');
				 	var tranning_type = (tmp[5] == 1) ? 'Thạc sĩ' : 'Tiến sĩ';
				 	var row = '<tr id="major_' + tmp[0] + '"><td></td><td class="major_name">' + tmp[1] + '</td><td class="major_name_en">' + tmp[2] + '</td><td class="major_code">' + tmp[3] + '</td><td class="id_group_field">' + list_group_field[tmp[4]] + '</td><td class="tranning_type">' + tranning_type + '</td><td><input type="hidden" name="control_str" class="control_str" value="' + control_str + '"><button class="btn btn-xs btn-warning editBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-pencil"></i> Sửa</button> <button class="btn btn-xs btn-danger deleteBtn" data-id="' + tmp[0] + '"><i class="glyphicon glyphicon-remove"></i> Xóa</button></td></tr>';
				 	$('#datatable').dataTable().fnAddTr($(row)[0]);
				 	$('#action-table').find('input, select').val("");
				}
			});
		});

		// Update
		$('#action-table').on('click', '.updateBtn', function() {
			var id = $('#major_id').val();
			var major_name = $('#major_name').val();
			var major_name_en = $('#major_name_en').val();
			var major_code = $('#major_code').val();
			var id_group_field = $('#group_type').val();
			var tranning_type = $('#tranning_type').val();
			$.post(ajaxUrl, {act: 'update', name: major_name, name_en: major_name_en, code: major_code, group_field: id_group_field, tranning_type: tranning_type, id: id}, function(data) {
				var data = $.parseJSON(data);
				$('.alert-message').html(data.message);
				$('.message .alert').addClass('alert-' + data.status);
				$('.message').show();
				if (data.status == 'success') {
					var control_str = data.returndata;
					var tmp = control_str.split(';');
					var tranning_type = (tmp[5] == 1) ? 'Thạc sĩ' : 'Tiến sĩ';
					$('#major_' + tmp[0] + ' .major_name').html(tmp[1]);
					$('#major_' + tmp[0] + ' .major_name_en').html(tmp[2]);
					$('#major_' + tmp[0] + ' .major_code').html(tmp[3]);
					$('#major_' + tmp[0] + ' .id_group_field').html(list_group_field[tmp[4]]);
					$('#major_' + tmp[0] + ' .tranning_type').html(tranning_type);
					$('#major_' + tmp[0] + ' .control_str').val(control_str);
					$('#action-table').find('input, select').val("");
					$('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();
				}
			});
		});

		// Cancel
		$('#action-table').on('click', '.cancelBtn', function() {
			$('#action-table').find('input, select').val("");
			$('.updateBtn').hide();$('.cancelBtn').hide();$('.addnewBtn').show();
		});

		$('#filter_group').on('click', function(){
			filter = $('#filter_group option:selected').val();
			$('#datatable').dataTable().fnFilter(filter);
		})
	})(jQuery);
</script>