<?php

class Ip_Robots_Block_Developer extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $content = '<p></p>';
        $content.= '<style>';
        $content.= '.developer {
                        background:#FAFAFA;
                        border: 1px solid #CCCCCC;
                        margin-bottom: 10px;
                        padding: 10px;
                        height:auto;

                    }
                    .developer h3 {
                        color: #EA7601;
                    }
                    .contact-type {
                        color: #EA7601;
                        font-weight:bold;
                    }
                    .developer img {

                        float:left;
                        height:255px;
                        width:220px;
                    }
                    .developer .info {
                        border: 1px solid #CCCCCC;
                        background:#E7EFEF;
                        padding: 5px 10px 0 5px;
                        margin-left:230px;
                        height:250px;
                    }
                    ';
        $content.= '</style>';


        $content.= '<div class="developer">';
            $content.= '<a href="http://www.ecommerceoffice.com/" target="_blank"><img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/base/default/ip_robots/promo.jpg" alt="www.ecommerceoffice.com" /></a>';
            $content.= '<div class="info">';
                $content.= '<h3>PROFESSIONAL MAGENTO DEVELOPMENT</h3>';
                $content.= '<p>EcommerceOffice provide premium services for Business, Corporate built with the latest innovations in web-development and SEO niche. You will pay less to go live with our wide range of solutions and services.<br/>';
                $content.= 'If you need Magento development , please contact us.</p>';
                $content.= '--------------------------------------------------------<br>';
                $content.= '<span class="contact-type">Website:</span> <a href="http://www.ecommerceoffice.com/" target="_blank">www.ecommerceoffice.com</a>  <br/>';
                $content.= '<span class="contact-type">E-mail:</span> volgodark@gmail.com  / office.commerce@gmail.com<br/>';
                $content.= '<span class="contact-type">Skype:</span> volgodark  <br/>';
                $content.= '<span class="contact-type">Phone:</span> +7 988024 1612 / +7 909389 2222 <br/>';
                $content.= '<span class="contact-type">Facebook:</span> <a href="http://www.facebook.com/ivan.proskuryakov" target="_blank">visit</a>  <br/>';
                $content.= '<span class="contact-type">LinkedIn:</span> <a href="http://www.linkedin.com/pub/ivan-proskuryakov/31/200/316" target="_blank">visit</a>  <br/>';

                $content.= '</div>';

        $content.= '</div>';

        return '';


    }


}
