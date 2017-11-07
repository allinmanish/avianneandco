<?php


class Wee_Fpc_Block_Checkout_Links extends Mage_Checkout_Block_Links
{
    const CART_LINK_BEFORE_TEXT = '<!--cart_link_start-->';
    const CART_LINK_AFTER_TEXT = '<!--cart_link_end-->';

    public function addCartLink()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
            $count = $this->helper('checkout/cart')->getSummaryCount();
            if( $count == 1 ) {
                // $text = $this->__('My Cart (%s item)', $count);
                $text = '<img src="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag.png') . '" srcset="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@2x.png') . ' 2x, ' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@3x.png') . ' 3x"> <b>' . $count . '</b>';
            } elseif( $count > 0 ) {
                // $text = $this->__('My Cart (%s items)', $count);
                $text = '<img src="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag.png') . '" srcset="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@2x.png') . ' 2x, ' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@3x.png') . ' 3x"> <b>' . $count . '</b>';
            } else {
                // $text = $this->__('My Cart');
                $text = '<img src="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag.png') . '" srcset="' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@2x.png') . ' 2x, ' . $this->getSkinUrl('images/skin-avianne-resp/ic-bag@3x.png') . ' 3x">';
            }
            $parentBlock->addLink($text, 'checkout/cart', 'Cart, ' . ($count ? $count : '0') . ' item(s)', true, array(), 50, null, 'class="top-link-cart"', self::CART_LINK_BEFORE_TEXT, self::CART_LINK_AFTER_TEXT);
        }
        return $this;
    }
}