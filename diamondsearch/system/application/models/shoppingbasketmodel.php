<?php
require_once("adminmodel.php");
class Shoppingbasketmodel extends Model {
 	function __construct(){
 		parent::Model();
		 //$this->CI =& get_instance();
 	}

 	function saveCustomerinfo($fname = '',$lname = '',$email = '',$phone = '',$address = ''){
 		$qry = "SELECT id 
				FROM ".$this->config->item('table_perfix')."customerinfo
				WHERE email = '".$email."'"; 
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		$cudtomer =  isset($result[0]) ? $result[0] : false;

		if($cudtomer == false){
			$isinsert = $this->db->insert($this->config->item('table_perfix').'customerinfo',
									array(
										'fname'		=> $fname,
										'lname'		=> $lname,
										'email'		=> $email,
										'phone'		=> $phone,
										'address'	=> $address
									)); 

			$cudtomer['id'] = $this->db->insert_id();
  	}

		return $cudtomer;
 	}

 	function saveOrder($customerid = '', $totalprice = '',$paymentmethod= '', $orderdate = '',$deliverydate = ''){

 		$qry = "SELECT id 

				FROM ".$this->config->item('table_perfix')."order

				WHERE customerid = '".$customerid."' AND session = '".$this->session->session_data['session_id']."'"; 

		$return = 	$this->db->query($qry);

		$result = $return->result_array();	

		$orderexist =  isset($result[0]) ? $result[0] : false;

		

		if($orderexist == false){

			$isinsert = $this->db->insert($this->config->item('table_perfix').'order',

										array(

											'customerid'		=> $customerid,

											'totalprice'		=> $totalprice,

											'paymentmethod'		=> $paymentmethod,

											'orderdate'			=> $orderdate,

											'deliverydate'		=> $deliverydate,

											'session'			=> $this->session->session_data['session_id']

										));

			$orderexist['id'] = $this->db->insert_id();					

		}

		else{			

			$where = "customerid = ".$customerid." AND session = '".$this->session->session_data['session_id']."'";

			$value = array('totalprice' => $totalprice);

			$isinsert = $this->db->update($this->config->item('table_perfix').'order',$value,$where);

			

		}

		

		if($isinsert){ return $orderexist;} 

		else 'No order found';

 	}

	

	function CustomerByID($customerid)

	{ 

	   $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'customerinfo where 1=1 and id='.$customerid;

		//var_dump($sql); 

	 	$result = $this->db->query($sql);

			

		return $result->result_array();

	}

 	

 	function saveShippinginfo($orderid = '',$customerid = '',$paymentmethod = '',$creditcardname = '',$creditcardno = '',$cvvcode = '',$cardexpirydate = '',$toname = '',$tophone = '',$tophonecodecountry = '',$toext = '',$tocompany ='',$toaddress = '',$tocity = '', $tostate = '', $tocountry = '',$topostcode = '',$referenceno = '',$giftcertificate = '',$howdidyouknow = '', $fname = '', $lname = '', $company = '', $adress = '', $city = '',  $state ='', $country = '', $postcode = '', $phonecodecountry = '', $phone = '',  $ext = '', $shippingmethod, $shippingamount,$issamedestination = 0,$isdeliverd = 0){
		
		$qry2 = "SELECT * FROM ".$this->config->item('table_perfix')."shippinginfo
	 				WHERE orderid = '".$orderid."'";

		 		$result2 = $this->db->query($qry2);

				$orderexist = $result2->result_array();

				

				if($orderexist > 0){

						$sql = "DELETE FROM ".$this->config->item('table_perfix')."shippinginfo WHERE orderid = '".$orderid."'";

						$result = $this->db->query($sql);

				}
 		

 		$isinsert = $this->db->insert($this->config->item('table_perfix').'shippinginfo',

 										array(

 											'orderid' 			=> $orderid,

 											'customerid'		=> $customerid,

 											 'paymentmethod'	=> $paymentmethod,

 											 'creditcardname'	=> $creditcardname,

 											 'creditcardno'		=> $creditcardno,

 											 'cvvcode'			=> $cvvcode,

 											 'cardexpirydate'	=> $cardexpirydate,

 											 'shippingmethod'	=> $shippingmethod,

											 'shipping_amount'	=> $shippingamount,

 											 'issamedestination'=> $issamedestination,

 											 'toname'			=> $toname,

 											 'tophone'			=> $tophone,

											 'tocountryphncode'	=> $tophonecodecountry,

 											 'tophnext'			=> $toext,

 											 'tocompany'		=> $tocompany, 											 

 											 'toaddress'		=> $toaddress,

 											 'tocity'			=> $tocity, 

											 'tostate'			=> $tostate, 

 											 'tocountry'		=> $tocountry, 

 											 'topostcode'		=> $topostcode, 

 											 'isdelivered'		=> $isdeliverd,

 											 'referencecode'	=> $referenceno,

 											 'giftcertificateno'=> $giftcertificate,

 											 'howdidyouknow'	=> $howdidyouknow,

 											 

 											 'billfname' 		=> $fname,

 											 'billlname'		=> $lname,

 											 'billcompany'		=> $company,

 											 'billaddress'		=> $adress,

 											 'billcity'			=> $city,

     										 'billstate'		=> $state,

 											 'billcountry'		=> $country,

 											 'billpostcode'		=> $postcode,

 											 'billphone'		=> $phone,

 											 'billcountryphncode'=> $phonecodecountry,

 											 'billphnext'		=>  $ext

 										));

 	}

 	

 	function saveOrderdetails($orderid = '', $isconfirmed = 0){

 		

 				

 				$qry2 = "SELECT * FROM ".$this->config->item('table_perfix')."orderdetails

		 				WHERE orderid = '".$orderid."'

		 				";

		 		$result2 = $this->db->query($qry2);

				$orderexist = $result2->result_array();

				

				if($orderexist > 0){

						$sql = "DELETE FROM ".$this->config->item('table_perfix')."orderdetails WHERE orderid = '".$orderid."'";

						$result = $this->db->query($sql);

				}

				

 			

				$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart

		 				WHERE sessionid = '".$this->session->session_data['session_id']."'

		 				";

		 		$result = $this->db->query($qry);

				$carts = $result->result_array();

				

				if(sizeof($carts)>0){

					foreach ($carts as $cart){

						$this->db->insert($this->config->item('table_perfix').'orderdetails',

															array(

															'orderid' 		=> $orderid,

															'lot'			=> $cart['lot'],

															'sidestone1'	=> $cart['sidestone1'],

															'sidestone2'	=> $cart['sidestone2'],

															'ringsetting'	=> $cart['ringsetting'],

															'earringsetting'=> $cart['earringsetting'],

															'pendantsetting'=> $cart['pendantsetting'],

															'studearring'	=> $cart['studearring'],
															
															'watchid'	=> $cart['watchid'],

															'price'			=> $cart['price'],

															'orderdate'		=> date("Y-m-d"),

															'quantity'		=> $cart['quantity'],

															'totalprice'	=> $cart['totalprice'],

															'addoption'		=> $cart['addoption'],

															'dsize'		=> $cart['dsize'],

															'isconfirmed'	=> $isconfirmed

															

															));

					}

				} 

 		 

		

		

 	}

 	

 	function confirmOrder($orderid, $value)

	{

 		

 		$where = "orderid = '".$orderid."'";

		$tablevalue = array('isconfirmed'=>$value);

 		//$this->db->where('orderid',$orderid);

		$t = $this->db->update($this->config->item('table_perfix').'orderdetails',$tablevalue,$where);		

		if($t){

		        $where2 = "id = '".$orderid."'";

		        $tablevalue2 = array('isconfirmed' => $value);

		        $this->db->update($this->config->item('table_perfix').'order', $tablevalue2, $where2);

				$this->sendConfirmEmail($orderid);

				return true;

			}else {

				return false;

			}

			

		

		

		//$this->OrderInfoByID($orderid);

 	}

	



		

	function sendConfirmEmail($orderid = '') {
		if($orderid!='') {
		   $this->CI =& get_instance();
		   $adminmodel = new Adminmodel();
           //$data['order'] = $this->CI->adminmodel->orderinfo($orderid); 
  		   //$data['orderdetails'] = $this->CI->adminmodel->orderdetails($orderid);
		   $data['order'] = $adminmodel->orderinfo($orderid); 
  		   $data['orderdetails'] = $adminmodel->orderdetails($orderid);
		   $customerid = (isset($data['order']['0']['customerid'])) ? $data['order']['0']['customerid'] : '';
		   $lot = (isset($data['order']['0']['lot'])) ? $data['order']['0']['lot'] : '';
		   if($customerid!='') {

		     //$data['customer'] = $this->CI->adminmodel->getCustomerByID($customerid);
			 $data['customer'] = $adminmodel->getCustomerByID($customerid);

			 $customeremail = $data['customer'][0]['email'];

			}

		   else

		     $data['customer']=array();

			

		  if($lot!='')

		    $data['product'] = $adminmodel->getProductByLot($lot);

		  else

		     $data['product']=array();	

			 

		  $data['shipping'] = $adminmodel->getShippingByID($orderid);

		   

		   

		    $output = $this->load->view('shoppingbasket/emailorder' , $data , true);

		

			$message='<meta http-equiv="Content-Type" content="text/html; charset="utf-8">
				 <style>
.w950px{width: 950px;}
.w600px{width: 600px;}
.w700px{width: 700px; }
.w400px{width: 400px; }
.w195px{width: 195px;}
.pad10{padding: 10px 10px 0px 10px;}
.pad5{padding:5px;}
.pad05{padding:0px 5px 5px 5px;}
.m10,.footer img{ margin: 10px 10px 0px 10px; }
.mcontent{ min-height: 750px; }
.floatr{float:right;}
.floatl{float: left;}
.clear{clear: both;}
* {
	font-family:Verdana, Arial, Helvetica, sans-serif;

	color:#555555;

	font-size:12px;

}
.bodytop{background: url(http://www.intercarats.com/images/bodytop.jpg) no-repeat;width: 720px; height: 12px;}
.bodymid{background: url(http://www.intercarats.com/images/bodymid.jpg) repeat-y; width: 710px; padding: 0px 5px;}
.bodymid a{color:#006600;}
.bodybottom{background:url(http://www.intercarats.com/images/bodybottom.jpg) no-repeat;width: 720px; height: 12px;}
.dbr{height: 20px;}
.txtcenter{text-align: center;} 
.txtleft{text-align: left;} 
.txtright{text-align: right;} 
.w20px{width:20px;}
.w25px{width:25px;}
.w35px{width:35px;}
.w50px{width:50px;}
.w60px{width:60px;margin-left:5px;}
.w80px{width:80px;}
.w85px{width:85px;}
.w100px{width:100px;}
.w125px{width:125px;}
.w150px{width:150px;}
.w200px{width:200px;}
.w350px{width:350px;}
.inboxcolumn{width:240px;}
.column{width:255px;}
.center{margin: 0px auto; padding: 0px; }
.m2{margin: 2px 2px 0px;}
.m5{margin: 5px 5px 0px;}
.m7{margin:7px 7px 0px;}
.m10{margin:10px 10px 0px 10px;}
.ml4{margin-left:4px;}
.commonheader{background-color:#81ae33; font-weight:bold; height:20px; padding-left:5px; padding-top:6px;margin-top:5px;}
</style>'.$output;

$message = str_replace('<form method="POST" action="#">','',$message);
$message = str_replace('</form>','',$message);
			

			$this->load->library('email');

			$config['protocol'] = 'mail';

			$config['wordwrap'] = false;

			$config['mailtype'] = 'html';

			$config['charset'] = 'utf-8';

			$config['crlf'] = "\r\n";

			$config['newline'] = "\r\n";

			

			$this->email->initialize($config);



			$this->email->from('info@7techniques.com', 'Directloose Diamonds');

			$this->email->to('AlanGJewelers@aol.com');

			$this->email->cc($customeremail);

			//$this->email->bcc('intercar@gmail.com');

			

			//$this->email->bcc('shashkamder@gmail.com');

			$this->email->subject('New Order Information');
			
			//$message = htmlentities($message);
			
			$this->email->message($message);

			

			$this->email->send();

			

			// $this->email->print_debugger();

		}

	}  

	

	function output($layout='')

	{

		$data['loginlink'] = $this->user->loginhtml();

		$output = $this->load->view($this->config->item('template').'header' , $data , true);

		$output .= $layout;

		$output .= $this->load->view($this->config->item('template').'footer', $data , true);

		return $output;

	}	

	
	/*function country()

	{

		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."country "; 

		$return = 	$this->db->query($qry);

		$result = $return->result_array();	

		$countries =  isset($result[0]) ? $result[0] : false;
		
		return $countries;

	}*/
	

 function getShippingInfo($orderid, $customerid) {
	
		
		/*$qry = "SELECT * 

				FROM ".$this->config->item('table_perfix')."shippinginfo

				WHERE orderid = '".$order[0]['id']."' AND customerid = '".$order[0]['customerid']."' order by id GROUP BY orderid, customerid"; */
		
		$qry = "SELECT * 
				FROM ".$this->config->item('table_perfix')."shippinginfo
				WHERE orderid = '".$orderid."' AND customerid = '".$customerid."' order by id desc LIMIT 0, 1"; 

		$result = $this->db->query($qry);
		$shippinginfo = $result->result_array();
		return isset($shippinginfo[0]) ? $shippinginfo[0] : false;

	
	
 }

 }
 ?>