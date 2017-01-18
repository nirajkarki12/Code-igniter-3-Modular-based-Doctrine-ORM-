<style>
img.action-image {
	cursor: pointer; 
}
form.inline{
	display: inline;
}
</style>

<?php 
loadPlugin('http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css', 'css');
loadPlugin('datatables/dataTables.bootstrap.css', 'css');
loadPlugin('datatables/jquery.dataTables.min.js', 'js');
loadPlugin('datatables/dataTables.bootstrap.min.js', 'js');

if($filter):?>
<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title">Filter <?php echo ucfirst($report->getName())?></h3>
					<div class="box-tools">
					    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					    </button>
					    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
				<?php echo form_open('','name = "viewreport" method="get" class="validate"');?>
					<?php echo $filter;?>
				<?php echo form_close();?>
				</div>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</section>
<?php endif;?>

<section class="content" style="min-height:auto">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-solid box-sm">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo ucfirst($report->getName())?></h3>
					<?php if ($hasResult):?>
					<?php echo form_open(site_url('report/dumpxls/'.$report->getSlug()), 'name="dumpxls" class="validate inline" method="post"');?>

					<div class="pull-right">
						<?php echo action_button('excel','javascript:void(0)',array('title' =>	"Export Report as XLS", 'class'=>"export-report action-image header"))."&emsp;";?>
					<?php echo $filterValues;?>
					
					</div>
					<?php echo form_close();?>
					<?php endif; ?>
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
<script type="text/javascript">
$(function() {	
	var paging;
	if($("#paginateTable").find('tbody tr').length><?php echo \Options::get('offset');?>){
			paging = true;
	}else {
			paging = false;
	}

	$('#paginateTable').DataTable({
		"paging" : paging,
		// "lengthChange": false,
		"pageLength": <?php echo \Options::get('offset');?>,
		"searching": true,
		"ordering": true,
		"bPaginate": false,
		// "info": true,
		// "autoWidth": false
    });
	$('#clear').bind('click',function() {
		$('.datepicker, .account, .repinstatus, .remarks, .freetext, .cardstatus, .card, .branch, .branchcode').val('');
		return false;
	});

	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
	});
	$('input.datepicker').change(function() {
		$('.generate').show();
	});

	<?php if ($hasResult):?> 
		$('.inner-placeholder .printbtn, .inner-placeholder .export-report').show();
	<?php endif;?>
	$('.filter-area').find('input, textarea, select').bind('keyup, change',function(){
		$('#submit-filter').show();
	}); 

	$('.export-report').click(function() {
		$('form[name="dumpxls"]').submit();
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
	});
});

</script>