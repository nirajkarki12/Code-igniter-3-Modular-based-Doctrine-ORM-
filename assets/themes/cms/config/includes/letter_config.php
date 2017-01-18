<div class="tab-pane" id="letter-config">
		<h3 class="heading">Welcome Letter</h3>
		<div class="form-group">
			<table class="table table-striped">
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
        <tr>
          <td>Welcome Letter General</td>
          <td>Welcome Letter format of Cards (General)</td>
          <td><?php echo action_button('edit', 'config/letter/general',array('title' =>  'Edit Welcome Letter'))."&emsp;";?></td>
        </tr>
        <tr>
          <td>Welcome Letter USD</td>
          <td>Welcome Letter format of Cards (USD)</td>
          <td><?php echo action_button('edit', 'config/letter/usd',array('title' =>  'Edit Welcome Letter'))."&emsp;";?></td>
        </tr>
      </table>
		</div>
</div><!-- /.tab-pane -->
