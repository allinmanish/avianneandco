<?php 

class Searchresultmodel extends Model {

	function __construct(){
		parent::Model();
	}	
	
	function getAllSearchResultByKey($inputkey){
		 $qry = "SELECT * 
				FROM ".config_item('table_perfix')."search 
				WHERE keyfield like '%".$inputkey."%'
				";
		// var_dump($qry);
		$return = 	$this->db->query($qry);
		$result = $return->result_array(); 
		return isset($result) ? $result : false;
	}
}


?>