/*-----------------------------------------------------------------------------------

FILE INFORMATION

Description: JavaScript on the "Bold News" WooTheme.
Date Created: 2011-01-17.
Author: Matty.
Since: 1.0


TABLE OF CONTENTS

- Featured Slider Setup (jCarouselLite)
- Featured Slider Hover Event

-----------------------------------------------------------------------------------*/
var woo_jcarousellite_settings = {"autoStart":"1","interval":"5","speed":"1500","hoverPause":"1","visible":"4","scroll":"1","circular":"1"};
jQuery(function($) {
	
	if ( jQuery('.slides').length ) {	

/*-----------------------------------------------------------------------------------
  Featured Slider Setup (jCarouselLite)
-----------------------------------------------------------------------------------*/
	
		// Setup dynamic variables.
		
		var autoStart = Boolean( woo_jcarousellite_settings.autoStart );
		var autoInterval = parseInt( woo_jcarousellite_settings.interval );
		var speed = parseInt( woo_jcarousellite_settings.speed );
		var visible = parseInt( woo_jcarousellite_settings.visible );
		var scroll = parseInt( woo_jcarousellite_settings.scroll );
		var hoverPause = Boolean( woo_jcarousellite_settings.hoverPause );
	
		// Setup carousel parameters.
		
		var carouselArgs = {
		
			//circular: true, 
			speed: speed, 
			visible: visible, 
			btnNext: '.btn-next', 
			btnPrev: '.btn-previous', 
			scroll: scroll, 
			easing: 'easeOutQuad', 
			hoverPause: hoverPause
			
		}
		
		// If the auto start setting is enabled, add the auto start functionality.
		
		if ( autoStart ) {
		
			carouselArgs['auto'] = autoInterval * 1000;
		
		} // End IF Statement
		
		// Instantiate the carousel.
	
		jQuery('.slides').jCarouselLite( carouselArgs );

/*-----------------------------------------------------------------------------------
  Featured Slider Hover Event
-----------------------------------------------------------------------------------*/
		
		jQuery('.slides .slide').hover(
		
			// Over...
			function() {
				
				jQuery(this).addClass('hover');
				
			}, 
			// ...and Out.
			function() {
				
				jQuery(this).removeClass('hover');
				
			}
			
		);

	} // End IF Statement
	
});