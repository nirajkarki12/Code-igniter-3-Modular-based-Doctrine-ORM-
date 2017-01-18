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
		    <th>Username</th>
			<th>Full Name</th>
			<th>Email Address</th>
			<th>Groups</th>
			<th>Status</th>
			<th>Force Password Change</th>
			<th>Created</th>
		</tr>
    </thead>
    <tbody>
<?php if(isset($users) && count($users)>0):
	$count = isset($offset) ? $offset+1 : 1;
	foreach($users as $u):
		$class = ($count%2==0) ? "odd" :"even";   
?>
    <tr class="<?= $class?>">
        <td><?= $count++ ?></td>
		<td><?php echo $u->getUsername();?></td>
		<td><?php echo $u->getName();?></td>
		<td><?php echo $u->getEmail();?></td>
		<td><?php
				foreach ($u->getGroups() as $i=>$group) {
					$grpCount = count($u->getGroups());
					if($grpCount == 1 && $group->isDeleted()){
						echo "<strong><em style='color: red;'>Deleted</em></strong>";
						echo (!$u->isDeleted())? " <strong><em>(Re-Assign)</em></strong>" : '';
						continue;
					}elseif($group->isDeleted()){
						continue;
					}
					echo $i==0 || $i==$grpCount?'':', ';
					echo $group->getName();
				}
			?>
		</td>
		<td><?php echo \models\User::$status_types[$u->getStatus()];?></td>
		<td>&nbsp;<?php echo $u->isFirstLogin() ? 'Enabled' : '';?></td>
		<td>&nbsp;<?php echo $u->getCreatedAt()->format(\Options::get('date_format'));?></td>
    </tr>
    <?php
        endforeach; //end of foreach
    ?>
<?php else: ?>
	<tr>
  		<td colspan="12">No record found.</td>
  	</tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>