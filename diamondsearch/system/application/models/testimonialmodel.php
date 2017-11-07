<?php
 class Testimonialmodel extends Model {
 	function __construct()
 	{
 		parent::Model();
 	}
 	
 	function getfeedback($start=0){
		$sql = "SELECT * FROM ".$this->config->item('table_perfix')."feedbacks WHERE status='accepted'  limit ".$start." ,10";
		$result = $this->db->query($sql);
		$results['result'] = $result->result_array();
		$qry2 = "SELECT * FROM ".$this->config->item('table_perfix')."feedbacks WHERE status='accepted'";
		$result2 = 	$this->db->query($qry2);
		$results['count'] = $result2->num_rows();
		
		return 	$results;
		
 	}
 	
 	function updatefeedback(){
// 		$name=$_POST['name'];
 		$sql = "INSERT INTO ".$this->config->item('table_perfix')."feedbacks (name,email,httpaddress,description,status,adddate) VALUES ('".$_POST['name']."','".$_POST['email']."','".$_POST['web']."','".addslashes($_POST['comments'])."','*new','".date('Y-m-d')."')";
		$result = $this->db->query($sql);
 	}
 	
 	
 	
 	
 } ?>