<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shoping cart model
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mfmc_mfmcallforprice_Model_Rewrite_FrontCheckoutCart extends Mage_Checkout_Model_Cart
{

    public function addProduct($product, $info=null)
    {
        $product = $this->_getProduct($product);
        $request = $this->_getProductRequest($info);

        if ($product->getId()) {

            // mfmc mfmcallforprice
            $callForPrice = Mage::getModel('mfmcallforprice/mfmcallforprice')->load($product->getId(), 'product_id');
            if($callForPrice->getHidePrice() && !$product->getSpecialPrice()) {
                return $this;
            }
            // end mfmc mfmcallforprice

            $result = $this->getQuote()->addProduct($product, $request);

            /**
             * String we can get if prepare process has error
             */
            if (is_string($result)) {

                $this->getCheckoutSession()->setRedirectUrl($product->getProductUrl());
                if ($this->getCheckoutSession()->getUseNotice() === null) {
                    $this->getCheckoutSession()->setUseNotice(true);
                }
                Mage::throwException($result);
            }
        }
        else {
            Mage::throwException(Mage::helper('checkout')->__('Product does not exist'));
        }

        Mage::dispatchEvent('checkout_cart_product_add_after', array('quote_item'=>$result, 'product'=>$product));
        $this->getCheckoutSession()->setLastAddedProductId($product->getId());
        return $this;
    }

}