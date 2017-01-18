<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Group <em>(<?php echo $group->getName();?>)</em></h3>
                </div><!-- /.box-header -->
                <form class="form-horizontal validate" action="" method="post">
                    <div class="box-body col-sm-7">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name" >Group Name</label>
                            <div class="col-sm-8">
                                <input id="name" type="text" name="name" class="form-control required" value="<?php echo $group->getName();?>" placeholder="Group Name" pattern="[A-Za-z]+" title="Alphabet Only">
                            </div>                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="description" >Group Description</label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" class="form-control required" pattern="[A-Za-z]+" title="Alphabet Only" style="resize:none"><?php echo $group->getDescription();?></textarea>
                            </div>                            
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="status">Status</label>
                            <div class="col-sm-8">
                                <select id ="status" name="status" class="form-control required">
                                    <option value=""> --- Select Status --- </option>
                                    <?php
                                        foreach (\models\Group::$status_types as $id => $value) {
                                            $sel = ($group->getStatus() == $id) ? 'selected="selected"' : '';
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
                                <button type="submit" class="btn btn-primary" value="Save">Save</button>
                                <a href="<?php echo site_url('user/group/edit/'.$group->getId())?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>