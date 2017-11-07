<?php
  class Rightads extends Model{
  	 function __construct(){
  	 	parent::Model();
  	 }
  	 function getrightads($controllers = 'diamonds' , $function = 'index'){
  	 	$des=$controllers.'/'.$function;
  	 	$sql = "SELECT description FROM  ". $this->config->item('table_perfix')."rightads where controller='$des'";
    	$query = $this->db->query($sql);
    	$result = $query->result();
	    if($query->num_rows()){
				echo $result[0]->description;
			}else {
				return '';
			}
  	 }
  	 
  	 
  	 function pageinfo($pagid, $pageposition){
	$sql = "SELECT description FROM ". $this->config->item('table_perfix')."pageinfo where pageid='$pagid' AND pageposition='$pageposition'";
	$query = $this->db->query($sql);
	$result = $query->result();
	if($query->num_rows()){
	echo $result[0]->description;
	}else {
	return '';
	}
	}

  	 
  	 
  }
  ?>