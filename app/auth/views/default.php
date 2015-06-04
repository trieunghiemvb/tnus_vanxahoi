<?php
if ( !defined('AREA') ) { die('Access denied'); }

?>
<div id="loginbox" class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Đăng nhập</div>
        </div>
    <div class="panel-body">
        <?php if (!$this->is_success) { ?>
            <div id="login-alert" class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                Đăng nhập không thành công!
            </div>
        <?php } ?>
        <form action="" id="loginform" class="form-horizontal" role="form" method="post">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" id="username" class="form-control" name="username" value="" placeholder="Tên đăng nhập">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" id="password" class="form-control" name="password" placeholder="Mật khẩu">
            </div>
            <div class="input-group">
                <div class="checkbox">
                    <label for=""><input type="checkbox" name="remember" value="1" id="remember">Ghi nhớ đăng nhập</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 controls">
                    <button class="btn btn-success" id="btnLogin" type="submit">Đăng nhập</button>
                </div>
            </div>
            <input type="hidden" name="app" value="auth" />
            <input type="hidden" name="act" value="login" />
        </form>
    </div>
    </div>
</div>