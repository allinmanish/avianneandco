<?php
class HM_QuickContact_Model_Source_Fields
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'phone', 'label' => 'Phone'),
            array('value' => 'subject', 'label' => 'Subject'),
			array('value' => 'contact_type', 'label' => 'Contact Type'),
        );
    }
}
?>