<style>
    #ui-datepicker-div{z-index: 9999!important;}
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
	
	label.right {float: none; display: inline;}
	.tab-content .table tr td{
		padding: 8px;
	}
  	.toggle{cursor: pointer}
</style>

<?php 
loadPlugin('tinymce/tinymce.min.js', 'js');

loadJS(array('datetimepicker',))?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->config->item('project_name'); ?> Settings</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<div class="nav-tabs-custom">
                		<ul class="nav nav-tabs">
                			<li class="active"><a aria-expanded="true" href="#general-config" data-toggle="tab">General</a></li>
                			<?php if(\WidgetManager::view()){?><li><a href="#widgets-config" data-toggle="tab">Widgets</a></li><?php }?>
                			<li><a href="#message-config" data-toggle="tab">Notice</a></li>
                            <?php if(\Options::get('letter')){?><li><a href="#letter-config" data-toggle="tab">Welcome Letter</a></li><?php }?>
                			<?php if(user_access('view report')){?><li><a href="#report-config" data-toggle="tab">Reports</a></li><?php }?>
                			<li><a href="#maintenance-config" data-toggle="tab">Site Maintenance</a></li>
                		</ul>
                		<div class="tab-content">
            				<?php $this->load->theme('config/includes/general_config');?>
	            			<?php if(\WidgetManager::view()){?>
            					<?php $this->load->theme('config/includes/widgets_config');?>
	            			<?php }?>    
            				<?php $this->load->theme('config/includes/message_config');?>
                            <?php $this->load->theme('config/includes/letter_config');?>
	            			<?php if(user_access('view report')){?>
	            				<?php $this->load->theme('config/includes/report_config');?>
	            			<?php }?>

            				<?php $this->load->theme('config/includes/maintenance_config');?>
            			</div><!-- /.tab-content -->
            		</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function(){
	$('.fa-trash').bind('click',function(){
		return confirm('Are you sure to delete this report?');
	});

	$('tr.toggle').bind('click',function(ev){
	  	var that = $(this);
       
	  	$(that).nextAll('tr.hidethis').each(function(){
		  	$(this).slideToggle(1);
            if($(".hidethis").is(":hidden")) {
                $(this).prev("tr").find("td.action").html('<i class="fa fa-minus"></i>');
            } else {
                $(this).prev("tr").find("td.action").html('<i class="fa fa-plus"></i>');
            }
		  	if($(this).next().hasClass('toggle')) return false;
	  	});
  	});

	$("body").on("click",".child", function(){
    	var totalChild = $(".child").length;
    	var activeChecked = $(".child:checked").length;
    	if(totalChild == activeChecked)
    	{
    		$("body").find("#parent").prop('checked', true);
    	}else{
    		$("body").find("#parent").prop('checked', false);
    	}

    });
	if($("#chargeY").is(':checked'))
    {
        $("body").find(".charge").css('display', 'block');
        $("body").find("#receiver-account").addClass('required');
        $("body").find("#charge_url").addClass('required');
    }
	$("body").on("click","#chargeY", function(){
		$("body").find(".charge").css('display', 'block');
        $("body").find("#receiver-account").addClass('required');
        $("body").find("#charge_url").addClass('required');

	});

    $("body").on("click","#chargeN", function(){
        $("body").find(".charge").css('display', 'none');
        $("body").find("#receiver-account").removeClass('required');
        $("body").find("#charge_url").removeClass('required');

    });

    $("body").on("click","#parent", function(){
        if($(this).is(':checked'))
        {
            $("body").find(".child").prop('checked', true);
        }else{
            $("body").find(".child").prop('checked', false);
        }
    });

	if ($("body").find('.child:checked').length == $('.child').length) {
		$("body").find("#parent").prop('checked', true);
	}
	var hash = window.location.hash;
  hash && $('ul.nav a[href="' + hash + '"]').tab('show');
});
</script>
