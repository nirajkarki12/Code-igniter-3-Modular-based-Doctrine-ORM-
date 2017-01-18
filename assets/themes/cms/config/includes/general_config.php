<div class="tab-pane active" id="general-config">
	<form class="form-horizontal validate" action="<?php echo site_url('config/settings'); ?>" method="post" id="general-config-form">
		<h3 class="heading">General Setting</h3>
		<input type="hidden" name="params" value="general-config">
		<div class="col-sm-7">
			<div class="form-group">
				<label for="site_title" class="col-sm-2 control-label">Site Title </label>
				<div class="col-sm-9">
					<input type="text" id="site_title" name="site_title" value="<?= Options::get('site_title');?>" class="form-control required" autocomplete="off" title="Site Title should be following format: A-Z/a-z/0-9/_" pattern="[0-9A-Za-z_]+" placeholder="bank title" minlength="3" maxlength="50">
				</div>
			</div>
			<div class="form-group">
				<label for="date_format" class="col-sm-2 control-label">Date Format </label>
				<div class="col-sm-9">
					<select name="date_format" id="date_format" class="form-control required">
	                    <option value="">-select-</option>
	                    <option value="Y-m-d" <?php if(Options::get('date_format')=='Y-m-d'){echo "selected";}?>>2017-01-01</option>
	                    <option value="d-m-Y" <?php if(Options::get('date_format')=='d-m-Y'){echo "selected";}?>>01-01-2017</option>
	                    <option value="Y/m/d" <?php if(Options::get('date_format')=='Y/m/d'){echo "selected";}?>>2017/01/01</option>
	                    <option value="d/m/Y" <?php if(Options::get('date_format')=='d/m/Y'){echo "selected";}?>>01/01/2017</option>
	                    <option value="Y-m-d H:i" <?php if(Options::get('date_format')=='Y-m-d H:i'){echo "selected";}?>>2017-01-01 13:11</option>
	                    <option value="Y-m-d h:i a" <?php if(Options::get('date_format')=='Y-m-d h:i a'){echo "selected";}?>>2017-01-01 01:11 pm</option>
	                    <option value="d M, Y h:i a" <?php if(Options::get('date_format')=='d M, Y h:i a'){echo "selected";}?>>01 Jan, 2017 01:11 pm</option>
	                    <option value="D M d, Y h:i a" <?php if(Options::get('date_format')=='D M d, Y h:i a'){echo "selected";}?>>Sun Jan 01, 2017 01:11 pm</option>
	                </select>
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">Email Address </label>
				<div class="col-sm-9">
					<input type="text" id="email" name="email" value="<?= Options::get('email');?>" class="form-control email required" autocomplete="off" placeholder="email address">
				</div>
			</div>


			<div class="form-group">
				<label for="offset" class="col-sm-2 control-label">Data Per Page </label>
				<div class="col-sm-9">
					<select name="offset" id="offset" class="form-control required">
	                    <option value="">-select-</option>
	                    <option value="10" <?php if(Options::get('offset')=='10'){echo "selected";}?>>10</option>
	                    <option value="25" <?php if(Options::get('offset')=='25'){echo "selected";}?>>25</option>
	                    <option value="50" <?php if(Options::get('offset')=='50'){echo "selected";}?>>50</option>
	                    <option value="100" <?php if(Options::get('offset')=='100'){echo "selected";}?>>100</option>
	                </select>
				</div>
			</div>
			<div class="form-group">
				<label for="sliderY" class="col-sm-2 control-label">Slider </label>
				<div class="col-sm-9">
					<div class="radio">
						<label>
							<input type="radio" name="slider" id="sliderY" value="1" <?php echo (Options::get('slider','0')==1) ? 'checked="checked"':''?>> YES
						</label>&emsp;
						<label>
							<input type="radio" name="slider" id="sliderN" value="0" <?php echo (Options::get('slider','0')==0) ? 'checked="checked"':''?>> NO
						</label>
					</div>
				</div>
			</div>
		</div>
        <div class="box-footer col-sm-6 clearfix">
			<div class="col-sm-12">
				<button type="submit" value="Save" class="btn btn-primary" id="submit-general-config">Save</button> 
				<a href="<?php echo site_url('config')?>" class="btn btn-danger">Cancel</a>
			</div>
		</div>
		<div class="clearfix"></div>
	</form>
</div><!-- /.tab-pane -->