<?php 
 class Rolexmodel extends Model {
 	
 	function __construct(){
 		parent::Model();
 	}
 	
 	function getCollectionNameBySection($section){
 		 
 		$qry = "SELECT * FROM ".config_item('table_perfix')."rolex 
 				WHERE section = '".$section."' 
 				GROUP BY collection
				";	
 		
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
 	}
 	
 	function getAllByCollectionName($collectionname){
 		$qry = "SELECT * FROM ".config_item('table_perfix')."rolex 
 				WHERE collection = '".$collectionname."'  				
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
 	}
 	
 	function getAllByStock($stockno){
 		$qry = "SELECT * FROM ".config_item('table_perfix')."rolex 
 				WHERE stock_number = '".$stockno."'  				
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
 	}
 	
 	function getMaxPrice(){
		$qry = "SELECT max( price ) AS max
				FROM ".config_item('table_perfix')."rolex";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['max'];
	}
	
	function getMinPrice(){
		$qry = "SELECT min( price ) AS min
				FROM ".config_item('table_perfix')."rolex";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['min'];
	}
	
	function getAllThreestoneRing(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."rolex
				WHERE style = 'Three Stones'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
	
	function getPendants(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."rolex
				WHERE section = 'Rolex' AND collection = 'Pendants'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
	
	function getAllPendantSettings($isPlatinum = false, $isYellowgold = false, $isWhitegold = false, $isSolitaire = false, $isThreestone = false){
		
		$qwhere = "";
			 
		  		  
		  $instr = '';
		  $instr .= ($isPlatinum == 'platinum') 	? "'platinum',"  	: '';
		  $instr .= ($isYellowgold == 'yellowgold') ? "'yellowgold'," 	: '';
		  $instr .= ($isWhitegold == 'whitegold') 	? "'whitegold',"  	: '';
		  if($instr != ''){
		  		$instr = substr($instr , 0 , (strlen($instr) -1));
			  	$qwhere .=  " AND p.metal in (".$instr.")";
			  	$design = 1;				  	
		  } 
		  
		  $stylestr = '';
		  $stylestr .= ($isSolitaire == 'solitaire') 	? "'solitaire',"  	: '';
		  $stylestr .= ($isThreestone == 'threestone') ? "'threestone',"  	: '';
		  $design = 0;
		  if($stylestr != ''){
		  		$stylestr = substr($stylestr , 0 , (strlen($stylestr) -1));
			  	$qwhere .=  " AND p.style in (".$stylestr.")";
			  	$design = 1;				  	
		  } 
		  
		  		
		
		/*$qry = "SELECT j.stock_number,						
						p.id,
						p.shape,
						p.description,
						p.price,
						p.metal,
						p.style,
						p.small_image,
						p.big_image
				FROM dev_rolex AS j
				INNER JOIN dev_pendantsettings AS p 
				ON j.stock_number = p.stock_number ".$qwhere."
				";*/
		
		$qry = "SELECT 				
						p.id,
						p.shape,
						p.description,
						p.price,
						p.metal,
						p.style,
						p.small_image,
						p.big_image
				FROM ".config_item('table_perfix')."pendantsettings AS p 
				where 1=1 ".$qwhere."
				";
		
		$return = 	$this->db->query($qry);
//		var_dump($qry); 
//		exit(0);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
	
	function getPendentSettingsById($id = ''){
		$qry = "SELECT
						p.id,
						p.shape,
						p.description,
						p.price,
						p.metal,
						p.style,
						p.small_image,
						p.big_image
				FROM ".config_item('table_perfix')."pendantsettings AS p 
				WHERE p.id = ".$id."
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getEarringSettingsByShapeMetal($shape ='', $metal = ''){
		$qry = "SELECT * 
				FROM ".config_item('table_perfix')."earringsettings 
				WHERE shape = '".$shape."' AND metal = '".$metal."' AND style != ''
				ORDER BY id";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		//var_dump($result);
		return isset($result) ? $result : false;
	}
	
	function getEarringSettingsById($id = ''){
		$qry = "SELECT * 
				FROM ".config_item('table_perfix')."earringsettings 
				WHERE id = ".$id."
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array(); 
		return isset($result[0]) ? $result[0] : false;
	}
		
	function getRings($section = '', $metal = 'all', $sortby = 'all'){
		$qry = "SELECT * FROM ".config_item('table_perfix')."rolex 
 				WHERE 1 = 1 
				";
		$qwhere = "";
		$orderby = "";
		
		
		
		if($section != '' && $section != 'false'){
			$section = str_replace('_', ' ', $section);
			$qwhere .= " AND section = '".$section."'";
		}
		
		
		
		if($metal != 'all' && $metal != 'false')  {
			$metal = str_replace('_', ' ', $metal);
			switch ($metal){
				case 'platinum':
					$metal = "'Platinum','platinum','.950 platinum','950 Platinum'";
					break;
				case 'whitegold':
					$metal = "'white gold','18 kt. White Gold'";
					break;
				case 'gold':
					$metal = "'Yellow Gold','18 kt. Yellow Gold','18 Yellow Gold'";
					break;
				default:
					$metal;
					break;
			}
			$qwhere .= " AND metal in (".$metal.")";
		}
		
		
		
		
		if($sortby != '' && $sortby != 'false'){			
			switch ($sortby){
				case 'priceasc':
					$orderby = " ORDER BY price ASC";
					break;
				case 'pricedec':
					$orderby = " ORDER BY price DESC";
					break;
				default:
					break;
			}
		}
		
		
		
 		
		$qry = $qry.$qwhere.$orderby;
		 
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}
	
 }


?>