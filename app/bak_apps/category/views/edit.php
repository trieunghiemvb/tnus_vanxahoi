<?php
if ( !defined('AREA') ) { die('Access denied'); }
	
?>
<link href="<?php echo AppObject::getBaseFile('app/category/css/style.css')?>" rel="stylesheet" media="screen">

<script>
	function check(){
		var title=document.getElementById("title").value;
		
		if(title==""){
			alert("Bạn phải nhập Tên chủ đề");
			document.getElementById("title").style.border="1px solid red";
			window.scroll(0,findPos(document.getElementById("title")));
			return false;
		}
		return true;
		
	}
	function checkdata(event){
		if(event=='cancel'){
			document.getElementById("task").value="cancel";
			document.getElementById("category_form").submit();
		}else
		if(check()==true){
		switch(event){
			case 'save':
			document.getElementById("task").value="save";
			document.getElementById("category_form").submit();
			break;
			case 'update':
			document.getElementById("task").value="update";
			document.getElementById("category_form").submit();
			break;
			case 'savenew':
			document.getElementById("task").value="savenew";
			document.getElementById("category_form").submit();
			break;
			case 'apply':
			document.getElementById("task").value="apply";
			document.getElementById("category_form").submit();
			case 'cancel':
			document.getElementById("task").value="cancel";
			document.getElementById("category_form").submit();
			break;
		}
		}
	}

</script>

<div id="con_title">
	<div class="con_title"><h3><?php if(isset($_REQUEST['check'])){echo "Chỉnh sửa chủ đề";}else{echo "Thêm mới chủ đề";}?></h3></div>
	<div class="con_description"><strong><?php if(isset($_REQUEST['check'])){echo "Thực hiện chỉnh sửa chủ đề";}else{echo "Thực hiện thêm mới chủ đề";}?></strong></div>
</div>
<?php /************/ ?>
<form action="" method="post" class="form-search" id="category_form">
	<div id="con_content">
		<div id="con_toonbar">
		
			<div class="con_toonbar">
				<?php if($_REQUEST['task']!='edit'){?>
				<input type="button" onclick="checkdata('save')" value="Lưu"  class="btn ">
				<input type="button" onclick="checkdata('savenew')" value="Lưu và Thêm mới"  class="btn ">
				<input type="button" onclick="checkdata('apply')"  value="Áp Dụng" class="btn ">
				<?php }else{ ?>
				<input type="button" onclick="checkdata('update')" value="Cập Nhật"  class="btn ">
				<?php } ?>
				<input type="button" onclick="checkdata('cancel')" value="<?php if($_REQUEST['task']=='edit'){echo 'Hủy';}else{echo 'Hủy';}?>" class="btn ">
			</div>
			
		</div>	
		<?php global $message; echo $message;?>
		<div class="con_content">
			<div class="con_form">
				<fieldset>
					<legend>Nội dung</legend>
					<ul>
						<li>
							<label class="con_form_label">Tên chủ đề</label><input type="text" placeholder="Tên chủ đề" name="title" id="title" value="<?php echo $this->loaditem['title']; ?>"><br>				
						</li><br>
						<li>
							<?php if($this->loaditem['published']==1){$published1="checked";$published2="";}else{$published2="checked";$published1="";} ?>
							<label class="con_form_label">Trạng thái</label>
							<label class="radio">Bật<input type="radio" name="published" id="published1" value="1" <?php echo $published1; ?>></label>
							<label class="radio">Tắt<input type="radio" name="published" id="published2" value="0" <?php echo $published2; ?>></label><br>
						</li><br>
						<li>
							<label class="con_form_label">Cha Chủ đề bài viết</label>
							<select name="parent" id="parent">
								<option value="">Lựa chọn chủ đề </option>
								<?php
									
									foreach($this->category as $catories){
										if($catories['id']==$this->loaditem['parent']){$selected="selected";}else{$selected="";}
										echo"<option value='".$catories['id']."' ".$selected.">".$catories['title']."</option>";
									}
								?>
							</select><br>
						</li>
						<li>
							<label class="con_form_label">Nội dung chính</label><br><br>
							<div class="editor">
							<textarea cols="80" id="description" name="description" rows="10"><?php echo str_replace('\\',"",$this->loaditem['description']);?></textarea>
							
							</div>
						</li><br>
						<li>
							<label class="con_form_label">Metadesc</label>
							<textarea name="metadesc" id="metadesc" rows="3" ></textarea>
						</li><br>
						<li>
							<label class="con_form_label">Metakey</label>
							<textarea name="metakey" id="metakey" rows="3" ></textarea>
						</li><br>
						<li>
							<label class="con_form_label">Metadata</label>
							<textarea name="metadata" id="metadata" rows="3" ></textarea><br>
						</li>
					</ul>
				</fieldset>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" id="id" value="<?php echo $this->loaditem['id']; ?>" />
</form>
<!--Script editor -->
<script type="text/javascript">
                        
                        CKEDITOR.replace( 'description',
                        {
                            filebrowserBrowseUrl :'http://ngoisaoonline.vn/libs/ckeditor/filemanager/browser/default/browser.html?Connector=http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserImageBrowseUrl : 'http://ngoisaoonline.vn/libs/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserFlashBrowseUrl :'http://ngoisaoonline.vn/libs/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserUploadUrl  :'http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/upload.php?Type=File',
                            filebrowserImageUploadUrl : 'http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
                            filebrowserFlashUploadUrl : 'http://ngoisaoonline.vn/libs/ckeditor/filemanager/connectors/php/upload.php?Type=Flash'
                        });

                        //]]>
                    </script>