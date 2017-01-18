<?php 
$currentUser = \Current_User::user();
$user_status=isset($filters['user_status'])?$filters['user_status']:'';
$role=isset($filters['groups'])?$filters['groups']:'';
$username = isset($filters['username'])? $filters['username'] : '';
$offset = $this->input->get('per_page');
$switch = TRUE;
	
if (is_numeric($main_user = $this->session->main_user)) {

	$main_user = $this->doctrine->em->find('models\User', $main_user);
	if ($main_user) $switch = FALSE;
}
?>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Filter Users</h3>
					<div class="box-tools">
					    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					    </button>
					    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
				<form name="user_filter" method="get" action="">
					<div class="box-body">
						<div class="row">
                			<div class="col-xs-12 col-md-4 form-group">
                  				<label for="role">Role</label>
                  				<select id ="role" name="groups" class="form-control">
                  					<option value=""> --- Select Group --- </option>
                  					<?php foreach($groups as $g):?>
									<option	value="<?php echo $g->getId();?>"
											<?php if($g->getId()==$role){echo'selected="selected"';}?>
											<?php echo set_select('id', $g->getId()) ?>>
											
											<?php echo $g->getName(); ?>
									</option>
									<?php endforeach;?>
                  				</select>
                			</div>
                			<div class="col-xs-12 col-md-4 form-group">
                  				<label for="status">Status</label>
								<select id ="status" name="user_status" class="form-control">
									<option value=""> --- Select Status --- </option>
									<?php foreach (\models\User::$status_types as $id => $value): ?>
										<option value="<?=$id; ?>" <?php echo (isset($filters['user_status']) && $filters['user_status'] == $id) ?  'selected="selected"' : '' ?>><?=$value; ?></option>
									<?php endforeach; ?>
								</select>
                			</div>
                			<div class="col-xs-12 col-md-4 form-group">
                  				<label for="username">Username</label>
                  				<input class="form-control" id="username" type="text" name="username" value="<?php echo $username; ?>" title="A-Z/a-z/0-9/_" pattern="[0-9A-Za-z_]+" placeholder="Username">
                			</div>
						</div>
					</div>
					<div class="box-footer clearfix">
						<div class="col-xs-12 form-group">
            				<input type="submit" class="btn btn-primary" value="Filter" name="a_filter"/>
        					<input id="clear" type="submit" class="btn btn-danger" value="Clear"  />
            			</div>
					</div>
				</form>
				</div> <!-- /.box body -->
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">List of Users</h3>
				<?php if(isset($users) && count($users)>0): ?>
					<div class="pull-right">
						<?php
						if (user_access('add user')):
						echo action_button('add','user/add' ,array('title' =>'Add a User', 'class' => 'header'  )).'&emsp;';
						endif;
						echo action_button('excel','user?do=xls&user_status='.$user_status.'&groups='.$role.'&username='.$username.'&per_page='.$offset.'&a_filter=Filter' ,array('title' =>'Export to Excel', 'class' => 'header'  ));
						?>
					</div>
				<?php endif;?>
				</div><!-- /.box-header -->
				<div class="box-body">
					<?php if(isset($users) && count($users)>0): ?>
						<table class="table table-striped">
							<tbody>
								<tr>
									<th>S.N.</th>
			        				<th>Username</th>
			        				<th>Full Name</th>
			        				<th>Email Address</th>
			        				<th>Role</th>
			        				<th>Status</th>
			        				<th>Force Password Change</th>
			        				<th>Created</th>
			        				<th>Actions</th>
								</tr>

								<?php
								$count = isset($offset)?$offset+1:1;
								foreach($users as $u):
								?>
			        			<tr <?php // echo $attr ?>>
			        				<td><?php echo $count++ ?></td>
			        				<td><?php echo $u->getUsername();?></td>
			        				<td><?php echo $u->getName();?></td>
			        				<td><?php echo $u->getEmail();?></td>
			        				<td><?php
			        						foreach ($u->getGroups() as $i=>$group) {
			        							$grpCount = count($u->getGroups());
			        							if($grpCount == 1 && $group->isDeleted()){
			        								echo "<strong><em style='color: red;'>Deleted</em></strong>";
			        								echo (!$u->isDeleted())? " <strong><em>(Re-Assign)</em></strong>" : '';
			        								continue;
			        							}elseif($group->isDeleted()){
			        								continue;
			        							}
												echo $i==0 || $i==$grpCount?'':', ';
												echo $group->getName();
											}
			        					?>
			        				</td>
			        				<td><?php echo \models\User::$status_types[$u->getStatus()];?></td>
			        				<td><?php echo $u->isFirstLogin() ? 'Enabled' : '-';?></td>
			        				<td><?php echo $u->getCreatedAt()->format(\Options::get('date_format'));?></td>
			        				<td>
					        			<?php
			        						if(!$u->isSuperAdmin()):
					        				if($currentUser->getId() != $u->getId()) {
					        					switch($u->getStatus())
					        					{
					        						case \models\User::STATUS_ACTIVE :{
					        							if( user_access('edit user') ){
					        								echo action_button('edit','user/edit/'.$u->getUsername() ,array('title'	=>	'Edit '.$u->getUsername() ))."&emsp;";
							        						echo action_button('block','user/block/'.$u->getUsername() ,array('title'  =>  'Block '.$u->getUsername() ))."&emsp;";
					        							}
					        							if (user_access('reset password')) echo action_button('key','user/resetpwd/'.$u->getUsername() ,array('title'	=>	'Reset Password'))."&emsp;";

					        							if (user_access('force change password')) echo action_button('wrench','user/force-change-password/'.$u->getUsername() ,array('title'	=>	'Force Change Password'))."&emsp;";
					        							
					        							if ($switch and user_access('allow user switching') and $u->getId() != 1 and $u->isActive() ) {
					        								echo action_button('user','auth/switchuser/'.$u->getUsername(),array('title'  =>  'Run CMS as '.$u->getUsername()))."&emsp;";
					        							}
					        							if(user_access('modify user'))
					        							{
					        								echo action_button('delete','user/delete/'.$u->getUsername() ,array('title'	=>	'Delete '.$u->getUsername() ))."&emsp;";
					        							}
					        							break;
					        						}
					        						case \models\User::STATUS_BLOCK :{
					        							if( user_access('edit user') ){
					        								echo action_button('unblock','user/unblock/'.$u->getUsername() ,array('title'  =>  'Unblock '.$u->getUsername() ))."&emsp;";
					        							}
					        							break;
					        						}
					        						case \models\User::STATUS_DELETE :{
					        							echo "<strong style='color: red;'><em>Deleted!</em></strong>";
					        							break;
					        						}
					        					}
					        				} else {
					        					echo "<strong><em>This is you!</em></strong>";	
					        				}
					        			else:
					        					echo "<strong><em>Admin user!</em></strong>";	

					        			endif;
					        			?>
			        				</td>
			        			</tr>		        			
			        			<?php  endforeach;?>
							</tbody>
						</table>
					<?php else: ?>
						<p>Sorry, User(s) not found.</p>
					<?php endif; ?>
				</div><!-- box body -->
				
				<?php if(isset($pagination)):?>
					<div class="box-footer clearfix">
						<?php echo $pagination; ?>
					</div>
				<?php endif;?>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>

<!-- /.content -->

<script>
$(document).ready(function(){
	
	$('#clear').bind('click',function() {
		$('#username, #status, #role').val('');
		return false;
	});
	
	$('.fa-trash').click(function(){
		return confirm('Are you sure to delete this User?');
	});

	$('.fa-ban').click(function(){
		return confirm('Are you sure to block this User?');
	});

	$('.fa-check-square-o').click(function(){
		return confirm('Are you sure to unblock this User?');
	});

	$('.fa-wrench').click(function(){
		return confirm('Are you sure to Force this User to change password?');
	});

});
				
</script>