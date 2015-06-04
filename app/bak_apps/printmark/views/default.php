<?php
if ( !defined('AREA') ) {
    die('Access denied');

}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/printmark/css/app.css')?>">

<script src="<?php echo AppObject::getBaseFile('app/printmark/js/jquery.download.js')?>"></script>

<div class="col-sm-12">
	<div class="form-horizontal filters">
		<div class="col-sm-4">
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Khóa:</label>
				<div class="col-sm-6">
					<select name="filter_course" id="filter_course" class="form-control filter">
						<option value="">-- Chọn khóa học --</option>
						<?php foreach ($this->courses as $key => $value) { ?>
						<option value="<?=$key?>"><?=$value?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Ngành học:</label>
				<div class="col-sm-6">
					<select name="filter_group_field" id="filter_group_field" class="form-control filter depend" disabled="false">
						<option value="">-- Tất cả --</option>
						<?php foreach ($this->group_field as $key => $value) { ?>
						<option value="<?=$key?>"><?=$value?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Lớp:</label>
				<div class="col-sm-6">
					<select name="filter_class" id="filter_class" class="form-control depend" disabled>
						<option value="">-- Tất cả --</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Chuyên ngành:</label>
				<div class="col-sm-6">
					<select name="filter_major" id="filter_major" class="form-control" disabled>
						<option value="">-- Tất cả --</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Năm học:</label>
				<div class="col-sm-6">
					<select name="filter_year" id="filter_year" class="form-control depend" disabled>
						<option value="">-- Tất cả --</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Học kỳ:</label>
				<div class="col-sm-6">
					<select name="filter_term" id="filter_term" class="form-control depend" disabled>
						<option value="">-- Tất cả --</option>
					</select>
				</div>
			</div>
			<div class="split_line"></div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Người ký:</label>
				<div class="col-sm-6">
					<select name="signer" id="signer" class="form-control depend" disabled>
						<option value="1">PGS.TS. Trịnh Thanh Hải</option>
						<option value="2">PGS.TS. Lê Thị Thanh Nhàn</option>
						<option value="3">PGS.TS. Nông Quốc Chinh</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-5 print_options">
			<div class="form-group form-group-sm">
				<div class="checkbox">
					<label>
						<input type="checkbox" value="1" name="autoDate" id="autoDate" class="depend" checked="" disabled>
						Tự động lấy ngày xuất báo cáo
					</label>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="radio-inline">
					<input type="radio" class="mark_select_type depend" name="mark_select_type" value="First" disabled> In điểm lần 1
				</label>
				<label class="radio-inline">
					<input type="radio" class="mark_select_type depend" name="mark_select_type" value="Max" checked="" disabled> In điểm cao nhất
				</label>
			</div>
			<button class="btn btn-xs btn-primary depend" id="printByTerm" disabled>In bảng điểm theo kỳ học</button>
			<button class="btn btn-xs btn-success depend" id="printByYear" disabled>In bảng điểm theo năm học</button>
			<button class="btn btn-xs btn-danger depend" id="printByCourse" disabled>In bảng điểm toàn khóa</button>
			<div class="split_line"></div>
			<div class="form-group form-group-sm">
				<label for="" class="control-label col-sm-4">Bảng điểm cá nhân:</label>
				<div class="col-sm-6">
					<select name="signer" id="signer" class="form-control depend" disabled>
						<option value="">Tiếng Việt</option>
						<option value="">Tiếng Anh</option>
					</select>
				</div>
			</div>
			<button class="btn btn-xs btn-primary depend" id="printPersonal" disabled>In những sinh viên được chọn</button>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div class="col-sm-12">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<strong>DANH SÁCH HỌC VIÊN THEO LỚP</strong>
		</div>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th class="text-center">STT</th>
					<th class="text-center">Mã học viên</th>
					<th colspan="2" class="text-center">Họ và tên</th>
					<th class="text-center">Ngày sinh</th>
					<th class="text-center">Chuyên ngành</th>
					<th class="text-center">
						<div class="checkbox">
							<label>
								<input class="print_student" type="checkbox" value="11" name="" id="checkAllBtn">
							</label>
						</div>
					</th>
				</tr>
			</thead>
			<tbody id="listStudent"></tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	var ajaxUrl = "<?=AppObject::getBaseFile('app/printmark/helpers/ajax.php')?>";
	var printUrl = "<?=AppObject::getBaseFile('app/printmark/helpers/print.php')?>";
</script>
<script src="<?php echo AppObject::getBaseFile('app/printmark/js/app.js')?>"></script>