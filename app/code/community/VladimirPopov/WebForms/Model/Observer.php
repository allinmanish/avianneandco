<?php
class VladimirPopov_WebForms_Model_Observer{
	
	public function addAssets($observer){
		$layout = $observer->getLayout();
		$update = $observer->getLayout()->getUpdate();
			
		if(in_array('cms_page', $update->getHandles())){
			
			$pageId = Mage::app()->getRequest()
				->getParam('page_id', Mage::app()->getRequest()->getParam('id', false));
			
			$page = Mage::getModel('cms/page')->load($pageId);
			
			if(stristr($page->getContent(),'webforms/form')){
				Mage::helper('webforms')->addAssets($layout);
			}
		}
		
	}
	
	public function postDeposit($observer){
		
		$order = $observer->getEvent()->getOrder();
		foreach ($order->getAllItems() as $item) {
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$sku = $product->getSku();
			if ($sku == 'C3DMD') {
				$opts = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
				
				$ref_id = 0;
				
				foreach ($opts['options'] as $opt) {
					if ($opt['label'] == 'Model Reference ID') {
						$ref_id = $opt['option_value'];
					}
				}
				
				if (!$ref_id) {
					continue;
				}
				
				$_result = Mage::getModel('webforms/results')->load($ref_id);
				$_result->setStatus('Deposit Received')->save();
				
				$webform = Mage::getModel('webforms/webforms')->load(4);
				
				$emailSettings = $webform->getEmailSettings();
					
				if($emailSettings['email_enable']){
				
					$result = Mage::getModel('webforms/results')->load($ref_id);
					$result->sendEmail();
					if($webform->getDuplicateEmail()){
						$result->sendEmail('customer');
					}
				}
					
				// email contact
				$fields = Mage::getModel('webforms/fields')->getCollection()
					->addFilter('webform_id',$result->getWebformId())
					->addFilter('type','select/contact');
				foreach($fields as $field){
					foreach($result->getData() as $key=>$value){
						if($key == 'field_'.$field->getId() && $value){
							$result->sendEmail('contact',$field->getContactArray($value));
						}
					}
				}
							
							
			}
		}
		
	}
}
?>
