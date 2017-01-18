<style type="text/css">
	.box{
		margin-top: 10px;
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

<script type="text/javascript">
$(function() {
	
	$("body").on("click","#save", function(event){
		event.preventDefault();
		$('#myModal').modal('show');
	});
	
	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
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

	$('input.datepicker').change(function() {
		$('#report-gen').val('Regenerate Report');
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
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