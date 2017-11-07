<?php

class Wee_Fpc_Model_Processor_HeaderCart extends Wee_Fpc_Model_Processor_Abstract
{
    const HEADER_CART_KEY = 'header_cart';

    public function prepareContent($content, array $requestParameter)
    {
        $block = new Mage_Checkout_Block_Cart_Sidebar();
        $block->setLayout(Mage::app()->getLayout());
        $block->setTemplate('wee_fpc/cart/header_cart.phtml');
        $blockContent = str_replace('$','\$',$block->toHtml());
        return $this->_replaceContent(self::HEADER_CART_KEY, $blockContent, $content);
    }
}