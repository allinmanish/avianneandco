//load jquery dynamically if it's not loaded
var jQueryScriptOutputted = false;
var jQueryNoConflict;

function LiveChatInitJQuery()
{
//if the jQuery object isn't available
if (typeof(jQuery) == 'undefined') {

if (! jQueryScriptOutputted) {
//only output the script once..
	jQueryScriptOutputted = true;

//check for other libraries
	if (typeof($) != "undefined") {
		jQueryNoConflict = true;
	}

//output the script (load it from google api)
	document.write("<scr" + "ipt type=\"text/javascript\" src=\"" + livechat_jquery + "\"></scr" + "ipt>");
}

setTimeout("LiveChatInitJQuery()", 50);

} else {

	//return control of $ back to the other library
	if (jQueryNoConflict == true) {
		jQuery.noConflict();
	}


(function($) {

var LiveChat =
{
	init: function()
	{
		this.bindButton();
		this.toggleForm();
		this.alreadyHaveAccountForm();
		this.newLicenseForm();
		this.resetLink();
	},

	bindButton: function()
	{
		//disable original action and hide
		$('.form-buttons button.add').hide().attr('onclick', '').click(function(){
			$('#livechat_new_account form').submit();
		});
	},

	toggleForm: function()
	{
		var show_form = function()
		{
			if ($('#choice_account_0').is(':checked'))
			{
				$('.form-buttons button.save').hide();
				$('.form-buttons button.add').show();

				$('#livechat_already_have').hide();
				$('#livechat_new_account').show();
			}
			else if ($('#choice_account_1').is(':checked'))
			{
				$('.form-buttons button.add').hide();
				$('.form-buttons button.save').show();

				$('#livechat_new_account').hide();
				$('#livechat_already_have').show();
			}
		}

		show_form();
		$('#choice_account input').click(show_form);
	},

	validateNewLicenseForm: function()
	{
		if ($('#livechat_account_name').val().length < 1)
		{
			alert ('Please enter your name.');
			$('#livechat_account_name').focus();
			return false;
		}

		if (/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test($('#livechat_account_email').val()) == false)
		{
			alert ('Please enter a valid email address.');
			$('#livechat_account_email').focus();
			return false;
		}

		if ($.trim($('#livechat_account_password').val()).length < 6)
		{
			alert('Password must be at least 6 characters long');
			$('#livechat_account_password').focus();
			return false;
		}

		if ($('#livechat_account_password').val() !== $('#livechat_account_password_retype').val())
		{
			alert('Both passwords do not match.');
			$('#livechat_account_password').val('');
			$('#livechat_account_password_retype').val('');
			$('#livechat_account_password').focus();
			return false;
		}

		return true;
	},

	calculateGMT: function()
	{
		var date, dateGMTString, date2, gmt;

		date = new Date((new Date()).getFullYear(), 0, 1, 0, 0, 0, 0);
		dateGMTString = date.toGMTString();
		date2 = new Date(dateGMTString.substring(0, dateGMTString.lastIndexOf(" ")-1));
		gmt = ((date - date2) / (1000 * 60 * 60)).toString();

		return gmt;
	},

	alreadyHaveAccountForm: function()
	{
		$('#livechat_already_have form').submit(function()
		{
			if (parseInt($('#license_number').val()) == 0)
			{
				var login = $.trim($('#livechat_login').val());
				if (!login.length)
				{
					$('#livechat_login').focus();
					return false;
				}

				$('#livechat_already_have .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

				$.getJSON('https://api.livechatinc.com/license/number/'+login+'?callback=?', function(response)
				{
					if (response.error)
					{
						$('#livechat_already_have .ajax_message').removeClass('wait').addClass('message').html('Incorrect LiveChat login.');
						$('#livechat_login').focus();
						return false;
					}
					else
					{
						$('#license_number').val(response.number);
						$('#livechat_already_have form').submit();
					}
				});

				return false;
			}
		});
	},

	newLicenseForm: function()
	{
		$('#livechat_new_account form').submit(function()
		{
			if (parseInt($('#new_license_number').val()) > 0)
			{
				return true;
			}

			if (LiveChat.validateNewLicenseForm())
			{
				$('#livechat_new_account .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

				// Check if email address is available
				$.getJSON('http://www.livechatinc.com/php/licence_info.php?email='+$('#livechat_account_email').val()+'&jsoncallback=?',
				function(response)
				{					
					if (response.response == 'true')
					{
						LiveChat.createLicense();
					}
					else if (response.response == 'false')
					{
						$('#livechat_new_account .ajax_message').removeClass('wait').addClass('message').html('This email address is already in use. Please choose another e-mail address.');
					}
					else
					{
						$('#livechat_new_account .ajax_message').removeClass('wait').addClass('message').html('Could not create account. Please try again later.');
					}
				});
			}

			return false;
		});
	},

	createLicense: function()
	{
		var url;

		$('#livechat_new_account .ajax_message').removeClass('message').addClass('wait').html('Creating new account&hellip;');

		url = 'https://www.livechatinc.com/signup/';
		url += '?name='+encodeURIComponent($('#livechat_account_name').val());
		url += '&email='+encodeURIComponent($('#livechat_account_email').val());
		url += '&password='+encodeURIComponent($('#livechat_account_password').val());
		url += '&website='+encodeURIComponent($('#livechat_account_website').val());
		url += '&timezone_gmt='+encodeURIComponent(this.calculateGMT());
		url += '&action=magento_signup';
		url += '&jsoncallback=?';

		$.getJSON(url, function(data)
		{
			data = parseInt(data.response);
			if (data == 0)
			{
				$('#livechat_new_account .ajax_message').html('Could not create account. Please try again later.').addClass('message').removeClass('wait');
				return false;
			}

			// save new licence number
			$('#license_number').val(data);
			$('#livechat_already_have form').submit();
		});
	},

	resetLink: function()
	{
		$('#reset_settings').click(function()
		{
			if (confirm('This will reset your LiveChat plugin settings. Continue?'))
			{
				$('#livechat_already_have form').submit();
			}
		})
	}
};

$(document).ready(function()
{
	LiveChat.init();
});
})(jQuery);
}
}

LiveChatInitJQuery();