<?php
/* Author: JTS
	Description: Magento Google "Cancelled Feed"  for Trusted Stores
	Notes:  Just sends the transaction as "MerchantCancelled" as there is no real way to tell in Magento
			Right now it pulls the past day's worth of date.  You can adjust the range via the "from_date" and "to_date".
Google Feed Instructions:
	https://support.google.com/trustedstoresmerchant/bin/answer.py?hl=en&answer=2609890&ctx=go
 
*/
require_once '/var/www/html/store/app/Mage.php';
Mage::app();
 
//setup variables
$SEP="\t";
 
$from_date = strtotime("-1 day"); // begin date
$to_date = strtotime("+1 day"); //end date (really in the future and note necessary..just in case something shipped while we just executed
$orders=Mage::getModel('sales/order');
$to_date = date('Y-m-d' . ' 00:00:00', $to_date);
$from_date = date('Y-m-d' . ' 00:00:00', $from_date);
$orders = Mage::getModel('sales/order')->getCollection()
    ->addFieldToFilter('created_at', array('from'=>$from_date, 'to'=>$to_date))
	->addAttributeToFilter('status', 'canceled')
	->addAttributeToSort('created_at', 'DSC')
    ;
$string='';
$string = "merchant order id\treason\n";
foreach ($orders as $order) {
 
        $id=$order->getIncrementId() ; //7/10/12: Change to increment id
	#$objOrder	= Mage::getModel('sales/order')->load($id);
	#$iOrderNum	= $objOrder->getIncrementId();
		$string.=  $id.$SEP;
		$string.=  "MerchantCanceled";
		$string.=  "\n";
 
		#print "<BR> ID:$id CPAPNUMBE:$iOrderNum";

}
function cleanString($string){
	$string = str_replace("\\t",'',$string);
	$string = str_replace("\\n",'',$string);
	$string = str_replace("\\r",'',$string);
	return trim($string);
}
if (file_exists('google/avianne_cancellation_feed.txt')) {
	unlink('google/avianne_cancellation_feed.txt');
}
$fp = fopen('google/avianne_cancellation_feed.txt', 'w+');
fputs($fp, $string);
fclose($fp);
?>
