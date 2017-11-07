<?php class Account extends Controller {

	function __construct(){

		parent::Controller();

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

	

	function index()

	{

		$data = $this->getCommonData(); 

		$data['title'] = 'Your Account';

	    $output = $this->load->view('account/index' , $data , true);

		$this->output($output , $data);		

	}

	

	

	function signin($msg = ''){

	if($this->session->isLoggedin()){ $this->load->helper('url'); redirect('/account/myaccount' , 'refresh');}

	$data = $this->getCommonData(); 

	$data['title'] = 'Login';

	$this->load->helper(array('form', 'url'));

	$this->load->library('form_validation');

	if(($msg != ''))$data['loginerror'] =  $msg ;

	$this->form_validation->set_rules('username', 'User ID', 'trim|required');

	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[35]');

	$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

	

	$data['username'] = $this->input->post('username');

	$data['password'] = $this->input->post('password');
	$data['title'] = "Emerald Cut Diamonds|Princess Cut Diamonds|Trillion Cut Diamonds|Asscher Cut Diamonds";
	$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Emerald Cut Diamonds|Princess Cut Diamonds|Trillion Cut Diamonds|Asscher Cut Diamonds">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online Emerald Cut Diamonds, Princess Cut Diamonds, Trillion Cut Diamonds, Asscher Cut Diamonds, wholesale diamonds rings, tension set diamond ring, wholesale diamonds rings, asscher cut diamonds, emerald cut diamonds, princess cut diamonds, trillion cut diamonds, diamond wedding rings">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Buy online Emerald Cut Diamonds, Princess Cut Diamonds, Trillion Cut Diamonds, Asscher Cut Diamonds, wholesale diamonds rings, tension set diamond ring, wholesale diamonds rings, asscher cut diamonds, emerald cut diamonds, princess cut diamonds, trillion cut diamonds, diamond wedding rings">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

	if ($this->form_validation->run() == FALSE){

			    $output = $this->load->view('account/login' , $data , true); 	

				$this->output($output , $data , false , false);

	}else {

			$loginreturn 	= $this->user->login($data['username'] , $data['password']);

  			

  			 if($loginreturn['error'] !='')

  			 {

  			 	$data['loginerror']  = $loginreturn['error'];

  			 	

  			 	$output = $this->load->view('account/login' , $data , true); 	

				$this->output($output , $data , false , false);

  			 }else{

  			 	$user = $this->session->userdata('user');

  			 	header('location:'.config_item('base_url').'account/myaccount/');

  			 }

	}

	}

		

	function signout(){

		$this->user->logout();

		header('location:'.config_item('base_url').'account/signin/signout completed'); 

	}

	

	function forgotpassword(){

		echo 'Sorry You forgot password ? we forgot constracting this page ....please keep waiting till we develop this forgot password page. : ! <a href="'.config_item('base_url').'"> Go Back to site</a>';

	}

	

	function myaccount(){

		if($this->session->isLoggedin()){

			$data = $this->getCommonData(); 

			$data['title'] = 'Your Account';

		    $output = $this->load->view('account/myaccount' , $data , true);

			$this->output($output , $data , true ,false);	

		}else{header('location:'.config_item('base_url').'account/signin');}	

	}

	

	

}?>