<div class="content">
    <div class="col_1">
        <h2 class="page_title">TIN TỨC</h2>
        <div class="news_content">
            <h2 class="news_title"><?= $this->news->title ?></h2>
            <p class="news_intro"><?= strip_tags($this->news->introtext) ?></p>
            <div class="news_details"><?= str_replace('\\', "", $this->news->fulltext) ?></div>
            <p class="author"><?= $this->news->cread_by ?></p>
        </div>
        <div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
        <div class="fb-comments" data-href="http://developers.facebook.com/docs/plugins/comments/" data-numposts="5" data-colorscheme="light"></div>
        <div class="clearfix"></div>
        <div class="news_other">
            <h2 class="title">Tin liên quan</h2>
            <?php
            foreach ($this->arr_other_news as $news) {
                ?>
                <div class="items">
                    <a href="?app=tintuc&view=chitiet&id=<?= $news['id'] ?>">
                        <img src="<?= TintucApp::get_url_first_image($news['fulltext']) ?>" alt="thumb" class="thumbnail" />
                        <p class="news_title"><?= $news["title"] ?></p>
                    </a>
                </div>
                <?php
            }
            ?>
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
                        <a href="?app=tintuc&view=chitiet&id=<?= $news['id'] ?>"><?= $news["title"] ?></a>
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
                        <a href="?app=tintuc&view=chitiet&id=<?= $news['id'] ?>"><?= $news["title"] ?></a>
                        <p class="date"><?= $news["date"] ?></p>
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
<!--facebook-->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&appId=1431768223799282";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>