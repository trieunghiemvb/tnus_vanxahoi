<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/council/css/app.css')?>">
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
					<option value="">-- Chọn ngành học --</option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-sm-4">
		<div class="panel panel-primary">
			<div class="panel-heading">Danh sách học viên đủ điều kiện bảo vệ</div>
			<table class="table table-striped table-condensed">
				<tbody id="listStudents"></tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="panel panel-danger">
			<div class="panel-heading">Chi tiết Luận văn và Hội đồng</div>
			<div class="panel-body">
				<div class="message"></div>
				<button class="btn btn-sm btn-warning" id="editBtn">Sửa thông tin</button>
				<div class="form form-horizontal" id="essayDetails">
					<div class="form-group">
						<label class="control-label col-sm-3">Tên luận văn:</label>
						<div class="col-sm-9">
							<textarea name="essay_name" id="essay_name" class="form-control" rows="2" disabled="disabled"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Tên luận văn (English):</label>
						<div class="col-sm-9">
							<textarea name="essay_name_en" id="essay_name_en" class="form-control" rows="2" disabled="disabled"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Chủ tịch:</label>
						<div class="col-sm-9">
							<input type="text" name="chairman" id="chairman" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Thư ký:</label>
						<div class="col-sm-9">
							<input type="text" name="secretary" id="secretary" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Phản biện 1:</label>
						<div class="col-sm-9">
							<input type="text" name="critic_1" id="critic_1" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Phản biện 2:</label>
						<div class="col-sm-9">
							<input type="text" name="critic_2" id="critic_2" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Ủy viên:</label>
						<div class="col-sm-9">
							<input type="text" name="member" id="member" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="col-sm-9 col-sm-offset-3">
						<button class="btn btn-sm btn-primary" id="updateBtn">Lưu</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var ajaxUrl = "<?=AppObject::getBaseFile('app/council/helpers/ajax.php')?>";
</script>
<script src="<?php echo AppObject::getBaseFile('app/council/js/app.js')?>"></script>