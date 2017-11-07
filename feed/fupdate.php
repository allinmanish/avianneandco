<?php
if (!file_exists('AvianneInventory.xlsx')) {
	//$to      = 'i.gnedych@websitemovers.com';
	$to      = 'avianneandco@gmail.com';
	$subject = 'Sync Aviannes Feed with RealTime Povada Feed failed!';
	$message = 'Sync Aviannes Feed with RealTime Povada Feed failed!
	
File AvianneInventory.xlsx doesn\'t exist. Please check your feed folder.';
	$headers = 'From: webmaster@avianneandco.com' . "\r\n" .
	    'Reply-To: noreply@avianneandco.com' . "\r\n";
	
	mail($to, $subject, $message, $headers);
	exit();
}
if (file_exists('tmp/Povada.csv')) {
	unlink('tmp/Povada.csv');
}
if(!file_put_contents('tmp/Povada.csv', file_get_contents('http://www.povada.com/media/productsfeed/k.csv'))) {
	//$to      = 'i.gnedych@websitemovers.com';
	$to      = 'avianneandco@gmail.com';
	$subject = 'Sync Aviannes Feed with RealTime Povada Feed failed!';
	$message = 'Sync Aviannes Feed with RealTime Povada Feed failed!
	
Couldn\'t get Povada feed file. Please check http://www.povada.com/media/productsfeed/k.csv url.';
	$headers = 'From: webmaster@avianneandco.com' . "\r\n" .
			'Reply-To: noreply@avianneandco.com' . "\r\n";
	
	mail($to, $subject, $message, $headers);
	exit();
}

if (file_exists('tmp/AvianneInventory.csv')) {
	unlink('tmp/AvianneInventory.csv');
}
if (file_exists('tmp/AvianneMoldList.csv')) {
	unlink('tmp/AvianneMoldList.csv');
}
require_once 'PHPExcel/PHPExcel/IOFactory.php';
$excel = PHPExcel_IOFactory::load("AvianneInventory.xlsx");
$writer = PHPExcel_IOFactory::createWriter($excel, 'CSV');
$writer->setDelimiter(',');
$writer->setEnclosure('"');
$writer->save("tmp/AvianneInventory.csv");

// Remove excess columns
$inv_array = array();
$fh = fopen("tmp/AvianneInventory.csv", 'r');
while ( ($data = fgetcsv($fh,null,",")) !== FALSE) {
	$inv_array[$data[0]] = $data[1];
}
fclose($fh);
$_fh = fopen("tmp/AvianneInventory.csv", 'w+');
foreach ($inv_array as $_sku => $_qty) {
	fputcsv($_fh, array($_sku,$_qty));
}
unset($inv_array);
fclose($_fh);

if (file_exists('tmp/Avianne.csv')) {
	unlink('tmp/Avianne.csv');
}
if (file_exists("AvianneMoldList.xls")) {
	$excel = PHPExcel_IOFactory::load("AvianneMoldList.xls");
	$writer = PHPExcel_IOFactory::createWriter($excel, 'CSV');
	$writer->setDelimiter(',');
	$writer->setEnclosure('"');
	$writer->save("tmp/AvianneMoldList.csv");
	
	// Bootstrapping Magento
	require_once('../app/Mage.php');
	umask(0);
	// Set an Admin Session
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	Mage::getSingleton('core/session', array('name'=>'adminhtml'));
	$userModel = Mage::getModel('admin/user');
	$userModel->setUserId(1);
	$session = Mage::getSingleton('admin/session');
	$session->setUser($userModel);
	$session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());
	// Parsing Mold List
	$file = fopen('tmp/AvianneMoldList.csv', 'r');
	$molds = array();
	while ( ($data = fgetcsv($file,null,",")) !== FALSE) {
		$sku = array_shift((explode(" ", $data[0])));
		$product = Mage::getModel('catalog/product')
		->loadByAttribute('sku',$sku);
		if ($product) {
			$molds[] = $sku;
		}
	}
	fclose($file);
	// Parsing Inventory List
	$used_molds = array();
	$_file = fopen('tmp/AvianneInventory.csv', 'r');
	$new_file = fopen('tmp/Avianne.csv', 'a+');
	while ( ($data = fgetcsv($_file,null,",")) !== FALSE) {
		if (in_array(trim($data[0]),$molds)) {
			$data[1] = 1;
			$used_molds[] = trim($data[0]);
		}
		fputcsv($new_file, $data);
	}
	fclose($_file);
	$new_items = array_diff($molds, $used_molds);
	foreach ($new_items as $item) {
		fputcsv($new_file, array($item,1));
	}
	fclose($new_file);
	unset($molds);
} else {
	copy('tmp/AvianneInventory.csv', 'tmp/Avianne.csv');
}
// Parsing Povada feed
$povada_array = array();
$fp = fopen('tmp/Povada.csv', 'r');
while ( ($data = fgetcsv($fp,null,",")) !== FALSE) {
	$povada_array[$data[0]] = $data[3];
}
fclose($fp);
// Creating Result Feed
$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/AviannePovadaMolds.csv', 'w+');
$av = fopen('tmp/Avianne.csv', 'r');
while ( ($data = fgetcsv($av,null,",")) !== FALSE) {
	if ($data[0]=="Alternate Lookup") {
		continue;
	} elseif (in_array(trim($data[0]),array_keys($povada_array))) {
		$data[1] = $povada_array[trim($data[0])];
	}
	fputcsv($handle, $data);
}
fclose($av);
unset($povada_array);
fclose($handle);