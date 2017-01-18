$(function(){
	$('input.required, textarea.required, select.required').closest('div').prev('label').append('<em class="required">*</em>');
		
	$.validator.addMethod('money',function(value,element){
		if(value <= 0) return false;
		return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
	},"Invalid Amount");
	
	
	$.validator.addMethod('greaterThan',function(value,element,param){
		if(value <= 0) return false;
		
		var other = $(param).val();
		return ( Number(value) > Number(other));
		
	},"Invalid Amount");

	// handle global ajax events
	$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
		var currentUrl = window.location.href;
		var timeout = ['Unauthorized'];
		timeout.indexOf(thrownError);
		if( timeout.indexOf(thrownError) != -1 || thrownError  == 'Unauthorized' ){
			location.reload();
		}
		if( thrownError  == 'Forbidden' ){
			window.location.assign(CMS.config.base_url);
		}
	});
});

function printDiv(divID) {
    var divElements = document.getElementById(divID).innerHTML;

    var oldPage = document.body.innerHTML;
	        
    document.body.innerHTML =  "<body>" +  divElements + "</body>";

    
    window.print();
    document.body.innerHTML = oldPage;
    return true;
}
