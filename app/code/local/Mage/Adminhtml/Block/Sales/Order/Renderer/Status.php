<?php

class Mage_Adminhtml_Block_Sales_Order_Renderer_Status
extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {


        $value = $row->getData('eye4_status');
        $incid= $row->getData('increment_id');
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($incid);

        $fraudData = Mage::helper('eye4fraud_connector')->getOrderStatus($incid);//$fraudData['StatusCode']

        if($value == 'I')
        {
            $value='Insured';
        }else if($value == ''){


            

            $payment_method = $order->getPayment()->getMethodInstance()->getCode();
            if($payment_method =='amazon_payments'){

                $my_date = date("Y-m-d H:i:s");
                $dataEye4Fraud = array('order_id'=>$incid,'status'=>'I','updated_at'=>$my_date,'description'=>'Insured'); 
                $eye4fraudModel = Mage::getModel("eye4fraud_connector/status")->setData($dataEye4Fraud); 
                $eye4fraudModel->save();
                $value='Insured';
            }
            else $value='Declined';
        }else{
           $value='Declined';
       }


       return $value;
   }
}