<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                    <h3 class="box-title">Create new Group</h3>
                </div><!-- /.box-header -->
                <form class="form-horizontal validate" action="" method="post">
                    <div class="box-body col-sm-7">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name" >Group Name</label>
                            <div class="col-sm-8">
                                <input id="name" type="text" name="name" class="form-control required" placeholder="Group Name" value="<?php echo set_value('name');?>" pattern="[A-Za-z]+" title="Alphabet Only">
                            </div>                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="description" >Group Description</label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" class="form-control required" pattern="[A-Za-z]+" title="Alphabet Only" style="resize:none"><?php echo set_value('description');?></textarea>
                            </div>                            
                        </div>
                    </div>
                    <div class="box-footer col-sm-6 clearfix " style="margin-left:15px;">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary" value="Create">Create</button>
                                <a href="<?php echo site_url('user/group/add')?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>