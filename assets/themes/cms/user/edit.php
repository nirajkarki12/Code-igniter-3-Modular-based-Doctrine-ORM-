<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit User <em>(<?php echo $user->getUsername(); ?>)</em></h3>
                </div><!-- /.box-header -->
                <form class="form-horizontal validate" action="" method="post" name="editUser">
                    <div class="box-body col-sm-7">
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
                            <label class="col-sm-2 control-label" for="email">Email Address</label>
                            <div class="col-sm-8">
                                <input id="email" type="email" name="email" class="form-control required" value="<?php echo $user->getEmail(); ?>" placeholder="Email Address" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="username">Username</label>
                            <div class="col-sm-8">
                                <input type="text" id="username" readonly="readonly" autocomplete="off" class="form-control" value="<?php echo $user->getUsername();?>" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="role">Groups</label>
                            <div class="col-sm-8">
                                <select id ="role" name="groups[]" class="form-control required" multiple="true">
                                    <?php foreach ($groups as $group): 
                                        if(!$group->isAdmin()):
                                        $sel = in_array($group->getId(), $userGroups) ? 'selected="selected"' :'';
                                        $sel = isset($post) ? set_select('groups', $group->getId()) : $sel;
                                        ?>
                                        <option value="<?php echo $group->getId(); ?>"
                                            <?php echo $sel ?>>
                                            <?php echo $group->getName(); ?>
                                        </option>
                                    <?php endif;endforeach; ?>
                                </select>
                            </div>
                            <span><em><strong>(For assigning multiple groups. Hold down CTRL and select.)</strong></em></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="status">Status</label>
                            <div class="col-sm-8">
                                <select id ="status" name="status" class="form-control required">
                                    <option value=""> --- Select Status --- </option>
                                    <?php
                                        foreach (\models\User::$status_types as $id => $value) {
                                            $sel = ($user->getStatus() == $id) ? 'selected="selected"' : '';
                                            $sel = isset($post) ? set_select('status', $id) : $sel;
                                            ?>
                                            <option value="<?=$id; ?>" <?=$sel; ?>><?=$value; ?></option>
                                        <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer col-sm-6 clearfix " style="margin-left:15px;">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary" value="Update">Update</button>
                                <a href="<?php echo site_url('user/edit/'.$user->getUsername())?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>