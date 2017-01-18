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

loadPlugin('http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css', 'css');
loadPlugin('datatables/dataTables.bootstrap.css', 'css');
loadPlugin('datatables/jquery.dataTables.min.js', 'js');
loadPlugin('datatables/dataTables.bootstrap.min.js', 'js');

echo form_open('','name = "addreport" class="form-horizontal validate"');?>
<style type="text/css">
	/*.content-wrapper{
		min-height: 560px !important;
	}*/
</style>
<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $report->getGroup()->getName().'/'.$report->getName();?></h3>
				</div><!-- /.box-header -->
			<div class="box-body">
				<div class="form-group">
                    <label class="col-sm-1 control-label" for="sqlquery">SQL Query</label>
                    <div class="col-sm-12">
                    <textarea id="sqlquery" cols=80 rows=8 name="sqlquery" autocomplete="off" class="form-control required" style="resize:none;" ><?php echo ($editmode) ? $this->input->post('sqlquery') : $report->getSqlQuery()?></textarea>
                    <script type="text/javascript">
						var editor = CodeMirror.fromTextArea(document.getElementById("sqlquery"), {
							mode: 'text/x-sql',
							theme : 'mdn-like',
							autoCloseTags: true,
							extraKeys: {"Ctrl-Space": "autocomplete"},
							lineNumbers: true,
							matchBrackets : true,
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
							<input type="submit" class="btn btn-primary" value="Regenerate Report" name="gen-report" id="report-gen"/>
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
</section>
<?php echo form_close();?>

<?php if(isset($filter) && isset($query_result)):?>
<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Preview Filter</h3>
					<div class="box-tools">
					    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					    </button>
					    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<?php echo $filter;?>
				</div>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>
<?php endif;?>

<?php if (isset($query_result)):?>
<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Preview Report</h3>
				</div><!-- /.box-header -->
				<div class="box-body table-responsive">
					<?php echo $query_result;?>
				</div><!-- /.box -->
				<?php if(isset($pagination)):?>
					<div class="box-footer clearfix">
						<?php echo $pagination; ?>
					</div>
				<?php endif;?>
			</div>
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>
<?php endif;?>

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

		            	$sel = ($g['group_id']==1) ? 'selected': '';
						if ($g['group_id']!=1) {
							$sel = (in_array($g['group_id'], $report->getUserGroups())) ? 'selected': '';
						}
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
				<td><input type="text" name="name" value="<?= $editmode ? $report->getName() : $this->input->post('name')?>" class="form-control required" id="reportname" autocomplete="off" placeholder="Report Name"></td>
			</tr>
			<tr>
				<td><label class="" for="description">Description<em class="required">*</em></label></td>
				<td><textarea id="description" name="description" autocomplete="off" class="form-control required" maxlength="100" style="resize:none" pattern="[A-Za-z\s]+" placeholder="Report Description"><?= $editmode ? $report->getDescr() : $this->input->post('description')?></textarea></td>
			</tr>
			<?php 
			$hasGroup = ! is_null($report->getGroup());
			if (count($reportgroups) > 0):?>
			<tr>
				<td><label class="control-label" for="reportgroup_id">Menu Group</label></td>
				<td>
					<select id ="reportgroup_id" name="reportgroup_id" class="form-control required">
		            <?php 
		            if (!$hasGroup) echo '<option value=""> -- Select -- </option>'; 
					foreach ($reportgroups as $rg):
						$sel = ($hasGroup and $rg['id'] == $report->getGroup()->id()) ? 'selected="selected"' : '';
						echo "<option value='{$rg['id']}' {$sel} > {$rg['name']} </option>";
		            endforeach; ?>
		        </select>
				</td>
			</tr>
		<?php endif; ?>
			<tr>
				<td><label class="control-label" for="reportgroup"><?= count($reportgroups) > 0 ? 'or': '';?> New Menu Group</label></td>
				<td><input type="text" id="reportgroup" name="reportgroup" autocomplete="off" class="form-control toggle-reqd" maxlength="30"  placeholder="<?php echo (count($reportgroups) > 0) ? 'overrides existing menu group' : ''; ?>" 
						class="<?php echo (count($reportgroups) == 0 or ! $hasGroup) ? 'required toggle-reqd' : ''; ?>" size="30" maxlength="30"></td>
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
<?php echo form_close();endif;?>

<script type="text/javascript">
$(function() {		
	$("body").on("click","#save", function(event){
		event.preventDefault();
		$('#myModal').modal('show');
	});
							
	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
	});

	$('#report-gen').bind('click',function(){
		$('#reportname, #reportdescr, .toggle-reqd').removeClass('required');
	});

	if($('#reportgroup_id').val() == '') 
	{
		$('.toggle-reqd').addClass('required');
	}else{
		$('#reportgroup_id').addClass('required');
	}

	$('#report-submit').bind('click',function(){
		$("#query").val($("#sqlquery").val());
		
		$(this).closest("form").validate({
			errorElement:'span',
			errorPlacement: function(error, element) {
				if (element.attr("type") == "checkbox") {
					error.insertAfter($(element).parent('div').find('span').last());
				} else {
					error.insertAfter(element);
				}
			},
			ignore: ":hidden"
		});
	});

	$('#sqlquery').bind('keyup',function(){
		$('.output').hide(); 
		$('.generate').show();
	});

	$('input.datepicker').change(function() {
		$('.generate').show();
	});

	$('#revert-change').click(function(){
		window.location = '<?php echo current_url()?>'
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
	});

	$('.filter-area').find('input, textarea, select').bind('keyup, change',function(){
		$('.generate').show();
	}); 

	$('.export-report').click(function() {
		$('form[name="dumpxls"]').submit();
	});

	$('#reportgroup_id').change(function(){
		if ($(this).val() != '') {
			 $('.toggle-reqd').removeClass('required').val('');
			 $('.toggle-error').find('span.error, em.required').hide();
			  
		} else {
			$('.toggle-reqd').addClass('required');
			$('.toggle-error').find('em.required').show();
		}
	});
	var paging;
	if($("#paginateTable").find('tbody tr').length><?php echo \Options::get('offset');?>){
			paging = true;
	}else {
			paging = false;
	}
    $('#paginateTable').DataTable({
      "paging": paging,
      // "lengthChange": false,
      "pageLength": <?php echo \Options::get('offset');?>,
      "searching": true,
      "ordering": true,
      // "info": true,
      // "autoWidth": false
    });
});

</script>