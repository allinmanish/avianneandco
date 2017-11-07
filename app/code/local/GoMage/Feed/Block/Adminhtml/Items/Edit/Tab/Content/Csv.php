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

class GoMage_Feed_Block_Adminhtml_Items_Edit_Tab_Content_Csv extends Mage_Adminhtml_Block_Template
{	
	protected $_config; 
	
	public function __construct()
    {
        parent::__construct();                
        $this->getConfig()->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/ajaxupload'));        
        $this->getConfig()->setParams(array('form_key' => $this->getFormKey()));
        $this->getConfig()->setFileField('file');
        $this->getConfig()->setFilters(array(            
            'all'    => array(
                'label' => Mage::helper('adminhtml')->__('All Files'),
                'files' => array('*.*')
            )
        ));
    } 
    
    public function getHtmlId()
    {
        if ($this->getData('upload_id')===null) {
            $this->setData('upload_id', 'id_gomage_feed_upload' );
        }
        return $this->getData('upload_id');
    }
    
    public function getConfigJson()
    {
        return Zend_Json::encode($this->getConfig()->getData());        
    }
    
    public function getConfig()
    {
        if(is_null($this->_config)) {
            $this->_config = new Varien_Object();
        }

        return $this->_config;
    }
       
    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }
    
    public function getPostMaxSize()
    {
        return ini_get('post_max_size');
    }

    public function getUploadMaxSize()
    {
        return ini_get('upload_max_filesize');
    }

    public function getDataMaxSize()
    {
        return min($this->getPostMaxSize(), $this->getUploadMaxSize());
    }
    
    public function getDataMaxSizeInBytes()
    {
        $iniSize = $this->getDataMaxSize();
        $size = substr($iniSize, 0, strlen($iniSize)-1);
        $parsedSize = 0;
        switch (strtolower(substr($iniSize, strlen($iniSize)-1))) {
            case 't':
                $parsedSize = $size*(1024*1024*1024*1024);
                break;
            case 'g':
                $parsedSize = $size*(1024*1024*1024);
                break;
            case 'm':
                $parsedSize = $size*(1024*1024);
                break;
            case 'k':
                $parsedSize = $size*1024;
                break;
            case 'b':
            default:
                $parsedSize = $size;
                break;
        }
        return $parsedSize;
    }
    	
	public function getUploaderUrl($url)
    {
        if (!is_string($url)) {
            $url = '';
        }
        $design = Mage::getDesign();
        $theme = $design->getTheme('skin');
        if (empty($url) || !$design->validateFile($url, array('_type' => 'skin', '_theme' => $theme))) {
            $theme = $design->getDefaultTheme();
        }
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) .
            $design->getArea() . '/' . $design->getPackageName() . '/' . $theme . '/' . $url;
    }
	
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    } 
    
    protected function _prepareLayout()
    {
       $this->setChild(
            'delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                    'id'      => '{{id}}-delete',
                    'class'   => 'delete',
                    'type'    => 'button',
                    'label'   => Mage::helper('adminhtml')->__('Remove'),
                    'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                ))
        );

        return parent::_prepareLayout();
    } 
    
	public function getFeed(){
		
		if(Mage::registry('gomage_feed')){
        	return Mage::registry('gomage_feed');
        }else{
        	return  new Varien_Object();
        }
		
	}
				      
}