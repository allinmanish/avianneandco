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

		$data['countryx'] = $this->input->post('country');

		$data['postcode'] = $this->input->post('postcode'); 

		$data['phonecode'] = $this->input->post('phonecode'); 

		$data['phone'] = $this->input->post('phone'); 

		$data['extension'] = $this->input->post('extension'); 

		

		$data['rcvname'] = $this->input->post('rcvname');

		$data['rcvaddress1'] = $this->input->post('rcvaddress1'); 

		$data['rcvaddress2'] = $this->input->post('rcvaddress2'); 

		$data['rcvcity'] = $this->input->post('rcvcity');

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

					$this->form_validation->set_rules('city', 'City', 'required');				

					$this->form_validation->set_rules('country', 'Country', 'required');

					$this->form_validation->set_rules('postcode', 'Post code', 'required');				

					$this->form_validation->set_rules('phone', 'Phone number', 'required');

					

					if($_POST['shipaddress'] == 'no'){

							$this->form_validation->set_rules('rcvname', 'Receiver name', 'required');				

							$this->form_validation->set_rules('rcvaddress1', 'Receiver address', 'required');

							$this->form_validation->set_rules('rcvcity', 'Receiver city', 'required');				

							$this->form_validation->set_rules('rcvcountry', 'Receiver country', 'required');

							$this->form_validation->set_rules('rcvphone', 'Reciever phone', 'required');

					} 

					

					$this->form_validation->set_rules('intshipping', 'Shipping policy', 'required');

					 

					$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			

					

					if ($this->form_validation->run() == FALSE){			

							 $output = $this->load->view('shoppingbasket/billingandshipping' , $data , true); 	

							 $this->output($output , $data);

					}

					else{

							 $orderid 	 = $this->session->userdata('svar_orderid');

							 $customerid = $this->session->userdata('svar_customerid') ;

							 

							 $order = $this->session->userdata('svar_order');

							 $paymentmethod = $order['paymentmethod'];

							 

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

							 $rcvcountry 		= $_POST['rcvcountry'];

							 $rcvpostcode 		= $_POST['rcvpostcode'];

							 $rcvphonecodecountry= $_POST['rcvphonecode']; 

							 $rcvphone 			= $_POST['rcvphone'];

							 $rcvext 			= $_POST['rcvextension'];

							 

							 $referencecode 	= $_POST['referencecode'];

							 $giftcertificate 	= $_POST['giftcertificate'];							 

							 $howdidyouknow 	= $_POST['howdidyouknow'];		

							// $email=	$_POST['email'];					 

							 

							

							  $oinfo['creditcardname'] = $creditcardname;

							  $oinfo['creditcardno']=$creditcardno;

							  $oinfo['expdate']=$expdate;

							 // $oinfo['email']=$email;

								 $this->session->set_userdata('ccinfo', $_POST) ;

							 

							 $this->shoppingbasketmodel->saveOrderdetails($orderid);

							 $this->shoppingbasketmodel->saveShippinginfo($orderid, $customerid, $paymentmethod, $creditcardname, $creditcardno, $cvvcode, $expdate, $rcvname, $rcvphone, $rcvphonecodecountry, $rcvext, $rcvcompany, $rcvaddress, $rcvcity, $rcvcountry, $rcvpostcode, $referencecode, $giftcertificate, $howdidyouknow, $fname, $lname, $company, $adress, $city,  $country, $postcode, $phonecodecountry, $phone,  $ext, $issamedestination);

							

							 

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

	

}

	

?>