<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Advancedmenu
 * @copyright  Copyright (c) 2008-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

class AW_Advancedmenu_Block_Navigationplain extends Mage_Catalog_Block_Navigation
{
    
    public function drawItem($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {

            return $html;
        }

        if (@class_exists('Mage_Catalog_Helper_Category_Flat') && Mage::helper('catalog/category_flat')->isEnabled()) {
           $children = $category->getChildrenNodes();
           $childrenCount = count($children);
				} else {
           $children = $category->getChildren();
           $childrenCount = $children->count();
        }

        $hasChildren = $children && $childrenCount;
        $html.= '<li';
        if ($hasChildren) {
             $html.= ' onmouseover="toggleMenu(this,1)" onmouseout="toggleMenu(this,0)"';
        }

        $html.= ' class="level'.$level;
        $html.= ' nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath()));
        if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
        if ($last && !$level) {
            $html .= ' last';
        }
        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
            $html .= ' parent';
        }
        $html.= '">'."\n";
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";
        //$html.= '<span>'.$level.'</span>';

		if($level)
			$html.= '</li>'."\n";

        if ($hasChildren){
			
            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren) && !$level) {
                $html.= '<ul class="level' . $level . '">'."\n"
                        .$htmlChildren
                        .'</ul>';
            }else{
				$html.=$htmlChildren;
			}

        }
		
		if(!$level)
			$html.= '</li>'."\n";	
        return $html;
    }
}
