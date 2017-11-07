<?php 
class WSM_Rank_Adminhtml_RanksController extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		$this->loadLayout()->renderLayout();
	}
	public function exportAction() {
		$filename = 'rank_export_'.date('m_d_Y').'.csv';
		$content = Mage::helper('rank')->generateRankList();
		
		$this->_prepareDownloadResponse($filename, $content);
	}
	public function postAction()
	{
		$post = $this->getRequest()->getPost();
		try {
			if (empty($post)) {
				Mage::throwException($this->__('Invalid form data.'));
			}
			
			if(isset($_FILES['rank']['name']) && $_FILES['rank']['name'] != '') {
				$uploaderFile = new Varien_File_Uploader('rank');
			    $uploaderFile->setAllowedExtensions(array());
			    $uploaderFile->setAllowRenameFiles(false);
			    $uploaderFile->setFilesDispersion(false);
			    $uploaderFilepath = Mage::getBaseDir('var') . DS . 'export' . DS;
			    $uploaderFile->save($uploaderFilepath, $_FILES['rank']['name'] );
			    
			    $file =  $_FILES['rank']['name'];
			    $filepath = $uploaderFilepath.$file;
			    $csv = new Varien_File_Csv();
			    $data = $csv->getData($filepath);
			    
			    for($i=1; $i<count($data); $i++) {
			    	$rank = Mage::getModel('rank/rank');
			    	$rank->load($data[$i][0]);
			    	$rank->setManualPoints($data[$i][3]);
			    	$rank->save();
			    }
			}
	
			$message = $this->__('Your form has been submitted successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}
}