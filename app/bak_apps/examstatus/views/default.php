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
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/examstatus/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/examstatus/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/examstatus/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/examstatus/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/examstatus/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/examstatus/js/scripts.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/examstatus/js/jquery.download.js')?>"></script>
<style>
.message2{

}
</style>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Chọn phòng thi </h4>
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
                    <label for="" class="col-sm-4 control-label">Đợt thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_exam" id="filter_exam" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Môn thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_subject" id="filter_subject" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>
						</select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Phòng thi:</label>
                    <div class="col-sm-8">
                        <select name="filter_room" id="filter_room" class="form-control input-sm select_filter">
                            <option value="">-- Tất cả --</option>							
                        </select>
                    </div>
                </div>		
            </form>
			<div class="form-group">
				<div class="col-sm-4"></div>
				<div class="col-sm-8">
				   <button class="btn btn-md btn-info btnSelect disabled"><i class="glyphicon glyphicon-arrow-right"></i> Chọn phòng thi </button>
				</div>
			</div>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách thí sinh</h4>
        </div>
        <div class="panel-body"> 
			<div class="panel-exam"> 
				<div class="col-sm-6">
					<label for="" class="col-sm-3 control-label">Khóa học:</label>
					<div id="course_selected">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="" class="col-sm-3 control-label">Đợt thi:</label>
					<div id="exam_selected">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="" class="col-sm-3 control-label">Môn thi:</label>
					<div id="subject_selected">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="" class="col-sm-3 control-label">Phòng thi:</label>
					<div id="room_selected">
					</div>
				</div>
			</div>
            <table class="table table-striped tbAutoScroll" id="datatables">
                <thead>
                    <tr>
                        
                        <th>#</th>
                        <th>Mã học viên</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Lớp học</th>
                        <th>Tình trạng thi</th>
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
						<input type="hidden" name="id_exam_list_str" id="id_exam_list_str" value="">
						<input type="hidden" name="id_exam_room_str" id="id_exam_room_str" value="">
						<input type="hidden" name="id_exam_state_str" id="id_exam_state_str" value="">						
						<button class="btn btn-md btn-primary btnAddNew"><i class="glyphicon glyphicon-download-alt"></i> Lưu trạng thái</button>
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
		scrollY: $(window).height() - 380,
		fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			var index = iDisplayIndex +1;
			$('td:eq(0)',nRow).html(index);
		}

	});
	
    (function(){
		$('.btnAddNew').prop('disabled', true);
		$('.btnReset').prop('disabled', true);
		$('.panel-exam').hide();
       
        var ajaxUrl = "<?=AppObject::getBaseFile('app/examstatus/helpers/ajax.php')?>";
		// xóa thông báo
        $(document).on('click', '.btn', function(){
            $('.message2 .alert').removeClass('alert-success alert-danger');
            $('.message2').hide();
			$('.message2').slideUp();
        });
		$(document).on('click', '.formattedNumberField', function(){
			$(this).select();
		});
		

// Bắt sự kiện thay đổi điều kiện lọc trên #form_filter
        $('#form_filter').on('change','.select_filter', function()
		{
			$(".message2").hide();
			$(".message2").html("");
			$(".message2 .alert-message").html("");
			$(".message2").removeClass('alert alert-danger');
			$(".message2").removeClass('alert alert-success');
			$('.message2').slideUp();
			var type_filter = "";
			var course = $('#filter_course').val();
			var exam = $('#filter_exam').val();
            var subject = $('#filter_subject').val();
            var room = $('#filter_room').val(); 
			
			if(this.id == 'filter_course'){
				type_filter = 'course';
			}
			if(this.id == 'filter_exam'){
				type_filter = 'exam';
			}
			if(this.id == 'filter_subject'){
				type_filter = 'subject';
			}
			if(this.id == 'filter_room'){
				type_filter = 'room';
				
				$.post(ajaxUrl, {act: 'loadfilter', type_filter: "get_id_exam", course:course, exam:exam, subject:subject, room:room}, function(data){					
					var data = $.parseJSON(data);
					var str_data = data.returndata;				
					if(str_data !='')
					{
						$('#id_exam_list_str').val(str_data); 
					}
					else
					{
						$('#id_exam_list_str').val(""); 
					}
				});
				
				if(room == 0)
				{
					$('.btnSelect').addClass('disabled');
					$('#id_exam_list_str').val(""); 
					$('#id_exam_room_str').val(""); 
				}
				else
				{
					$('.btnSelect').removeClass('disabled');
					$('#id_exam_room_str').val(room); 
				}
								
			}
			
			$.post(ajaxUrl, {act: 'loadfilter', type_filter: type_filter, course:course, exam:exam, subject:subject, room:room}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;				
				if(type_filter == 'course')
				{
					if(str_data !='')
					{
						$('#filter_exam').html(str_data);
					}else
					{
						$('#filter_exam').html('<option value="0">-- Tất cả --</option>');
						
					}
						$('#filter_subject').html('<option value="0">-- Tất cả --</option>');
						$('#filter_room').html('<option value="0">-- Tất cả --</option>');
						$('.btnSelect').addClass('disabled');
				}
				if(type_filter == 'exam'){
					if(str_data !=''){
						$('#filter_subject').html(str_data);
					}else
					{
						$('#filter_subject').html('<option value="0">-- Tất cả --</option>');						
					}
						$('#filter_room').html('<option value="0">-- Tất cả --</option>');
						$('.btnSelect').addClass('disabled');
				}
				if(type_filter == 'subject'){
					if(str_data !=''){
						$('#filter_room').html(str_data);
					}else
					{
						$('#filter_room').html('<option value="0">-- Tất cả --</option>');
					}					
					$('.btnSelect').addClass('disabled');
				}				                       
            });
			
        });
// Event cập nhật trạng thái thi của thí sinh
        $('#action-table').on('click', '.btnAddNew', function() 
		{            
			var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// mã phòng được chọn
			var id_exam_room_hidden = $('#id_exam_room_str').val();		// số phòng được chọn
			var success_list = 0;
			var error_list = 0;
			var totalsv = 0;
			$('#datatables tbody tr').each(function(){	
				var thisis = $(this);
				var number = $(this).find("td.number").text();				
				var student_id = $("#student_i_"+number).val();
				var student_state = $("#filter_state_"+student_id+" option:selected").val();
							
				var postData = "Student:"+ student_id+" State:"+ student_state+ " Exam: "+ id_exam_list_hidden + "Room: "+ id_exam_room_hidden;
				//chuyển trạng thái: khóa nút Cập nhật
				$('.btnAddNew').prop('disabled', true);
				$('.btnSelect').prop('disabled', true);		
				$('.btnReset').prop('disabled', true);		
				// cập nhật trạng thái thi của thí sinh
				$.post(ajaxUrl, {act: 'updatstate', id_exam: id_exam_list_hidden, id_student: student_id, room: id_exam_room_hidden, state: student_state}, function(data) {				
					var data = $.parseJSON(data);	
					totalsv = totalsv + 1;					
					if(data.status == 'success'){						
						success_list = success_list + 1;
						$("#filter_state_"+student_id).css('background', "#ccc");
						$("#filter_state_"+student_id).prop('disabled', true);
						var data_result = "Cập nhật thành công trạng thái thi của "+success_list+"/"+totalsv+" thí sinh.";
						$('.message2').html(data_result);
						$('.alert-message').html(data_result);
						$('.message2').addClass('alert-success');
						$('.message2').addClass('alert');
						$('.message2').show();
					}
										
					console.log(postData+" Return: {" +id_student+ "-"+data.status+"}");
				});					
			});	
			//$('.btnPrint').prop('disabled', false);
			//$('.btnAddNew').prop('disabled', false);
			$('.btnReset').prop('disabled', false);		
			$('.btnSelect').prop('disabled', false);		
        });       
// Event chọn phòng thi		
		$('.btnSelect').click(function()
		{
			var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// mã phòng được chọn
			var id_exam_room_hidden = $('#id_exam_room_str').val();		// số phòng được chọn
			var id_exam_state_hidden = $('#id_exam_state_str').val(); 	// trạng thái danh sách
			var course = $('#filter_course').val();
			var exam = $('#filter_exam').val();
            var subject = $('#filter_subject option:selected').html();
            var room = $('#filter_room').val(); 
            $('#course_selected').html(course); 
            $('#exam_selected').html(exam); 
            $('#subject_selected').html(subject); 
            $('#room_selected').html(room); 
			
			$('.panel-exam').slideDown();
			$('.panel-exam').addClass('panel-heading');
			$(".message2 .alert-message").html("");
			$('.btnSelect').prop('disabled', true);
			$('.btnReset').prop('disabled', true);
			
			//if(id_exam_list_hidden !== "" && id_exam_room_hidden !== "")
			//{
				// tải thông tin thí sinh
				$.post(ajaxUrl, {act: 'loadtester', id_exam:id_exam_list_hidden, room:id_exam_room_hidden}, function(data) 
				{				
					//alert(data);					
					var data = $.parseJSON(data);
					var str_data = data.returndata;	
					// load lại table
					if(str_data !=''){
						$('#datatables tbody').html(str_data);
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html("Danh sách đã được tải.");
					}
					$('.btnSelect').prop('disabled', false);
					$('.btnAddNew').prop('disabled', false);	
					$('.btnReset').prop('disabled', false);	
				});
			//}
			
		});
// Event tải lại danh sách thí sinh
		$('.btnReset').click(function()
		{
			var id_exam_list_hidden = $('#id_exam_list_str').val(); 	// mã phòng được chọn
			var id_exam_room_hidden = $('#id_exam_room_str').val();		// số phòng được chọn
			var id_exam_state_hidden = $('#id_exam_state_str').val(); 	// trạng thái danh sách
			$('.btnReset').prop('disabled', true);
			$('.btnSelect').prop('disabled', true);
			$.post(ajaxUrl, {act: 'loadtester', id_exam:id_exam_list_hidden, room:id_exam_room_hidden}, function(data) 
			{				
					//alert(data);					
					var data = $.parseJSON(data);
					var str_data = data.returndata;	
					// load lại table
					if(str_data !=''){
						$('#datatables tbody').html(str_data);
						$(".message2").show();
						$(".message2").addClass('alert alert-success');					
						$(".message2").html("Danh sách đã được tải.");
					}
					$('.btnSelect').prop('disabled', false);
					$('.btnAddNew').prop('disabled', false);	
					$('.btnReset').prop('disabled', false);	
				});
		});
		
    })(jQuery);
	
</script>
