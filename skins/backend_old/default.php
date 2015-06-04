<?php
if (!defined('AREA')) {
    die('Access denied');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="<?php echo $meta_keywords; ?>">
        <meta name="description" content="<?php echo $meta_desc; ?>">
        <title><?php echo $page_title; ?></title>
        <link href="<?php echo AppObject::getBaseFile('libs/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" media="screen">
        <link href="<?php echo AppObject::getBaseFile('public/css/base.css') ?>" rel="stylesheet" media="screen">
        <link href="<?php echo AppObject::getBaseFile('skins/backend/css/style.css') ?>" rel="stylesheet" media="screen">
        <script src="<?php echo AppObject::getBaseFile('public/js/jquery.js') ?>"></script>
        <script src="<?php echo AppObject::getBaseFile('libs/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo AppObject::getBaseFile('skins/backend/js/default.js') ?>"></script>
        <script src="<?php echo AppObject::getBaseFile('skins/backend/js/scripts.js') ?>"></script>
    </head>
    <body>
        <div class="overlay" style="display:none;">
            <div id="dialog-box">
                <div id="dialog-icon" class=""></div>
                <h4 class="dialog-title">Đang xử lý...</h4>
                <p class="dialog-messeage"></p>
                <button type="button" class="btn btn-xs btn-danger dialog-btn" style="display:none;" onclick="dialogMesseage.hide()">x</button></h4>
            </div>
        </div>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">QUẢN LÝ SAU ĐẠI HỌC</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Danh mục hệ thống <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="?app=course">Quản lý khóa học</a></li>
                            <li><a href="?app=groupfield">Quản lý ngành học</a></li>
                            <li><a href="?app=major">Quản lý chuyên ngành</a></li>
                            <li><a href="?app=class">Quản lý lớp</a></li>
                            <li class="divider"></li>
                            <li><a href="?app=subjectsdict">Từ điển môn học</a></li>
                            <li><a href="?app=markformula">Công thức tính điểm</a></li>
                            <!-- <li class="divider"></li>
                            <li><a href="#">Quản lý tài khoản người dùng</a></li>
                            <li><a href="#">Phân quyền sử dụng chức năng</a></li> -->
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Quản lý học viên <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="?app=importprofile">Nhập hồ sơ từ file excel</a></li>
                            <li><a href="?app=searchprofile">Tra cứu hồ sơ</a></li>
                            <li class="divider"></li>
                            <li><a href="?app=classfile">Quản lý học viên theo lớp</a></li>
                            <li><a href="?app=phanlop">Phân lớp cho học viên</a></li>
                            <li><a href="?app=transfer_class">Chuyển lớp cho học viên</a></li>
                            <li><a href="?app=setmajor">Phân chuyên ngành cho học viên</a></li>
                            <li class="divider"></li>
                            <li><a href="?app=gencode">Đánh mã học viên</a></li>
                            <li><a href="?app=profilestatus">Cập nhật trạng thái học viên</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Quản lý đào tạo <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="?app=planning_training">Lập kế hoạch đào tạo Khóa</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Lập kế hoạch lớp môn học</a></li>
                            <li><a href="#">In danh sách lớp môn học</a></li>
                            <li><a href="?app=markcomponent">Nhập điểm thành phần</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Xét điều kiện làm luận văn</a></li>
                            <li><a href="#">Xét duyệt tốt nghiệp</a></li>
                            <li class="divider"></li>
                            <li><a href="#">In bảng điểm</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Khoá điểm</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Quản lý thi <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Tạo - Chia phòng thi</a></li>
                            <li><a href="#">Cập nhật tình trạng vắng thi</a></li>
                            <li><a href="#">Quản lý phách</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Nhập điểm thi theo danh sách</a></li>
                            <li><a href="#">Nhập điểm thi theo số phách</a></li>
                        </ul>
                    </li>
                </ul>
                <?php
                /* load block */
                if (!empty($_SESSION["auth"]["id_user"])) {
                    $db = new Database();
                    $this->loaduser = $db->getValue('users', "*", array("id_user" => $_SESSION["auth"]["id_user"]));
                } else {
                    header("Location: ?app=auth");
                }
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="glyphicon glyphicon-user"></i> <?php echo $this->loaduser['username']; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
                            <li><a href="#"><i class="glyphicon glyphicon-lock"></i> Change password</a></li>
                            <li class="divider"></li>
                            <li><a href="?app=auth&act=logout"><i class="glyphicon glyphicon-ban-circle"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>

            </div><!-- /.navbar-collapse -->
        </nav>

        <div class="notice col-xs-12" style="display:none;">
            <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <span class="alert-message"></span>
            </div>
        </div>
        <div class="app_content">
            <?php echo $content; ?>
        </div><!-- /.app_content -->
    </body>
</html>