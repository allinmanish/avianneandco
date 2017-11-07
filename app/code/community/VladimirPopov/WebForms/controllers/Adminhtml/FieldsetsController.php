<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Adminhtml_FieldsetsController
	extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('webforms/webforms');
		return $this;
	}
	
	public function indexAction(){
		$this->_initAction();
		$this->renderLayout();
	}
	
	public function gridAction()
	{
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('webforms/adminhtml_webforms_edit_tab_fieldsets')->toHtml()
		);
	}	
	
	public function editAction(){
		if((float)substr(Mage::getVersion(),0,3) > 1.3)
			$this->_title($this->__('Web-forms'))->_title($this->__('Edit Field Set'));
		$fieldsetId = $this->getRequest()->getParam('id');
		$webformsId = $this->getRequest()->getParam('webform_id');
		$fieldsetsModel = Mage::getModel('webforms/fieldsets')->load($fieldsetId);
		if($fieldsetsModel->getWebformId()){
			$webformsId = $fieldsetsModel->getWebformId();
		}
		$webformsModel = Mage::getModel('webforms/webforms')->load($webformsId);
		
		if($fieldsetsModel->getId() || $fieldsetId == 0){
			Mage::register('webforms_data',$webformsModel);
			Mage::register('fieldsets_data',$fieldsetsModel);
			
			$this->loadLayout();
			$this->_setActiveMenu('webforms/webforms');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Web-forms'),Mage::helper('adminhtml')->__('Web-forms'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			$this->_addContent($this->getLayout()->createBlock('webforms/adminhtml_fieldsets_edit'));
				
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webforms')->__('Fieldset does not exist'));
			$this->_redirect('*/adminhtml_webforms/edit',array('id' => $webformsId));
		}
	}
	
	public function newAction()
	{
		$this->_forward('edit');
	}
	
	public function saveAction()
	{
		if( $this->getRequest()->getPost()){
			try{
				$postData = $this->getRequest()->getPost();
				$fieldsetsModel = Mage::getModel('webforms/fieldsets');
				
				$fieldsetsModel->setData($postData)
					->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
					->setId($this->getRequest()->getParam('id'))
					->save();

				if( $this->getRequest()->getParam('id') <= 0 )
					$fieldsetsModel->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Fieldset was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setWebFormsData(false);
				
				if($this->getRequest()->getPost('saveandcontinue')){
					$this->_redirect('*/adminhtml_fieldsets/edit',array('id' => $fieldsetsModel->getId(),'webform_id' => $this->getRequest()->getParam('webform_id')));
				} else {
					$this->_redirect('*/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
				}
				return;
			} catch (Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setWebFormsData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit',array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			
		}
		$this->_redirect('*/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
	}
	
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0){
			try{
				$fieldsetsModel = Mage::getModel('webforms/fieldsets');
				$fieldsetsModel->load($this->getRequest()->getParam('id'))->delete();
				$webform_id = $fieldsetsModel->getWebformId();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Fieldset was successfully deleted'));
				$this->_redirect('*/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
			} catch (Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/adminhtml_webforms/edit',array('id' => $webform_id,'tab' => 'form_fieldsets'));
	}
	
	public function massDeleteAction()
	{
		$Ids = (array)$this->getRequest()->getParam('id');
		
		try {
			foreach($Ids as $id){
				$result = Mage::getModel('webforms/fieldsets')->load($id);
				$result->delete();
			}

			$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) have been deleted.', count($Ids))
			);
		}
		catch (Mage_Core_Model_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Exception $e) {
			$this->_getSession()->addException($e, $this->__('An error occurred while updating records.'));
		}

		$this->_redirect('webforms/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
	}
	
	public function massStatusAction(){
		$Ids = (array)$this->getRequest()->getParam('id');
		$status = (int)$this->getRequest()->getParam('status');
		
		try {
			foreach($Ids as $id){
				$result = Mage::getModel('webforms/fieldsets')->load($id);
				$result->setData('is_active',$status);
				$result->save();
			}

			$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) have been updated.', count($Ids))
			);
		}
		catch (Mage_Core_Model_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Exception $e) {
			$this->_getSession()->addException($e, $this->__('An error occurred while updating records.'));
		}
		
		$this->_redirect('webforms/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
	}

	public function massDuplicateAction(){
		$Ids = (array)$this->getRequest()->getParam('id');
		
		try {
			foreach($Ids as $id){
				$result = Mage::getModel('webforms/fieldsets')->load($id);
				$result->duplicate();
			}

			$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) have been duplicated.', count($Ids))
			);
		}
		catch (Mage_Core_Model_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Exception $e) {
			$this->_getSession()->addException($e, $this->__('An error occurred while duplicating records.'));
		}
		
		$this->_redirect('webforms/adminhtml_webforms/edit',array('id' => $this->getRequest()->getParam('webform_id'),'tab' => 'form_fieldsets'));
	}
	
}
?>