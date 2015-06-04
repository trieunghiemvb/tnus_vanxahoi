<?php

define("DB_TYPE","mysql");// Loại CSDL ex: mysql, sqlserver, oracle, vv..
define("DB_HOSTNAME","localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD","");
define("DB_DATABSE","db_mobileshop");
define("DB_TABLE_PREFIX","");

define('AREA','C');
require './class.core.php';
require './class.database.php';



$db = new Database();
$db->init("mysql", "localhost", "root", "", "db_mobileshop");

$arr_product = $db->getRows("product", "id,name,price", "");

//var_dump($arr_product);

$obj_product = $db->getRows("product", "id,name,price", "",  PDO::FETCH_OBJ);
var_dump($obj_product);