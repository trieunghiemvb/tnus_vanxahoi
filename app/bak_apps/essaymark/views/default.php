<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/essaymark/css/app.css')?>">
<div class="col-sm-12">
	<div class="form-inline filters clearfix">
		<div class="col-sm-4">
			<div class="form-group form-group-sm">
				<label for="" class="control-label">Khóa học</label>
				<select name="filter_course" id="filter_course" class="form-control filter">
					<option value="">-- Tất cả --</option>
					<?php foreach ($this->courses as $key => $value) { ?>
					<option value="<?=$key?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group form-group-sm">
				<label for="" class="control-label">Ngành</label>
				<select name="filter_group_field" id="filter_group_field" class="form-control filter">
					<option value="">-- Chọn ngành học --</option>
					<?php foreach ($this->group_field as $key => $value) { ?>
					<option value="<?=$key?>" class="group_field_options"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group form-group-sm">
				<label for="" class="control-label">Lớp</label>
				<select name="filter_class" id="filter_class" class="form-control" disabled="disable">
					<option value="">-- Chọn lớp --</option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading">Danh sách học viên đủ điều kiện bảo vệ</div>
			<form action="" id="myForm">
				<input type="hidden" name="act" value="updateEssay">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th class="text-center">STT</th>
						<th class="text-center">Mã học viên</th>
						<th class="text-center">Họ và tên</th>
						<th class="text-center">Tên luận văn</th>
						<th class="text-center">Ngày bảo vệ</th>
						<th class="text-center">Điểm luận văn</th>
					</tr>
				</thead>
				<tbody id="listStudents"></tbody>
			</table>
			</form>
			<div class="panel-footer">
				<button class="btn btn-sm btn-primary" id="updateBtn">Lưu thông tin</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var ajaxUrl = "<?=AppObject::getBaseFile('app/essaymark/helpers/ajax.php')?>";
</script>
<script src="<?php echo AppObject::getBaseFile('app/essaymark/js/app.js')?>"></script>