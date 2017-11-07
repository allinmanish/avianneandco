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

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
		
    protected function _prepareForm()
    {
        
        $form = new Varien_Data_Form();
        
        if(Mage::registry('gomage_feed')){
        	$item = Mage::registry('gomage_feed');
        	$type = $item->getType();
        }else{
        	$item = new Varien_Object();
        	$type = $this->getRequest()->getParam('type', null);
        }
        
        $this->setForm($form);
        $fieldset = $form->addFieldset('main_fieldset', array('legend' => $this->__('Item information')));
        $this->_setFieldset(array(), $fieldset);        
        
        $headerBar = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'   => Mage::helper('catalog')->__('Feed Pro Help'),
                    'class'   => 'go',
                    'id'      => 'feed_pro_help',
                    'onclick' => 'window.open(\'http://www.gomage.com/faq/extensions/feed-pro\')'
                ));
        
        $fieldset->setHeaderBar(
                    $headerBar->toHtml()
                );         
     	
     	$fieldset->addField('type', 'hidden', array(
            'name'      => 'type',
        ));
     	
    	$fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => $this->__('Name'),
            'title'     => $this->__('Name'),
            'required'  => true,
            'note'	=>$this->__('e.g. "Google Base", "Yahoo! Store"...')
        ));
        
        if($item->getId() && ($url = $item->getUrl())){        	
	        $fieldset->addField('comments', 'note', array(
	            'label'    => $this->__('Access Url'),
	            'title'    => $this->__('Access Url'),
	            'text'    => '<a href="'.$url.'" target="_blank">'.$url.'</a>',
	        ));	
        }
        
        $fieldset->addField('filename', 'text', array(
            'name'      => 'filename',
            'label'     => $this->__('Filename'),
            'title'     => $this->__('Filename'),
            'required'  => false,            
        ));
        
        $values = array();
        if ($type == 'csv'){
        	$values = Mage::getModel('gomage_feed/adminhtml_system_config_source_extension_csv')->toOptionArray(); 
        }elseif ($type == 'xml'){
        	$values = Mage::getModel('gomage_feed/adminhtml_system_config_source_extension_xml')->toOptionArray();
        }
        
        $fieldset->addField('filename_ext', 'select', array(
            'name'      => 'filename_ext',
            'label'     => $this->__('Filename Extension'),
            'title'     => $this->__('Filename Extension'),
            'required'  => false,  
        	'values'	=> $values,          
        ));
                
        $fieldset->addField('store_id', 'select', array(
            'label'     => $this->__('Store View'),
            'required'  => true,
            'name'      => 'store_id',
            'values'    => Mage::getModel('gomage_feed/adminhtml_system_config_source_store')->getStoreValuesForForm(),
        ));
        
        $fieldset->addField('generated_at', 'labeldate', array(
            'name'      => 'generated_at',
            'label'     => $this->__('Last Generated'),
            'title'     => $this->__('Last Generated'),
        	'renderer'  => 'GoMage_Feed_Block_Adminhtml_Items_Grid_Renderer_Datetime'                 		               	                       
        ));
        
        $fieldset->addField('generation_time', 'label', array(
            'name'      => 'generation_time',
            'label'     => $this->__('Generation Time'),
            'title'     => $this->__('Generation Time'),         	               	                       
        ));
        
        $fieldset->addField('uploaded_at', 'labeldate', array(
            'name'      => 'uploaded_at',
            'label'     => $this->__('Last Uploaded'),
            'title'     => $this->__('Last Uploaded'),        	           	       	               	                       
        ));
                               
        if(!$item->getType() && $this->getRequest()->getParam('type')){
        	$item->setType($this->getRequest()->getParam('type'));
        }
        
        $form->setValues($item->getData());
        
        
        return parent::_prepareForm();
        
    }
    
    protected function _getAdditionalElementTypes()
    {        	        	
        return array(
            'labeldate' => Mage::getConfig()->getBlockClassName('gomage_feed/adminhtml_helper_labeldate'),
        );
    } 
         
}