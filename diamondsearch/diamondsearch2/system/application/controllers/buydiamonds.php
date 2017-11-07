<?php class Buydiamonds extends Controller {
	function __construct(){
		parent::Controller();
	}
	
	
	function index()
	{
		$data = $this->getCommonData(); 
		$data['title'] = 'Buy Diamonds';
	    $output = $this->load->view('buydiamond/index' , $data , true);
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
	
}?>