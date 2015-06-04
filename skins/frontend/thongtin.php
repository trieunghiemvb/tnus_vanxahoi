<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<head prefix="og: http://ogp.me/ns# fb">
<meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID ?>" />
<meta property="og:url" content="<?php echo HTTP_HOST.HTTP_PATH; ?>index.php?app=thongtin_chitiet&act=hienthi_thongtin_chitiet&ma_dm=<?php echo $_REQUEST['ma_dm'] ?>&id=<?php echo $_REQUEST['id']; ?>">
<meta property="og:title" content="<?php echo strip_tags(str_replace('\"',"",$this->thongtin['title'])); ?>" />
<meta 
<?php 
	$y = strip_tags(str_replace('\"',"",$this->thongtin['introtext']));
 ?>
<meta property="og:description" content="<?php echo $y; ?>" />
<meta property="og:type" content="website" />
<?php 
	$x = str_replace('\"',"",$this->thongtin['fulltext']);
	preg_match("/<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)>/siU",$x, $k);
						$anh = $k[2];
 ?>
<meta property="og:image" content="<?php echo $anh; ?>" />
<meta property="og:site_name" content="http://oto360.net/" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="keywords" content="<?php echo $this->thongtin['metakey']?>"/>
<meta name="description" content="<?php echo $this->thongtin['metadesc']?>"/>

<link href="css/CSS.css" rel="stylesheet" type="text/css" />
<link href="css/cssthitruong.css" rel="stylesheet" type="text/css" />
<meta name="description" content="oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video." />
<title>oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video.</title>
</head>
<body>
<script type="text/javascript">
function hide_float_left() {
  var content = document.getElementById('float_content_left');
  var hide = document.getElementById('hide_float_left');
  if (content.style.display == "none")
  	{content.style.display = "block"; hide.innerHTML = '<a href="javascript:hide_float_left()">Tắt quảng cáo [X]</a>'; }
  else { content.style.display = "none"; hide.innerHTML = '<a href="javascript:hide_float_left()">Xem quảng cáo...</a>';
  	}
}
</script>
<script type="text/javascript">
function hide_float_right() {
  var content = document.getElementById('float_content_right');
  var hide = document.getElementById('hide_float_right');
  if (content.style.display == "none")
  	{content.style.display = "block"; hide.innerHTML = '<a href="javascript:hide_float_right()">Tắt quảng cáo [X]</a>'; }
  else { content.style.display = "none"; hide.innerHTML = '<a href="javascript:hide_float_right()">Xem quảng cáo...</a>';
  	}
}
</script>



	<div id="all_web">
    	<div id="header">
        	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/logo.php');?>
        </div>  <!------------------ ALL header --------------------->
        <div id="menu">
          	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/menu.php');?>
    	</div><!-- -____________________________  End.menu________________________  -->
    <div class="clear"></div>
        <div class="height5px"></div>
         <div class="height5px"></div>
    <div id="content">
    	<!-------------QUANG CAO 2 BEN --------------->
            	<div style="position:fixed; margin-left: 513px; left:53%;z-index:999;top:10px;">
                      <a href="http://www.99thang.com/?Intr=134505" onClick="return openLink(this.href)">
                      	  <?php echo str_replace('\"','"',$this->ria_phai_chitiet['anh']); ?>
                      </a>
                </div><!------------------------END quang cao ria phai--------------------------------->
                <div style="position:fixed; margin-right: 513px; right:53%;z-index:999;top:10px;">
                	  <a href="http://www.99thang.com/?Intr=134505" onClick="return openLink(this.href)">
                      	  <?php echo str_replace('\"','"',$this->ria_trai_chitiet['anh']); ?>
                      </a>
                </div><!------------------------END quang cao ria trai--------------------------------->
    
    			<!-------------------ECHO Content---------------------------->
        
    	<div>
    	<div class="content_left">
      	 	 	<?php echo $content ?>
        </div>   <!------------------ END Content_left --------------------->
        <div class="content_right">
        		<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/right_thongtin.php');?>
        </div> <!------------------END_RIGHT--------------------->
        </div>
    </div>  <!------------------END_CONTENT--------------------->
    
     <div class="clear"></div>
        <div class="5px"> &nbsp;</div>
        	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/tag.php');?>
        <div class="clear"></div>
        <div id="footer">
        	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/footer.php');?>
        </div>  <!------------------ END FOOTER --------------------->
    </div> <!------------------END_ALLWWEB--------------------->
    
    
    
<?php
	if(!empty($this->goc_trai_chitiet['anh'])){ ?>
<div class="float-ck" style="left: 0px">
  <div id="hide_float_left">
  		<a href="javascript:hide_float_left()" onClick="return openLink(this.href)">Tắt Quảng Cáo [X]</a>
  </div>
  <div id="float_content_left" style="z-index:9999;">
      <p>
          <a href="http://betbongda.net/" onClick="return openLink(this.href)">
         	 <?php echo str_replace('\"','"',$this->goc_trai_chitiet['anh']); ?>
          </a>
      </p>
  </div>
</div><!------------------------END goc_trai_home--------------------------------->

<?php	}
?>


<?php
	if(!empty($this->goc_phai_chitiet['anh'])){ ?>
		<div class="float-ck" style="right: 0px">
  <div id="hide_float_right">
  		<a href="javascript:hide_float_right()" onClick="return openLink(this.href)">Tắt Quảng Cáo [X]</a>
  </div>
  <div id="float_content_right" style="z-index:9999;">
      <p>
          <a href="http://betbongda.net/" onClick="return openLink(this.href)">
         	 <?php echo str_replace('\"','"',$this->goc_phai_chitiet['anh']); ?>
          </a>
      </p>
  </div>
</div><!------------------------END goc_phai_home--------------------------------->		
<?php	}
?>    
</body>
</html>
