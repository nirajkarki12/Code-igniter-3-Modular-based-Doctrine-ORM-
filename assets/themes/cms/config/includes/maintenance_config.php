<div class="tab-pane" id="maintenance-config">
	<form class="form-horizontal validate" action="<?php echo site_url('config/settings'); ?>" method="post" id="maintenance-config-form">
	<h3 class="heading">Site Maintenance</h3>
	<input type="hidden" name="params" value="maintenance-config">
	<div class="col-sm-7">
		<div class="form-group">
			<label class="control-label col-sm-3" for="site_maintenance">Force Site Maintenance</label>
			<div class="col-sm-8">
				<div class="radio">
					<label>
						<input type="radio" name="site_maintenance" id="site_maintenance" value="1" <?php echo (Options::get('site_maintenance','0')=='1') ? 'checked="checked"':''?>> YES
					</label>&emsp;
					<label>
						<input type="radio" name="site_maintenance" value="0" <?php echo (Options::get('site_maintenance','0')=='0') ? 'checked="checked"':''?>> NO
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3" for="site_maintenance_resume_after">Auto Resume After</label>
			<div class="col-sm-8">
				<input type="text" class="form-control <?php echo (Options::get('site_maintenance','0')=='1' and Options::get('site_maintenance_resume','0')=='1') ? 'required':''?>" name="site_maintenance_resume_after" id="site_maintenance_resume_after" 
					placeholder="Date Time" value="<?php echo Options::get('site_maintenance_resume_after','');?>" readonly="readonly">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3" for="site_maintenance_resume">Enable Auto Resume</label>
			<div class="col-sm-8">
				<div class="radio">
					<label>
						<input type="radio" name="site_maintenance_resume" id="site_maintenance_resume" value="1" <?php echo (Options::get('site_maintenance_resume','0')=='1') ? 'checked="checked"':''?>> YES
					</label>&emsp;
					<label>
						<input type="radio" name="site_maintenance_resume" value="0" <?php echo (Options::get('site_maintenance_resume','0')=='0') ? 'checked="checked"':''?>> NO
					</label>
				</div>
			</div>
		</div>
	</div>
    <div class="box-footer col-sm-6 clearfix " style="margin-left:40px;">
		<div class="form-group">
			<div class="col-sm-4">
				<button type="submit" value="Save" class="btn btn-primary" id="submit-maintenance-config">Save</button> 
				<a href="<?php echo site_url('config')?>" class="btn btn-danger">Cancel</a>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	</form>
	<script>
		$(function() {
			if ($('input[name="site_maintenance_resume"]').filter(':checked').val() == 1 && $('input[name="site_maintenance"]').filter(':checked').val() == 1)  $('#site_maintenance_resume_after').addClass('required').addClass('reqd');
			$('#site_maintenance_resume-yes').click(function() {
				$('#site_maintenance_resume_after').addClass('required').addClass('reqd');
				if ($('#site_maintenance_resume_after').prev('label').find('.required').length != 1) $('#site_maintenance_resume_after').prev('label').append('<em class="required">*</em>');
			});
			$('#site_maintenance_resume-no').click(function() {
				$('#site_maintenance_resume_after').removeClass('required').removeClass('reqd').prev('label').find('.required').remove();
			});
			$('#site_maintenance-no').click(function() {
				$('#site_maintenance_resume_after').removeClass('required').removeClass('reqd').prev('label').find('.required').remove();
			});
			$('#site_maintenance-yes').click(function() {
				if ($('input[name="site_maintenance_resume"]').filter(':checked').val() == 1) $('#site_maintenance_resume-yes').trigger('click');
			});

			$('#site_maintenance_resume_after').datetimepicker({dateFormat: 'yy-mm-dd', minDate: 0, changeMonth: true, changeYear: true});
		})
	</script>
</div>