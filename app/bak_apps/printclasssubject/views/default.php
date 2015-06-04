<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/printclasssubject/css/app.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/printclasssubject/js/jquery.download.js')?>"></script>
<div class="col-sm-12">
	<div class="form-inline filters">
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Khóa học</label>
			<select name="filter_course" id="filter_course" class="form-control filter">
				<option value="">-- Tất cả --</option>
				<?php foreach ($this->courses as $key => $value) { ?>
				<option value="<?=$key?>"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Ngành</label>
			<select name="filter_group_field" id="filter_group_field" class="form-control filter">
				<option value="">-- Chọn ngành học --</option>
				<?php foreach ($this->group_field as $key => $value) { ?>
				<option value="<?=$key?>" class="group_field_options"><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Năm học</label>
			<select name="filter_year" id="filter_year" class="form-control" disabled="disabled">
				<option value="">-- Lựa chọn --</option>
			</select>
		</div>
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Kỳ học</label>
			<select name="filter_term" id="filter_term" class="form-control" disabled="disabled">
				<option value="">-- Lựa chọn --</option>
			</select>
		</div>
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Môn học</label>
			<select name="filter_subject" id="filter_subject" class="form-control" disabled="disabled">
				<option value="">-- Lựa chọn --</option>
			</select>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="form-inline filters">
		<div class="form-group form-group-sm">
			<label for="" class="control-label">Chọn mẫu báo cáo:</label>
			<select name="print_template" id="print_template" class="form-control filter">
				<option value="ds_ghidiem">Mẫu 01 - Danh sách ghi điểm</option>
				<option value="ds_diemdanh">Mẫu 02 - Danh sách điểm danh</option>
			</select>
		</div>
		<button id="printBtn">Xuất Excel</button>
	</div>
</div>
<div class="col-sm-12">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<strong>DANH SÁCH LỚP HỌC PHẦN</strong>
		</div>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th class="text-center" style="width:60px;">STT</th>
					<th class="text-center">Tên lớp</th>
					<th class="text-center" style="width:150px;">Số học viên</th>
					<th class="text-center" style="width:200px;">
						<div class="checkbox">
							<label>
								<input class="print_student" type="checkbox" value="11" name="" id="checkAllBtn"> Chọn tất cả
							</label>
						</div>
					</th>
				</tr>
			</thead>
			<tbody id="listClass"></tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	var ajaxUrl = "<?=AppObject::getBaseFile('app/printclasssubject/helpers/ajax.php')?>";
	var printUrl = "<?=AppObject::getBaseFile('app/printclasssubject/helpers/print.php')?>";
</script>
<script src="<?php echo AppObject::getBaseFile('app/printclasssubject/js/app.js')?>"></script>