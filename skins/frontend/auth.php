<?php
if ( !defined('AREA') ) { die('Access denied'); }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=$meta_keywords?>">
<meta name="description" content="oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video." />
<title>oto360.Net - Tạp chí ô tô - xe máy trực tuyến cập nhật các tin tức mới nhất về xe ôtô, xe máy, giá cả thị trường, đánh giá, hởi đáp, tư vấn, video.</title>
<link href="<?php echo AppObject::getBaseFile('libs/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet" media="screen">
<link href="<?php echo AppObject::getBaseFile('css/base.css')?>" rel="stylesheet" media="screen">
</head>
<body>
	<div id="main-wrapper">
		<?php echo $content;?>
	</div>
</body>
</html>