/**
 * Mageix LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to Mageix LLC's  End User License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mageix.com/index.php/license-guide/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to webmaster@mageix.com so we can send you a copy immediately.
 *
 * DISCLAIMER

 *
 * @category	Mageix
 * @package 	Mageix_Ixcba
 * @copyright   Copyright (c) 2011 Mageix LLC (http://mageix.com)
 * @license	http://mageix.com/index.php/license-guide/  End User License Agreement
 */

/*
 * Magento Mageix ICBA Module
 *
 * @category   Checkout & Payments
 * @package	Mageix_Ixcba
 * @copyright  Copyright (c) 2011 Mageix LLC (http://mageix.com)
 * @designer & author  Brian Graham
 * @co-author Rahul Sharma
  *@licence 	http://mageix.com/index.php/license-guide/ 
 */
IxcbaAccordion = Class.create();
IxcbaAccordion.prototype = {
    initialize: function(elem, clickableEntity, checkAllow) {
        this.container = $(elem);
        this.checkAllow = checkAllow || false;
        this.disallowAccessToNextSections = false;
        this.sections = $$('#' + elem + ' .section');
        this.currentSection = false;
        var headers = $$('#' + elem + ' .section ' + clickableEntity);
        headers.each(function(header) {
            Event.observe(header,'click',this.sectionClicked.bindAsEventListener(this));
        }.bind(this));
    },

    sectionClicked: function(event) {
        this.openSection($(Event.element(event)).up('.section'));
        Event.stop(event);
    },

    openSection: function(section) {

		try {

			var section = $(section);

			// Check allow
			if (this.checkAllow && !Element.hasClassName(section, 'allow')){
				return;
			}
			//alert(section.id+' '+this.currentSection);
			if(section.id == 'opc-review'){
				this.currentSection = 'opc-payment';
			}
			if(section.id == 'opc-shipping_method'){
				if($('opc-billing').hasClassName('active')) {
					this.currentSection = 'opc-billing';
				}else if($('opc-shipping').hasClassName('active')) {
					this.currentSection = 'opc-shipping';
				}
			}
			//alert(section.id+' '+this.currentSection);

			if(section.id != this.currentSection) {
				this.closeExistingSection();
				this.currentSection = section.id;
				$(this.currentSection).addClassName('active');
				var contents = Element.select(section, '.a-item');
				contents[0].show();
				//Effect.SlideDown(contents[0], {duration:.2});

				if (this.disallowAccessToNextSections) {
					var pastCurrentSection = false;
					for (var i=0; i<this.sections.length; i++) {
						if (pastCurrentSection) {
							Element.removeClassName(this.sections[i], 'allow')
						}
						if (this.sections[i].id==section.id) {
							pastCurrentSection = true;
						}
					}
				}
			}

		} catch (e) {}
    },

    closeSection: function(section) {
		if(document.getElementById(section)) {
			$(section).removeClassName('active');
		}else{
			try {
				if($('opc-login').hasClassName('active')) {
					section = 'opc-login';
				}else if($('opc-billing').hasClassName('active')) {
					section = 'opc-billing';
				}else if($('opc-shipping').hasClassName('active')) {
					section = 'opc-shipping';
				}else if($('opc-payment').hasClassName('active')) {
					section = 'opc-payment';
				}
				$(section).removeClassName('active');
			} catch (e) {}
		}
        var contents = Element.select(section, '.a-item');
        contents[0].hide();
        //Effect.SlideUp(contents[0]);
    },

    openNextSection: function(setAllow){
        for (section in this.sections) {
            var nextIndex = parseInt(section)+1;
            if (this.sections[section].id == this.currentSection && this.sections[nextIndex]){
                if (setAllow) {
                    Element.addClassName(this.sections[nextIndex], 'allow')
                }
                this.openSection(this.sections[nextIndex]);
                return;
            }
        }
    },

    openPrevSection: function(setAllow){
        for (section in this.sections) {
            var prevIndex = parseInt(section)-1;
            if (this.sections[section].id == this.currentSection && this.sections[prevIndex]){
                if (setAllow) {
                    Element.addClassName(this.sections[prevIndex], 'allow')
                }
                this.openSection(this.sections[prevIndex]);
                return;
            }
        }
    },

    closeExistingSection: function() {
        if(this.currentSection) {
            this.closeSection(this.currentSection);
        }
    }
}