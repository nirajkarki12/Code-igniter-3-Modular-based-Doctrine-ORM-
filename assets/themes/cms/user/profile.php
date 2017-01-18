<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                        <h3 class="box-title">Profile Information <em>(<?php echo ucfirst($user->getUsername()); ?>)</em></h3>
                </div><!-- /.box-header -->
                <form method="post" name="user_profile" action="" id="user_profile" class="form-horizontal validate">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="fname">First Name</label>
                            <div class="col-sm-8">
                                <input type="text" id="fname" name="fname" autocomplete="off" class="form-control required" value="<?php echo $user->getFirstName(); ?>" placeholder="First Name" pattern="[A-Za-z]+" title="Alphabet Only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="mname">Middle Name</label>
                            <div class="col-sm-8">
                                <input type="text" id="mname" name="mname" autocomplete="off" class="form-control" value="<?php echo $user->getMiddleName(); ?>" placeholder="Middle Name" pattern="[A-Za-z]+" title="Alphabet Only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="lname">Last Name</label>
                            <div class="col-sm-8">
                                <input type="text" id="lname" name="lname" autocomplete="off" class="form-control required" value="<?php echo $user->getLastName(); ?>" placeholder="Last Name" pattern="[A-Za-z]+" title="Alphabet Only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="address">Address</label>
                            <div class="col-sm-8">
                                <input type="text" id="address" name="address" autocomplete="off" class="form-control required" value="<?php echo $user->getAddress(); ?>" placeholder="Address" maxlength="25" pattern="[A-Za-z0-9\,\-\s]+" title="A-Z/a-z/0-9/,/-">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="phone">Phone</label>
                            <div class="col-sm-8">
                                <input type="text" id="phone" name="phone" autocomplete="off" class="form-control" value="<?php echo $user->getPhone(); ?>" placeholder="Phone" pattern ="[0-9]+" maxlength="9" title="0-9 Only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="mobile">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" id="mobile" name="mobile" autocomplete="off" class="form-control" value="<?php echo $user->getMobile(); ?>" placeholder="Mobile" pattern="[9][0-9]{9}" minlength="10" title="9XXXXXXXXX">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="email">Email Address</label>
                            <div class="col-sm-8">
                                <input id="email" type="email" name="email" class="form-control required" value="<?php echo $user->getEmail(); ?>" placeholder="Email Address" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="username">Username</label>
                            <div class="col-sm-8">
                                <input type="text" id="username" autocomplete="off" class="form-control" value="<?php echo $user->getUsername();?>"  readonly="readonly">
                            </div>
                        </div>

                        <!-- to be deleted -->
                        <input type="hidden" name="dump" value="1">
                    </div>
                    <div class="box-footer clearfix">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary" value="Update">Update</button>
                                <a href="<?php echo site_url('user/profile')?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>