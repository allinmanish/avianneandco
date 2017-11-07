<?php class Engagementmodel extends  Model {
	function __construct(){
		parent::Model();
	} 
	
	function getRings($start = 0 , $rp =20, $isPave = false , $Solitaire = false , $Sidestone = false ,$platinum = true, $gold = true,$whitegold = true,$isAnniversary=true , $isWeddingband=true ,$minprice = 0 , $maxprice = 1000000 , $shape = 'all', $isMarkt = false, $isErd = false, $isVatche = false, $isDaussi = false, $isAntique = false,$isThreestone = false, $isHalo = false, $isMatching = false ){
		    $results = array();
		     
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			 
			  $qwhere .= " AND price >= ". str_replace('_','.',$minprice);
			  $qwhere .= " AND price <= ". str_replace('_','.',$maxprice);

			  
			  $instr = '';
			  $instr .= ($isMarkt == 'marktcollection') 	? "'International Collection',"  	: '';
			  $instr .= ($isErd == 'erdcollection') 		? "'erd_collection'," 		: '';
			  $instr .= ($isVatche == 'vatchecollection') 	? "'vatche_collection',"  	: '';
			  $instr .= ($isDaussi == 'daussicollection') 	? "'daussi_collection',"  	: '';
			  $instr .= ($isAntique == 'antiquecollection') ? "'antiqu_ecollection',"  	: '';
			  $design = 0;
			  if($instr != ''){
			  		$instr = substr($instr , 0 , (strlen($instr) -1));
				  	$qwhere .=  " AND collection in (".$instr.")";
				  	$design = 1;				  	
			  } 
			  
			  $instr = '';
			  $instr .= ($isPave == 'pave') ? "'Pave',"  : '';
			  $instr .= ($Solitaire == 'solitaire') ? "'Solitaire',"  : '';
			  $instr .= ($Sidestone == 'sidestones') ?  "'Sidestones'," : ''; 
			  
			  $instr .= ($isThreestone == 'threestone') ?  "'Three Stones'," : ''; 
			  $instr .= ($isHalo == 'halo') ?  "'Halo'," : ''; 
			  $instr .= ($isMatching == 'matching') ?  "'MatchingSet'," : ''; 
			  $instr .= ($isAnniversary == 'anniversary') ?  "'Anniversary Ring'," : ''; 
			  $instr .= ($isWeddingband == 'weddingband') ?  "'Wedding Bands'," : ''; 
			  
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND style in (".$instr.")"  ;
				  $design = 1;
			  }
			   
			 
			  $instr = '';
			  $instr .= ($platinum == 'platinum') ? "'Platinum','platinum','.950 platinum','950 Platinum',"  : '';
			  $instr .= ($gold == 'gold') ? "'Yellow Gold','18 kt. Yellow Gold','18 Yellow Gold',"  : '';
			  $instr .= ($whitegold == 'whitegold') ?  "'white gold','18 kt. White Gold'," : '';
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND metal in (".$instr.")"  ;
				  $design = 1;
			  } 
			  
			  if($shape != 'all' && $shape != 'undefined'){
			   $qwhere .=  " AND shape ='".$shape."'"  ;
			   
			  }
			  
			  if($design==0)
			  {
			      $qwhere .=  " AND collection in ('')";
				  $qwhere .=  " AND style in ('')"  ;
				  $qwhere .=  " AND metal in ('')"  ;
				  
			  }
			  			    
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'jewelries where 1=1 '. $qwhere . ' order by price desc '. $limit;
	//var_dump($sql); 
	//	exit(0);
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT  stock_number FROM  '. $this->config->item('table_perfix').'jewelries  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
		//var_dump($results); 
		//exit(0);
    	return $results;
		
	}
	
	function getRingByStockId($stockno = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."rings
				WHERE stock_number = '".$stockno."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getShapeByStockId($stockid){
		$qry = "SELECT DISTINCT(shape),id,image 
				FROM ".config_item('table_perfix')."ringimages 
				WHERE stock_number = '".$stockid."'GROUP BY shape
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
		
	function getFlashByStockId($stockid){
		$qry = "SELECT id,flash1, flash2 , flash3 ,image45 , image90, image180 ,image45_bg , image90_bg, image180_bg
				FROM ".config_item('table_perfix')."ringanimation 
				WHERE stock_num = '".$stockid."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getImageByStockAndShapeId($stockid, $shape, $id){
		$qry = "SELECT shape
				FROM ".config_item('table_perfix')."ringimages
				WHERE stock_number = '".$stockid." and shape=".$shape." and id=".$id."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getAllThreestoneRing(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."rings
				WHERE style = 'three stones'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
	 
}
?>