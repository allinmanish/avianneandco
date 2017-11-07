<?php
class WSM_SalesReporting_BestsellersController extends Mage_Adminhtml_Controller_Report_Abstract
{
    public function indexAction()
    {
        $this->_title($this->__('Best Seller Report'));
        
        $this->_initAction()
            ->_setActiveMenu('adminhtml/bestsellers/index')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Best Seller Report'), Mage::helper('adminhtml')->__('Best Seller Report'));

        $gridBlock = $this->getLayout()->getBlock('adminhtml_bestsellers.grid');
        $filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction(array(
            $gridBlock,
            $filterFormBlock
        ));

        $this->getLayout()->getBlock('head')->setTitle($this->__('Best Seller Report'));
        $this->renderLayout();
    }
    
    /**
     * Export bestsellers report grid to CSV format
     */
    public function exportBestsellersCsvAction()
    {
    	$fileName   = 'bestsellers.csv';
    	$grid       = $this->getLayout()->createBlock('wsm_salesreporting/adminhtml_bestsellers_grid');
    	$this->_initReportAction($grid);
    	$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    
    /**
     * Export bestsellers report grid to Excel XML format
     */
    public function exportBestsellersExcelAction()
    {
    	$fileName   = 'bestsellers.xml';
    	$grid       = $this->getLayout()->createBlock('wsm_salesreporting/adminhtml_bestsellers_grid');
    	$this->_initReportAction($grid);
    	$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}   