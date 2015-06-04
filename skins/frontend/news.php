<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi-vn" lang="vi-vn">
    <head>
        <title><?= $this->page_title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="favicon.ico" title="WEBSITE KHOA VĂN - ĐẠI HỌC KHOA HỌC" />
        <link rel="stylesheet" href="<?= "." . DS . DIR_LAYOUT . DS . "frontend" ?>/css/style.css" type="text/css" />
        <link rel="stylesheet" href="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>/css/tintuc.css" type="text/css" />
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/jquery-latest.js"></script>
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/menu.js"></script>
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/ad.js"></script> 
        <script type="text/javascript" src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/jquery.scrollerota.min.js"></script>
    </head>
    <body id="bd">
        <div class="wrapper">
            <div class="background">
                <div id="top_menu">
                    <?php include(DIR_ROOT . DS . 'skins/modules/menu.php'); ?>
                </div><!-- ./ #top_menu -->
                <div id="header">
                    <?php include(DIR_ROOT . DS . 'skins/modules/header.php'); ?>
                </div><!-- ./ #header -->
                <div id="breadcrum">
                    <?php include(DIR_ROOT . DS . 'skins/modules/breadcrum.php'); ?>
                </div><!-- ./ #breadcrum -->
                <div id="content">
                    <?=$content?>
                </div><!-- ./ #content -->
                <?php include(DIR_ROOT . DS . 'skins/modules/partner.php'); ?>
                <?php include(DIR_ROOT . DS . 'skins/modules/big_ad.php'); ?>
                <!--/.partner-->

                <?php include(DIR_ROOT . DS . 'skins/modules/footer.php'); ?>
                <!--  /.footer -->
            </div>
            <!--/.background-->
        </div>
        <!--/.wrapper-->
        <!--Slider script-->
        <script type="text/javascript">
            $(".slider").scrollerota();
        </script>
        <!-- quảng cáo-->
        <div id="divAdRight" style="display: none; position: absolute; top: 0px">
            <a href="http://itfunny.net/"><img src="media/images/ad/quangcao2.png" width="125" alt="hinhanh" /></a>
        </div>
        <div id="divAdLeft" style="display: none; position: absolute; top: 0px">
            <a href="http://itfunny.net/"><img src="media/images/ad/quangcao2.png" width="125" alt="hinhanh" /></a>   
        </div>
        <script type='text/javascript' src="<?= "." . DS . DIR_LAYOUT . DS . "frontend/" ?>js/ad_config.js" ></script>
    </body>
</html>
