<?php

class Idev_OneStepCheckout_IndexController extends Mage_Core_Controller_Front_Action
{
    
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
    
    public function successAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}

?>