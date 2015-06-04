<?php
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Start 2014-11-16
 * @author: HieuBD
 * @class: core gốc set các thông số gốc của hệ thống
 */
class Core{
	public function __construct(){
		
	}

        /**
         * by HieuBD
         * @global <string> $message: biến toàn cục lưu thông báo hệ thống
         * @param <string> $title: Tiêu đề thông báo
         * @param <string> $msg: Nội dung thông báo
         * @param <string> $type: Loại thông báo. ("info","error","success")
         */
	public static function setMsg($title,$msg,$type="success"){
		$types=array("info","error","success");
		if(!in_array($type,$types)) $type="warning";
                $_SESSION['messeage']="<div class='alert alert-$type fade in'><strong>$title:</strong> $msg</div>";
	}

        public static  function showMsg(){
            echo $_SESSION['messeage'];
            $_SESSION['messeage']="";
        }
}
?>