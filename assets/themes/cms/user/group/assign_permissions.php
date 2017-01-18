<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Group Permissions <em>(<?php echo $this->session->userdata('group')['name'];?>)</em></h3>
                </div><!-- /.box-header -->
                <form name="group_permissions" method="post" action="" id="permission_form">
                    <div class="box-body">
                    	<table  class="table table-striped">
                    		<tbody>
                    			<tr>
                    				<th width="3%"><input type="checkbox" id="select_all" name="select_all" title="select all" /></th>
					        		<th><label for="select_all">Permission</label></th>
					        		<th>Description</th>
                    			</tr>
	        			<?php foreach($all_permissions as $module=>$permissions){
	        				$perms = array_intersect_key($permissions, $db_permissions)
							// if (!is_array($permissions) || !$perms = array_intersect_key($permissions, $db_permissions)) continue;
							?>
							<tr style="background-color: #BECCDD;">
                                <th>
                                    <input id="<?php echo ucfirst($module); ?>" type="checkbox" class="checkIt" /></th>
                                <th colspan="2">
                                    <label for="<?php echo ucfirst($module); ?>">
                                    <?php echo ucfirst($module)." Permissions"; ?></label>
                                </th>
                            </tr>
							<?php foreach ($perms as $name=>$descr){
							 	$depends = '';
							 ?>
								<tr>
									<td>
					        			<?php 
					        				if(is_array($descr)){
					        					foreach ($descr['depends_on'] as $val){
					        						$depends .= str_replace(' ', '-', $val)." ";
					        					}
					        				}
					        				else {
					        					$depends = '';
					        				}
					        			?>
					        			<input id="<?php echo str_replace(' ', '-', $name); ?>" 
				        				   depends="<?php echo $depends; ?>"
				        				   type="checkbox" value="<?php echo $db_permissions[$name]?>" 
				        				   name="permission[<?php echo $db_permissions[$name]?>]"
				        				   class="checkIt <?php echo ucfirst($module);?>" />
					        		</td>
									<td><label for="<?php echo str_replace(' ', '-', $name); ?>"><?php echo ucwords($name); ?></label></td>
									<td><?php echo (is_array($descr)) ? $descr['description'] :  $descr; ?></td>
								</tr>

							<?php } ?>
						<?php } ?>
                    		</tbody>
                    	</table>
                    </div>
                    
                    <div class="box-footer clearfix">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary save-perms" name="save_perms" value="Save Permissions">Save Permissions</button>
        						<input type="reset" class="btn btn-warning" value="Reset"/>
        						<!-- <a href="<?php echo site_url('user/group')?>" class="btn btn-danger">Cancel</a> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>

<script>

$(document).ready(function(e){
    $(function(){
        if ($('.checkIt:checked').length == $('.checkIt').length) $('#select_all').prop('checked', true);
    });

    $('.checkIt').click(function(){ 
        if(!$(this).prop('checked')) $('#select_all').prop('checked',false); 
        if ($('.checkIt:checked').length == $('.checkIt').length) $('#select_all').prop('checked', true);
    })
    
	$('#select_all').bind('click',function(){
        $( '.checkIt' ).prop('checked', this.checked);
        var title = $(this).prop('checked') ? 'deselect all' : 'select all';
        $(this).attr('title', title);
	});

    <?php foreach($all_permissions as $k=>$v):?>

        $('#<?php echo ucfirst($k);?>').bind('click',function(){
            $( '.<?php echo ucfirst($k);?>' ).prop('checked', this.checked);
            if ($('.checkIt:checked').length == $('.checkIt').length) $('#select_all').prop('checked', true);
        });
        $('.<?php echo ucfirst($k);?>').bind('click',function(){
            if ($('.<?php echo ucfirst($k);?>:checked').length > 0){ $('#<?php echo ucfirst($k);?>').prop('checked',true);}
            else $('#<?php echo ucfirst($k);?>').prop('checked',false);
            
        });
        if ($('.<?php echo ucfirst($k);?>:checked').length > 0){ $('#<?php echo ucfirst($k);?>').prop('checked',true);}
        else $('#<?php echo ucfirst($k);?>').prop('checked',false);
    
    <?php endforeach;?>

	$('.save-perms').click(function() {
		if ($('.checkIt:checked').length < 1) {
			alert('Please set atleast one permission.');
			return false;
		}
	});

	// $(".checkIt").click(function(e){
	// 	var depends_on = $(this).attr('depends');
	// 	var splitted_depends = depends_on.split(" "); 
	// 	var checked = $(this).attr('checked');

	// 	if(checked){
	// 		if(depends_on){
	// 			$.each(splitted_depends, function(i, val){
	// 				$("#"+val).attr('checked','checked')
	// 			});
	// 		}
			
	// 	}
	// 	else{
	// 		if(depends_on){
	// 			$.each(splitted_depends, function(i, val){
	// 				$("#"+val).removeAttr('checked','checked')
	// 			});
	// 		}
	// 	}
		
	// });
    
});

</script>