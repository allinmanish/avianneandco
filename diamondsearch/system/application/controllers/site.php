<?php 



class Site extends Controller {

	function Site(){

		parent::Controller();

		

	}

	

	function index()

	{

		$data = $this->getCommonData(); 
		$data['title'] = "Buy Diamond Ring|Earrings|Pendant|Three Stone Ring|Online Jewellary Store|Jewellary Ring Online";
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Buy Diamond Ring|Earrings|Pendant|Three Stone Ring|Online Jewellary Store|Jewellary Ring Online">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Online Jewellary Store offers to Buy Discounted Rate Engagement Diamond Ring, Earings, Three Stone Ring, Diamond Pendant, Loose Diamonds, Premium Diamond. Build your own Ring, Earrings, Three Stone Ring, Diamond Pendant Online. Purchase Engagement Ring at Discounted Price at Intercarts.">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Online Jewellary Store, Engagement Diamond Ring, Earings, Three Stone Ring, Diamond Pendant, Loose Diamonds, Premium Diamond. Build your own Ring, Diamond Pendant Online, Purchase Engagement Ring">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

	    $output = $this->load->view('diamond/index' , $data , true);

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

		//if($isright)$output .= $this->load->view($this->config->item('template').'right' , $data , true);

		$output .= $this->load->view($this->config->item('template').'footer', $data , true);

		$this->output->set_output($output);

	}

	

	

	function page($topic = 'aboutus')

	{		 

  		$data = $this->getCommonData($topic); 

  		$data['title'] =  ucfirst($topic);	    

		$data['content']  	= $this->commonmodel->getcompanyinfo($topic);		
		
		if($topic == 'aboutus') {
			$data['title'] = "3 Stone Diamond Ring|Antique Diamond Ring|Set Engagement Ring|Pave Diamond Rings";
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="3 Stone Diamond Ring|Antique Diamond Ring|Set Engagement Ring|Pave Diamond Rings">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online emerald cut diamond rings, three stone diamond rings, 3 stone diamond ring, antique diamond ring, set engagement ring, tension engagement rings, affordable engagement ring, 3 stone diamond ring, antique diamond ring, set engagement ring, pave diamond rings">
	<meta name="keywords" content="emerald cut diamond rings, three stone diamond rings, 3 stone diamond ring, antique diamond ring, set engagement ring, tension engagement rings, affordable engagement ring, 3 stone diamond ring, antique diamond ring, set engagement ring, pave diamond rings">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
		}
		
		$output = $this->load->view($this->config->item('template').'printcontent' , $data , true); 		

		$this->output($output , $data);		

	}

	

	function setsession($sessionvar = 'temp', $sessionvalue = ''){
		$sessionvalue = str_ireplace('_' , '.' , $sessionvalue);
		$this->session->set_userdata($sessionvar,$sessionvalue);
	}

	

	function ringdetails($stockno = '', $ringoption='',$lot = ''){

		$data = $this->getCommonData();

		$lot 		= ($lot == 'undefined') ? 0 : $lot ;

	    $this->load->model('diamondmodel');

		$this->load->model('engagementmodel');

		$this->load->model('jewelrymodel');

		$data['products'] = $this->diamondmodel->getDetailsByLot($lot);

		$data['details'] = $this->jewelrymodel->getAllByStock($stockno);				

		$data['stockno'] = $stockno;

		$data['ringoption'] = $ringoption;

		$data['lot'] = $lot;

		

		$data['flashfiles']	= $this->engagementmodel->getFlashByStockId($stockno);			

						

		if($data['details']){

			$output = $this->load->view('erd/ringdetails' , $data , true);

			echo $output;

		}	

	 

   }

   

   function threestoneringdetails($stockno = '', $centerid = '',$sidestone1 = '',$sidestoen2 = ''){

   		$data = $this->getCommonData();

		$this->load->model('engagementmodel');

		$this->load->model('jewelrymodel');

			

		$data['details'] = $this->jewelrymodel->getAllByStock($stockno);

		$data['stockno'] = $stockno;

		//$data['ringoption'] = $ringoption;

		$data['centreid'] = $centerid;

		$data['sidestoneid1'] = $sidestone1;

		$data['sidestoneid2'] = $sidestoen2;

		

		$data['flashfiles']	= $this->engagementmodel->getFlashByStockId($stockno);			

						

		if($data['details']){

			$output = $this->load->view('erd/threestoneringdetails' , $data , true);

			echo $output;

		}	

   }

   

   function errormsg() {

   	$msg = ($this->commonmodel->errordb());

   	echo $msg[0]->msg;

   	

   }
}
?>