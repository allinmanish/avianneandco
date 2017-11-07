<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Ip_Robots_Model_Source_Crawldelay {

    public function toOptionArray() {
        return array(
            array('value' => 'none', 'label' => Mage::helper('robots')->__('No delay')),
            array('value' => '5', 'label' => Mage::helper('robots')->__('5 seconds')),
            array('value' => '10', 'label' => Mage::helper('robots')->__('10 seconds')),
            array('value' => '20', 'label' => Mage::helper('robots')->__('20 seconds')),
            array('value' => '60', 'label' => Mage::helper('robots')->__('60 seconds')),
            array('value' => '120', 'label' => Mage::helper('robots')->__('120 seconds')),

        );
    }

}