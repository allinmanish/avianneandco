<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

class VladimirPopov_WebForms_IndexController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction()
	{	
		Mage::register('webforms_preview',true);
		Mage::register('show_form_name',true);
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');
		$this->renderLayout();
	}
	
	public function iframeAction()
	{
		$webform = Mage::getModel('webforms/webforms')->load(Mage::app()->getRequest()->getPost("webform_id"));

		$success = false;
		$result = array("success" => false, "errors" => array());
		if(Mage::app()->getRequest()->getPost('submitWebform_'.$webform->getId())){
			$result["success"] = $webform->savePostResult();
			if($result["success"]){
				
				$product = Mage::getModel('catalog/product')->load(31774);
				$param = array(
						'product' => $product->getId(),
						'qty' => 1,
						'options' => array(
								218140 => $result["success"]
						)
				);
				$request = new Varien_Object();
				$request->setData($param);
				$cart = Mage::getModel('checkout/cart');
				$cart->init();
				$cart->addProduct($product, $request);
				$cart->save();
				Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
				Mage::getSingleton('core/session')->addSuccess("Make a deposit for us to begin constructing of your 3D Model");
				
// 				$result["success_text"] = $webform->getSuccessText();
				$result["success_text"] = 'Make a deposit for us to begin constructing of your 3D Model';
				
				if((float)substr(Mage::getVersion(),0,3)>1.3)
					$result["success_text"] = Mage::helper('cms')->getPageTemplateProcessor()->filter($webform->getSuccessText());
				if($webform->getRedirectUrl()){
					if(strstr($webform->getRedirectUrl(),'://'))	
						$redirectUrl = $webform->getRedirectUrl();
					else
						$redirectUrl = Mage::app()->getStore()->getUrl($webform->getRedirectUrl());
					$result["redirect_url"] = $redirectUrl;
				}
			} else {
				$errors = Mage::getSingleton('core/session')->getMessages(true)->getItems();
				foreach($errors as $err){
					$result["errors"][] = $err->getCode();
				}
				$html_errors = "";
				if(count($result["errors"])>1){
					foreach($result["errors"] as $err){
						$html_errors.= '<li>'.$err.'</li>';
					}
					$result["errors"] = '<ul class="webforms-errors-list">'.$html_errors.'</ul>';
				} else {
					$result["errors"] = '<p class="webforms-error-message">'.$result["errors"][0].'</p>';
				}
			}
		}
		
		Mage::dispatchEvent('webforms_controllers_index_iframe_action',array('result'=>$result, 'webform'=>$webform));

		$this->getResponse()->setBody(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}
	
}  
?>
