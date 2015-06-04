<?php
/**
 * @author HieuBD
 * @version 1.0
 * @since 18/11/2014
 */

if ( !defined('AREA') ) { die('Access denied'); }
session_regenerate_id();
define('TIME', time());
define('MICROTIME', microtime(true));
if (stristr(PHP_OS, 'WIN')) {// Define operation system
	define('IS_WINDOWS', true);
}
define('DIR_ROOT', dirname(__FILE__)); 
define('DS',DIRECTORY_SEPARATOR);
define('DIR_CONFIG','configs');// thư mục chứa file config
define('DIR_CORE','core');// thư mục chứa class core
define('DIR_APP','app');// thư mục chứa các module
define('DIR_LIBS','libs');// thư mục chứa các thư viện ngoài
define('DIR_LAYOUT','skins');// thư mục chứa các layout


// Tải file cấu hình
require(DIR_ROOT.DS.DIR_CONFIG.DS.'config.php');
// khai báo một số biến toàn cục
require(DIR_ROOT.DS.DIR_CONFIG.DS.'global.php');
// tải thư viện
require(DIR_ROOT.DS.DIR_CORE.DS.'core.php');

?>