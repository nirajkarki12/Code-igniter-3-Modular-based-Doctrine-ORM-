<div>
<?php if(array_key_exists('online_users', $data) && !empty($data['online_users'])){ 
		for($i=0; $i<count($data['online_users']); $i++){
	?>
	<p class="widget">
	<?php echo $data['online_users'][$i] ?>
	</p>
<?php }}else{?>
	<p class="widget">
	<label>Online users not available</label>
	</p>
<?php }?>

</div>
