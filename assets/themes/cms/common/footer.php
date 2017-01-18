	</div><!-- ./wrapper -->
		
<footer class="main-footer">
<div class="pull-right hidden-xs">
  	<b>Version</b> 1.0
</div>
<strong>Copyright &copy; <?=date('Y')?> <a href="<?php echo site_url(); ?>"><?php echo $this->config->item('project_name'); ?></a>.</strong> All rights reserved.
</footer>
	<?php sm_get_footer_assets(); ?>
	<script type="text/javascript">
		$(function() {
			// this will get the full URL at the address bar
			var url = window.location.href;
			// passes on every "a" tag
			$(".sidebar-menu a").each(function() {
				// checks if its the same on the address bar
				if (url == (this.href)) {
					$(this).closest("li").addClass("active");
					$(this).parents('li').addClass('menu-open active');
					$(this).parents('ul').addClass('menu-open');
					$(this).parents('ul').css('display', 'block');
				}
			});
		});
	</script>
</body>
</html>