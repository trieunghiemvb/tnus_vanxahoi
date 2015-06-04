<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/searchprofile/css/app.css')?>">

<div class="col-sm-3">
	<div class="well">
		<div class="form" role="form" id="filter">
			<form action="" method="post" class="form">
				<div class="input-group">
	                <input type="text" class="form-control input-sm" placeholder="Tìm theo tên, mã học viên" name="search_kw">
	                <div class="input-group-btn">
	                    <button class="btn btn-default btn-sm" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	                </div>
	            </div>
			</form>
			<hr>
			<div class="form-group form-group-sm">
				<label for="" class="control-label">Khóa:</label>
				<select name="filter_course" id="filter_course" class="form-control input-sm filter">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->courses as $key => $value) { ?>
					<option value="<?=$key?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
			<div id="treeview">
				<div class="tree" id="listClass">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-sm-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Tra cứu hồ sơ học viên</h4>
		</div>
		<div class="panel-body">
			<table class="table table-striped" id="datatable">
				<thead>
					<tr>
						<th>#</th>
						<th>Mã học viên</th>
						<th>Họ và tên</th>
						<th>Ngày sinh</th>
						<th>Giới tính</th>
						<th>Tình trạng</th>
						<th>Chi tiết</th>
					</tr>
				</thead>
				<tbody id="listStudent">
				<?php if ($this->students): ?>
				<?php $i = 1; ?>
				<?php foreach ($this->students as $item): ?>
					<tr>
						<td><?=$i?></td>
						<td><?=$item->student_code?></td>
						<td><?=$item->last_name?> <?=$item->first_name?></td>
						<td><?=$item->birthday?></td>
						<td><?=($item->sex == 1 ? 'Nam' : 'Nữ')?></td>
						<td><?=$item->status?></td>
						<td><button class="btn btn-xs btn-primary viewDetail" id="<?=$item->id?>"><i class="glyphicon glyphicon-eye-open"></i> Xem</button></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach ?>
				<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script type="text/javascript">
	var ajaxUrl = "<?=AppObject::getBaseFile('app/searchprofile/helpers/ajax.php')?>";
</script>
<script src="<?php echo AppObject::getBaseFile('app/searchprofile/js/app.js')?>"></script>

<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Thông tin học viên</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->