<?php  //ini_set("display_errors", 1);
//error_reporting(E_ALL);
 class Cronmodel extends Model {
 	
 	function __construct()
	{
 		parent::Model();
 	}
 	
 	function gethelixinclude()
	{
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixinclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	function gethelixexclude()
	{
		$qry = "SELECT value FROM ".config_item('table_perfix')."helix_rules where variable = 'helixexclude'";
		$result = 	$this->db->query($qry);
		$ret = $result->result_array();	
		 
		return isset($ret[0]) ? $ret[0]['value'] : '';
	}
	
	function emptyhelix()
	{
		return $this->db->query('TRUNCATE TABLE '.config_item('table_perfix') . 'products');
	}
	
	function saveinhelix($cols)
	{
	  if(is_array($cols))
	  {
	  	$carat  		= isset($cols[0]) ? $cols[0] : '0';
	  	$Cert  			= isset($cols[1]) ? $cols[1] : '';
	  	$Cert_n  		= isset($cols[2]) ? $cols[2] : '';
		$clarity  		= isset($cols[3]) ? $cols[3] : '';
	  	$color  		= isset($cols[4]) ? $cols[4] : '';
	  	$Comment  		= isset($cols[5]) ? $cols[5] : '';
	  	$Country   		= isset($cols[6]) ? $cols[6] : '';
		$City    		= isset($cols[7]) ? $cols[7] : '';
	  	$Culet  		= isset($cols[8]) ? $cols[8] : '';
	  	$cut            = isset($cols[9]) ? $cols[9] : 0;
		$Depth  		= isset($cols[10]) ? $cols[10] : '0';
	  	$Flour  		= isset($cols[11]) ? $cols[11] : '';
	  	$Girdle  		= isset($cols[12]) ? $cols[12] : '';
	  	$lot 			= isset($cols[13]) ? $cols[13] : 0;
	  	$Meas  			= isset($cols[14]) ? str_replace('-','x',$cols[14]) : '0';
	  	$Polish  		= isset($cols[15]) ? $cols[15] : '';
	  	$price  		= isset($cols[16]) ? $cols[16] : '250';
	  	$Rap  	    	= isset($cols[17]) ? $cols[17] : '0';
	  	$Make    		= isset($cols[18]) ? $cols[18] : '';
	  	$ratio    		= isset($cols[19]) ? $cols[19] : '';
	  	$owner  		= isset($cols[20]) ? $cols[20] : 'NA';
	  	$shape  		= isset($cols[21]) ? $cols[21] : '';
	    $State   		= isset($cols[22]) ? $cols[22] : '';
	  	$Stock_n    	= isset($cols[23]) ? $cols[23] : '';
	  	$Sym  			= isset($cols[24]) ? $cols[24] : '';
	  	$TablePercent  	= isset($cols[25]) ? $cols[25] : 'NA';
	  	$Stones  		= isset($cols[26]) ? $cols[26] : '';
	  	$CertImage   	= isset($cols[27]) ? $cols[27] : '';
	  	$Date    		= isset($cols[28]) ? $cols[28]  : '';
		$MeasArray		= explode('x', $Meas);
	  	 if(strcmp($cut,'')===0) 
		   $cut='G';
		
	  	 
	  	$Cert = strtoupper($Cert);
	  	$ratio = ( isset($ratio) && $ratio != null) ? $ratio : ' ';
	  	$price = $this->erdprice($price * $carat) ;
	  	
		if(is_numeric($Depth) && is_numeric($TablePercent))
		{
		$helixInclude = strtolower (trim($this->gethelixinclude()));	
		$helixIncludeArray = explode(',', $helixInclude);
	    $tempowner = strtolower(trim($owner));
		if(in_array($tempowner,  $helixIncludeArray)) 
		{
		  $isinsert = $this->db->insert($this->config->item('table_perfix').'products',
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
					       'tbl'	=> config_item('base_url').'diamonds/search/true/true/false/false/false/'.$lot,
						   'certimage' => $CertImage,
						   'length' => $MeasArray[0],
						   'width' => $MeasArray[1],
						   'height' => $MeasArray[2]
						));
				if(!$isinsert)
				{
					return false;
				}
				else 
				    return true;	
				}
			}
	    }
	}

function fixhelix()
{
		$qry = "Update ". $this->config->item('table_perfix') ."products set Polish= 'GD' where Polish='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Polish= 'ID' where Polish='I'";
		$this->db->query($qry);
		
		/// fix fluro
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'NO' where Flour='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'MED' where Flour='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'ST BLUE' where Flour='SB'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Flour= 'VST BLUE' where Flour='VSLB'";
		$this->db->query($qry);

		// fix SYMMETRY
		$qry = "Update ". $this->config->item('table_perfix') ."products set Sym= 'GD' where Sym='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Sym= 'ID' where Sym='I'";
		$this->db->query($qry);
		 
		// fix CULET
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'NO' where Culet='N'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'VS' where Culet='V'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'SM' where Culet='S'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'PN' where Culet='P'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'ME' where Culet='M'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set Culet= 'LR' where Culet='L'";
		$this->db->query($qry);
		
		// fix cut
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Good' where cut='G'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Very Good' where cut='VG'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Premium' where cut='EX'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set cut= 'Ideal' where cut='I'";
		$this->db->query($qry);
		
		/*
		// fix clarity 
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '0' where clarity='IF'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '1' where clarity='VVS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '2' where clarity='VVS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '3' where clarity='VS1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '4' where clarity='VS2'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '5' where clarity='SI1'";
		$this->db->query($qry);
		$qry = "Update ". $this->config->item('table_perfix') ."products set clarity= '6' where clarity='SI2'";
		$this->db->query($qry);
		
		  
		$DELETE  = "DELETE FROM ". $this->config->item('table_perfix') ."products WHERE ";
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
		*/
	}
	
	function erdprice($price = 250){
 	if(trim(floatval($price)) < 1) $price = 0;
	$price=floatval($price);
 	$qry = "SELECT rate from ".config_item('table_perfix')."helix_prices
				WHERE  pricefrom <= $price and priceto >= $price
				";
		$result = 	$this->db->query($qry);
		$return = $result->result_array();	
		if(isset($return[0]))
		{
			//$price += $price * ($return[0]['rate']/100);
			$price = $price * $return[0]['rate'];
		}
		
		return $price;
 }

 function getDiamondByLOT($sku = ''){
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products
				WHERE lot = '".$sku."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	}
	
	function getRoundStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1206795010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 18253467;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 18253468;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 18253469;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 18253470;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 18253471;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 18253472;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 18253473;
		} else  {
			$storeCatID = 18253474;
		}
			
			return $storeCatID;
	
	}

	function getPrincessStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1206796010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 18429729;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 18429730;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 18429731;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 18429732;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 18429733;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 18429734;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 18429735;
		} else  {
			$storeCatID = 18429736;
		}
			
			return $storeCatID;
	
	}
	 
	function getPearStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548066010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 785096010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 785097010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 785098010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 785099010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 785100010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 785101010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 785102010;
		} else  {
			$storeCatID = 785103010;
		}
		return $storeCatID;
	}
	
	function getEmeraldStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548064010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 785113010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 785114010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 785115010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 785116010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 785117010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 785118010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 785119010;
		} else  {
			$storeCatID = 785120010;
		}
		return $storeCatID;
	}

	function getOvalStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548068010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 785188010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 785189010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 785190010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 785191010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 785192010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 785193010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 785194010;
		} else  {
			$storeCatID = 785195010;
		}
		return $storeCatID;
	}

	function getMarquiseStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548074010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 785163010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 785164010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 785165010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 785166010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 785167010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 785168010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 785169010;
		} else  {
			$storeCatID = 785170010;
		}
		return $storeCatID;
	}
	
	function getAsscherStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1001021010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 1001022010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 1001023010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 1001024010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 1001025010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 1001026010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 1001027010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 1548224010;
		} else  {
			$storeCatID = 1548225010;
		}
		return $storeCatID;
	}
	
	function getCushionStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548246010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 1548247010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 1548248010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 1548249010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 1548250010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 1548251010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 1548252010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 1548253010;
		} else  {
			$storeCatID = 1548254010;
		}
		return $storeCatID;
	}

	function getRadiantStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1001066010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 1001067010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 1001068010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 1001069010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 1001070010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 1001071010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 1001072010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 1548229010;
		} else  {
			$storeCatID = 1548230010;
		}
		return $storeCatID;
	}
	
	function getHeartStoreCategoryID($carat) {
		if($carat >= 0.20 && $carat < 0.40) {
			$storeCatID = 1548270010;
		} else if($carat >= 0.40 && $carat < 0.70) {
			$storeCatID = 1548271010;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$storeCatID = 1548272010;
		} else if($carat >= 0.90 && $carat < 1.00) {
			$storeCatID = 1548273010;
		} else if($carat >= 1.00 && $carat < 1.50) {
			$storeCatID = 1548274010;
		} else if($carat >= 1.50 && $carat < 2.00) {
			$storeCatID = 1548275010;
		} else if($carat >= 2.00 && $carat < 3.00) {
			$storeCatID = 1548276010;
		} else if($carat >= 3.00 && $carat < 4.00) {
			$storeCatID = 1548277010;
		} else  {
			$storeCatID = 1548278010;
		}
		return $storeCatID;
	}

	function getItemSpecification($detail){
		
				switch ($detail['shape']){
			case 'B':
				$shape = 'Round';
			break;
			case 'PR':
				$shape = 'Princess';
			break;
			case 'R':
				$shape = 'Radiant';
				break;
			case 'E':
				$shape = 'Emerald';
				break;
			case 'AS':
				$shape = 'Asscher';
				break;
			case 'O':
				$shape = 'Oval';
				break;
			case 'M':
				$shape = 'Marquise';
				break;
			case 'P':
				$shape = 'Pear';
				break;
			case 'H':
				$shape = 'Heart';
				break;
			case 'C':
				$shape = 'Cushion';
				break;								  	
			default:
				$shape = $detail['shape'];
				break;
		 }	
		$specification = '<ItemSpecifics> 
					  <NameValueList> 
						<Name>Condition</Name> 
						<Value>New</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Ring Size</Name> 
						<Value>Size Selectable</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Metal</Name> 
						<Value>White Gold</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Metal Purity</Name> 
						<Value>14k</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Clarity</Name> 
						<Value>'.$detail['clarity'].'</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Stone Shape</Name> 
						<Value>'.$shape.'</Value> 
					  </NameValueList> 
					 <NameValueList> 
						<Name>Exact Carat Total Weight</Name> 
						<Value>'.$detail['carat'].'</Value> 
					  </NameValueList> 
					<NameValueList> 
						<Name>Certification/Grading</Name> 
						<Value>'.$detail['Cert'].'</Value> 
					  </NameValueList> 
					<NameValueList> 
						<Name>Color</Name> 
						<Value>'.$detail['color'].'</Value> 
					  </NameValueList>
					<NameValueList>
					  <Name>Main Stone Treatment</Name> 
					  <Value>Not Enhanced</Value> 
				  </NameValueList>

					</ItemSpecifics>';
					  
		return $specification;

	}
	function get_attribute($detail) {
		$requestXmlBody .= '<AttributeSetArray> 
							  <AttributeSet attributeSetID="2426"> 
								<Attribute attributeID="10244"> 
								  <Value> 
									<ValueID>10425</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="26177"> 
								  <Value> 
									<ValueID>93464</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="26176"> 
								  <Value> 
									<ValueID>26247</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="47013"> 
								  <Value> 
									<ValueID>46802</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="26178"> 
								  <Value> 
									<ValueID>'.$this->getClarity($detail['clarity']).'</ValueID> 
								  </Value> 
								</Attribute>
								
								<Attribute attributeID="83057"> 
								  <Value> 
									<ValueID>'.$this->getEbayCarat($detail['carat']).'</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="26350"> 
								  <Value> 
									<ValueID>-3</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="26175"> 
								  <Value> 
									<ValueID>'.$this->getEbayShape($detail['shape']).'</ValueID> 
								  </Value> 
								</Attribute>

								<Attribute attributeID="35939"> 
								  <Value> 
									<ValueID>'.$this->getEbayCertificate($detail['Cert']).'</ValueID> 
								  </Value> 
								</Attribute>
							 
								<Attribute attributeID="26353"> 
								  <Value> 
									<ValueID>'.$this->getEbayColor($detail['color']).'</ValueID> 
								  </Value> 
								</Attribute>
							  </AttributeSet> 
							</AttributeSetArray>';
		
		return $requestXmlBody;
	}

	function getClarity($clarity) {

		switch(strtoupper($clarity)) {
			case 'VS2' :
				$value = 26342;
			break;
			case 'VVS2' :
				$value = 26340;
			break;
			case 'VVS1' :
				$value = 26340;
			break;
			case 'VS1' :
				$value = 26342;
			break;
			case 'SI1' :
				$value = 26347;
			break;
			case 'I1' :
				$value = 26344;
			break;
			case 'SI2' :
				$value = 26347;
			break;
			case 'IF' :
				$value = 26338;
			break;
			case 'SI3' :
				$value = -6;
			break;
			case 'I2' :
				$value = 26344;
			break;
			case '' :
				$value = -10;
			break;
			case 'I3' :
				$value = 26344;
			break;
			case 'FL' :
				$value = 26338;
			break;
			default :
				$value = -6;
			break;
		}

		return $value;

	}

	function getEbayCarat($carat) {
		if($carat < 0.30) {
			$value = 26300;
		} else if($carat >= 0.30 && $carat < 0.46) {
			$value = 80924;
		} else if($carat >= 0.46 && $carat < 0.70) {
			$value = 80925;
		} else if($carat >= 0.70 && $carat < 0.90) {
			$value = 80927;
		} else if($carat >= 0.90 && $carat < 1.40) {
			$value = 80929;
		} else if($carat >= 1.40 && $carat < 1.80) {
			$value = 80931;
		} else if($carat >= 1.80 && $carat < 2.80) {
			$value = 80933;
		} else if($carat >= 2.80) {
			$value = 80935;
		} else  {
			$value = -6;
		}
		
		return $value;
	}

	function getEbayShape($shape) {

		switch(strtoupper($shape)) {
			case 'B':
				$value = 26241;
			break;
			case 'PR':
				$value = 26242;
			break;
			case 'R':
				$value = 26240;
				break;
			case 'E':
				$value = 26179;
				break;
			case 'AS':
				$value = 26238;
				break;
			case 'O':
				$value = 26186;
				break;
			case 'M':
				$value = 26185;
				break;
			case 'P':
				$value = 26239;
				break;
			case 'H':
				$value = 26181;
				break;
			case 'C':
				$value = 35943;
				break;								  	
			default:
				$value = -6;
				break;
		}

		return $value;

	}

	function getEbayCertificate($certificate) {

		switch(strtoupper($certificate)) {
			case 'AGS':
				$value = 35957;
			break;
			case 'EGL USA':
			case 'EGL U':
				$value = 35958;
			break;
			case 'GIA':
				$value = 35959;
				break;
			case 'IGI':
				$value = 35960;
				break;
			default:
				$value = -6;
				break;
		}

		return $value;

	}

	function getEbayColor($color) {

		switch(strtoupper($color)) {
			case 'D':
				$value = 26314;
			break;
			case 'E':
				$value = 26315;
			break;
			case 'F':
				$value = 26316;
				break;
			case 'G':
				$value = 26317;
				break;
			case 'H':
				$value = 26318;
				break;
			case 'I':
				$value = 26319;
				break;
			case 'J':
				$value = 26320;
				break;
			case 'K':
			case 'L':
			case 'M':
				$value = 94971;
				break;
			case 'N':
			case 'O':
			case 'P':
			case 'Q':
			case 'R':
				$value = 94972;
				break;
			case 'S':
			case 'T':
			case 'U':
			case 'V':
			case 'W':
				$value = 94973;
				break;
			case 'FANCY COLOR':
				$value = 26337;
				break;
			case '':
				$value = -10;
				break;
			default:
				$value = -6;
				break;
		}

		return $value;

	}
	function addDiamondtoEbay($detail, $duration){
		//print_r($detail);
		$destFolder = 'images/diamonds/shape/';
		switch ($detail['shape']){
			case 'B':
				$shape = 'Round';
				$destFile = $destFolder.'round.jpg';
				$storeCategoryId = $this->getRoundStoreCategoryID($detail['carat']);
			break;
			case 'PR':
				$shape = 'Princess';
				$destFile = $destFolder.'princessrings.jpg';
				$storeCategoryId = $this->getPrincessStoreCategoryID($detail['carat']);
			break;
			case 'R':
				$shape = 'Radiant';
				$destFile = $destFolder.'radiantring.jpg';
				$storeCategoryId = $this->getRadiantStoreCategoryID($detail['carat']);
				break;
			case 'E':
				$shape = 'Emerald';
				$destFile = $destFolder.'emeraldring.jpg';
				$storeCategoryId = $this->getEmeraldStoreCategoryID($detail['carat']);
				break;
			case 'AS':
				$shape = 'Asscher';
				$destFile = $destFolder.'asscherring.jpg';
				$storeCategoryId = $this->getAsscherStoreCategoryID($detail['carat']);
				break;
			case 'O':
				$shape = 'Oval';
				$destFile = $destFolder.'oval.jpg';
				$storeCategoryId = $this->getOvalStoreCategoryID($detail['carat']);
				break;
			case 'M':
				$shape = 'Marquise';
				$destFile = $destFolder.'marquisering.jpg';
				$storeCategoryId = $this->getMarquiseStoreCategoryID($detail['carat']);
				break;
			case 'P':
				$shape = 'Pear';
				$destFile = $destFolder.'pear_ring.jpg';
				$storeCategoryId = $this->getPearStoreCategoryID($detail['carat']);
				break;
			case 'H':
				$shape = 'Heart';
				$destFile = $destFolder.'heartring.jpg';
				$storeCategoryId = $this->getHeartStoreCategoryID($detail['carat']);
				break;
			case 'C':
				$shape = 'Cushion';
				$destFile = $destFolder.'cushionring.jpg';
				$storeCategoryId = $this->getCushionStoreCategoryID($detail['carat']);
				break;								  	
			default:
				$shape = $detail['shape'];
				break;
		 }		

		 switch ($detail['Polish']) {
			case 'VG':
				$polish  = 'Very Good';
			break;
			case 'GD':
				$polish  = 'Good';
			break;
			case 'EX':
				$polish  = 'Excellent';
			break;
			case 'ID':
				$polish  = 'Ideal';
			break;
			default:
				$polish = $detail['Polish'];
			break;
		 }
		 
		 switch($detail['Sym']) {
			case 'VG':
				$sym  = 'Very Good';
			break;
			case 'GD':
				$sym  = 'Good';
			break;
			case 'EX':
				$sym  = 'Excellent';
			break;
			case 'ID':
				$sym  = 'Ideal';
			break;
			case 'P':
				$sym  = 'Premium';
			break;
			default:
				$sym = $detail['Sym'];
			break;
		 }

		
		$watchImage = config_item('base_url').$destFile;
		$colorImage = config_item('base_url').'images/Color_Profile.jpg';
		$storeImage = config_item('base_url').'images/upperbar02.jpg';

		$requestArray['listingType'] = 'StoresFixedPrice';//'FixedPriceItem';
		$requestArray['primaryCategory'] = '152899';
        $requestArray['itemTitle']       = $detail['carat'].' Carats '.$shape.' DIAMOND SOLITAIRE WG '.$detail['color'].'-'.$detail['clarity'].' '.$detail['Cert'];
		//$requestArray['itemTitle']       = $detail['carat'].' Carats Diamond Solitaire Ring '.$detail['color'].' '.$detail['clarity'].' '.$detail['Cert'];
		$requestArray['productID']       = $detail['lot'];
		$price = round(($detail['price'] + 150.00) * 1.25);
		/*$watchDetail = '<p>This <i> '.$detail['cut'].' </i>-cut, <i> '.$detail['color'].' </i>-color, and <i> '.$detail['clarity'].' </i>-clarity diamond comes accompanied by a diamond grading report from the '.$detail['Cert'].'.</p>';
		
		$watchdetail ='<table>';
		 
		$watchDetail .='<tr><td><B>Lot #:</B>'.ucfirst($detail['lot']).'</td><td>&nbsp;</td><td><B>Depth %:</B>'.ucfirst($detail['Depth']).'</td></tr>';
		$watchDetail .='<tr><td><B>Carat::</B>'.ucfirst($detail['carat']).'</td><td>&nbsp;</td><td><B>Table %:</B>'.ucfirst($detail['TablePercent']).'</td></tr>';
		$watchDetail .='<tr><td><B>Cut:</B>'.ucfirst($detail['cut']).'</td><td>&nbsp;</td><td><B>Symmetry: </B>'.ucfirst($detail['Sym']).'</td></tr>';
		$watchDetail .='<tr><td><B>Color:</B>'.ucfirst($detail['color']).'</td><td>&nbsp;</td><td><B>Polish: </B>'.ucfirst($detail['Polish']).'</td></tr>';
		$watchDetail .='<tr><td><B>Clarity:</B>'.ucfirst($detail['clarity']).'</td><td>&nbsp;</td><td><B>Girdle:</B>'.ucfirst($detail['Girdle']).'</td></tr>';
		$watchDetail .='<tr><td><B>Shape:</B>'.ucfirst($Shape).'</td><td>&nbsp;</td><td><B>Culet:</B>'.ucfirst($detail['Culet']).'</td></tr>';
		$watchDetail .='<tr><td>&nbsp;</td><td>&nbsp;</td><td><B>Fluorescence:</B>'.ucfirst($detail['Flour']).'</td></tr>';
		$watchDetail .='<tr><td>&nbsp;</td><td>&nbsp;</td><td><B>Measurements:</B>'.ucfirst($detail['Meas']).'</td></tr>';
		$watchDetail .='<tr><td colspan=3>&nbsp;</td></tr>';
		$watchDetail .='<tr><td colspan=3>Diamond Certificate Information</td></tr>';
		$watchDetail .='<tr><td colspan=3 align="left"><img src="'.$detail['certimage'].'"></td></tr></table>';
		$price = ($detail['price'] + 150.00) * 1.25;
		$watchDetail .='<div>
		<TABLE width=598 align=center border=0>
			<TBODY>
				<TR>
					<TD align=middle width=626><MARQUEE><FONT face=Verdana size=5><B>WELCOME TO ALAN G, YOUR SOURCE FOR CERTIFIED GIA &amp; EGL DIAMONDS </B></FONT></MARQUEE>
					<P><MARQUEE><FONT face=Verdana size=3><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (877)425-2645 / (213)623-1456</B></FONT></MARQUEE>
					<MARQUEE><A href="mailto:alangjewelers@aol.com?subject=ebay auction">alangjewelers@aol.com</A></MARQUEE><BR>
					</P>&nbsp;</TD>
				</TR>
				<TR>
					<TD align=middle width=626><IMG height=99 src="'.$storeImage.'" width=900 border=0></TD>
				</TR>
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
					<TABLE height=228 cellSpacing=1 cellPadding=1 width=364 border=0>
					<TBODY>
					<TR>
					<TD align=middle width=360 bgColor=black height=17><B><FONT face="Georgia, Times New Roman, Times, serif" color=#ffffff size=2>Information</FONT></B></TD></TR>
					
					<TR>
					<TD width=360 bgColor=silver height=18>&nbsp;SHAPE OF DIAMOND:&nbsp;&nbsp;'.$shape.'</TD></TR>

					<TR>
					<TD width=360 height=18>&nbsp;WEIGHT OF DIAMOND:&nbsp;'.$detail['carat'].'CARAT</TD></TR>
					<TR>
					<TD width=360 bgColor=silver height=18>&nbsp;CLARITY:&nbsp;'.ucfirst($detail['clarity']).'</TD></TR>
					<TR>
					<TD width=360 height=21>&nbsp;COLOR: &nbsp;'.ucfirst($detail['color']).'</TD></TR>
					<TR>
					<TD width=360 bgColor=silver height=18>&nbsp;MEASUREMENT:&nbsp;&nbsp;'.ucfirst($detail['Meas']).'&nbsp;

					  MM</TD></TR>
					<TR>
					<TD width=360 height=18>&nbsp;DEPTH:&nbsp;&nbsp;'.ucfirst($detail['Depth']).'%</TD></TR>
					<TR>
					<TD width=360 bgColor=silver height=18>&nbsp;TABLE:&nbsp;&nbsp; '.ucfirst($detail['TablePercent']).'%</TD></TR>
					<TR>
					<TD width=360 height=15>&nbsp;FLUORESCENCE:&nbsp;&nbsp;'.ucfirst($detail['Flour']).'</TD></TR>
					<TR>

					<TD width=360 bgColor=silver height=18>&nbsp;POLISH:&nbsp;'.$polish.'</TD></TR>
					<TR>
					<TD width=360 height=22>&nbsp;SYMMETRY:&nbsp;'.$sym.'</TD></TR>
					<TR>
					<TD width=360 height=22>&nbsp;CUT:&nbsp;'.ucfirst($detail['cut']).'</TD></TR>
					<TR>
					<TD width=360 height=22>&nbsp;Certificate:&nbsp;'.$detail['Cert'].'</TD></TR>
					<TR>
					<TD width=360 bgColor=yellow height=18>Our Price: <FONT color=#ff0000>$</FONT><font color="#ff0000">'.$price.'</font>&nbsp;
					  &amp;&nbsp; No Reserve <FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT>
					</TD>
					</TR>
					</TBODY>
					</TABLE>';
		
			/*'<DIV style="WIDTH: 338px; HEIGHT: 521px" align=center>
			<TABLE height=1 width=365 border=0>
			<TBODY>
			<TR>
			<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif" vAlign=top width=359><FONT color=black>&nbsp;*************************************************</FONT><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
			<TR>
			<TD vAlign=top width=359 height=289>*<FONT color=#0000ff>Marquise Cut Solitaire Diamond Ladies Band;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </FONT>
			<P><FONT color=#0000ff>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</FONT><I><B><FONT color=#0000ff size=4>APPRAISED VALUE</FONT></B></I></P>

			  <P align=center><I><B><FONT color=#000080 size=5>$'.$price.'</FONT></B></I></P>
			<P align=justify><FONT face=Verdana size=2>This auction is for a <FONT color=#008080><B><I>BRAND NEW</I></B>
			</FONT>OVAL CUT<FONT color=#008080> </FONT>SOLITAIRE diamond ladies\' band.&nbsp; The
			weight of this diamond 1.01 carats.</FONT></P>
			<P align=justify><FONT face=Verdana size=2>Color grade of the diamond is E (colorless) and the clarity is graded as
			SI1.&nbsp; SI1 clarity is explained as inclusions are viewable under 10 power magnification.&nbsp; </FONT></P>

			<P align=justify><FONT face=Verdana size=2>The RING is made of <U><FONT color=#008080><B>14kt white gold</B>.</FONT></U>&nbsp; Available in yellow gold at no additional charge.&nbsp; Upgrade to platinum for additional
			$495.00.&nbsp; </FONT></P>
			<P align=justify><FONT face=Verdana size=2>The diamonds have excellent cut, polish and symmetry and is simply incredible.&nbsp; They are clear and bright with exceptional brilliance and fire.&nbsp; The clarity is truly amazing, and this diamonds sparkle immensely the shape and cut are perfect.&nbsp; </FONT></P>
			<P align=justify><FONT face=Verdana size=2>Please email me with your questions or comments.&nbsp;

			You may visit my store to find more selection of certified <SPAN style="BACKGROUND-COLOR: #ff00ff; TEXT-DECORATION: underline">GIA &amp; EGL diamonds</SPAN>.
			The highest bidder will win this beauty.&nbsp; Bid with full confidence. </FONT></P>
			<P><FONT color=#ff0000>These diamonds are all guaranteed to be 100% natural, with no enhancements or treatments.&nbsp; The gemstones are&nbsp; guaranteed to be 100% natural, with no enhancements or treatments.&nbsp; All items have been examined by a GIA gemologist.</FONT></P>
			<P><FONT face=Arial color=black size=1>Descriptions of quality are inherently subjective. The quality descriptions we provide, are to the best of&nbsp;our certified gemologists&nbsp;ability, and are&nbsp;her honest opinion. Disagreements with quality descriptions may occur.&nbsp; &nbsp;</FONT><FONT face=Arial size=1>Appraisal value is given at high retail value for insurance purposes only.&nbsp; Appraisal value is subjective and may vary from one gemologist to another.&nbsp; </FONT><FONT face=Arial color=black size=1>Opinions of appraisers may vary up to 25%. Diamond grading is subjective and may vary greatly. If the lowest color or clarity grades we specify are determined to be more than one grade lower than indicated. you may return the item for a full refund less shipping and insurance.&nbsp;

			We are not responsible for lost diamonds or gems.</FONT></P><FONT face=Verdana size=2>
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<FORM name=orderform action=http://www.ewebcart.com/cgi-bin/cart.pl?add_items=1 method=post>
			<TBODY>
			<TR>
			</FONT>
			<TD vAlign=top width=348 height=>
			</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></DIV>';
			$watchDetail .= '</TD>
			<TD width=365 height=853>
			<TABLE height=758 cellSpacing=1 cellPadding=1 width=389 align=center border=0>
			<TBODY>
			<TR>
			<TD width=414 height=212><IMG height=300 src="'.$watchImage.'" width=300></TD></TR>
			<TR>
			<TD width=414 height=259>
			<P align=center><FONT face="Arial, Helvetica, sans-serif" color=#000033 size=3><BR></FONT><FONT face=Verdana>BID WITH CONFIDENCE!</FONT> </P>

			<P align=center><I><B><FONT color=#008080 size=5>PLATINUM POWER SELLER</FONT></B></I></P>
			<P align=center><i><b><font color="#008080" size="5">OVER 1250 POSITIVE
			FEEDBACKS</font></b></i></P>
			<P dir=rtl align=center><FONT face=Verdana size=2><FONT color=#800000>Alan G Jewelers Guarantees all our <BR>diamonds to be 100% natural <BR></FONT></P>
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<TBODY>
			<TR>
			<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif" vAlign=top width="100%"><FONT color=#0000ff>*********************************************************</FONT><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR></FORM></TBODY></TABLE>
			<TABLE height=482 width=354 align=center border=0>
			<TBODY>
			<TR>
			<TD vAlign=center width=348 background=topbk.jpg bgColor=black height=20>
			<P align=center><B><FONT face="Georgia, Times New Roman, Times, serif" color=white size=2>Your Free Gift</FONT></B></P></TD></TR>

			<TR>
			<TD vAlign=top width=348 height=454>
			<UL>
			<LI><FONT face=Verdana size=2>Jewelry Box </FONT></LI>
			<LI><FONT face=Verdana size=2>Guaranteed to be 100% genuine diamond</FONT></LI>
			<LI><FONT face=Verdana size=2>Guaranteed to be 100% genuine 14kt GOLD</FONT></LI>
			<LI><FONT face=Verdana size=2>Free appraisal for the estimated retail value of the item with every purchase. </FONT></LI>
			<LI><FONT face=Verdana,Arial color=#000000 size=2>Items will be shipped 5-7 days after payment is received.&nbsp;
			  (shipping cut off time is 1:00 PM pacific standard time)</FONT>
			<P>Alan G. Jewelers has been dedicated to excellent customer satisfaction and lowest prices in the jewelry business for nearly 20 years. We are a direct diamond importer and all of our diamonds and gemstones are guaranteed to appraise for twice the amount of purchase price. Our merchandise is being offered on EBAY in order to provide the same exceptional quality and value to the general public. <FONT color=#ff0000>These diamonds are all guaranteed to be natural, with no enhancements or treatments.</FONT> Our goal is to reach the highest customer satisfaction rate possible. We welcome the opportunity to serve you.<BR></FONT></B></FONT></P>

			<P></P>
			<P><FONT face=Verdana color=#ff0000 size=4>Please review our feedback for your Confidence.&nbsp;
			Lay away plan is available, please click here for additional information </FONT>&nbsp;<FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT></P></LI></UL>
			</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
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
			<P align=justify><FONT face=Verdana size=2>We accept ELECTRONIC BANK WIRE
			TRANSFER, <FONT color=red>PAYPAL, VISA, MASTERCARD, AMERICAN EXPRESS, ECHECK.</FONT></FONT></P>
			<P align=justify><FONT face=Verdana size=2><IMG height=34 src="http://images.andale.com/f2/103/108/10035456/1054817181174_creditcard.jpg" width=379 border=0></FONT> <IMG height=24 src="http://pics.ebaystatic.com/aw/pics/paypal/iconEcheck.gif" width=50 border=0></P>
			<P align=justify></P>

			<P></P></TD>
			<TR vAlign=top align=left>
			<TD width=617 colSpan=2 height=369>&nbsp;<U><B><FONT face=Verdana size=3>Helpful Information </FONT></B></U>
			<P class=text><FONT face=Verdana size=2>GIA stands for Gemological Institute of America and EGL stands for European Gemological Laboratory. GIA and EGL certification are prepared by a third independent party not affiliated to Alan G Jewelers for your protection. The certifications state the color and clarity of diamonds greater than .50cts. They are both well respected in the jewelry industry. If you need any more information regarding these laboratories, you may visit EGL at <A href="http://www.eglusa.com/customerlogin.html">www.eglusa.com</A> </FONT>
			<P class=text><U><B><FONT face=Verdana>Satisfied Client</FONT><FONT face=Verdana size=3>\'s Letter </FONT></B></U>
			<P class=text>&nbsp;
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
			<DIV></DIV></TD></TR></TBODY></TABLE></DIV></TD></TR>
			<TR>
			<TD vAlign=top align=middle></TD></TR></TBODY></TABLE></div>';*/

		$watchDetail = '<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>New Page 12</title>
<META content=FrontPage.Editor.Document name=ProgId><!-- Begin Description --> <!-- Begin Description -->
<META content="Microsoft FrontPage 4.0" name=GENERATOR>
<META content=FrontPage.Editor.Document name=ProgId>
</head>

<body onload="init();ebHelpContextualRebrand(\'buy\');" rightmargin="0" topmargin="0" leftmargin="0" bottommargin="0" marginheight="0" marginwidth="0">

<br><!-- ST_SEAL_HTML_END -->

<TABLE width=598 align=center border=0>
<TBODY>
<TR>
<TD align=middle width=626>
<MARQUEE><FONT face=Verdana size=5><B>WELCOME TO ALAN G, YOUR SOURCE FOR
CERTIFIED GIA & EGL DIAMONDS </B></FONT></MARQUEE>
<P>
<MARQUEE><FONT face=Verdana size=3><B>                              (877)425-2645 / (213)623-1456</B></FONT></MARQUEE>
<MARQUEE><A href="mailto:alangjewelers@aol.com?subject=ebay auction">alangjewelers@aol.com</A></MARQUEE><BR></P> </TD></TR>
<TR>
<TD align=middle width=626><IMG height=99 src="http://www.alangjewelers.com/images/upperbar02.jpg" width=900 border=0></TD></TR>
<TR>
<TD vAlign=top width=626 height=2309>
<DIV align=center>
<TABLE height=2479 width="100%" border=0>
<TBODY>
<TR>
<TD vAlign=top align=right width="99%" height=1457>
<TABLE height=638 width=625 align=center border=0>
<TBODY>
<TR vAlign=top align=left>
<TD width=252 height=931>
 <TABLE height=1 cellSpacing=1 cellPadding=1 width=364 border=0>
<TBODY>
<TR>
<TD align=middle width=360 bgColor=black height=17><B><FONT face="Georgia, Times New Roman, Times, serif" color=#ffffff size=2>Information</FONT></B></TD></TR>
<TR>
<TD width=360 height=10> ITEM NUMBER:'.$detail['lot'].'</TD></TR>
<TR>
<TD width=360 bgColor=silver height=2> METAL:  14KT WHITE GOLD</TD></TR>
<TR>
<TD width=360 bgColor=aqua height=16> ITEM INFO: '.$detail['Cert'].' CERTIFIED               </TD></TR>
<TR>
<TD width=360 height=18> SHAPE OF DIAMOND: '.$shape.' BRILLIANT CUT</TD></TR>
<TR>
<TD width=360 bgColor=silver height=15> WEIGHT OF DIAMOND: '.$detail['carat'].'  CARAT</TD></TR>
<TR>
<TD width=360 height=21> MEASUREMENT: '.$detail['Meas'].' </TD></TR>

<TR>
<TD width=360 bgColor=silver height=19> CLARITY:   '.$detail['clarity'].' (NATURAL
CLARITY)</TD></TR>
<TR>
<TD width=360 height=18> COLOR:   '.$detail['color'].'  (NATURAL
COLOR)</TD></TR>
<TR>
<TD width=360 bgColor=silver height=15> DEPTH:    '.$detail['Depth'].'%</TD></TR>
<TR>
<TD width=360 height=21> TABLE:    '.$detail['TablePercent'].'%</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> POLISH: '.$polish.'</TD></TR>
<TR>
<TD width=360 height=21> SYMMETRY: '.$sym.'</TD></TR>
<TR>
<TD width=360 bgColor=silver height=15> FLUORESCENCE: '.$detail['Flour'].'</TD></TR>
<TR>
<TD width=360 height=21> CUT: '.$detail['cut'].'</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> STYLE OF RING: DIAMOND SOLITAIRE</TD></TR>
<TR>
<TD width=360 height=21> DIAMOND QUANTITY:  1 INDIVIDUAL</TD></TR>
<TR>
<TD width=360 bgColor=silver height=19> RING SIZE:   ****FREE
RING SIZING****</TD></TR>
<TR>
<TD width=360 bgColor=#ffffff height=18> WIDTH OF BAND:   2.5 MM</TD></TR>
<TR>
<TD width=360 bgColor=silver height=18> DIAMOND SETTING:   PRONG</TD></TR>
<TR>
<TD width=360 height=22> CONDITION:  100% BRAND NEW</TD></TR>
<TR bgColor=#c8c8d8>
<TD width=360 bgColor=silver height=24> ESTIMATED RETAIL VALUE : $'.round($price * 4.5).'</TD></TR>
<TR>
<TD width=360 bgColor=yellow height=18> Our Price: <FONT color=#ff0000>$'.$price.'</FONT>  &  No Reserve <FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT></TD></TR></TBODY></TABLE>
<DIV style="WIDTH: 365px; HEIGHT: 830px" align=center>
<TABLE height=1 width=365 border=0>
<TBODY>
<TR>
<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif" vAlign=top width=359><FONT color=black> *************************************************</FONT><FONT face=Verdana size=2> </FONT></TD></TR>
<TR>
<TD vAlign=top width=359 height=289><FONT color=#0000ff>                          </FONT><P align=justify><FONT face=Verdana size=2>This auction is for a <FONT color=#008080><B><I>
BRAND NEW</I></B> </FONT>
SOLITAIRE  diamond ladies\' engagement ring.   </FONT></P>
<P align=justify><font face="Verdana" size="2">The copy of the original certificate will be
mailed with the diamond upon purchase. The original will be mailed after
buyer has left feedback.   </font></P>
<P align=justify><FONT face=Verdana size=2>The RING is made of <U><FONT color=#008080><B>14kt white gold</B>.</FONT></U> 
14kt Yellow Gold available with no additional charge.  Upgrade to Platinum
950 for additional $495.00.  All rings are shipped in size 6 if sizing is
not indicated in your PAYPAL payment.  Please indicate your ring size in PAYPAL
Payment.  </FONT></P>
<P align=justify><FONT face=Verdana size=2>The diamond has an excellent polish 
and symmetry and is simply incredible.  It is clear and bright with 
exceptional brilliance and fire.  The clarity is truly amazing, and it sparkles immensely.  </FONT></P>
<P align=justify><FONT face=Verdana size=2>Please email me with your questions or
comments before you bid on any item.  Look for our auctions on Ebay. We
offer a huge selection of certified <SPAN style="text-decoration: underline">GIA & EGL diamonds</SPAN>.  The highest bidder will win this beauty.  Bid with full confidence. </FONT></P>
<P align="justify"><FONT color=#ff0000>These diamonds are all guaranteed to be 100% natural, with no enhancements or treatments.  The gemstones are  guaranteed to be 100% natural, with no enhancements or treatments.  All items have been examined by a GIA gemologist.</FONT></P>
<P align="justify"align="justify"><font face="Verdana" size="2"><FONT color=black>Descriptions of quality are inherently subjective. The quality descriptions we provide, are to the best of our certified gemologists ability, and are her honest opinion. Disagreements with quality descriptions may occur. 
</FONT>Appraisal value is given at high retail value for insurance purposes only.  Appraisal value is subjective and may vary from one gemologist to another. 
<FONT face=Verdana color=black size=2>Opinions of appraisers may vary up to 25%. Diamond grading is subjective and may vary greatly. If the lowest color or clarity grades we specify are determined to be more than one grade lower than indicated. you may return the item for a full refund less shipping and insurance. 
Buyer is responsible for lost diamonds or gems after purchase.  GIA &
EGL diamonds are independently certified and graded based on the companies
standard of grading diamonds.  Alan G is not responsible for diamond
grading by EGL or GIA.</FONT></font></P></TD></TR></TBODY></TABLE></DIV></TD>
<TD width=365 height=931>
<TABLE height=554 cellSpacing=1 cellPadding=1 width=389 align=center border=0>
<TBODY>
<TR>
<TD width=414 height=212><IMG height=325 src="'.$watchImage.'" width=400></TD></TR>
<TR>
<TD width=414 height=55><FONT face=Verdana size=2>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<FORM name=orderform action=http://www.ewebcart.com/cgi-bin/cart.pl?add_items=1 method=post>
<TBODY>
<TR>
<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif" vAlign=top width="100%"><FONT color=#0000ff>*********************************************************</FONT><FONT face=Verdana size=2> </FONT></TD></TR></FORM></TBODY></TABLE></FONT>
<TABLE height=557 width=394 align=center border=0>
<TBODY>
<TR>
<TD vAlign=center width=388 background=topbk.jpg bgColor=black height=20>
<P align=center><B><FONT face="Georgia, Times New Roman, Times, serif" color=white size=2>Your Free Gift</FONT></B></P></TD></TR>
<TR>
<TD vAlign=top width=388 height=529>
<UL>
<LI><FONT face=Verdana color=#0000ff size=2>Jewelry Box </FONT></LI>
<LI><FONT face=Verdana color=#0000ff size=2>Guaranteed to be 100% genuine diamond</FONT></LI>
<LI><FONT face=Verdana color=#0000ff size=2>Guaranteed to be 100% genuine 14kt GOLD</FONT></LI>
<LI><FONT face=Verdana color=#0000ff size=2>Free appraisal for the high estimated retail value of the item with every purchase. 
(Appraisal is for insurance purposes only.  Please do not make a buying
decision based on any one\'s appraisal price.)</FONT></LI>
<LI><FONT face=Verdana,Arial color=#0000ff size=2>Items will be shipped 3 - 5 days
after your payment is received.  (shipping cut off time is 1:00 PM pacific standard time)</FONT>
<P>Alan G. Jewelers has been dedicated to excellent customer satisfaction and lowest prices in the jewelry business for nearly
25 years. We are a direct diamond importer and all of our diamonds and gemstones are guaranteed to appraise for twice the amount of purchase price. Our merchandise is being offered on EBAY in order to provide the same exceptional quality and value to the general public. <FONT color=#ff0000>These diamonds are all guaranteed to be natural, with no enhancements or treatments.</FONT> Our goal is to reach the highest customer satisfaction rate possible. We welcome the opportunity to serve you.</P>
<P><FONT face=Verdana color=#ff0000 size=4>Please review our feedback for your Confidence. </FONT></P>
<P><font face="Verdana" size="4" color="#FF0000">WE GUARANTEE ALL OUR DIAMONDS
TO APPRAISE HIGHER THAN YOUR PURCHASE PRICE.</font></P>
<P> </P>
<P align=center><FONT face=Verdana>BID WITH CONFIDENCE!</FONT> </P>
<P align=center><I><B><FONT color=#008080 size=5>PLATINUM POWER SELLER</FONT></B></I></P>
<P dir=rtl align=center><FONT color=#800000>Alan G Jewelers Guarantees all our <BR>diamonds to be 100% natural</FONT></P>
<P> </P></LI></UL></TD></TR></TBODY></TABLE>
</TD></TR></TBODY></TABLE></TD>
<TR vAlign=top align=left>
<TD width=617 colSpan=2 height=243 >
<!-- End Description -->
<P style="margin-top:20px;"> <U><B><FONT face=Verdana size=3>About us</FONT></B></U> </P>
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
<P align=justify><FONT face=Verdana size=2>We accept <font color="#FF0000"> ELECTRONIC BANK
WIRE TRANSFER, PAYPAL, VISA, MASTERCARD, DISCOVER, AND AMEX</font></FONT></P>
<P align=justify>  <IMG height=24 src="http://pics.ebaystatic.com/aw/pics/paypal/iconEcheck.gif" width=50 border=0></P>
<P class=fontblack><u><b><font face="Verdana" size="3">Feedback Information</font></b></u></P>
<p align="justify">Please read our return policy detailed in our store. 
Contact us if you are not happy with your purchase before leaving any negative
feedback.  We have been in business for over 25 years and will be glad to
resolve any issues.</p>
<p align="justify"> </TD>
<TR vAlign=top align=left>
<TD width=617 colSpan=2 height=369> <U><B><FONT face=Verdana size=3>Helpful Information </FONT></B></U>
<P class=text><FONT face=Verdana size=2>GIA stands for Gemological Institute of America and EGL stands for European Gemological Laboratory. GIA and EGL certification are prepared by a third independent party not affiliated to Alan G Jewelers for your protection. The certifications state the color and clarity of diamonds greater than .50cts. They are both well respected in the jewelry industry. If you need any more information regarding these laboratories, you may visit EGL at <A href="http://www.eglusa.com/customerlogin.html">www.eglusa.com</a>. </FONT>
<P ><U><B><FONT face=Verdana size=3>Satisfied Clients</FONT></B></U>
<P >Please read our feedback to obtain positive feedback from our clients.  If you have any questions, please do not hesitate to contact our office or email.<FONT face=Verdana size=2>
<P ><U><B><FONT face=Verdana size=3>Clarity </FONT></B></U></P>
<P align=justify>The following table explains the diamond clarity (inside the diamond):<BR>
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
<P class=fontblack> </P>
</FONT>
<TR>
<TD class=basic10 colSpan=2 height=394><FONT face=Verdana size=2><U><B><FONT face=Verdana size=3>Color </FONT></B></U></FONT>
<p>While many diamonds appear colorless, or white, they may actually have subtle yellow or brown tones that can be detected when comparing diamonds side by side. Diamonds were formed under intense heat and pressure, and traces of other elements may have been incorporated into their atomic structure accounting for the variances in color. <BR><BR>Diamond color grades start at D and continue down through the alphabet. Colorless diamonds, graded D, are extremely rare and very valuable. The closer a diamond is to being colorless, the more valuable and rare it is. <BR><BR>The color of a diamond is graded with the diamond upside down before it is set in a mounting. The first three colors D, E, F are often called collection color. The subtle changes in collection color are so minute that it is difficult to identify them in the smaller sizes. Although the presence of color makes a diamond less rare and valuable, some diamonds come out of the ground in vivid "fancy" colors - well defin
 ed reds, blues, pinks, greens, and bright yellows. These are highly priced and extremely rare.<BR><BR>
</p>
<DIV align=center><IMG height=200 src="http://www.alangjewelers.com/images/Color/Color_Profile.jpg" width=600> </DIV></TD></TR><TR>
<TD align=middle width=626><img src="'.$detail['certimage'].'"></TD></TR></TBODY></TABLE>
<DIV></DIV></TD></TR></TBODY></TABLE><!-- End Description --></DIV></TD></TR>

<BR />
	
</body>';
		//echo $watchDetail;
		//die('pp');
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($watchDetail);
        } else {
            $requestArray['itemDescription'] = $watchDetail;
        }

		$requestArray['ItemSpecification'] = $this->getItemSpecification($detail);
		$requestArray['AttributeArray'] = $this->get_attribute($detail);
		$listing_duration = 'Days_'.$duration;
		$requestArray['listingDuration'] = $listing_duration;
        $requestArray['startPrice']      = round($price);
        $requestArray['buyItNowPrice']   = round($price);
        //$requestArray['quantity']        = $detail['quantity'];
		$requestArray['quantity']        = '1';
		/*if ($requestArray['listingType'] == 'StoresFixedPrice') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for SIF
          $requestArray['listingDuration'] = 'GTC';
        }
        
        if ($listingType == 'Dutch') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for Dutch
        }*/
		
		$requestArray['storeCategoryID'] = $storeCategoryId;
		$requestArray['itemID'] = $detail['ebayid'];
		$requestArray['image'] = $watchImage;//config_item('base_url').'/images/tamal/diamond/top_'.$shape.'.jpg';
		//print_R($requestArray);

//die('tt');
		//if($action=='Add') {
		if($detail['ebayid']=='' || $detail['ebayid']=='0') {	
			$itemID = $this->sendRequestEbay($requestArray);
		} 
		return $itemID;
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
		//$requestXmlBody .= '';
		$requestXmlBody .= '<PrimaryCategory>';
		$requestXmlBody .= "<CategoryID>".$requestArray['primaryCategory']."</CategoryID>";
		$requestXmlBody .= '</PrimaryCategory>';
		$requestXmlBody .= '<PrivateListing>true</PrivateListing>';
		//$min = ($requestArray['startPrice'] * .85);
		$min = round($requestArray['startPrice'] * .85);
		$requestXmlBody .= '<ListingDetails>
								<ConvertedBuyItNowPrice currencyID="USD">0.00</ConvertedBuyItNowPrice>
								<ConvertedStartPrice currencyID="USD">'.$requestArray['startPrice'].'</ConvertedStartPrice>
								<ConvertedReservePrice currencyID="USD">0.0</ConvertedReservePrice>
								<MinimumBestOfferPrice currencyID="USD">'.$min.'</MinimumBestOfferPrice>
							</ListingDetails>';
		/*$requestXmlBody .= '<AttributeSetArray> 
							  <AttributeSet attributeSetID="1952"> 
								<Attribute attributeID="10244"> 
								  <Value> 
									<ValueID>10425</ValueID> 
								  </Value> 
								</Attribute> 
							  </AttributeSet> 
							</AttributeSetArray>';*/
		$requestXmlBody .= $requestArray['ItemSpecification'];//$requestArray['AttributeArray'];
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
								<StoreCategoryID>'.$requestArray['storeCategoryID'].'</StoreCategoryID>  
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
								<ReturnsWithinOption>Days_7</ReturnsWithinOption>
								<Description>Please visit our eBay store for a detailed return policy</Description>
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
								<GalleryType>Gallery</GalleryType>
								<GalleryURL>'.$requestArray[image].'</GalleryURL> 
								<PhotoDisplay>None</PhotoDisplay> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
								<PictureSource>Vendor</PictureSource>
							 </PictureDetails>'; 
		$requestXmlBody .= '</Item>';
		$requestXmlBody .= '</AddItemRequest>';
		
		//ECHO $requestXmlBody;

		//echo $requestXmlBody ;
		//die('tt');
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		//echo '<pre>';
		////print_r($responseXml);	
		//die('pp');
		//get any error nodes

		$responses = $responseDoc->getElementsByTagName("AddItemResponse");
        foreach ($responses as $response) {
           $acks = $response->getElementsByTagName("Ack");
           $ack   = $acks->item(0)->nodeValue;
          //echo "Ack = $ack <BR />\n";   // Success if successful
		}
		
		$errors = $responseDoc->getElementsByTagName('Errors');
		
		//if there are error nodes
		//if($errors->length > 0)
		if($ack == 'Failure')
		{	//echo '<br>'.die('xyz');
			foreach($errors AS $error) { 
				$SeverityCode     = $error->getElementsByTagName('SeverityCode');
				//echo '<br>'.$SeverityCode->item(0)->nodeValue;
				if($SeverityCode->item(0)->nodeValue=='Error') {
					$status = '<P><B>eBay returned the following error(s):</B>';
					//display each error
					//Get error code, ShortMesaage and LongMessage
					$code     = $error->getElementsByTagName('ErrorCode');
					$shortMsg = $error->getElementsByTagName('ShortMessage');
					$longMsg  = $error->getElementsByTagName('LongMessage');

					//Display code and shortmessage
					$status .= '<P>'. $code->item(0)->nodeValue. ' : '. str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
					//if there is a long message (ie ErrorLevel=1), display it
					if(count($longMsg) > 0)
						$status .= '<BR>'.str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
						
					

				}
			}
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
              $status = "<a href=$linkBase" . $itemID . ">".$requestArray['itemTitle']."</a> <BR />";
			
			  $this->db->where('lot' , $requestArray['productID']);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'products',
			 		array(			  
			  				'ebayid'	=> $itemID,
			  				
						 ));
            
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
		//echo $status;
	}

	function getAllDiamonds(){
		/* $qry = "SELECT *
				FROM ".config_item('table_perfix')."products WHERE price !='0.0' AND clarity IN('SI3') AND ebayid IS NULL  ORDER BY RAND() LIMIT 0,1
				";*/
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products WHERE price !='0.0' AND ebayid IS NULL  ORDER BY RAND() LIMIT 0,20000
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

	function getDiamonds(){
		/* $qry = "SELECT *
				FROM ".config_item('table_perfix')."products WHERE price !='0.0' AND clarity IN('SI3') AND ebayid IS NULL  ORDER BY RAND() LIMIT 0,1
				";*/
		$qry = "SELECT *
				FROM ".config_item('table_perfix')."products WHERE price !='0.0' AND ebayid IS NULL  ORDER BY RAND() LIMIT 0,8
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result) ? $result : false;
	}

 }
?>