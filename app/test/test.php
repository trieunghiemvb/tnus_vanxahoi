<?php

/**
 * App kiểm tra function
 * @author HieuBD <buiduchieuvnu@gmail.com>
 * @version 1.0
 */
session_start();
ob_start();

if (!defined('AREA')) {
    die('Access denied');
}
?>
<?php

class TestApp extends AppObject {

    public $app_name = "test";
    public $dir_layout = "backend"; // thu m?c ch?a c�c layout
    public $page_title = "App test";
    public $testCotent = "";

    public function __construct() {
        parent::__construct();
    }

    public function display() {
        $task = "";
        if (isset($_REQUEST['task'])) {
            $task = $_REQUEST['task'];
        }
        TestApp::views();
    }

    /**
     * Xuất view
     * @return [type] [description]
     */
    public function views() {
        $this->dir_layout = "backend";
        $this->layout = "default";
        $this->view = "default";
//        $this->testCotent = TestApp::get_slide();
//        $this->testCotent = TestApp::get_home_banner();
//        $this->testCotent = TestApp::get_arr_home_cate();
//        $this->testCotent = TestApp::get_arr_news_contents(50);
//        $this->testCotent = TestApp::get_most_views_content(array(50, 51, 52, 53), 5);
//        $this->testCotent = TestApp::get_content_by_cat_id(59, 5);
//        $this->testCotent = TestApp::get_content(1264);
//        $this->testCotent = TestApp::update_hits(1310);
        $this->testCotent = TestApp::get_other_news(1310);
        parent::display();
    }

    /**
     * Hàm lấy danh sách tin liên quan
     * @param int $id
     * @param int $num
     * @return array
     */
    public function get_other_news($id, $num=4) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = " SELECT *,`introtext` FROM $table  
                WHERE cat_id=(SELECT cat_id from $table WHERE id=$id)
                AND id<>$id
                LIMIT $num";
        $arr_news = $db->queryAll($sql, array());
        return $arr_news;
    }

    /**
     * Hàm cập nhật lượt xem (hits) của content
     * @param int $id
     * @return bôlean
     */
    public static function update_hits($id) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "SELECT  `hits` FROM $table WHERE id=$id";
        $rs = $db->query($sql);
        $hits = $rs["hits"] + 1;
        $sql = "  UPDATE $table SET `hits`=$hits";
        $sql .= " WHERE id = $id ";
        return $db->query($sql);
    }

    /**
     * Hàm lấy chi tiết content theo id
     * @param int $id
     * @return obj
     */
    public function get_content($id) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "  SELECT *,`introtext` FROM $table ";
        $sql .= " WHERE id = $id ";
        $obj_news = $db->queryAll($sql, array(), PDO::FETCH_OBJ);
        $this->update_hits($id);
        return $obj_news[0];
    }

    /**
     * Hàm lấy mảng content có số lượt xem (hits) giảm dần
     * @param array $arr_cat_id Danh sách cat_id
     * @param int $num Số lượng content cần lấy
     * @return array Mảng content
     */
    public static function get_content_by_cat_id($cat_id, $num) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "  SELECT *,`introtext` FROM $table ";
        $sql .= " WHERE cat_id = $cat_id ";
        $sql .= " ORDER BY id DESC ";
        $sql .= " LIMIT $num ";
        $arr_news = $db->queryAll($sql);
        return $arr_news;
    }

    /**
     * Hàm lấy mảng content có số lượt xem (hits) giảm dần
     * @param array $arr_cat_id Danh sách cat_id
     * @param int $num Số lượng content cần lấy
     * @return array Mảng content
     */
    public static function get_most_views_content($arr_cat_id, $num) {
        $db = new Database();
        $str_cat_id = implode(",", $arr_cat_id);
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "  SELECT *,`introtext` FROM $table ";
        $sql .= " WHERE cat_id IN ($str_cat_id) ";
        $sql .= " ORDER BY `hits` DESC ";
        $sql .= " LIMIT $num ";
        $arr_news = $db->queryAll($sql);
        return $arr_news;
    }

    public function get_arr_news_contents($cat_id, $start = 0, $limit = 6) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "  SELECT *,`introtext` FROM $table ";
        $sql .= " WHERE cat_id=$cat_id ";
        $sql .= " LIMIT $start, $limit ";
        $arr_news = $db->queryAll($sql);
//        $arr_news["introtext"] = str_replace('<p>', '', $arr_news["introtext"]);
//        $arr_news["introtext"] = str_replace('</p>', '', $arr_news["introtext"]);
        return $arr_news;
    }

    /**
     * Hàm trả về danh sách sách các đối tượng cate
     * @return array_object Ex: Array
      (
      [0] => stdClass Object
      (
      [id] => 50
      [cat_title] => Nissan
      [con_id] => 1318
      [con_title] => Nissan Việt Nam gặp rắc rối do nợ thuế
      [introtext] => Nếu không nhanh chóng đóng bù thuế nhập khẩu linh kiện,
      )
      )
     */
    private function get_arr_home_cate() {
        $db = new Database();
        $arr_cate_id = $this->get_arr_home_cate_id();
        $arr_home_cate = array();
        foreach ($arr_cate_id as $cat_id) {
            $home_cate = new stdClass();
            $cate = $db->getRow("categories", "*", array("id" => $cat_id));
            $home_cate->id = $cate["id"];
            $home_cate->cat_title = $cate["title"];
            // Get last content
            $table = DB_TABLE_PREFIX . 'content';
            $sql = "SELECT id,title,introtext,`fulltext`
                    FROM
                    $table
                    WHERE id = (select MAX(id) FROM $table WHERE cat_id = $cat_id)";
            $arr_rs = $db->query($sql, array(":cat_id" => $cat_id));
            $home_cate->con_id = $arr_rs["id"];
            $home_cate->con_title = $arr_rs["title"];
            $home_cate->con_introtext = $arr_rs["introtext"];
            $home_cate->con_img = $this->get_url_first_image($arr_rs["fulltext"]);
            array_push($arr_home_cate, $home_cate);
        }
        return $arr_home_cate;
    }

    /**
     * Hàm trả về danh sách các chuyên mục trình bày tại trang chủ
     * @return array
     */
    private function get_arr_home_cate_id() {
        $db = new Database();
        $arr_rs = $db->getRows("references", "`values`", array("name" => "home_cate"));
        return json_decode($arr_rs[0]["values"]);
    }

    private function get_home_banner() {
        $db = new Database();
        $arr_rs = $db->getRows("references", "`values`", array("name" => "home_ad_ngang"));
        return json_decode($arr_rs[0]["values"]);
    }

    private function get_slide() {
        $db = new Database();
        $arr_slide = array();
        $arr_id = implode(",", $this->get_home_slide());
        $arr_content = $db->getRows("content", "id,title,`fulltext`", " id IN ($arr_id)");
        return $arr_content;
    }

    private function get_home_slide() {
        $db = new Database();
        $arr_rs = $db->getRows("references", "`values`", array("name" => "home_slide"));
        return json_decode($arr_rs[0]["values"]);
    }

    /**
     * Hàm lấy url hình ảnh đầu tiên của bài viết
     * @param  string
     * @return string
     */
    public static function get_url_first_image($content) {
        $first_img = '';
        ob_start();
        ob_end_clean();
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $first_img);
        if (empty($first_img)) {
            ////Defines a default image
            $first_img = "/images/default.jpg";
            return $first_img;
        } else {
            return $first_img[1];
        }
    }

}

?>