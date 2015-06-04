<?php
if ( !defined('AREA') ) { die('Access denied'); }
/*
Tải các thư viện mã nguồn trong thư mục core
*/
require(DIR_ROOT.DS.DIR_CORE.DS.'class.core.php');
require(DIR_ROOT.DS.DIR_CORE.DS.'class.database.php');
require(DIR_ROOT.DS.DIR_CORE.DS.'class.object.php');
require(DIR_ROOT.DS.DIR_CORE.DS.'class.system.php');
require(DIR_ROOT.DS.DIR_CORE.DS.'class.user.php');
require(DIR_ROOT.DS.DIR_CORE.DS.'class.app.php');
// kết nối đến cơ sở dữ liệu
?>