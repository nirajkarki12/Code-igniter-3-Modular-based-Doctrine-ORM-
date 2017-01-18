<div class="tab-pane" id="report-config">
	<h3 class="heading">Report Listing </h3>
	<table class="table table-striped">
	<thead>
		<tr>
			<th>SN.</th>
			<th>Name</th>
			<th>Description</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$count = isset($offset)?$offset+1:1; 
			foreach($reports as $c):
			if(count($subreports[$c['id']]) > 0):
		?>
		<tr class="toggle">
			<td><strong><?php echo $count++;?></strong></td>
			<td><strong><?php echo $c['name'];?></strong></td>
			<td><?php echo array_key_exists('description', $c) ? $c['description'] :'-';?></td>
			<td class="action"><i class="fa fa-plus"></i></td>
		</tr>
		<?php foreach($subreports[$c['id']] as $subrep):?>
		<tr class="hidethis"  style="display:none;">
			<td></td>
			<td><?php echo $subrep->getName();?></td>
			<td><?php echo ucfirst( $subrep->getDescr());?></td>
			<td>
				<?php 
					if (report_access($c['id'])): 
						echo action_button('arrow', 'report/result/'.$subrep->getSlug(),array('title' =>	'Execute Report Query'))."&emsp;";
						echo action_button('edit', 'report/change/'.$subrep->getSlug(),array('title' =>	'Edit Report Query'))."&emsp;";
						echo action_button('delete', 'report/delete/'.$subrep->getSlug(),array('title' => 'Delete Report'))."&emsp;";
					endif;
				?>
			</td>
		</tr>
	<?php endforeach; endif; endforeach;?>
	</tbody>
	</table>
<?php if(user_access('add report')):?>
	<span><a href="<?php echo site_url('report/editor')?>" class="btn btn-primary">Add a Report</a></span>
<?php endif; ?>
</div><!-- /.tab-pane --> 