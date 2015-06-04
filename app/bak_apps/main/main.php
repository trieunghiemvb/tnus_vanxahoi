<?php

if ( !defined('AREA') ) { die('Access denied'); }
?>
<?php
class MainApp extends AppObject{
	public $app_name="main";
	public $dir_layout="backend"; // thư mục chứa các layout
	public function __construct(){
		parent::__construct();
	}
	public function display(){
		$this->dir_layout="backend";
		$this->layout="default";
		$this->view="default";
		parent::display();
	}
}
?>