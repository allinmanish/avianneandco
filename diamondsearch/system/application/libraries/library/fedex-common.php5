<?php

// Copyright 2009, FedEx Corporation. All rights reserved.

define('TRANSACTIONS_LOG_FILE', config_item('base_path').'logs/fedextransactions.log');  // Transactions log file

/**
 *  Print SOAP request and response
 */
function printRequestResponse($client) {
include_once(config_item('base_path').'system/application/libraries/library/xmlparser.php');

  echo '<h2>Transaction processed successfully.</h2>'. "\n"; 
 // echo '<h2>Request</h2>' . "\n";
 // echo '<pre>' . htmlspecialchars($client->__getLastRequest()). '</pre>';  
 // echo "\n";
   
 // echo '<h2>Response</h2>'. "\n";
 echo '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
 // echo "\n";

 // return htmlspecialchars($client->__getLastRequest());
	
  //$responseDoc = new DomDocument();
  //$response = $responseDoc->loadXML($client->__getLastRequest());
 // $result = $responseDoc->getElementsByTagName('RateReplyDetails');
 // $xml = simplexml_load_string($client->__getLastResponse());
 //   return $xml;

//$xmlParser = new xmlparser();
echo '<pre>';
//$array = $xmlParser->GetXMLTree(htmlspecialchars($client->__getLastResponse()));
//$array = htmlspecialchars($xmlParser->GetXMLTree($result);
$xml_element = new SimpleXMLElement($client->__getLastResponse(), NULL, false, 'http://www.w3.org/2003/05/soap-envelope');
$name_spaces = $xml_element->getNamespaces(true);
print_r($name_spaces);
foreach ($xml_element->children($name_spaces['env']) as $body)
{
printf("%s<br />", $body->getName());

foreach ($body->children($name_spaces['ns1']) as $function)
{
printf("%s<br />", $function->getName());

foreach ($function->children() as $parameters)
{
printf("%s =>%s<br />", $parameters->getName(), $parameters);
}
}
}
//$response = $client->__getLastResponse();
//$out = preg_replace('|&lt;([/\w]+)(:)|m','&lt;$1',$response);
//$out = preg_replace('|(\w+)(:)(\w+=\&quot;)|m','$1$3',$out);
//echo 'cv'.$xml = simplexml_load_string ( $out ); 
//print_r($xml);
}



/**
 *  Print SOAP Fault
 */  
function printFault($exception, $client) {
    echo '<h2>Fault</h2>' . "\n";                        
    echo "<b>Code:</b>{$exception->faultcode}<br>\n";
    echo "<b>String:</b>{$exception->faultstring}<br>\n";
    writeToLog($client);
}

/**
 * SOAP request/response logging to a file
 */                                  
function writeToLog($client){  
if (!$logfile = fopen(TRANSACTIONS_LOG_FILE, "a"))
{
   error_func("Cannot open " . TRANSACTIONS_LOG_FILE . " file.\n", 0);
   exit(1);
}

fwrite($logfile, sprintf("\r%s:- %s",date("D M j G:i:s T Y"), $client->__getLastRequest(). "\n\n" . $client->__getLastResponse()));
}

?>