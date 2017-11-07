<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php
Class Ip_Robots_Model_Data {
	
    protected function getItemModel() {
        return Mage::getModel('robots/item');
    }
    
 	public function AdditionalRobotsCrawlers()
    {
		$lines = '';
    	if (Mage::getStoreConfig('robots/options/googlebot')) {
	    	$lines.= '# ------------------ googlebot ------------------'.'<br>';
			$lines.= 'User-agent: googlebot'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/yandex')) {
	    	$lines.= '# ------------------ yandex ------------------'.'<br>';
			$lines.= 'User-agent: yandex'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/googlebotimage')) {
	    	$lines.= '# ------------------ googlebotimage ------------------'.'<br>';
			$lines.= 'User-agent: googlebotimage'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/googlebotmobile')) {
	    	$lines.= '# ------------------ googlebotmobile ------------------'.'<br>';
			$lines.= 'User-agent: googlebotmobile'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/msnbot')) {
	    	$lines.= '# ------------------ msnbot ------------------'.'<br>';
			$lines.= 'User-agent: msnbot'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/slurp')) {
	    	$lines.= '# ------------------ slurp ------------------'.'<br>';
			$lines.= 'User-agent: slurp'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/teoma')) {
	    	$lines.= '# ------------------ teoma ------------------'.'<br>';
			$lines.= 'User-agent: teoma'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/twiceler')) {
	    	$lines.= '# ------------------ twiceler ------------------'.'<br>';
			$lines.= 'User-agent: twiceler'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/gigabot')) {
	    	$lines.= '# ------------------ gigabot ------------------'.'<br>';
			$lines.= 'User-agent: gigabot'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/twiceler')) {
	    	$lines.= '# ------------------ twiceler ------------------'.'<br>';
			$lines.= 'User-agent: twiceler'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/scrubby')) {
	    	$lines.= '# ------------------ scrubby ------------------'.'<br>';
			$lines.= 'User-agent: scrubby'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/robozilla')) {
	    	$lines.= '# ------------------ robozilla ------------------'.'<br>';
			$lines.= 'User-agent: robozilla'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/nutch')) {
	    	$lines.= '# ------------------ nutch ------------------'.'<br>';
			$lines.= 'User-agent: nutch'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/robozilla')) {
	    	$lines.= '# ------------------ robozilla ------------------'.'<br>';
			$lines.= 'User-agent: robozilla'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/iaarchiver')) {
	    	$lines.= '# ------------------ iaarchiver ------------------'.'<br>';
			$lines.= 'User-agent: iaarchiver'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/baiduspider')) {
	    	$lines.= '# ------------------ baiduspider ------------------'.'<br>';
			$lines.= 'User-agent: baiduspider'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/naverbot')) {
	    	$lines.= '# ------------------ naverbot ------------------'.'<br>';
			$lines.= 'User-agent: naverbot'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/yeti')) {
	    	$lines.= '# ------------------ yeti ------------------'.'<br>';
			$lines.= 'User-agent: yeti'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/yahoommcrawler')) {
	    	$lines.= '# ------------------ yahoommcrawler ------------------'.'<br>';
			$lines.= 'User-agent: yahoommcrawler'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/psbot')) {
	    	$lines.= '# ------------------ psbot ------------------'.'<br>';
			$lines.= 'User-agent: psbot'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	if (Mage::getStoreConfig('robots/options/yahooblogs')) {
	    	$lines.= '# ------------------ yahooblogs ------------------'.'<br>';
			$lines.= 'User-agent: yahooblogs'.'<br>';
			$lines.= 'Disallow: /'.'<br>';
    	}
    	
		return $lines;
    }    
//    
//    protected function getItemCollection() {
//        $storeId = Mage::app()->getStore()->getId();
//        $collection = $this->getItemModel()->getCollection();
//        $collection->addFilter('is_active', 1);
//        $collection->addStoreFilter($storeId);
//        $collection->addOrder('position', 'ASC');
//        return $collection;
//    }
//    
//    public function getItems() {
//        return $this->getItemCollection();
//    }
}