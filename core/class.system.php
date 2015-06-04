<?php
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * @author HieuBD
 * Started 2014-16-11
 * Class hệ thống
 */
class System extends TNUSObject{
	public function __construct(){
		parent::__construct();
	}

        /** @author HieuBD
         * Hàm xử lý hiển thị nội dung trang web
         * @param <string> $default_m : modul mặc định
         * @param <boolean> $is_auth: tham số kiếm tra trạng thái đăng nhập.
         *                                  Mặc định = false (chưa đăng nhập)
         * @param <string> $default_task: tác vụ mặc định
         */
	public static function dispatch($default_m,$is_auth=false,$default_task="display"){	
		$app=null;

                // truy cập vào vùng yêu cầu đăng nhập nhưng User chưa đăng nhập
		if($is_auth==true && empty($_SESSION["auth"]["id_user"])){
			// Chuyển tới App đăng nhập
			$app="auth";
		}
		else{
                        // Lấy app cần xử lý
			$app=empty($_REQUEST["app"])?$default_m:$_REQUEST["app"];
		}

		// xác định action trong app
		$task=empty($_REQUEST["act"])?$default_task:$_REQUEST["act"];
                // đường dẫn đến app, không chứa / cuối
		define("DIR_MODULE",DIR_ROOT.DS.DIR_APP.DS.$app);
		define("URI_MODULE",HTTP_HOST.HTTP_PATH."/".DIR_APP."/".$app);
		// kiểm tra app có tồn tại
		if (!file_exists(DIR_MODULE.DS.$app.".php")) {
			die("Không tìm thấy App.");
		}
		// nạp file chính của App
		require_once(DIR_MODULE.DS.$app.".php");
		// kiểm tra có Class theo đúng cấu trúc: ExampleApp với Example là tên App
		$class_app=$app."App";// kiểm tra có Class theo đúng cấu trúc: ExampleApp với Example là tên App
		if (!class_exists($class_app)) {
			die("Không tìm thấy Class chính.");
		}
		// khởi tạo đối tượng của App
		$obj = new $class_app;
		// thực thi công việc
		if(!method_exists($obj,$task)) $task=$default_task; // nếu method không tồn tại thì gọi mặc định
		$obj->$task();
	}
	
}
?>