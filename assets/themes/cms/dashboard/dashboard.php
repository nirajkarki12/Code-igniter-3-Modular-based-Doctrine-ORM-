<style type="text/css">
.box-header.with-border{
    color: #444;
    background: #FFF;
}
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-sm">
                <div class="box-header with-border">
                        <h3 class="box-title">Dashboard</h3>
                </div><!-- /.box-header --> 
                <div class="box-body">
                <?php if(\Options::get('notice')!=''):?>
                    <div class="col-md-12">
                        <div class="box box-success">
                          <div class="box-header with-border">
                            <h3 class="box-title">Notice</h3>
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                          <div class="box-body">
                                <?php echo \Options::get('notice');?>
                          </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php 
                  WidgetManager::render();
                ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
  $("body").on("click","#remove", function(){
    $(this).closest("div.col-md-4").slideUp(900);
  });
</script>