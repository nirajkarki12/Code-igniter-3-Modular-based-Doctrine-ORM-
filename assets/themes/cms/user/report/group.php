<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    table th,td{
        text-align: left;
    }
    .heading th{
      background: #CCC !important;
    }
    .odd td
    {
        background: #F1F1F1 !important;
    }
</style>
</head>
<body>

<table border="0" cellpadding="5" cellspacing="1" width="100%" class="sortable">
    <thead>
		<tr class="heading">
		    <th>S.N</th>
		    <th>Group Name</th>
			<th>Description</th>
			<th>Number of Users</th>
			<th>Status</th>
		</tr>
    </thead>
    <tbody>
<?php if(isset($groups) && count($groups)>0):
	$count = isset($offset) ? $offset+1 : 1;
	foreach($groups as $g):
		$class = ($count%2==0) ? "odd" :"even";   
?>
    <tr class="<?= $class?>">
        <td><?= $count++ ?></td>
		<td><?php echo $g->getName();?></td>
		<td><?php echo ($g->getDescription())?:'N/A';?></td>
		<td><?php echo ($g->getUsers()) ? count($g->getUsers()) : 0 ?></td>
		<td><?php echo \models\Group::$status_types[$g->getStatus()];?></td>
    </tr>
    <?php
        endforeach; //end of foreach
    ?>
<?php else: ?>
	<tr>
  		<td colspan="4">No record found.</td>
  	</tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>