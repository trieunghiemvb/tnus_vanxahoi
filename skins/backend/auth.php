<?php
if ( !defined('AREA') ) { die('Access denied'); }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />
	<meta name="keywords" content="<?=$meta_keywords?>">
	<meta name="description" content="<?=$meta_desc?>">
	<title><?=$page_title?></title>
	<!-- Bootstrap CSS -->
	<link href="<?php echo AppObject::getBaseFile('libs/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet" media="screen">
	<!-- Custom Style -->
	<link href="<?php echo AppObject::getBaseFile('public/css/login.css')?>" rel="stylesheet" media="screen">
</head>
<body>
	<div class="container">
		<?php echo $content; ?>
	</div>

	<!-- Javascripts file at bottom to increase page load speed -->
	<script src="<?php echo AppObject::getBaseFile('public/js/jquery.js')?>"></script>
	<script src="<?php echo AppObject::getBaseFile('libs/bootstrap/js/bootstrap.min.js')?>"></script>
</body>
</html>