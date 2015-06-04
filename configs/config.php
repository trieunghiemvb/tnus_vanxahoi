<?php
/**
 * @author HieuBD
 * @version 1.0
 * @since 18/11/2014
 */
if ( !defined('AREA') ) { die('Access denied'); }
// bộ nhớ tối đa
@ini_set('memory_limit', '48M');
// thời gian tối đa thực hiện script
@set_time_limit(3600);
// set múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Định nghĩa thông số cơ sở dữ liệu
define("DB_TYPE","mysql");// Loại CSDL ex: mysql, sqlserver, oracle, vv..
define("DB_HOSTNAME","localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD","");
define("DB_DATABSE","db_tnus_khoavan");
define("DB_TABLE_PREFIX","tnus_");
// Định nghĩa URL
define("HTTP_HOST","http://localhost/projects");
define("HTTP_PATH","");
?>