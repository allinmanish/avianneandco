<?php
/**
 * OnePica_ImageCdn
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0), a
 * copy of which is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OnePica
 * @package    OnePica_ImageCdn
 * @author     OnePica Codemaster <codemaster@onepica.com>
 * @copyright  Copyright (c) 2009 One Pica, Inc.
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Helper methods
 */
class OnePica_ImageCdn_Helper_Image extends Mage_Catalog_Helper_Image
{	
	/**
	 * In older versions of Magento (<1.1.3) this method was used to get an image URL.
	 * However, 1.1.3 now uses the getUrl() method in the product > image model. This code
	 * was added for backwards compatibility.
	 *
	 * @return string
	 */
    public function __toString()
    {
        parent::__toString();
        return $this->_getModel()->getUrl();
    }
    
    public function directResize($img_min_w, $img_min_h, $ratio=0, $autoriseAgrandissement = false)
    {
    	//--> Search dimentions of the original image
    	$size 			= getimagesize($this);
    	$img_src_w 		= $size[0];
    	$img_src_h 		= $size[1];
    	//--> The calculation of the image is made only if the dimensions are smaller than those required Original
    	if($img_min_w < $img_src_w || $img_min_h < $img_src_h || $autoriseAgrandissement){
    		//--> Calculating the dimensions of the image to be displayed after the chosen ratio
    		switch ($ratio){
    			case 0:	//-- resizing the exact dimentions data
    				$img_min_w_calc	= $img_min_w;
    				$img_min_h_calc	= $img_min_h;
    				break;
    			case 1: //-- fixed height and width resizing calculated
    				$img_min_w_calc	= $img_min_w;
    				$img_min_h_calc	= round($img_src_h * $img_min_w_calc / $img_src_w);
    				break;
    			case 2: //-- fixed height and width resizing calculated
    				$img_min_h_calc	= $img_min_h;
    				$img_min_w_calc	= round($img_src_w * $img_min_h_calc / $img_src_h);
    				break;
    			case 3: //-- Image scaling for proportionally fit in the width and fixed heuteur
    				$ratio_wh		= $img_src_w / $img_src_h;
    				$ratio_whmin	= $img_min_w / $img_min_h;
    				if ($ratio_wh > $ratio_whmin){
    					$img_min_w_calc	= $img_min_w;
    					$img_min_h_calc	= round($img_src_h * $img_min_w_calc / $img_src_w);
    				} else {
    					$img_min_h_calc	= $img_min_h;
    					$img_min_w_calc	= round($img_src_w * $img_min_h_calc / $img_src_h);
    				}
    				break;
    			case 4: //-- for resizing the image to cover more than just the height and width set
    				if ($img_src_w/$img_src_h > $img_min_w/$img_min_h) {
    					$img_min_h_calc	= $img_min_h;
    					$img_min_w_calc	= round($img_src_w * $img_min_h_calc / $img_src_h);
    				} else {
    					$img_min_w_calc	= $img_min_w;
    					$img_min_h_calc	= round($img_src_h * $img_min_w_calc / $img_src_w);
    				}
    				break;
    		}
    		//--> The procedure for displaying the miniature continue by Magento
    		$this->resize($img_min_w_calc, $img_min_h_calc);
    	}
    	return $this;
    }
}