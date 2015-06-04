<?php
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * @author HieuBD
 * Class đối tượng kế thừa từ lớp Database
 * @name TNUS: Thai Nguyên University of Sciences
 */
class TNUSObject extends Database{

        /**
         * Hàm khởi tạo gốc
         */
	public function __construct(){
		parent::__construct();
	}
}
?>