$(function() {
	$('#slider_callout').hide();
	var calloutVisible = false;
	$('.slider_bar').slider({
		handle: '.slider_handle',
		minValue: 0,
		maxValue: 25,
		start: function(e, ui) {
			$('#slider_callout').fadeIn('fast', function() { calloutVisible = true;});
		},
		stop: function(e, ui) { 
			if (calloutVisible == false) {
				$('#slider_callout').fadeIn('fast', function() { calloutVisible = true;});
				$('#slider_callout').css('left', ui.handle.css('left')).text(Math.round(ui.value));
			}
			$('#slider_callout').fadeOut('fast', function() { calloutVisible = false; });
		},
		slide: function(e, ui) {
			$('#slider_callout').css('left', ui.handle.css('left')).text(Math.round(ui.value));
		}
	});
});

