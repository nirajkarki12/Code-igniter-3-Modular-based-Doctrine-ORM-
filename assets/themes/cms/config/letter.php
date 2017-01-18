<?php loadPlugin('tinymce/tinymce.min.js', 'js');
// echo "<pre>";print_r($data);echo "</pre>";?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                        <h3 class="box-title"><?=$title?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="col-md-12">
                  	<form class="form-horizontal validate" action="<?php echo site_url('config/settings'); ?>" method="post" id="message-config-form">
                      <input type="hidden" name="params" value="letter-config">
                  		<div class="form-group">
                  			<textarea name="letter_<?php echo $format;?>" id="letter" cols="10" rows="10" class="tinymce"><?php echo $data;?></textarea>
                  		</div>
                  		<div class="form-group">
                  			<div class="col-sm-offset-4 col-sm-8">
                  				<button type="submit" value="Save" class="btn btn-primary" id="submit-widget-config">Save</button> 
                  				<a href="<?php echo site_url('config')?>" class="btn btn-danger">Cancel</a>
                  			</div>
                  		</div>
                  	</form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
tinymce.init({
    relative_urls : false,
    remove_script_host : false,
    document_base_url : '<?php echo base_url(); ?>',
    convert_urls : true,
    selector:'textarea#letter',
    height:'320px',
    menubar:false,
    content_css: "<?php echo base_url('assets/template/style.css');?>",
    plugins: [
    "advlist autolink lists link charmap print preview anchor",
    "searchreplace visualblocks code",
    "table contextmenu template paste"],
    toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | print preview code | template",
    template_popup_width: "900",
    templates : [
        {
          title: "Welcome Letter",
          url: "<?php echo base_url('assets/template/general.html');?>",
          description: "Welcome Letter format of Cards"
        },
        {
          title: "Welcome Letter USD",
          url: "<?php echo base_url('assets/template/usd.html');?>",
          description: "Welcome Letter format of Cards (USD)"
        }
    ],
});
</script>