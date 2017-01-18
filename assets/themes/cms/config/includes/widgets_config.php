<div class="tab-pane" id="widgets-config">
	<form class="form-horizontal validate" action="<?php echo site_url('config/settings'); ?>" method="post" id="widgets-config-form">
		<h3 class="heading">Widgets</h3>
		<input type="hidden" name="params" value="widgets-config">
		<div class="form-group">
			<table class="table table-striped">
				<tr>
					<th><input type="checkbox" id="parent" title="select all"></th>
					<th><label for="parent">Name</label></th>
					<th>Description</th>
				</tr>
			<?php foreach (\WidgetManager::view() as $widget):?>
				<tr>
					<td>
						<input type="checkbox" value="<?= $widget['ID']?>" name="widgets[]" class="child" id="<?= $widget['ID']?>" <?php if(isset($widgetArray) && is_array($widgetArray) && in_array($widget['ID'], $widgetArray)){ echo "checked";}?>>
					</td>
					<td><label for="<?= $widget['ID']?>"><?= $widget['name']?></label></td>
					<td><?= $widget['description']?></td>
				</tr>
			<?php endforeach;?>
			</table>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<button type="submit" value="Save" class="btn btn-primary" name="widget" id="submit-widget-config">Save</button> 
					<a href="<?php echo site_url('config')?>" class="btn btn-danger">Cancel</a>
				</div>
			</div>
		</div>
	</form>
</div><!-- /.tab-pane -->