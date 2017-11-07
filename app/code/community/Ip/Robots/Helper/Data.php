<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php class Ip_Robots_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function versionUseAdminTitle() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function versionUseWysiwig() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function RobotsPreviewUrl() {
       return Mage::helper('adminhtml')->getUrl('*/admin_item/preview/');
    }
    public function RobotsGenerateUrl() {
       return Mage::helper('adminhtml')->getUrl('*/admin_item/generatefile/');
    }
    
    public function RobotsInstall() {
       return Mage::helper('adminhtml')->getUrl('*/admin_item/install/');
    }
    

}
