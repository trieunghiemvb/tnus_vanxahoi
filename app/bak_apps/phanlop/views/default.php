<?php 
if ( !defined('AREA') ) {
    die('Access denied');
}
function makeDate($str){
	$str_array1 = explode(" ", $str);
	$str_array2 = explode("-", $str_array1[0]);
	$str_array3 = explode(":", $str_array1[1]);
	$str_return = $str_array2[2].'/'.$str_array2[1].'/'.$str_array2[0].' ';
	//$str_return .= $str_array3[0].':'.$str_array2[1];
	return $str_return;	
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/phanlop/css/dataTables.bootstrap.css')?>">
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/phanlop/css/class_style.css')?>">
<script src="<?php echo AppObject::getBaseFile('app/phanlop/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/phanlop/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/phanlop/js/fnAddTr.js')?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/phanlop/js/scripts.js')?>"></script>
<div class="col-sm-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>Chọn lớp </h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="form_filter" >
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Khóa:</label>
                    <div class="col-sm-8">
                        <select name="filter_course" id="filter_course" class="form-control input-sm select_filter">
                            <option value="0">-- Tất cả --</option>
                            <?php foreach ($this->course as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">Ngành:</label>
                    <div class="col-sm-8">
                        <select name="filter_group_field" id="filter_group_field" class="form-control input-sm select_filter">
                            <option value="0">-- Tất cả --</option>
                            <?php foreach ($this->group_field as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-4 control-label">Lớp:</label>
                    <div class="col-sm-8">
                        <select name="filter_class" id="filter_class" class="form-control input-sm">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($this->classAjax as $key => $value) { ?>
                            <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Danh sách người học</h4>
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
                    <tr align="center">                        
                        <th colspan="5" style="display:none">
                           Số lượng sinh viên đã chọn: <span id="totalProfile"></span>
                        </th>                        
                       
                    </tr>
                    <tr align="center">
                        <td colspan="5">
                            <button class="btn btn-md btn-info addnewBtn"><i class="glyphicon glyphicon-plus-sign"></i> Thực hiện phân lớp</button>
                            
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkall"></th>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->items as $item) {
                        $i++;
                        ?>
                    <tr id="profile_<?=$item['id']?>">
                        <td><input type="checkbox" value="<?=$item['id']?>"></td>
                        <td class="number"><?=$i?></td>
                        <td class="profile_name"><?=$item['last_name']?> <?=$item['first_name']?></td>
                        <td class="profile_birthday"><?=$item['birthday']?></td>
                        <td class="profile_phone"><?=$item['sex']?></td>
                        <td class="profile_birth_place"><?=$item['birth_place']?></td>
                        <td class="profile_comment"><?=$item['note']?></td>
                    </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ajax xu ly: Edit by ANVT-->
<script type="text/javascript">

    (function(){
        var oTable = $('#datatable').DataTable({
            ordering: false,
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                //                var index = iDisplayIndex +1;
                //                $('td:eq(0)',nRow).html(index);
            }
        });

        var ajaxUrl = "<?=AppObject::getBaseFile('app/phanlop/helpers/ajax.php')?>";

        $(document).on('click', '.btn', function(){
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });
		// get all checkbox checked
		function getAllIDChecked(){   

			var getid = $('input[type=checkbox]:checked').each(function(){

				//if(this.checked && this.value != '') return this.value;

			}).get().join(',');             

			return getid;
		}
		$('input[type=checkbox]').change(function(){
			var numberOfChecked = $('input:checkbox:checked').length;
			var	total = getValueIDChecked();
			$('#totalProfile').html(numberOfChecked);
		});	
		$('input[type=checkbox]').click(function(){
			if($(this).prop("checked") == false){				
				$('#checkall').prop("checked", false);	
				var val = $(this).val();
				//$('#profile_'+val).css('background', '#65a954');
				//$('#profile_'+val).css('color', '#fff');
				if (val % 2){
					//$('#profile_'+val).addClass("odd");
					$('#profile_'+val).css('background', '#f9f9f9');
					$('#profile_'+val).css('color', '#000');
				}else{
					$('#profile_'+val).css('background', '#fff');
					$('#profile_'+val).css('color', '#000');
				}
			}else{
				var val = $(this).val();
				$('#profile_'+val).css('background', '#65a954');//#c6edb6
				$('#profile_'+val).css('color', '#fff');
			}			
		});	
		// get all id checked
		function getValueIDChecked(){	
			var getid = $(':checkbox').map(function() {
				if(this.checked && this.value != '') return this.value;
			}).get().join(',');				
			getid = getid.replace("on,",'');
			return getid;
		}
		//	 click checkall box
		$('#checkall').click(function(){
			if($('#checkall').prop("checked")){
				setAllIdChecked();			
			}else{
				resetNoChecked();
			}		
		});
		//set all check box checked
		function setAllIdChecked(){
			$('input[type=checkbox]').each(function(){
				$(this).prop("checked", true);	
				var val = $(this).val();
				$('#profile_'+val).css('background', '#65a954');
				$('#profile_'+val).css('color', '#fff');
			});		
		}    
		//reset all check box
		function resetNoChecked(){
			$('input[type=checkbox]').each(function(){
				$(this).prop("checked", false);	
				var val = $(this).val();
				if (val % 2){
					//$('#profile_'+val).addClass("odd");
					$('#profile_'+val).css('background', '#f9f9f9');
					$('#profile_'+val).css('color', '#000');
				}else{
					$('#profile_'+val).css('background', '#fff');
					$('#profile_'+val).css('color', '#000');
				}
			});		
		}
        

        // Addnew
        $('#action-table').on('click', '.addnewBtn', function() {
            var total_profile = getValueIDChecked();
			var class_id = $('#filter_class option:checked').val();
            $.post(ajaxUrl, {act: 'addstudent', total_profile: total_profile, class_id: class_id}, function(data) {			
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if(data.status == 'success'){
                    var control_str = data.returndata;
                    
					var tmp = control_str.split(';');
					for(i = 0; i < tmp.length; i++){ 
						oTable.row('#profile_' + tmp[i]).remove().draw(false);
					}                    
                    //$('#action-table').find('input, select').val("");
                    $('#datatable').dataTable().fnFilter('');					
		    resetNoChecked();			
                }
            });
        });       

        // Bắt sự kiện thay đổi điều kiện lọc trên #form_filter
        $('#form_filter').on('change','.select_filter', function(){
            var id_course = $('#filter_course').val();
            var id_group_field = $('#filter_group_field').val();
			//alert("khóa:"+id_course+" nhóm:"+id_group_field);
            $.post(ajaxUrl, {act: 'loadtable', id_course:id_course, id_group_field:id_group_field}, function(data) {
				//alert(data);
				var data = $.parseJSON(data);
                var str_data = data.returndata;
                var row = str_data.split('#');
				var i=0;
				var option = '<option value="">-- Tất cả --</option>';
				if(row[i] != ""){
                $.each(row,function(key,value){
                    i++
                    var tmp = value.split(';');
                    if(tmp[0]!=null&&tmp[0]!=""){
						 r = '<option value="'+tmp[0]+'">' + tmp[1] + '</option>';                                              
                    }
					option = option + r;
				});
				}
				$('#filter_class').html(option);                
            });
        })
    })(jQuery);
</script>