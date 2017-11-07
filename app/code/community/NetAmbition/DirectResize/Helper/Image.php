<?php

class NetAmbition_DirectResize_Helper_Image extends Mage_Catalog_Helper_Image
{

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

?>