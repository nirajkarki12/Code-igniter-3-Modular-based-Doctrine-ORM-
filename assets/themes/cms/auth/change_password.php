<p class="login-box-msg">Change Your Password</p>

<form action="<?php echo site_url('auth/change-password')?>" method="post" class="sm-login">
    <div class="form-group has-feedback">
        <input type="text" class="form-control" readonly="readonly" placeholder="Username" value="<?php echo $user->getUsername(); ?>" />
        <span class="fa fa-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control required" placeholder="Password" name="sm_password" value="" minlength="6"/>
        <span class="fa fa-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control required" placeholder="Confirm Password" name="sm_confPassword" value="" minlength="6"/>
        <span class="fa fa-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat" value="login">Change</button>
        </div><!-- /.col -->
    </div>
</form>
 