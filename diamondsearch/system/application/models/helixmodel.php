<?php
$plus_one = array(
	        "olympic",
	        "aloni",
	        "asd",
	        "levydi",
	        "s&s bro",
	        "wfdiam",
	        "taly",
	        "illum"
		);
		
class Helixmodel extends  Model {
	function __construct(){
		parent::Model();
	}
	
	function saveinhelix($cols){
	  if(is_array($cols)){
	  	$lot 			= isset($cols[0]) ? $cols[0] : 0;
	  	$owner  		= isset($cols[1]) ? $cols[1] : 'NA';
	  	$shape  		= isset($cols[2]) ? $cols[2] : '';
	  	$carat  		= isset($cols[3]) ? $cols[3] : '0';
	  	$color  		= isset($cols[4]) ? $cols[4] : '';
	  	$clarity  		= isset($cols[5]) ? $cols[5] : '';
	  	$cut  			= isset($cols[6]) ? $cols[6] : '';
	  	$price  		= isset($cols[7]) ? $cols[7] : '250';
	  	$Rap  	    	= isset($cols[8]) ? $cols[8] : '0';
	  	$Cert  			= isset($cols[9]) ? $cols[9] : '';
	  	$Depth  		= isset($cols[10]) ? $cols[10] : '0';
	  	$TablePercent  	= isset($cols[11]) ? $cols[11] : 'NA';
	  	$Girdle  		= isset($cols[12]) ? $cols[12] : '';
	  	$Culet  		= isset($cols[13]) ? $cols[13] : '';
	  	$Polish  		= isset($cols[14]) ? $cols[14] : '';
	  	$Sym  			= isset($cols[15]) ? $cols[15] : '';
	  	$Flour  		= isset($cols[16]) ? $cols[16] : '';
	  	$Meas  			= isset($cols[17]) ? $cols[17] : '0';
	  	$Comment  		= isset($cols[18]) ? $cols[18] : '';
	  	$Stones  		= isset($cols[19]) ? $cols[19] : '';
	  	$Cert_n  		= isset($cols[20]) ? $cols[20] : '';
	  	$Stock_n    	= isset($cols[21]) ? $cols[21] : '';
	  	$Make    		= isset($cols[22]) ? $cols[22] : '';
	  	$Date    		= isset($cols[23]) ? $cols[23] : '';
	  	$City    		= isset($cols[24]) ? $cols[24] : '';
	  	$State   		= isset($cols[25]) ? $cols[25] : '';
	  	$Country   		= isset($cols[26]) ? $cols[26] : '';
	  	 
	  	 
	  	$Cert = strtoupper($Cert);
	  	$ratio = ( isset($ratio) && $ratio != null) ? $ratio : ' ';
	  	$price = $this->erdprice($price);
	  	
	  	 
	    	$isinsert = $this->db->insert($this->config->item('table_perfix').'helix_products',
					array('lot'    =>  $lot,
					       'owner' =>  $owner,
					       'shape' =>  $shape,
					       'carat' => $carat,
					       'color' => $color,
					       'clarity' => $clarity,
					       'cut'   => $cut,
					       'price' => $price,
					       'Rap'   => $Rap,
					       'Cert'  			=> $Cert,
					       'Depth' 			=> $Depth,
					       'TablePercent' 	=> $TablePercent,
					       'Girdle' 		=> $Girdle,
					       'Culet' 	=> $Culet,
					       'Polish' => $Polish,
					       'Sym' 	=> $Sym,
					       'Flour' 	=> $Flour,
					       'Meas' 	=> $Meas,
					       'Comment' 	=> $Comment,
					       'Stones' 	=> $Stones,
					       'Cert_n' 	=> $Cert_n,
					       'Stock_n' 	=> $Stock_n,
					       'Make' 	=> $Make,
					       'Date' 	=> $Date,
					       'City' 	=> $City,
					       'State' 	=> $State,
					       'Country' => $Country,
					       'ratio'  => $ratio,
					       'tbl'	=> config_item('base_url').'diamonds/search/true/true/false/false/false/'.$lot
						));
					if(!$isinsert){
						$isinsert = $this->db->insert('error',
							array('msg'	=> $lot . '-helix_products insert faild'));
							return true;
					}else return true;	
	    	
	   
	  }
	}
	
	
	function lotExist($lot = '99999')
	{
		    $sql = "SELECT * FROM ".$this->config->item('table_perfix')."helix_products WHERE lot= '$lot'";
            $query = $this->db->query($sql);
	        
            if($query->num_rows()){
				return true;
			}else {
				return false;
			}
	}
	function lotExistRedSeller($lot)
	{
		    $sql = "SELECT * FROM ".$this->config->item('table_perfix')."helix_productsredseller WHERE lot= '".trim($lot)."'";
           $query = $this->db->query($sql);
			$res=$query->result();
			if(count($res)>0){
				return true;
			}else {
			
				return false;
			}
	}
 	function fixhelix(){
		
		/// fix polish 
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Polish= 'GD' where Polish='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Polish= 'ID' where Polish='I'";
		$this->db->query($qry);
		
		/// fix fluro
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Flour= 'NO' where Flour='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Flour= 'MED' where Flour='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Flour= 'ST BLUE' where Flour='SB'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Flour= 'VST BLUE' where Flour='VSLB'";
		$this->db->query($qry);

		// fix SYMMETRY
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Sym= 'GD' where Sym='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Sym= 'ID' where Sym='I'";
		$this->db->query($qry);
		 
		// fix CULET
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'NO' where Culet='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'VS' where Culet='V'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'SM' where Culet='S'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'PN' where Culet='P'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'ME' where Culet='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set Culet= 'LR' where Culet='L'";
		$this->db->query($qry);
		
		// fix cut
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set cut= 'Good' where cut='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set cut= 'Very Good' where cut='VG'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set cut= 'Premium' where cut='EX'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set cut= 'Ideal' where cut='I'";
		$this->db->query($qry);
		
		
		// fix clarity 
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '0' where clarity='IF'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '1' where clarity='VVS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '2' where clarity='VVS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '3' where clarity='VS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '4' where clarity='VS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '5' where clarity='SI1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_products set clarity= '6' where clarity='SI2'";
		$this->db->query($qry);
		
		  
		$DELETE  = "DELETE FROM ". $this->config->item('table_perfix') ."helix_products WHERE ";
		$DELETE  .= " Cert  NOT IN('AGS', 'GIA')";
		$DELETE  .= " OR cut NOT IN('Good','Very Good','Premium','Ideal') "; 
		$DELETE  .= " OR clarity NOT IN('0','1','2','3','4','5','6') ";
		$DELETE  .= " OR Polish NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Sym NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Flour NOT IN('NO','FB','F','MB','MED','S','ST BLUE','VSL','VST BLUE') ";
		$DELETE  .= " OR Culet NOT IN('NO','VS','SM','PN','ME','LR') ";
		$DELETE  .= " OR price < 250";
		$DELETE  .= " OR color NOT IN('D','E','F','G','H','I','J') ";
		$DELETE  .= " OR sym NOT IN('EX','GD','ID','VG','F') ";
		$DELETE  .= " OR shape NOT IN('B','PR','R','E','AS','O','M','P','H','C') ";
		$this->db->query($DELETE);
		
		 
		 
		  
		
		
	}
		function fixRedhelix(){
		
		/// fix polish 
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Polish= 'GD' where Polish='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Polish= 'ID' where Polish='I'";
		$this->db->query($qry);
		
		/// fix fluro
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Flour= 'NO' where Flour='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Flour= 'MED' where Flour='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Flour= 'ST BLUE' where Flour='SB'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Flour= 'VST BLUE' where Flour='VSLB'";
		$this->db->query($qry);

		// fix SYMMETRY
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Sym= 'GD' where Sym='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Sym= 'ID' where Sym='I'";
		$this->db->query($qry);
		 
		// fix CULET
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'NO' where Culet='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'VS' where Culet='V'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'SM' where Culet='S'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'PN' where Culet='P'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'ME' where Culet='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set Culet= 'LR' where Culet='L'";
		$this->db->query($qry);
		
		// fix cut
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set cut= 'Good' where cut='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set cut= 'Very Good' where cut='VG'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set cut= 'Premium' where cut='EX'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set cut= 'Ideal' where cut='I'";
		$this->db->query($qry);
		
		
		// fix clarity 
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '0' where clarity='IF'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '1' where clarity='VVS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '2' where clarity='VVS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '3' where clarity='VS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '4' where clarity='VS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '5' where clarity='SI1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."helix_productsredseller set clarity= '6' where clarity='SI2'";
		$this->db->query($qry);
		
		  
		$DELETE  = "DELETE FROM ". $this->config->item('table_perfix') ."helix_productsredseller WHERE ";
		$DELETE  .= " Cert  NOT IN('AGS', 'GIA')";
		$DELETE  .= " OR cut NOT IN('Good','Very Good','Premium','Ideal') "; 
		$DELETE  .= " OR clarity NOT IN('0','1','2','3','4','5','6') ";
		$DELETE  .= " OR Polish NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Sym NOT IN('GD','VG','EX','ID','F') ";
		$DELETE  .= " OR Flour NOT IN('NO','FB','F','MB','MED','S','ST BLUE','VSL','VST BLUE') ";
		$DELETE  .= " OR Culet NOT IN('NO','VS','SM','PN','ME','LR') ";
		$DELETE  .= " OR price < 250";
		$DELETE  .= " OR color NOT IN('D','E','F','G','H','I','J') ";
		$DELETE  .= " OR sym NOT IN('EX','GD','ID','VG','F') ";
		$DELETE  .= " OR shape NOT IN('B','PR','R','E','AS','O','M','P','H','C') ";
		$this->db->query($DELETE);
		
		 
		 
		  
		
		
	}	
 
	private function ratio($measurement){
	        $R = explode('x',$this->measurement($measurement));
	        $ratio = ' ';
	        if(isset($R[1])){
	        if($R[1]!=0) $ratio = round($R[0]/$R[1],2);
	        }else $ratio = ' ';
	        return $ratio;
	}
	
	
	
	private function adjust($meas, $owner) {
		global $plus_one;
	
		$data = explode('x',$meas);
		$owner = strtolower($owner);
	
	
		if ( array_search($owner,$plus_one) !== false ){
	
			$data[0] += 0.01;
			
			$data[1] += 0.01;
			$data[2] += 0.01;
		//	print "Yump!";
		}
	
	
	
		return implode('x',$data);
	
	
	}
	
	private function measurement($measurement){
	        $measurement = str_replace("*","x",str_replace("-","x",str_replace("X","x",str_replace(" ","",str_replace("MM","",$measurement)))));
	return $measurement;
	}	
		
 
	
 
	
	function emptyhelix(){
		return $this->db->query('TRUNCATE TABLE '.config_item('table_perfix') . 'helix_products');
	}
	
	function gethelixinclude(){
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixinclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	function gethelixexclude(){
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixexclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	
	function savecurlurl($post){
	   $ret  = array();
       $ret['error'] = '';
       $ret['success'] = '';
       $helixinclude = trim(addslashes($post['helixinclude']));
       $helixexclude = trim(addslashes($post['helixexclude']));
       
       if($helixinclude == '') $helixinclude = '60037,34292,55009,3430,12724,33858,11685,13227,6603,45680,36778,23402,30597,9913,46115,32185,46677,19029,29424,30113,32640,59908,40476,13198,20996,28578,24538,16393,18908,24893,19515,56065,14948,32102,46668,51356,14255,4356,25336,26199,46913,66863,11811,60822,2655,65821,43225,32931,36177,53017,24321,43820,43615,8588,39038,20986,21571,19106,21592,24784,46761';
       if($helixexclude == '') $helixexclude = '39427,14152,14661,55155,16387,13321,8972,32856,34004,30579,18762,67851,13177,13712,48606,61592,67042,55582,18063,1928,24639,1309,50167,8142,53991,39216,30138,15558,13211,55605,39790,55149,6262,6907,48044,29361,12045,31896,32019,1178,12199,13789,15860,48623,39822,16172,12108,21677,44473,53443';
       
       $qry = "update ".config_item('table_perfix')."helix_rules set value = '".$helixinclude."' where variable = 'helixinclude'";
	   $r = 	$this->db->query($qry);
       
	   $qry = "update ".config_item('table_perfix')."helix_rules set value = '".$helixexclude."' where variable = 'helixexclude'";
	   $r = 	$this->db->query($qry);
       
       if($r)$ret['success'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Helix Rules has been saved</td></tr>  </table>';
       	 else $ret['error'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>ERROR !! Helix rules not saved</td></tr>  </table>';  
       return $ret;
       
       
		
	}
	
    function getprices( $page =1 , $rp = 10 ,$sortname = 'pricefrom' ,$sortorder = 'asc' ,$query= '' , $qtype = 'pricefrom' , $oid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'helix_prices where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT  rowid FROM  '. $this->config->item('table_perfix').'helix_prices  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
	
    function helixprices($post , $action = 'view' , $id =0)
  	{
  		
  		$retuen  = array();
    	$retuen['error'] 	= '';
    	$retuen['success'] 	= '';
    	  if($action == 'delete'){
				   		$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."helix_prices WHERE rowid IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$items)); 
						$retuen['total'] = $total;
					 	 
				   	}
		  else{
		  	if(is_array($post)){		
				 				$pricefrom 			= isset($post['pricefrom']) 		? $post['pricefrom'] : 0;
							 	$priceto 		= isset($post['priceto']) 		? $post['priceto'] : '9999999';
							 	$rate   	= isset($post['rate']) 	? $post['rate'] : '0';
		  	}else{
		  							$pricefrom  =  0;
								 	$priceto 	=  0;
								 	$rate   	= 1;
		  	}
		  							$pricefrom  =  floatval($pricefrom);
								 	$priceto 	=  floatval($priceto);
								 	$rate   	= floatval($rate);
		  	
		  	
				 if( ($pricefrom < $priceto ) && ($pricefrom >0) && ($priceto > 0)){
				   		
					
			 		
			 		if($action == 'add')
			 		{
			 			 	
				 		$isinsert = $this->db->insert($this->config->item('table_perfix').'helix_prices',
				 		array(
		  						  'pricefrom' => $pricefrom,
		  						  'priceto'	  => $priceto,
		  						  'rate'	  => $rate
		  						  
							 ));
			 		   
			 		}
			 		if($action == 'edit')
			 		{
			 		$this->db->where('rowid' , $id);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'helix_prices',
			 		array(			  
			  					  'pricefrom' => $pricefrom,
		  						  'priceto'	  => $priceto,
		  						  'rate'	  => $rate
									 
						 ));
			 		}
			 	 				  
				 	if($isinsert) $retuen['success'] .= '<h1 class="success">Price Range has been '.ucfirst($action).'ed .</h1><br><br><center><a href="'.config_item('base_url').'admin/helixprice">Continue</a></center><br /> ';
				 }
				 else{
				 	$retuen['error'] .= '<h1 class="error">Invalid price range ! Try Again</h1><br /> ';
				 }
         			
		 
  	}
  	
    return $retuen;
  
    
 }
 
 	function getPriceByID($id = 0){
 	$qry = "SELECT *
				FROM ".config_item('table_perfix')."helix_prices
				WHERE rowid = '".$id."'
				";
		$result = 	$this->db->query($qry);
		$return = $result->result_array();	
		return isset($return[0]) ? $return[0] : false;
 }
 
 function erdprice($price = 250){
 	if(trim(floatval($price)) < 1) $price = 0;
 	$qry = "SELECT rate from ".config_item('table_perfix')."helix_prices
				WHERE  pricefrom <= $price and priceto >= $price
				";
		$result = 	$this->db->query($qry);
		$return = $result->result_array();	
		if(isset($return[0])){
			$price += $price * ($return[0]['rate']/100);
		}
		
		return $price;
 }
	
	                                                                                                                                                                                                                                                                                                  
}?>