<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Ip_Robots_Block_Admin_Item extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'admin_item';
        $this->_blockGroup = 'robots';
        $this->_headerText = Mage::helper('robots')->__('Robots.txt rules');
        $this->_addButtonLabel = Mage::helper('robots')->__('New Rule');
        parent::__construct();
        $previewurl = Mage::helper('robots')->RobotsPreviewUrl();
        $generateurl = Mage::helper('robots')->RobotsGenerateUrl();
        $installrobotsrules = Mage::helper('robots')->RobotsInstall();
        
        $this->_addButton('generate', array(
            'label'     => 'Preview rules in pop-up',
            'onclick'   => "window.open('".$previewurl."','Preview','width=900,height=600')",
            'class'     => 'generate',
        ));    	 
        $this->_addButton('buildrobotsfile', array(
            'label'     => 'Create and generate robots.txt',
            'onclick'   => 'setLocation(\'' . $generateurl .'\')',
            'class'     => 'buildrobotsfile',
        ));    	 
        $this->_addButton('installrobotsrules', array(
            'label'     => 'Install standard rules for my Magento',
            'onclick'   => 'setLocation(\'' . $installrobotsrules .'\')',
            'class'     => 'installrobotsrules',
        ));    	 
    }
  	protected function _prepareLayout()
    {
//		$block = $this->getLayout()->createBlock(
//            'Mage_Core_Block_Template',
//            'my_block_name_here',
//            array('template' => 'widget/grid.phtml')
//        );
//        
//        if ($block){
//            $this->getLayout()->getBlock('content')->insert($block)->toHtml();
//        }else{
//            echo "no block";
//        }    	
        parent::_prepareLayout();
        
    }    

}
