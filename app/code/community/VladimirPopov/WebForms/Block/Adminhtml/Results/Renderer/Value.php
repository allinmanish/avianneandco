<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Results_Renderer_Value extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$field = Mage::getModel('webforms/fields')->load($field_id);
		$value =  $row->getData($this->getColumn()->getIndex());
		$html = '';
		if($field->getType() == 'file'){
			if(strlen($value)>1){
				$html='<nobr><a href="'.$this->getDownloadLink($row).'">'.$value.'</a> <small>['.$row->getFileSizeText($field_id,$value).']</small></nobr>';
			}
			else return;
		}
		if($field->getType() == 'image'){
			if(strlen($value)>1){
				if($this->getImageThumbnail($row))
					$html = '<a href="'.$this->getDownloadLink($row).'" target="_blank"><img src="'.$this->getImageThumbnail($row).'"/></a>';
				else 
					$html = '<nobr><a href="'.$this->getDownloadLink($row).'">'.$value.'</a> <small>['.$row->getFileSizeText($field_id,$value).']</small></nobr>';
			}
			else return;
		}
		if($field->getType() == 'stars'){
			$html = $this->getStarsBlock($row);
		}
		if($field->getType() == 'textarea'){
			$html = $this->getTextareaBlock($row);
		}
		if($field->getType() == 'wysiwyg'){
			$html = $this->getHtmlTextareaBlock($row);
		}
		if(strstr($field->getType() , 'date')){
			$html = $field->formatDate($value);
		}
		
		$html_object = new Varien_Object(array('html'=>$html));
		
		Mage::dispatchEvent('webforms_block_adminhtml_results_renderer_value_render',array('field'=>$field,'html_object'=>$html_object,'value'=>$value));
		
		if($html_object->getHtml())
			return $html_object->getHtml();
			
		return nl2br(htmlspecialchars($value));
	}
	
	public function getTextareaBlock(Varien_Object $row){
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$value =  htmlspecialchars($row->getData($this->getColumn()->getIndex()));
		if(strlen($value)> 200 || substr_count($value, "\n")>11){
			$div_id = 'x_'.$field_id.'_'.$row->getId();
			$onclick = "Effect.toggle('$div_id', 'slide', { duration: 0.3 }); this.style.display='none';  return false;";
			$pos = strpos($value,"\n",200);
			if($pos>300 || !$pos)
				$pos = strpos($value," ",200);
			if($pos>300)
				$pos = 200;
			if(!$pos) $pos = 200;
			$html = '<div>'.nl2br(substr($value,0,$pos)).'</div>';
			$html.= '<div id="'.$div_id.'" style="display:none">'.nl2br(substr($value,$pos,strlen($value))).'<br/></div>';
			$html.= '<a onclick="'.$onclick.'" style="text-decoration:none;float:right">['.$this->__('Expand').']</a>';
			return $html;
		}
		return nl2br($value);
	}
	
	public function getHtmlTextareaBlock(Varien_Object $row){
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$value =  $row->getData($this->getColumn()->getIndex());
		if(strlen(strip_tags($value))> 200 || substr_count($value, "\n")>11){
			$div_id = 'x_'.$field_id.'_'.$row->getId();
			$preview_div_id = 'preview_x_'.$field_id.'_'.$row->getId();
			$onclick = "$('{$preview_div_id}').hide(); Effect.toggle('$div_id', 'slide', { duration: 0.3 }); this.style.display='none';  return false;";
			$html = '<div style="min-width:400px" id="'.$preview_div_id.'">'.Mage::helper('webforms')->htmlCut($value,200).'</div>';
			$html.= '<div id="'.$div_id.'" style="display:none;min-width:400px">'.$value.'</div>';
			$html.= '<a onclick="'.$onclick.'" style="text-decoration:none;float:right">['.$this->__('Expand').']</a>';
			return $html;
		}
		return $value;
	}
	
	public function getDownloadLink(Varien_Object $row){
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$value =  Varien_File_Uploader::getCorrectFileName($row->getData($this->getColumn()->getIndex()));
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'webforms/'.$row->getId().'/'.$field_id.'/'.$value;
	}
	
	public function getImageThumbnail(Varien_Object $row){
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$value =  $row->getData($this->getColumn()->getIndex());
		return $row->getThumbnail($field_id,$value,Mage::getStoreConfig('webforms/images/grid_thumbnail_width'),Mage::getStoreConfig('webforms/images/grid_thumbnail_height'));
	}
	
	public function getStarsBlock(Varien_Object $row){
		$field_id = str_replace('field_','',$this->getColumn()->getIndex());
		$field = Mage::getModel('webforms/fields')->load($field_id);
		$value =  (int)$row->getData($this->getColumn()->getIndex());
		$blockwidth = ($field->getStarsCount()*16).'px';
		$width = round(100*$value/$field->getStarsCount()).'%';
		$html = "<div class='stars' style='width:$blockwidth'><ul class='stars-bar'><li class='stars-value' style='width:$width'></li></ul></div>";
		return $html;
	}
}
  
?>
