<?php 
    $uri = str_replace( array(CI::$APP->config->item('url_suffix'), site_url(), 'auth/authenticate', 'auth/login'), '', current_url());
?>
<p class="login-box-msg">Sign in to start your session</p>

<form action="<?php echo site_url('auth/authenticate'.$uri)?>" method="post" class="sm-login">
    <div class="form-group has-feedback">
        <input type="text" class="form-control required" placeholder="Username or Email" name="sm-username" value=""/>
        <span class="fa fa-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control required" placeholder="Password" name="sm-password" value="" minlength="6"/>
        <span class="fa fa-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat" value="login">Login</button>
        </div><!-- /.col -->
    </div>
</form>
<p class="pull-right">
    <strong><a href="<?php echo site_url('auth/forgotPassword'); ?>">Forgot Password?</a></strong>
</p>