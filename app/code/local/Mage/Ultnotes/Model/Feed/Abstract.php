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

class Mage_Ultnotes_Model_Feed_Abstract extends Mage_Core_Model_Abstract{
	
    public function getFeedData()
    {

        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
            'timeout'   => 1
        ));

//if ($_SERVER['REMOTE_ADDR'] == '178.219.251.50')
//die(var_dump($this->getFeedUrl()));

        $curl->write(Zend_Http_Client::GET, $this->getFeedUrl(), '1.0');
        $data = $curl->read();
        if ($data === false) {
            return false;
        }

        $data = preg_split('/^\r?$/m', $data, 2);
        $data = trim($data[1]);
        $curl->close();


    	try {
            $xml  = new SimpleXMLElement($data);
        }
        catch (Exception $e) {
            return false;
        }

        return $xml;
    }


}