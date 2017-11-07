jQuery(document).ready(function(){

jQuery('#contactform').submit(function(){

	var action = jQuery(this).attr('action');

	jQuery('#submit')
		.before('<img src="'+$_SKIN_URL+'quickcontact/images/opc-ajax-loader.gif" class="loader" style="padding-right:10px;"/>')
        
		.attr('disabled','disabled');

    
	jQuery.post(action, { 
		name: jQuery('#name').val(),
		email: jQuery('#email').val(),
		telephone: jQuery('#telephone').val(),
		comment: jQuery('#comment').val()
	},
		function(data){
			jQuery('#contactform #submit').attr('disabled','');
			jQuery('.response').remove();
			jQuery('#contactform').before('<span class="response">'+data+'</span>');
			jQuery('.response').slideDown();
			jQuery('#contactform img.loader').fadeOut(500,function(){jQuery(this).remove()});
		}
	);

	return false;

});
});