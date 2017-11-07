<?php

class Wee_Fpc_Model_Processor_CssJs extends Wee_Fpc_Model_Processor_Abstract
{
    const CSS_JS_KEY = 'css_js';
    
	public function prepareContent($content, array $requestParameter)
    {
    	$layout = Mage::getSingleton('core/layout');
    	$layout->getUpdate()->load('default');
    	$layout->generateXml()->generateBlocks();
    	
        $block = $layout->getBlock('head');
        $blockContent = $block->getCssJsHtml();
        
//         if ($_GET['debug'] == 'true') {
//         	die(var_dump( $blockContent, get_class($block) ));
//         }
        return $this->_replaceContent(self::CSS_JS_KEY, $blockContent, $content);
//         return $content;
    }

}