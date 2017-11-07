<?php

class Cart extends Controller {

	function Cart()
	{
		parent::Controller();
		$this->load->model('cartmodel');

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

	function addtocart($addoption = '', $lot= '', $sidestone1 = '', $sidestone2 = '', $stockno = '',$price = '',$dsize = ''){



		$html = '';
		$msg = '';

		$success = false;
		switch ($addoption){
			case 'addloosediamond':
				$success = $this->cartmodel->addloosediamond($lot,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'diamonds/search" style="font-weight:bold;color:#000" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			case 'addtoring':
					//	 echo($dsize);
		// exit(0);
				$success = $this->cartmodel->addring($lot,$stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			case 'tothreestone':
				$success = $this->cartmodel->add3stonering($lot,$sidestone1,$sidestone2,$stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			case 'toearring':
				$success = $this->cartmodel->addearring($lot,$stockno,$addoption,$price,$sidestone1,$sidestone2,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			case 'addearringstud':
				$success = $this->cartmodel->adddiamondstudearring($stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;

			case 'addwatch':
				$success = $this->cartmodel->addwatch($stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;

			case 'addpendantsetings':
				$success = $this->cartmodel->adddiamondpendant($lot,$stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			case 'addpendantsetings3stone':
				$success = $this->cartmodel->add3stonediamondpendant($lot,$sidestone1,$sidestone2,$stockno,$addoption,$price,$dsize);

				if($success == 'success') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Item has been added</td></tr>  </table>';
				if($success == 'exist') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/worning.gif"></td><td>You heve alredy added this product!</td></tr>  </table>';
				if($success == 'fail') $msg = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>There was an error while saving this item.</td></tr>  </table>';
				$html .= '<center>
							 	'.$msg.'
								<div class="dbr"></div>
								<a href="'.config_item('base_url').'engagement" style="font-weight:bold;" class="underline">Continue Shopping</a> <span style="color:#81ae33; font-weight:bold;">|</span>
								<a href="'.config_item('base_url').'shoppingbasket/mybasket" style="font-weight:bold;" class="underline">Check Out</a>

						  </center>
						';
				echo $html;
				break;
			default:
				break;
		}

	}

	function updatecart($id = '', $price = '', $qty = '',$dsize=''){
		$success = '';
		$newprice = '';
		$newprice = $price*$qty;

		$success = $this->cartmodel->updatecartbyid($id,$newprice,$qty,$dsize);
		echo ($success) ? 'Updated Sucessfully' : 'Failed to update!';
	}

	function deletecart($id){
		$success = $this->cartmodel->deletcartitembyid($id);
		echo ($success) ? 'Item Deleted' : 'Failed to Delete!';
	}


}
?>