<?php
if ( !defined('AREA') ) {
    die('Access denied');
}
/**
 * @author: HieuBD
 * Started 2014-11-16
 * Class: Xử lý đối tượng App
 */
class AppObject extends TNUSObject {
    public $app_name=null; // tên  thư mục app
    public $layout="default"; // tên file layout
    public $dir_layout="frontend"; // thư mục chứa các layout
    public $view="default"; // view hiển thị của app
    public $base_layout;// thư mục gốc chứa tất cả các layout
    public $page_title=null; // Tiêu đề của trang
    public $meta_keywords=null; // thẻ meta keyword của trang web
    public $meta_desc=null; // thẻ meta description của trang web

    public function __construct() {
        $this->base_layout=DIR_ROOT.DS.DIR_LAYOUT;
        parent::__construct();
    }
    public function display() {
        $app_object=new $this;
        if(empty($this->layout)) {
            if(!empty($this->view)) require(DIR_MODULE.DS."views".DS.$this->view.'.php');
        }else {
            ob_start();
            if(!empty($this->view))  require(DIR_MODULE.DS."views".DS.$this->view.'.php');
            $content = ob_get_contents();
            ob_end_clean();
            // gọi layout
            $page_title		=$this->page_title;
            $meta_keywords	=$this->meta_keywords;
            $meta_desc		=$this->meta_desc;
            require($this->base_layout.DS.$this->dir_layout.DS.$this->layout.'.php');
        }
        die;
    }
    public static function getBaseFile($path) {
        //return HTTP_PATH."/".$path;
        return HTTP_PATH."".$path;
    }
    public function getAppFile($path) {
        return HTTP_PATH."".DIR_APP."/".$this->app_name."/".$path;
    }

    /*
		set Pagination
    */
    public	function setPage($total=0,$limit=0) {

        if(empty($_REQUEST['page'])) {
            $limitstart = 0;
        }
        else {
            if($limit>=$total) {
                $limitstart = 0;
            }else {
                $limitstart = ($_REQUEST['page'] - 1) * $limit;
            }
        }
        return $limitstart;
    }
    /*
		get Pagination
    */
    public function getpage($total=0,$limit=0,$link="") {
        $numOfPage = ceil($total/$limit);
        if(!empty($_REQUEST["search"])) {
            $search='&search='.$_REQUEST["search"];
        }else {
            unset($_REQUEST["search"]);
        }
        $html.="<div class='paging style2 clearfix'>";

        if(!empty($_REQUEST["page"]) && $_REQUEST["page"]%4==0) {
            $page=$_REQUEST["page"]-3;
            $dem=$numOfPage-($_REQUEST["page"]+4);
            if($dem<0) {
                $num=$numOfPage;
            }else {
                $num=$_REQUEST["page"]+4;
            }
        }else {
            $page=$_REQUEST["page"]-3;
            if($page<1)$page=1;
            $dem=$numOfPage-($_REQUEST["page"]+4);
            if($dem<0) {
                $num=$numOfPage;
            }else {
                $num=$_REQUEST["page"]+4;
            }
        }

        if($_REQUEST['page']>5) {
            $html.="<a  href='".$link."page=1".$search."'><<</a>";

        }

        for($i=$page;$i<=$num;$i++) {
            if($i==$_REQUEST['page']) {
                $html.="<a class='current' href='".$link."page=$i".$search."'>$i</a>";
            }
            else {
                $html.="<a href='".$link."page=$i".$search."'>$i</a>";
            }
        }
        if($_REQUEST['page']<5) {
            $html.="<a  href='".$link."page=$numOfPage".$search."'>>></a>";

        }


        $html.="</div>";
        return $html;
    }

}
?>