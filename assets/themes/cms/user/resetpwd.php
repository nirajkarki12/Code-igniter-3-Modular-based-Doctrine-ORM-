<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                        <h3 class="box-title">Choose new Password <em>(For <?php echo $user->getUsername(); ?>)</em></h3>
                </div><!-- /.box-header -->
                <form method="post" action="" class="form-horizontal validate">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="newpwd">New Password</label>
                            <div class="col-sm-8">
                                <input id="newpwd" type="password" name="newPwd" class="form-control required" minlength='6'/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="conpwd">Confirm Password</label>
                            <div class="col-sm-8">
                                <input id="conpwd" type="password" name="confPwd" class="form-control required" minlength='6'/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary" value="Change">Change</button>
                                <a href="<?php echo site_url('user/resetpwd')?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>