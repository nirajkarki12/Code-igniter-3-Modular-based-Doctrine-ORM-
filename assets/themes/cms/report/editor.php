<?php 
loadPlugin('codemirror/lib/codemirror.css', 'css');
loadPlugin('codemirror/lib/codemirror.js', 'js');
loadPlugin('codemirror/mode/sql/sql.js', 'js');
loadPlugin('codemirror/addon/hint/sql.js', 'js');
loadPlugin('codemirror/addon/fold/foldcode.js', 'js');
loadPlugin('codemirror/addon/edit/closetag.js', 'js');
loadPlugin('codemirror/addon/hint/show-hint.css', 'css');
loadPlugin('codemirror/addon/hint/show-hint.js', 'js');
loadPlugin('codemirror/addon/hint/sql-hint.js', 'js');
loadPlugin('codemirror/theme/mdn-like.css', 'css');
loadPlugin('codemirror/addon/scroll/simplescrollbars.css', 'css');
loadPlugin('codemirror/addon/scroll/simplescrollbars.js', 'js');
loadPlugin('codemirror/addon/dialog/dialog.css', 'css');
loadPlugin('codemirror/addon/dialog/dialog.js', 'js');
loadPlugin('codemirror/addon/search/search.js', 'js');
loadPlugin('codemirror/addon/search/searchcursor.js', 'js');

echo form_open('','name = "addreport" class="form-horizontal validate"');?>
<style type="text/css">
	.CodeMirror{
		border: 1px solid #DDD;
		border-left: none;
	}
</style>
<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Add a Report Query</h3>
				</div><!-- /.box-header -->
			<div class="box-body">
				<div class="form-group">
                    <label class="col-sm-1 control-label" for="sqlquery">SQL Query</label>
                    <div class="col-sm-12">
                    <textarea id="sqlquery" cols=80 rows=8 name="sqlquery" autocomplete="off" class="form-control required" style="resize:none;" ><?php echo $this->input->post('sqlquery')?></textarea>
                    <script type="text/javascript">
						var editor = CodeMirror.fromTextArea(document.getElementById("sqlquery"), {
							mode: 'text/x-sql',
							theme : 'mdn-like',
							autoCloseTags: true,
							extraKeys: {"Ctrl-Space": "autocomplete"},
							lineNumbers: true,
							matchBrackets : true,
							lineWrapping: true,
						    indentWithTabs: true,
						    smartIndent: true,
						    autofocus: true,
						    scrollbarStyle: 'simple',
						    lineWiseCopyCut: true,
						    undoDepth: 5000,
						    dragDrop: true,
						    hintOptions: {tables: {
						    	<?php foreach ($tables as $key=>$table) {?>
						    		<?php echo $key;?>:
						    		[
							    		<?php foreach ($table as $col) {
							    			echo '"'.$col.'",';
							    		} ?>
						    		],
						    	<?php }?>
						    }}
						});
                    </script>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
							<input type="submit" class="btn btn-primary" value="Generate Report" name="gen-report" id="report-gen">
							<?php if(isset($query_result)):?>
							<button class="btn btn-primary" id="save">Save</button>
							<?php endif;?>
                            <a href="<?php echo site_url('report/editor')?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
        	</div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
<?php echo form_close();?>

<?php 
if(isset($query_result)) $this->load->theme('report/result');
?>
</section>
<?php 
if(isset($query_result)):
echo form_open('','name = "savereport" class="form-horizontal validate2"');?>
<!-- Modal box for Error -->
<div class="modal modal-default fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="title">Save Report</h4>
      </div>
      <div class="modal-body" id="content">
      	<input type="hidden" name="sqlquery" id="query">
		<table class="table table-hover">
      		<tr>
      			<td><label class="control-label" for="role">Groups<em class="required">*</em></label></td>
      			<td>
      				<select id ="role" name="usrgrp[]" class="form-control required usr-grp" multiple="true">
	                    <?php foreach ($groups as $g): 
                            $sel = isset($post) ? set_select('groups', $g['group_id']) : '';
	                    ?>
	                        <option value="<?php echo $g['group_id']; ?>"
	                        	<?php echo $sel;?>>
	                            <?php echo $g['name']; ?>
	                        </option>
	                    <?php endforeach; ?>
	                </select>
               	</td>
      		</tr>
      		<tr>
	      		<td><label class="control-label" for="reportname">Name<em class="required">*</em></label></td>
	      		<td><input type="text" name="name" value="<?php echo set_value('name')?>" class="form-control required" id="reportname" autocomplete="off" placeholder="Report Name"></td>
      		</tr>
      		<tr>
	      		<td><label class="" for="description">Description<em class="required">*</em></label></td>
	      		<td><textarea id="description" name="description" autocomplete="off" class="form-control required" maxlength="100" style="resize:none" pattern="[A-Za-z\s]+" placeholder="Report Description"></textarea></td>
      		</tr>
      		<?php if (count($reportgroups) > 0):?>
      		<tr>
	      		<td><label class="control-label" for="reportgroup_id">Menu Group</label></td>
	      		<td>
	      			<select id ="reportgroup_id" name="reportgroup_id" class="form-control required">
                        <option value="">--select--</option>
	                    <?php foreach ($reportgroups as $rg): ?>
	                        <option value="<?php echo $rg['id']; ?>">
	                            <?php echo $rg['name']; ?>
	                        </option>
	                    <?php endforeach; ?>
	                </select>
	      		</td>
      		</tr>
			<?php endif; ?>
      		<tr>
	      		<td><label class="control-label" for="reportgroup"><?= count($reportgroups) > 0 ? 'or': '';?> New Menu Group<em class="required">*</em></label></td>
	      		<td><input type="text" id="reportgroup" name="reportgroup" autocomplete="off" class="form-control required toggle-reqd" maxlength="30"  placeholder="<?php echo (count($reportgroups) > 0) ? 'overrides existing menu group' : ''; ?>" size="30" maxlength="30"></td>
      		</tr>
      		<tr>
	      		<td></td>
	      		<td><button type="submit" class="btn btn-primary" name="save-report" id="report-submit" value="Save">Save</button>
        			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></td>
      		</tr>
      	</table>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();
endif;?>
