<?php 
if ( !defined('AREA') ) {
    die('Access denied');
}
set_time_limit(36000);
ini_set("memory_limit","1220M");
function makeDate($str){
	$str_array1 = explode(" ", $str);
	$str_array2 = explode("-", $str_array1[0]);
	$str_array3 = explode(":", $str_array1[1]);
	$str_return = $str_array2[2].'/'.$str_array2[1].'/'.$str_array2[0].' ';
	//$str_return .= $str_array3[0].':'.$str_array2[1];
	return $str_return;	
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/markcomponent/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/markcomponent/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/markcomponent/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/markcomponent/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/markcomponent/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/markcomponent/js/scripts.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/markcomponent/js/jquery.download.js')?>"></script>
<style>
.message2{

}
</style>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Chọn lớp </h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="form_filter" >
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Khóa học:</label>
                    <div class="col-sm-8">
                        <select name="filter_course" id="filter_course" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->course as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Năm học:</label>
                    <div class="col-sm-8">
                        <select name="filter_year" id="filter_year" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Kỳ học:</label>
                    <div class="col-sm-8">
                        <select name="filter_term" id="filter_term" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
						</select>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Nhóm ngành:</label>
                    <div class="col-sm-8">
                        <select name="filter_group_field" id="filter_group_field" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>   
							<?php foreach ($this->group_field as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>							
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Môn:</label>
                    <div class="col-sm-8">
                        <select name="filter_subject" id="filter_subject" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Lớp:</label>
                    <div class="col-sm-8">
                        <select name="filter_class" id="filter_class" class="form-control input-sm select_filter">
                            <option value="0">-- Tất cả --</option>                            
                        </select>
                    </div>
                </div>
				
            </form>
			<div class="form-group">
				<div class="col-sm-4"></div>
				<div class="col-sm-8">
				   <button class="btn btn-md btn-info btnSelect"><i class="glyphicon glyphicon-arrow-right"></i> Chọn lớp học phần </button>
				</div>
			</div>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách người học</h4>
        </div>
        <div class="panel-body">            
            <table class="table table-striped tbAutoScroll" id="datatables">
                <thead>
                    <tr>
                        
                        <th>#</th>
                        <th>Mã học viên</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Điểm chuyên cần</th>
                        <th>Điểm kiểm tra</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
		<div class="well">
			<div class="message2" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close bnt" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
			<table id="action-table" class="table">				
				<tr align="center">
					<td colspan="5">
						<button class="btn btn-md btn-info btnPrint"><i class="glyphicon glyphicon-print"></i> In bảng điểm </button>
						<button class="btn btn-md btn-primary btnAddNew"><i class="glyphicon glyphicon-download-alt"></i> Lưu bảng điểm</button>
						<button class="btn btn-md btn-warning btnReset"><i class="glyphicon glyphicon-refresh"></i> Reset </button>
						
					</td>
				</tr>
			</table>
		</div>
    </div>
</div>

<!-- Ajax xu ly: Edit by ANVT-->
<script type="text/javascript">
	$('.tbAutoScroll').DataTable({
		ordering: false,
		paging: false,
		info: false,
		searching: false,
		scrollY: $(window).height() - 350,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}

	});
	
    (function(){
		$('.btnPrint').prop('disabled', true);
		$('.btnAddNew').prop('disabled', true);
		$('.btnReset').prop('disabled', true);
       
        var ajaxUrl = "<?=AppObject::getBaseFile('app/markcomponent/helpers/ajax.php')?>";
		// xóa thông báo
        $(document).on('click', '.btn', function(){
            $('.message2 .alert').removeClass('alert-success alert-danger');
            $('.message2').hide();
			$('.message2').slideUp();
        });
		$(document).on('click', '.formattedNumberField', function(){
			$(this).select();
		});
		// kiểm tra số nhập vào	
		/* $(document).on('keydown', '.formattedNumberField', function(event){
			var kt = 'profile_mark_kt formattedNumberField';
			if($(this).attr('class') == kt){
				var n = parseFloat($(this).val());
			}else{
				var n = parseInt($(this).val().replace(/\D/g,''),10);
			}			
			if(n > 10){
				//event.preventDefault();
				// Show message to the user
				$(".message2").show();
				$(".message2").addClass('alert alert-danger');					
				$(".message2").html("Điểm không được lớn hơn 10.");
				$(this).css('color', "#FF0000");
				$('.btnAddNew').prop('disabled', true);
			}
			else {
				// A number is pressed so we hide the message
				$(".message2").hide();
				$(".message2 .alert-message").html("");
				$(".message2").removeClass('alert alert-danger');
				$('.message2').slideUp();
				$(this).css('color', "#000");
				$('.btnAddNew').prop('disabled', false);
			}
		});			
		 */
		function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
		function isFloat(evt) {
			var charCode = (event.which) ? event.which : event.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
				alert('Please enter only no or float value');
				return false;
			}
			else {
				//if dot sign entered more than once then don't allow to enter dot sign again. 46 is the code for dot sign
				var parts = evt.srcElement.value.split('.');
				if (parts.length > 1 && charCode == 46)
					return false;
				return true;
			}
		}
		$(document).on('keyup', '.formattedNumberField', function(event)
		{
			var kt = 'profile_mark_kt formattedNumberField';
			if($(this).attr('class') == kt){
				var n = parseFloat($(this).val());
			}
			else{
				var n = parseInt($(this).val().replace(/\D/g,''),10);	
				if(n.toLocaleString() == 'NaN'){
					$(this).val("");
				}else{
					$(this).val(n);
				}
			}
			
			 console.log(event.keyCode);
			 // Allow ".",
			if (event.keyCode == 190)
			{				
				var kt = 'profile_mark_kt formattedNumberField';
				if($(this).attr('class') == kt)
				{
					//do it
					if($(this).val().match(/\./g).length > 1)
					{						
						if(!isNaN(parseFloat($(this).val()))) $(this).val(parseFloat($(this).val()));
					}
					else{					
						//nothing
					}					
				}
				else{
					//nothing
					event.preventDefault();							
				}
			}else
			if(event.keyCode == 188) 
			{
				event.preventDefault();
				var kt = 'profile_mark_kt formattedNumberField';
				if($(this).attr('class') == kt)
				{
					if(!isNaN(parseFloat($(this).val()))){
						$(this).val(parseFloat($(this).val()));
					}else{
						$(this).val("");
					}
					$(".message2").show();
					$(".message2").addClass('alert alert-danger');					
					$(".message2").html("Sử dụng dấu . để biểu diễn số thập phân.");					
				}
			}
			else
			if (event.keyCode == 46 || event.keyCode == 8) 
			{				
				// Allow only backspace, delete, key right and key left
				// leave it empty.
				//var n = parseInt($(this).val().replace(/\D/g,''),10);
				if(n > 10)
				{
					//event.preventDefault();
					// Show message to the user
					$(".message2").show();
					$(".message2").addClass('alert alert-danger');					
					$(".message2").html("Điểm không được lớn hơn 10.");
					$(this).css('color', "#FF0000");
					$('.btnAddNew').prop('disabled', true);
				}
				else {
					// A number is pressed so we hide the message
					$(".message2").hide();
					$(".message2 .alert-message").html("");
					$(".message2").removeClass('alert alert-danger');
					$('.message2').slideUp();
					$(this).css('color', "#000");
					$('.btnAddNew').prop('disabled', false);
					var cc = $(this).parents('tr').find('.profile_mark_cc');
					var kt = $(this).parents('tr').find('.profile_mark_kt');					
					if(checkForm() == 1)
					{
						if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) )
						{
							$(this).parents('tr').css('background', '#dff0d8');
							$(this).parents('tr').css('color', '#000');
						}
						else{
							if(parseInt($(this).parents('tr').find('.number').html()) % 2)
							{						
								$(this).parents('tr').css('background', '#f9f9f9');
								$(this).parents('tr').css('color', '#000');
							}else{ 
								$(this).parents('tr').css('background', '#fff');
								$(this).parents('tr').css('color', '#000');
							}
						}
					}
					else{
						if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) )
						{						
							$(this).parents('tr').css('background', '#dff0d8');
							$(this).parents('tr').css('color', '#000');
						}
						else{
							if(parseInt($(this).parents('tr').find('.number').html()) % 2)
							{						
								$(this).parents('tr').css('background', '#f9f9f9');
								$(this).parents('tr').css('color', '#000');
							}
							else{ 
								$(this).parents('tr').css('background', '#fff');
								$(this).parents('tr').css('color', '#000');
							}
						}
					}
				}
			}
			else {
				//Tab key
				if (event.keyCode==9) 
				{		
					event.preventDefault();
					var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
					var cc = $(this).parents('tr').find('.profile_mark_cc');
					var kt = $(this).parents('tr').find('.profile_mark_kt');					
					if(checkForm() == 1)
					{
						if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) )
						{
							$(this).parents('tr').css('background', '#dff0d8');// 65a954
							$(this).parents('tr').css('color', '#000');
						}
						else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2)
								{						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}
								else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
					}
					else{
						if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) )
						{						
							$(this).parents('tr').css('background', '#dff0d8');
							$(this).parents('tr').css('color', '#000');
						}
						else{
							if(parseInt($(this).parents('tr').find('.number').html()) % 2)
							{						
								$(this).parents('tr').css('background', '#f9f9f9');
								$(this).parents('tr').css('color', '#000');
							}
							else{ 
								$(this).parents('tr').css('background', '#fff');
								$(this).parents('tr').css('color', '#000');
							}
						}
					}
					if(parseInt(inputs.eq( inputs.index(this)-1 ).val()) > 10)
					{						
						inputs.eq( inputs.index(this)-1 ).focus().select();
					}
					else{						
						$(this).focus().select();
					}
				}
				//left key
				else if (event.keyCode==37) {					
					event.preventDefault();
					if(n > 10){
						//nothing
					}
					else{
						var cc = $(this).parents('tr').find('.profile_mark_cc');
						var kt = $(this).parents('tr').find('.profile_mark_kt');					
						if(checkForm() == 1){
							if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}else{
							if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){					
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}
						var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
						inputs.eq( inputs.index(this)-1 ).focus().select();
						
					}
				}
				// up key
				else if (event.keyCode==38) {					
					event.preventDefault();
					if(n > 10){
						//nothing						
					}
					else{
						var cc = $(this).parents('tr').find('.profile_mark_cc');
						var kt = $(this).parents('tr').find('.profile_mark_kt');					
						if(checkForm() == 1){
							if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}else{
							if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){					
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}
						var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
						inputs.eq( inputs.index(this) - 2 ).focus().select();
					}
				}
				//right key
				else if (event.keyCode==39) {					
					event.preventDefault();
					if(n > 10){
						//nothing
					}
					else{
						var cc = $(this).parents('tr').find('.profile_mark_cc');
						var kt = $(this).parents('tr').find('.profile_mark_kt');					
						if(checkForm() == 1){
							if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}else{
							if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){					
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}
						var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
						inputs.eq( inputs.index(this)+ 1 ).focus().select();
					}
				}
				//down key
				else if (event.keyCode==40) {					
					event.preventDefault();
					if(n > 10){
						//nothing
					}
					else{
						var cc = $(this).parents('tr').find('.profile_mark_cc');
						var kt = $(this).parents('tr').find('.profile_mark_kt');					
						if(checkForm() == 1){
							if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}else{
							if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){					
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}
						var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
						inputs.eq( inputs.index(this)+ 2 ).focus().select();
					}
				}
				else if (event.keyCode == 13) {
					event.preventDefault();
					if(n > 10){
						//nothing
					}
					else{
						var cc = $(this).parents('tr').find('.profile_mark_cc');
						var kt = $(this).parents('tr').find('.profile_mark_kt');					
						if(checkForm() == 1){
							if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}else{
							if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){					
								$(this).parents('tr').css('background', '#dff0d8');
								$(this).parents('tr').css('color', '#000');
							}else{
								if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
									$(this).parents('tr').css('background', '#f9f9f9');
									$(this).parents('tr').css('color', '#000');
								}else{ 
									$(this).parents('tr').css('background', '#fff');
									$(this).parents('tr').css('color', '#000');
								}
							}
						}
						var inputs = $(this).parents('tbody').find(':input[type="text"]:enabled:visible:not("disabled")');
						inputs.eq( inputs.index(this)+ 1 ).focus().select();
					}
				}
					// Ensure that it is a number and stop the keypress
				else if (event.keyCode < 48 || (event.keyCode > 57 && event.keyCode <96) || event.keyCode > 105 ) {
					event.preventDefault();
					var kt = 'profile_mark_kt formattedNumberField';
					if($(this).attr('class') == kt)
					{
						if(!isNaN(parseFloat($(this).val()))){
							$(this).val(parseFloat($(this).val()));
						}else{
							$(this).val("");
						}
					}
					
					// Show message to the user
					$(".message2").show();
					$(".message2").addClass('alert alert-danger');					
					$(".message2").html("Chỉ nhập số.");
				}
				else {
					// A number is pressed so we hide the message
					$(".message2").hide();
					$(".message2 .alert-message").html("");
					$(".message2").removeClass('alert alert-danger');
					$('.message2').slideUp();

				}
				if(n > 10){
					// Show message to the user
					$(".message2").show();
					$(".message2").addClass('alert alert-danger');					
					$(".message2").html("Điểm không được lớn hơn 10.");
					$(this).css('color', "#FF0000");
					$('.btnAddNew').prop('disabled', true);
					if(parseInt($(this).parents('tr').find('.number').html()) % 2){						
						$(this).parents('tr').css('background', '#f9f9f9');
						$(this).parents('tr').css('color', '#000');
					}else{ 
						$(this).parents('tr').css('background', '#fff');
						$(this).parents('tr').css('color', '#000');
					}
				}
				else {
					// A number is pressed so we hide the message
					$(".message2").hide();
					$(".message2 .alert-message").html("");
					$(".message2").removeClass('alert alert-danger');
					$('.message2').slideUp();
					$(this).css('color', "#000");
					$(this).css('background', "#fff");
					$('.btnAddNew').prop('disabled', false);
					var cc = $(this).parents('tr').find('.profile_mark_cc');
					var kt = $(this).parents('tr').find('.profile_mark_kt');					
					if(checkForm() == 1){
						if((cc.val() != "" && parseInt(cc.val()) <11) || (kt.val() != "" && parseFloat(kt.val()) <11) ){
							$(this).parents('tr').css('background', '#dff0d8');
							$(this).parents('tr').css('color', '#000');
						}
					}else{
						if((cc.val() != "" && parseInt(cc.val()) <11) && (kt.val() != "" && parseFloat(kt.val()) <11) ){						
							$(this).parents('tr').css('background', '#dff0d8');
							$(this).parents('tr').css('color', '#000');
						}
					}
				}
			}
		});	
		function checkForm(){
			var mark_form = 1;	
			var totalPoints = 0;
			$('.profile_mark_kt').each(function(){									
						totalPoints += parseInt($(this).val());
					});
				if(totalPoints > 0 ){
					mark_form = 2;
				}
			return mark_form;	
		}
        // Addnew
        $('#action-table').on('click', '.btnAddNew', function() {            
			var class_id = $('#filter_class option:checked').val();
			var mark_form = 1;	
			var success_list = 0;
			var error_list = 0;
			var totalsv = 0;
			$('#datatables tbody tr').each(function(){	
				var thisis = $(this);
				var number = $(this).find("td.number").text();				
				var profile_id = $("#profile_i_"+number).val();
				var profile_mark_cc = $("#profile_mark_cc_"+profile_id).val();
				var profile_mark_kt = $("#profile_mark_kt_"+profile_id).val();
				// lấy điểm cc, kt
				var mark_cc = (parseInt(profile_mark_cc).toLocaleString() == "NaN") ? "" : parseInt(profile_mark_cc);
				var mark_kt = (parseFloat(profile_mark_kt).toLocaleString() == "NaN") ? "" : parseFloat(profile_mark_kt);
							
				var totalPoints1 = 0;
				$('.profile_mark_cc').each(function(){									
						//totalPoints1 += parseInt($(this).val());
					if($(this).val() !==""){
					     totalPoints1 = 1;
					}

					});
				var totalPoints2 = 0;
				$('.profile_mark_kt').each(function(){									
						//totalPoints2 += parseInt($(this).val());
					if($(this).val() !==""){
					     totalPoints2 = 1;
					}

				});
				if(totalPoints1 > 0 && totalPoints2 > 0 ){
					mark_form = 2;
				}
				else{
					mark_form = 1;
				}
				/*
				if(profile_mark_cc =="" && mark_form == 2){
					$("#profile_mark_cc_"+profile_id).val("0");
					mark_cc = 0;
				}
				if(profile_mark_kt =="" && mark_form == 2){
					$("#profile_mark_kt_"+profile_id).val("0");
					mark_kt = 0;
				}
				if(profile_mark_cc =="" && mark_form == 1 && totalPoints1 == 1){
					$("#profile_mark_cc_"+profile_id).val("0");
					mark_cc= 0;
				}
				if(profile_mark_kt =="" && mark_form == 1 && totalPoints2 == 1){
					$("#profile_mark_kt_"+profile_id).val("0");
					mark_kt = 0;
				}

	
*/				
				//if()
				//alert(mark_form);			
				var postData = "CC:"+ mark_cc+" KT:"+ mark_kt+" ID:"+ profile_id+" Format:"+ mark_form+" Class:"+ class_id;
				//chuyển trạng thái: khóa nút Cập nhật
				$('.btnAddNew').prop('disabled', true);
				$('.btnReset').prop('disabled', true);		
				// cập nhật dữ liệu điểm
				$.post(ajaxUrl, {act: 'updatemark', id_profile: profile_id, class_id: class_id, mark_cc: mark_cc, mark_kt: mark_kt, mark_form: mark_form}, function(data) {				
					var data = $.parseJSON(data);	
					totalsv = totalsv + 1;					
					if(data.status == 'success'){						
						success_list = success_list + 1;
						$("#profile_mark_cc_"+profile_id).css('background', "#ccc");
						$("#profile_mark_cc_"+profile_id).prop('disabled', true);
						$("#profile_mark_kt_"+profile_id).prop('disabled', true);
						$("#profile_mark_kt_"+profile_id).css('background', "#ccc");
						var data_result = "Cập nhật điểm thành công "+success_list+" học viên.";
						$('.message2').html(data_result);
						$('.alert-message').html(data_result);
						$('.message2').addClass('alert-success');
						$('.message2').addClass('alert');
						$('.message2').show();
					}
					if(data.status == 'danger'){
						//error_list = error_list + 1;
						//alert("Có lỗi. Kiểm tra lại dữ liệu nhập vào.");
						$("#profile_mark_cc_"+profile_id).parents('tr').css('background', "#000");
						$("#profile_mark_cc_"+profile_id).parents('tr').css('color', "#fff");
						if(data.returndata == "cc"){
							$("#profile_mark_cc_"+profile_id).css('background', "#fff621");							
						}else{
							$("#profile_mark_kt_"+profile_id).css('background', "#fff621");
						}
					}					
					console.log(postData);
				});					
			});	
			$('.btnPrint').prop('disabled', false);
			$('.btnAddNew').prop('disabled', false);
			$('.btnReset').prop('disabled', false);		
        });       

        // Bắt sự kiện thay đổi điều kiện lọc trên #form_filter
        $('#form_filter').on('change','.select_filter', function(){
			$(".message2").hide();
			$(".message2").html("");
			$(".message2 .alert-message").html("");
			$(".message2").removeClass('alert alert-danger');
			$(".message2").removeClass('alert alert-success');
			$('.message2').slideUp();
			var type_filter = "";
			if(this.id == 'filter_course'){
				type_filter = 'course';
			}
			if(this.id == 'filter_year'){
				type_filter = 'year';
			}
			if(this.id == 'filter_term'){
				type_filter = 'term';
			}
			if(this.id == 'filter_group_field'){
				type_filter = 'group';
			}
			if(this.id == 'filter_subject'){
				type_filter = 'subject';
			}
			if(this.id == 'filter_class'){
				type_filter = 'profile';		
				$('.btnSelect').prop("disabled", false);				
				/* $(".message2").show();
				$(".message2").addClass('alert alert-success');					
				$(".message2").html("Đang tải dữ liệu..."); */
			}
			var course = $('#filter_course').val();
			var year = $('#filter_year').val();
            var term = $('#filter_term').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
			var class_id = $('#filter_class option:checked').val();            
			
			$.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course:course, year:year, term:term, group_field:group_field, subject:subject, id_class:class_id}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;
				var op = '<option value="0">-- Tất cả --</option>';
				if(type_filter == 'course'){
					$('#filter_year').html(str_data);
					$('#filter_term').html(op);
					$('#filter_subject').html(op);
					$('#filter_class').html(op);
				}
                if(type_filter == 'year'){
					$('#filter_term').html(str_data);
					$('#filter_subject').html(op);
					$('#filter_class').html(op);
				}
                if(type_filter == 'term'){
					$('#filter_subject').html(op);
					$('#filter_class').html(op);
				}
                if(type_filter == 'group'){
					$('#filter_subject').html(str_data);
					$('#filter_class').html(op);
				}
                if(type_filter == 'subject'){
					$('#filter_class').html(str_data);
					$('#datatables tbody').html("");
				}
				if(type_filter == 'profile'){				
					$(".btnSelect").prop('disabled', false);
				}
				/*
				// load lại table	
					if(class_id != 0){
						$('#datatables tbody').html(str_data);
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html("Danh sách đã được tải.");
					}else{
						$('#datatables tbody').html("");	
						$(".message2").slideUp();
						$(".message2").removeClass('alert alert-success');					
						$(".message2").html("");
					}									
					$('.btnAddNew').prop('disabled', false);
					$('.btnReset').prop('disabled', false);	
				} */                                 
            });
			
        });
// Event chọn lớp học phần
		$('.btnSelect').click(function()
		{
			$(".message2").hide();
			$(".message2").html("");
			$(".message2 .alert-message").html("");
			$(".message2").removeClass('alert alert-danger');
			$(".message2").removeClass('alert alert-success');
			$('.message2').slideUp();
			var type_filter = "";			
				type_filter = 'profile';				
				$(".message2").show();
				$(".message2").addClass('alert alert-success');					
				$(".message2").html("Đang tải dữ liệu...");
			
			var course = $('#filter_course').val();
			var year = $('#filter_year').val();
            var term = $('#filter_term').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
			var class_id = $('#filter_class option:checked').val();            
			
			$.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course:course, year:year, term:term, group_field:group_field, subject:subject, id_class:class_id}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;
				
				// load lại table	
					if(class_id != 0){
						$('#datatables tbody').html(str_data);
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html("Danh sách đã được tải.");
					}else{
						$('#datatables tbody').html("");	
						$(".message2").slideUp();
						$(".message2").removeClass('alert alert-success');					
						$(".message2").html("");
					}									
					$('.btnAddNew').prop('disabled', false);
					$('.btnReset').prop('disabled', false);	
				                            
            });
			
		});
		$('.btnReset').click(function(){
			$(".message2").hide();
			$(".message2").html("");
			$(".message2 .alert-message").html("");
			$(".message2").removeClass('alert alert-danger');
			$(".message2").removeClass('alert alert-success');
			$('.message2').slideUp();
			var type_filter = "";			
				type_filter = 'profile';				
				$(".message2").show();
				$(".message2").addClass('alert alert-success');					
				$(".message2").html("Đang tải dữ liệu...");
			
			var course = $('#filter_course').val();
			var year = $('#filter_year').val();
            var term = $('#filter_term').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
			var class_id = $('#filter_class option:checked').val();            
			
			$.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course:course, year:year, term:term, group_field:group_field, subject:subject, id_class:class_id}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;
				
				// load lại table	
					if(class_id != 0){
						$('#datatables tbody').html(str_data);
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html("Danh sách đã được tải.");
					}else{
						$('#datatables tbody').html("");	
						$(".message2").slideUp();
						$(".message2").removeClass('alert alert-success');					
						$(".message2").html("");
					}									
					$('.btnAddNew').prop('disabled', false);
					$('.btnReset').prop('disabled', false);	
				                            
            });
		});
		// Event click btnPrint
		$('.btnPrint').click(function(){
            var course = $('#filter_course').val();
			var year = $('#filter_year').val();
            var term = $('#filter_term').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
			var class_id = $('#filter_class option:checked').val();

            var downloadUrl = "<?= AppObject::getBaseFile('app/markcomponent/helpers/downloadMark.php') ?>";

            if (class_id !== "0")
                $.download(downloadUrl, "course=" + course + "&year=" + year + "&term=" + term + "&subject=" + subject + "&class_id=" + class_id, 'get');
            else
                alert('Chưa chọn lớp cần in danh sách vào điểm.');
        });	
    })(jQuery);
	
</script>
