<?php
session_start();
ob_start();
if ( !defined('AREA') ) {
    die('Access denied');
}
include_once(DIR_ROOT."/app/category/helpers/category.php");
?>
<?php
class CategoryApp extends AppObject {
    public $app_name="category";
    public $dir_layout="backend"; // thu m?c ch?a c�c layout

    public $limitstart;
    public $limit;
    public $total;
    public $where;
    public $numOfPage;
    public $query;
    public $search;
    public $pagination;
    public $loaditem;
    public $category;

    public $items=null;

    public function __construct() {

        if(empty($_SESSION["auth"]["id_user"])) {
            header("Location: ".INDEX); /* Redirect browser */
            exit;
        }

        parent::__construct();
    }
    public function display() {

        switch($_SESSION['display']) {
            case 'cancel':$this->setMsg("Cancel","Bạn đã hủy","success");
                break;
            case 'save':$this->setMsg("Save","Bạn đã lưu","info");
                break;
            case 'savenew':$this->setMsg("Save New","Bạn đã lưu và tiếp tục thêm mới","info");
                break;
            case 'apply':$this->setMsg("Apply","Bạn đã áp dụng","info");
                break;
            case 'notsave':$this->setMsg("Not Save","Bạn đã không lưu được","error");
                break;
            case 'update':$this->setMsg("Update","Bạn đã cập nhật","info");
                break;
            case 'notupdate':$this->setMsg("Not Update","Bạn đã không cập nhật được","error");
                break;
            case 'delete':$this->setMsg("Delete","Bạn đã xóa","info");
                break;
            case 'notdelete':$this->setMsg("Not Delete","Bạn đã không xóa được","info");
                break;
        }
        unset($_SESSION['display']);

        $task="";
        if(isset($_REQUEST['task'])) {
            $task=$_REQUEST['task'];
        }
        switch($task) {
            case 'new':CategoryApp::news();
                break;
            case 'edit':CategoryApp::edits();
                break;
            case 'delete':CategoryApp::deletes();
                break;
            case 'save':CategoryApp::saves();
                break;
            case 'update':CategoryApp::updates();
                break;
            case 'savenew':CategoryApp::savenews();
                CategoryApp::news();
                break;
            case 'apply':CategoryApp::saveapplys();
                break;
            case 'cancel':CategoryApp::cancels();
                break;
            default:CategoryApp::views();
        }

    }
    /*
		get views
    */
    public function views() {

        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";

        $table="categories";
        $field="*";
        $db=new Database();

        CategoryApp::getQuery();
        CategoryApp::getContentWhere();
        CategoryApp::setPagination();
        $where=$this->where." ORDER BY id DESC LIMIT ".$this->limitstart.",".$this->limit;

        $this->items=$db->getArray($table,$field,$where);
        $this->pagination=CategoryApp::getPagination();

        parent::display();
    }
    /*
		getcategory
    */
    public function getCategories() {

        $db=new Database();
        $table="categories";
        $field="*";
        $where=" published=1 ORDER BY id DESC";
        $this->category=$db->getArray($table,$field,$where);
        return $this->category;
    }
    /*
		get query
    */
    public function getQuery() {
        if(empty($this->query)) {
            $db=new Database();
            $table="categories";
            $field="*";
            $this->query=$db->getArray($table,$field);
        }
        return $this->query;
    }
    /*
		lấy tổng các quyền
		@param:
		@return: tổng số dữ liệu trả về.
    */
    public	function getTotal() {
        if (empty($this->total)) {
            $this->total = count($this->query);
        }
        return $this->total;
    }
    /*
		set Pagination
    */
    public	function setPagination() {
        CategoryApp::getTotal();
        $total = $this->total;
        $this->limit = $_REQUEST["perPage"];

        if(empty($this->limit)) {
            $this->limit=10;
        }
        $this->numOfPage = ceil($total/$this->limit);
        if(empty($_REQUEST['page'])) {
            $this->limitstart = 0;
        }
        else {
            if($this->limit>=$total) {
                $this->limitstart = 0;
            }else {
                $this->limitstart = ($_REQUEST['page'] - 1) * $this->limit;
            }
        }
        return true;
    }
    /*
		Phân trang
		@return:chuỗi html
    */
    public	function getPagination() {
        if(!empty($_REQUEST["search"])) {
            $search='&search='.$_REQUEST["search"];
        }else {
            unset($_REQUEST["search"]);
        }
        $html.="<div class='pagination pagination-right'>";
        $html.='<select name="perPage" onchange="this.form.submit()" style="width:55px;margin-top: -23px;" class="perPage">';
        for($j=1;$j<=$this->total;$j++) {
            $perPage=$j*10;
            $html.="<option value='".$perPage."'>".$perPage."</option>";
        }
        $html.='<option value="'.$this->total.'">all</option>';
        $html.='</select>';


        if(!empty($_REQUEST["page"]) && $_REQUEST["page"]%5==0) {
            $page=$_REQUEST["page"]-4;
            $dem=$this->numOfPage-($_REQUEST["page"]+5);
            if($dem<0) {
                $num=$this->numOfPage;
            }else {
                $num=$_REQUEST["page"]+5;
            }
        }else {
            $page=1;
            $dem=$this->numOfPage-($_REQUEST["page"]+5);
            if($dem<0) {
                $num=$this->numOfPage;
            }else {
                $num=$_REQUEST["page"]+5;
            }
        }

        $html.="<ul>";
        for($i=$page;$i<=$num;$i++) {
            if($i==$_REQUEST['page']) {
                $html.="<li class='active'><a href='?app=category&page=$i".$search."'><b>$i</b></a></li>";
            }
            else {
                $html.="<li><a href='?app=category&page=$i".$search."'><i>$i</i></a></li>";
            }
        }
        $html.="</ul>";
        $html.="</div>";
        return $html;
    }
    /*
		Lấy điều kiện
    */
    public function getContentWhere() {

        if(empty($this->where)) {
            $where=array();
            $search=$_REQUEST["search"];
            if ($search) {
                $where[] = 'LOWER(title) LIKE "%'.$search.'%"';
            }
            $this->where 		= ( count( $where ) ? implode( $where,' AND ') : 1 );
        }
        return $this->where;
    }
    /*
		deletes
    */
    public function deletes() {

        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="default";
        $db=new Database();
        if(isset($_REQUEST['check'])) {
            $where=implode(' , ',$_REQUEST['check']);
            $delete=$db->delete('categories',"id IN(".$where.")");
            if($delete==false) {
                $_SESSION['display']='notdelete';
            }else {
                $_SESSION['display']='delete';
            }
        }

        header("Location: ".INDEX."?app=category");
    }
    /*
		new
    */
    public function news() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="edit";
        CategoryApp::getCategories();
        parent::display();
    }
    /*
		edit
    */
    public function edits() {
        $this->dir_layout="backend";
        $this->layout="default";
        $this->view="edit";
        $db=new Database();
        $table="categories";
        $fields="*";
        if(isset($_REQUEST['check'])) {
            $where=implode(' , ',$_REQUEST['check']);
            $conditions="id IN (".$where.")";
        }
        if(isset($_REQUEST['title'])) {
            $conditions="title like '".$_REQUEST['title']."'";
        }
        CategoryApp::getCategories();
        $this->loaditem=$db->getRecord($table,$fields,$conditions);
        parent::display();
    }
    /*
		cancel
    */
    public function cancels() {
        $_SESSION['display']='cancel';
        header("Location: ".INDEX."?app=category");
    }
    /*
		saves
    */
    public function saves() {
        $saves=CategoryApp::applys();
        if($saves==false) {
            $_SESSION['display']='notsave';
        }else {
            $_SESSION['display']='save';
        }
        header("Location: ".INDEX."?app=category");
    }
    /*
		savenews
    */
    public function savenews() {
        $savenews=CategoryApp::applys();
        if($savenews==false) {
            $_SESSION['display']='notsave';
        }else {
            $_SESSION['display']='savenew';
        }
    }
    /*
		applys
    */
    public function applys() {
        $db=new Database();
        $table='categories';
        $rep=new helpersCategory();
        $alias=$_REQUEST['alias'];
        if($alias=="") {
            $alias=$rep->alias($_REQUEST['title']);
        }
        $data=array("title"=>$_REQUEST['title'],"alias"=>$alias,"cread_by"=>$_SESSION["auth"]["username"],
                "published"=>$_REQUEST['published'],"description"=>$_REQUEST['description'],"parent"=>$_REQUEST['parent'],
                "metadesc"=>$_REQUEST['metadesc'],"metakey"=>$_REQUEST['metakey'],"metadata"=>$_REQUEST['metadata']);
        $applys=$db->insert($table,$data);
        return $applys;
    }
    /*
		saveapplys
    */
    public function saveapplys() {
        $saveapplys=CategoryApp::applys();
        if($saveapplys==false) {
            $_SESSION['display']='notsave';
        }else {
            $_SESSION['display']='apply';
        }
        header("Location: ".INDEX."?app=category&task=edit&title=".$_REQUEST['title']);
    }
    /*
		updates
    */
    public function updates() {
        $db=new Database();
        $table='categories';

        $rep=new helpersCategory();
        $alias=$_REQUEST['alias'];
        if($alias=="") {
            $alias=$rep->alias($_REQUEST['title']);
        }
        $data=array("title"=>$_REQUEST['title'],"alias"=>$alias,
                "published"=>$_REQUEST['published'],"description"=>$_REQUEST['description'],"parent"=>$_REQUEST['parent'],
                "metadesc"=>$_REQUEST['metadesc'],"metakey"=>$_REQUEST['metakey'],"metadata"=>$_REQUEST['metadata']);
        $where=$_REQUEST['id'];
        $conditions="id IN (".$where.")";
        $updade=$db->update($table,$data,$conditions);

        if($updade==false) {
            $_SESSION['display']='notupdate';
        }else {
            $_SESSION['display']='update';
        }

        header("Location: ".INDEX."?app=category");
    }
}
?>