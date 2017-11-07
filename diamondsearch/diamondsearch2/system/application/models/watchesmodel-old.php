<?php class Watchesmodel extends  Model {
	function __construct(){
		parent::Model();
	} 
	
	function getWatch($start = 0 , $rp =20, $isPave = false , $Solitaire = false , $platinum = true, $gold = true, $goldss = true, $minprice = 0 , $maxprice = 1000000 ,  $isMarkt = false){
		    $results = array();
		     
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			 
			  $qwhere .= " AND price1 >= ". str_replace('_','.',$minprice);
			  $qwhere .= " AND price1 <= ". str_replace('_','.',$maxprice);

			  
			  $instr = '';
			  if($isMarkt != 'false') {
				  $isMarktArray = explode('___', $isMarkt);
				  foreach($isMarktArray As $index=> $value) {
					 $instr .= "'".str_replace('_',' ',$value)."'," ;
				  }
			  } 
			 
			  //$instr .= ($isErd == 'erdcollection') 		? "'erd_collection'," 		: '';
			  //$instr .= ($isVatche == 'vatchecollection') 	? "'vatche_collection',"  	: '';
			  //$instr .= ($isDaussi == 'daussicollection') 	? "'daussi_collection',"  	: '';
			  //$instr .= ($isAntique == 'antiquecollection') ? "'antiqu_ecollection',"  	: '';
			  $design = 0;
			  if($instr != ''){
			  		$instr = substr($instr , 0 , (strlen($instr) -1));
				  	$qwhere .=  " AND brand in (".$instr.")";
				  	$design = 1;				  	
			  } 
			  
			  $instr = '';
			  $instr .= ($isPave == 'preowned') ? "'preowned',"  : '';
			  $instr .= ($Solitaire == 'new') ? "'new',"  : '';
			 // $instr .= ($Sidestone == 'sidestones') ?  "'Sidestones'," : ''; 
			  
			  //$instr .= ($isThreestone == 'threestone') ?  "'Three Stones'," : ''; 
			  //$instr .= ($isHalo == 'halo') ?  "'Halo'," : ''; 
			  //$instr .= ($isMatching == 'matching') ?  "'MatchingSet'," : ''; 
			  //$instr .= ($isAnniversary == 'anniversary') ?  "'Anniversary Ring'," : ''; 
			  
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND style in (".$instr.")"  ;
				  $design = 1;
			  }
			   
			 
			  $instr = '';
			  //$instr .= ($platinum == 'ss') ? "'Platinum','platinum','.950 platinum','950 Platinum',"  : '';
			  //$instr .= ($gold == 'gold') ? "'Yellow Gold','18 kt. Yellow Gold','18 Yellow Gold',"  : '';

			  $instr .= ($platinum == 'ss') ? "'ss',"  : '';
			  $instr .= ($gold == 'gold') ? "'gold',"  : '';
			  $instr .= ($goldss == 'gold_ss') ? "'gold_ss',"  : '';

			  //$instr .= ($whitegold == 'whitegold') ?  "'white gold','18 kt. White Gold'," : '';
			  if($instr != ''){
				  $instr = substr($instr , 0 , (strlen($instr) -1));
				  $qwhere .=  " AND metal in (".$instr.")"  ;
				  $design = 1;
			  } 
			  
			 // if($shape != 'all' && $shape != 'undefined'){
			   //$qwhere .=  " AND shape ='".$shape."'"  ;
			   
			  //}
			  
			  if($design==0)
			  {
			      $qwhere .=  " AND brand in ('')";
				  $qwhere .=  " AND style in ('')"  ;
				  $qwhere .=  " AND metal in ('')"  ;
				  
			  }
			  			    
			 
	$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'watches where 1=1 '. $qwhere . ' order by price1 desc '. $limit;
	//var_dump($sql); 
	//	exit(0);
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT  * FROM  '. $this->config->item('table_perfix').'watches  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
		//var_dump($results); 
		//exit(0);
    	return $results;
		
	}
	
	function getWatchByProductId($productID = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."watches
				WHERE productID = '".$productID."'
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

	function getWatchBrand(){
		$qry = "SELECT brand 
				FROM ".config_item('table_perfix')."watches 
				Group by brand
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();
		return $result;
	}

	function getWatchBySKU($sku = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."watches
				WHERE SKU = '".$sku."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	 
}
?>