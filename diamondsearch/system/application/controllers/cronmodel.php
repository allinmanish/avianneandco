<?php  //ini_set("display_errors", 1);
//error_reporting(E_ALL);
 class Cronmodel extends Model {
 	
 	function __construct()
	{
 		parent::Model();
 	}
 	
 	function gethelixinclude()
	{
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixinclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	function gethelixexclude()
	{
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixexclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	function emptyhelix()
	{
		return $this->db->query('TRUNCATE TABLE '.config_item('table_perfix') . 'products');
	}
	
	function saveinhelix($cols)
	{
	  if(is_array($cols))
	  {
	  	$carat  		= isset($cols[0]) ? $cols[0] : '0';
	  	$Cert  			= isset($cols[1]) ? $cols[1] : '';
	  	$Cert_n  		= isset($cols[2]) ? $cols[2] : '';
		$clarity  		= isset($cols[3]) ? $cols[3] : '';
	  	$color  		= isset($cols[4]) ? $cols[4] : '';
	  	$Comment  		= isset($cols[5]) ? $cols[5] : '';
	  	$Country   		= isset($cols[6]) ? $cols[6] : '';
		$City    		= isset($cols[7]) ? $cols[7] : '';
	  	$Culet  		= isset($cols[8]) ? $cols[8] : '';
	  	$cut            = isset($cols[9]) ? $cols[9] : 0;
		$Depth  		= isset($cols[10]) ? $cols[10] : '0';
	  	$Flour  		= isset($cols[11]) ? $cols[11] : '';
	  	$Girdle  		= isset($cols[12]) ? $cols[12] : '';
	  	$lot 			= isset($cols[13]) ? $cols[13] : 0;
	  	$Meas  			= isset($cols[14]) ? str_replace('-','x',$cols[14]) : '0';
	  	$Polish  		= isset($cols[15]) ? $cols[15] : '';
	  	$price  		= isset($cols[16]) ? $cols[16] : '250';
	  	$Rap  	    	= isset($cols[17]) ? $cols[17] : '0';
	  	$Make    		= isset($cols[18]) ? $cols[18] : '';
	  	$ratio    		= isset($cols[19]) ? $cols[19] : '';
	  	$owner  		= isset($cols[20]) ? $cols[20] : 'NA';
	  	$shape  		= isset($cols[21]) ? $cols[21] : '';
	    $State   		= isset($cols[22]) ? $cols[22] : '';
	  	$Stock_n    	= isset($cols[23]) ? $cols[23] : '';
	  	$Sym  			= isset($cols[24]) ? $cols[24] : '';
	  	$TablePercent  	= isset($cols[25]) ? $cols[25] : 'NA';
	  	$Stones  		= isset($cols[26]) ? $cols[26] : '';
	  	$CertImage   	= isset($cols[27]) ? $cols[27] : '';
	  	$Date    		= isset($cols[28]) ? $cols[28]  : '';
		$MeasArray		= explode('x', $Meas);
	  	 if(strcmp($cut,'')===0) 
		   $cut='G';
		
	  	 
	  	$Cert = strtoupper($Cert);
	  	$ratio = ( isset($ratio) && $ratio != null) ? $ratio : ' ';
	  	$price = $this->erdprice($price * $carat) ;
	  	
		if(is_numeric($Depth) && is_numeric($TablePercent))
		{
		$helixInclude = strtolower (trim($this->gethelixinclude()));	
		$helixIncludeArray = explode(',', $helixInclude);
	    $tempowner = strtolower(trim($owner));
		if(in_array($tempowner,  $helixIncludeArray)) 
		{
		  $isinsert = $this->db->insert($this->config->item('table_perfix').'products',
					array('lot'    =>  $lot,
					       'owner' =>  $owner,
					       'shape' =>  $shape,
					       'carat' => $carat,
					       'color' => $color,
					       'clarity' => $clarity,
					       'cut'   => $cut,
					       'price' => $price,
					       'Rap'   => $Rap,
					       'Cert'  			=> $Cert,
					       'Depth' 			=> $Depth,
					       'TablePercent' 	=> $TablePercent,
					       'Girdle' 		=> $Girdle,
					       'Culet' 	=> $Culet,
					       'Polish' => $Polish,
					       'Sym' 	=> $Sym,
					       'Flour' 	=> $Flour,
					       'Meas' 	=> $Meas,
					       'Comment' 	=> $Comment,
					       'Stones' 	=> $Stones,
					       'Cert_n' 	=> $Cert_n,
					       'Stock_n' 	=> $Stock_n,
					       'Make' 	=> $Make,
					       'Date' 	=> $Date,
					       'City' 	=> $City,
					       'State' 	=> $State,
					       'Country' => $Country,
					       'ratio'  => $ratio,
					       'tbl'	=> config_item('base_url').'diamonds/search/true/true/false/false/false/'.$lot,
						   'certimage' => $CertImage,
						   'length' => $MeasArray[0],
						   'width' => $MeasArray[1],
						   'height' => $MeasArray[2]
						));
				if(!$isinsert)
				{
					return false;
				}
				else 
				    return true;	
				}
			}
	    }
	}

function fixhelix()
{
		$qry = "Update ". $this->config->item('table_perfix') ."products set Polish= 'GD' where Polish='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Polish= 'ID' where Polish='I'";
		$this->db->query($qry);
		
		/// fix fluro
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'NO' where Flour='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'MED' where Flour='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'ST BLUE' where Flour='SB'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'VST BLUE' where Flour='VSLB'";
		$this->db->query($qry);

		// fix SYMMETRY
		$qry = "Update ". $this->config->item('table_perfix') ."products set Sym= 'GD' where Sym='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Sym= 'ID' where Sym='I'";
		$this->db->query($qry);
		 
		// fix CULET
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'NO' where Culet='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'VS' where Culet='V'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'SM' where Culet='S'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'PN' where Culet='P'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'ME' where Culet='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'LR' where Culet='L'";
		$this->db->query($qry);
		
		// fix cut
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Good' where cut='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Very Good' where cut='VG'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Premium' where cut='EX'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Ideal' where cut='I'";
		$this->db->query($qry);
		
		/*
		// fix clarity 
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '0' where clarity='IF'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '1' where clarity='VVS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '2' where clarity='VVS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '3' where clarity='VS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '4' where clarity='VS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '5' where clarity='SI1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '6' where clarity='SI2'";
		$this->db->query($qry);
		
		  
		$DELETE  = "DELETE FROM ". $this->config->item('table_perfix') ."products WHERE ";
		$DELETE  .= " Cert  NOT IN('AGS', 'GIA')";
		$DELETE  .= " OR cut NOT IN('Good','Very Good','Premium','Ideal') "; 
		$DELETE  .= " OR clarity NOT IN('0','1','2','3','4','5','6') ";
		$DELETE  .= " OR Polish NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Sym NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Flour NOT IN('NO','FB','F','MB','MED','S','ST BLUE','VSL','VST BLUE') ";
		$DELETE  .= " OR Culet NOT IN('NO','VS','SM','PN','ME','LR') ";
		$DELETE  .= " OR price < 250";
		$DELETE  .= " OR color NOT IN('D','E','F','G','H','I','J') ";
		$DELETE  .= " OR sym NOT IN('EX','GD','ID','VG','F') ";
		$DELETE  .= " OR shape NOT IN('B','PR','R','E','AS','O','M','P','H','C') ";
		$this->db->query($DELETE);
		*/
	}
	
	function erdprice($price = 250){
 	if(trim(floatval($price)) < 1) $price = 0;
	$price=floatval($price);
 	$qry = "SELECT rate from ".config_item('table_perfix')."helix_prices
				WHERE  pricefrom <= $price and priceto >= $price
				";
		$result = 	$this->db->query($qry);
		$return = $result->result_array();	
		if(isset($return[0]))
		{
			//$price += $price * ($return[0]['rate']/100);
			$price = $price * $return[0]['rate'];
		}
		
		return $price;
 }

 function getDiamondByLOT($sku = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE lot = '".$sku."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	 
	function addDiamondtoEbay($detail, $action='Add'){
		//print_r($detail);
		switch ($detail['shape']){
			case 'B':
				$shape = 'Round';
			break;
			case 'PR':
				$shape = 'Princess';
			break;
			case 'R':
				$shape = 'Radiant';
				break;
			case 'E':
				$shape = 'Emerald';
				break;
			case 'AS':
				$shape = 'Asscher';
				break;
			case 'O':
				$shape = 'Oval';
				break;
			case 'M':
				$shape = 'Marquise';
				break;
			case 'P':
				$shape = 'Pear';
				break;
			case 'H':
				$shape = 'Heart';
				break;
			case 'C':
				$shape = 'Cushion';
				break;								  	
			default:
				$shape = $detail['shape'];
				break;
		 }		

		$requestArray['listingType'] = 'FixedPriceItem';
		$requestArray['primaryCategory'] = '152823';
        $requestArray['itemTitle']       = $detail['carat'].' -Carat '.$shape.' Shape Diamond';
		$requestArray['productID']       = $detail['lot'];
		$watchDetail = '<p>This <i> '.$detail['cut'].' </i>-cut, <i> '.$detail['color'].' </i>-color, and <i> '.$detail['clarity'].' </i>-clarity diamond comes accompanied by a diamond grading report from the '.$detail['Cert'].'.</p>';
		
		$watchdetail ='<table>';
		 
		$watchDetail .='<tr><td><B>Lot #:</B>'.ucfirst($detail['lot']).'</td><td>&nbsp;</td><td><B>Depth %:</B>'.ucfirst($detail['Depth']).'</td></tr>';
		$watchDetail .='<tr><td><B>Carat::</B>'.ucfirst($detail['carat']).'</td><td>&nbsp;</td><td><B>Table %:</B>'.ucfirst($detail['TablePercent']).'</td></tr>';
		$watchDetail .='<tr><td><B>Cut:</B>'.ucfirst($detail['cut']).'</td><td>&nbsp;</td><td><B>Symmetry: </B>'.ucfirst($detail['Sym']).'</td></tr>';
		$watchDetail .='<tr><td><B>Color:</B>'.ucfirst($detail['color']).'</td><td>&nbsp;</td><td><B>Polish: </B>'.ucfirst($detail['Polish']).'</td></tr>';
		$watchDetail .='<tr><td><B>Clarity:</B>'.ucfirst($detail['clarity']).'</td><td>&nbsp;</td><td><B>Girdle:</B>'.ucfirst($detail['Girdle']).'</td></tr>';
		$watchDetail .='<tr><td><B>Shape:</B>'.ucfirst($Shape).'</td><td>&nbsp;</td><td><B>Culet:</B>'.ucfirst($detail['Culet']).'</td></tr>';
		$watchDetail .='<tr><td>&nbsp;</td><td>&nbsp;</td><td><B>Fluorescence:</B>'.ucfirst($detail['Flour']).'</td></tr>';
		$watchDetail .='<tr><td>&nbsp;</td><td>&nbsp;</td><td><B>Measurements:</B>'.ucfirst($detail['Meas']).'</td></tr>';
		$watchDetail .='<tr><td colspan=3>&nbsp;</td></tr>';
		$watchDetail .='<tr><td colspan=3>Diamond Certificate Information</td></tr>';
		$watchDetail .='<tr><td colspan=3 align="left"><img src="'.$detail['certimage'].'"></td></tr>';

		
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($watchDetail);
        } else {
            $requestArray['itemDescription'] = $watchDetail;
        }
		
		$requestArray['listingDuration'] = 'Days_3';
        $requestArray['startPrice']      = $detail['price'];
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
		$requestArray['image'] = config_item('base_url').'/images/tamal/diamond/top_'.$shape.'.jpg';
		//print_R($requestArray);

//die('tt');
		//if($action=='Add') {
		if($detail['ebayid']=='') {	
			$itemID = $this->sendRequestEbay($requestArray);
		} 
		return $itemID;
	}

	function sendRequestEbay($requestArray, $section='watches') {
	
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
			
			  $this->db->where('lot' , $requestArray['productID']);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'products',
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

	function getAllDiamonds(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products WHERE price !='0.0' LIMIT 0,1
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

 }
?>