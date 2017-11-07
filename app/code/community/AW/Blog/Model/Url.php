<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Blog
 * @version    1.3.18
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Blog_Model_Url extends Mage_Core_Model_Url
{

    public function getUrl($routePath = null, $routeParams = null)
    {
        $route = Mage::helper('blog')->getRoute();
        if (!empty($route)) {
            $isUseCategoryUrl = Mage::helper('blog')->isCategoryUrl();
            $category = Mage::getSingleton('blog/cat');
            $post = Mage::getSingleton('blog/post');
            $tag = $this->getRequest()->getParam('tag', false);
            if ($isUseCategoryUrl && $category->getCatId()) {
                $route .= '/' . AW_Blog_Helper_Data::CATEGORY_URI_PARAM . '/' . $category->getIdentifier();
            }
            if ($post->getIdentifier()) {
                if ($isUseCategoryUrl && $category->getCatId()) {
                    $route .= '/' . AW_Blog_Helper_Data::POST_URI_PARAM . '/' . $post->getIdentifier();
                }
                else {
                    $route .= '/' . $post->getIdentifier();
                }
            }
            if ($tag) {
                $route .= '/' . AW_Blog_Helper_Data::TAG_URI_PARAM . '/' . $tag;
            }
            $routePath = $route;
        }
        return parent::getUrl($routePath, $routeParams);
    }

}
