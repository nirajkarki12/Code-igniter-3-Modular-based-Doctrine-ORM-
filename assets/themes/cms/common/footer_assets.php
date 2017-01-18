<?php
  loadJS(array(
    'jquery.validate',
    'jquery.loadmask.min',
    'jquery.multi-select'
  ));
?>

<?php 
  loadJs(array('app.min.js'));

  loadJS(array('custom'));
?>
<style type="text/css">
  #noty_topCenter_layout_container{
    top:0 !important;
  }
</style>
<?php 
$notyUI = ((isset($site_maintenance) and is_array($site_maintenance)) or 
  (isset($user_switch) and is_array($user_switch))) ? TRUE : FALSE; 

if ($notyUI): ?>
  <?php loadPlugin('noty/packaged/jquery.noty.packaged.min.js', 'js');
    loadPlugin('noty/css/animate.css', 'css');
  ?>
<?php endif?>


<script type="text/javascript">
if ( ! window.console || typeof console === "undefined" ) {
  console = {};
  console.log = function(arg){};
}

$(function(){
  $('a').click(function (e) {
    // e.preventDefault();
      var anchorText = $(this).html();
      if(anchorText=='<i class="fa fa-check fa-lg fa-lg"></i>')
      {
        $(this).addClass('submit-disabled');
      }
  });
  if($("body").find('a').hasClass('submit-disabled')){
    return false;
  }

  <?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>   
  generateSiteNoty();
  <?php endif?>  
  <?php if (isset($user_switch) and is_array($user_switch)): ?>   
  generateUserNoty();
  <?php endif?> 

  $('form.validate').each(function() {
      $(this).validate({
      errorElement:'span',
      errorPlacement: function(error, element) {
        if (element.attr("type") == "checkbox") {
          error.insertAfter($(element).parent('div').find('span').last());
        } else {
          error.insertAfter(element);
        }
      },
      ignore: ":hidden",
      submitHandler: function(form) {
        //add disable submit button class
        if($(form).find('button[type="submit"],input[type="submit"]').hasClass('submit-disabled'))
        {
          return false;
        }else{
          $(form).find('button[type="submit"],input[type="submit"]').addClass('submit-disabled');
          form.submit();
        }
      }
    });
  });

  $(".multiselect").multiSelect();
  
  $('.cancelaction').click(function(){
    window.history.back();
  });

  $('.backlink').click(function(){
    window.location = $(this).attr('link');
  });
});

<?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>
function generateSiteNoty() {
  
    var s = noty({
      text: '<?php echo $site_maintenance['text']?>',
      type: '<?php echo $site_maintenance['type']?>',
        dismissQueue: true,
      layout: '<?php echo $site_maintenance['layout']?>',
      theme: '<?php echo $site_maintenance['theme']?>',
      closeWith:['button'],
      animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutRight',
            easing: 'swing',
            speed : 300
        }
    });
  }
<?php endif?>

<?php if (isset($user_switch) and is_array($user_switch)): ?> 
function generateUserNoty() { 
    var u = noty({
      text: '<?php echo $user_switch['text']?>',
      type: '<?php echo $user_switch['type']?>',
        dismissQueue: true,
      layout: '<?php echo $user_switch['layout']?>',
      theme: '<?php echo $user_switch['theme']?>',
      closeWith:['button'],
      animation   : {
            open  : 'animated bounceInLeft',
            close : 'animated bounceOutRight',
            easing: 'swing',
            speed : 300
        }
    });
  }
<?php endif?>

var weekdaystxt=["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"]

function showLocalTime(container, servermode, offsetMinutes, displayversion){
    if (!document.getElementById || !document.getElementById(container)) return
    this.container=document.getElementById(container)
    this.displayversion=displayversion
    var servertimestring=(servermode=="server-php")? '<?php print date("F d, Y H:i:s", time())?>' : (servermode=="server-ssi")? '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' : '<%= Now() %>'
    this.localtime=this.serverdate=new Date(servertimestring)
    this.localtime.setTime(this.serverdate.getTime()+offsetMinutes*60*1000) //add user offset to server time
    this.updateTime()
    this.updateContainer()
}

showLocalTime.prototype.updateTime=function(){
    var thisobj=this
    this.localtime.setSeconds(this.localtime.getSeconds()+1)
    setTimeout(function(){thisobj.updateTime()}, 1000) //update time every second
}

showLocalTime.prototype.updateContainer=function(){
    var thisobj=this
    if (this.displayversion=="long")
        this.container.innerHTML=this.localtime.toLocaleString()
    else{
        var hour=this.localtime.getHours()
        var minutes=this.localtime.getMinutes()
        var seconds=this.localtime.getSeconds()
        var ampm=(hour>=12)? "PM" : "AM"
        var dayofweek=weekdaystxt[this.localtime.getDay()]
        this.container.innerHTML=+formatField(hour, 1)+":"+formatField(minutes)+":"+formatField(seconds)+" "+ampm+" "
    }
    setTimeout(function(){thisobj.updateContainer()}, 1000) //update container every second
}

function formatField(num, isHour){
    if (typeof isHour!="undefined"){ //if this is the hour field
        var hour=(num>12)? num-12 : num
        return (hour==0)? 12 : hour
    }
        return (num<=9)? "0"+num : num//if this is minute or sec field
}
function checkTimeOut(cond)
{
    if(cond){
        $(location).attr('href', "<?php echo site_url();?>");
     }
}

function chkList(selector, msg)
  {
    var total = $(selector+':checked').length;
    if(total ==0)
    {
      $("body").find('#errorModal').modal('show');
      $('#errortitle').html('Error');
      $('#errorcontent').html(msg);
      return false;
    }else return true;
    
  }
</script>
<!-- Modal box for Error -->
<div class="modal modal-default fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="errortitle"></h4>
      </div>
      <div class="modal-body" id="errorcontent">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>