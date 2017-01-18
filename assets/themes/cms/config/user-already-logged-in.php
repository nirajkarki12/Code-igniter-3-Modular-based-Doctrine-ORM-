	<div class="grid_12">
				<h2>Already Logged In</h2>
				
				<div class="grid_12">
					<fieldset>
						<legend>The user <?php echo Current_User::user()->getUsername()?> has already logged in to CMS from another machine.</legend>
						
						<div class="grid_12">
							
							Sorry! We detected that the user you tried to login already has an active session in CMS.<br/> 
							Try logging in as <a href="<?php echo site_url('auth/logout')?>">another user</a>. 
							
						</div>
						
					</fieldset>
				</div>
				
		</div>
		
<?php
	echo get_footer();
	exit; // exit is required
?>