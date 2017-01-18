<?php 
$group_status=isset($filters['group_status'])?$filters['group_status']:'';
$name = isset($filters['name'])? $filters['name'] : '';
$offset = $this->input->get('per_page');
?>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Filter Groups</h3>
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
                  				<label for="group_status">Status</label>
								<select id ="group_status" name="group_status" class="form-control">
									<option value=""> --- Select Status --- </option>
									<?php foreach (\models\Group::$status_types as $id => $value): ?>
										<option value="<?=$id; ?>" <?php echo (isset($filters['group_status']) && $filters['group_status'] == $id) ?  'selected="selected"' : '' ?>><?=$value; ?></option>
									<?php endforeach; ?>
								</select>
                			</div>
                			<div class="col-xs-12 col-md-4 form-group">
                  				<label for="name">Group Name</label>
                  				<input class="form-control" id="name" type="text" name="name" value="<?php echo $name; ?>" title="A-Z/a-z/0-9/_" pattern="[0-9A-Za-z_]+" placeholder="Group name">
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
					<h3 class="box-title">List of Groups</h3>
				<?php if(isset($groups) && count($groups)>0): ?>
					<div class="pull-right">
						<?php
						if (user_access('add user group')):
						echo action_button('add','user/group/add' ,array('title' =>'Add a User Group', 'class' => 'header'  )).'&emsp;';
						endif;
						echo action_button('excel','user/group?do=xls&group_status='.$group_status.'&name='.$name.'&per_page='.$offset.'&a_filter=Filter' ,array('title' =>'Export to Excel', 'class' => 'header'  ));
						?>
					</div>
				<?php endif;?>
				</div><!-- /.box-header -->
				<div class="box-body">
					<?php if(count($groups) > 0):?>
					<table class="table table-striped">
						<tbody>
							<tr>
								<th>S.N.</th>
		        				<th>Group Name</th>
		        				<th>Description</th>
		        				<th>Number of Users</th>
		        				<th>Status</th>
		        				<th>Actions</th>
							</tr>
					<?php 
							$count = 1;
							foreach($groups as $g):
							?>
							<tr>
								<td><?php echo $count++;?>.</td>
								<td><?php echo $g->getName();?></td>
								<td><?php echo ($g->getDescription())?:'N/A';?></td>
        						<td><?php echo ($g->getUsers()) ? count($g->getUsers()) : 0 ?></td>
        						<td><?php echo \models\Group::$status_types[$g->getStatus()];?></td>
        						<td>
        							<?php
        								if(!$g->isAdmin()){
				        				if($g->getId() != $adminGroupId) {
				        					switch($g->getStatus())
				        					{
				        						case \models\Group::STATUS_ACTIVE :{
				        							if( user_access('modify user group') ){
				        								echo action_button('edit','user/group/edit/'.$g->getId() ,array('title'	=>	'Edit Group'))."&emsp;";
				        								echo action_button('permissions',site_url('user/group/permissions/'.$g->getId()),array('title'	=>	'Edit Permissions')) ."&emsp;";
						        						echo action_button('block','user/group/block/'.$g->getId() ,array('title'  =>  'Block Group'))."&emsp;";
				        							}
				        							if(user_access('modify user group'))
				        							{
						        						echo action_button('delete','user/group/delete/'.$g->getId() ,array('title'  =>  'Delete Group'))."&emsp;";
				        							}
				        							break;
				        						}
				        						case \models\Group::STATUS_BLOCK :{
				        							if( user_access('modify user group') ){
						        						echo action_button('unblock','user/group/unblock/'.$g->getId() ,array('title'  =>  'Unblock Group'))."&emsp;";
				        							}
				        							break;
				        						}
				        						case \models\Group::STATUS_DELETE :{
				        							echo "<strong style='color: red;'><em>Deleted!</em></strong>";
				        							break;
				        						}
				        					}
				        				}
				        				}else{
					        					echo "<strong><em>Admin group!</em></strong>";	
					        			}
				        			?>
        						</td>
        					</tr>
        					<?php 
        					endforeach;
        			?>
						</tbody>
					</table>
					<?php else: ?>
						<p>Sorry, Group(s) not found.</p>
					<?php endif; ?>
				</div>
				<?php if(isset($pagination)):?>
					<div class="box-footer clearfix">
						<?php echo $pagination; ?>
					</div>
				<?php endif;?>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>

<script>
$(function(){
	$('#clear').bind('click',function() {
		$('#group_status, #name').val('');
		return false;
	});
		
	$('.fa-trash').click(function(){
		return confirm('User assigned to Group are not able to login while deleting group?');
	});

	$('.fa-ban').click(function(){
		return confirm('Are you sure to block this Group?');
	});

	$('.fa-check-square-o').click(function(){
		return confirm('Are you sure to unblock this Group?');
	});

});
				
</script>