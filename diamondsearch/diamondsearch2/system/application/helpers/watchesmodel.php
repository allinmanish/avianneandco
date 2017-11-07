<?php 
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL);
	class Watchesmodel extends  Model {
	function __construct(){
		parent::Model();
	} 
	
	function getWatch($start = 0 , $rp =20, $isPave = false , $Solitaire = false , $platinum = true, $gold = true, $goldss = true, $minprice = 0 , $maxprice = 1000000 ,  $isMarkt = false){
		    $results = array();
		     
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			 
			  $qwhere .= " AND price1 >= ". str_replace('_','.',$minprice);
			  $qwhere .= " AND price1 <= ". str_replace('_','.',$maxprice);

			  
			  $instr = '';
			  if($isMarkt != 'false') {
				  $isMarktArray = explode('___', $isMarkt);
				  foreach($isMarktArray As $index=> $value) {
					 $instr .= "'".str_replace('_',' ',$value)."'," ;
				  }
			  } 
			 
			  //$instr .= ($isErd == 'erdcollection') 		? "'erd_collection'," 		: '';
			  //$instr .= ($isVatche == 'vatchecollection') 	? "'vatche_collection',"  	: '';
			  //$instr .= ($isDaussi == 'daussicollection') 	? "'daussi_collection',"  	: '';
			  //$instr .= ($isAntique == 'antiquecollection') ? "'antiqu_ecollection',"  	: '';
			  $design = 0;
			  if($instr != ''){
			  		$instr = substr($instr , 0 , (strlen($instr) -1));
				  	$qwhere .=  " AND brand in (".$instr.")";
				  	$design = 1;				  	
			  } 
			  
			  $instr = '';
			  $instr .= ($isPave == 'preowned') ? "'preowned',"  : '';
			  $instr .= ($Solitaire == 'new') ? "'new',"  : '';
			 // $instr .= ($Sidestone == 'sidestones') ?  "'Sidestones'," : ''; 
			  
			  //$instr .= ($isThreestone == 'threestone') ?  "'Three Stones'," : ''; 
			  //$instr .= ($isHalo == 'halo') ?  "'Halo'," : ''; 
			  //$instr .= ($isMatching == 'matching') ?  "'MatchingSet'," : ''; 
			  //$instr .= ($isAnniversary == 'anniversary') ?  "'Anniversary Ring'," : ''; 
			  
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND style in (".$instr.")"  ;
				  $design = 1;
			  }
			   
			 
			  $instr = '';
			  //$instr .= ($platinum == 'ss') ? "'Platinum','platinum','.950 platinum','950 Platinum',"  : '';
			  //$instr .= ($gold == 'gold') ? "'Yellow Gold','18 kt. Yellow Gold','18 Yellow Gold',"  : '';

			  $instr .= ($platinum == 'ss') ? "'ss',"  : '';
			  $instr .= ($gold == 'gold') ? "'gold',"  : '';
			  $instr .= ($goldss == 'gold_ss') ? "'gold_ss',"  : '';

			  //$instr .= ($whitegold == 'whitegold') ?  "'white gold','18 kt. White Gold'," : '';
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND metal in (".$instr.")"  ;
				  $design = 1;
			  } 
			  
			 // if($shape != 'all' && $shape != 'undefined'){
			   //$qwhere .=  " AND shape ='".$shape."'"  ;
			   
			  //}
			  
			  if($design==0)
			  {
			      $qwhere .=  " AND brand in ('')";
				  $qwhere .=  " AND style in ('')"  ;
				  $qwhere .=  " AND metal in ('')"  ;
				  
			  }
			  			    
			 
	$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'watches where 1=1 '. $qwhere . ' order by price1 desc '. $limit;
	//var_dump($sql); 
	//	exit(0);
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT  * FROM  '. $this->config->item('table_perfix').'watches  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
		//var_dump($results); 
		//exit(0);
    	return $results;
		
	}
	
	function getWatchByProductId($productID = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."watches
				WHERE productID = '".$productID."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getShapeByStockId($stockid){
		$qry = "SELECT DISTINCT(shape),id,image 
				FROM ".config_item('table_perfix')."ringimages 
				WHERE stock_number = '".$stockid."'GROUP BY shape
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
		
	function getFlashByStockId($stockid){
		$qry = "SELECT id,flash1, flash2 , flash3 ,image45 , image90, image180 ,image45_bg , image90_bg, image180_bg
				FROM ".config_item('table_perfix')."ringanimation 
				WHERE stock_num = '".$stockid."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getImageByStockAndShapeId($stockid, $shape, $id){
		$qry = "SELECT shape
				FROM ".config_item('table_perfix')."ringimages
				WHERE stock_number = '".$stockid." and shape=".$shape." and id=".$id."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getAllThreestoneRing(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."rings
				WHERE style = 'three stones'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

	function getWatchBrand(){
		$qry = "SELECT brand 
				FROM ".config_item('table_perfix')."watches 
				Group by brand
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();
		return $result;
	}

	function getWatchBySKU($sku = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."watches
				WHERE SKU = '".$sku."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	 
	function addWatchtoEbay($detail, $action='Add'){
		print_r($detail);
		$requestArray['listingType'] = 'FixedPriceItem';
		$requestArray['primaryCategory'] = '31387';
        $requestArray['itemTitle']       = $detail['productName'];
		$requestArray['productID']       = $detail['productID'];
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($detail['productDescription']);
        } else {
            $requestArray['itemDescription'] = $detail['productDescription'];
        }
		
		$requestArray['listingDuration'] = 'Days_7';
        $requestArray['startPrice']      = $detail['price2'];
        $requestArray['buyItNowPrice']   = '0.0';
        //$requestArray['quantity']        = $detail['quantity'];
		$requestArray['quantity']        = '1';
		if ($requestArray['listingType'] == 'StoresFixedPrice') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for SIF
          $requestArray['listingDuration'] = 'GTC';
        }
        
        if ($listingType == 'Dutch') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for Dutch
        }

		$requestArray['itemID'] = $detail['ebayid'];
		$requestArray['image'] = config_item('base_url').$detail['large'];
		//print_R($requestArray);

//die('tt');
		//if($action=='Add') {
		if($detail['ebayid']=='') {	
			$itemID = $this->sendRequestEbay($requestArray);
		} else {
			$itemID = $this->updateEbayItem($requestArray);
		}
		return $itemID;
	}

	function getAllWatches(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."watches
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

	function sendRequestEbay($requestArray) {
	
		global $userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel;
		include_once(config_item('base_path').'system/application/helpers/eBaySession.php');
		include_once(config_item('base_path').'system/application/helpers/keys.php');
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'AddItem';
		//echo 'devid'.$devID;
		///Build the request Xml string
		$requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>en_US</ErrorLanguage>';
		$requestXmlBody .= "<Version>$compatabilityLevel</Version>";
		$requestXmlBody .= '<Item>';
		$requestXmlBody .= '<Site>US</Site>';
		$requestXmlBody .= '<PrimaryCategory>';
		$requestXmlBody .= "<CategoryID>".$requestArray['primaryCategory']."</CategoryID>";
		$requestXmlBody .= '</PrimaryCategory>';
		$requestXmlBody .= "<BuyItNowPrice currencyID=\"USD\">".$requestArray['buyItNowPrice']."</BuyItNowPrice>";
		$requestXmlBody .= '<Country>US</Country>';
		$requestXmlBody .= '<Currency>USD</Currency>';
		$requestXmlBody .= "<ListingDuration>".$requestArray['listingDuration']."</ListingDuration>";
        $requestXmlBody .= "<ListingType>".$requestArray['listingType']."</ListingType>";
		$requestXmlBody .= '<Location><![CDATA[San Jose, CA]]></Location>';
		$requestXmlBody .= '<PaymentMethods>AmEx</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>VisaMC</PaymentMethods>';
		//$requestXmlBody .= '<PaymentMethods>Paypal</PaymentMethods>';
		//$requestXmlBody .= '<PayPalEmailAddress>test@ebay.com</PayPalEmailAddress>';
		$requestXmlBody .= "<Quantity>".$requestArray['quantity']."</Quantity>";
		$requestXmlBody .= '<RegionID>0</RegionID>';
		$requestXmlBody .= "<StartPrice>".$requestArray['startPrice']."</StartPrice>";
		$requestXmlBody .= '<ShippingTermsInDescription>True</ShippingTermsInDescription>';
		$requestXmlBody .= "<Title><![CDATA[".$requestArray['itemTitle']."]]></Title>";
		$requestXmlBody .= "<Description><![CDATA[".$requestArray['itemDescription']."]]></Description>";
		$requestXmlBody .= '<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>Days_30</ReturnsWithinOption>
								<Description>If you are not satisfied, return the book for refund.</Description>
								<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
							</ReturnPolicy>
							<ShippingDetails>
							  <ShippingServiceOptions>
								<ShippingService>Freight</ShippingService>
								<FreeShipping>false</FreeShipping>
							  </ShippingServiceOptions>
							  <ShippingType>FreightFlat</ShippingType>
							</ShippingDetails>
							<PictureDetails> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
							 </PictureDetails>'; 
		$requestXmlBody .= '</Item>';
		$requestXmlBody .= '</AddItemRequest>';
		
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
			
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		
		//if there are error nodes
		if($errors->length > 0)
		{
			$status = '<P><B>eBay returned the following error(s):</B>';
			//display each error
			//Get error code, ShortMesaage and LongMessage
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			//Display code and shortmessage
			$status .= '<P>'. $code->item(0)->nodeValue. ' : '. str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
			//if there is a long message (ie ErrorLevel=1), display it
			if(count($longMsg) > 0)
				$status .= '<BR>'.str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
	
		} else { //no errors
            
			//get results nodes
            $responses = $responseDoc->getElementsByTagName("AddItemResponse");
            foreach ($responses as $response) {
              $acks = $response->getElementsByTagName("Ack");
              $ack   = $acks->item(0)->nodeValue;
              //echo "Ack = $ack <BR />\n";   // Success if successful
              
              $endTimes  = $response->getElementsByTagName("EndTime");
              $endTime   = $endTimes->item(0)->nodeValue;
              //echo "endTime = $endTime <BR />\n";
              
              $itemIDs  = $response->getElementsByTagName("ItemID");
              $itemID   = $itemIDs->item(0)->nodeValue;
              // echo "itemID = $itemID <BR />\n";
              
              $linkBase = "http://cgi.sandbox.ebay.com/ws/eBayISAPI.dll?ViewItem&item=";
              $status = "<a href=$linkBase" . $itemID . ">".$requestArray['itemTitle']."</a> <BR />";

			  $this->db->where('productID' , $requestArray['productID']);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'watches',
			 		array(			  
			  				'ebayid'	=> $itemID,
			  				
						 ));
              
              $feeNodes = $responseDoc->getElementsByTagName('Fee');
              foreach($feeNodes as $feeNode) {
                $feeNames = $feeNode->getElementsByTagName("Name");
                if ($feeNames->item(0)) {
                    $feeName = $feeNames->item(0)->nodeValue;
                    $fees = $feeNode->getElementsByTagName('Fee');  // get Fee amount nested in Fee
                    $fee = $fees->item(0)->nodeValue;
                    if ($fee > 0.0) {
                        if ($feeName == 'ListingFee') {
                          $status .= "<B>$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n"; 
                        } else {
                          $status .= "$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n";
                        }      
                    }  // if $fee > 0
                } // if feeName
              } // foreach $feeNode
            
            } // foreach response
            
		} // if $errors->length > 0
		echo $status;
	}

	function updateEbayItem($requestArray) {
	echo config_item('base_path').'system/application/helpers/eBaySOAP.php';
		include config_item('base_path').'system/application/helpers/eBaySOAP.php';
		die('ty65t');
		$config = parse_ini_file(config_item('base_path').'system/application/helpers/ebay.ini', true);

		$site = $config['settings']['site'];
		$compatibilityLevel = $config['settings']['compatibilityLevel'];

		$dev = $config[$site]['devId'];
		$app = $config[$site]['appId'];
		$cert = $config[$site]['cert'];
		$token = $config[$site]['authToken'];
		$location = $config[$site]['gatewaySOAP'];

		// Create and configure session
		$session = new eBaySession($dev, $app, $cert);
		$session->token = $token;
		$session->site = 0; // 0 = US;
		$session->location = $location;

		try {
	$client = new eBaySOAP($session);

	$PrimaryCategory = array('CategoryID' => $requestArray['primaryCategory']);

	/*$Item = array('ListingType' => $requestArray['listingType']
				  'Currency' => 'USD',
				  'Country' => 'US',
				  'PaymentMethods' => 'PaymentSeeDescription',
				  'RegionID' => 0,
				  'ListingDuration' => $requestArray['listingDuration'],
				  'Title' => $requestArray['itemTitle'],
				  'SubTitle' => 'The new item subtitle',
				  'Description' => $requestArray['itemDescription'],
				  'Location' => "San Jose, CA",
				  'Quantity' => $requestArray['quantity'],
				  'StartPrice' => $requestArray['startPrice'],
				  'BuyItNowPrice' => $requestArray['buyItNowPrice'],
				  'PrimaryCategory' => $PrimaryCategory,
				 );*/

	// Get it to confirm
	$params = array('Version' => $compatibilityLevel, 'ItemID' =>  $requestArray['itemID']);
	$results = $client->GetItem($params);
	print_r($results);

	if($results->Errors->ErrorCode == '17') {
	// Revise it and change the Title and raise the BuyItNowPrice
		$status = $this->sendRequestEbay($requestArray);
	} else if($results->Errors) {
			$status = '<b>'.$results->Errors->ShortMessage.'<br>'.$results->Errors->LongMessage.'</b>';
	} else {
	$Item = array('Title' => $requestArray['itemTitle'],
				  'Description' => $requestArray['itemDescription'],
				  'Quantity' => $requestArray['quantity'],
				  'StartPrice' => $requestArray['startPrice'],
				  'BuyItNowPrice' => $requestArray['buyItNowPrice'],
				  );

	 
	$params = array('Version' => $compatibilityLevel, 
	                'Item' => $Item
	               );

	$results = $client->ReviseItem($params);
		//print_r($results);
		if($results->Errors) {
			// Revise it and change the Title and raise the BuyItNowPrice
			$status = '<b>'.$results->Errors->ShortMessage.'<br>'.$results->Errors->LongMessage.'</b>';
		} else {
			$status = '<b>Item Updated Successfully!</b>';
		}
	}
		echo $status;
	} catch (SOAPFault $f) {
		print $f; // error handling
	}
  }
}
?>