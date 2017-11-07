<?php

class SavantDegrees_GetDiamonds_Model_Product extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		parent::_construct();
		$this->_init('product/product');
	}

	public function pullFromRapnet($state)
	{
		//define("PWD" ,'samoa$velar');
		//1 - Authenticate with TechNet. The authentication ticket will be stored in $auth_ticket. Note this MUST be HTTPS.
		$auth_url = "https://technet.rapaport.com/HTTP/Authenticate.aspx";
		$post_string = "username=35696&password=" . urlencode('samoa$velar');

		//create HTTP POST request with curl:
		$request = curl_init($auth_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$auth_ticket = curl_exec($request); // execute curl post and store results in $auth_ticket
		curl_close ($request);


		//2 - prepare HTTP request for data. Copy the URL from the RapLink Feed page in RapNet.com:
		// go to: http://www.rapnet.com/RapNet/DownloadListings/Download.aspx, choose your parameters, and click
		// generate code. Make sure to specify the columns wanted. This can produce a very long URL.

		$feed_url = "http://technet.rapaport.com/HTTP/RapLink/download.aspx?AvailabilityIDs=1&StateID=$state&SortBy=Owner&White=1&Fancy=1&Programmatically=yes&UseCheckedCulommns=1&cCT=1&cCERT=1&cCLAR=1&cCOLR=1&cCRTCM=1&cCountry=1&cCITY=1&cCulet=1&cCuletSize=1&cCuletCondition=1&cCUT=1&cDPTH=1&cFLR=1&cGIRDLE=1&cLOTNN=1&cMEAS=1&cPOL=1&cPX=1&cDPX=1&cRapSpec=1&cRatio=1&cOWNER=1&cNC=1&cSHP=1&cSTATE=1&cSTOCK_NO=1&cSYM=1&cTBL=1&cSTONES=1&cCertificateImage=1&cCertID=1";
		$feed_url .= "&ticket=".$auth_ticket; //add authentication ticket:

		//prepare to save response as file.
		$fp = fopen('rapnetfeed'.$state.'.csv', 'wb');
		if ($fp == FALSE)
		{
			echo "File not opened";
			exit;
		}

		//create HTTP GET request with curl
		$request = curl_init($feed_url); // initiate curl object
		curl_setopt($request, CURLOPT_FILE, $fp); //Ask cURL to write the contents to a file
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_TIMEOUT, 300); //set timeout to 5 mins
		curl_exec($request); // execute curl post
		// additional options may be required depending upon your server configuration
		// you can find documentation on curl options at http://www.php.net/curl_setopt
		curl_close ($request); // close curl object
		fclose($fp); //close file;
//		EXIT;		


	}
	
	function readFeedFile($state){
		$fp = fopen('rapnetfeed'.$state.'.csv', 'r');
		while (($arr = fgetcsv($fp)) !== FALSE) {
			$all[] = $arr;
		}
		fclose($fp);
//		unlink('rapnetfeed'.$state.'.csv');
		return $all;		
	}
}