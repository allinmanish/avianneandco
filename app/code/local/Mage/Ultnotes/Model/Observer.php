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

class Mage_Ultnotes_Model_Observer
{
    public function preDispatch(Varien_Event_Observer $observer)
    {

        if (Mage::getSingleton('admin/session')->isLoggedIn()) {

            $feedModel  = Mage::getModel('adminnotification/feed');

            $feedModel->checkUpdate();

        }

    }
}
