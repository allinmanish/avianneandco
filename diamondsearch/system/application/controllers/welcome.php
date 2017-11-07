<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->view('welcome_message');
		
		$this->db->where('id' , 1);
		$this->db->update('error', array('msg' => $sql . '------------'. $cutmin));
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */