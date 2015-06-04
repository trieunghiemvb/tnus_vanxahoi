<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('default_charset', 'UTF-8');
// Định nghĩa các hằng số hệ thống
define('AREA','A'); // Định nghĩa vùng truy cập dành cho Admin
define('INDEX','admin.php'); // Định nghĩa tên file đang request
$php_value = phpversion();
if (version_compare($php_value, '5.2.0') == -1) {
    echo 'Currently installed PHP version (' . $php_value . ') is not supported. Minimal required PHP version is  5.3.0.';
    die();
}
require dirname(__FILE__) . '/init.php'; // file chứa các định nghĩa thông số cơ bản

// Chạy module chính
//System::dispatch("main",false);
System::dispatch("main",true);
?>