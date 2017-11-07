<?php
/**
 * Noam Design Group
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA available 
 * through the world-wide-web at this URL:
 * http://ultimento.com/legal/license.txt
 * 
 * MAGENTO EDITION USAGE NOTICE
 * 
 * This package is designed for Magento COMMUNITY edition. 
 * =================================================================
 *
 * @package    Ultimento
 * @copyright  Copyright (c) 2006-2011 Noam Design Group. (http://www.noamdesign.com) * @license    http://ultimento.com/legal/license.txt
 * @terms    http://ultimento.com/legal/terms
 */
?>
<?php

class Mage_Ultnotes_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH    = 'ultnotes/feed/use_https';
    const XML_FEED_URL_PATH     = 'ultnotes/feed/url';
    const XML_FREQUENCY_PATH    = 'ultnotes/feed/check_frequency';
	const XML_FREQUENCY_ENABLE    = 'ultnotes/feed/enabled';
    const XML_LAST_UPDATE_PATH  = 'ultnotes/feed/last_update';

	public static function check(){
		if(!Mage::getStoreConfig(self::XML_FREQUENCY_ENABLE)){		
			return;
		}
		return Mage::getModel('mage_ultnotes/feed')->checkUpdate();
	}
}
