
jQuery(document).ready(function() { 
    // jQuery('#prod_social_buttons').prepend('<div class="fb-like" class="fb-like" data-font="verdana" data-href="'+data_href_social+'" data-send="false" data-stream="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>');
	jQuery('#top-social-buttons').prepend('<div class="fb-like" class="fb-like" data-font="arial" data-href="'+data_href_social+'" data-send="false" data-stream="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>');

	jQuery('#social-content-facebook').append('<div id="fb-root"></div>'); 
	jQuery('#social-content-facebook').append('<div class="fb-like" style="height:24px;" class="fb-like" data-href="http://www.facebook.com/AvianneJewelers" data-send="false" data-stream="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>'); 
	
    jQuery.getScript('https://connect.facebook.net/en_US/all.js#xfbml=1', function() { 
		FB.init({status: true, cookie: true, xfbml: true}); 
		FB.Event.subscribe('edge.create',
			    function(response) {
		    		alert('Thank you for connecting with us on Facebook! Your coupon code to save 5% is FB223');
			    }
			);
    }); 
    
    jQuery.getScript('https://platform.twitter.com/widgets.js', function( data, textStatus, jqxhr ) { 
    	twttr.ready(function (twttr) {
    		  twttr.events.bind('tweet', tweetEventAlert);
    	});
    });
    jQuery.getScript('https://assets.pinterest.com/js/pinit.js');
});
(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
function scrollToTop() {
 	top.window.scrollTo(0,0);
 	top.window.focus();
}  
function fixIEbug() {
	if ( navigator.appName == "Microsoft Internet Explorer" ) {
		setTimeout( "scrollToTop()", 2000 );		// delay command for this number of milliseconds
	}
}
function tweetEventAlert (intentEvent) {
	if (!intentEvent) return;
	alert('Thank you for connecting with us on Facebook! Your coupon code to save 5% is FB223');
}
function googleClick(args) {
	alert('Thank you for connecting with us on G+! Your coupon code to save 5% is FB223');
}