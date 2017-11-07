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

class Mage_Ultnotes_Model_Feed_Updates extends Mage_Ultnotes_Model_Feed_Abstract{
	
    public function getFeedUrl(){
		return Mage_Ultnotes_Helper_Config::UPDATES_FEED_URL;
    }
	
	public function check(){
		if((time()-Mage::app()->loadCache('mage_ultnotes_updates_feed_lastcheck')) > Mage::getStoreConfig('ultnotes/feed/check_frequency')){
			$this->refresh();
			return $this->refresh();
		}
	}
	
	public function refresh(){
		$feedData = array();
		try{
			$Node = $this->getFeedData();
			if (is_object($Node)){
				foreach($Node->children() as $item){
						$date = strtotime((string)$item->date);
						$feedData[] = array(
						   'severity'      => 4,
						   'date_added'    => $this->getDate((string)$item->date),
						   'title'         => (string)$item->title,
						   'description'   => (string)$item->content,
						   'url'           => (string)$item->url,
						);
										 
				}
			}
			if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(($feedData));
            }
			Mage::app()->saveCache(time(), 'mage_ultnotes_updates_feed_lastcheck');
			return true;
		}catch(Exception $E){
			return false;			
		}
	}	

    public function getDate($rssDate)
    {
        return gmdate('Y-m-d H:i:s', strtotime($rssDate));
    }
	
	
	
	
}