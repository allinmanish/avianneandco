jQuery(function() {
	if( document.location.hash.length != 0 ) {
		jQuery('.customer-service-acc dd, .customer-service-acc dt').hide();
		jQuery(document.location.hash).show().next().show();
	}
	jQuery('.customer-service-navy li a, .homepage-footer-table .custom-serv a').click(function(e) {
		var idItem = jQuery(this).attr('href');
		if(idItem.indexOf('/customer-service/')==0) {
			idItem = idItem.replace(/\/customer-service\//g,'');
		}
		jQuery('.customer-service-acc dd, .customer-service-acc dt').hide();
		jQuery(idItem).show().next().show();
		e.preventDefault();
	});
	jQuery('a.faq').click(function(e) {
		var idItem = jQuery(this).attr('href');
		var new_position = jQuery(idItem).offset();
	    window.scrollTo(new_position.left,new_position.top);
		e.preventDefault();
	});
	jQuery('.customer-service-home div').click(function(e) {
		var idItem = jQuery(this).attr('class');
		jQuery('.customer-service-acc dd, .customer-service-acc dt').hide();
		jQuery(idItem).show().next().show();
		e.preventDefault();
	});
})