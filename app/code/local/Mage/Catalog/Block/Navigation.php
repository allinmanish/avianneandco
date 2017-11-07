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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog navigation
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Navigation extends Mage_Core_Block_Template
{
    protected $_categoryInstance = null;

    /**
     * Array of level position counters
     *
     * @var array
     */
    protected $_itemLevelPositions = array();

    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => false,
            'cache_tags'        => array(Mage_Catalog_Model_Category::CACHE_TAG, Mage_Core_Model_Store_Group::CACHE_TAG),
        ));
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $shortCacheId = array(
            'CATALOG_NAVIGATION',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            'name' => $this->getNameInLayout()
        );
        $cacheId = $shortCacheId;

        $shortCacheId = array_values($shortCacheId);
        $shortCacheId = implode('|', $shortCacheId);
        $shortCacheId = md5($shortCacheId);

        $cacheId['category_path'] = $this->getCurrenCategoryKey();
        $cacheId['short_cache_id'] = $shortCacheId;

        return $cacheId;
    }

    public function getCurrenCategoryKey()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getPath();
        } else {
            return Mage::app()->getStore()->getRootCategoryId();
        }
    }

    /**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
        $helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
    }

    /**
     * Retrieve child categories of current category
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getCurrentChildCategories()
    {
        $layer = Mage::getSingleton('catalog/layer');
        $category   = $layer->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $categories = $category->getChildrenCategories();
        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $layer->prepareProductCollection($productCollection);
        $productCollection->addCountToCategories($categories);
        return $categories;
    }

    /**
     * Checkin activity of category
     *
     * @param   Varien_Object $category
     * @return  bool
     */
    public function isCategoryActive($category)
    {
        if ($this->getCurrentCategory()) {
            return in_array($category->getId(), $this->getCurrentCategory()->getPathIds());
        }
        return false;
    }

    protected function _getCategoryInstance()
    {
        if (is_null($this->_categoryInstance)) {
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }

    /**
     * Get url for category data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_getCategoryInstance()
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }

    /**
     * Return item position representation in menu tree
     *
     * @param int $level
     * @return string
     */
    protected function _getItemPosition($level)
    {
        if ($level == 0) {
            $zeroLevelPosition = isset($this->_itemLevelPositions[$level]) ? $this->_itemLevelPositions[$level] + 1 : 1;
            $this->_itemLevelPositions = array();
            $this->_itemLevelPositions[$level] = $zeroLevelPosition;
        } elseif (isset($this->_itemLevelPositions[$level])) {
            $this->_itemLevelPositions[$level]++;
        } else {
            $this->_itemLevelPositions[$level] = 1;
        }

        $position = array();
        for($i = 0; $i <= $level; $i++) {
            if (isset($this->_itemLevelPositions[$i])) {
                $position[] = $this->_itemLevelPositions[$i];
            }
        }
        return implode('-', $position);
    }

    /**
     * Render category to html
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether ot not this item is last, affects list item class
     * @param boolean Whether ot not this item is first, affects list item class
     * @param boolean Whether ot not this item is outermost, affects list item class
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @param boolean Whether ot not to add on* attributes to list item
     * @return string
     */
    protected function _renderCategoryMenuItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
    		$isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
    	if (!$category->getIsActive()) {
    		return '';
    	}
    	$html = array();
    
    	// get all children
    	if (Mage::helper('catalog/category_flat')->isEnabled()) {
    		$children = (array)$category->getChildrenNodes();
    		$childrenCount = count($children);
    	} else {
    		$children = $category->getChildren();
    		$childrenCount = $children->count();
    	}
    	$hasChildren = ($children && $childrenCount);
    
    	// select active children
    	$activeChildren = array();
    	foreach ($children as $child) {
    		if ($child->getIsActive()) {
    			$activeChildren[] = $child;
    		}
    	}
    	$activeChildrenCount = count($activeChildren);
    	$hasActiveChildren = ($activeChildrenCount > 0);
    
    	// prepare list item html classes
    	$classes = array();
    	$classes[] = 'level' . $level;
    	$classes[] = 'nav-' . $this->_getItemPosition($level);
    	if ($this->isCategoryActive($category)) {
    		$classes[] = 'active';
    	}
    	$linkClass = '';
    	if ($isOutermost && $outermostItemClass) {
    		$classes[] = $outermostItemClass;
    		$linkClass = ' class="'.$outermostItemClass.'"';
    	}
    	if ($isFirst) {
    		$classes[] = 'first';
    	}
    	if ($isLast) {
    		$classes[] = 'last';
    	}
    	if ($hasActiveChildren || (strpos($this->getCategoryUrl($category), "womens-diamond-necklaces")!==false)) {
    		$classes[] = 'parent';
    	}
    
    	// prepare list item attributes
    	$attributes = array();
    	if (count($classes) > 0) {
    		$attributes['class'] = implode(' ', $classes);
    	}
    	if ($hasActiveChildren && !$noEventAttributes) {
    		$attributes['onmouseover'] = 'toggleMenu(this,1)';
    		$attributes['onmouseout'] = 'toggleMenu(this,0)';
    	}
    
    	// assemble list item with attributes
    	$htmlLi = '<li';
    	foreach ($attributes as $attrName => $attrValue) {
    		$htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
    	}
    	$htmlLi .= '>';
    	$html[] = $htmlLi;
    
    	if($category->getId()==408) {
    		$url = "/blog/celebrity-diamond-jewelry/";
    	} else {
    		$url = $this->getCategoryUrl($category);
    	}
    	$html[] = '<a href="'.$url.'"'.$linkClass.'>';
    
    	switch ($this->escapeHtml($category->getName())) {
    		case "Wedding Rings":
    			if($category->getId()==54) {
    				$cat = "Bridal";
    			} else {
    				$cat = $this->escapeHtml($category->getName());
    			}
    			break;
    		case "Who's Wearing Avianne &amp; Co?":
    			$cat = "Celebrities";
    			break;
    		default:
    			$cat = $this->escapeHtml($category->getName());
    			break;
    	}
    	$html[] = '<span>' . $cat . '</span>';
    	$html[] = '</a>';
    
    	// render children
    	$htmlChildren = '';
    	$j = 0;
    	foreach ($activeChildren as $child) {
    		$htmlChildren .= $this->_renderCategoryMenuItemHtml(
    				$child,
    				($level + 1),
    				($j == $activeChildrenCount - 1),
    				($j == 0),
    				false,
    				$outermostItemClass,
    				$childrenWrapClass,
    				$noEventAttributes
    		);
    		$j++;
    	}
    	if (!empty($htmlChildren)) {
    		if ($childrenWrapClass) {
    			$html[] = '<div class="' . $childrenWrapClass . '">';
    		}
    		$html[] = '<ul class="level' . $level . '">';
    		if(strpos($this->getCategoryUrl($category), "mens-diamond-rings-1")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-rings-1")===false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-rings-1"><span>Mens Diamond Rings</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "mens-diamond-chains-1")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-chains-1")===false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-chains-1"><span>Mens Diamond Chains</span></a></li>';
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-gold-chains"><span>Solid Gold Chains</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "mens-diamond-earrings-1")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-earrings-1")===false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-earrings-1"><span>Mens Diamond Earrings</span></a></li>';
    		}
    		/*if(strpos($this->getCategoryUrl($category), "mens-diamond-cufflinks")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-cufflinks")===false) {
    		 $html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'mens-diamond-cufflinks"><span>Diamond Cufflinks</span></a></li>';
    		}*/
    		if(strpos($this->getCategoryUrl($category), "mens-diamond-pendants-1")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-pendants-1")===false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-pendants-1"><span>Mens Diamond Pendants</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "mens-diamond-bracelets-1")!==false && strpos($this->getCategoryUrl($category), "womens-diamond-bracelets-1")===false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-bracelets-1"><span>Mens Diamond Bracelets</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "womens-diamond-rings-1")!==false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-diamond-rings-1"><span>Diamond Rings</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "womens-diamond-earrings-1")!==false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-diamond-earrings-1"><span>Diamond Earrings</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "womens-diamond-bracelets-1")!==false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-diamond-bracelets-1"><span>Diamond Bracelets</span></a></li>';
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'gold-diamond-bangle-bracelets"><span>Diamond Bangles</span></a></li>';
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'onyx-ball-bead-bracelet"><span>Ball Bead Bracelets</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "womens-diamond-pendants-1")!==false) {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-diamond-pendants-1"><span>Diamond Pendants</span></a></li>';
    		}
    		if(strpos($this->getCategoryUrl($category), "pre-set-diamond-engagement-rings")!==false&&$this->escapeHtml($category->getName())!=="Wedding Rings") {
    			$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'pre-set-diamond-engagement-rings"><span>Engagement Rings</span></a></li>';
    		}
    		if( (strpos($this->getCategoryUrl($category), "mens-custom-diamond-jewelry")!==false) ) {
    			$html[] = '<li class="level1 nav-6-1-1"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'blog/celebrity-diamond-jewelry/"><span>Celebrity Jewelry</span></a></li>';
    		}
    		$html[] = $htmlChildren;
    		$html[] = '</ul>';
    		if ($childrenWrapClass) {
    			$html[] = '</div>';
    		}
    	} elseif (strpos($this->getCategoryUrl($category), "womens-diamond-necklaces")!==false) {
    		if ($childrenWrapClass) {
    			$html[] = '<div class="' . $childrenWrapClass . '">';
    		}
    		$html[] = '<ul class="level' . $level . '">';
    		$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-diamond-necklaces"><span>Womens Necklaces</span></a></li>';
    		$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'ball-bead-necklaces"><span>Ball Bead Necklaces</span></a></li>';
    		$html[] = $htmlChildren;
    		$html[] = '</ul>';
    		if ($childrenWrapClass) {
    			$html[] = '</div>';
    		}
    	} /*elseif (strpos($this->getCategoryUrl($category), "mens-diamond-cufflinks")!==false) {
    	if ($childrenWrapClass) {
    	$html[] = '<div class="' . $childrenWrapClass . '">';
    	}
    	$html[] = '<ul class="level' . $level . '">';
    	$html[] = '<li class="level2 nav-1-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'mens-diamond-cufflinks"><span>Diamond Cufflinks</span></a></li>';
    	$html[] = $htmlChildren;
    	$html[] = '</ul>';
    	if ($childrenWrapClass) {
    	$html[] = '</div>';
    	}
    	}*/
    	$html[] = '</li>';
    	if(strpos($this->getCategoryUrl($category), "womens-colored-diamond-rings")!==false) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'pre-set-diamond-engagement-rings"><span>Diamond Engagement Rings</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'eternity-bands"><span>Diamond Eternity Bands</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'womens-wedding-bands"><span>Womens Wedding Bands</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'five-stone-diamond-rings"><span>Five-Stone Diamond Rings</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'three-stone-diamond-rings"><span>Three-Stone Diamond Rings</span></a></li>';
    	}
    	if(strpos($this->getCategoryUrl($category), "multi-colored-diamond-rings")!==false) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-diamond-wedding-bands"><span>Diamond Wedding Bands</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "womens-diamond-heart-rings")!==false) || ( strpos($this->getCategoryUrl($category), "eternity-bands")!==false) ) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'gold-wedding-bands"><span>Gold Wedding Bands</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "womens-diamond-hoop-earrings")!==false)) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'mens-black-diamond-earrings"><span>Black Diamond Earrings</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "womens-diamond-solitaire-pendants")!==false)) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-lock-and-key-pendants"><span>Diamond Key Pendants</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-initial-pendants"><span>Diamond Initial Pendants</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "onyx-ball-bead-bracelet")!==false) && strpos($this->getCategoryUrl($category), "womens-onyx-ball-bead-bracelet")===false) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-hip-hop-bracelets"><span>Hip Hop Bracelets</span></a></li>';
    	}
    
    	if( (strpos($this->getCategoryUrl($category), "diamond-rosary-chains")!==false)) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'cuban-chains"><span>Solid Gold Cuban Links</span></a></li>';
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'franco-chains"><span>Solid Gold Franco Chains</span></a></li>';
    	}
    
    	if( (strpos($this->getCategoryUrl($category), "gold-wedding-bands")!==false)) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-hip-hop-rings"><span>Hip Hop Rings</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "mens-black-diamond-earrings")!==false)) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-hip-hop-earrings"><span>Hip Hop Earrings</span></a></li>';
    	}
    	if( (strpos($this->getCategoryUrl($category), "mens-diamond-crosses")!==false) && strpos($this->getCategoryUrl($category), "womens-diamond-crosses")===false) {
    		$html[] = '<li class="level2 nav-2-1-0"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, null).'diamond-hip-hop-pendants"><span>Hip Hop Pendants</span></a></li>';
    	}
    
    	$html = implode("\n", $html);
    	return $html;
    }

    /**
     * Render category to html
     *
     * @deprecated deprecated after 1.4
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether ot not this item is last, affects list item class
     * @return string
     */
    public function drawItem($category, $level = 0, $last = false)
    {
        return $this->_renderCategoryMenuItemHtml($category, $level, $last);
    }

    /**
     * Enter description here...
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (Mage::getSingleton('catalog/layer')) {
            return Mage::getSingleton('catalog/layer')->getCurrentCategory();
        }
        return false;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getCurrentCategoryPath()
    {
        if ($this->getCurrentCategory()) {
            return explode(',', $this->getCurrentCategory()->getPathInStore());
        }
        return array();
    }

    /**
     * Enter description here...
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function drawOpenCategoryItem($category) {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }

        $html.= '<li';

        if ($this->isCategoryActive($category)) {
            $html.= ' class="active"';
        }

        $html.= '>'."\n";
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";

        if (in_array($category->getId(), $this->getCurrentCategoryPath())){
            $children = $category->getChildren();
            $hasChildren = $children && $children->count();

            if ($hasChildren) {
                $htmlChildren = '';
                foreach ($children as $child) {
                    $htmlChildren.= $this->drawOpenCategoryItem($child);
                }

                if (!empty($htmlChildren)) {
                    $html.= '<ul>'."\n"
                            .$htmlChildren
                            .'</ul>';
                }
            }
        }
        $html.= '</li>'."\n";
        return $html;
    }

    /**
     * Render categories menu in HTML
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function renderCategoriesMenuHtml($level = 0, $outermostItemClass = '', $childrenWrapClass = '')
    {
        $activeCategories = array();
        foreach ($this->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $html = '';
        $j = 0;
        foreach ($activeCategories as $category) {
            $html .= $this->_renderCategoryMenuItemHtml(
                $category,
                $level,
                ($j == $activeCategoriesCount - 1),
                ($j == 0),
                true,
                $outermostItemClass,
                $childrenWrapClass,
                true
            );
            $j++;
        }

        return $html;
    }

}
