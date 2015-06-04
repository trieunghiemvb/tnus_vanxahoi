<?php

class TrangchuApp extends AppObject {

    public $app_name = "trangchu";
    public $dir_layout = "frontend"; // thư mục chứa các layout
    public $arr_slide;

    public function __construct() {
        parent::__construct();
    }

    public function display() {
        $this->hienthi_trangchu();
        //User::checkPerm(array("test","manage_user"));
        $this->view = null;
        //echo '<pre>';print_r($_SESSION);

        parent::display();
    }

    public function hienthi_trangchu() {
        $this->page_title = "KHOA VĂN XÃ HỘI - TRƯỜNG ĐẠI HỌC KHOA HỌC - ĐH THÁI NGUYÊN";
        $this->dir_layout = "frontend";
        $this->layout = "default";
        $this->view = "default";
        $this->arr_slide = $this->get_slide();
        $this->obj_ad = $this->get_obj_home_banner();
        $this->arr_home_cate = $this->get_arr_home_cate();
        parent::display();
    }

    /**
     * Hàm trả về danh sách sách các đối tượng cate
     * @return array_object Ex: Array
      ([0] => stdClass Object(
            [id] => 50
            [cat_title] => Nissan
            [con_id] => 1318
            [con_title] => Nissan Việt Nam gặp rắc rối do nợ thuế
            [introtext] => Nếu không nhanh chóng đóng bù thuế nhập khẩu linh kiện,
      ))
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
            $arr_rs["introtext"] = str_replace('<p>','', $arr_rs["introtext"]);
            $arr_rs["introtext"] = str_replace('</p>','', $arr_rs["introtext"]);
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

    /**
     * Hàm trả về thông tin banner ngang
     * @return object Ex:stdClass Object([img] => http://localhost/projects/tnus_vanxahoi/media/images/ad/banner_ngang.jpg,[url] => #)
     */
    private function get_obj_home_banner() {
        $db = new Database();
        $arr_rs = $db->getRows("references", "`values`", array("name" => "home_ad_ngang"));
        return json_decode($arr_rs[0]["values"]);
    }

    /**
     * Hàm lấy danh sách slide item
     * @return array Ex: array([0]=>array('id'=>0,'title'=>'tiêu đề', 'fulltext'=>'nội dung'))
     */
    private function get_slide() {
        $db = new Database();
        $arr_slide = array();
        $arr_id = implode(",", $this->get_home_slide());
        $arr_content = $db->getRows("content", "id,title,`fulltext`", " id IN ($arr_id)");
        return $arr_content;
    }

    /*     * *
     * Hàm lấy danh sách bài viết nằm trong slide
     * @param string
     * @return json array
     */

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
            $first_img = DIR_ROOT . DS ."media/images/web/no_images.png";
            return $first_img;
        } else {
            return $first_img[1];
        }
    }

}

?>