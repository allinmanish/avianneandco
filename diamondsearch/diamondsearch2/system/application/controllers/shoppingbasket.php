<?php



class Shoppingbasket extends Controller {



	function Shoppingbasket()

	{

		parent::Controller();

		$this->load->model('shoppingbasketmodel');



	}



	function index()

	{

		$data = $this->getCommonData();

		$data['title'] = 'Engagement';

		$output = $this->load->view('engagement/index' , $data , true);

		$this->output($output , $data);

	}



	private function getCommonData($banner='')

	{

		$data = array();

		$data = $this->commonmodel->getPageCommonData();

		return $data;



	}



	function output($layout = null , $data = array() , $isleft = true , $isright = true)

	{

		$data['loginlink'] = $this->user->loginhtml();

		$output = $this->load->view($this->config->item('template').'header' , $data , true);

		if($isleft)$output .= $this->load->view($this->config->item('template').'left' , $data , true);

		$output .= $layout;

		if($isright)$output .= $this->load->view($this->config->item('template').'right' , $data , true);

		$output .= $this->load->view($this->config->item('template').'footer', $data , true);

		$this->output->set_output($output);

	}	

		

	function mybasket($isajax = false)

	{

		$data = $this->getCommonData();

		$this->load->model('cartmodel'); 
		$this->load->model('jewelrymodel');
		

		$allitems = '';

		$price = '';

		$items = '';

		$allitems = $this->cartmodel->getitemsbysessionid();

		$data['mycartitems'] = $allitems;

		$this->session->set_userdata('myallitmes',$allitems);

		

		$data['onloadextraheader'] = "getcarthtml();";

			

		$data['title'] = 'My Shopping Basket';

		if($isajax) $data['ajax'] = true; 
    $data['title'] = "solitaire diamond rings|gemstone engagement rings|irish engagement rings|marquise engagement rings";
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="solitaire diamond rings|gemstone engagement rings|irish engagement rings|marquise engagement rings">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy gold diamond ring, diamond ring sale, sell diamond ring, unique diamond rings, 
custom diamond rings, diamond ring design, diamond rings online, marquise diamond rings, pink diamond rings, solitaire diamond rings, gemstone engagement rings, irish engagement rings, marquise engagement rings online">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="gold diamond ring, diamond ring sale, sell diamond ring, unique diamond rings, 
custom diamond rings, diamond ring design, diamond rings online, marquise diamond rings, pink diamond rings, solitaire diamond rings, gemstone engagement rings, irish engagement rings, marquise engagement rings online">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

		if(isset($_POST['checkout'])){

			$this->orderinformation($_POST);

			$output = $this->load->view('shoppingbasket/orderinformation' , $data , true); 

		}

		else $output = $this->load->view('shoppingbasket/basket' , $data , true);

		

		if($isajax) echo $output; else $this->output($output , $data);

	} 

	

	function orderinformation(){

		$data = $this->getCommonData();

		$data['title'] = 'Order Information'; 

		$data['totalprice'] = isset($_POST['totalprice']) ? $_POST['totalprice'] : 0; 

		

		$allitems = $this->session->userdata('myallitmes');

		$data['myallitems'] = $allitems; 

		

		if(isset($_POST)){

						

			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');

			

			if(isset($_POST['continueorder'])){

				$this->form_validation->set_rules('fname', 'First Name', 'required');		

				$this->form_validation->set_rules('lname', 'Last Name', 'required');

				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');		

				$this->form_validation->set_rules('phone', 'Phone number', 'required');

				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			}			

			

			$data['fname'] = $this->input->post('fname');

			$data['lname'] = $this->input->post('lname');

			$data['email'] = $this->input->post('email');

			$data['phone'] = $this->input->post('phone'); 

		

		 

			if ($this->form_validation->run() == FALSE){			

				 $output = $this->load->view('shoppingbasket/orderinformation' , $data , true); 	

				 $this->output($output , $data);

			}		

			else{ 

				

				if(isset($_POST) && sizeof($_POST)>0){

					$data['totalprice'] = $_POST['totalprice'];					

					if(isset($_POST['paymentmethod'])){

						 		 

						switch ($_POST['paymentmethod']){

							case 'creditcard':

								$this->billingandshipping($_POST); 

								//header('location:'.config_item('base_url').'shoppingbasket/billingandshipping');

								//$output = $this->load->view('shoppingbasket/billingandshipping' , $data , true);

								break;

							case 'phone': 

							case 'moneyorder':

								$this->billingandshipping($_POST); 

								//$output = $this->load->view('shoppingbasket/phoneorder' , $data , true);

								//$this->output($output , $data);

								break;

							case 'paypal':

								$this->billingandshipping($_POST);  

								$output = $this->load->view('shoppingbasket/paypal' , $data , true);

								$this->output($output , $data);

								break;

							default: 

								$output = $this->load->view('shoppingbasket/orderinformation' , $data , true);

								$this->output($output , $data);

								break;

						}

					}

					else {

						$output = $this->load->view('shoppingbasket/orderinformation' , $data , true);

					}			

					//$this->output($output , $data);

				} 

				else { header('location:'.config_item('base_url').'diamonds');} 

				

						

				//$this->output($output , $data);

				

			}

		

		

		}else { header('location:'.config_item('base_url').'diamonds');}  		

		

	} 

	

		function billingandshipping($post = ''){

		$data = $this->getCommonData();

		$data['title'] = 'Billing & Shipping'; 

		$data['post'] = '';

		$data['totalprice'] = 0;

		$data['orderid'] = '<small><i>(not exist)</i></small>';

		//$data['countries'] = $this->shoppingbasketmodel->country();

		$s_order = $this->session->userdata('svar_order');

		

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');



			

		

		//var_dump($_POST); 

		if(isset($_POST['continuebillingandshipping']))

		     $this->session->set_userdata('ccinfo',$_POST) ;

		

		$data['cardtype'] = $this->input->post('cardtype');

		$data['creditcardno'] = $this->input->post('creditcardno');

		$data['expmonth'] = $this->input->post('expmonth');

		$data['expyear'] = $this->input->post('expyear'); 

		$data['cvvcode'] = $this->input->post('cvvcode');

		$data['fname'] = $this->input->post('fname');

		$data['lname'] = $this->input->post('lname');

		$data['address1'] = $this->input->post('address1');

		$data['address2'] = $this->input->post('address2');

		$data['city'] = $this->input->post('city'); 

		$data['statex'] = ($this->input->post('country') == 'US') ? $this->input->post('state') : $this->input->post('statetxt'); 

		$data['countryx'] = $this->input->post('country');

		$data['postcode'] = $this->input->post('postcode'); 

		$data['phonecode'] = $this->input->post('phonecode'); 

		$data['phone'] = $this->input->post('phone'); 

		$data['extension'] = $this->input->post('extension'); 

		

		$data['rcvname'] = $this->input->post('rcvname');

		$data['rcvaddress1'] = $this->input->post('rcvaddress1'); 

		$data['rcvaddress2'] = $this->input->post('rcvaddress2'); 

		$data['rcvcity'] = $this->input->post('rcvcity');

		$data['rcvstatex'] = ($this->input->post('rcvcountry') == 'US') ? $this->input->post('rcvstate') : $this->input->post('rcvstatetxt'); 

		$data['rcvcountry'] = $this->input->post('rcvcountry'); 

		$data['rcvphone'] = $this->input->post('rcvphone'); 

		$data['rcvphonecode'] = $this->input->post('rcvphonecode'); 

		$data['rcvextension'] = $this->input->post('rcvextension'); 

		

		

		

		$allitems = $this->session->userdata('myallitmes');

		$data['myallitems'] = $allitems; 

			

		if(isset($post) && sizeof($post)>0){ 

			

			if(isset($post['paymentmethod'])){ 

					$fname = $post['fname'];

					$lname = $post['lname'];

					$email = $post['email'];

					$phone = $post['phone'];

					$address = $post['address1'].$post['address2'];

		

					$totalprice = $post['totalprice'];

					$paymentmethod = $post['paymentmethod'];

					if(strcmp($post['paymentmethod'],'creditcard')===0)

					    $totalprice = round(($totalprice + (($totalprice*3)/100)),'0');

						

					$orderdate = date("Y-m-d");

					$deliverydate = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+2, date("Y")));	

					

					$customer = $this->shoppingbasketmodel->saveCustomerinfo($fname,$lname,$email,$phone,$address);

					$customerid = $customer['id'];

					

					//echo('Customer : ');print_r($customer);

					//exit(0);

					$this->session->set_userdata('svar_customerid',$customerid) ;

					if($customerid){

							$s_order = array(

										'customerid' 	=>$customerid,

										'totalprice' 	=>$totalprice,

										'paymentmethod' =>$paymentmethod,

										'orderdate' 	=>$orderdate,

										'deliverydate' 	=>$deliverydate

							);

							

							$this->session->set_userdata('svar_order',$s_order);

					}

					

									

					$order = $this->shoppingbasketmodel->saveOrder($customerid,$totalprice,$paymentmethod,$orderdate,$deliverydate);

					$data['orderid'] = $order['id'];

					$this->session->set_userdata('svar_orderid',$order['id']);

					//print_r($order);

                 // exit(0);	

				// echo($post['paymentmethod']);

				  //  $this->shoppingbasketmodel->saveOrderdetails ($order['id']);

					switch ($post['paymentmethod']){

						case 'creditcard': 

						$orderid 	 = $this->session->userdata('svar_orderid');

							//var_dump($orderid);

							//exit(0);

							$output = $this->load->view('shoppingbasket/billingandshipping' , $data , true);

							$this->output($output , $data);

							break;

						case 'phone':

						case 'moneyorder':

						    $orderid 	 = $this->session->userdata('svar_orderid');

							//var_dump($orderid);

							//exit(0);

							$this->shoppingbasketmodel->saveOrderdetails ($orderid);

							$this->shoppingbasketmodel->confirmOrder($orderid, true);

							//$orderid = $this->session->userdata('svar_orderid');

		                    // $this->shoppingbasketmodel->confirmOrder($orderid, true);

							

							$data['orderid']=$orderid ;

							$output = $this->load->view('shoppingbasket/phoneorder' , $data , true);

							$this->output($output , $data);

							break;

						case 'paypal': 

							$output = $this->load->view('shoppingbasket/paypal' , $data , true);

							$this->output($output , $data);

							break;

						default:

							$output = $this->load->view('shoppingbasket/orderinformation' , $data , true);

							$this->output($output , $data);

							break;

					}

			}

			elseif(isset($_POST['continuebillingandshipping'])){
				

					$this->form_validation->set_rules('cardtype', 'Crdit card', 'required');	

					$this->form_validation->set_rules('creditcardno', 'Crdit card no', 'required');		

					$this->form_validation->set_rules('expmonth', 'Expiry month', 'required');				

					$this->form_validation->set_rules('expyear', 'Expiry year', 'required');

					$this->form_validation->set_rules('cvvcode', 'CVV code', 'required');				

					$this->form_validation->set_rules('fname', 'First Name', 'required');

					$this->form_validation->set_rules('lname', 'Last Name', 'required');				

					$this->form_validation->set_rules('address1', 'Address', 'required');

					$this->form_validation->set_rules('country', 'Country', 'required');

					$this->form_validation->set_rules('postcode', 'Post code', 'required');				

					$this->form_validation->set_rules('phone', 'Phone number', 'required');

					$this->form_validation->set_rules('city', 'City', 'required');

					if($_POST['country'] != 'US'){
						$this->form_validation->set_rules('statetxt', 'State', 'required');		
					} 
					
					if($_POST['shipaddress'] == 'no'){

							$this->form_validation->set_rules('rcvname', 'Receiver name', 'required');				

							$this->form_validation->set_rules('rcvaddress1', 'Receiver address', 'required');

							$this->form_validation->set_rules('rcvcity', 'Receiver city', 'required');				

							if($_POST['rcvcountry'] == 'US'){
								$this->form_validation->set_rules('rcvstatetxt', 'Receiver State', 'required');		
							} 

							$this->form_validation->set_rules('rcvcountry', 'Receiver country', 'required');

							$this->form_validation->set_rules('rcvphone', 'Reciever phone', 'required');

					} 

					
					if($_POST['country'] != 'US'){
						$this->form_validation->set_rules('intshipping', 'Shipping policy', 'required');
					}
					 

					$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			

					

					if ($this->form_validation->run() == FALSE){			

							 $output = $this->load->view('shoppingbasket/billingandshipping' , $data , true); 	

							 $this->output($output , $data);

					}

					else{

							 $orderid 	 = $this->session->userdata('svar_orderid');

							 $customerid = $this->session->userdata('svar_customerid') ;

							 $data['orderid'] = $orderid;
							
							 $data['customerid'] = $customerid;

							 $order = $this->session->userdata('svar_order');

							 $paymentmethod = $order['paymentmethod'];

							 //print_r($_POST);

							 //var_dump($_POST);

							 //$creditcardname 	= $_POST['cardtype']

							 $creditcardname	= isset($_POST['cardtype']) ? $_POST['cardtype'] : '';

							 $creditcardno		= $_POST['creditcardno'];

							 

							 $expdate 			= date("Y-m-d",mktime(0, 0, 0, $_POST['expmonth']  , '01', $_POST['expyear']));	

							 

							 /*$cardexpiry 		= $_POST['expmonth'];

							 $cardtype 			= $_POST['expyear'];*/

							 

							 $cvvcode			= $_POST['cvvcode'];

							 

							 $fname 			= $_POST['fname'];

							 $lname 			= $_POST['lname'];

							 $company 			= $_POST['company'];

							 $adress 			= $_POST['address1'].' '.$_POST['address2'];	
							 
							 $city 				= $_POST['city'];

							 $state 			= ($_POST['country'] == 'US') ? $_POST['state'] : $_POST['statetxt'];

							 $country 			= $_POST['country'];

							 $postcode 			= $_POST['postcode'];

							 $phonecodecountry	= $_POST['phonecode'];

							 $phone 			= $_POST['phone'];

							 $ext 				= $_POST['extension'];

							  $issamedestination = ($_POST['shipaddress'] == 'yes') ? 1 : 0;

							 

							 $rcvname 			= $_POST['rcvname'];

							 $rcvcompany		= $_POST['rcvcompany']; 

							 $rcvaddress 		= $_POST['rcvaddress1'].' '.$_POST['rcvaddress2'];
							 
							 $rcvcity 			= $_POST['rcvcity'];

							 $rcvstate 			= ($_POST['rcvcountry'] == 'US') ? $_POST['rcvstate'] : $_POST['rcvstatetxt'];

							 $rcvcountry 		= $_POST['rcvcountry'];

							 $rcvpostcode 		= $_POST['rcvpostcode'];

							 $rcvphonecodecountry= $_POST['rcvphonecode']; 

							 $rcvphone 			= $_POST['rcvphone'];

							 $rcvext 			= $_POST['rcvextension'];

							 $referencecode 	= $_POST['referencecode'];

							 $giftcertificate 	= $_POST['giftcertificate'];							 

							 $howdidyouknow 	= $_POST['howdidyouknow'];		

							// $email=	$_POST['email'];					 

							$shippingmethod 	= $_POST['shippingmethod'];

							$shippingamoount 			= $_POST['shipping_amoount'];

							

							  $oinfo['creditcardname'] = $creditcardname;

							  $oinfo['creditcardno']=$creditcardno;

							  $oinfo['expdate']=$expdate;

							 // $oinfo['email']=$email;

								 $this->session->set_userdata('ccinfo', $_POST) ;

							 

							 $this->shoppingbasketmodel->saveOrderdetails($orderid);

							 $this->shoppingbasketmodel->saveShippinginfo($orderid, $customerid, $paymentmethod, $creditcardname, $creditcardno, $cvvcode, $expdate, $rcvname, $rcvphone, $rcvphonecodecountry, $rcvext, $rcvcompany, $rcvaddress, $rcvcity, $rcvstate, $rcvcountry, $rcvpostcode, $referencecode, $giftcertificate, $howdidyouknow, $fname, $lname, $company, $adress, $city, $state, $country, $postcode, $phonecodecountry, $phone,  $ext, $shippingmethod, $shippingamoount, $issamedestination);

							

							 

							 $output = $this->load->view('shoppingbasket/revieworder' , $data , true); 	

							 $this->output($output , $data);

					}

			}

			elseif($_POST['confirmorder']){							
							
							$orderid = $this->session->userdata('svar_orderid');

							

							//$data['myallitems']=$allitems;

							$this->orderconfirmation($orderid);

							//$data['myallitems'] = $_SESSION['myallitems'];

							

							//unset($_SESSION['myallitems']);

							//$this->shoppingbasketmodel->confirmOrder($orderid, 1);

							//header('location:'.config_item('base_url').'shoppingbasket/orderconfirmation');

			}

			else { 

							$output = $this->load->view('shoppingbasket/orderinformation' , $data , true);

							$this->output($output , $data);

			}			

			/*$this->output($output , $data);*/

		} 

		else { header('location:'.config_item('base_url').'diamonds');} 

	 	

		

	}

	

	function orderconfirmation($orderid = ''){

		$data = $this->getCommonData();

		$data['title'] = 'Order Confirmation'; 

		

		$allitems = $this->session->userdata('myallitmes');

		$ccinfo=$this->session->userdata('ccinfo');

		$data['myallitems']=$allitems;

		$data['ccinfo']=$ccinfo;

		$orderid = $this->session->userdata('svar_orderid');

		$data['orderid'] = $orderid;
		
		$customerid = $this->session->userdata('svar_customerid') ;

		$data['customerid'] = $customerid;

		$this->shoppingbasketmodel->confirmOrder($orderid, true);

		$output = $this->load->view('shoppingbasket/conf' , $data , true);

		$this->output($output , $data);

	} 

	

	function getOrderInfoByID($orderid = '')

	{

		if($orderid!='')

		{

		    $this->load->model('adminmodel');

		

	     	$data['order'] = $this->adminmodel->orderinfo($orderid);
			  $data['orderdetails'] = $this->adminmodel->orderdetails($orderid);

			//$data['order'] = $this->adminmodel->downloadCSV();

		  // print_r($data['order']);

		   $customerid = (isset($data['order']['0']['customerid'])) ? $data['order']['0']['customerid'] : '';

		   $lot = (isset($data['order']['0']['lot'])) ? $data['order']['0']['lot'] : '';

		   if($customerid!='')

		     $data['customer'] = $this->adminmodel->getCustomerByID($customerid);

		   else

		     $data['customer']=array();

			

		  if($lot!='')

		    $data['product'] = $this->adminmodel->getProductByLot($lot);

		  else

		     $data['product']=array();	

			 

		  $data['shipping'] = $this->adminmodel->getShippingByID($orderid);

		   

		   

		    $output = $this->load->view('shoppingbasket/vieworder' , $data , true);

			//$output = $this->load->view('erd/order' , $data , true);

		 $this->load->model('shoppingbasketmodel');

		 $this->shoppingbasketmodel->sendConfirmEmail($orderid);

			

		  echo $output;

		}

	}  

	function estimateShipping($service='', $country='', $zipcode='', $state='', $city='', $street_address='' )

	{
		require_once(config_item('base_path').'system/application/libraries/library/fedex-common.php5');

		$newline = "<br />";
		//The WSDL is not included with the sample code.
		//Please include and reference in $path_to_wsdl variable.
		$path_to_wsdl = config_item('base_path').'system/application/libraries/wsdl/RateService_v7.wsdl';
		//$path_to_wsdl = "../wsdl/RateService_v3.wsdl";
		ini_set("soap.wsdl_cache_enabled", "0");
		
		$this->load->model('cartmodel'); 
		$this->load->model('jewelrymodel');
		

		$allitems = $this->cartmodel->getitemsbysessionid();
		//print_r($allitems);

		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		$request['WebAuthenticationDetail'] = array('UserCredential' =>
											  array('Key' => 'AVdBxcwx47pVsZe9', 'Password' => 'koNvuiSexsIxgevPnu4nwYnm9')); // Replace 'XXX' and 'YYY' with FedEx provided credentials 
		$request['ClientDetail'] = array('AccountNumber' => '114790990', 'MeterNumber' => '101727405');// Replace 'XXX' with your account and meter number
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v7 using PHP ***');
		$request['Version'] = array('ServiceId' => 'crs', 'Major' => '7', 'Intermediate' => '0', 'Minor' => '0');
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		//$request['RequestedShipment']['ServiceType'] = 'PRIORITY_OVERNIGHT'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
		$request['RequestedShipment']['ServiceType'] = $service;
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$request['RequestedShipment']['Shipper'] = array('Address' => array(
												  'StreetLines' => array('10 Fed Ex Pkwy'), // Origin details
												  'City' => 'Los Angeles',
												  'StateOrProvinceCode' => 'TN',
												  'PostalCode' => '38115',
												  'CountryCode' => 'US'));
		if($country == 'US') {
			$request['RequestedShipment']['Recipient'] = array('Address' => array (
														   'StreetLines' => array($street_address), // Destination details
														   'City' => $city,
														   'StateOrProvinceCode' => $state,
														   'PostalCode' => $zipcode,
														   'CountryCode' => $country));
		} else{
			$request['RequestedShipment']['Recipient'] = array('Address' => array (
													   'StreetLines' => array($street_address), // Destination details
													   'City' => $city,
													   'PostalCode' => $zipcode,
													   'CountryCode' => $country));
		}
		$request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
																'Payor' => array('AccountNumber' => '114790990', // Replace 'XXX' with payor's account number
																			 'CountryCode' => 'US'));
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		$request['RequestedShipment']['PackageCount'] = '2';
		$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';  //  Or PACKAGE_SUMMARY
	//	echo '<pre>';
		$i=0;
		foreach($allitems AS $index=>$value) {
		
		 if($value['addoption']=='addwatch') {
			$weight = '3.0';
			$length = 11;
			$width = 9;
			$height = 6;
		 } else {
			$weight = '1.0';
			$length = 14;
			$width = 12;
			$height = 6;
		 }
		
		$request['RequestedShipment']['RequestedPackageLineItems'][$i]['Weight'] =  array('Value' => 2.0,
																					'Units' => 'LB');
																							
		$request['RequestedShipment']['RequestedPackageLineItems'][$i]['Dimensions'] =	 array('Length' => 10,																																'Width' => 10,
																								'Height' => 3,
																								'Units' => 'IN');
		$i++;
	 }

	// print_r($request['RequestedShipment']['RequestedPackageLineItems']);
		/*$request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => 2.0,
																							'Units' => 'LB'),
																							'Dimensions' => array('Length' => 10,
																								'Width' => 10,
																								'Height' => 3,
																								'Units' => 'IN')),
																		   '1' => array('Weight' => array('Value' => 5.0,
																							'Units' => 'LB'),
																							'Dimensions' => array('Length' => 20,
																								'Width' => 20,
																								'Height' => 10,
																								'Units' => 'IN')));*/
	//	print_r($request['RequestedShipment']['RequestedPackageLineItems']);

		try 
		{
			$response = $client ->getRates($request);
				
			if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
		   {
				//echo '<pre>';
				//printRequestResponse($client);
				//echo $response->RateReplyDetails->DeliveryTimestamp;
				//$delvery_date_arr = explode('T', $response->RateReplyDetails->DeliveryTimestamp);
				//echo $response->RateReplyDetails->DeliveryTimestamp;
				//$delvery_date = explode('-', $delvery_date_arr[0]);
				$amount = $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
				//print_r($response->RateReplyDetails->RatedShipmentDetails);
				//echo 'Amount'.$response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
				echo '<B><font color ="red">Estimated Shipping cost would be $'.$amount.'.</font></B>~'.$amount;

			}
			else
			{
				echo '<B><font color ="red">Error in processing transaction.'. $newline; 
				foreach ($response -> Notifications as $notification)
				{           
					if(is_array($response -> Notifications))
					{              
					   echo $notification -> Severity;
					   echo ': ';           
					   echo $notification -> Message;
					}
					else
					{
						echo $notification;
					}
				} 
				echo '</font></b>';
			} 
			
			writeToLog($client);    // Write to log file   

		} catch (SoapFault $exception) {
		   printFault($exception, $client);        
		}

		

	} 

	function estimateCurlShipping($country='', $zipcode='', $state='', $city='' )

	{
		$this->load->model('cartmodel'); 
		$this->load->model('jewelrymodel');
		

		$allitems = $this->cartmodel->getitemsbysessionid();
		//print_r($allitems);
		if($country == '') {
			$destination = '<StateOrProvinceCode>'.$state.'</StateOrProvinceCode>
							<PostalCode>'.$zipcode.'</PostalCode>
							<CountryCode>'.$country.'</CountryCode>';
 
		} else {
			$destination = '<PostalCode>'.$zipcode.'</PostalCode>
			<CountryCode>'.$country.'</CountryCode>';
		}
		
		$request = '<?xml version="1.0" encoding="UTF-8" ?>
		  <FDXRateAvailableServicesRequest xmlns:api="http://www.fedex.com/fsmapi"
		  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		  xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">

	  <RequestHeader>
			<CustomerTransactionIdentifier>1</CustomerTransactionIdentifier>
			<AccountNumber>510087160</AccountNumber>
			<MeterNumber>118507130</MeterNumber>
			<CarrierCode>FDXE</CarrierCode>
		  </RequestHeader>
		  <ShipDate>2010-03-27</ShipDate>
		<DropoffType>REGULARPICKUP</DropoffType>
	    <Service>INTERNATIONALECONOMY</Service>
	    <Packaging>YOURPACKAGING</Packaging>';
	 foreach($allitems AS $index=>$value) {
		 $request.=  '<WeightUnits>LBS</WeightUnits>';
		 if($value['addoption']=='addwatch') {
			$weight = '3.0';
			$length = 11;
			$width = 9;
			$height = 6;
		 } else {
			$weight = '1.0';
			$length = 14;
			$width = 12;
			$height = 6;
		 }
		//$total
		$request.= '<Weight>'.$weight.'</Weight>';	
		$request .= '<Dimensions>
						<Length>'.$length.'</Length>
						<Width>'.$width.'</Width>
						<Height>'.$height.'</Height>
						<Units>IN</Units>
					  </Dimensions>';

	 }

	 // <WeightUnits>LBS</WeightUnits>
	 // <Weight>10.0</Weight>
	  $request .= '<OriginAddress>
		<StateOrProvinceCode>TN</StateOrProvinceCode>
		<PostalCode>37115</PostalCode>
		<CountryCode>US</CountryCode>
		</OriginAddress>
		<DestinationAddress>'.$destination.'</DestinationAddress>
		<Payment>
		<PayorType>SENDER</PayorType>
		</Payment>
		<PackageCount>1</PackageCount>
</FDXRateAvailableServicesRequest>';



		echo "<h3>Request</h3>\n";
		echo "<pre>\n";
		print_r(simplexml_load_string($request));
		echo "</pre>\n";
		echo "<h3>Response</h3>\n";
		$response = $this->callFedEx($request);
		foreach ($response->Entry AS $service)
		{
		  echo "It would cost \${$service->EstimatedCharges->DiscountedCharges->NetCharge}
			to mail the package with " . $serviceOptions["{$service->Service}"] . ' ';
		  echo "Which has an estimated delivery date of " . date('l dS \of F',
			strtotime($service->DeliveryDate)) . "<br>";
		}
		echo "<pre>";
		print_r($response);
		echo "</pre>";

		

	} 

	function callFedEx($request)
	{
		  $endpoint = "https://gatewaybeta.fedex.com:443/GatewayDC";
		  $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
		  $reffer = "https://gatewaybeta.fedex.com";

		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, $endpoint);
		  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		  curl_setopt($ch, CURLOPT_POST, 1);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		  curl_setopt($ch, CURLOPT_REFERER, $reffer);
		  
		  $response = curl_exec($ch);
		  if (curl_error($ch))
		  {
			echo "<br>\n";
			echo "Errors were encountered:";
			echo curl_errno($ch);
			echo curl_error($ch);
			curl_close($ch);
			return NULL;
		  }else
		  {
			curl_close($ch);
			$xml = simplexml_load_string($response);
			return $xml;
		  }
	}



}

	

?>