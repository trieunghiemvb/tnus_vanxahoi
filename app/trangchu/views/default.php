
<div class="content">
    <div class="row_1">
        <div class="slider">
            <ul class="images">
                <?php
                foreach ($this->arr_slide as $key => $slide) {
                    $url_img = TrangchuApp::get_url_first_image($slide['fulltext']);
                    ?>
                    <li><img  alt="hinh anh" src="<?= $url_img ?>" /></li>
                    <?php
                }
                ?>
            </ul>
            <ul class="text">
                <?php
                foreach ($this->arr_slide as $key => $slide) {
                    $title = $slide['title'];
                    ?>
                    <li><?= $title ?><a href="?app=tintuc&view=chitiet&id=<?=$slide['id']?>" class="readmore">Xem tiếp...</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div> 
    <!-- /.row_1-->
    <div class="row_2">
        <a href="<?= $this->obj_ad->url ?>" target="_blank">
            <img src="<?= $this->obj_ad->img ?>" class="ad_banner_ngang" alt="banner ngang" />
        </a>
    </div>
    <!--/.row_2-->
    <div class="row_3">
        <?php
        foreach ($this->arr_home_cate as $home_cate) {
            ?>

            <div class="cate_item">
                <h2 class="cate_title"><a href="<?="?app=tintuc"?>"><?=$home_cate->cat_title?>  <img  alt="hinh anh" src="media/images/web/arrow.png" width="16"  /></a></h2>
                <h3 class="new_title"><?=$home_cate->con_title?></h3>
                <img  alt="hinh anh" class="new_thumb" src="<?=$home_cate->con_img?>" />
                <p class="new_intro"><?=$home_cate->con_introtext?></p>
                <div class="new_readmore">
                    <p class="count_view">Lượt xem: 180</p>
                    <a  href="?app=tintuc&view=chitiet&id=<?=$home_cate->con_id?>"> Xem tiếp</a> 
                </div>

            </div>
            <?php
        }
        ?>
    </div>
    <!--/.row_3-->
</div>
<!--  /.content -->