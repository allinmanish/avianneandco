
jQuery(document).ready(function() {	

	//select all the a tag with name equal to modal
	jQuery('a[name=modal]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = jQuery(this).attr('href');
	
		//Get the screen height and width
		var maskHeight = jQuery(document).height();
		var maskWidth = jQuery(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':100+'%','height':maskHeight});
		
		//transition effect		
		jQuery('#mask').fadeIn(500);	
		jQuery('#mask').fadeTo("slow",0.5);	
	
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
              
		//Set the popup window to center
		jQuery(id).css('top',  20);
		jQuery(id).css('left', winW/2-jQuery(id).width()/2);
		jQuery(id).css('position',  'fixed');
		
		//transition effect
		jQuery(id).fadeIn(500); 
	
	});
	
	//if close button is clicked
	jQuery('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		jQuery('.response').remove();
		jQuery('#mask').hide();
		jQuery('.window').hide();
	});		
	
	//if mask is clicked
	//jQuery('#mask').click(function () {
		// jQuery('.response').remove();
		// jQuery(this).hide();
		// jQuery('.window').hide();
	//});			
	
});
