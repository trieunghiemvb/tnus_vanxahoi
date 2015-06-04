<div class="content">
    <div class="col_1">
        <h2 class="page_title">TIN TỨC</h2>
        <div class="slider">
            <ul class="images">
                <?php
                foreach ($this->arr_slide as $key => $slide) {
                    $url_img = TintucApp::get_url_first_image($slide['fulltext']);
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
        <div class="list_news">
            <?php
            foreach ($this->arr_news as $news) {
                ?>
                <div class="news_item ">
                    <div class="news_content">
                        <h3 class="new_title"><a href="?app=tintuc&view=chitiet&id=<?=$news['id']?>"><?= TintucApp::trim_text($news['title'],60) ?></a></h3>
                        <p class="new_intro"><?= TintucApp::trim_text(strip_tags($news['introtext']), 90) ?></p>
                    </div>
                    <img  alt="hinh anh" class="new_thumb" src="<?= TintucApp::get_url_first_image($news['fulltext']) ?>" />
                </div>
                <?php
            }
            ?>
            <div id="pagination">
                <?php echo $this->pagination; ?>
                <!--<span class="disabled_pagination">Lùi</span><a href="#1">1</a><a href="#2">2</a><a href="#3">3</a><span class="active_tnt_link">4</span><a href="#5">5</a><a href="#6">6</a><a href="#7">7</a><a href="#8">8</a><a href="#9">9</a><a href="#10">10</a><a href="#forwaed">Tiến</a>-->
            </div>
        </div>
    </div> 
    <!-- /.col_1-->
    <div class="col_2">
        <div class="most_view">
            <h2 class="title"><a href="#">Đọc nhiều </a></h2>
            <ul class="list">
                <?php
                foreach ($this->arr_most_view as $news) {
                    ?>
                    <li>
                        <a href="?app=tintuc&view=chitiet&id=<?=$news['id']?>"><?= $news["title"] ?></a>
                        <p class="date"><?= $news["date"] ?></p>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="event">
            <h2 class="title"><a href="#">Sự kiện </a></h2>
            <ul class="list">
                <?php
                foreach ($this->arr_event as $news) {
                    ?>
                    <li>
                        <a href="?app=tintuc&view=chitiet&id=<?=$news['id']?>"><?=$news["title"] ?></a>
                        <p class="date"><?=$news["date"] ?></p>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="ad_left">
            <img alt="quangcao" src="media/images/ad/99f12b15-3d94-47b8-aebf-5aa1be00678d2.jpg" />
        </div>
    </div>
    <!-- /.col_2-->
</div>
<!--  /.content -->