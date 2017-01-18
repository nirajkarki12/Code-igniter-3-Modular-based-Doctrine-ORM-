<?php 
	loadCss(array('bootstrap.min.css', 
		'font-awesome.min.css',
		'AdminLTE.min.css',
		'jquery.loadmask.css',
		'skins/_all-skins.min.css',	
	)); 

	loadPlugin('ionicons/css/ionicons.min.css', 'css');
?>

<link href="<?php echo $printstyler; ?>" rel="stylesheet" type="text/css" media="print" />
<link href="<?php echo $themestyler; ?>" rel="stylesheet" type="text/css" />
<?php loadPlugin('iCheck/flat/blue.css', 'css') ?>
<?php
	// jQuery 2.1.4
	loadPlugin('jQuery/jQuery-2.1.4.min.js', 'js');
	// jQuery UI 1.11.4
	loadPlugin('jQueryUI/jquery-ui.min.js', 'js');
	//loadPlugin('select2/select2.full.min.js', 'js');
	loadPlugin('jQueryUI/jquery-ui.min.css', 'css');
	// loadJs(array('jquery-ui-1.8.21.custom.min'));
?>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  	$.widget.bridge('uibutton', $.ui.button);
</script>

<?php
	loadJs(array('bootstrap.min.js'));
?>
<script type="text/javascript" src="<?php echo site_url('JS/core')?>"></script>