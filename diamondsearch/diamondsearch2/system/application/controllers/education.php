<?php class Education extends Controller {
	function __construct(){
		parent::Controller();
	}
	
	
	function index()
	{
		$data = $this->getCommonData(); 
		$data['title'] = 'Education';
	    $output = $this->load->view('education/index' , $data , true);
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
	
	function diamond($shape = 'index'){
		
		$data = $this->getCommonData(); 
		$data['title'] = $shape;
		if($shape == 'index') {
		$data['title'] = 	 "Bridal Ring Sets|Art Deco Rings|Diamond Heart Rings|Right Hand Ring|Create A Ring";
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Bridal Ring Sets|Art Deco Rings|Diamond Heart Rings|Right Hand Ring|Create A Ring">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online diamond anniversary rings, diamond eternity rings, diamond solitaire ring
diamond eternity ring, right hand ring, diamond pearl ring, diamond heart rings, bridal ring sets, create a ring, art deco rings, bridal ring set online">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Buy online diamond anniversary rings, diamond eternity rings, diamond solitaire ring
diamond eternity ring, right hand ring, diamond pearl ring, diamond heart rings, bridal ring sets, create a ring, art deco rings, bridal ring set online">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
		} else if($shape == 'shape') {
			$data['title'] = "Baguette Diamond Ring|Emerald Diamond Ring|Filigree Engagement Ring|Yellow Diamond Rings";
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Baguette Diamond Ring|Emerald Diamond Ring|Filigree Engagement Ring|Yellow Diamond Rings">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Purchase baguette diamond ring, emerald diamond ring, filigree engagement ring yellow diamond rings, pink diamond ring, three stone diamond ring, yellow diamond ring, emerald cut diamond ring, princess cut diamond ring, wholesale diamond ring, wholesale diamond rings, asscher engagement rings, discount engagement rings, engagement ring sets">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="baguette diamond ring, emerald diamond ring, filigree engagement ring yellow diamond rings, pink diamond ring, three stone diamond ring, yellow diamond ring, emerald cut diamond ring, princess cut diamond ring, wholesale diamond ring, wholesale diamond rings, asscher engagement rings, discount engagement rings, engagement ring sets">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
			
		}
		$output = $this->load->view('education/diamond/'.$shape , $data , true);
		$this->output($output , $data);
		
	}
	
	function jewelry($shape = 'index'){
		
		$data = $this->getCommonData(); 
		$data['title'] = $shape;
		
		$data['title'] = 	 "Bridal Ring Sets|Art Deco Rings|Diamond Heart Rings|Right Hand Ring|Create A Ring";
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Bridal Ring Sets|Art Deco Rings|Diamond Heart Rings|Right Hand Ring|Create A Ring">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online diamond anniversary rings, diamond eternity rings, diamond solitaire ring
diamond eternity ring, right hand ring, diamond pearl ring, diamond heart rings, bridal ring sets, create a ring, art deco rings, bridal ring set online">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Buy online diamond anniversary rings, diamond eternity rings, diamond solitaire ring
diamond eternity ring, right hand ring, diamond pearl ring, diamond heart rings, bridal ring sets, create a ring, art deco rings, bridal ring set online">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
					
	
		$output = $this->load->view('education/jewelry/'.$shape , $data , true);
		$this->output($output , $data);
		
	}
	
	function platinum(){
		
		$data = $this->getCommonData(); 
		$data['title'] = 'Platinum';
		$output = $this->load->view('education/platinum/index'  , $data , true);
		$this->output($output , $data);
		
	}
	
	function gold(){
		
		$data = $this->getCommonData(); 
		$data['title'] = 'Gold';
		$output = $this->load->view('education/gold/index' , $data , true);
		$this->output($output , $data);
		
	}
	
	
	
	
}?>