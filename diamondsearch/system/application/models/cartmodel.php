<?php 
 class Cartmodel extends Model {
 	
 	function __construct(){
 		parent::Model();
 	}
 	
 	function addloosediamond($lot,$addoption,$price,$dsize){
 		
 		echo 'Addloosediamond Function = '.$price;
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." AND sessionid = '".$this->session->session_data['session_id']."'
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();	
 		 		
 		if(sizeof($isexist) < 1){ 
 			
			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
	 										array(
	 												'sessionid' => $this->session->session_data['session_id'],
	 												'lot' 		=> $lot,
	 												'addoption' => $addoption,
	 												'price'		=> $price,
	 												'totalprice'=> $price,
													'dsize'=> $dsize
													
	 											)	
	 		);
	 		 
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		
 		return $return;
 	}
 	
 	function adddiamondpendant($lot,$settings,$addoption,$price,$dsize){
 		
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." 
 				AND sessionid = '".$this->session->session_data['session_id']."' 
 				AND pendantsetting = ".$settings."
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();	
 		
 		if(sizeof($isexist) < 1){ 
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'lot' 		=> $lot,
 												'pendantsetting' => $settings,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		
 		return $return;
 	}
 	
 	function add3stonediamondpendant($lot,$sidestone1,$sidestone2,$settings,$addoption,$price,$size){
 		
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." 
 				AND pendantsetting = ".$settings."
 				and sidestone1 = ".$sidestone1."
 				and sidestone2 = ".$sidestone2."
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
 		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'lot' 		=> $lot,
 												'sidestone1'=> $sidestone1,
 												'sidestone2'=> $sidestone2,
 												'pendantsetting' => $settings,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		return $return;
 	}
 	
 	function addring($lot,$stockno,$addoption,$price,$dsize){
 		
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." 
 				AND ringsetting = ".$stockno." 				
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
 		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'lot' 		=> $lot,
 												'ringsetting' => $stockno,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		return $return;
 	}
 	
 	function addearring($lot,$stockno,$addoption,$price,$sidestone1,$sidestone2,$dsize){
 		
 		 
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." 
 				and sidestone1 = ".$sidestone1."
 				and sidestone2 = ".$sidestone2."
 				and earringsetting = ".$stockno."
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
 		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'lot' 		=> $lot,
 												'earringsetting' => $stockno,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
 												'sidestone1'=> $sidestone1,
 												'sidestone2'=> $sidestone2,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		return $return;
 	}
 	
 	function adddiamondstudearring($stockno,$addoption,$price,$dsize){
 		
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart  
 				WHERE studearring = ".$stockno."
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
 		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'studearring' => $stockno,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		return $return;
	}
	
	function add3stonering($lot, $sidestoen1, $sidestone2, $stockno,$addoption,$price,$dsize){
		
		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart 
 				WHERE lot = ".$lot." 
 				AND ringsetting = ".$stockno."
 				and sidestone1 = ".$sidestoen1."
 				and sidestone2 = ".$sidestone2."
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'lot' 		=> $lot,
 												'sidestone1'=> $sidestoen1,
 												'sidestone2'=> $sidestone2,
 												'ringsetting'=> $stockno,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		return $return;
	}
 
 	function clearinvalidcart(){
 		//delete FROM `dev_cart` WHERE `sessionid` NOT IN (select session_id from dev_sessions)
 		//$this->db->delete
 	}
 	
 	function getitemsbysessionid(){
 		
 		$sessionid = $this->session->session_data['session_id'];
 		
 		$qry = "SELECT *
				FROM ".config_item('table_perfix')."cart
				WHERE sessionid = '".$sessionid."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
 	}
 	
 	function updatecartbyid($id,$price,$qty,$dsize){
 		$this->db->where('id',$id);
		$success = $this->db->update($this->config->item('table_perfix').'cart',
									array('totalprice'			=> $price,
										   'quantity'		=> $qty	,
										   'dsize'=> $dsize
									));
		if($success){
				return true;
			}else {
				return false;
			}
 	}
 	
 	function deletcartitembyid($id){
 		$qry = "DELETE FROM ".$this->config->item('table_perfix')."cart WHERE id = ".$id;
		$result = $this->db->query($qry);
		return $result;
 	}

	 function addwatch($stockno,$addoption,$price,$dsize){
 		
 		$qry = "SELECT * FROM ".$this->config->item('table_perfix')."cart  
 				WHERE watchid = ".$stockno."
 				AND sessionid = '".$this->session->session_data['session_id']."' 				
 				";
 		$result = 	$this->db->query($qry);
 		$isexist = $result->result_array();
 		
 		if(sizeof($isexist) < 1){
 			$isinsert = $this->db->insert($this->config->item('table_perfix').'cart',
 										array(
 												'sessionid' => $this->session->session_data['session_id'],
 												'watchid' => $stockno,
 												'addoption' => $addoption,
 												'price'		=> $price,
 												'totalprice'=> $price,
												'dsize'=> $dsize
 											)	
	 		);
	 		if($isinsert) $return =  'success';
	 		else $return = 'fail';
 		}
 		else $return = 'exist';
 		
 		
 		return $return;
	}
 	 
 }
 ?>