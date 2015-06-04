<?php
if ( !defined('AREA') ) { die('Access denied'); }
?>
<link href="<?php echo AppObject::getBaseFile('app/auth/css/style.css')?>" rel="stylesheet" media="screen">

<script>
	function check(){
		
		var title=document.getElementById("name").value;
		var pass=document.getElementById("pass").value;
		if(title==""){
			alert("Bạn phải nhập Tên ");
			document.getElementById("name").style.border="1px solid red";
			window.scroll(0,findPos(document.getElementById("name")));
			return false;
		}else if(pass==""){
			alert("Bạn phải nhập Mật khẩu ");
			document.getElementById("pass").style.border="1px solid red";
			window.scroll(0,findPos(document.getElementById("pass")));
			return false;
		}
		return true;
		
	}
	function checkdata(){
		
		if(check()==true){
			document.getElementById("category_form").submit();
		}
	}

</script>

<div id="con_title">
	<div class="con_title"><h3>Chỉnh sửa Tên và Mật khẩu</h3></div>
	<div class="con_description"><strong>Thực hiện chỉnh sửa Tên và Mật khẩu của bạn</strong></div>
</div>
<?php /************/ ?>
<form action="" method="post" class="form-search" id="category_form">
	<div id="con_content">
		<div id="con_toonbar">
		
			<div class="con_toonbar">
			
				<input type="button" onclick="checkdata()" value="Lưu"  class="btn ">
				
			</div>
			
		</div>	
		<?php global $message; echo $message;?>
		<div class="con_content">
			<div class="con_form">
				<fieldset>
					<legend>Nội dung</legend>
					<ul>
						<li>
							<label class="con_form_label">Tên Quản Trị</label>
							<input type="text" placeholder="Tên Quản Trị" name="name" id="name" value="<?php echo $this->loaduser['name']; ?>"><br>						
						</li><br>
						<li>
							<label class="con_form_label">Mật Khẩu</label>
							<input type="password" placeholder="Mật khẩu" name="pass" id="pass" value="">
						</li><br>		
					</ul>
				</fieldset>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" id="id" value="<?php echo $_SESSION["auth"]["id_user"]; ?>" />
</form>
<!--Script editor -->