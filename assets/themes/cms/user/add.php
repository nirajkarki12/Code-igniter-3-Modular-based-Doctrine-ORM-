<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Create a User</h3>
				</div><!-- /.box-header -->
				<form class="form-horizontal validate" action="" method="post" name="addUser">
					<div class="box-body col-sm-7">
						<div class="form-group">
				            <label class="col-sm-2 control-label" for="fname">First Name</label>
				            <div class="col-sm-8">
				            	<input type="text" id="fname" name="fname" autocomplete="off" class="form-control required" value="<?php echo set_value('fname');?>" placeholder="First Name" pattern="[A-Za-z]+" title="Alphabet Only">
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="mname">Middle Name</label>
				            <div class="col-sm-8">
				            	<input type="text" id="mname" name="mname" autocomplete="off" class="form-control" value="<?php echo set_value('mname');?>" placeholder="Middle Name" pattern="[A-Za-z]+" title="Alphabet Only">
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="lname">Last Name</label>
				            <div class="col-sm-8">
				            	<input type="text" id="lname" name="lname" autocomplete="off" class="form-control required" value="<?php echo set_value('lname');?>" placeholder="Last Name" pattern="[A-Za-z]+" title="Alphabet Only">
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="email">Email Address</label>
				            <div class="col-sm-8">
				            	<input id="email" type="email" name="email" class="form-control required" value="<?php echo set_value('email')?>" placeholder="Email Address" maxlength="30">
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="username">Username</label>
				            <div class="col-sm-8">
				            	<input type="text" id="username" name="username" autocomplete="off" class="form-control required" value="<?php echo set_value('username');?>" placeholder="Username">
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="password">Password</label>
				            <div class="col-sm-8">
				            	<input id="password" type="password" name="password" autocomplete="off" class="form-control required"  placeholder="Password" onpaste="return false;">
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="confirmpassword">Confirm Password</label>
				            <div class="col-sm-8">
				            	 <input id="confirmpassword" type="password" name="confPassword" autocomplete="off" class="form-control required" placeholder="Confirm Password" onpaste="return false;">
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-sm-2 control-label" for="role">User Groups</label>
				            <div class="col-sm-8">
				            	<select id ="role" name="groups[]" class="form-control required" multiple="true">
	              					<?php foreach ($groups as $group): 
	              					if(!$group->isAdmin()):?>
	              						<option value="<?php echo $group->getId(); ?>"
	              							<?php echo set_select('groups', $group->getId()); ?>>
	              							<?php echo $group->getName(); ?>
	              						</option>
	              					<?php endif;endforeach; ?>
              					</select>
				            </div>
				            <span><em><strong>(For assigning multiple groups. Hold down CTRL and select.)</strong></em></span>
				        </div>
					</div>
                    <div class="box-footer col-sm-6 clearfix " style="margin-left:15px;">
			        	<div class="form-group">
				            <div class="col-sm-4">
				                <button type="submit" class="btn btn-primary" value="Create">Create</button>
								<a href="<?php echo site_url('user/add')?>" class="btn btn-danger">Cancel</a>
				            </div>
				        </div>
			        </div>
                    <div class="clearfix"></div>
		        </form>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>