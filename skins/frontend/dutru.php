<?php
if ( !defined('AREA') ) { die('Access denied'); }
?>
<!DOCTYPE html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/CSS.css" rel="stylesheet" type="text/css" />
<link href="css/cssthitruong.css" rel="stylesheet" type="text/css" />
<meta name="description" content="oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video." />
<title>oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video.</title>
</head>
<body>
	<div id="all_web">
    	<div id="header">
        	<div class="head_left"><a href="<?php echo INDEX ?>"><img src="media/mediaskins/1365138700_admin_logo-otozine.png" /></a></div>
            <div class="head_right"><img src="media/mediaskins/1373561794_1_banner-thiet-ke-website-saigon-soft.png" /></div>
        </div>  <!------------------ ALL HEADER --------------------->
        
        <div id="menu">
          <div class="mainMenu">
              <ul class="topMenu">
                	<?php include 'app/nguoidung/views/inc_menu.php' ?>
              </ul>
          </div>
    </div><!-- -____________________________  End.menu________________________  -->
        <div class="clear"></div>
       
        <div class="height5px"> &nbsp; </div>
        <div class="clear"></div>
        <div id="content">
        	<div id="content_left">
            	<?php include'app/nguoidung/views/inc_picsh.php' ?>
                <div id="categori_show">
                	<div class="h3">
                    	<h3 class="hmp-cate-maintitle">
                    		<span><a class="a-maintitle" href="#" style="color:#FFF;">TIN MỚI NHẤT</a></span>
                    	</h3>
                    </div>          <!---------------------HIEN THI TIEU DE-------------------->
                    <ul class="ul_hmp-cate-hot">
                    	<?php include'app/nguoidung/views/inc_tinmoi_r.php' ?>              
                    </ul>
                </div>  <!----------------------------------categori_show---------------------------->
                			<div class="clear"></div>
                			<div class="height3px"> &nbsp; </div>
                <div id="categoriNgang">
                			<div class="height5px"> &nbsp; </div>
                	<ul class="list_ngang">
                    	<?php include'app/nguoidung/views/inc_list_ngang.php' ?>
                    </ul>
                </div> <!----------------------------------categoriNgang---------------------------->
            </div>	<!------------------ CONTENT_LEFT --------------------->
            <div id="content_right">
            		<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/mod_right_home.php');?>
            </div>   	<!------------------ CONTENT_RIGHT --------------------->
        </div> <!------------------ END CONTENT --------------------->
        <div class="clear"></div>
        <div class="height5px"> &nbsp; </div>
        <!-------------------------------------------------------------------->
        <div id="content_duoi">
        	<div id="content_left2">
            	<?php include'app/nguoidung/views/inc_list_sp.php' ?>   
            </div><!------------------------------ content_left ------------------------------------------>
            <div id="content_right2">            	
                <?php include(DIR_ROOT.DS.'/skins/frontend/webpart/right_home.php');?>
            </div> <!------------------ content_phai --------------------->
        </div>     <!------------------ content_duoi --------------------->
        <div class="clear"></div>
        <div class="5px"> &nbsp;</div>
        	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/tag.php');?>
        <div class="clear"></div>
        <div id="footer">
        	<?php include(DIR_ROOT.DS.'/skins/frontend/webpart/footer.php');?>
        </div>  <!------------------ END FOOTER --------------------->
    </div>  <!------------------ ALL WEB --------------------->
   
</body>
</html>