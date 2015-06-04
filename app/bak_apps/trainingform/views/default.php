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
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/trainingform/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/trainingform/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/trainingform/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/trainingform/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/trainingform/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/trainingform/js/scripts.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/trainingform/js/jquery.download.js')?>"></script>
<style>
.message2{

}
.center{
	margin-left: auto;
	margin-right: auto;
	width:100%;
	vertical-align: middle ;
}
</style>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Xây dựng khung chương trình đào tạo</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="form_filter" >
                <div class="form-group">
				<label for="" class="col-sm-12 control-label">Phương án xây dựng khung chương trình:</label>
                    <div class="col-sm-12">
                        <select name="filter_form" id="filter_form" class="form-control input-sm select_filter">
                            <option value="0">Xây dựng mới khung chương trình</option>                            
                            <option value="1">Kế thừa từ khóa học gần nhất</option>
                        </select>
                    </div>
                </div>               
				<div class="form-group" id="form_upgrade" style="display:none;">
                    <label for="" class="col-sm-4 control-label">Kế thừa từ:</label>
                    <div class="col-sm-8">
                        <select name="final_course" id="final_course" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->course as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>               
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
                    <label for="" class="col-sm-4 control-label">Chuyên ngành:</label>
                    <div class="col-sm-8">
                        <select name="filter_subject" id="filter_subject" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->subjects as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Khối kiến thức:</label>
                    <div class="col-sm-8">
                        <select name="filter_knowledge" id="filter_knowledge" class="form-control input-sm select_filter">
                            <option value="0">-- Tất cả --</option>
                            <?php foreach ($this->knowledge as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
				 <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Số tín chỉ:</label>
                    <div class="col-sm-8">
                        <input type="text" id="curriculum">
                    </div>
                </div>			
            </form>
			<div>                    
				<button class="btn btn-md btn-primary btnAddNew col-sm-10 center"><i class="glyphicon glyphicon-download-alt"></i> Lưu khung chương trình</button>                    
			</div>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Khung chương trình đào tạo sau đại học</h4>
        </div>
        <div class="panel-body">            
           <div id= "training_form">
		   <div class="auto_scroll " id="">
                    <table id="list_training_form" class="table table-striped tbAutoScroll" data-toggle="table" data-height="250">
                        <thead> 
                            <tr class="row_title">
                                <th colspan="3">Thống kê khung chương trình đào tạo</th>
                            </tr>
                            <tr>
                                <th>Khóa học</th>
                                <th>Chuyên ngành</th>
                                <th>Khối kiến thức</th>
                                <th>Số tín chỉ</th>
                            </tr>
                        </thead>
                        <tbody>
						</tbody>
                    </table>
                </div>
		   </div>
        </div>
		<div class="well">
			<div class="message2" style="display:none;">
				<div class="alert alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<span class="alert-message"></span>
				</div>
			</div>
			
		</div>
    </div>
</div>

<!-- Ajax xu ly: Edit by ANVT-->
<script type="text/javascript">
	
    (function(){
		$('.tbAutoScroll').DataTable({
			ordering: false,
			paging: false,
			info: false,
			searching: false,
			scrollY: $(window).height() - 320,
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
				var index = iDisplayIndex +1;
				$('td:eq(0)',nRow).html(index);
			}

		});
		$('.btnAddNew').prop('disabled', true);
		
        var ajaxUrl = "<?=AppObject::getBaseFile('app/trainingform/helpers/ajax.php')?>";
		// xóa thông báo
        $(document).on('click', '.btn', function(){
            $('.message2 .alert').removeClass('alert-success alert-danger');
            $('.message2').hide();
			$('.message2').slideUp();
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
			var course_upgrade = $('#final_course').val();
			var course = $('#filter_course').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
            var knowledge = $('#filter_knowledge').val();
			var curriculum = $('#curriculum').val(); 			
			if(this.id == 'filter_form'){
				type_filter = 'upgrade';				
				
				if($('#filter_form').val() == 1){
					$('select:not("#filter_form,#filter_course,#final_course")').attr('disabled', true);
					$('#curriculum').attr('disabled', true);
					$('#form_upgrade').css('display','');
					$('#final_course').focus();
				}
				else
				{
					$('#form_upgrade').css('display','none');
					$('select').attr('disabled', false);
					$('#curriculum').attr('disabled', false);
				}
			}
			if(this.id == 'filter_course'){
				type_filter = 'course';	
				
			}
			if(this.id == 'final_course'){
				type_filter = 'course';		
				course = $('#final_course').val();				
			}
			if(this.id == 'filter_group_field'){
				type_filter = 'group';				
			}
			if(this.id == 'filter_knowledge'){
				type_filter = 'knowledge';				
			}
			if(knowledge == 0)
			{
				$('#curriculum').val("");
				$('#curriculum').attr('disabled', true);
			}
			else if( $('#filter_form').val() != 1)
			{				
				$('#curriculum').attr('disabled', false);
			}
			if((course_upgrade == "" || course == "") && type_filter == 'course') {
				$('table tbody').html("");
			}
			if(course == "" || course_upgrade == "" || subject == "" ||knowledge == 0) {
				$('.btnAddNew').prop('disabled', true);				
			}
			if(course != "" && course_upgrade != "" || course != course_upgrade) {
				$('.btnAddNew').prop('disabled', false);
			}
			
			$.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course:course, group_field:group_field, subject:subject, knowledge:knowledge}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;
				if(data.status == 'error')
				{						
					$(".message2").show();
					$(".message2").addClass('alert alert-error');					
					$(".message2").html(data.message);
				}
				else
				{
					$(".message2").show();
					$(".message2").addClass('alert alert-success');					
					$(".message2").html(data.message);
				}
				if(course !="" && knowledge != "" && curriculum != "" && subject != ""){
					$('.btnAddNew').prop('disabled', false);
				}
				if(course !="" && knowledge != "" && subject != "" && str_data != ""){
					$('.btnAddNew').prop('disabled', false);
					if(type_filter == 'group')
					{		
					
					}else if(type_filter != 'course'){	
						$('#curriculum').val(str_data);
					}
				}
				if(type_filter == 'group')
				{					
					$('#filter_subject').html(str_data);
				}else
					if(type_filter == 'course' && course !="" && str_data != "")
					{
						$('table tbody').html(str_data);
					}
					else				
					{
						if(type_filter == 'course')
						{
							$('table tbody').html("");
						}					
					}
			});                              
		});	
		$(document).on('keyup', '#curriculum', function(event)
		{		
			var n = parseInt($(this).val().replace(/\D/g,''),10);
			var course = $('#filter_course').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
            var knowledge = $('#filter_knowledge').val();     			
			if(n.toLocaleString() == 'NaN')
			{
				$(this).val("");
				$('.btnAddNew').prop('disabled', true);
			}
			else
			{
				$(this).val(n);
				if(course !="" && knowledge != "" && curriculum != "" && subject != "")
				{
					$('.btnAddNew').prop('disabled', false);
				}
			}

		});		
		$('.btnAddNew').click(function(){
			var ajaxUrl = "<?=AppObject::getBaseFile('app/trainingform/helpers/ajax.php')?>";
			var course_upgrade = $('#final_course').val();
			var course = $('#filter_course').val();
            var group_field = $('#filter_group_field').val();
            var subject = $('#filter_subject').val();
            var knowledge = $('#filter_knowledge').val();
			var curriculum = $('#curriculum').val(); 
			var act = 'updateform';
			
			$(".message2 .alert-message").html("");
			$('.btnAddNew').prop('disabled', true);
			if($('#filter_form').val() == 1){
				type_filter = 'upgrade';	
				$('#filter_course').focus();
				act= 'upgrade';
				
				$.post(ajaxUrl, {act: act, course:course, course_upgrade:course_upgrade}, function(data) {				
					//alert(data);
					var data = $.parseJSON(data);
					var str_data = data.returndata;
					$('table tbody').html(str_data);
					if(data.status == 'error')
					{						
						$(".message2").show();
						$(".message2").addClass('alert alert-error');					
						$(".message2").html(data.message);						
					}
					else
					{
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html(data.message);
					}
				}); 
				$('.btnAddNew').prop('disabled', false);
			}
			else
			{
				$.post(ajaxUrl, {act: act, course:course, group_field:group_field, subject:subject, knowledge:knowledge, curriculum:curriculum}, function(data) {				
					//alert(data);
					var data = $.parseJSON(data);
					var str_data1 = data.returndata;
					$('table tbody').html(str_data1);
					$(".message2").show();
					$(".message2").addClass('alert alert-success');					
					$(".message2").html("Thao tác thành công.");
					$('#curriculum').val('');
					$('#curriculum').html('');
					$('.btnAddNew').prop('disabled', false);
				}); 
			}
		});
    })(jQuery);
	
</script>
