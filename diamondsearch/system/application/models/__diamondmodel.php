<?php class Diamondmodel extends  Model {
	function __construct(){
		parent::Model();
	}
	 
	function getSearch( $page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = '', $searchtype = ''){
		$results = array();
		  if(strcmp($searchtype,'toearring')===0)
		  {
			// $sort = " ORDER BY price, cut, carat, color, clarity  ASC";
			 $sort = " ORDER BY  price ASC";
		  }
		  else
		  {
			$sort = "ORDER BY $sortname $sortorder";
		  }	
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		
			if(($this->session->userdata('searchminprice'))) $qwhere .= " AND price >= ". $this->session->userdata('searchminprice');
			if(($this->session->userdata('searchmaxprice'))) $qwhere .= " AND price <= ". $this->session->userdata('searchmaxprice');
			if(($this->session->userdata('depthmin'))) $qwhere .= " AND Depth >= ". $this->session->userdata('depthmin');
			if(($this->session->userdata('depthmax'))) $qwhere .= " AND Depth <= ". $this->session->userdata('depthmax');
			if(($this->session->userdata('caratmin'))) $qwhere .= " AND carat >= ". $this->session->userdata('caratmin');
			if(($this->session->userdata('caratmax'))) $qwhere .= " AND carat <= ". $this->session->userdata('caratmax');
			// file_put_contents("somefile.sql.txt",$searchtype,FILE_APPEND);
			$shape = '';
			if(($this->session->userdata('shape'))) $shape =  $this->session->userdata('shape');
			$shapes = array();
			$shapes = explode('.' , $shape);
			$shapestr = '';
			if(sizeof($shapes)>0){
				foreach ($shapes as $val){
					if($val != '')$shapestr .= "'".$val."',";
				}
				$shapestr = substr($shapestr  , 0 , (strlen($shapestr) -1));
			}
			if($shapestr != '')$qwhere .=  " AND shape in ($shapestr)";
			else{
				if($searchtype == 'tothreestone')$qwhere .=  " AND shape in ('B','PR','E')";
			}
			
			
			
			$cutstr = '';  
			$cutmin = 0;
			$cutmax = 3;  
			if(($this->session->userdata('cutmin'))) $cutmin = round($this->session->userdata('cutmin'));
			if(($this->session->userdata('cutmax'))) $cutmax = round($this->session->userdata('cutmax'));
			 
			$cutarray = array(
							'0' => 'Good',	
							'1' => 'Very Good',
							'2' => 'Ideal',
							'3' => 'Premium'
							 );
							 
			if($cutmin and $cutmax < count($cutarray)) {
				for($i= $cutmin; $i<=$cutmax; $i++)
				if($cutarray[$i] != '')$cutstr .= "'".$cutarray[$i]."',";
				$cutstr = substr($cutstr , 0 , (strlen($cutstr) -1));
			}
			
			if(($this->session->userdata('ispremium'))){ $cutstr = "'Premium'"; }
			if($cutstr != '')$qwhere .=  " AND cut in ($cutstr)";
			
			

			$colors = array('0' => 'D',
							'1' => 'E',	
							'2' => 'F',
							'3' => 'G',
							'4' => 'H',
							'5' => 'I',
							'6' => 'J'
								);
			$color = '';  
			$colormin = 0;
			$colormax = count($colors);
			
			if(($this->session->userdata('colormin'))) $colormin =  $this->session->userdata('colormin');
			if(($this->session->userdata('colormax'))) $colormax =  $this->session->userdata('colormax');			
			if($colormin and $colormax < count($colors)) {
				for($i= $colormin; $i<$colormax; $i++)
				if($colors[$i] != '')$color .= "'".$colors[$i]."',";
				$color = substr($color , 0 , (strlen($color) -1));
			}
			if($color != '')$qwhere .=  " AND color in ($color)";			
			
			

			$claritys = array(	'0' => 'IF',
								'1' => 'VVS1',	
								'2' => 'VVS2',
								'3' => 'VS1',
								'4' => 'VS2',
								'5' => 'SI1',
								'6' => 'SI2',
								'7' => 'I1' 
							);
			$clarity = '';  
			$claritymin = 0;
			$claritymax = count($claritys);
			
			if(($this->session->userdata('claritymin'))) $claritymin =  $this->session->userdata('claritymin');
			if(($this->session->userdata('claritymax'))) $claritymax =  $this->session->userdata('claritymax');

			if($claritymin and $claritymax < count($claritys)) {
				for($i= $claritymin; $i<$claritymax; $i++)
				if($claritys[$i] != '')$clarity .= "'".$claritys[$i]."',";
				$clarity = substr($clarity , 0 , (strlen($clarity) -1));
			}
		 	
			
			if($clarity != '')$qwhere .=  " AND clarity in ($clarity)";			
			
			$fluroarray = array('0' => 'NO',
							'1' => 'FB',	
							'2' => 'MB',
							'3' => 'MED',
							'4' => 'ST BLUE',
							'5' => 'VST BLUE'
							 );			
			$flurostr = '';  
			$fluromin = 0;
			$fluromax = count($fluroarray);
			if(($this->session->userdata('fluromin'))) $fluromin =  $this->session->userdata('fluromin');
			if(($this->session->userdata('fluromax'))) $fluromax =  $this->session->userdata('fluromax');

			if($fluromin and $fluromax < count($fluroarray)) {
				for($i= $fluromin; $i<$fluromax; $i++)
				if($fluroarray[$i] != '')$flurostr .= "'".$fluroarray[$i]."',";
				$flurostr = substr($flurostr , 0 , (strlen($flurostr) -1));
			}
			if($flurostr != '')$qwhere .=  " AND Flour in ($flurostr)";
			
			$polisarray = array('0' => 'ID',
							'1' => 'EX',	
							'2' => 'VG',
							'3' => 'GD',
							'4' => 'F'
							 );			
			$polisstr = '';  
			$polismin = 0;
			$polismax = count($polisarray);
			if(($this->session->userdata('polismin'))) $polismin =  $this->session->userdata('polismin');
			if(($this->session->userdata('polismax'))) $polismax =  $this->session->userdata('polismax');

			if($polismin and $polismax < count($polisarray)){
				for($i= $polismin; $i<$polismax; $i++)
				if($polisarray[$i] != '')$polisstr .= "'".$polisarray[$i]."',";

				$polisstr = substr($polisstr , 0 , (strlen($polisstr) -1));
			}
			if($polisstr != '')$qwhere .=  " AND Polish in ($polisstr)";
			
			$symmetarray = array('0' => 'ID',
							'1' => 'EX',	
							'2' => 'VG',
							'3' => 'GD',
							'4' => 'F'
							 );			
			$symmetstr = '';  
			$symmetmin = 0;
			$symmetmax = count($symmetarray);
			if(($this->session->userdata('symmetmin'))) $symmetmin =  $this->session->userdata('symmetmin');
			if(($this->session->userdata('symmetmax'))) $symmetmax =  $this->session->userdata('symmetmax');

			if($symmetmin and $symmetmax < count($symmetarray)){
				for($i= $symmetmin; $i<$symmetmax; $i++)
				if($symmetarray[$i] != '')$symmetstr .= "'".$symmetarray[$i]."',";

				$symmetstr = substr($symmetstr , 0 , (strlen($symmetstr) -1));
			}
			if($symmetstr != '')$qwhere .=  " AND Sym in ($symmetstr)";
			
			
			if(($this->session->userdata('tablemin'))) $qwhere .= " AND TablePercent >= ". $this->session->userdata('tablemin');
			if(($this->session->userdata('tablemax'))) $qwhere .= " AND TablePercent <= ". $this->session->userdata('tablemax');
			if(($this->session->userdata('pricePerCaratmin'))) $qwhere .= " AND pricepercrt >= ". $this->session->userdata('pricePerCaratmin');
			if(($this->session->userdata('pricePerCaratmax'))) $qwhere .= " AND pricepercrt <= ". $this->session->userdata('pricePerCaratmax');
			
			$minpricefilter = floatval($this->commonmodel->db_config('minpricefilter'));
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'products where price > '.$minpricefilter.' '. $qwhere . ' ' . $sort . ' '. $limit;
//		 echo $sql;
file_put_contents("sqlrrr", $sql);
	//	file_put_contents("somefile.sql.txt",$sql,FILE_APPEND);	
		
	 	$result = $this->db->query($sql);
		$results['query']  = $sql;
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT  lot FROM  '. $this->config->item('table_perfix').'products  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
	
	function getRatio($price) {
		$qry = "SELECT rate
				FROM ".$this->config->item('table_perfix')."helix_prices 
				WHERE (pricefrom <= ".$price." AND priceto >= ".$price.")";
				$result = 	$this->db->query($qry);
		    $return = $result->result_array();	
		    return isset($return[0]) ? $return[0]['rate'] : false;
	}
	
	function getMaxPrice(){
		$qry = "SELECT max( price ) AS max
				FROM ".config_item('table_perfix')."products";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['max'];
	}
	
	function getMinPrice(){
		$qry = "SELECT min( price ) AS min
				FROM ".config_item('table_perfix')."products";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['min'];
	}
	
	function getDetailsByLot($lot){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE lot = '".$lot."'
				";
		$result = 	$this->db->query($qry);
		$return = $result->result_array();	
		return isset($return[0]) ? $return[0] : false;
	}
	
	function getMinPricePerCarat(){
		$qry = "SELECT min( pricepercrt ) AS min
				FROM ".config_item('table_perfix')."products";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['min'];
	}
	
	function getMaxPricePerCarat(){
		$qry = "SELECT max( pricepercrt ) AS max
				FROM ".config_item('table_perfix')."products";
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['max'];
	}
	
	function getCountDiamond($minPrice,$maxPrice,$shape=''){
		$qry = "SELECT count(lot) as cnt
				FROM ".config_item('table_perfix')."products";
		if($minPrice!='' && $maxPrice!=''){
			$qry .= " WHERE price >=".$minPrice." and price <=".$maxPrice;
		}
				
		$result = 	$this->db->query($qry);
		$price = $result->result_array();	
		return $price[0]['cnt'];
	}
	
	function getMinMaxCarat(){		
		 
		$qry = "SELECT min(carat) as min, max(carat) as max
				FROM ".config_item('table_perfix')."products";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return $result[0];
		
	}
	
	function sidestonecondition($diamond){
			/*if($diamond['carat'] >= 0.5 && $diamond['carat'] <= 0.69){$carat = 'carat >= 0.25 and carat <= 0.30';}
			elseif ($diamond['carat'] >= 0.70 && $diamond['carat'] <= 0.99){$carat = 'carat >= 0.31 and carat <= 0.40';}
			elseif ($diamond['carat'] >= 1 && $diamond['carat'] <= 1.50){$carat = 'carat >= 0.41 and carat <= 0.55';}
			elseif ($diamond['carat'] >= 1.51 && $diamond['carat'] <= 2){$carat = 'carat >= 0.56 and carat <= 0.75';}
			elseif ($diamond['carat'] >= 2 && $diamond['carat'] <= 3.50){$carat = 'carat >= 0.75 and carat <= 0.90';}			
			else $carat='carat >= 0.50 and carat <= 3.50';*/

			/*if($diamond['carat'] >= 0.50 && $diamond['carat'] <= 0.69 ){$carat = 'carat >= 0.25 and carat <= 0.30';}
			elseif ($diamond['carat'] >= 0.70 && $diamond['carat'] <= 0.99){$carat = 'carat >= 0.30 and carat <= 0.50';}
			elseif ($diamond['carat'] >= 1 && $diamond['carat'] <= 1.50){$carat = 'carat >= 0.45 and carat <= 0.60';}
			elseif ($diamond['carat'] >= 1.51 && $diamond['carat'] <= 2){$carat = 'carat >= 0.60 and carat <= 1';}
			elseif ($diamond['carat'] >= 2 && $diamond['carat'] <= 3.50){$carat = 'carat >= 0.75 and carat <= 1.50';}			
			else $carat='carat >= 0.50 and carat <= 3.50';*/

			$colorary = array(
							'D' => "color in ('D','E')",
							'E' => "color in ('D','E','F')",
							'F' => "color in ('E','F','G')",
							'G' => "color in ('F','G','H')",
							'H' => "color in ('G','H','I')",
							'I' => "color in ('H','I','J')",
							'J' => "color in ('I','J')"
						);
			$color = $colorary[$diamond['color']];
						
			$clarityary = array(
								'IF' 	=> "clarity in ('IF','VVS1')",
								'VVS1' 	=> "clarity in ('IF','VVS1','VVS2')",
								'VVS2' 	=> "clarity in ('VVS1','VVS2','VS1')",
								'VS1' 	=> "clarity in ('VVS2','VS1','VS2')",
								'VS2' 	=> "clarity in ('VS1','VS2','SI1')",
								'SI1' 	=> "clarity in ('VS2','SI1','SI2')",
								'SI2' 	=> "clarity in ('SI1','SI2','I1')",
								'I1' 	=> "clarity in ('SI2','I1')"
							);
			$clarity = $clarityary[$diamond['clarity']];
			
			if($diamond['Cert'] != '')
			{
			  $certificate = " Cert = '".$diamond['Cert']."' ";
			}
			
			$lower_depth = (((100-3)/100) * $diamond['Depth']);		

			$upper_depth = (((100+3)/100) * $diamond['Depth']);	

			$depth = " Depth >= '".$lower_depth."' and Depth <='".$upper_depth."' ";

			$lower_table = (((100-3)/100) * $diamond['TablePercent']);		

			$upper_table = (((100+3)/100) * $diamond['TablePercent']);	

			$tablePercent =  " TablePercent >= '".$lower_table."' and TablePercent <='".$upper_table."' ";

			$length = $diamond['length'];
			$lower_length = $length - 0.10;	
			$upper_length = $length + 0.10;	
			$length_cond = " length >= '".$lower_length."' and length <='".$upper_length."' ";
			$width = $diamond['width'];
			$lower_width = $width - 0.10;	
			$upper_width = $width + 0.10;
			$width_cond = " width >= '".$lower_width."' and width <='".$upper_width."' ";
			
			$condition='';



			/*if($carat!='' && isset($carat)) 
			{
			  $condition.=  $carat .' and ';
			}*/
			if($color!='')
			{
			  $condition.=$color.' and ';
			}
			if($certificate!='')
			{
			  $condition.=$certificate.' and ';
			}

			if($tablePercent!='')
			{
			  $condition.=$tablePercent.' and ';
			}

			if($depth!='')
			{
			  $condition.=$depth.' and ';
			}
			
			if($length_cond!='')
			{
			  $condition.=$length_cond.' and ';
			}
			if($width_cond!='')
			{
			  $condition.=$width_cond.' and ';
			}

			$condition.=$clarity;
			//print_r($diamond);
			//echo($condition);
			return  $condition;
	}
	
	function getSidestoneByCenterLot($diamond = ''){
		$condition =  $this->sidestonecondition($diamond);		
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE ".$condition;
		//print_r($diamond);
		//die($qry);
		$result = 	$this->db->query($qry);
		
		$return = $result->result_array(); 
		return isset($return) ? $return : false;
	}
	
	function getAllSideStones($diamond = '',$start = 0){
		$results = array();
		$condition =  $this->sidestonecondition($diamond);		
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE ".$condition . " limit ".$start." ,10";
		$result = 	$this->db->query($qry);
		$results['result'] = $result->result_array();
		
		$qry2 = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE ".$condition;
		$result2 = 	$this->db->query($qry2);
		$results['count'] = $result2->num_rows();
		return $results;		
	}
	
	function get2sidestone($tablemin,$tablemax,$depthmin,$depthmax,$sidelot){
		$diamond = $this->getDetailsByLot($sidelot);				
		//$condition =  $this->sidestonecondition($diamond);
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE carat < ".$diamond['carat']." and TablePercent >=".$tablemin." and TablePercent <=".$tablemax."  and Depth >=".$depthmin." and Depth <=".$depthmax." order by carat DESC";				
		
		
		$result = 	$this->db->query($qry);
		$return = $result->result_array();
		return isset($return) ? $return : false;
	}	
	
	function getPairSidestone($carat, $color, $clarity, $tablecondition, $depthcondition){
				
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE carat = ".$carat." and color = '".$color."' and clarity = '".$clarity."' and ".$tablecondition." and ".$depthcondition;
	//	file_put_contents("somefile.sql.txt",$qry,FILE_APPEND);	
//		$this->db->where('id' , 1);
//		$this->db->update('error', array('msg' => $qry . '------------'));		
		
		$result = 	$this->db->query($qry);
		$return = $result->result_array(); 		
		return isset($return) ? $return : false;
	}

	function getEarRingSideStone($diamond = ''){
		$condition =  $this->Earringsidestonecondition($diamond);		
	
		$minpricefilter = floatval($this->commonmodel->db_config('minpricefilter'));
		
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE ".$condition. " and price > $minpricefilter ORDER BY price ASC";
		//print_r($diamond);
		//die($qry);
		$result = 	$this->db->query($qry);
		
		$return = $result->result_array(); 
		return isset($return) ? $return[0] : false;
	}

	function Earringsidestonecondition($diamond){
			
			$colorary = array(
							'D' => "color in ('D','E')",
							'E' => "color in ('D','E','F')",
							'F' => "color in ('E','F','G')",
							'G' => "color in ('F','G','H')",
							'H' => "color in ('G','H','I')",
							'I' => "color in ('H','I','J')",
							'J' => "color in ('I','J')"
						);
			$color = $colorary[$diamond['color']];
						
			$clarityary = array(
								'IF' 	=> "clarity in ('IF','VVS1')",
								'VVS1' 	=> "clarity in ('IF','VVS1','VVS2')",
								'VVS2' 	=> "clarity in ('VVS1','VVS2','VS1')",
								'VS1' 	=> "clarity in ('VVS2','VS1','VS2')",
								'VS2' 	=> "clarity in ('VS1','VS2','SI1')",
								'SI1' 	=> "clarity in ('VS2','SI1','SI2')",
								'SI2' 	=> "clarity in ('SI1','SI2','I1')",
								'I1' 	=> "clarity in ('SI2','I1')"
							);
			$clarity = $clarityary[$diamond['clarity']];
			
			if($diamond['Cert'] != '')
			{
			  $certificate = " Cert = '".$diamond['Cert']."' ";
			}
			
			$lower_depth = (((100-3)/100) * $diamond['Depth']);		

			$upper_depth = (((100+3)/100) * $diamond['Depth']);	

			$depth = " Depth >= '".$lower_depth."' and Depth <='".$upper_depth."' ";

			$lower_table = (((100-3)/100) * $diamond['TablePercent']);		

			$upper_table = (((100+3)/100) * $diamond['TablePercent']);	

			$tablePercent =  " TablePercent >= '".$lower_table."' and TablePercent <='".$upper_table."' ";

			
			  //$measurearray = explode('-', $diamond['Meas']);
			  $length = $diamond['length'];
			  $lower_length = $length - 0.10;	
			  $upper_length = $length + 0.10;	
			  $length_cond = " length >= '".$lower_length."' and length <='".$upper_length."' ";
			  $width = $diamond['width'];
			  $lower_width = $width - 0.10;	
			  $upper_width = $width + 0.10;
			  $width_cond = " width >= '".$lower_width."' and width <='".$upper_width."' ";

			$condition='';

			if($color!='')
			{
			  $condition.=$color.' and ';
			}
			if($certificate!='')
			{
			  $condition.=$certificate.' and ';
			}

			if($tablePercent!='')
			{
			  $condition.=$tablePercent.' and ';
			}

			if($depth!='')
			{
			  $condition.=$depth.' and ';
			}
			if($length_cond!='')
			{
			  $condition.=$length_cond.' and ';
			}
			if($width_cond!='')
			{
			  $condition.=$width_cond.' and ';
			}

			$condition.=$clarity;
			if($condition !='') 	$condition .= " and shape  = '".$diamond['shape']."'"; 
			else $condition .= " shape  = '".$diamond['shape']."'";
		
			//if($condition !='') 	$condition .= " and cut  = '".$diamond['cut']."'"; 
			//else $condition .= " cut  = '".$diamond['cut']."'";

			if($condition !='') 	$condition .= " and lot  != '".$diamond['lot']."'"; 
			else $condition .= " lot  != '".$diamond['lot']."'";


			//if($condition !='') 	$condition .= " and price  <= '".$diamond['price']."'"; 
			//else $condition .= " price  <= '".$diamond['price']."'";

			//print_r($diamond);
			//echo($condition);
			return  $condition;
		}

	function getStudBySKU($sku = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."diamondstudearring
				WHERE stock_number = '".$sku."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}

	function getRoundStoreCategoryID($carat) {
		if($carat == 0.10) {
			$storeCatID = 621585010;
		} else if($carat == 0.17) {
			$storeCatID = 621586010;
		} else if($carat == 0.25) {
			$storeCatID = 621587010;
		} else if($carat == 0.33) {
			$storeCatID = 621588010;
		} else if($carat == 0.50) {
			$storeCatID = 621589010;
		} else if($carat == 0.67) {
			$storeCatID = 621590010;
		} else if($carat == 0.75) {
			$storeCatID = 621591010;
		} else if($carat == 1.00) {
			$storeCatID = 621592010;
		} else if($carat == 1.25) {
			$storeCatID = 621593010;
		} else if($carat == 1.50) {
			$storeCatID = 621594010;
		} else if($carat == 1.75) {
			$storeCatID = 621595010;
		} else if($carat == 2.00) {
			$storeCatID = 621596010;
		} else if($carat == 3.00) {
			$storeCatID = 621597010;
		} else if($carat == 4.00) {
			$storeCatID = 621598010;
		} else {
			$storeCatID = 621599010;
		}
		return $storeCatID;
	}

	function getPrincessStoreCategoryID($carat) {
		if($carat == 0.10) {
			$storeCatID = 621646010;
		} else if($carat == 0.17) {
			$storeCatID = 621647010;
		} else if($carat == 0.25) {
			$storeCatID = 621648010;
		} else if($carat == 0.33) {
			$storeCatID = 621649010;
		} else if($carat == 0.50) {
			$storeCatID = 621650010;
		} else if($carat == 0.67) {
			$storeCatID = 621651010;
		} else if($carat == 0.75) {
			$storeCatID = 621652010;
		} else if($carat == 1.00) {
			$storeCatID = 621653010;
		} else if($carat == 1.25) {
			$storeCatID = 621654010;
		} else if($carat == 1.50) {
			$storeCatID = 621655010;
		} else if($carat == 1.75) {
			$storeCatID = 621656010;
		} else if($carat == 2.00) {
			$storeCatID = 621657010;
		} else if($carat == 3.00) {
			$storeCatID = 621658010;
		} else if($carat == 4.00) {
			$storeCatID = 621659010;
		} else {
			$storeCatID = 621660010;
		}
		return $storeCatID;
	}
	 
	function addStudtoEbay($detail, $action='Add'){
		//print_r($detail);
		$requestArray['listingType'] = 'StoresFixedPrice';
		$requestArray['primaryCategory'] = '164320';
        $requestArray['itemTitle']       = $detail['carat'].' Carat '.str_replace('Four- Prong', '', str_replace('18k','14k', $detail['collection'])).' H-VS2';
		$requestArray['productID']       = $detail['stock_number'];
		$storeImage = config_item('base_url').'images/upperbar02.jpg';
		$colorImage = config_item('base_url').'images/Color_Profile.jpg';

		/*if(file_exists(config_item('base_path').'images/'.$detail['big_image']) && $detail['big_image'] !='' ) {
			$watchImage = config_item('base_url').'images/'.$detail['big_image'];
		}*/

		switch ($detail['shape']){
			case 'B':
				$watchImage = config_item('base_url').'images/Round-stud.jpg';
			break;
			case 'PR':
				$watchImage = config_item('base_url').'images/Princess-stud.jpg';
			break;
			default:
				$watchImage = config_item('base_url').'images/'.$detail['big_image'];
				break;
		 }

	
		switch ($detail['shape']){
			case 'B':
				$shape = 'Round';
				$storeCategoryId = $this->getRoundStoreCategoryID($detail['carat']);
			break;
			case 'PR':
				$shape = 'Princess';
				$storeCategoryId = $this->getPrincessStoreCategoryID($detail['carat']);
			break;
			case 'R':
				$shape = 'Radiant';
				$storeCategoryId = 621481010;
				break;
			case 'E':
				$shape = 'Emerald';
				$storeCategoryId = 621482010;
				break;
			case 'AS':
				$shape = 'Asscher';
				$storeCategoryId = 621480010;
				break;
			case 'O':
				$shape = 'Oval';
				$storeCategoryId = 621485010;
				break;
			case 'P':
				$shape = 'Pear';
				$storeCategoryId = 621484010;
				break;
			case 'H':
				$shape = 'Heart';
				$storeCategoryId = 621483010;
				break;
			default:
				$storeCategoryId = 621483010;
				break;
		 }
		/*if($detail['ebay_cat_id'] != $storeCategoryId) {

		}*/
		$price = round(($detail['price'] + 70.00) * 1.25);
		$retailPrice = round($price * 4.5);
		$studDetail .= '<div><p align="justify">'.$detail['description'].'</p></div>';
		/*$studDetail .= '<div>
			<TABLE width=598 align=center border=0>
			<TBODY>
			<TR>
			<TD align=middle width=626>
			<MARQUEE><FONT face=Verdana size=5><B>WELCOME TO ALAN G, YOUR SOURCE FOR CERTIFIED GIA &amp; EGL DIAMONDS </B></FONT></MARQUEE>
			<P>
			<MARQUEE><FONT face=Verdana size=3><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (877)425-2645 / (213)623-1456</B></FONT></MARQUEE>
			<MARQUEE><A href="mailto:alangjewelers@aol.com?subject=ebay auction">alangjewelers@aol.com</A></MARQUEE><BR></P>&nbsp;</TD></TR>
			<TR>
			<TD align=middle width=626><IMG height=99 src="'.$storeImage.'" width=900 border=0></TD></TR>

			<TR>
			<TD vAlign=top width=626 height=2309>
			<DIV align=center>
			<TABLE height=2479 width="99%" border=0>
			<TBODY>
			<TR>
			<TD vAlign=top align=right width="99%" height=1457>
			<TABLE height=560 width=625 align=center border=0>
			<TBODY>
			<TR vAlign=top align=left>
			<TD width=252 height=853>
			<TABLE height=236 cellSpacing=1 cellPadding=1 width=513 border=0>
			<TBODY>
			<TR>
			<TD align=middle width=509 bgColor=black height=17><B><FONT face="Georgia, Times New Roman, Times, serif" color=#ffffff size=2>Information</FONT></B></TD></TR>
			<TR>
			<TD width=509 height=18>&nbsp;ITEM Name:&nbsp;'.$detail['collection'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=silver height=18>&nbsp;TOTAL WEIGHT:&nbsp;'.$detail['carat'].'</TD></TR>
			<TR>
			<TD width=509 height=22>&nbsp;ITEM DETAIL:&nbsp;'.$detail['description'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=yellow height=18>Our Price:<B> </B><FONT color=#ff0000>$'.$price.'</FONT>&nbsp; &amp;&nbsp; No Reserve <FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT></TD></TR></TBODY></TABLE>';

			<TD width=365 height=853>
			<TABLE height=670 cellSpacing=1 cellPadding=1 width=389 align=center border=0>
			<TBODY>
			<TR bgColor=#000000>
			<TD width=414 height=212><IMG height=262 src="'.$watchImage.'" width=535 border=0></TD></TR>
			<TR>
			<TD vAlign=center width=348 bgColor=black height=20>
			<P align=center><B><FONT face="Georgia, Times New Roman, Times, serif" color=white size=2>Your Free Gift</FONT></B></P></TD></TR>
			<TR>
			<TD vAlign=top width=348 height=454>
			<UL>

			<LI><FONT face=Verdana size=2>Jewelry Box </FONT></LI>
			<LI><FONT face=Verdana size=2>Guaranteed to be 100% genuine diamond</FONT></LI>
			<LI><FONT face=Verdana size=2>Guaranteed to be 100% genuine 14kt gold</FONT></LI>
			<LI><FONT face=Verdana size=2>Free appraisal for the estimated retail value of the item with every purchase. </FONT></LI>
			<LI><FONT face=Verdana,Arial color=#000000 size=2>Items will be shipped 5-7 days as payment is received.&nbsp; (shipping cut off time is 1:00 PM pacific standard time)</FONT> 
			<P>Alan G. Jewelers has been dedicated to excellent customer satisfaction and lowest prices in the jewelry business for nearly 20 years. We are a direct diamond importer and all of our diamonds and gemstones are guaranteed to appraise for twice the amount of purchase price. Our merchandise is being offered on EBAY in order to provide the same exceptional quality and value to the general public. <FONT color=#ff0000>These diamonds are all guaranteed to be natural, with no enhancements or treatments.</FONT> Our goal is to reach the highest customer satisfaction rate possible. We welcome the opportunity to serve you.<BR></FONT></B></FONT></P>

			<P></P>
			<P><FONT face=Verdana color=#ff0000 size=4>Please review our feedback for your Confidence.&nbsp; Lay away plan is available, please click here for additional information </FONT>&nbsp;<FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT></P></LI></UL>
			<P>&nbsp;</P></TD></TR>
			<TR>
			<TD width=414 height=124></TD></TR>
			<TR>
			<TD width=414 height=259>
			<P align=center><FONT face="Arial, Helvetica, sans-serif" color=#000033 size=3><IMG height=177 src="http://images.channeladvisor.com/Sell/SSProfiles/30055276/Images/powerseller.gif" width=209><BR></FONT><FONT face=Verdana>BID WITH CONFIDENCE!</FONT> </P>
			<P align=center><I><B><FONT color=#008080 size=5>PLATINUM POWER SELLER</FONT></B></I></P>
			<P dir=rtl align=center><FONT face=Verdana size=2><FONT color=#800000>Alan G Jewelers Guarantees all our <BR>diamonds to be 100% natural <BR></FONT></P></FONT></TD></TR></TBODY></TABLE></TD>

			<TR vAlign=top align=left>
			<TD width=617 colSpan=2 height=243>
			<P>&nbsp;<U><B><FONT face=Verdana size=3>About us</FONT></B></U> </P>
			<P class=text><FONT face=Verdana size=2>We invite you to read our <A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT>page to obtain information on: 
			<UL type=circle>
			<LI>
			<P class=text>Alan G Jewelers</P></LI>
			<LI>
			<P class=text>Store Policy</P></LI>

			<LI>
			<P class=text>Shipping </P></LI>
			<LI>
			<P class=text>Return Policy</P></LI></UL>
			<P class=fontblack><U><B><FONT face=Verdana size=3>Payment Information </FONT></B></U></P>
			<P align=justify><FONT face=Verdana size=2>We accept ELECTRONIC BANK <FONT color=red>Wire Transfer</FONT> and <FONT color=red>PAYPAL.</FONT></FONT></P>
			<P align=justify><FONT face=Verdana size=2><IMG height=34 src="http://images.andale.com/f2/103/108/10035456/1054817181174_creditcard.jpg" width=379 border=0></FONT> <IMG height=24 src="http://pics.ebaystatic.com/aw/pics/paypal/iconEcheck.gif" width=50 border=0></P>
			<P align=justify></P>

			<P></P></TD>
			<TR vAlign=top align=left>
			<TD width=617 colSpan=2 height=369>&nbsp;<U><B><FONT face=Verdana size=3>Helpful Information </FONT></B></U>
			<P class=text><FONT face=Verdana size=2>GIA stands for Gemological Institute of America and EGL stands for European Gemological Laboratory. GIA and EGL certification are prepared by a third independent party not affiliated to Alan G Jewelers for your protection. The certifications state the color and clarity of diamonds greater than .50cts. They are both well respected in the jewelry industry. If you need any more information regarding these laboratories, you may visit EGL at <A href="http://www.eglusa.com/customerlogin.html">www.eglusa.com</A> </FONT>
			<P class=text><U><B><FONT face=Verdana>Satisfied Client</FONT><FONT face=Verdana size=3>\'s Letter </FONT></B></U>
			<P class=text> 
			<DIV>dated July 13, 2004: 
			<P>"Alan,</P></DIV>
			<DIV>&nbsp;</DIV>
			<DIV>I received your diamond and its a beauty.&nbsp; Thank you so much for your patience and help, you were a dream come true and I know my future wife will appreciate the care you took.</DIV>

			<DIV><BR>&nbsp;<BR>Jeffery Ned"</DIV><FONT face=Verdana size=2>
			<P class=fontblack><U><B><FONT face=Verdana size=3>Clarity </FONT></B></U></P>
			<P align=justify><FONT face=Verdana size=2>The following table explains the diamond clarity (inside the diamond):<BR>
			<P>
			<TABLE width="80%" border=1>
			<TBODY>
			<TR>
			<TD align=middle><FONT face=Arial color=#586479 size=1>IF</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>VVS1</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>VVS2</FONT> </TD>

			<TD align=middle><FONT face=Arial color=#586479 size=1>VS1</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>VS2</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>SI1</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>SI2</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>SI3</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>I1</FONT> </TD>

			<TD align=middle><FONT face=Arial color=#586479 size=1>I2</FONT> </TD>
			<TD align=middle><FONT face=Arial color=#586479 size=1>I3</FONT> </TD></TR>
			<TR>
			<TD align=middle><FONT face=Arial color=#586479 size=1>FLAWLESS</FONT> </TD>
			<TD align=middle colSpan=2><FONT face=Arial color=#586479 size=1>EXTREMELY DIFFICULT TO SEE INCLUSIONS UNDER 10x MAGNIFICATION</FONT> </TD>
			<TD align=middle colSpan=2><FONT face=Arial color=#586479 size=1>DIFFICULT TO SEE INCLUSIONS UNDER 10x MAGNIFICATION</FONT> </TD>
			<TD align=middle colSpan=3><FONT face=Arial color=#586479 size=1>INCLUSIONS VISIBLE UNDER 10X MAGNIFICATION </FONT></TD>

			<TD align=middle colSpan=3><FONT face=Arial color=#586479 size=1>INCLUSIONS VISIBLE TO NAKED EYE</FONT> </TD></TR></TBODY></TABLE>
			<P>
			<P class=fontblack><U><B><FONT face=Verdana size=3>Color </FONT></B></U></P>
			<P align=justify><FONT face=Verdana size=2></FONT></P></FONT></FONT>
			<TR>
			<TD class=basic10 colSpan=2 height=394>While many diamonds appear colorless, or white, they may actually have subtle yellow or brown tones that can be detected when comparing diamonds side by side. Diamonds were formed under intense heat and pressure, and traces of other elements may have been incorporated into their atomic structure accounting for the variances in color. <BR><BR>Diamond color grades start at D and continue down through the alphabet. Colorless diamonds, graded D, are extremely rare and very valuable. The closer a diamond is to being colorless, the more valuable and rare it is. <BR><BR>The color of a diamond is graded with the diamond upside down before it is set in a mounting. The first three colors D, E, F are often called collection color. The subtle changes in collection color are so minute that it is difficult to identify them in the smaller sizes. Although the presence of color makes a diamond less rare and valuable, some diamonds come out of the ground in vivid "fancy" colors - well defined reds, blues, pinks, greens, and bright yellows. These are highly priced and extremely rare.<BR><BR>
			<DIV align=center><IMG height=200 src="'.$colorImage.'" width=600> </DIV></TD></TR></TBODY></TABLE>
			<DIV></DIV></TD></TR></TBODY></TABLE></DIV>
	</div>';*/

/*	$studDetail = '<head>
<META content="Microsoft FrontPage 4.0" name=GENERATOR>
<META content=FrontPage.Editor.Document name=ProgId> </head>

<TABLE CELLSPACING="28" CELLPADDING="0" WIDTH="100%">
<TR>
<TD VALIGN="top">

<!-- Begin Description -->
<TABLE width=598 align=center border=0>
<TBODY>
<TR>
<TD align=middle width=626>
<MARQUEE><FONT face=Verdana size=5><B>WELCOME TO ALAN G, YOUR SOURCE FOR 
CERTIFIED GIA & EGL DIAMONDS </B></FONT></MARQUEE>
<P>
<MARQUEE><FONT face=Verdana 
size=3><B>           
                   (877)425-2645 / 
(213)623-1456</B></FONT></MARQUEE>
<MARQUEE><A href="_mailto:alangjewelers@aol.com?subject=ebay_ 
(mailto:alangjewelers@aol.com?subject=ebay) 
auction">alangjewelers@aol.com</A></MARQUEE><BR></P> </TD></TR>
<TR>
<TD align=middle width=626><IMG height=99 
src="'.$storeImage.'" width=900 border=0></TD></TR>
<TR>
<TD vAlign=top width=626 height=2309>
<DIV align=center>
<TABLE height=2479 width="99%" border=0>
<TBODY>
<TR>
<TD vAlign=top align=right width="99%" height=1457>
<TABLE height=1 width=625 align=center border=0>
<TBODY>
<TR vAlign=top align=left>
<TD width=252 height=1>
<TABLE height=220 cellSpacing=1 cellPadding=1 width=364 border=0>
<TBODY>
<TR>
<TD align=middle width=360 bgColor=black height=17><B><FONT face="Georgia, 
Times New Roman, Times, serif" color=#ffffff 
size=2>Information</FONT></B></TD></TR>
<TR>
<TD width=360 height=18> ITEM NUMBER: '.$detail['stock_number'].' </TD></TR>
<TR>
<TD width=360 bgColor=silver height=1> METAL:   14KT WHITE 
GOLD</TD></TR>
<TR>
<TD width=360 bgColor=aqua height=15> ITEM INFO:   
CERTIFICATE 
APPRAISAL               </TD></TR>

<TR>
<TD width=360 height=18> WEIGHT OF DIAMOND:  '.$detail['carat'].' Carats</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> CLARITY:   
(NATURAL CLARITY)</TD></TR>
<TR>
<TD width=360 height=21> COLOR:    (NATURAL 
COLOR)</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> POLISH: EXCELLENT TO VERY 
GOOD</TD></TR>
<TR>
<TD width=360 height=21> SYMMETRY: EXCELLENT TO VERY GOOD</TD></TR>

<TR>
<TD width=360 bgColor=silver height=18> CUT:  EXCELLENT TO VERY 
GOOD</TD></TR>
<TR>
<TD width=360 height=19> NUMBER OF DIAMONDS:  2  
INDIVIDUAL</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> DIAMOND SETTING:   
4 PRONG</TD></TR>
<TR>
<TD width=360 bgColor=#ffffff height=18> CONDITION:  100% BRAND 
NEW</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> ESTIMATED RETAIL VALUE : 
$'.$retailPrice.'</TD></TR>
<TR>
<TD width=360 bgColor=yellow height=18> OUR PRICE: <FONT 
color=#ff0000>$'.$price.'</FONT>  &  
NO RESERVE <FONT face=Arial size=2><A 
href="_http://members.ebay.com/aboutme/alan-g-jewelers/_ (http://members.ebay.com/aboutme/alan-g-jewelers/) " 
target=_blank><IMG height=8 
src="_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_ (http://pics.ebaystatic.com/aw/pics/aboutme-small.gif) " 
width=23 border=0> </A></FONT></TD></TR></TBODY></TABLE>
<DIV style="WIDTH: 338px; HEIGHT: 521px" align=center>
<TABLE height=1 width=365 border=0>
<TBODY>
<TR>
<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, 
sans-serif" vAlign=top width=359 height=18><FONT 
color=black> *************************************************</FONT><FONT face=Verdana 
size=2> </FONT></TD></TR>
<TR>
<TD vAlign=top width=359 height=778><FONT 
color=#0000ff> </FONT>*<FONT color=#0000ff>*Diamond Stud Earring**  </FONT>
<p><FONT face=Verdana size=2>This auction is for a <FONT 
color=#008080><B><I>BRAND NEW
</I></B></FONT>DIAMOND 
STUD EARRING.  </FONT></p>
<P align=justify><FONT face=Verdana size=2>We use <B><U><FONT 
color=#008080>14kt white gold</FONT></U></B>
for all our diamond studs.  Diamond Studs are also available in 
yellow gold at no additional charge.  </FONT></P>
<P align=justify><FONT face=Verdana size=2>The diamond has an excellent 
polish 
and symmetry and is simply incredible.  It is clear and bright with 
exceptional brilliance and fire.  The clarity is truly amazing, and 
this 
diamond sparkles immensely, the shape and cut are perfect.  The 
diamond is 
gauged and measured for the best fit in this solitaire pendant.</FONT></P>
<P align=justify><FONT face=Verdana size=2>Please email us with your 
questions or
comments before you bid on an item.  The highest bidder will win this 
beauty.  Bid with full confidence. </FONT></P>
<P><FONT color=#ff0000>These diamonds are all guaranteed to be 100% 
natural, with no enhancements or treatments.  The gemstones are  
guaranteed to be 100% natural, with no enhancements or treatments.  All 
items have been examined by a GIA gemologist.</FONT></P>
<P><font face="Arial" size="3"><FONT color=black>Descriptions of quality 
are inherently subjective. The quality descriptions we provide, are to the 
best of our certified gemologists ability, and are her 
honest opinion. Disagreements with quality descriptions may occur.  
 </FONT>Appraisal value is given at high retail value for insurance purposes 
only.  Appraisal value is subjective and may vary from one gemologist 
to another. 
<FONT color=black>Diamond grading is subjective and may vary greatly. If 
the lowest color or clarity grades we specify are determined to be more than 
one grade lower than indicated. you may return the item for a full refund 
less shipping and insurance. 
All diamonds are set perfectly, buyer is responsible for lost diamonds or 
gems.</FONT></font></P></TD></TR>
<TR>
<TD vAlign=top width=359 height=33><center>
<p>
 </p>
</center> </TD></TR></TBODY></TABLE></DIV></TD>
<TD width=365 height=1>
<TABLE height=587 cellSpacing=1 cellPadding=1 width=389 align=center 
border=0>
<TBODY>
<TR>
<TD width=414 height=212><IMG height=325 src="'.$watchImage.'" width=400></TD></TR>
<TR>
<TD width=414 height=259>
<FONT face=Verdana size=2>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<FORM name=orderform 
action=http://www.ewebcart.com/cgi-bin/cart.pl?add_items=1 method=post>
<TBODY>
<TR>
<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, 
sans-serif" vAlign=top width="100%"><FONT 
color=#0000ff>**************************************************</FONT><FONT face=Verdana 
size=2> </FONT></TD></TR></FORM></TBODY></TABLE></FONT>
<TABLE height=482 width=354 align=center border=0>
<TBODY>
<TR>
<TD vAlign=center width=348 background=topbk.jpg bgColor=black height=20>
<P align=center><B><FONT face="Georgia, Times New Roman, Times, serif" 
color=white size=2>Your Free Gift</FONT></B></P></TD></TR>
<TR>
<TD vAlign=top width=348 height=454>
<UL>
<LI><FONT face=Verdana size=2>Jewelry Box </FONT></LI>
<LI><FONT face=Verdana size=2>guaranteed to be 100% genuine 
diamond</FONT></LI>
<LI><FONT face=Verdana size=2>guaranteed to be 100% genuine 14kt 
GOLD</FONT></LI>
<LI><FONT face=Verdana size=2>Free appraisal for the estimated retail 
value of the item with every purchase. </FONT></LI>
<LI><FONT face=Verdana,Arial color=#000000 size=2>Items will be shipped 
the same day as payment received.  (shipping cut off time is 12:00 PM 
pacific standard time)</FONT> 
<P>Alan G. Jewelers has been dedicated to excellent customer satisfaction 
and lowest prices in the jewelry business for nearly 20 years. We are a 
direct diamond importer and all of our diamonds and gemstones are guaranteed 
to appraise for twice the amount of purchase price. Our merchandise is being 
offered on EBAY in order to provide the same exceptional quality and value 
to the general public. <FONT color=#ff0000>These diamonds are all 
guaranteed to be natural, with no enhancements or treatments.</FONT> Our goal is to 
reach the highest customer satisfaction rate possible. We welcome the 
opportunity to serve you.<BR></FONT></B></FONT></P>
<P></P>
<P><FONT face=Verdana color=#ff0000 size=4>Please review our feedback for 
your Confidence.  </FONT></P><P><font face="Verdana" 
color="#ff0000" size="4">We
guarantee all our items to appraise higher than your purchase price. 
</font> <FONT face=Arial size=2><A 
href="_http://members.ebay.com/aboutme/alan-g-jewelers/_ (http://members.ebay.com/aboutme/alan-g-jewelers/) " 
target=_blank><IMG height=8 
src="_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_ (http://pics.ebaystatic.com/aw/pics/aboutme-small.gif) " width=23 
border=0> </A></FONT></P></LI></UL>
<P> </P></TD></TR></TBODY></TABLE></TD></TR>
<TR>
<TD width=414 height=259>
<P align=center><FONT face="Arial, Helvetica, sans-serif" color=#000033 
size=3><BR></FONT><FONT face=Verdana>BID WITH CONFIDENCE!</FONT> </P>
<P align=center><I><B><FONT color=#008080 size=5>PLATINUM POWER 
SELLER</FONT></B></I></P>
<P dir=rtl align=center><font color="#800000" face="Verdana" size="4">Alan 
G Jewelers Guarantees all our <BR>diamonds to be 100% natural</font><FONT 
face=Verdana size=2><FONT color=#800000> <BR></FONT></P></FONT>
<p> </TD></TR>
<TR>
<TD width=414 height=88>
<center>
<p>
 </p>
</center>
<p> </TD></TR></TBODY></TABLE></TD>


<TR vAlign=top align=left>
<TD width=617 colSpan=2 height=369> 

<P> <U><B><FONT face=Verdana size=3>About us</FONT></B></U> </P>
<P class=text><FONT face=Verdana size=2>We invite you to read our <A 
href="_http://members.ebay.com/aboutme/alan-g-jewelers/_ 
(http://members.ebay.com/aboutme/alan-g-jewelers/) " target=_blank><IMG height=8 
src="_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_ 
(http://pics.ebaystatic.com/aw/pics/aboutme-small.gif) " width=23 border=0> </A></FONT>page to obtain 
information on: 
<UL type=circle>
<LI>
<P class=text>Alan G Jewelers</P></LI>
<LI>
<P class=text>Store Policy</P></LI>
<LI>
<P class=text>Shipping </P></LI>
<LI>
<P class=text>Return Policy</P></LI></UL>
<P class=fontblack><U><B><FONT face=Verdana size=3>Payment Information 
</FONT></B></U></P>
<P align=justify><FONT face=Verdana size=2>We accept <FONT 
color=red>ELECTRONIC 
BANK Wire Transfer,</FONT> and <FONT color=red>PAYPAL.</FONT></FONT></P>
<P align=justify><FONT face=Verdana size=2><IMG height=34 
src="_http://images.andale.com/f2/103/108/10035456/1054817181174_creditcard.jpg_ 
(http://images.andale.com/f2/103/108/10035456/1054817181174_creditcard.jpg) " 
width=379 border=0></FONT> <IMG height=24 
src="_http://pics.ebaystatic.com/aw/pics/paypal/iconEcheck.gif_ 
(http://pics.ebaystatic.com/aw/pics/paypal/iconEcheck.gif) " width=50 border=0></P>

<p> <U><B><FONT face=Verdana size=3>Helpful Information 
</FONT></B></U>
</p>
<P class=text><FONT face=Verdana size=2>GIA stands for Gemological 
Institute of America and EGL stands for European Gemological Laboratory. GIA and 
EGL certification are prepared by a third independent party not affiliated 
to Alan G Jewelers for your protection. The certifications state the color 
and clarity of diamonds greater than .50cts. They are both well respected in 
the jewelry industry. If you need any more information regarding these 
laboratories, you may visit EGL at <A 
href="_http://www.eglusa.com/customerlogin.html">www.eglusa.com</a> </FONT>
<P class=text><U><B><FONT face=Verdana>Satisfied Client</FONT></B></U><P 
class=text>We
strive for the highest customer satisfaction in our industry.  Our
feedback proves that to you.  If you have any questions, please ask
before you bid.<FONT face=Verdana size=2>
<P class=fontblack><U><B><FONT face=Verdana size=3>Clarity 
</FONT></B></U></P>
<P align=justify><FONT face=Verdana size=2>The following table explains 
the diamond clarity (inside the diamond):<BR>
<P>
<TABLE width="80%" border=1>
<TBODY>
<TR>
<TD align=middle><FONT face=Arial color=#586479 size=1>IF</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>VVS1</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>VVS2</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>VS1</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>VS2</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>SI1</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>SI2</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>SI3</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>I1</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>I2</FONT> </TD>
<TD align=middle><FONT face=Arial color=#586479 size=1>I3</FONT> </TD></TR>
<TR>
<TD align=middle><FONT face=Arial color=#586479 size=1>FLAWLESS</FONT> 
</TD>
<TD align=middle colSpan=2><FONT face=Arial color=#586479 size=1>EXTREMELY 
DIFFICULT TO SEE INCLUSIONS UNDER 10x MAGNIFICATION</FONT> </TD>
<TD align=middle colSpan=2><FONT face=Arial color=#586479 size=1>DIFFICULT 
TO SEE INCLUSIONS UNDER 10x MAGNIFICATION</FONT> </TD>
<TD align=middle colSpan=3><FONT face=Arial color=#586479 
size=1>INCLUSIONS VISIBLE UNDER 10X MAGNIFICATION </FONT></TD>
<TD align=middle colSpan=3><FONT face=Arial color=#586479 
size=1>INCLUSIONS VISIBLE TO NAKED EYE</FONT> </TD></TR></TBODY></TABLE>
<P>
<P class=fontblack><U><B><FONT face=Verdana size=3>Color 
</FONT></B></U></P>
<P align=justify><FONT face=Verdana size=2></FONT></P></FONT></FONT>
<TR>
<TD class=basic10 colSpan=2 height=394>While many diamonds appear 
colorless, or white, they may actually have subtle yellow or brown tones that can 
be detected when comparing diamonds side by side. Diamonds were formed under 
intense heat and pressure, and traces of other elements may have been 
incorporated into their atomic structure accounting for the variances in color. 
<BR><BR>Diamond color grades start at D and continue down through the 
alphabet. Colorless diamonds, graded D, are extremely rare and very valuable. 
The closer a diamond is to being colorless, the more valuable and rare it 
is. <BR><BR>The color of a diamond is graded with the diamond upside down 
before it is set in a mounting. The first three colors D, E, F are often 
called collection color. The subtle changes in collection color are so minute 
that it is difficult to identify them in the smaller sizes. Although the 
presence of color makes a diamond less rare and valuable, some diamonds come 
out of the ground in vivid "fancy" colors - well defined reds, blues, pinks, 
greens, and bright yellows. These are highly priced and extremely 
rare.<BR><BR>
<DIV align=center><IMG height=200 
src="'.$colorImage.'" width=600> </DIV></TD></TR></TBODY></TABLE>
<DIV></DIV></TD></TR></TBODY></TABLE><!-- End Description 
--></DIV></TD></TR></TBODY></TABLE></TD></TR></TABLE>
<CENTER>
 </CENTER>
</DIV>
<DIV> </DIV>
<DIV> </DIV></FONT></BODY></HTML>';*/

$studDetail = '<!-- Begin Description -->
<table cellpadding="0" cellspacing="28" width="100%">
<tbody>
<tr>
<td valign="top"><!-- Begin Description -->
<table align="center" border="0" width="598"> <tbody>
<tr> <td align="middle" width="626">
<marquee><font face="Verdana" size="5"><b>WELCOME TO
ALAN G, YOUR SOURCE FOR CERTIFIED GIA & EGL
DIAMONDS</b></font></marquee>
<p>
<marquee><font face="Verdana" size="3"><b>
/ (213)623-1456</b></font></marquee>
<marquee><a href="http://vi.ebaydesc.com/ws/_mailto:alangjewelers@aol.com?subject=ebay_%20%28mailto:alangjewelers@aol.com?subject=ebay%29%20auction"&gt;alangjewelers@aol.com&lt;/a&gt;&lt;/marquee&gt;
<br>
</p>
</td>
</tr>
<tr>
<td align="middle" width="626"><img src="http://www.directloosediamonds.com/images/upperbar02.jpg" border="0" width="900" height="99"></td>
</tr>
<tr>
<td valign="top" width="626" height="2309">
<div align="center">
<table border="0" width="99%" height="2479">
<tbody>
<tr>
<td align="right" valign="top" width="99%" height="1457">
<table align="center" border="0" width="625" height="1">
<tbody>
<tr align="left" valign="top">
<td width="252" height="1">
<table border="0" cellpadding="1" cellspacing="1" width="364" height="220">
<tbody>
<tr>
<td align="middle" bgcolor="black" width="360" height="17"><b><font color="#ffffff" face="Georgia,
Times New Roman, Times, serif" size="2">Information</font></b></td>
</tr>
<tr>
<td width="360" height="18">ITEM
NUMBER: '.$detail['stock_number'].'</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="1">METAL:
14KT WHITE GOLD</td>
</tr>
<tr>
<td bgcolor="aqua" width="360" height="15">ITEM
INFO: CERTIFICATE APPRAISAL</td>
</tr>
<tr>
<td width="360" height="18">WEIGHT
OF DIAMOND: '.$detail['carat'].' CARATS</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">CLARITY:
VS2-SI1 (NATURAL CLARITY)</td>
</tr>
<tr>
<td width="360" height="21">COLOR:
H-I (NATURAL COLOR)</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">POLISH:
EXCELLENT TO GOOD</td>
</tr>
<tr>
<td width="360" height="21">SYMMETRY:
EXCELLENT TO GOOD</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">CUT:
EXCELLENT TO GOOD</td>
</tr>
<tr>
<td width="360" height="19">NUMBER
OF DIAMONDS: 2 INDIVIDUAL</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">DIAMOND
SETTING: 4 PRONG</td>
</tr>
<tr>
<td bgcolor="#ffffff" width="360" height="18">CONDITION:
100% BRAND NEW</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">ESTIMATED
RETAIL VALUE : $'.$retailPrice.'</td>
</tr>
<tr>
<td bgcolor="yellow" width="360" height="18">OUR
PRICE: <font color="#ff0000">$'.$price.'</font>
& NO RESERVE <font face="Arial" size="2"><a href="http://vi.ebaydesc.com/ws/_http://members.ebay.com/aboutme/alan-g-jewelers/_%20(http://members.ebay.com/aboutme/alan-g-jewelers/)" target="_blank"><img src="http://vi.ebaydesc.com/ws/_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_%20(http://pics.ebaystatic.com/aw/pics/aboutme-small.gif)" border="0" width="23" height="8"></a></font></td>
</tr>
</tbody>
</table>
<div style="width: 365; height: 945" align="center">
<table border="0" width="365" height="1">
<tbody>
<tr>
<td style="font-size: 11px; font-family:
Verdana,Arial,Helvetica,sans-serif;" valign="top" width="359" height="18"><font color="black">*************************************************</font></td>
</tr>
<tr>
<td valign="top" width="359" height="778">*<font color="#000000">*Diamond
Stud Earring**</font>
<p><font face="Verdana" size="2">This
auction is for a <font color="#008080"><b><i>BRAND
NEW </i></b></font>DIAMOND
STUD EARRING.</font></p>
<p align="justify"><font face="Verdana" size="2">We
use <b><u><font color="#008080">14kt
white gold</font></u></b>
for all our diamond studs.
Diamond Studs are also
available in yellow gold
at no additional charge.</font></p>
<p align="justify"><font face="Verdana" size="2">Our
diamonds have an excellent
polish and symmetry and are
simply incredible. They are
clear and bright with
exceptional brilliance and
fire. The clarity is truly
amazing, and this diamond
sparkles immensely, the
shape and cut are perfect.
They are gauged and
measured for the best fit.</font></p>
<p align="justify"><font face="Verdana" size="2">Please
email us with your
questions or comments
before you bid on an item.
The highest bidder will
win this beauty. Bid with
full confidence.</font></p>
<p><font color="#ff0000">These
diamonds are all
guaranteed to be 100%
natural, with no
enhancements or
treatments. The gemstones
are guaranteed to be 100%
natural, with no
enhancements or
treatments. All items have
been examined by a GIA
gemologist.</font></p>
<p><font face="Arial" size="3"><font color="black">Descriptions
of quality are inherently
subjective. The quality
descriptions we provide,
are to the best of our
certified gemologists
ability, and are her
honest opinion.
Disagreements with quality
descriptions may occur. </font>Appraisal
value is given at high
retail value for insurance
purposes only. Appraisal
value is subjective and
may vary from one
gemologist to another. <font color="black">Diamond
grading is subjective and
may vary greatly. If the
lowest color or clarity
grades we specify are
determined to be more than
one grade lower than
indicated. you may return
the item for a full refund
less shipping and
insurance. All diamonds
are set perfectly, buyer
is responsible for lost
diamonds or gems.</font></font></p>
</td>
</tr>
<tr>
<td valign="top" width="359" height="33"><center>
<p> </p>
<p> </p>
<p> </p>
<p> </p>
</center></td>
</tr>
</tbody>
</table>
</div>
</td>
<td width="365" height="1">
<table align="center" border="0" cellpadding="1" cellspacing="1" width="389" height="538">
<tbody>
<tr>
<td width="414" height="212"><img src="'.$watchImage.'" width="400" height="325"></td>
</tr>
<tr>
<td width="414" height="259"><font face="Verdana" size="2">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<form target="_blank" name="orderform" action="http://www.ewebcart.com/cgi-bin/cart.pl?add_items=1" method="post">
</form>
<tbody>
<tr>
<td style="font-size: 11px; font-family:
Verdana,Arial,Helvetica,sans-serif;" valign="top" width="100%"><font color="#0000ff">**************************************************</font></td>
</tr>
</tbody>
</table>
</font>
<table align="center" border="0" width="354" height="482">
<tbody>
<tr>
<td background="http://vi.ebaydesc.com/ws/topbk.jpg" bgcolor="black" valign="center" width="348" height="20">
<p align="center"><b><font color="white" face="Georgia, Times New Roman,
Times, serif" size="2">Your Free Gift</font></b></p>
</td>
</tr>
<tr>
<td valign="top" width="348" height="454">
<ul>
<li><font face="Verdana" size="2">Jewelry
Box</font></li>
<li><font face="Verdana" size="2">guaranteed
to be 100%
genuine diamond</font></li>
<li><font face="Verdana" size="2">guaranteed
to be 100%
genuine 14kt
GOLD</font></li>
<li><font face="Verdana" size="2">Free
appraisal for
the estimated
retail value of
the item with
every purchase.</font></li>
<li><font color="#000000" face="Verdana,Arial" size="2">Items
will be shipped
the same day as
payment
received.
(shipping cut
off time is
12:00 PM pacific
standard time)</font>
<p>Alan G.
Jewelers has
been dedicated
to excellent
customer
satisfaction and
lowest prices in
the jewelry
business for
nearly 20 years.
We are a direct
diamond importer
and all of our
diamonds and
gemstones are
guaranteed to
appraise for
twice the amount
of purchase
price. Our
merchandise is
being offered on
EBAY in order to
provide the same
exceptional
quality and
value to the
general public. <font color="#ff0000">These
diamonds are all
guaranteed to be
natural, with no
enhancements or
treatments.</font>
Our goal is to
reach the
highest customer
satisfaction
rate possible.
We welcome the
opportunity to
serve you.<br>
</p>
<p> </p>
<p><font color="#ff0000" face="Verdana" size="4">Please
review our
feedback for
your Confidence.</font></p>
<p><font color="#ff0000" face="Verdana" size="4">We
guarantee all
our items to
appraise higher
than your
purchase price. </font><font face="Arial" size="2"><a href="http://vi.ebaydesc.com/ws/_http://members.ebay.com/aboutme/alan-g-jewelers/_%20(http://members.ebay.com/aboutme/alan-g-jewelers/)" target="_blank"><img src="http://vi.ebaydesc.com/ws/_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_%20(http://pics.ebaystatic.com/aw/pics/aboutme-small.gif)" border="0" width="23" height="8"></a></font></p>
</li>
</ul>
<p> </p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td width="414" height="259">
<p align="center"><font color="#000033" face="Arial, Helvetica,
sans-serif" size="3"><br>
</font><font face="Verdana">BID
WITH CONFIDENCE!</font></p>
<p align="center"><i><b><font color="#008080" size="5">PLATINUM
POWER SELLER</font></b></i></p>
<p dir="rtl" align="center"><font color="#800000" face="Verdana" size="4">Alan
G Jewelers Guarantees all
our<br>
diamonds to be 100% natural</font><font color="#800000" face="Verdana" size="2"><br>
</font></p>
<p> </p>
</td>
</tr>
<tr>
<td width="414" height="39"></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr align="left" valign="top">
<td colspan="2" width="617" height="369">
<p><u><b><font face="Verdana" size="3">About
us</font></b></u></p>
<p class="text"><font face="Verdana" size="2">We
invite you to read our <a href="http://vi.ebaydesc.com/ws/_http://members.ebay.com/aboutme/alan-g-jewelers/_%20(http://members.ebay.com/aboutme/alan-g-jewelers/)" target="_blank"><img src="http://vi.ebaydesc.com/ws/_http://pics.ebaystatic.com/aw/pics/aboutme-small.gif_%20(http://pics.ebaystatic.com/aw/pics/aboutme-small.gif)" border="0" width="23" height="8">
</a></font>page to obtain
information on:</p>
<ul type="circle">
<li>
<p class="text">Alan G Jewelers</p>
</li>
<li>
<p class="text">Store Policy</p>
</li>
<li>
<p class="text">Shipping</p>
</li>
<li>
<p class="text">Return Policy</p>
</li>
</ul>
<p class="fontblack"><u><b><font face="Verdana" size="3">Payment
Information</font></b></u></p>
<p align="justify"><font face="Verdana" size="2">We
accept <font color="red">ELECTRONIC
BANK Wire Transfer, VISA,
MASTERCARD, DISCOVER, AMEX </font>and
<font color="red">PAYPAL.</font></font></p>
<p align="justify"> </p>
<p><u><b><font face="Verdana" size="3">Helpful
Information</font></b></u></p>
<p class="text"><font face="Verdana" size="2">GIA
stands for Gemological Institute of
America and EGL stands for European
Gemological Laboratory. GIA and EGL
certification are prepared by a
third independent party not
affiliated to Alan G Jewelers for
your protection. The certifications
state the color and clarity of
diamonds greater than .50cts. They
are both well respected in the
jewelry industry. If you need any
more information regarding these
laboratories, you may visit EGL at <a href="http://vi.ebaydesc.com/ws/_http://www.eglusa.com/customerlogin.html">www.eglusa.com</a></font></p>;
<p class="text"><u><b><font face="Verdana">Satisfied
Client</font></b></u></p>
<p class="text">We aim for the
highest customer satisfaction in our
industry. Our feedback speaks for
itself. If you have any
questions, please ask before you
bid. </p>
<p class="fontblack"><font face="Verdana" size="2"><u><b><font face="Verdana" size="3">Clarity</font></b></u></font></p>
<p align="justify"><font face="Verdana" size="2">The
following table explains the diamond
clarity (inside the diamond):<br>
</font></p>
<p>
<table border="1" width="80%">
<tbody>
<tr>
<td align="middle"><font color="#586479" face="Arial" size="1">IF</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">VVS1</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">VVS2</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">VS1</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">VS2</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">SI1</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">SI2</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">SI3</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">I1</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">I2</font></td>
<td align="middle"><font color="#586479" face="Arial" size="1">I3</font></td>
</tr>
<tr>
<td align="middle"><font color="#586479" face="Arial" size="1">FLAWLESS</font></td>
<td colspan="2" align="middle"><font color="#586479" face="Arial" size="1">EXTREMELY
DIFFICULT TO SEE INCLUSIONS
UNDER 10x MAGNIFICATION</font></td>
<td colspan="2" align="middle"><font color="#586479" face="Arial" size="1">DIFFICULT
TO SEE INCLUSIONS UNDER 10x
MAGNIFICATION</font></td>
<td colspan="3" align="middle"><font color="#586479" face="Arial" size="1">INCLUSIONS
VISIBLE UNDER 10X
MAGNIFICATION</font></td>
<td colspan="3" align="middle"><font color="#586479" face="Arial" size="1">INCLUSIONS
VISIBLE TO NAKED EYE</font></td>
</tr>
</tbody>
</table>
<p> </p>
<p class="fontblack"><font face="Verdana" size="2"><u><b><font face="Verdana" size="3">Color</font></b></u></font></p>
<p align="justify"> </p>
</td>
</tr>
<tr>
<td class="basic10" colspan="2" height="394">While
many diamonds appear colorless, or
white, they may actually have subtle
yellow or brown tones that can be
detected when comparing diamonds
side by side. Diamonds were formed
under intense heat and pressure, and
traces of other elements may have
been incorporated into their atomic
structure accounting for the
variances in color.<br>
<br>
Diamond color grades start at D and
continue down through the alphabet.
Colorless diamonds, graded D, are
extremely rare and very valuable.
The closer a diamond is to being
colorless, the more valuable and
rare it is.<br>
<br>
The color of a diamond is graded
with the diamond upside down before
it is set in a mounting. The first
three colors D, E, F are often
called collection color. The subtle
changes in collection color are so
minute that it is difficult to
identify them in the smaller sizes.
Although the presence of color makes
a diamond less rare and valuable,
some diamonds come out of the ground
in vivid "fancy" colors -
well defined reds, blues, pinks,
greens, and bright yellows. These
are highly priced and extremely
rare.<br>
<br>
<div align="center">
<img src="http://www.directloosediamonds.com/images/Color_Profile.jpg" width="600" height="200">
</div>
</td>
</tr>
</tbody>
</table>
<div>
</div>
</td>
</tr>
</tbody>
</table>
<!-- End Description
-->
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<div>
</div>
<div>
</div>
';

	//echo $studDetail;
	//die('ppt');
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($studDetail);
        } else {
            $requestArray['itemDescription'] = $studDetail;
        }
		
		$requestArray['storeCategoryId'] = $storeCategoryId;
		

		$requestArray['listingDuration'] = 'Days_7';
        $requestArray['startPrice']      = $price;//$detail['price'];
        $requestArray['buyItNowPrice']   = '0.0';
        //$requestArray['quantity']        = $detail['quantity'];
		$requestArray['quantity']        = '1';
		$requestArray['ItemSpecification'] = $this->getItemStudSpecification($detail);
		$requestArray['dispatchTime'] = '3';
		$requestArray['packageDepth'] = 3;
		$requestArray['packageLength'] = 12;
		$requestArray['packageWidth'] = 10;
		$requestArray['weightMajor'] = 1;
		$requestArray['weightMinor'] = 2;
		/*if ($requestArray['listingType'] == 'StoresFixedPrice') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for SIF
          $requestArray['listingDuration'] = 'GTC';
        }
        
        if ($listingType == 'Dutch') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for Dutch
        }*/

		$requestArray['itemID'] = $detail['ebayid'];
		//if(file_exists(config_item('base_path').'images/'.$detail['big_image']) && $detail['big_image'] !='' ) {
			$requestArray['image'] = $watchImage;//config_item('base_url').'images/'.$detail['big_image'];
		//}
		
		//print_R($requestArray);
		//$CI = & get_instance();
		//$CI->load->model('watchesmodel');
//die('tt');
		//if($action=='Add') {
		if($detail['ebayid']=='' || $detail['ebayid']==0) {	
			$itemID = $this->sendRequestEbay($requestArray, 'diamondStud');
		} else {
			//$itemID = $this->updateEbayItem($requestArray, 'diamondStud');
			$itemID = $this->updateRequestEbay($requestArray, 'diamondStud');
		}
		return $itemID;
	}

	function getAllStuds(){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."diamondstudearring
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

	function getItemStudSpecification($detail){
		
		$remArr = array(" ", "ct.", "ct tw");
		$total_carats = str_replace($remArr, "", $detail['total_carats']);

		$specification = '<ItemSpecifics> 
					  <NameValueList> 
						<Name>Style</Name> 
						<Value>Stud</Value>
					  </NameValueList> 
					  <NameValueList> 
						<Name>Main Stone</Name> 
						<Value>Diamond</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Metal</Name> 
						<Value>White Gold</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Main Stone Color</Name> 
						<Value>White</Value> 
					  </NameValueList>
					<NameValueList>
						  <Name>Metal Purity</Name> 
						  <Value>14k (Solid, Unplated)</Value> 
					  </NameValueList>
					  <NameValueList> 
						<Name>Main Stone Treatment</Name> 
						<Value>Not Enhanced</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Condition</Name> 
						<Value>New</Value> 
					  </NameValueList> 
					 <NameValueList> 
						<Name>Carat Total Weight</Name> 
						<Value>'.$this->getEbayCarat($detail['carat']).'</Value> 
					  </NameValueList> 
				</ItemSpecifics>';
					  
		return $specification;

	}
	function getEbayCarat($carat) {
		if($carat <= '.29') {
			$value = '0.29 &amp; Under';
		} else if($carat >= 0.30 && $carat < 0.46) {
			$value = '0.30 - 0.45';
		} else if($carat >= 0.46 && $carat < 0.70) {
			$value = '0.46 - 0.69';
		} else if($carat >= .70 && $carat < .90) {
			$value = '0.70 - 0.89';
		} else if($carat >= .90 && $carat < 1.40) {
			$value = '0.90 - 1.39';
		} else if($carat >= 1.40 && $carat < 1.80) {
			$value = '1.40 - 1.79';
		} else if($carat >= 1.80 && $carat < 2.80) {
			$value = '1.80 - 2.79';
		} else if($carat >= 2.80 && $carat < 3.80) {
			$value = '2.80 - 3.79';
		} else if($carat >= 3.80 && $carat <= 6 ) {
			$value = '3.80 - 6.00';
		} else  {
			$value =$carat;
		}
		
		return $value;
	}
	function sendRequestEbay($requestArray, $section='watches') {
	
		global $userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel;
		include_once(config_item('base_path').'system/application/helpers/eBaySession.php');
		include_once(config_item('base_path').'system/application/helpers/keys.php');
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'AddItem';
		//echo 'devid'.$devID;
		///Build the request Xml string
		$requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>en_US</ErrorLanguage>';
		$requestXmlBody .= "<Version>$compatabilityLevel</Version>";
		$requestXmlBody .= '<Item>';
		$requestXmlBody .= '<Site>US</Site>';
		$requestXmlBody .= '<PrimaryCategory>';
		$requestXmlBody .= "<CategoryID>".$requestArray['primaryCategory']."</CategoryID>";
		$requestXmlBody .= '</PrimaryCategory>';
		$requestXmlBody .= '<PrivateListing>true</PrivateListing>';
		//$min = $requestArray['startPrice'] - 1;
		$min = round($requestArray['startPrice'] * .85);
		$requestXmlBody .= '<ListingDetails>
								<ConvertedBuyItNowPrice currencyID="USD">0.00</ConvertedBuyItNowPrice>
								<ConvertedStartPrice currencyID="USD">'.$requestArray['startPrice'].'</ConvertedStartPrice>
								<ConvertedReservePrice currencyID="USD">0.0</ConvertedReservePrice>
								<MinimumBestOfferPrice currencyID="USD">'.$min.'</MinimumBestOfferPrice>
							</ListingDetails>';
		//$requestXmlBody .= '<ConditionID>1000</ConditionID>';
		/*$requestXmlBody .= '<AttributeSetArray> 
							  <AttributeSet attributeSetID="1952"> 
								<Attribute attributeID="10244"> 
								  <Value> 
									<ValueID>10425</ValueID> 
								  </Value> 
								</Attribute> 
							  </AttributeSet> 
							</AttributeSetArray>';*/
		$requestXmlBody .= $requestArray['ItemSpecification'];
		$requestXmlBody .= "<BuyItNowPrice currencyID=\"USD\">0.00</BuyItNowPrice>";
		$requestXmlBody .= '<Country>US</Country>';
		$requestXmlBody .= '<Currency>USD</Currency>';
		$requestXmlBody .= "<ListingDuration>".$requestArray['listingDuration']."</ListingDuration>";
        $requestXmlBody .= "<ListingType>".$requestArray['listingType']."</ListingType>";
		$requestXmlBody .= '<Location><![CDATA[Los Angeles, CA, 90013]]></Location>';
		$requestXmlBody .= '<PaymentMethods>AmEx</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>VisaMC</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>Discover</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>PayPal</PaymentMethods>';
		$requestXmlBody .= '<PayPalEmailAddress>alangjewelers@aol.com</PayPalEmailAddress>';
		$requestXmlBody .=  '<Storefront>  
								<StoreCategoryID>'.$requestArray['storeCategoryId'].'</StoreCategoryID>  
							</Storefront>';
		$requestXmlBody .= "<Quantity>".$requestArray['quantity']."</Quantity>";
		$requestXmlBody .= '<RegionID>0</RegionID>';
		$requestXmlBody .= "<StartPrice>".$requestArray['startPrice']."</StartPrice>";
		$requestXmlBody .= '<ShippingTermsInDescription>True</ShippingTermsInDescription>';
		$requestXmlBody .= "<Title>".substr($requestArray['itemTitle'],0,54)."</Title>";
		$requestXmlBody .= "<Description><![CDATA[".$requestArray['itemDescription']."]]></Description>";
		$requestXmlBody .= '<DispatchTimeMax>3</DispatchTimeMax>';
		$requestXmlBody .= '<BestOfferDetails>
								<BestOfferCount>1</BestOfferCount>
								<BestOfferEnabled>true</BestOfferEnabled>
							</BestOfferDetails>';
		/*$requestXmlBody .= '<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>Days_14</ReturnsWithinOption>
								<Description>If you are not satisfied, return the book for refund.</Description>
								<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
							</ReturnPolicy>
							<ShippingDetails>
							  <ShippingServiceOptions>
								<ShippingService>Freight</ShippingService>
								<FreeShipping>false</FreeShipping>
							  </ShippingServiceOptions>
							  <ShippingType>FreightFlat</ShippingType>
							</ShippingDetails>
							<PictureDetails> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
							 </PictureDetails>'; */
		$requestXmlBody .= '<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>Days_14</ReturnsWithinOption>
								<Description>PLEASE VISIT OUR EBAY STORE FOR A DETAILED RETURN POLICY.</Description> 
								  <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption> 
								  <ShippingCostPaidBy>Buyer</ShippingCostPaidBy> 
							</ReturnPolicy>
							<ShippingDetails>
								<ApplyShippingDiscount>false</ApplyShippingDiscount> 
								<CalculatedShippingRate>
									  <OriginatingPostalCode>90013</OriginatingPostalCode> 
									  <PackageDepth unit="in" measurementSystem="English">'.$requestArray['packageDepth'].'</PackageDepth> 
									  <PackageLength unit="in" measurementSystem="English">'.$requestArray['packageLength'].'</PackageLength> 
									  <PackageWidth unit="in" measurementSystem="English">'.$requestArray['packageWidth'].'</PackageWidth> 
									  <PackagingHandlingCosts currencyID="USD">5.99</PackagingHandlingCosts> 
									  <ShippingIrregular>false</ShippingIrregular> 
									  <ShippingPackage>PackageThickEnvelope</ShippingPackage> 
									  <WeightMajor unit="lbs" measurementSystem="English">'.$requestArray['weightMajor'].'</WeightMajor> 
									  <WeightMinor unit="oz" measurementSystem="English">'.$requestArray['weightMinor'].'</WeightMinor> 
									  <InternationalPackagingHandlingCosts currencyID="USD">9.99</InternationalPackagingHandlingCosts> 
								</CalculatedShippingRate>
								<SalesTax>
									<SalesTaxPercent>0.0</SalesTaxPercent>
									<ShippingIncludedInTax>false</ShippingIncludedInTax>
								</SalesTax>
								 <ShippingServiceOptions>
									<ShippingService>UPSGround</ShippingService>
									<ShippingServicePriority>1</ShippingServicePriority>
									<FreeShipping>true</FreeShipping>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>6</ShippingTimeMax>
									<FreeShipping>true</FreeShipping>
								</ShippingServiceOptions>
								<ShippingServiceOptions>
									<ShippingService>UPS3rdDay</ShippingService>
									<ShippingServicePriority>2</ShippingServicePriority>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>3</ShippingTimeMax>
								</ShippingServiceOptions>
								<ShippingServiceOptions>
									<ShippingService>UPS2ndDay</ShippingService>
									<ShippingServicePriority>3</ShippingServicePriority>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>2</ShippingTimeMax>
								</ShippingServiceOptions>
								<InternationalShippingServiceOption>
									<ShippingService>UPSStandardToCanada</ShippingService>
									<ShippingServicePriority>1</ShippingServicePriority>
									<ShipToLocation>CA</ShipToLocation>
								</InternationalShippingServiceOption>
								<InternationalShippingServiceOption>
									<ShippingService>UPSWorldWideExpedited</ShippingService>
									<ShippingServicePriority>2</ShippingServicePriority>
									<ShipToLocation>Europe</ShipToLocation>
									<ShipToLocation>Asia</ShipToLocation>
									<ShipToLocation>CA</ShipToLocation>
									<ShipToLocation>GB</ShipToLocation>
									<ShipToLocation>AU</ShipToLocation>
									<ShipToLocation>DE</ShipToLocation>
									<ShipToLocation>JP</ShipToLocation>
								</InternationalShippingServiceOption>
								<InternationalShippingServiceOption>
									<ShippingService>UPSWorldwideSaver</ShippingService>
									<ShippingServicePriority>3</ShippingServicePriority>
									<ShipToLocation>Europe</ShipToLocation>
									<ShipToLocation>Asia</ShipToLocation>
									<ShipToLocation>CA</ShipToLocation>
									<ShipToLocation>GB</ShipToLocation>
									<ShipToLocation>AU</ShipToLocation>
									<ShipToLocation>DE</ShipToLocation>
									<ShipToLocation>JP</ShipToLocation>
								</InternationalShippingServiceOption>
								<ShippingType>Calculated</ShippingType>
								<ThirdPartyCheckout>false</ThirdPartyCheckout>
								<TaxTable>
									<TaxJurisdiction>
										<JurisdictionID>CA</JurisdictionID>
										<SalesTaxPercent>9.75</SalesTaxPercent>
										<ShippingIncludedInTax>true</ShippingIncludedInTax>
									</TaxJurisdiction>
								</TaxTable>
						</ShippingDetails>
							<PictureDetails> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
							 </PictureDetails>'; 
		$requestXmlBody .= '</Item>';
		$requestXmlBody .= '</AddItemRequest>';
		//echo $requestXmlBody;
		//die('typ');
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		

		//echo '<pre>';
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		//print_R($responseXml);
		$responses = $responseDoc->getElementsByTagName("AddItemResponse");
        foreach ($responses as $response) {
           $acks = $response->getElementsByTagName("Ack");
           $ack   = $acks->item(0)->nodeValue;
          //echo "Ack = $ack <BR />\n";   // Success if successful
		}
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		
		//if there are error nodes
		//if($errors->length > 0)
		if($ack == 'Failure')
		{
			$status = '<P><B>eBay returned the following error(s):</B>';
			//display each error
			//Get error code, ShortMesaage and LongMessage
			$code     = $errors->item(0)->getElementsByTagName('ErrorCode');
			$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
			$longMsg  = $errors->item(0)->getElementsByTagName('LongMessage');
			//Display code and shortmessage
			$status .= '<P>'. $code->item(0)->nodeValue. ' : '. str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
			//if there is a long message (ie ErrorLevel=1), display it
			if(count($longMsg) > 0)
				$status .= '<BR>'.str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			echo $status;
		} else { //no errors
            
			//get results nodes
            $responses = $responseDoc->getElementsByTagName("AddItemResponse");
            foreach ($responses as $response) {
              $acks = $response->getElementsByTagName("Ack");
              $ack   = $acks->item(0)->nodeValue;
              //echo "Ack = $ack <BR />\n";   // Success if successful
              
              $endTimes  = $response->getElementsByTagName("EndTime");
              $endTime   = $endTimes->item(0)->nodeValue;
              //echo "endTime = $endTime <BR />\n";
              
              $itemIDs  = $response->getElementsByTagName("ItemID");
              $itemID   = $itemIDs->item(0)->nodeValue;
              // echo "itemID = $itemID <BR />\n";
              
              $linkBase = "http://cgi.ebay.com/ws/eBayISAPI.dll?ViewItem&item=";
              $status = "Item Added <a href=$linkBase" . $itemID . ">".$requestArray['itemTitle']."</a> <BR />";
			 
			  if($section == 'diamondStud') {
				$this->db->where('stock_number' , $requestArray['productID']);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'diamondstudearring',
			 		array(			  
			  				'ebayid'	=> $itemID,
			  				
						 ));
			  } else if($section == 'looseDiamond') {
				$this->db->where('lot' , $requestArray['lot']);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'products',
			 		array(			  
			  				'ebayid'	=> $itemID,
			  				
						 ));
			  }
              $feeNodes = $responseDoc->getElementsByTagName('Fee');
              foreach($feeNodes as $feeNode) {
                $feeNames = $feeNode->getElementsByTagName("Name");
                if ($feeNames->item(0)) {
                    $feeName = $feeNames->item(0)->nodeValue;
                    $fees = $feeNode->getElementsByTagName('Fee');  // get Fee amount nested in Fee
                    $fee = $fees->item(0)->nodeValue;
                    if ($fee > 0.0) {
                        if ($feeName == 'ListingFee') {
                          $status .= "<B>$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n"; 
                        } else {
                          $status .= "$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n";
                        }      
                    }  // if $fee > 0
                } // if feeName
              } // foreach $feeNode
            
            } // foreach response
            
		} // if $errors->length > 0
		echo $status;
	}

	function updateEbayItem($requestArray, $section='watches') {
//	echo config_item('base_path').'system/application/helpers/eBaySOAP.php';
		include config_item('base_path').'system/application/helpers/eBaySOAP.php';
	
		$config = parse_ini_file(config_item('base_path').'system/application/helpers/ebay.ini', true);

		$site = $config['settings']['site'];
		$compatibilityLevel = $config['settings']['compatibilityLevel'];

		$dev = $config[$site]['devId'];
		$app = $config[$site]['appId'];
		$cert = $config[$site]['cert'];
		$token = $config[$site]['authToken'];
		$location = $config[$site]['gatewaySOAP'];

		// Create and configure session
		$session = new eBaySession($dev, $app, $cert);
		$session->token = $token;
		$session->site = 0; // 0 = US;
		$session->location = $location;

		try {
	$client = new eBaySOAP($session);

	$PrimaryCategory = array('CategoryID' => $requestArray['primaryCategory']);

	/*$Item = array('ListingType' => $requestArray['listingType']
				  'Currency' => 'USD',
				  'Country' => 'US',
				  'PaymentMethods' => 'PaymentSeeDescription',
				  'RegionID' => 0,
				  'ListingDuration' => $requestArray['listingDuration'],
				  'Title' => $requestArray['itemTitle'],
				  'SubTitle' => 'The new item subtitle',
				  'Description' => $requestArray['itemDescription'],
				  'Location' => "San Jose, CA",
				  'Quantity' => $requestArray['quantity'],
				  'StartPrice' => $requestArray['startPrice'],
				  'BuyItNowPrice' => $requestArray['buyItNowPrice'],
				  'PrimaryCategory' => $PrimaryCategory,
				 );*/

	// Get it to confirm
	$params = array('Version' => $compatibilityLevel, 'ItemID' =>  $requestArray['itemID']);
	$results = $client->GetItem($params);
	print_r($results);

	if($results->Errors->ErrorCode == '17') {
	// Revise it and change the Title and raise the BuyItNowPrice
		$status = $this->sendRequestEbay($requestArray, $section);
	} else if($results->Errors) {
			$status = '<b>'.$results->Errors->ShortMessage.'<br>'.$results->Errors->LongMessage.'</b>';
	} else {
	$Item = array('Title' => $requestArray['itemTitle'],
				  'Description' => $requestArray['itemDescription'],
				  'Quantity' => $requestArray['quantity'],
				  'StartPrice' => $requestArray['startPrice'],
				  'BuyItNowPrice' => $requestArray['buyItNowPrice'],
				  );

	 
	$params = array('Version' => $compatibilityLevel, 
	                'Item' => $Item
	               );

	$results = $client->ReviseItem($params);
		//print_r($results);
		if($results->Errors) {
			// Revise it and change the Title and raise the BuyItNowPrice
			$status = '<b>'.$results->Errors->ShortMessage.'<br>'.$results->Errors->LongMessage.'</b>';
		} else {
			$status = '<b>Item Updated Successfully!</b>';
		}
	}
		echo $status;
	} catch (SOAPFault $f) {
		print $f; // error handling
	}
	}

	function updateRequestEbay($requestArray, $section='watches') {
		//print_r($requestArray);
		//die('tty');
		global $userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel;
		include_once(config_item('base_path').'system/application/helpers/eBaySession.php');
		include_once(config_item('base_path').'system/application/helpers/keys.php');
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'ReviseItem';
		//echo 'devid'.$devID;
		///Build the request Xml string
		$requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>en_US</ErrorLanguage>';
		$requestXmlBody .= "<Version>$compatabilityLevel</Version>";
		$requestXmlBody .= '<Item>';
		$requestXmlBody .= '<ItemID>'.$requestArray['itemID'].'</ItemID>';
		$requestXmlBody .=  '<Storefront>  
								<StoreCategoryID>'.$requestArray['storeCategoryId'].'</StoreCategoryID>  
							</Storefront>';
		//$requestXmlBody .= "<Quantity>".$requestArray['quantity']."</Quantity>";
		$requestXmlBody .= "<Title><![CDATA[".substr($requestArray['itemTitle'], 0, 54)."]]></Title>";
		$requestXmlBody .= "<Description><![CDATA[".$requestArray['itemDescription']."]]></Description>";

		$min = round($requestArray['startPrice'] * .85);
		$requestXmlBody .= '<ListingDetails>
								<ConvertedBuyItNowPrice currencyID="USD">0.00</ConvertedBuyItNowPrice>
								<ConvertedStartPrice currencyID="USD">'.$requestArray['startPrice'].'</ConvertedStartPrice>
								<ConvertedReservePrice currencyID="USD">0.0</ConvertedReservePrice>
								<MinimumBestOfferPrice currencyID="USD">'.$min.'</MinimumBestOfferPrice>
							</ListingDetails>';
		$requestXmlBody .= $requestArray['ItemSpecification'];//$requestArray['AttributeArray'];
		$requestXmlBody .= "<BuyItNowPrice currencyID=\"USD\">0.00</BuyItNowPrice>";
		$requestXmlBody .= "<StartPrice>".$requestArray['startPrice']."</StartPrice>";
		$requestXmlBody .= '<ShippingTermsInDescription>True</ShippingTermsInDescription>';
		$requestXmlBody .= '<BestOfferDetails>
								<BestOfferCount>1</BestOfferCount>
								<BestOfferEnabled>true</BestOfferEnabled>
							</BestOfferDetails>';
		$requestXmlBody .= '<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>Days_7</ReturnsWithinOption>
								<Description>PLEASE VISIT OUR EBAY STORE FOR A DETAILED RETURN POLICY.</Description> 
								  <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption> 
								  <ShippingCostPaidBy>Buyer</ShippingCostPaidBy> 
							</ReturnPolicy>
							<ShippingDetails>
								<ApplyShippingDiscount>false</ApplyShippingDiscount> 
								<CalculatedShippingRate>
									  <OriginatingPostalCode>90013</OriginatingPostalCode> 
									  <PackageDepth unit="in" measurementSystem="English">3</PackageDepth> 
									  <PackageLength unit="in" measurementSystem="English">12</PackageLength> 
									  <PackageWidth unit="in" measurementSystem="English">10</PackageWidth> 
									  <PackagingHandlingCosts currencyID="USD">5.99</PackagingHandlingCosts> 
									  <ShippingIrregular>false</ShippingIrregular> 
									  <ShippingPackage>PackageThickEnvelope</ShippingPackage> 
									  <WeightMajor unit="lbs" measurementSystem="English">1</WeightMajor> 
									  <WeightMinor unit="oz" measurementSystem="English">2</WeightMinor> 
									  <InternationalPackagingHandlingCosts currencyID="USD">9.99</InternationalPackagingHandlingCosts> 
								</CalculatedShippingRate>
								<SalesTax>
									<SalesTaxPercent>0.0</SalesTaxPercent>
									<ShippingIncludedInTax>false</ShippingIncludedInTax>
								</SalesTax>
								 <ShippingServiceOptions>
									<ShippingService>UPSGround</ShippingService>
									<ShippingServicePriority>1</ShippingServicePriority>
									<FreeShipping>true</FreeShipping>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>6</ShippingTimeMax>
									<FreeShipping>true</FreeShipping>
								</ShippingServiceOptions>
								<ShippingServiceOptions>
									<ShippingService>UPS3rdDay</ShippingService>
									<ShippingServicePriority>2</ShippingServicePriority>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>3</ShippingTimeMax>
								</ShippingServiceOptions>
								<ShippingServiceOptions>
									<ShippingService>UPS2ndDay</ShippingService>
									<ShippingServicePriority>3</ShippingServicePriority>
									<ExpeditedService>false</ExpeditedService>
									<ShippingTimeMin>1</ShippingTimeMin>
									<ShippingTimeMax>2</ShippingTimeMax>
								</ShippingServiceOptions>
								<InternationalShippingServiceOption>
									<ShippingService>UPSStandardToCanada</ShippingService>
									<ShippingServicePriority>1</ShippingServicePriority>
									<ShipToLocation>CA</ShipToLocation>
								</InternationalShippingServiceOption>
								<InternationalShippingServiceOption>
									<ShippingService>UPSWorldWideExpedited</ShippingService>
									<ShippingServicePriority>2</ShippingServicePriority>
									<ShipToLocation>Europe</ShipToLocation>
									<ShipToLocation>Asia</ShipToLocation>
									<ShipToLocation>CA</ShipToLocation>
									<ShipToLocation>GB</ShipToLocation>
									<ShipToLocation>AU</ShipToLocation>
									<ShipToLocation>DE</ShipToLocation>
									<ShipToLocation>JP</ShipToLocation>
								</InternationalShippingServiceOption>
								<InternationalShippingServiceOption>
									<ShippingService>UPSWorldwideSaver</ShippingService>
									<ShippingServicePriority>3</ShippingServicePriority>
									<ShipToLocation>Europe</ShipToLocation>
									<ShipToLocation>Asia</ShipToLocation>
									<ShipToLocation>CA</ShipToLocation>
									<ShipToLocation>GB</ShipToLocation>
									<ShipToLocation>AU</ShipToLocation>
									<ShipToLocation>DE</ShipToLocation>
									<ShipToLocation>JP</ShipToLocation>
								</InternationalShippingServiceOption>
								<ShippingType>Calculated</ShippingType>
								<ThirdPartyCheckout>false</ThirdPartyCheckout>
								<TaxTable>
									<TaxJurisdiction>
										<JurisdictionID>CA</JurisdictionID>
										<SalesTaxPercent>9.75</SalesTaxPercent>
										<ShippingIncludedInTax>true</ShippingIncludedInTax>
									</TaxJurisdiction>
								</TaxTable>
						</ShippingDetails>
							<PictureDetails> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
							 </PictureDetails>'; 
		$requestXmlBody .= '</Item>';
		$requestXmlBody .= '</ReviseItemRequest>';
		
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		//echo '<pre>';
		//print_r($responseXml);
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		
		$responses = $responseDoc->getElementsByTagName("ReviseItemResponse");
        foreach ($responses as $response) {
           $acks = $response->getElementsByTagName("Ack");
           $ack   = $acks->item(0)->nodeValue;
          //echo "Ack = $ack <BR />\n";   // Success if successful
		}
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		
		//if there are error nodes

		//if($errors->length > 0)
		if($ack == 'Failure')
		{	//echo '<br>'.die('xyz');
			foreach($errors AS $error) { 
				$SeverityCode     = $error->getElementsByTagName('SeverityCode');
				//echo '<br>'.$SeverityCode->item(0)->nodeValue;
				if($SeverityCode->item(0)->nodeValue=='Error') {
					$status = '<P><B>eBay returned the following error(s) while updating an item:</B>';
					//display each error
					//Get error code, ShortMesaage and LongMessage
					$code     = $error->getElementsByTagName('ErrorCode');
					$shortMsg = $error->getElementsByTagName('ShortMessage');
					$longMsg  = $error->getElementsByTagName('LongMessage');
					$errorCode = $code->item(0)->nodeValue;
					if($errorCode == 291) {
						$status = $this->sendRequestEbay($requestArray, $section);
					} else {
						//Display code and shortmessage
						$status .= '<P>'. $code->item(0)->nodeValue. ' : '. str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
						//if there is a long message (ie ErrorLevel=1), display it
						if(count($longMsg) > 0)
							$status .= '<BR>'.str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
					
					}
				}
			}
				echo $status;
		} else { //no errors
        
			//get results nodes
            $responses = $responseDoc->getElementsByTagName("ReviseItemResponse");
            foreach ($responses as $response) {
              $acks = $response->getElementsByTagName("Ack");
              $ack   = $acks->item(0)->nodeValue;
              //echo "Ack = $ack <BR />\n";   // Success if successful
              
              $endTimes  = $response->getElementsByTagName("EndTime");
              $endTime   = $endTimes->item(0)->nodeValue;
              //echo "endTime = $endTime <BR />\n";
              
              $itemIDs  = $response->getElementsByTagName("ItemID");
              $itemID   = $itemIDs->item(0)->nodeValue;
              // echo "itemID = $itemID <BR />\n";
              
              $linkBase = "http://cgi.ebay.com/ws/eBayISAPI.dll?ViewItem&item=";
              $status = "Item Updated <a href=$linkBase" . $requestArray['itemID'] . ">".$requestArray['itemTitle']."</a> <BR />";
			  
              $feeNodes = $responseDoc->getElementsByTagName('Fee');
              foreach($feeNodes as $feeNode) {
                $feeNames = $feeNode->getElementsByTagName("Name");
                if ($feeNames->item(0)) {
                    $feeName = $feeNames->item(0)->nodeValue;
                    $fees = $feeNode->getElementsByTagName('Fee');  // get Fee amount nested in Fee
                    $fee = $fees->item(0)->nodeValue;
                    if ($fee > 0.0) {
                        if ($feeName == 'ListingFee') {
                          $status .= "<B>$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n"; 
                        } else {
                          $status .= "$feeName :".number_format($fee, 2, '.', '')." </B><BR>\n";
                        }      
                    }  // if $fee > 0
                } // if feeName
              } // foreach $feeNode
            
            } // foreach response
            
			
		} // if $errors->length > 0
		echo $status;
	}
}
?>