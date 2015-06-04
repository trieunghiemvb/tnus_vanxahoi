<?php
if (!defined('AREA')) {
    die('Access denied');
}
?>
<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4>Test Content</h4>
        </div>
        <div class="panel-body">
            <fieldset>
                <legend>Array</legend>
                <pre>
                <?php
                print_r($this->testCotent);
                ?>
                </pre>
            </fieldset>
            <fieldset>
                <legend>Object</legend>
                <?php
                    echo "img: ".$this->testCotent->img."<br/>";
                    echo "url: ".$this->testCotent->url."<br/>";
                ?>
            </fieldset>
            <fieldset>
                <legend>String value</legend>
                <?php
                echo $this->testCotent;
                ?>
            </fieldset>
            

        </div>

    </div>
</div>
