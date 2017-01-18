<div class="tab-pane" id="message-config">
	<form class="form-horizontal validate" action="<?php echo site_url('config/settings'); ?>" method="post" id="message-config-form">
		<h3 class="heading">Notice</h3>
		<input type="hidden" name="params" value="message-config">
		<div class="form-group">
			<textarea name="notice" id="notice" cols="10" rows="10" class="tinymce"><?php echo \Options::get('notice');?></textarea>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="submit" value="Save" class="btn btn-primary" id="submit-widget-config">Save</button> 
				<a href="<?php echo site_url('config')?>" class="btn btn-danger">Cancel</a>
			</div>
		</div>
	</form>
</div><!-- /.tab-pane -->
<script>
tinymce.init({
    // menu:'off',
    selector:'textarea#notice',
    height:'280px',
    plugins: [
    "advlist autolink lists link charmap print preview anchor",
    "searchreplace visualblocks code image fullscreen",
    "insertdatetime table contextmenu paste filemanager"],
    toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link insertdatetime | print preview code fullscreen",
    //link unlink responsivefilemanager image media
   external_filemanager_path: 'assets/themes/cms/plugins/tinymce/filemanager/',
   filemanager_title:"Filemanager" ,
   external_plugins: { "filemanager" : "filemanager/plugin.min.js"},
});
</script>