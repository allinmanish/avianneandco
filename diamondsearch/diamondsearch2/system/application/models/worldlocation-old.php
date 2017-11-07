<?php 

class Worldlocation extends Model {
	function __construct()
	{
		parent::Model();
	}
	
	function getCountry()
	{
		$sql 		= 	'SELECT iso,printable_name FROM '. $this->config->item('table_perfix').'country order by printable_name';
		$result 	= 	$this->db->query($sql);
		return $result->result_array();
	 	
	}
	function getCountryName($iso)
	{
		$sql 		= 	'SELECT printable_name FROM '. $this->config->item('table_perfix').'country where iso=\''. $iso .'\' order by printable_name';
		$result 	= 	$this->db->query($sql);
		return $result->result_array();
	}
	
	/*function getAllState()
	{
		$sql 		= 	'SELECT id,name FROM '. $this->config->item('table_perfix').'states where isenabled=\'1\' order by name';
		$result 	= 	$this->db->query($sql);
		return $result->result_array();
	 	
	}*/
	
	function getStateName($id)
	{
		$sql 		= 	'SELECT name FROM '. $this->config->item('table_perfix').'states where id=\''.$id.'\' order by name';
		$result 	= 	$this->db->query($sql);
		return $result->result_array();
	}
	
	
	function getAllState($isallfield = false , $start = 0, $orderby = 'name' , $perpage = 20)
	{
		$sql 		= 	(!$isallfield) ? 'SELECT id,name FROM '. $this->config->item('table_perfix').'states where isenabled=\'1\' order by '.$orderby : 'SELECT * FROM '. $this->config->item('table_perfix').'states order by '.$orderby  . ' limit '. $start . ', ' . $perpage;
		$result 	= 	$this->db->query($sql);
		return $result->result_array();
	 	
	}
}