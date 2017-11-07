<?php 
/**
 * This Controller handles all the Home page . 
 * > guy@guysteuart.com
 * > Created Date: 26 Nov 2008 
 */

/**
 * Class Home
 */
 //ini_set("display_errors", 1);
//error_reporting(E_ALL);
class Cron extends Controller{
	function Cron() 
	{  
		parent::Controller();
		$this->load->model('cronmodel');	
			
	}
	function run(){
		
		//mail("shahadatsm@gmail.com","Diamond Database Updated",date('l jS \of F Y h:i:s A'));
          //mail("info@7techniques.com","Diamond Database Updated",date('l jS \of F Y h:i:s A'));		
		 $this->gethelixdiamonds();
			
			
	}
	function gethelixdiamonds()
	{
			$helixinclude  = '';// $this->cronmodel->gethelixinclude();
			$helixexclude  = '';//$this->cronmodel->gethelixexclude();

			if($helixinclude == '') $helixexclude = '39427,14152,14661,55155,16387,13321,8972,32856,34004,30579,18762,67851,13177,13712,48606,61592,67042,55582,18063,1928,24639,1309,50167,8142,53991,39216,30138,15558,13211,55605,39790,55149,6262,6907,48044,29361,12045,31896,32019,1178,12199,13789,15860,48623,39822,16172,12108,21677,44473,53443';
             if($helixexclude == '')  $helixinclude = '60037,34292,55009,3430,12724,33858,11685,13227,6603,45680,36778,23402,30597,9913,46115,32185,46677,19029,29424,30113,32640,59908,40476,13198,20996,28578,24538,16393,18908,24893,19515,56065,14948,32102,46668,51356,14255,4356,25336,26199,46913,66863,11811,60822,2655,65821,43225,32931,36177,53017,24321,43820,43615,8588,39038,20986,21571,19106,21592,24784,46761';						
			 if($helixinclude == '') 
			  // $curlurl ='http://www.diamonds.net/rapnet/DownloadListings/download.aspx?ExcludedSellers=39427&SortBy=owner&White=1&Programmatically=yes&cRatio=1';
			  $curlurl ='http://www.diamonds.net/rapnet/DownloadListings/download.aspx?HasCert=1&SortBy=owner&White=1&Fancy=1&Programmatically=yes&UseCheckedCulommns=1&cCT=1&cCERT=1&cCLAR=1&cCOLR=1&cCRTCM=1&cCountry=1&cCITY=1&cCulet=1&cCUT=1&cDPTH=1&cFLR=1&cGIRDLE=1&cLOTNN=1&cMEAS=1&cPOL=1&cPX=1&cDPX=1&cRapSpec=1&cRatio=1&cOWNER=1&cSHP=1&cSTATE=1&cSTOCK_NO=1&cSYM=1&cTBL=1&cSTONES=1&cCertificateImage=1&cCertID=1';
			  
             else  
			  $curlurl = 'http://www.diamonds.net/rapnet/DownloadListings/download.aspx?SellerLogin='.$helixinclude.'&ExcludedSellers='.$helixexclude.'&SortBy=owner&White=1&Programmatically=yes&cRatio=1'	;	
		     
			$login_url = 'https://technet.rapaport.com/HTTP/Authenticate.aspx';
			$post_string = "username=35620&password=".urlencode("american123");
			$urls = $login_url;
			//create HTTP POST request with curl:
			$ch = curl_init(); // initiate curl object
			curl_setopt($ch, CURLOPT_URL, $urls); 
			curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
			curl_setopt($ch, CURLOPT_SSLVERSION,3); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
			
			$auth_ticket = curl_exec($ch); // execute curl post and store results in $auth_ticket
			//print_r($ch);
			curl_close ($ch);
			
	//$curlurl ='http://technet.rapaport.com/HTTP/RapLink/download.aspx?SellerLogin='.$helixinclude.'&ExcludedSellers='.$helixexclude.'&HasCert=1&SortBy=owner&White=1&Fancy=1&Programmatically=yes&UseCheckedCulommns=1&cCT=1&cCERT=1&cCLAR=1&cCOLR=1&cCRTCM=1&cCountry=1&cCITY=1&cCulet=1&cCUT=1&cDPTH=1&cFLR=1&cGIRDLE=1&cLOTNN=1&cMEAS=1&cPOL=1&cPX=1&cDPX=1&cRapSpec=1&cRatio=1&cOWNER=1&cSHP=1&cSTATE=1&cSTOCK_NO=1&cSYM=1&cTBL=1&cSTONES=1&cCertificateImage=1&cCertID=1';
	$curlurl = 'http://technet.rapaport.com/HTTP/RapLink/download.aspx?SellerLogin='.$helixinclude.'&ExcludedSellers='.$helixexclude.'&HasCert=1&SortBy=owner&White=1&Fancy=1&Programmatically=yes&UseCheckedCulommns=1&cCT=1&cCERT=1&cCLAR=1&cCOLR=1&cCRTCM=1&cCountry=1&cCITY=1&cCulet=1&cCUT=1&cDPTH=1&cFLR=1&cGIRDLE=1&cLOTNN=1&cMEAS=1&cPOL=1&cPX=1&cDPX=1&cRapSpec=1&cRatio=1&cOWNER=1&cSHP=1&cSTATE=1&cSTOCK_NO=1&cSYM=1&cTBL=1&cSTONES=1&cCertificateImage=1&cCertID=1';
	$curlurl .= "&ticket=".$auth_ticket;								
			  $url = $curlurl;
			  $fp = fopen('import/rapnetfeed.csv',  'wb');
				if ($fp == FALSE)
				{
				echo "File not opened";
				//exit;
				}
			   //var_dump($url);
			  
			  // set_time_limit(18000);
			  @set_time_limit(0);
			  $ch = curl_init($url);	   
			  curl_setopt($ch, CURLOPT_URL, $url);
			  curl_setopt($ch, CURLOPT_HEADER, 1);
		//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			  //curl_setopt($ch, CURLOPT_USERPWD, '35696:samoa$velar');
   			  //curl_setopt($ch, CURLOPT_USERPWD, '71075:153687162');
			  //curl_setopt($ch, CURLOPT_USERPWD, '60542:cameronpc');
			  //curl_setopt($ch, CURLOPT_USERPWD, '60542:cameronpc');
			  //curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
			  $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
			  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			  $curldata = curl_exec($ch);
			  //print_r($curldata);
			  //die('ppt');
			   /*$ch = curl_init($url); // initiate curl object
			   curl_setopt($ch, CURLOPT_FILE, $fp); //Ask cURL to write the contents to a file
			   curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			   curl_setopt($ch, CURLOPT_TIMEOUT, 300); //set timeout to 5 mins
			   curl_exec($ch); // execute curl post
				// additional options may be required depending upon your server configuration
				// you can find documentation on curl options at http://www.php.net/curl_setopt
				curl_close ($ch); // close curl object
				fclose($fp); //close file;*/
				//die('ppt');
			   $a =  strpos($curldata , 'Content-Type: text/csv; charset=utf-8');
			   $b =  strlen('Content-Type: text/csv; charset=utf-8');
			   $offset = strpos($curldata , 'Content-Type: text/csv; charset=utf-8')+ strlen('Content-Type: text/csv; charset=utf-8');
				$curldata = substr($curldata , $offset);
			   //echo(substr($curldata,0,100));	 
			    curl_close($ch);
			  
			   $rows = explode("\n",$curldata);
			   $i = 0;
			 if(sizeof($rows) > 0) $r = $this->cronmodel->emptyhelix();
			
			   foreach ($rows as $row)
			   { 
			      //if($i==0 && $i==1)
				  //      echo($row);
				  if($i == 2) {
					  //echo '<br>Row'.$i;
					//echo'<br>'.$row;
				  }
			        if($i>2)
					{
						$cols = explode(',' , $row);
					//	print_r($cols);
						$t = $this->cronmodel->saveinhelix($cols);		
					 }
							
 					$i++;
			   }
			 $t = $this->cronmodel->fixhelix();
	  echo("Total $i rows added");
	  //$this->addDiamondtoEbay();
	}
	
	function addDiamondtoEbay($productID = ''){
	//	$data = $this->getCommonData();

		//echo'<Pre>';
			$data['details'] = $this->cronmodel->getAllDiamonds();
			foreach($data['details'] AS $index=>$value) {
					$status = $this->cronmodel->addDiamondtoEbay($value, 30);
					echo $status;
			}
		
		//print_r($data['details']);
		
	}

	function addToEbay($productID = ''){
	//	$data = $this->getCommonData();
		
		//echo'<Pre>';
			$data['details'] = $this->cronmodel->getDiamonds();
			//print_r($data['details']);
				
			foreach($data['details'] AS $index=>$value) {
					$status = $this->cronmodel->addDiamondtoEbay($value, 7);
		//			die('pp');
					echo $status;
			}
		
		//print_r($data['details']);
		
	}
} // class end
?>
