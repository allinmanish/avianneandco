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

class AW_Advancedmenu_Block_Navigationdropdown extends Mage_Catalog_Block_Navigation
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


		$TAG_NAME = $level ? "option" : "select";
		
		
		
        $html.= "<$TAG_NAME";
        if (!$level) {
             $html.= ' onchange="window.location.href=this.options[this.selectedIndex].value" ';
        }

		

        $html.= ' class="level'.$level;
        $html.= ' nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath()));
        
		
		
		
		if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
        if ($last) {
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
       
        
		if(!$level){
			$html.= '">
				<option>'.$this->htmlEscape($category->getName()).'</option>
				<option value="'.$this->getCategoryUrl($category).'">-----------------------------------------'.
				'<option value="'.$this->getCategoryUrl($category).'">'.$this->htmlEscape($category->getName()).''
				;
		}else{
			$html.= '" value="'.$this->getCategoryUrl($category).'">'.str_repeat(' &nbsp;',($level)*4).$this->htmlEscape($category->getName());
		}
		
		//$html.= '">'."\n";
         //$html.= '">'."\n";
		//$html.= '<span>'.$level.'</span>';

        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
                $html.= //'<ul class="level' . $level . '">'."\n"
                        ''
						.$htmlChildren
                        ;//.'</ul>';
            }

        }
        $html.= "</$TAG_NAME>"."\n";
        return $html;
    }

   

}
