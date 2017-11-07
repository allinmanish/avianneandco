<?php
class VladimirPopov_WebForms_Block_Adminhtml_Element_File extends Varien_Data_Form_Element_Abstract{
	
	public function __construct($attributes = array())
	{
		parent::__construct($attributes);
		$this->setType('file');
		$this->setExtType('file');
	}

	public function _getName(){
		return "file_{$this->getData('field_id')}";
	}
	
	public function getElementHtml()
	{
		$this->addClass('input-file');
		if ($this->getRequired()) {
			$this->removeClass('required-entry');
			if(!$this->getData('value'))
				$this->addClass('required-file');
		}

		$element = sprintf('<input id="%s" name="%s" %s />%s',
			$this->getHtmlId(),
			$this->_getName(),
			$this->serialize($this->getHtmlAttributes()),
			$this->getAfterElementHtml()
		);

		return $this->_getPreviewHtml() . $element . $this->_getDeleteCheckboxHtml();
	}

	protected function _getPreviewHtml(){
		$html = '';
		if($this->getData('result_id')){
			$result = Mage::getModel('webforms/results')->load($this->getData('result_id'));
			$field_id = $this->getData('field_id');
			$value = $this->getData('value');
			if($value)
				$html='<nobr><a href="'.$result->getDownloadLink($field_id,$value).'">'.$value.'</a> <small>['.$result->getFileSizeText($field_id,$value).']</small></nobr>';
		}
		return $html;
	}
	
	protected function _getDeleteCheckboxHtml()
	{
		$html = '';
		if ($this->getValue() && !$this->getRequired() && !is_array($this->getValue())) {
			$checkboxId = sprintf('%s_delete', $this->getHtmlId());
			$checkbox   = array(
				'type'  => 'checkbox',
				'name'  => str_replace('file_','delete_file_',$this->getName()),
				'value' => '1',
				'class' => 'checkbox',
				'id'    => $checkboxId
			);
			$label      = array(
				'for'   => $checkboxId
			);
			if ($this->getDisabled()) {
				$checkbox['disabled'] = 'disabled';
				$label['class'] = 'disabled';
			}

			$html .= '<div class="' . $this->_getDeleteCheckboxSpanClass() . '">';
			$html .= $this->_drawElementHtml('input', $checkbox) . ' ';
			$html .= $this->_drawElementHtml('label', $label, false) . $this->_getDeleteCheckboxLabel() . '</label>';
			$html .= '</div>';
		}
		return $html;
	}

	protected function _getDeleteCheckboxSpanClass()
	{
		return 'delete-file';
	}

	protected function _getDeleteCheckboxLabel()
	{
		return Mage::helper('adminhtml')->__('Delete File');
	}
	
	protected function _drawElementHtml($element, array $attributes, $closed = true)
	{
		$parts = array();
		foreach ($attributes as $k => $v) {
			$parts[] = sprintf('%s="%s"', $k, $v);
		}

		return sprintf('<%s %s%s>', $element, implode(' ', $parts), $closed ? ' /' : '');
	}
	
}
?>
