<div class="lockscreen-wrapper">
	<div class="lockscreen-logo">
        <a href="<?php echo site_url(); ?>"><h2 style="color:rgb(208, 35, 44);"><strong>CMS</strong></h2></a>
	</div>

	<div class="text-center"><h3>Under Maintenance</h3></div>

	<div class='text-center'>
		<?php 
			echo (strtotime(\Options::get('site_maintenance_resume_after', '0000-00-00 00:00:00')) > 0 
					and (\Options::get('site_maintenance_resume','0')=='1')
				) ? 
				'The '.$this->config->item('project_name').' Services will probably resume on '.\Options::get('site_maintenance_resume_after', '0').' !'
				 :
				'The '.$this->config->item('project_name').' Services will resume soon.'
			; 
		?>
	</div>
</div><!-- /.center -->
