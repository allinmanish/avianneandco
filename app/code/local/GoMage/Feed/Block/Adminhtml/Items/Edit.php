<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Block_Adminhtml_Items_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct(){
    	
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'gomage_feed';
        $this->_controller = 'adminhtml_items';
        
        $this->_updateButton('save', 'label', $this->__('Save'));
        $this->_updateButton('delete', 'label', $this->__('Delete'));
		
		$feed = Mage::registry('gomage_feed');
		
		if($feed && $feed->getId() > 0){
			
			$this->_addButton('generate', array(
				'id' 		=> 'gomage_feed_generate',
	            'label'     => $this->__('Generate File'),				
	            'onclick'   => 'GomageFeedGenerator.generate()',
	        ), -100);
	        
	        $this->_addButton('stop', array(
	        	'id' 		=> 'gomage_feed_stop',
	            'label'     => $this->__('Stop'),	        	
	            'onclick'   => 'GomageFeedGenerator.stopped()',
	        	'class'		=> 'delete',
	        	'style'		=> 'display:none;'
	        ), -100);
	        
	        if($feed->getFtpActive()){
	        
		        $this->_addButton('upload', array(
		            'label'     => $this->__('Upload File'),
		            'onclick'   => 'setLocation(\''.$this->getUrl('*/*/upload', array('id'=>$feed->getId())).'\')',
		        ), -100);
		        
	        }
        
        }
		
        $this->_addButton('saveandcontinue', array(
            'label'     => $this->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        
        $_data = array();
        $_data['data'] = Mage::helper('gomage_feed')->getSystemSections(); 
        $_data['url'] = $this->getUrl('*/*/mappingimportsection', array('id'=>($feed && $feed->getId() ? $feed->getId() : 0)));
        
        $_generator_data = array(
        	'feed_id' => ($feed && $feed->getId() ? $feed->getId() : 0),
        	'generate_url' => $this->getUrl('feed/index/generate'),        	
            'info_url' => $this->getUrl('*/*/processInfo'),    	
        );        
        
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }            
            var GomageFeedAdmin = new GomageFeedAdminSettings(" . Zend_Json::encode($_data) . ");            
            var GomageFeedGenerator = new GomageFeedGeneratorClass(" . Zend_Json::encode($_generator_data) . ");
        ";
        
        if ($this->getRequest()->getActionName() == 'new' &&
        	!$this->getRequest()->getParam('type')){
        	$this->removeButton('save');
        	$this->removeButton('saveandcontinue');
        }
        
    }
    
    public function getHeaderText(){
    	
        if( Mage::registry('gomage_feed') && Mage::registry('gomage_feed')->getId() ) {
            return $this->__("Edit %s", $this->htmlEscape(Mage::registry('gomage_feed')->getName()));
        } else {
            return $this->__('Add Item');
        }
    }
}