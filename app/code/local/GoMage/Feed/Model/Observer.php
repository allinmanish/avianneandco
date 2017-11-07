<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Model_Observer {
	
	public static function generateFeeds() {
		$collection = Mage::getResourceModel('gomage_feed/item_collection');
						
		$collection->getSelect()->where('`generate_day` like "%' . strtolower(date('D')) . '%"');
		
		foreach ($collection as $feed) {
			
			if (!$feed->getData('generate_status')){
				continue;
			}
			
			if (date('d.m.Y:H') == date('d.m.Y:H', strtotime($feed->getData('cron_started_at')))) {				
				continue;			
			}
			
			if (!Mage::helper('gomage_feed/generator')->needRunCron($feed->getData('generate_interval'), 
						  $feed->getData('generate_hour'), 
						  $feed->getData('generate_hour_to'), 
						  $feed->getData('cron_started_at'))){
				continue;			  	
			}
			
			try {				
				$cron_started_at = date('Y-m-j H:00:00', time());
				$feed->setData('cron_started_at', $cron_started_at);
				$feed->save();
				
				$feed->generate();

				$feed->setData('restart_cron', 0);
				$feed->save();	

				$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($feed->getId());
				$errors = $generate_info->getData('errors');
				if (empty($errors)){
					$message = Mage::helper('gomage_feed')->__('File was generated.');
					Mage::helper('gomage_feed/notification')->sendMessage($feed, $message, GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify::SUCCESSFULLY_GENERATED);
				}else{
					$message = implode(',', $errors);
					Mage::helper('gomage_feed/notification')->sendMessage($feed, $message, GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify::ERRORS); 
				} 				
			}
			catch (Exception $e) {
				Mage::helper('gomage_feed/notification')->sendMessage($feed, $e->getMessage(), GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify::ERRORS);				
				$feed->setData('restart_cron', intval($feed->getData('restart_cron')) + 1);
				$feed->save();				
				continue;			
			}		
		}	
	}
	
	public static function uploadFeeds() {
		$collection = Mage::getResourceModel('gomage_feed/item_collection');
		
		$collection->getSelect()->where('`upload_day` like "%' . strtolower(date('D')) . '%"');
						
		foreach ($collection as $feed) {
			
			if (!$feed->getData('upload_status')){
				continue;
			}
			
			if (date('d.m.Y:H') == date('d.m.Y:H', strtotime($feed->getData('cron_uploaded_at')))) {				
				continue;			
			}
			
			if (!Mage::helper('gomage_feed/generator')->needRunCron($feed->getData('upload_interval'), 
							  $feed->getData('upload_hour'), 
							  $feed->getData('upload_hour_to'), 
							  $feed->getData('cron_uploaded_at'))){
				continue;			  	
			}
			
			$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($feed->getId());
			if (!$generate_info->getData('finished')){
				continue;
			}
			
			try {
				$cron_uploaded_at = date('Y-m-j H:00:00', time());
				$feed->setData('cron_uploaded_at', $cron_uploaded_at);
				$feed->save();
				$feed->ftpUpload();
				$message = Mage::helper('gomage_feed')->__('File was uploaded.');
				Mage::helper('gomage_feed/notification')->sendMessage($feed, $message, GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify::SUCCESSFULLY_UPLOADED);
			}
			catch (Exception $e) {
				Mage::helper('gomage_feed/notification')->sendMessage($feed, $e->getMessage(), GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify::ERRORS);			
			}		
		}	
	}
	
	public static function checkK($event) {
		$key = Mage::getStoreConfig('gomage_activation/feed/key');
		Mage::helper('gomage_feed')->a($key);
	}

}