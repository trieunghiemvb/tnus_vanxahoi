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
            <?php
             print_r($this->testCotent);
//            var_dump($this->testCotent);
             echo $this->testCotent;
            ?>
        </div>

    </div>
</div>
