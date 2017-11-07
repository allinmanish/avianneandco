/**
 * GoMage.com
 * 
 * GoMage Feed Pro
 * 
 * @category Extension
 * @copyright Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author GoMage.com
 * @license http://www.gomage.com/licensing Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version Release: 3.0
 * @since Class available since Release 1.0
 */

function gomagefeed_validate_interval(from, to, v){
	if (from == to)
		return false;

	if (!to)
		to = 24;

	if (from > to) {
		if (((24 - from) + to - 1) < v * 1)
			return false;
		else
			return true;
	} else {
		if ((to - from) < v * 1)
			return false;
		else
			return true;
	}
}

Validation.add('gomage-feed-validate-upload-interval',
		'Time range is small for Upload Interval', function(v) {

			if (v * 1 > 6) {
				return true;
			}

			var control = $('upload_hour');
			var text = control.options[control.selectedIndex].innerHTML;
			var from = text.substring(0, 2) * 1;

			var control = $('upload_hour_to');
			var text = control.options[control.selectedIndex].innerHTML;
			var to = text.substring(0, 2) * 1;

			return gomagefeed_validate_interval(from, to, v);

		});

Validation.add('gomage-feed-validate-generate-interval',
		'Time range is small for Generate Interval', function(v) {

			if (v * 1 > 6) {
				return true;
			}

			var control = $('generate_hour');
			var text = control.options[control.selectedIndex].innerHTML;
			var from = text.substring(0, 2) * 1;

			var control = $('generate_hour_to');
			var text = control.options[control.selectedIndex].innerHTML;
			var to = text.substring(0, 2) * 1;
			
			return gomagefeed_validate_interval(from, to, v);

		});

function gomagefeed_setinterval(control, element_id) {
	if (control.value <= 6) {
		$(element_id).selectedIndex = 0;
		$(element_id).enable();
	} else {
		$(element_id).selectedIndex = 0;
		$(element_id).disable();
	}
}

GomageFeedAdminSettings = Class.create({
			system_sections : null,

			initialize : function(data) {
				this.system_sections = data.data;
				this.url = data.url;

				if ($('feed_system')) {
					$('feed_system').options[$('feed_system').options.length] = new Option(
							'-Select-', '', false, false);
					$('feed_section').options[$('feed_section').options.length] = new Option(
							'-Select-', '', false, false);

					if (!this.system_sections.size) {
						for ( var key in this.system_sections) {
							$('feed_system').options[$('feed_system').options.length] = new Option(
									key, key, false, false);
						}
					}
				}
			},
			setSystem : function(value) {

				$('feed_section').options.length = 0;
				$('feed_section').options[$('feed_section').options.length] = new Option(
						'-Select-', '', false, false);

				var data = this.system_sections[value];
				if (typeof (data) != 'undefined') {
					data
							.each(function(option, i) {
								$('feed_section').options[$('feed_section').options.length] = new Option(
										option, value + '/' + option, false,
										false);
							});
				}

			},
			submit : function(section, file) {

				if (section && !file) {
					alert('Please select Section');
					return;
				}

				params = {
					file : file,
					section : section
				};

				var request = new Ajax.Request(this.url, {
					method : 'GET',
					parameters : params,
					onSuccess : function(transport) {

						var response = eval('('
								+ (transport.responseText || false) + ')');

						if (response.error) {
							alert(response.error_text);
						} else {
							$('mapping-table-body').innerHTML = response.feed;
						}

					},
					onFailure : function() {
						alert("Import failure");
					}
				});
			}
		});

GomageFeedGeneratorClass = Class.create({
	feed_id : null,
	generate_url : '',
	stop_url: '',
	info_url : '',	
	stop : false,
	timer : null,
	send_stop_command: false,

	initialize : function(data) {
		this.feed_id = data.feed_id;
		this.generate_url = data.generate_url;		
		this.info_url = data.info_url;
	},

	generate : function() {
		var params = {
			'feed_id' : this.feed_id
		};
		var self = this;

		$('gomage_feed_generate').hide();
		$('gomage_feed_stop').show();
		$('gfeed-loader').show();
		$('gfeed-loader-percent').innerHTML = '0';
		$('gfeed-loader-time').innerHTML = '0min 0sec';

		var request = new Ajax.Request(this.generate_url, {
			method : 'GET',
			parameters : params,
			loaderArea : false,
			onSuccess : function(transport) {
				var response = eval('(' + (transport.responseText || false)
						+ ')');
			}
		});
		this.send_stop_command = false;
		this.stop = false;
		this.processInfo();
	},

	processInfo : function() {
		if (this.timer)
			clearTimeout(this.timer);
		if (this.stop){
			$('gfeed-loader').hide();
			$('gomage_feed_generate').show();
			$('gomage_feed_stop').hide();
			return;
		}	

		var self = this;

		this.timer = setTimeout(function() {

			var params = {
				'feed_id' : self.feed_id,
				'stop_command' : (self.send_stop_command ? 1 : 0)
			};

			var request = new Ajax.Request(self.info_url, {
				method : 'GET',
				parameters : params,
				loaderArea : false,
				onSuccess : function(transport) {
					var response = eval('(' + (transport.responseText || false)
							+ ')');

					if (typeof(response.percent) != 'undefined'){
						$('gfeed-loader-percent').innerHTML = response.percent;
					}
					if (typeof(response.time) != 'undefined'){
						$('gfeed-loader-time').innerHTML = response.time;
					}
										
					if (response.redirect){
						setLocation(response.redirect);
						self.stop = true;
						return;
					}					
					if (response.error){
						self.stop = true;
						alert(response.error);
					}					
					if (response.stop) {
						self.stop = true;
					}
					
					if (self.stop){
						$('gfeed-loader').hide();
						$('gomage_feed_generate').show();
						$('gomage_feed_stop').hide();
						return;
					}
					
					self.processInfo();
				}
			});
		}, 500);
	},
	
	stopped: function (){
		this.send_stop_command = true;
	}

});

function showImportNotice(){
	if(typeof notice_timeout != 'undefined'){
		clearTimeout(notice_timeout);
	}
	$('import-notice').style.display = 'block';
	$('export-notice').style.display = 'none';
	notice_timeout = setTimeout(function(){
		$('import-notice').style.display = 'none';
	}, 5000);
}

function showExportNotice(){
	if(typeof notice_timeout != 'undefined'){
		clearTimeout(notice_timeout);
	}
	$('import-notice').style.display = 'none';
	$('export-notice').style.display = 'block';
	notice_timeout = setTimeout(function(){
		$('export-notice').style.display = 'none';
	}, 5000);
}

function gfp_changeAdditionHeader(control){
	if (control.value == 1){
		$('addition_header').show();
	}else{
		$('addition_header').hide();
	}
}

function gfp_toggle_multi(control, select_id){
	var select = $(select_id);
	if (select.multiple == true) {
		select.multiple = false;
		control.innerHTML = '+';
	} else {
		select.multiple = true;
		control.innerHTML = '-';
	}
}