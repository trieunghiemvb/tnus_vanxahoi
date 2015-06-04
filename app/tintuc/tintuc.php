<?php

class TintucApp extends AppObject {

// <editor-fold defaultstate="collapsed" desc="Properties & construct">
    public $app_name = "tintuc";
    public $dir_layout = "frontend"; // thư mục chứa các layout
    public $limitstart;
    public $limit;
    public $total;
    public $where;
    public $numOfPage;
    public $query;
    public $pagination;
    public $arr_slide;

    public function __construct() {
        parent::__construct();
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code app">
    public function display() {
        $view = isset($_REQUEST['view']) ? $_REQUEST['view'] : "danhsach";
        switch ($view) {
            case "danhsach":
                $this->hienthi_danhsach();
                break;
            case "chitiet":
                if (isset($_REQUEST['id'])) {
                    $this->hienthi_chitiet($_REQUEST['id']);
                } else {
                    $this->hienthi_danhsach();
                }
                break;
            default:
                $this->hienthi_danhsach();
                break;
        }


        //User::checkPerm(array("test","manage_user"));
        $this->view = null;
        //echo '<pre>';print_r($_SESSION);

        parent::display();
    }

    public function hienthi_chitiet($id) {
        $this->page_title = "TIN TỨC";
        $this->dir_layout = "frontend";
        $this->layout = "news";
        $this->view = "chitiet";
        $this->arr_most_view = $this->get_most_views_content(array(50, 51, 52, 53, 54), 5);
        $this->arr_event = $this->get_content_by_cat_id(59, 5);
        $this->news = $this->get_content($id);
        $this->arr_other_news = $this->get_other_news($id, 3);
        parent::display();
    }

    public function hienthi_danhsach() {
        $this->page_title = "TIN TỨC";
        $this->dir_layout = "frontend";
        $this->layout = "news";
        $this->view = "danhsach";
        $this->arr_slide = $this->get_slide();
        $this->arr_news = $this->get_arr_news_contents(59);
        $this->arr_most_view = $this->get_most_views_content(array(50, 51, 52, 53, 54), 5);
        $this->arr_event = $this->get_content_by_cat_id(59, 5);
        parent::display();
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code xử lý chung">
    /**
     * Hàm cắt văn bản ko mất từ, nối thêm dấu
     * @param string $input text to trim
     * @param int $length in characters to trim to
     * @param bool $ellipses if ellipses (...) are to be added
     * @param bool $strip_html if html tags are to be stripped
     * @return string 
     */
    public static function trim_text($input, $length, $ellipses = true, $strip_html = true) {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code view tin tức">
    /**
     * Hàm lấy mảng- content có số lượt xem (hits) giảm dần
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

    /**
     * Hàm lấy danh sách slide item
     * @return array Ex: array([0]=>array('id'=>0,'title'=>'tiêu đề', 'fulltext'=>'nội dung'))
     */
    private function get_slide() {
        $db = new Database();
        $arr_slide = array();
        $arr_id = implode(",", $this->get_home_slide());
        $arr_content = $db->getRows("content", "id,title,`fulltext`", " id IN ($arr_id)");
//        print_r($arr_content);
        return $arr_content;
    }

    /**
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
            $first_img = DIR_ROOT . DS . "media/images/web/no_images.png";
            return $first_img;
        } else {
            return $first_img[1];
        }
    }

    /**
     * Hàm lấy danh sách tin tức
     * @param type $cat_id
     * @param type $start
     * @param type $limit
     * @return type
     */
    public function get_arr_news_contents($cat_id) {
        $db = new Database();
        TintucApp::getQuery($cat_id);
        TintucApp::setPagination();
        $limit = $this->limit;
        $start = $this->limitstart;
//        echo "Limit: $limit; Start:$start; Page: $page";
        $table = DB_TABLE_PREFIX . 'content';
        $sql = "  SELECT *,`introtext` FROM $table ";
        $sql .= " WHERE cat_id=$cat_id ";
        $sql .= " ORDER BY id DESC ";
        $sql .= " LIMIT $start, $limit ";
        $arr_news = $db->queryAll($sql);

        $this->pagination = TintucApp::getPagination();
        return $arr_news;
    }

    public function setPagination() {
        TintucApp::getTotal();
        $total = $this->total;
//        echo "Total" . $total;
        $this->limit = $_REQUEST["perPage"];

        if (empty($this->limit)) {
            $this->limit = 6;
        }
        $this->numOfPage = ceil($total / $this->limit);
        if (empty($_REQUEST['page'])) {
            $this->limitstart = 0;
        } else {
            if ($this->limit >= $total) {
                $this->limitstart = 0;
            } else {
                $this->limitstart = ($_REQUEST['page'] - 1) * $this->limit;
            }
        }
        return true;
    }

    /*
      PhĂ¢n trang
      @return:chuá»—i html
     */

    public function getPagination() {

        if (!empty($_REQUEST["search"])) {
            $search = '&search=' . $_REQUEST["search"];
        } else {
            unset($_REQUEST["search"]);
        }
        if (!empty($_REQUEST["catid"])) {
            $catid = '&catid=' . $_REQUEST["catid"];
        } else {
            unset($_REQUEST["catid"]);
        }

        $html.="<div class='paging style2 clearfix'>";

        if (!empty($_REQUEST["page"]) && $_REQUEST["page"] % 3 == 0) {
            $page = $_REQUEST["page"] - 2;
            $dem = $this->numOfPage - ($_REQUEST["page"] + 3);
            if ($dem < 0) {
                $num = $this->numOfPage;
            } else {
                $num = $_REQUEST["page"] + 3;
            }
        } else {
            $page = $_REQUEST["page"] - 3;
            if ($page < 1)
                $page = 1;

            $dem = $this->numOfPage - ($_REQUEST["page"] + 3);
            if ($dem < 0) {
                $num = $this->numOfPage;
            } else {
                $num = $_REQUEST["page"] + 3;
            }
        }

        $html.="<ul>";
        $html.="<ul>";
        if ($_REQUEST['page'] > 5) {
            $html.="<a  href='?app=tintuc" . $catid . "&page=1" . $search . "'><<</a>";
        }

        for ($i = $page; $i <= $num; $i++) {
            if ($i == $_REQUEST['page']) {
                $html.="<a class='current' href='?app=tintuc" . $catid . "&page=$i" . $search . "'>$i</a>";
            } else {
                $html.="<a href='?app=tintuc" . $catid . "&page=$i" . $search . "'>$i</a>";
            }
        }
        if ($_REQUEST['page'] < 5) {
            $html.="<a  href='?app=tintuc" . $catid . "&page=$this->numOfPage" . $search . "'>>></a>";
        }

        $html.="</ul>";
        $html.="</div>";
        return $html;
    }

    /*
      get query
     */

    public function getQuery($cat_id) {
        if (empty($this->query)) {
            $db = new Database();
            $table = "content";
            $field = "*";
            $this->query = $db->getRows($table, $field, " cat_id=$cat_id ");
        }
        return $this->query;
    }

    /*
      Lấy tổng số tin
      @param:
      @return: tá»•ng sá»‘ dá»¯ liá»‡u tráº£ vá».
     */

    public function getTotal() {
        if (empty($this->total)) {
            $this->total = count($this->query);
        }
        return $this->total;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Code view chitiet">

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
     * Hàm lấy danh sách tin liên quan
     * @param int $id
     * @param int $num
     * @return array
     */
    public function get_other_news($id, $num = 4) {
        $db = new Database();
        $table = DB_TABLE_PREFIX . 'content';
        $sql = " SELECT *,`introtext` FROM $table  
                WHERE cat_id=(SELECT cat_id from $table WHERE id=$id)
                AND id<>$id ORDER BY id DESC
                LIMIT $num";
        $arr_news = $db->queryAll($sql, array());
        return $arr_news;
    }

// </editor-fold>
}

?>