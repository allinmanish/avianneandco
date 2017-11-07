<?php
 class Adminmodel extends Model {
 	function __construct()
 	{
 		parent::Model();
 	}
 
	function adminmenuhtml($page = 'myaccount'){
  		$usermenu =  '';
  	
  		if($this->session->isLoggedin()){ 
  	    
  		$usermenu .= '<div class="menu_list" id="secondpane"> <!--Code for menu starts here-->

  					<p class="menu_head" id="sitemanage">Site Manage</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/commonpagetemplate"';
					  				$usermenu .= ($page == 'staticpage') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Static Pages</a>';
						/*$usermenu .= '<a href="'.config_item('base_url').'admin/globalvariables"';
					  				$usermenu .= ($page == 'globalvariable') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Global Variables</a>';					  			    	
			  	         $usermenu .= '<a href="'.config_item('base_url').'admin/sitemap"';
					  				$usermenu .= ($page == 'sitemap') ?  'class="active">' : '>';
					  			    	$usermenu .= 'SIte Map</a>';
					  	 $usermenu .= '<a href="'.config_item('base_url').'admin/managesearchresult"';
					  				$usermenu .= ($page == 'managesearchresult') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Manage Search Results</a>';
					  	 $usermenu .= '<a href="'.config_item('base_url').'admin/rightads"';
									$usermenu .= ($page == 'rightads') ? 'class="active">' : '>';
										$usermenu .= 'Right Ads</a>';*/

			  	        
			  	        $usermenu .= '</div>
			 
			  	        <p class="menu_head" id="jewelrymanage">Manage Database</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/jewelries"';
					  				$usermenu .= ($page == 'jewelries') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Jewelry Database</a>';
	  			    	/*$usermenu .= '<a href="'.config_item('base_url').'admin/diamonds"';
					  				$usermenu .= ($page == 'diamonds') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Diamond Database</a>';*/
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/rolex"';
					  				$usermenu .= ($page == 'rolex') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Rolex Database</a>';
					  	/*$usermenu .= '<a href="'.config_item('base_url').'admin/diamondsreport"';
					  				$usermenu .= ($page == 'diamondsreport') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Diamonds Report</a>';*/
					  			    	
			  	        $usermenu .= '</div>
			  	        
			  	        <p class="menu_head" id="rapnet">Rap-net</p>
						<div class="menu_body">';
	  					$usermenu .= '<a href="'.config_item('base_url').'admin/helixprice"';
					  				$usermenu .= ($page == 'helixprice') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Helix Price Rules</a>';
			  		//	$usermenu .= '<a href="'.config_item('base_url').'admin/helixrules"';
					  //				$usermenu .= ($page == 'helixvar') ?  'class="active">' : '>';
					 // 			    	$usermenu .= 'Helix Sellers</a>';
					//  	$usermenu .= '<a href="'.config_item('base_url').'admin/gethelixdiamonds"';
					//  				$usermenu .= ($page == 'helixget') ?  'class="active">' : '>';
					  	//		    	$usermenu .= 'Get Rapnet Diamonds</a>';
					//	$usermenu .= '<a href="'.config_item('base_url').'admin/gethelixRedSellerdiamonds"';
					  //				$usermenu .= ($page == 'helixgetRedSeller') ?  'class="active">' : '>';
					  //			    	$usermenu .= 'Get Red Seller Diamonds</a>';
					//	$usermenu .= '<a href="'.config_item('base_url').'admin/ViewhelixRedSellerdiamonds"';
					//  				$usermenu .= ($page == 'helixgetRedSellerView') ?  'class="active">' : '>';
					//  			    	$usermenu .= 'View Red Seller Diamonds</a>';
	  			    	
			  	   //     $usermenu .= '<a href="'.config_item('base_url').'admin/syncronizerapnet"';
					 // 				$usermenu .= ($page == 'syncronize') ?  'class="active">' : '>';
					  //			    	$usermenu .= 'Syncronize Helix table and Products table</a>';
					 // 			    	
			  	        $usermenu .= '</div>
			  	        
			  	        <p class="menu_head" id="ecommerce">E-commerce</p>
						<div class="menu_body">';
	  			
	  			    	$usermenu .= '<a href="'.config_item('base_url').'admin/customers"';
					  				$usermenu .= ($page == 'Customers') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Customers</a>';
	  			    	$usermenu .= '<a href="'.config_item('base_url').'admin/orders"';
					  				$usermenu .= ($page == 'Orders') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Orders</a>';
	  			    	 
					  /*			    	
			  	        $usermenu .= '</div>
			  	       
			  	        
			  	     
			     	 <p class="menu_head" id="pricescopeconvert">Pricescope Converter</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/pricescopestructure"';
					  				$usermenu .= ($page == 'pricescopestruct') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Pricescope Structure</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'admin/pricescopecsv"';
					  				$usermenu .= ($page == 'pricescopecsv') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Pricescope CSV</a>';
			  	        
			  	        $usermenu .= '<a href="'.config_item('base_url').'admin/savedcsv"';
					  				$usermenu .= ($page == 'savedcsv') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Saved CSV</a>';
			  	       
			  	        $usermenu .= '</div>
			  	        
			  	     
			      
			  	        <p class="menu_head" id="manageTestimonials">Customer\'s Response</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/testimonials"';
					  				$usermenu .= ($page == 'testimonials') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Testimonials</a>';
			  	        
			  	        $usermenu .= '</div>
			  	        
			  	        
			  	         
			  	        <p class="menu_head" id="design">Template</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'admin/edittemplate"';
					  				$usermenu .= ($page == 'edittemplate') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Edit Template</a>';
					  	*/ 		    	
			  	        
			  	        $usermenu .= '</div>
			  	  
			  	  
				  </div> ';
		 }
  		return $usermenu;
				 
  	}
    
    function getCommonPages(){
    	$sql = "select * from ".$this->config->item('table_perfix')."companyinfo";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
	    return  $result;
    }
    
    function getCommonPageTemplate($pageid){
    	$sql = "select content from ".$this->config->item('table_perfix')."companyinfo where topicid ='$pageid'";
    	$query = $this->db->query($sql);
    	$result = $query->result();
	    
	    if($query->num_rows()){
				return $result[0]->content;
			}else {
				return '';
			}
    }
    
    function saveCommonPageTemplate($pageid = '' , $content = '')
    {
    	$this->db->where('topicid',$pageid);
		$t = $this->db->update($this->config->item('table_perfix').'companyinfo',array('content'=>$content));
		if($t){
				return true;
			}else {
				return false;
			}			
    }
    
    function getjewelries( $page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'jewelries where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT    	stock_number FROM  '. $this->config->item('table_perfix').'jewelries  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
	  
 	function jewelries($post , $action = 'view' , $id =0, $imageSmall = '', $imageBig = '')
  	{
  		
  		$retuen  = array();
    	$retuen['error'] 	= '';
    	$retuen['success'] 	= '';
    	  if($action == 'delete'){
				   		//$items = rtrim($_POST['items'],",");
						$items = $_POST['items'];//rtrim($_POST['items'],",");
						$itemsArr = explode(',',$items);
						foreach($itemsArr AS $index=>$value) {
							if($value !='') {
								$itemDetail = $this->getAllByStockID($value);
								//$itemDetail = $this->getAllByProductID(67706);
								$sql = "DELETE FROM ".$this->config->item('table_perfix')."jewelries WHERE stock_number = $value";
								$result = $this->db->query($sql);
								if($itemDetail['ebayid'] != '' && $itemDetail['ebayid'] !=0) {
									$status = $this->endFixedPriceBid($itemDetail['ebayid']);
								}

							}
						}
						$items = rtrim($_POST['items'],",");
						/*$sql = "DELETE FROM ".$this->config->item('table_perfix')."jewelries WHERE stock_number IN ($items)";
						$result = $this->db->query($sql);*/
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."ringanimation WHERE stock_num IN ($items)";
						$result = $this->db->query($sql);
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."ringimages WHERE stock_number IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$items)); 
						$retuen['total'] = $total;
					 	 
				   	}
		  else{
				   		 
				   		
					if(is_array($post)){	
					            $name	        = isset($post['name']) 		? $post['name'] : 0;
				 				$price 			= isset($post['price']) 		? $post['price'] : 0;
							 	$section 		= isset($post['section']) 		? $post['section'] : '';
							 	$collection   	= isset($post['collection']) 	? $post['collection'] : '';
							 	$carat   		= isset($post['carat']) 		? $post['carat'] : '';
							 	$shape   		= isset($post['shape']) 		? $post['shape'] : '';
							 	$metal   		= isset($post['metal']) 		? $post['metal'] : '';
							 	//$finger_size   	= isset($post['finger_size']) 	? $post['finger_size'] : '';
							 	$diamond_count  = isset($post['diamond_count']) ? $post['diamond_count'] : '';
							 	$diamond_size   = isset($post['diamond_size']) 	? $post['diamond_size'] : '';
								$total_carats   = isset($post['total_carats']) 	? $post['total_carats'] : '';
								$pearl_lenght  	= isset($post['pearl_lenght']) 	? $post['pearl_lenght'] : '';
								$pearl_mm   	= isset($post['pearl_mm']) 		? $post['pearl_mm'] : '';
								$semi_mounted   = isset($post['semi_mounted']) 	? $post['semi_mounted'] : '';
								$side  			= isset($post['side']) 			? $post['side'] : '';
								$description 	= isset($post['description']) 	? $post['description'] : '';
							    $small_img 		= isset($post['small_image'])   ? $post['small_image'] : '';
							    $carat_image	= isset($post['carat_image']) 	? $post['carat_image'] : '';
							    $style          = isset($post['style'])			? $post['style'] : '';
							    $ringtype       = isset($post['ringtype'])		? $post['ringtype'] : '';
							    $platinum_price	= isset($post['platinum_price']) 		? $post['platinum_price'] : 0;
							    $white_gold_price = isset($post['white_gold_price'])? $post['white_gold_price'] : 0;                               $yellow_gold_price 			= isset($post['yellow_gold_price']) 		? $post['yellow_gold_price'] : 0;
							    $yellow_gold_price = isset($post['yellow_gold_price'])? $post['yellow_gold_price'] : 0;                               $yellow_gold_price 			= isset($post['yellow_gold_price']) 		? $post['yellow_gold_price'] : 0;
							 	
							    
							    
			 		
			 		if($action == 'add')
			 		{
			 			 	
				 		$isinsert = $this->db->insert($this->config->item('table_perfix').'jewelries',
				 		array(
		  						  'price'			=> $price,
		  						  'section'			=> $section,
		  						  'collection'		=> $collection,
		  						  'carat'			=> $carat,
		  						  'shape'			=> $shape ,
		  						  'metal'			=> $metal,
		  						  //'finger_size'		=> $finger_size,
		  						  'diamond_count'	=> $diamond_count,
		  						  'diamond_size'	=> $diamond_size,
		  						  'total_carats'	=> $total_carats,
		  						  'pearl_lenght'	=> $pearl_lenght,
		  						  'pearl_mm'		=> $pearl_mm,
		  						  'semi_mounted'	=> $semi_mounted,
		  						  'side'			=> $side,
		  						  'description'		=> $description,
								  'style'			=> $style,
								  'ringtype'		=> $ringtype,
								  'name'            =>$name,
								  'yellow_gold_price'=>$yellow_gold_price,
								  'white_gold_price' =>$white_gold_price,
								  'platinum_price'   =>$platinum_price
							 ));
			 		   $rid = $this->db->insert_id();
			 		   $isinsert = $this->db->insert($this->config->item('table_perfix').'ringanimation',array('stock_num' => $rid));
			 		}
			 		if($action == 'edit')
			 		{
			 		$rid = $id;	
			 		$this->db->where('stock_number' , $id);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'jewelries',
			 		array(			  
			  						  'price'			=> $price,
			  						  'section'			=> $section,
			  						  'collection'		=> $collection,
			  						  'carat'			=> $carat,
			  						  'shape'			=> $shape ,
			  						  'metal'			=> $metal,
			  						  //'finger_size'		=> $finger_size,
			  						  'diamond_count'	=> $diamond_count,
			  						  'diamond_size'	=> $diamond_size,
			  						  'total_carats'	=> $total_carats,
			  						  'pearl_lenght'	=> $pearl_lenght,
			  						  'pearl_mm'		=> $pearl_mm,
			  						  'semi_mounted'	=> $semi_mounted,
			  						  'side'			=> $side,
			  						  'description'		=> $description,
									  'style'			=> $style,
								  	  'ringtype'		=> $ringtype,
									  'name'            =>$name,
									  'yellow_gold_price'=>$yellow_gold_price,
									  'white_gold_price' =>$white_gold_price,
									  'platinum_price'   =>$platinum_price
									 
						 ));
			 		}
					$qry='select * from '.$this->config->item('table_perfix').'ringanimation where stock_num='.$rid;
					$return = 	$this->db->query($qry);
		          // $result = $return->result_array();	
				   if($return->num_rows()<=0)
				   {
		           //$ranimerexist =  isset($result[0]) ? $result[0] : false;
				  // if(!$ranimerexist)
			 		$isinsertringanim = $this->db->insert($this->config->item('table_perfix').'ringanimation',array('stock_num' => $rid));
			 	   }   
			 											
				 if($_FILES['image_small']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image_small', 'uploads', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/uploads' , $rid , 'jewelries', 'stock_number' , $rid , 'small_image');                      
				
	             if($_FILES['carat_image']['name'] != '') 															
	             $this->uploadfile($_FILES, 'carat_image', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/carat' , $rid , 'jewelries', 'stock_number' , $rid , 'carat_image');                      
				
	             if($_FILES['image45']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image45', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/45' , $rid , 'ringanimation', 'stock_num' , $rid , 'image45');                      
				
	             if($_FILES['image90']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image90', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/90' , $rid , 'ringanimation', 'stock_num' , $rid , 'image90');                      
				
	             if($_FILES['image180']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image180', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/180' , $rid , 'ringanimation', 'stock_num' , $rid , 'image180');
	             
	             
	             
	             if($_FILES['image45_bg']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image45_bg', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/45' , $rid.'b', 'ringanimation', 'stock_num' , $rid , 'image45_bg');                      
				
	             if($_FILES['image90_bg']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image90_bg', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/90' , $rid.'b' , 'ringanimation', 'stock_num' , $rid , 'image90_bg');                      
				
	             if($_FILES['image180_bg']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image180_bg', '', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/rings/icons/180' , $rid.'b' , 'ringanimation', 'stock_num' , $rid , 'image180_bg');                      
	             
				
	          //  if($_FILES['animation1']['name'] != '') 															
	         //    $this->uploadfile($_FILES, 'animation1', 'flash/45', 'swf' , config_item('base_path').'flash/45' , $rid , 'ringanimation', 'stock_num' , $rid , 'flash1');                      
				if($_FILES['animation2']['name'] != '') 															
	             $this->uploadfile($_FILES, 'animation2', 'flash/90', 'swf' , config_item('base_path').'flash/90' , $rid , 'ringanimation', 'stock_num' , $rid , 'flash2');                      
				if($_FILES['animation3']['name'] != '') 															
             $this->uploadfile($_FILES, 'animation3', 'flash/180', 'swf' , config_item('base_path').'flash/180' , $rid , 'ringanimation', 'stock_num' , $rid , 'flash3');                      
				 																																	
			$productArr = $this->getAllByStockID($rid);
			$this->addRingtoEbay($productArr);	 		
				 				  
				 if($isinsert) $retuen['success'] .= '<h1 class="success">Ring info Successfully '.ucfirst($action).'ed .</h1><br /><br /><small> <a href="'.config_item('base_url').'admin/jewelries/edit/'.$rid.'">Click Here </a> To View/Edit</small>';
         }			
		 
  	}
  	
    return $retuen;
  
    
 }
 
 function getAllByStockID($sid) {
		$qry = "SELECT * FROM ".config_item('table_perfix')."jewelries 
 				WHERE stock_number = '".$sid."'  				
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
  }
 
  function uploadfile($_FILES, $formfilenvar = 'myfile', $imageurlbase = '' , $extsupport = 'jpeg,gif,jpg,bmp,png,swf' ,  $base_savepath = 'uploads/', 	$filename = 'error.error', $dbtable = 'error', $idfield = 'id' , $idvalue = '1', $tablefield ='msg'){
    	 												$attachExtension = '';
													    if($_FILES[$formfilenvar]['name'] != ''){
														 			    $supportExt = explode(',',$extsupport);
																		$fileExt = explode('.',$_FILES[$formfilenvar]['name']);
																		if(in_array(strtolower($fileExt[1]),$supportExt))
																			{
																			    $attachFileName = $_FILES[$formfilenvar]['tmp_name'];
																			    //$attachFileName = $_FILES['image_small']['tmp_name'];
																				$attachExtension = strtolower($fileExt[1]);
																						$saveTo = 	 $base_savepath .'/' .$tablefield.'_'.$filename . '.' . $attachExtension; 
																						$imageurl =  $imageurlbase.'/'.$tablefield.'_'.$filename . '.' . $attachExtension; 
																						//chmod($base_savepath, 0777);
																						$ismove = move_uploaded_file($attachFileName, $saveTo);
																						//chmod($base_savepath, 0655);
																					if($ismove){
																								   $this->db->where($idfield , $idvalue);
																								 	$t = $isinsert = $this->db->update($this->config->item('table_perfix').$dbtable  ,array($tablefield => $imageurl));
													   												if($t){
																										$ret['success'] = $imageurl;
																										
																									}else{
																										  $ret['error'] = 'ERROR ! Image not uploaded';
																									} 
																					}else {
																						$ret['error'] =  '<br><b>ERROR ! </b>File Can\t upload to server';
																					}
																					
																			  
																		    }
													
																			else
																			{
																				$ret['error'] =  '<br> Invalid File Type : <b>'.$fileExt[1] . '</b>';
																				
													
																			}
													
																		}
    }
 
    function getglobalvariable(){
    	$sql = "select * from ".$this->config->item('table_perfix')."siteconfig";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
	    return  $result;
    }
    
    function getglobalvariableByid($id){
    	$sql = "select value from ".$this->config->item('table_perfix')."siteconfig where id ='$id'";
    	$query = $this->db->query($sql);
    	$result = $query->result();
	    
	    if($query->num_rows()){
				return $result[0]->value;
			}else {
				return '';
			}
    }
    
    function saveglobalvariableByid($id = '' , $content = '')
    {
    	$this->db->where('id',$id);
		$t = $this->db->update($this->config->item('table_perfix').'siteconfig',array('value'=>$content));
		if($t){
				return true;
			}else {
				return false;
			}			
    }
         
    function getFlashByStockId($stockid){
		$qry = "SELECT id,flash1, flash2 , flash3 ,image45 , image90, image180, image45_bg , image90_bg, image180_bg
				FROM ".config_item('table_perfix')."ringanimation 
				WHERE stock_num = '".$stockid."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		//return $result;
		return isset($result[0]) ? $result[0] : false;
	}
		
	function getcollections(){
		$qry = "SELECT collection 
				FROM ".config_item('table_perfix')."jewelries 
				Group by collection
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();
		return $result;
	}
	
	function getsections(){
		$qry = "SELECT section  
				FROM ".config_item('table_perfix')."jewelries  
				GROUP By section
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();
		return $result;
	}
	
	function gettestimonials( $page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder,adddate desc";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'feedbacks where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT id FROM  '. $this->config->item('table_perfix').'feedbacks  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
	
	function testimonials($post , $action = 'view' , $id =0)
  	{
  		
  		$retuen  = array();
    	$retuen['error'] 	= '';
    	$retuen['success'] 	= '';
    	  if($action == 'delete'){
				   		$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."feedbacks WHERE id IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$id)); 
						$retuen['total'] = $total;
					 	 
				   	}
		  if($action == 'accept'){
				   		$items = rtrim($_POST['items'],",");
						$sql = "UPDATE ".$this->config->item('table_perfix')."feedbacks SET status='accepted' WHERE id IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$id)); 
						$retuen['total'] = $total;
					 	 
				   	}
		  if($action == 'reject'){
				   		$items = rtrim($_POST['items'],",");
						$sql = "UPDATE ".$this->config->item('table_perfix')."feedbacks SET status='rejected' WHERE id IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$id)); 
						$retuen['total'] = $total;
					 	 
				   	}
		 else{
		 	
		 }
  	
    return $retuen;
  
    
 }
 
 	function newfeedbacks(){
 		$sql2 = 'SELECT    	id FROM  '. $this->config->item('table_perfix').'feedbacks  where status=\'*new\' ';
		$result2 = $this->db->query($sql2);
    	return  $result2->num_rows();
 		
 	}
 	
 	function getdiamondsmap( $page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = '', $module = 'jewelry'){
	$results = array();
	
	$sort = "ORDER BY $sortname $sortorder";
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$qwhere = "";
	if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
	if($oid != '') $qwhere .= " AND id = $oid";
	
	$sql = 'SELECT * FROM '. $this->config->item('table_perfix').'site_map where 1=1 and pagemodule=\''.$module.'\' '. $qwhere . ' ' . $sort . ' '. $limit;
	//var_dump($sql);
	$result = $this->db->query($sql);
	$results['result'] = $result->result_array();
	$results['count'] = $result->num_rows();
	
	return $results;
	
	
	}

	
	function editpagedata($id){
	$sql = 'SELECT * FROM '. $this->config->item('table_perfix').'site_map WHERE `pageid`=\''.$id.'\'';
	$result = $this->db->query($sql);
	$results = $result->result_array();
	return $results;
	}

	
	function updatepagedata($id){
	$pagetitle=$_POST['pagetitle'];
	$siteurl=$_POST['httpaddress'];
	$sql = "UPDATE ".$this->config->item('table_perfix')."site_map SET pagetitle='$pagetitle' , httpaddress = '$siteurl' WHERE pageid='$id'";
	$result = $this->db->query($sql);
	return $result;
	}

	
	function getgetpageinfodata( $id){
	$results = array();
	$sql = 'SELECT * FROM '. $this->config->item('table_perfix').'pageinfo where `pageid`=\''.$id.'\'';
	//var_dump($sql);
	$result = $this->db->query($sql);
	$results['result'] = $result->result_array();
	$results['count'] = $result->num_rows();
	
	return $results;
	}

	
	//--------------------------Tamal-------------------------
	
	function getAllSearch(){
		$sql = "SELECT * 
				FROM ". $this->config->item('table_perfix')."search  
			";		 
	 	$query = $this->db->query($sql);
    	$result = $query->result_array();
	    return  $result;
	}
	
	function getSearchById($id){ 
		$sql = "SELECT * 
				FROM ". $this->config->item('table_perfix')."search 
				WHERE id = ".$id."
			";		
		 
	 	$query = $this->db->query($sql);
    	$result = $query->result_array();
	    return  $result;
	}
	 

	//--------------------------end tamal-------------------
	
	function getrightadd(){
		$sql = "select * from ".$this->config->item('table_perfix')."rightads";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

	function getrightaddcontent($pageid){
		$sql = 'SELECT description FROM '. $this->config->item('table_perfix').'rightads where id=\''.$pageid.'\'';
		$query = $this->db->query($sql);
		$result = $query->result();
		
		if($query->num_rows()){
			return $result[0]->description;
		}else {
			return '';
		}
	}
	
	function saverightaddcontent($pageid = '' , $content = '')
	{
		$this->db->where('id',$pageid);
		$t = $this->db->update($this->config->item('table_perfix').'rightads',array('description'=>$content));
		if($t){
			return true;
		}else {
			return false;
		}
	} 
 
 	function editpageinfodata($id, $position){
	$sql = 'SELECT * FROM '. $this->config->item('table_perfix').'pageinfo WHERE `pageid`=\''.$id.'\' AND `pageposition`=\''.$position.'\'';
	$result = $this->db->query($sql);
	$results = $result->result_array();
	return $results;
	}
	
	function updatepageinfodata($id, $position){
	$pageid=$_POST['pagedescription'];
	$sql = "UPDATE ".$this->config->item('table_perfix')."pageinfo SET description='$pageid' WHERE pageid='$id' AND pageposition='$position'";
	$result = $this->db->query($sql);
	var_dump($result);
	return $result;
	}
	
	function customers($post , $action = 'view' , $id =0)
	{
	  		
	  		$retuen  = array();
	    	$retuen['error'] 	= '';
	    	$retuen['success'] 	= '';
	    	  if($action == 'delete'){
					   		$items = rtrim($_POST['items'],",");
							$sql = "DELETE FROM ".$this->config->item('table_perfix')."orderdetails WHERE orderid IN (select id FROM ".$this->config->item('table_perfix')."order WHERE customerid IN($items))";
							$result = $this->db->query($sql);

							$sql = "DELETE FROM ".$this->config->item('table_perfix')."order WHERE customerid IN ($items)";
							$result = $this->db->query($sql);
							
							$sql = "DELETE FROM ".$this->config->item('table_perfix')."customerinfo WHERE id IN ($items)";
							$result = $this->db->query($sql);
							
								
							$total = count(explode(",",$items)); 
							$retuen['total'] = $total;
						 	 
					   	}
			   
					   		 
					  
	  	
	    return $retuen;
	  
	    
	 }
 
	function getcustomers($page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = ''){
		
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'customerinfo where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
	 	$sql2 = 'SELECT * FROM  '. $this->config->item('table_perfix').'customerinfo  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
    	
		$results['result']  = $result->result_array();
		 
    	return $results;
		
	}
	
	//------------------Orders ------------//
	
	function getorders($page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = ''){
		
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'order where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();
		$results['count']  = $result->num_rows();
		 
    	return $results;
		
	}
	
	function diamonds($post , $action = 'view' , $id =0, $table = 'products')
  	{
  		
  		$retuen  = array();
    	$retuen['error'] 	= '';
    	$retuen['success'] 	= '';
    	  if($action == 'delete'){
				   		$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix').$table." WHERE lot IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$items)); 
						$retuen['total'] = $total;
					 	 
				   	}
		 
  	
    return $retuen;
  
    
 }
    function getdiamonds( $page =1 , $rp = 10 ,$sortname = 'lot' ,$sortorder = 'desc' ,$query= '' , $qtype = 'lot' , $oid = '' , $table = 'products'){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').$table.' where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT    	lot FROM  '. $this->config->item('table_perfix').$table.' where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
    function syncronizerapnet(){
       $ret  = array();
       $ret['error'] = '';
       $ret['success'] = '';
       $r = $this->db->query('DROP TABLE IF EXISTS '.  $this->config->item('table_perfix') .'products');
       $r = $this->db->query('create TABLE IF NOT EXISTS '.  $this->config->item('table_perfix') .'products as select * from '.  $this->config->item('table_perfix') .'helix_products');
       if($r)$ret['success'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Transection Success</td></tr>  </table>';
       else $ret['error'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>ERROR !! </td></tr>  </table>';  
       return $ret;
    	
    }
    
    function diamondscountbysellerswithshape(){
    	$qry = "SELECT count(*) as total, owner,shape FROM ".config_item('table_perfix')."products group by owner,shape order by owner,shape";
		$result = 	$this->db->query($qry);
		return $result->result_array();	
		
    }
    function diamondscountbysellers(){
    	$qry = "SELECT count(*) as total, owner,shape FROM ".config_item('table_perfix')."products group by owner order by owner";
		$result = 	$this->db->query($qry);
		return $result->result_array();	
		
    }
    
    function getminmaxForShape($shape = 'B', $filed = 'color' , $isminmax = false){
    	if(!$isminmax) $qry = "SELECT ".$filed." as fields FROM ".config_item('table_perfix')."products where shape = '".$shape."' group by $filed order by $filed";
    	else  {
    		$qry = "SELECT MIN(".$filed."1) as min, MAX(".$filed."2) as max FROM ".config_item('table_perfix')."pricescopebasic where shape = '".$shape."'";
    	}
		$result = 	$this->db->query($qry);
		return $result->result_array();	
    }

    
    
    function orders($post , $action = 'view' , $id =0)
	{
	  		
	  		$retuen  = array();
	    	$retuen['error'] 	= '';
	    	$retuen['success'] 	= '';
	    	  if($action == 'delete'){
					   		$items = rtrim($_POST['items'],",");
							$sql = "DELETE FROM ".$this->config->item('table_perfix')."orderdetails WHERE orderid IN (select id FROM ".$this->config->item('table_perfix')."order WHERE id IN($items))";
							$result = $this->db->query($sql);

							$sql = "DELETE FROM ".$this->config->item('table_perfix')."order WHERE id IN ($items)";
							$result = $this->db->query($sql);
							
								
							$total = count(explode(",",$items)); 
							$retuen['total'] = $total;
						 	 
					   	}
			   
					   		 
					  
	  	
	    return $retuen;
	  
	    
	 }
	 
	 
	 
	 function getPriceScopeStructure($orderby = 'id'){
	 	$qry = "SELECT * FROM ".config_item('table_perfix')."pricescopest order by ". $orderby;
		$result = 	$this->db->query($qry);
		return $result->result_array();	
	 }
	 
	 function savePriceScopeStructure($post){
	 	$ret = array();
	 	$ret['error'] = '';
	 	$ret['success'] = '';
	 	
	 	$stack = array();	
	 	for($i= 0; $i<$post['totalrows'];$i++){
	 	 	if(isset($post['isexport'.$i])){ array_push($stack,$post['exportorder'.$i]);}
	 	}
	 	
	 	$stack2 = array_unique($stack);
	 	
	 	if(sizeof($stack) != sizeof($stack2)){
	 	$ret['error'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>ERROR !! Export Order Must be Unique</td></tr>  </table>';
	 	}else{
				for($i= 0; $i<$post['totalrows'];$i++){
												 		if(isset($post['isexport'.$i])){
												 											$this->db->where('id',$i+1);
																							$t = $this->db->update($this->config->item('table_perfix').'pricescopest',array('isexport' => '1','exportorder' => $post['exportorder'.$i], 'exportname' => $post['exportname'.$i]));
																							if(!$t) $ret['error'] .= '<br>ERROR !';
												 		}else {
												 											$this->db->where('id',$i+1);
																							$t = $this->db->update($this->config->item('table_perfix').'pricescopest',array('isexport' => '0','exportorder' => '', 'exportname' => ''));
																							if(!$t) $ret['error'] .= '<br>ERROR !';
												 		}
				 	}
				 	
				 	if($ret['error'] == '') $ret['success'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Price Scope Structure has been saved</td></tr>  </table>';
			        else $ret['error'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>ERROR !! </td></tr>  </table>';
	 	}
	 	return  $ret;	
	 }
	 
	 function getPriceScopeCSV($qry = 'id' , $where = ''){
	 	$where = ($where == '') ? '1  = 0' : $where; 
	 	$qry = "SELECT " . $qry . " FROM ".config_item('table_perfix')."products where ". $where;
		$result = 	$this->db->query($qry);
		return $result->result_array();	
	 }
	 
	 function savePriceScopeBasic($query = ''){
	 	if($query !=''){
	 		$result = 	$this->db->query($query);
	 	}
	 	
	 }
	 
	 
	 function PricescopeExport($shape = 'B'){
	 	$qry = "SELECT * FROM ".config_item('table_perfix')."pricescopebasic where shape = '". $shape . "'";
		$result = 	$this->db->query($qry);
		return $result->result_array();	
	 }
	 
	 function gettemplate($page =1 , $rp = 10 ,$sortname = 'title' ,$sortorder = 'desc' ,$query= '' , $qtype = 'title' , $oid = ''){
		
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'templates where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
	 	$sql2 = 'SELECT id FROM  '. $this->config->item('table_perfix').'templates  where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
    	
		$results['result']  = $result->result_array();
		 
    	return $results;
		
	}
	
	function getaddedittemplate($action = 'view' , $post = array() , $id = ''){
	  if($action == 'get' && $id != '')	{
		$qry = "SELECT * FROM ".config_item('table_perfix')."templates 
				WHERE id = '".$id."'
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
	  }
	}
	 

 
 
 /*    Shahadat */
	
	function orderdetails($orderid)
	{ 
	   /*
	   $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'order where 1=1 and id='.$orderid;
		///var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['order']  = $result->result_array();	
		return $results;
		*/
		$this->db->select('*');
        $this->db->from($this->config->item('table_perfix').'order');
        $this->db->join($this->config->item('table_perfix').'orderdetails', $this->config->item('table_perfix').'order.id= '.$this->config->item('table_perfix').'orderdetails.orderid  where '.$this->config->item('table_perfix').'order.id='.$orderid);
        // WHERE id = $id ... goes here somehow...
		//$this->db->where($this->config->item('table_perfix').'order.id='.$orderid);
        $result = $this->db->get();
        return $result->result_array();	
	}
	
	function orderinfo($orderid)
	{ 
	   
	   $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'order where 1=1 and id='.$orderid;
		///var_dump($sql); 
	 	$result = $this->db->query($sql);	
		return $result->result_array();
   }
	
	
	function getCustomerByID($customerid)
	{ 
	   $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'customerinfo where 1=1 and id='.$customerid;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
			
		return $result->result_array();
	}
	
	function getShippingByID($orderid)
	{ 
	   $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'shippinginfo where 1=1 and orderid='.$orderid;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		 
		return $result->result_array();	
	}
	
	
	function getProductByLot($lotnumber)
	{
	     $sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'products where 1=1 and lot='.$lotnumber;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		 	
		return $result->result_array();
	}
	/**/

 
 
 
 function uploadCSVFile()
 {
        $file=config_item('base_url')."rings.csv";
		$qry="LOAD DATA INFILE ".$file."
             INTO TABLE ".config_item('table_perfix')."jewelries 
			 FIELDS TERMINATED BY ','
			 OPTIONALLY ENCLOSED BY '\"\"'
			 LINES TERMINATED BY '\r\n';
		    ";
		$return = 	$this->db->query($qry);	
 }
 
 function uploadCSVFile2()
 {
        $file=config_item('base_url')."rings.csv";
		 $fcontents = file ($file);
		for($i=0; $i<sizeof($fcontents); $i++) 
		{
             $line = trim($fcontents[$i]);
             echo "$line<BR>";
             $arr = explode("\"", $line);
             echo "$arr";
             $qry = "insert into ".config_item('table_perfix')."jewelries values (". implode("'", $arr)
.")";
             $this->db->query($qry);	
		}
 }
 
 function downloadCSV()
 {
        $sql = "SELECT * FROM ".config_item('table_perfix')."jewelries";
		$values=$this->db->query($sql);
		$result = $values->result_array();
		//print_r($result);
		foreach($result as $key => $value) {
		  foreach($value as $k => $v) {
	     	$csv_output .= $v.", ";
		}
		$csv_output .= "\n";
		}
		file_put_contents(config_item('base_url').'csv.csv',$csv_output);
		$filename = $file."_".date("Y-m-d_H-i",time());
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header( "Content-disposition: filename=".$filename.".csv");
		print $csv_output;
		exit;
 }
 	function rolex($post , $action = 'view' , $id =0, $imageSmall = '', $imageBig = '')
  	{
  		
  		$retuen  = array();
    	$retuen['error'] 	= '';
    	$retuen['success'] 	= '';
    	  if($action == 'delete'){
				   		/*$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."watches WHERE watches IN ($items)";
						$result = $this->db->query($sql);
						$total = count(explode(",",$items)); 
						$retuen['total'] = $total;*/
						$items = $_POST['items'];//rtrim($_POST['items'],",");
						$itemsArr = explode(',',$items);
						foreach($itemsArr AS $index=>$value) {
							if($value !='') {
								$itemDetail = $this->getAllByWatchID($value);
								//$itemDetail = $this->getAllByProductID(67706);
								$sql = "DELETE FROM ".$this->config->item('table_perfix')."watches WHERE productID = $value";
								$result = $this->db->query($sql);
								if($itemDetail['ebayid'] != '' && $itemDetail['ebayid'] !=0) {
									$status = $this->endFixedPriceBid($itemDetail['ebayid']);
								}

							}
						}
						$items = rtrim($_POST['items'],",");
						$total = count(explode(",",$items)); 
						$retuen['total'] = $total;
					 	 
				   	}
		  else{
				   		 
				   		
					if(is_array($post)){	
					            $name	        = isset($post['name']) 		? $post['name'] : 0;
				 				$price 			= isset($post['price']) 		? $post['price'] : 0;
							 	$uprice 		= isset($post['uprice']) 		? $post['uprice'] : '';
							 	$model_number   	= isset($post['model_number']) 	? $post['model_number'] : '';
							 	$tempbrand   		= isset($post['brand']) 		? $post['brand'] : '';
							 	$gender   		= isset($post['gender']) 		? $post['gender'] : '';
							 	$metal   		= isset($post['metal']) 		? $post['metal'] : '';
							 	//$finger_size   	= isset($post['finger_size']) 	? $post['finger_size'] : '';
							 	$sku  = isset($post['sku']) ? $post['sku'] : '';
							 	$description 	= isset($post['description']) 	? $post['description'] : '';
							    $small_img 		= isset($post['small_image'])   ? $post['small_image'] : '';
							    $big_image	= isset($post['big_image']) 	? $post['big_image'] : '';
							    $style          = isset($post['style'])			? $post['style'] : '';
							   if($tempbrand=='-1'){					    
									$brand = $post['otherbrandname'];
								} else {					    
									$brand = $tempbrand;
								}
								
								$warranty          = isset($post['warranty'])			? $post['warranty'] 	: '';
								$papers          = isset($post['papers'])			? $post['papers'] 	: '';
								$box          = isset($post['box'])			? $post['box'] 	: '';
								$lugwidth          = isset($post['lugwidth'])			? $post['lugwidth'] 	: '';
								$thickness          = isset($post['thickness'])			? $post['thickness'] 	: '';
								$height          = isset($post['height'])			? $post['height'] 	: '';
								$width          = isset($post['width'])			? $post['width'] 	: '';
								$movement          = isset($post['movement'])			? $post['movement'] 	: '';
								$calibre          = isset($post['calibre'])			? $post['calibre'] 	: '';
								$crystal          = isset($post['crystal'])			? $post['crystal'] 	: '';
								$features          = isset($post['features'])			? $post['features'] 	: '';
								$bezel          = isset($post['bezel'])			? $post['bezel'] 	: '';
								$markers          = isset($post['markers'])			? $post['markers'] 	: '';
								$hands          = isset($post['hands'])			? $post['hands'] 	: '';
								$dial          = isset($post['dial'])			? $post['dial'] 	: '';
								$band          = isset($post['band'])			? $post['band'] 	: '';
							 	
			 		
			 		if($action == 'add')
			 		{
			 			 	//die('pp');
				 		$isinsert = $this->db->insert($this->config->item('table_perfix').'watches',
				 		array(
		  						  'productName'			=> $name,
		  						  'productDescription'	=> $description,
		  						  'price1'		=> $price,
		  						  'price2'			=> $uprice,
		  						  'SKU'			=> $sku,
		  						  'metal'			=> $metal,
		  						  //'finger_size'		=> $finger_size,
		  						  'style'	=> $style,
		  						  'gender'	=> $gender,
		  						  'model_number'	=> $model_number,
		  						  'brand'		=> $brand,
								  'band'		=> $band,
								  'dial'		=> $dial,
								  'hands'		=> $hands,
								  'markers'		=> $markers,
								  'bezel'		=> $bezel,
								  'features'		=> $features,
								  'crystal'		=> $crystal,
								  'movement'		=> $movement,
								  'calibre'		=> $calibre,
								  'width'		=> $width,
								  'height'		=> $height,
								  'thickness'		=> $thickness,
							      'lugwidth'		=> $lugwidth,
								  'box'		=> $box,
								  'papers'		=> $papers,
								  'warranty'		=> $warranty,
								  //'insertionDate'		=> 'now()'
							 ));
			 		   $rid = $this->db->insert_id() ;
			 		   //$isinsert = $this->db->insert($this->config->item('table_perfix').'ringanimation',array('stock_num' => $rid));
			 		}
			 		if($action == 'edit')
			 		{
			 		$rid = $id;	
			 		$this->db->where('productID' , $id);
			 		$isinsert = $this->db->update($this->config->item('table_perfix').'watches',
			 		array(			  
			  						  'productName'			=> $name,
			  						  'productDescription'	=> $description,
			  						  'price1'				=> $price,
			  						  'price2'				=> $uprice,
			  						  'SKU'					=> $sku ,
			  						  'metal'				=> $metal,
			  						  'style'				=> $style,
								  	  'gender'				=> $gender,
									  'brand'				=>$brand,
									  'model_number'		=>$model_number,
									  'band'		=> $band,
									  'dial'		=> $dial,
									  'hands'		=> $hands,
									  'markers'		=> $markers,
									  'bezel'		=> $bezel,
									  'features'		=> $features,
									  'crystal'		=> $crystal,
									  'movement'		=> $movement,
									  'calibre'		=> $calibre,
									  'width'		=> $width,
									  'height'		=> $height,
									  'thickness'		=> $thickness,
									  'lugwidth'		=> $lugwidth,
									  'box'		=> $box,
									  'papers'		=> $papers,
									  'warranty'		=> $warranty,
     								//  'updated'				=> now()
 
						 ));
			 		}
								 											
				 if($_FILES['image_small']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image_small', 'images/watches', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/watches' , $rid , 'watches', 'productID' , $rid , 'thumb');                      
				
	             if($_FILES['big_image']['name'] != '') 															
	             $this->uploadfile($_FILES, 'big_image', 'images/watches', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/watches' , $rid , 'watches', 'productID' , $rid , 'large');           
				 
				  if($_FILES['image_small2']['name'] != '') 															
	             $this->uploadfile($_FILES, 'image_small2', 'images/watches', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/watches' , $rid , 'watches', 'productID' , $rid , 'image_small2'); 

				   if($_FILES['big_image2']['name'] != '') 															
	             $this->uploadfile($_FILES, 'big_image2', 'images/watches', 'jpeg,gif,jpg,bmp,png' , config_item('base_path').'images/watches' , $rid , 'watches', 'productID' , $rid , 'image_big2'); 
				
				$productArr = $this->getAllByWatchID($rid);
			    $this->addWatchtoEbay($productArr);
	            
				if($isinsert) $retuen['success'] .= '<h1 class="success">Watch info Successfully '.ucfirst($action).'ed .</h1><br /><br /><small> <a href="'.config_item('base_url').'admin/rolex/edit/'.$rid.'">Click Here </a> To View/Edit</small>';
         }			
		 
  	}
  	
    return $retuen;
  
    
 }
 
 function getAllByWatchID($pid){
 		$qry = "SELECT * FROM ".config_item('table_perfix')."watches 
 				WHERE productID = '".$pid."'  				
				";
		$return = 	$this->db->query($qry);
		$result = $return->result_array();	
		return isset($result[0]) ? $result[0] : false;
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
	
 function getrolex( $page =1 , $rp = 10 ,$sortname = 'lot' ,$sortorder = 'desc' ,$query= '' , $qtype = 'lot' , $oid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND $qtype LIKE '%$query%' ";
			if($oid != '') $qwhere .= " AND id = $oid";
		  
			 
		$sql = 'SELECT  * FROM  '. $this->config->item('table_perfix').'watches where 1=1 '. $qwhere . ' ' . $sort . ' '. $limit;
		//var_dump($sql); 
	 	$result = $this->db->query($sql);
		$results['result']  = $result->result_array();	
		$sql2 = 'SELECT productID FROM  '. $this->config->item('table_perfix').'watches where 1=1 '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
    	
	}
	function getRolexCategoryID($gender) {
		
		switch($gender) {
			case 'men':
				$storeCategoryID = 1027754010;
				break;
			case 'ladies':
				$storeCategoryID = 1027755010;
				break;
			default:
				$storeCategoryID = 1027754010;
				break;
		}
		return $storeCategoryID;
	}

	function addWatchtoEbay($detail, $action='Add'){
		//print_r($detail);
		switch($detail['brand']) {
			case'Rolex':
				$catgoryStoreID = $this->getRolexCategoryID($detail['gender']);
				break;
			case'CONCORD':
				$catgoryStoreID = 952190010;
				break;
			case'CHOPARD':
				$catgoryStoreID = 2857141;
				break;
			case'PANERAI':
				$catgoryStoreID = 2857142;
				break;
			case'OMEGA':
				$catgoryStoreID = 5653330;
				break;
			case'ANTIQUE':
				$catgoryStoreID = 5653331;
				break;
			case'FRANCK MULLER':
				$catgoryStoreID = 462196010;
				break;
			case'PATEK PHILIPPE':
				$catgoryStoreID = 462197010;
				break;
			case'IWC':
				$catgoryStoreID = 462198010;
				break;
			default:
				$catgoryStoreID = 952189010;
				break;
		}
		
		$requestArray['listingType'] = 'StoresFixedPrice';
		$requestArray['primaryCategory'] = '31387';
		$metalarray =  array('gold_ss'=>'SS & Gold', 'ss'=>'SS', 'gold'=>'Gold'); 
        $requestArray['itemTitle']       = $detail['model_number'].' '.ucfirst($detail['brand']).' '.$detail['productName'].' '.$metalarray[$detail['metal']].' '.ucfirst($detail['style']);
		$requestArray['productID']       = $detail['productID'];
		$watchDetail = '<div><p align="justify">'.$detail['productDescription'].'</p>';
		
		$watchDetail .='<p><br><B>Brand:</B>'.ucfirst($detail['brand']);
		$watchDetail .='<br><B>Retail:</B>'.$detail['price1'];
		$watchDetail .='<br><B>Our Price:</B>'.$detail['price2'] * 1.12;
		$watchDetail .='<br><B>Gender:</B>'.ucfirst($detail['gender']);
		$watchDetail .='<br><B>Case:</B>'.$metalarray[$detail['metal']];
		$watchDetail .='<br><B>Band:</B>'.ucfirst($detail['band']);
		$watchDetail .='<br><B>Dial:</B>'.ucfirst($detail['dial']);
		//$watchDetail .='<br>'.ucfirst($detail['hands']);
		//$watchDetail .='<br>'.ucfirst($detail['markers']);
		$watchDetail .='<br><B>Bezel:</B>'.ucfirst($detail['bezel']);
		$watchDetail .='<br><B>Features:</B>'.ucfirst($detail['features']);
		$watchDetail .='<br><B>Crystal:</B>'.ucfirst($detail['crystal']);
		$watchDetail .='<br><B>Movement:</B>'.ucfirst($detail['movement']);
		$watchDetail .='<br><B>Calibre:</B>'.ucfirst($detail['calibre']);
		$watchDetail .='<br><B>Width:</B>'.ucfirst($detail['width']);
		$watchDetail .='<br><B>Height:</B>'.ucfirst($detail['height']);
		$watchDetail .='<br><B>Thickness:</B>'.ucfirst($detail['thickness']);
		$watchDetail .='<br><B>Lug Width:</B>'.ucfirst($detail['lugwidth']);
		$watchDetail .='<br><B>Condition:</B>'.ucfirst($detail['style']);
		$watchDetail .='<br><B>Papers:</B>'.ucfirst($detail['papers']);
		$watchDetail .='<br><B>Warranty:</B>'.ucfirst($detail['warranty']);
		$watchDetail .='</p></div>';
		$storeImage = config_item('base_url').'images/upperbar02.jpg';
		$colorImage = config_item('base_url').'images/Color_Profile.jpg';
		$watchImage = config_item('base_url').$detail['thumb'];
		$price = round($detail['price2'] * 1.12);
		$watchDetail .= '<div>
		<table align="center" style="border-spacing: 0px;width:100%;"><tr><td><div id="EBdescription">
		<TABLE width=598 align=center border=0>
		<TBODY>
		<TR>
		<TD align=middle width=626>
		<MARQUEE><FONT face=Verdana color=black size=5><B>Welcome to Alan G, your source for GIA &amp; EGL certified diamonds </B></FONT></MARQUEE>
		<P>
		<MARQUEE><FONT face=Verdana size=2><B>(877)-425-2645</B></FONT></MARQUEE>
		<P></P>
		<MARQUEE><A href="mailto:alangjewelers@aol.com?subject=ebay auction">alangjewelers@aol.com</A></MARQUEE><BR>&nbsp;</TD></TR>
		<TR>
		<TD align=middle width=626><IMG height=99 src="'.$storeImage.'" width=900 border=0></TD></TR></TBODY></TABLE>
		<TABLE id=AutoNumber2 style="BORDER-COLLAPSE: collapse" borderColor="#111111" width=887 align=center border=0>
		<TBODY>
		<TR>
		<TD vAlign=center align=middle width=506><B><FONT face="Times New Roman, Times, serif" color="#ff0000" size=5>'.ucfirst($detail[brand]).'</FONT></B></TD>
		<TD width=426 rowSpan=3>
		<TABLE id=AutoNumber3 style="BORDER-COLLAPSE: collapse" borderColor="#111111" height=302 cellSpacing=1 cellPadding=2 width=426 border=0>
		<TBODY>
		<TR>
		<TD align=right width=420 bgColor="#ffffff" colSpan=2 height=20>';
		$watchDetail .= '
		<TABLE id=AutoNumber11 style="BORDER-COLLAPSE: collapse" borderColor="#111111" height=70 cellSpacing=1 cellPadding=2 width=169 align=right border=0>
		<TBODY></TBODY></TABLE></TD></TR>
		<TR bgColor="#ffffff">
		<TD align=right width=420 colSpan=2 height=19>
		<P align=center><FONT face="Times New Roman, Times, serif" size=6><B><FONT color="#0000ff">Item Information </FONT></B><BR></FONT></P></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Manufacturer:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[brand]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Model Name:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[productName]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Gender:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[gender]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Style:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[model_number]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Case:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.$metalarray[$detail[metal]].'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Dial:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[dial]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Band:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[band]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><B><FONT face="Times New Roman, Times, serif" color="#1e2d3b" size=4>Bezel</FONT></B><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[bezel]).'
		</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Crystal:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[crystal]).'</FONT><FONT size=4> </FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Movement:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[movement]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Features:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[features]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><B><FONT face="Times New Roman, Times, serif" color="#1e2d3b" size=4>Width</FONT></B><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT size=4><FONT face="Times New Roman, Times, serif" color="#333333">'.ucfirst($detail[width]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Condition:</FONT></B></FONT><FONT size=4> </FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT face="Times New Roman, Times, serif" color="#333333" size=4>'.ucfirst($detail[style]).'</FONT><FONT size=4> </FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Papers:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT size=4><FONT face="Times New Roman, Times, serif" color="#333333">'.ucfirst($detail[papers]).'</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><B><FONT face="Times New Roman, Times, serif" color="#1e2d3b" size=4>Box</FONT></B><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT size=4><FONT face="Times New Roman, Times, serif" color="#333333">Watch Box&nbsp;&nbsp; </FONT>&nbsp;</FONT></TD></TR>
		<TR>
		<TD noWrap align=right width=130 bgColor="#d7d7d7" height=19><FONT face="Times New Roman, Times, serif" size=4><B><FONT color="#1e2d3b">Retail:</FONT></B></FONT></TD>
		<TD align=left width=285 bgColor="#efefef" height=19><FONT size=4><FONT face="Times New Roman, Times, serif" color="#333333">$'.$detail[price1] .'</FONT></TD></TR>	
		</TBODY></TABLE></TD></TR>
		<tr>
<td align="middle" height="200" valign="center" width="506">
<table height="200">
<tbody>
<tr>
<td height="200">
<font face="Times New Roman, Times, serif">
<img src="'.$watchImage.'" border="0" height="598" width="312"></font></td></tr></tbody></table></td></tr></table>
	';
		$watchDetail .= '
		<P>&nbsp;</P>
		<TABLE width=766 align=center border=0>
		<TBODY>
		<TR>
		<TD width=758>
		<P align=center><FONT face=Verdana size=5>BID WITH CONFIDENCE! </FONT></P>
		<P dir=rtl align=center><FONT color="#ff0000" size=4><I><B>OVER 1200 POSITIVE FEEDBACKS</B></I></FONT></P>
		<P dir=rtl align=center><B><I><FONT face=Verdana color="#00ff00" size=5>&nbsp;All our watches are guaranteed to be <U>100%</U></B> <B>Authentic</B></FONT></I><B><FONT face=Verdana color="#00ff00" size=5><I><BR>&nbsp;</I></FONT></B></P><BR>&nbsp;</TD>
		<TD align=right width=4 rowSpan=2>
		<TABLE width="100%" border=0>
		<TBODY></TBODY></TABLE></TD></TR></TBODY></TABLE>
		<P align=center>
		<P>
		<CENTER>
		<P><FONT face="Times New Roman, Times, serif"><I><FONT color="#333333" size=2></FONT></I></FONT>&nbsp;</P>
		<TABLE id=AutoNumber6 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=750 border=0>
		<TBODY>
		<TR>
		<TD width="100%">
		<P align=center><B><FONT size=6><SPAN style="BACKGROUND-COLOR: #ffff00">Your Free Gift </SPAN></FONT></B></P></TD></TR>
		<TR>
		<TD width="100%" bgColor="#eeeeee">
		<UL>
		<LI><FONT face=Verdana size=2>Free appraisal for the estimated retail value of the item with every purchase. </FONT></LI>
		<LI><FONT face=Verdana size=2>Free Box</FONT></LI>
		<LI><FONT face=Verdana size=2>Free Ground Shipping to all US destinations (Additional charge for Hawaii &amp; Alaska)</FONT></LI></UL>
		<P></P></TD></TR></TBODY></TABLE>
		<P>&nbsp;</P>
		<TABLE id=AutoNumber6 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=750 border=0>
		<TBODY>
		<TR>
		<TD width="100%">
		<P align=center><A name=Shipping><SPAN style="BACKGROUND-COLOR: #ffff00"><B><FONT face="Times New Roman, Times, serif" size=6>Shipping</FONT></B></SPAN></A></P></TD></TR>
		<TR>
		<TD width="100%" bgColor="#eeeeee">
		<UL>
		<LI><FONT face=Verdana size=2>We use UPS or FedEx to ship our packages for all Domestic &amp; International purchases</FONT></LI>
		<LI><FONT face=Verdana size=2>We use USPS for all APOs </FONT></LI>
		<LI><FONT face=Verdana,Arial color="#000000" size=2>Items ship up to 5 days after your payment has been received (shipping cut off time is 1:00 PM Pacific standard time)</FONT></LI></UL>
		<P></P></TD></TR></TBODY></TABLE>
		<P>&nbsp;</P>
		<TABLE id=AutoNumber6 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=750 border=0>
		<TBODY>
		<TR>
		<TD width="100%">
		<P align=center><A name=Shipping><B><FONT face="Times New Roman, Times, serif" size=6><SPAN style="BACKGROUND-COLOR: #ffff00">About Us</SPAN></FONT></B></A></P></TD></TR>
		<TR>
		<TD width="100%" bgColor="#eeeeee">
		<P>Alan G. Jewelers has been dedicated to excellent customer satisfaction and lowest prices in the jewelry business for nearly 25 years. We are a direct diamond importer and watch dealer.&nbsp; We have expanded our business on Ebay since 2003 to provide the same exceptional quality and value to our clients.&nbsp; All our diamonds and jewelry items are guaranteed to appraise for twice the amount of purchase price. <FONT color="#ff0000">All our diamonds are guaranteed to be natural, with no enhancements or treatments.</FONT> All our watches are authentic and are accompanied with either manufacturers papers or Alan G certificate of authenticity.&nbsp; Our goal is to reach the highest customer satisfaction possible. We welcome the opportunity to serve your jewelry needs.&nbsp; <BR></FONT></B></FONT></P>
		<P></P></FONT>
		<P align=center><FONT face=Verdana color="#ff0000" size=5>Please review our feedback for your Confidence.</FONT><FONT size=5> </FONT></P>
		<P></P></TD></TR></TBODY></TABLE>
		<P>&nbsp;</P>
		<TABLE id=AutoNumber7 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=750 border=0>
		<TBODY>
		<TR>
		<TD width="100%">
		<P align=center><B><FONT size=6><SPAN style="BACKGROUND-COLOR: #ffff00">Payment Option</SPAN></FONT></B></P></TD></TR>
		<TR>
		<TD width="100%" bgColor="#eeeeee">
		<P align=left><FONT face=Verdana size=2>We accept <FONT color="#ff0000">Electronic BANK WIRE TRANSFER, LOCAL PICK UP, CHECKS.&nbsp; </FONT></P>
		<P align=justify>PLEASE ALLOW 15 BUSINESS DAYS FOR CHECKS TO CLEAR OUR BANK.&nbsp; INTERNATIONAL BUYERS ARE ACCEPTED ONLY WITH ELECTRONIC BANK WIRE TRANSFER, NO EXCEPTIONS.&nbsp; MERCHANT CREDIT CARD ACCEPTED, ADDITIONAL FEE MAY APPLY.</P></FONT></TD></TR></TBODY></TABLE>
		<P style="MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; LINE-HEIGHT: 100%" align=left>&nbsp;</P>
		<P style="MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; LINE-HEIGHT: 100%" align=left>&nbsp;</P>
		<P style="MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; LINE-HEIGHT: 100%" align=left><FONT face="Times New Roman, Times, serif" size=2></FONT>&nbsp;</P>
		<TABLE id=AutoNumber8 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=639 border=0>
		<TBODY>
		<TR>
		<TD width=756>
		<P align=center><B><FONT face="Times New Roman, Times, serif" size=6><SPAN style="BACKGROUND-COLOR: #ffff00">Return Policy</SPAN></FONT></B></P></TD></TR>
		<TR vAlign=center>
		<TD class=wht_bder noWrap width=756>
		<TABLE height=25 cellSpacing=0 cellPadding=0 width=756 border=0>
		<TBODY>
		<TR>
		<TD align=middle width=756><A href="http://stores.shop.ebay.com/Alan-G-Jewelers/RETURN-POLICY.html">RETURN POLICY</A></TD></TR>
		<TR>

		<TD align=middle width=756><A href="http://stores.shop.ebay.com/Alan-G-Jewelers/FAQ.html">FAQ</A></TD></TR>
		<TR>
		<TD align=middle width=756><A href="http://stores.shop.ebay.com/Alan-G-Jewelers/SHIPPING-INFORMATION.html">SHIPPING INFORMATION</A></TD></TR>
		<TR>
		<TD align=middle width=756>&nbsp;</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
		<P>&nbsp;</P>
		<TABLE id=AutoNumber9 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=0 cellPadding=0 width=750 border=0>
		<TBODY>
		<TR>
		<TD>
		<P align=center><B><FONT face="Times New Roman, Times, serif" size=6><SPAN style="BACKGROUND-COLOR: #ffff00">Disclaimer </SPAN></FONT></B></P></TD></TR>
		<TR>
		<TD bgColor="#eeeeee">
		<P><FONT face=Arial size=2>Alan G is an independent company and it is not affiliated with any watch company or manufacturers.&nbsp; All watches are registered trademark of their company.&nbsp; Alan G warranties all of its watches.&nbsp; Descriptions of our watches are done to the best of our ability.&nbsp; One (1) year warranty is provided with purchase of each watch.&nbsp; Alan G is not responsible for any problems arising from misuse or alterations of your watch.&nbsp;&nbsp; </FONT></P>
		<P></P></TD></TR></TBODY></TABLE>
		<P class=MsoNormal>&nbsp;</P>
		<TABLE id=AutoNumber10 style="BORDER-COLLAPSE: collapse" borderColor="#111111" cellSpacing=1 cellPadding=2 width=750 border=0>
		<TBODY>
		<TR>
		<TD>
		<P align=center><B><SPAN style="BACKGROUND: #ffff00"><FONT size=6>Alan G Watch Warranty:</FONT></SPAN></B></P></TD></TR>
		<TR>
		<TD bgColor="#eeeeee">
		<P><B><SPAN style="FONT-SIZE: 10pt">Full&nbsp;one year; </SPAN></B><SPAN style="FONT-SIZE: 10pt">Alan</SPAN><SPAN style="FONT-SIZE: 10pt">G355 warrants dependable functioning of this watch for a period of&nbsp;one year from the date of purchase. This warranty does not cover loss, tampering, mistreatment or modification through the addition or substitution of parts or accessories not supplied at the time of sale. In the event of malfunction arising with in a year of warranty period, </SPAN><SPAN style="FONT-SIZE: 10pt">Alan</SPAN><SPAN style="FONT-SIZE: 10pt">G355 will cause the defect to be remedied at no cost to the consumer, once the consumer delivers the watch to our store.</SPAN></P>';

		$watchDetail .='<P><B><SPAN style="FONT-SIZE: 10pt">Full&nbsp;two years; </SPAN></B><SPAN style="FONT-SIZE: 10pt">Alan</SPAN><SPAN style="FONT-SIZE: 10pt">G555 warrants dependable functioning of this watch for a period of&nbsp;two years from the date of purchase. This warranty does not cover loss, tampering, mistreatment or modification through the addition or substitution of parts or accessories not supplied at the time of sale. In the event of malfunction arising with in two years of warranty period, </SPAN><SPAN style="FONT-SIZE: 10pt">Alan</SPAN><SPAN style="FONT-SIZE: 10pt">G355 will cause the defect to be remedied at no cost to the consumer, once the consumer delivers the watch to our store. (this can be purchased for additional charge of $99.00)</SPAN></P></TD></TR><BR></TBODY></TABLE>
	</div>';
		//echo $watchDetail;
		//die('pp');
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($watchDetail);
        } else {
            $requestArray['itemDescription'] = $watchDetail;
        }
		
		$requestArray['listingDuration'] = 'Days_7';
        $requestArray['startPrice']      = $price;
		//$requestArray['minimumbestofferprice']  = $detail['price2'] * 1.12;
        $requestArray['buyItNowPrice']   = 0.0;
        //$requestArray['quantity']        = $detail['quantity'];
		$requestArray['quantity']        = '1';

		$requestArray['ItemSpecification'] = $this->getItemWatchSpecification($detail);
		$requestArray['dispatchTime'] = '3';
		$requestArray['packageDepth'] = 4;
		$requestArray['packageLength'] = 16;
		$requestArray['packageWidth'] = 12;
		$requestArray['weightMajor'] = 3;
		$requestArray['weightMinor'] = 4;

		/*if ($requestArray['listingType'] == 'StoresFixedPrice') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for SIF
          $requestArray['listingDuration'] = 'GTC';
        }
        
        if ($listingType == 'Dutch') {
          $requestArray['buyItNowPrice'] = 0.0;   // don't have BuyItNow for Dutch
        }*/
		
		/*if($detail['ebayid']!='' && $detail['ebayid'] !=0) {
			if($detail['ebay_cat_id'] != $catgoryStoreID) {
				//$this->endFixedPriceBid($detail['ebay_cat_id'], $catgoryStoreID, $detail['ebayid']);

			}
		}*/

		$requestArray['storeCategoryID'] = $catgoryStoreID;
		$requestArray['itemID'] = $detail['ebayid'];
		$requestArray['image'] = config_item('base_url').$detail['thumb'];
		$requestArray['replace_gurantee'] = 'Days_14';
		//print_R($requestArray);

//die('tt');
		//if($action=='Add') {
		if($detail['ebayid']=='' || $detail['ebayid']==0) {	
			$itemID = $this->sendRequestEbay($requestArray);
		} else {
			//$itemID = $this->updateEbayItem($requestArray);
			$itemID = $this->updateRequestEbay($requestArray);
		}
		return $itemID;
	}

	function sendRequestEbay($requestArray, $section='watches') {
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
		$verb = 'AddItem';
		$compatabilityLevel = 601;
		//echo 'devid'.$devID;
		//print_r($requestArray);
		///Build the request Xml string
		$requestXmlBody  = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= '<ErrorLanguage>en_US</ErrorLanguage>';
		$requestXmlBody .= "<Version>$compatabilityLevel</Version>";
		$requestXmlBody .= '<Item>';
		$requestXmlBody .= '<Country>US</Country>';
		$requestXmlBody .= '<Currency>USD</Currency>';
		$requestXmlBody .= "<Description><![CDATA[".$requestArray['itemDescription']."]]></Description>";
		/*$requestXmlBody .= '<ListingDetails>';
		$requestXmlBody .= '<MinimumBestOfferPrice currencyID="USD">'.$requestArray['minimumbestofferprice'].'</MinimumBestOfferPrice>';
		$requestXmlBody .= '</ListingDetails>';
		$requestXmlBody .= '<DispatchTimeMax>1</DispatchTimeMax>';*/
		$requestXmlBody .= "<ListingType>".$requestArray['listingType']."</ListingType>";
		$requestXmlBody .= "<ListingDuration>".$requestArray['listingDuration']."</ListingDuration>";
		$requestXmlBody .= '<Location><![CDATA[Los Angeles, CA]]></Location>';
		$requestXmlBody .= '<PaymentMethods>AmEx</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>VisaMC</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>Discover</PaymentMethods>';
		$requestXmlBody .= '<PaymentMethods>PayPal</PaymentMethods>';
		$requestXmlBody .= '<PayPalEmailAddress>alangjewelers@aol.com</PayPalEmailAddress>';
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
		/*if($section=='rings') {
		$requestXmlBody .= '<AttributeSetArray> 
							  <AttributeSet attributeSetID="1952"> 
								<Attribute attributeID="10244"> 
								  <Value> 
									<ValueID>10425</ValueID> 
								  </Value> 
								</Attribute> 
							  </AttributeSet> 
							</AttributeSetArray>';
		}*/
		$requestXmlBody .= $requestArray['ItemSpecification'];
		$requestXmlBody .= "<BuyItNowPrice currencyID=\"USD\">0.00</BuyItNowPrice>";
		/*$requestXmlBody .= '<PictureDetails> 
								<PictureURL>'.$requestArray[image].'</PictureURL>
							 </PictureDetails>';*/
		$requestXmlBody .= "<Quantity>".$requestArray['quantity']."</Quantity>";
		$requestXmlBody .= '<DispatchTimeMax>'.$requestArray['dispatchTime'].'</DispatchTimeMax>';
		$requestXmlBody .= '<BestOfferDetails>
								<BestOfferCount>1</BestOfferCount>
								<BestOfferEnabled>true</BestOfferEnabled>
							</BestOfferDetails>';
		$requestXmlBody .= "<StartPrice>".$requestArray['startPrice']."</StartPrice>";
        $requestXmlBody .= "<Title><![CDATA[".substr($requestArray['itemTitle'],0,54)."]]></Title>";
		$requestXmlBody .= '<ShippingTermsInDescription>True</ShippingTermsInDescription>';
		/*$requestXmlBody .= '<ShippingDetails>
							  <ShippingServiceOptions>
								<ShippingService>Freight</ShippingService>
								<FreeShipping>false</FreeShipping>
							  </ShippingServiceOptions>
							  <ShippingType>FreightFlat</ShippingType>
							</ShippingDetails>
							<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>'.$requestArray['replace_gurantee'].'</ReturnsWithinOption>
								<Description>Please visit our eBay store for a detailed return policy</Description>
								<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
							</ReturnPolicy>';
							$requestArray['packageDepth'] = 3;
		$requestArray['packageLength'] = 12;
		$requestArray['packageWidth'] = 10;
		$requestArray['weightMajor'] = 1;
		$requestArray['weightMinor'] = 2;
							*/
				$requestXmlBody .= '<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
								<RefundOption>MoneyBack</RefundOption>
								<ReturnsWithinOption>'.$requestArray['replace_gurantee'].'</ReturnsWithinOption>
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
		$requestXmlBody .=  '<Storefront>  
								<StoreCategoryID>'.$requestArray['storeCategoryID'].'</StoreCategoryID>  
							</Storefront>';
		$requestXmlBody .= '<RegionID>0</RegionID>';
		$requestXmlBody .= '</Item>';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</AddItemRequest>';
		
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		//echo '<pre>';
		//print_r($responseXml);
		//die('tt');
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		//
		//print_r($responseXml);
	
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
        //   echo '<br>'.die('ppt');
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
              
              $linkBase = "http://cgi.sandbox.ebay.com/ws/eBayISAPI.dll?ViewItem&item=";
              $status = "<a href=$linkBase" . $itemID . ">".$requestArray['itemTitle']."</a> <BR />";
			  
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
            
			if($section == 'watches') {
				//echo $itemID;
				//echo $requestArray['productID'];
				$this->db->where('productID' , $requestArray['productID']);
			 	$isinsert = $this->db->update($this->config->item('table_perfix').'watches',
			 	array(			  
			  			'ebayid'	=> $itemID,
			  	 ));

			  } else {

				  $this->db->where('stock_number' , $requestArray['productID']);
			 	  $isinsert = $this->db->update($this->config->item('table_perfix').'jewelries',
			 	  array(			  
			  			'ebayid'	=> $itemID,
			  		 ));

			  }
		} // if $errors->length > 0
		//echo $status;
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
	//print_r($results);
//die('pp');
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

  	function endFixedPriceBid($itemID) {
		global $userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel;
		include_once(config_item('base_path').'system/application/helpers/eBaySession.php');
		include_once(config_item('base_path').'system/application/helpers/keys.php');
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'EndFixedPriceItem';
		//echo 'devid'.$devID;
		///Build the request Xml string
		
		$requestXmlBody  = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<EndFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<ItemID>$itemID</ItemID>";
		$requestXmlBody .= "<EndingReason>Incorrect</EndingReason>";
		$requestXmlBody .= '<RequesterCredentials><eBayAuthToken>'.$userToken.'</eBayAuthToken></RequesterCredentials>';
		$requestXmlBody .= '</EndFixedPriceItemRequest>';
		
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		//print_r($responseXml);	
		//die('ppt');
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		
		//if there are error nodes
		if($errors->length > 0)
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
	
		} else { //no errors
            
			$responses = $responseDoc->getElementsByTagName("SetStoreCategoriesResponse");
           // print_r($responses);
		
            foreach ($responses as $response) {
                           
              $status = $response->getElementsByTagName('Ack');
			  $status = $status->item(0)->nodevalue;
                          
            } 
			
		} 
			
			return $status;
		

	}
	
	function addRingtoEbay($detail, $action='Add'){
		
		//print_r($detail);
		$ringAnimationArray = $this->getFlashByStockId($detail['stock_number']);
		if(is_array($ringAnimationArray)) {
			if($ringAnimationArray['image45_bg'] != '') {
				$ringImage = config_item('base_url').'images/rings/icons/45'.$ringAnimationArray['image45_bg'];
			} else if ($ringAnimationArray['image180_bg'] != '') {
				$ringImage = config_item('base_url').'images/rings/icons/180'.$ringAnimationArray['image180_bg'];
			} else {
				$ringImage = config_item('base_url').'images/rings/icons/90'.$ringAnimationArray['image90_bg'];
			}
		} else {
			$ringImage = config_item('base_url').'images/rings/'.$detail['small_image'];
		}
		$remArr = array(" ", "ct.", 'ct..', "ct tw");
		$total_carats = str_replace($remArr, "", $detail['total_carats']);
		$requestArray['listingType'] = 'StoresFixedPrice';
		$requestArray['primaryCategory'] = '152869';
        $requestArray['itemTitle']       = $total_carats.' Carats '.$detail['shape'].' Diamond Ring White Gold F-VS2';
		$requestArray['productID']       = $detail['stock_number'];
		$watchDetail = '<div><p align="justify">'.$detail['description'].'</p>';
		$watchDetail .='<p><br><B>Shape:</B>'.ucfirst($detail['shape']);
		$watchDetail .='<br><B>Metal:</B> : White Gold';
		$watchDetail .='<br><B>Finger Size:</B>'.ucfirst($detail['finger_size']);
		$watchDetail .='<br><B>Diamond Count:</B>'.ucfirst($detail['diamond_count']);
		$watchDetail .='<br><B>Total Carats:</B>'.$metalarray[$detail['total_carats']];
		$watchDetail .='<br><B>Style:</B>'.ucfirst($detail['style']);
		$watchDetail .='<br><B>Yellow Gold Price:</B>'.round($detail['yellow_gold_price']*1.25);
		$watchDetail .='<br><B>Platinum Price:</B>'.round($detail['platinum_price']*1.25).'</p></div>';
		$storeImage = config_item('base_url').'images/upperbar02.jpg';
		$destFolder = 'images/diamonds/shape/';
		if(ucfirst($detail['shape']) == 'Asscher') {
			$destFile = $destFolder.'asscherring.jpg';
		}else if(ucfirst($detail['shape']) == 'Cushion') {
			$destFile = $destFolder.'cushionring.jpg';
		}else if(ucfirst($detail['shape']) == 'Emerald') {
			$destFile = $destFolder.'emeraldring.jpg';
		}else if(ucfirst($detail['shape']) == 'Heart') {
			$destFile = $destFolder.'heartring.jpg';
		}else if(ucfirst($detail['shape']) == 'Marquise') {
			$destFile = $destFolder.'marquisering.jpg';
		}else if(ucfirst($detail['shape']) == 'Oval') {
			$destFile = $destFolder.'round.jpg';
		}else if(ucfirst($detail['shape']) == 'Pear') {
			$destFile = $destFolder.'pear_ring.jpg';
		}else if(ucfirst($detail['shape']) == 'Princess') {
			$destFile = $destFolder.'princessrings.jpg';
		}else if(ucfirst($detail['shape']) == 'Radiant') {
			$destFile = $destFolder.'radiantring.jpg';
		} else {
			$destFile = $destFolder.'round.jpg';
		}
		$watchImage = config_item('base_url').$destFile;
		$colorImage = config_item('base_url').'images/Color_Profile.jpg';
		$price = round($detail['white_gold_price'] * 1.25);
		$retailPrice = round($price * 4.5);
		$yellow_gold_price = round($detail['yellow_gold_price'] * 1.25);
		$platinum_price = round($detail['platinum_price'] * 1.25);
		/*$watchDetail .='<div>
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

			<TD width=509 height=18>&nbsp;ITEM Name:&nbsp;'.$detail['name'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=silver height=1>&nbsp;METAL:&nbsp;&nbsp;'.$detail['metal'].'</TD></TR>
			<TR>
			<TD width=509 height=18>&nbsp;Finger Size:&nbsp;'.$detail['finger_size'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=silver height=18>&nbsp;TOTAL WEIGHT:&nbsp;'.$detail['total_carats'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=#00ff00 height=18>&nbsp;SHAPE OF DIAMONDS:&nbsp;&nbsp;&nbsp;'.$detail['shape'].'</TD></TR>
			<TR>
			<TD width=509 bgColor=silver height=18>&nbsp;Style:'.$detail['style'].'</TD></TR>
			<TR>

			<TD width=509 height=22>&nbsp;White Gold Price:&nbsp;'.round($detail['white_gold_price'] * 1.25).'</TD></TR>
			<TR>
			<TD width=509 bgColor=silver height=18>&nbsp;Yellow Gold Price:&nbsp;'.round($detail['yellow_gold_price'] * 1.25).'&nbsp; </TD></TR>
			<TR>
			<TD width=509 height=22>&nbsp;Platinum Price:'.round($detail['platinum_price'] * 1.25).'</TD></TR>
			<TR>
			<TD width=509 bgColor=yellow height=18>Our Price:<B> </B><FONT color=#ff0000>$'.$price.'</FONT>&nbsp; &amp;&nbsp; No Reserve <FONT face=Arial size=2><A href="http://members.ebay.com/aboutme/alan-g-jewelers/" target=_blank><IMG height=8 src="http://pics.ebaystatic.com/aw/pics/aboutme-small.gif" width=23 border=0> </A></FONT></TD></TR></TBODY></TABLE>';
			
			/*<DIV style="WIDTH: 338px; HEIGHT: 521px" align=center>
			<TABLE height=1 width=364 border=0>
			<TBODY>
			<TR>
			<TD style="FONT-SIZE: 11px; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif" vAlign=top width="100%"><FONT color=black>&nbsp;*************************************************</FONT><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>

			<TR>
			<TD vAlign=top width=358 height=289>*<FONT color=#0000ff>Lady\'s Three Stone Ring;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </FONT>
			<P><FONT color=#0000ff>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</FONT><I><B><FONT color=#0000ff size=6>APPRAISED VALUE</FONT></B></I></P>
			<P align=center><I><B><FONT color=#000080 size=5>$'.$price.'</FONT></B></I></P>
			<P align=justify><FONT face=Verdana size=2>This auction is for a <I><B><FONT color=#008080>BRAND NEW</FONT></B></I> lady\'s three stone diamond&nbsp; ring.&nbsp; Total weight of diamonds and gems in this engagement ring is 0.90 carats.&nbsp;&nbsp; </FONT></P>

			<P align=justify><FONT face=Verdana size=2>The center diamond\'s weight is .50 carats.&nbsp; Color grade of the of the diamond is&nbsp; E (colorless), E color is colorless.&nbsp; The clarity is graded as SI1 by EGL.&nbsp; SI1 clarity is explained as inclusions are viewable under 10 power magnification.&nbsp;&nbsp; </FONT></P>
			<P align=justify><FONT face=Verdana size=2>The total weight of pink sapphires are .40 carats.&nbsp; They are round shape and are set in a prong setting.&nbsp;&nbsp;</FONT></P>
			
			<P align=justify><FONT face=Verdana size=2>The diamond and pink sapphires have excellent cut, polish and symmetry and is simply incredible.&nbsp; They are clear and bright with exceptional brilliance and fire.&nbsp; The clarity is truly amazing, and this diamonds sparkle immensely the shape and cut are perfect.&nbsp; They have been selected, gauged and measured for the best fit in this ring.&nbsp; &nbsp; </FONT></P>
			<P align=justify><FONT face=Verdana size=2>Please email me with your questions or comments.&nbsp; You may visit my store to find more selection of certified <SPAN style="BACKGROUND-COLOR: #ff00ff">GIA &amp; EGL diamonds</SPAN>. A certificate appraisal will accompany this diamond BAND.&nbsp; The estimated retail value of this BAND&nbsp; is $11,100.00 The highest bidder will win this beauty.&nbsp; Bid with full confidence. </FONT></P>

			<P align=justify><FONT color=#ff0000>These diamonds are all guaranteed to be 100% natural, with no enhancements or treatments.&nbsp; All items have been examined by a GIA gemologist.</FONT></P>
			<P align=justify><FONT face=Arial color=black size=1>Descriptions of quality are inherently subjective. The quality descriptions we provide, are to the best of&nbsp;our certified gemologists&nbsp;ability, and are&nbsp;her honest opinion. Disagreements with quality descriptions may occur.&nbsp; &nbsp;</FONT><FONT face=Arial size=1>Appraisal value is given at high retail value for insurance purposes only.&nbsp; Appraisal value is subjective and may vary from one gemologist to another.&nbsp; </FONT><FONT face=Arial color=black size=1>Opinions of appraisers may vary up to 25%. Diamond grading is subjective and may vary greatly. If the lowest color or clarity grades we specify are determined to be more than one grade lower than indicated. you may return the item for a full refund less shipping and insurance.&nbsp; We are not responsible for lost diamonds or gems.</FONT></P></TD></TR></TBODY></TABLE></DIV>*/
			/*$watchDetail .='</TD>
			<TD width=365 height=853>
			<TABLE height=670 cellSpacing=1 cellPadding=1 width=389 align=center border=0>
			<TBODY>
			<TR bgColor=#000000>
			<TD width=414 height=212><IMG height=262 src="'.$ringImage.'" width=535 border=0></TD></TR>
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

	$watchDetail = '<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>New Page 2</title>
</head>

<body onload="init();ebHelpContextualRebrand(\'buy\');">
<table style="border-spacing: 0px; width: 100%;" align="center">
<tbody>
<tr>
<td>
<div id="EBdescription">
<!-- Begin Description -->
<!-- Begin Description -->
<table cellpadding="0" cellspacing="28" width="100%">
<tbody>
<tr>
<td valign="top"><!-- Begin Description -->
<table align="center" border="0" width="598">
<tbody>
<tr>
<td align="middle" width="626">
<marquee><font face="Verdana" size="5"><b>WELCOME TO
ALAN G, YOUR SOURCE FOR CERTIFIED GIA & EGL
DIAMONDS</b></font></marquee>
<p>
<marquee><font face="Verdana" size="3"><b>/
(213)623-1456</b></font></marquee>
<marquee></marquee>
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
<td bgcolor="silver" width="360" height="1"><strong>Center
stone of your choice priced </strong><strong>separately</strong><strong>,
please visit our Loose
Diamond Selection in our
eBay Store</strong></td>
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
OF DIAMOND: '.$total_carats.' CARATS</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">CLARITY:
VS2-SI1 (NATURAL CLARITY)</td>
</tr>
<tr>
<td width="360" height="21">COLOR:
F-G (NATURAL COLOR)</td>
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
<td bgcolor="silver" width="360" height="18">NUMBER
OF DIAMONDS: '.$detail['diamond_count'].' INDIVIDUAL</td>
</tr>
<tr>
<td width="360" height="19">PLATINUM
PRICE: $'.$platinum_price.'</td>
</tr>
<tr>
<td bgcolor="silver" width="360" height="18">YELLOW
GOLD PRICE: $'.$yellow_gold_price.'</td>
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
<div style="width: 365; height: 1009" align="center">
<table border="0" width="365" height="1">
<tbody>
<tr>
<td style="font-size: 11px; font-family: 
Verdana,Arial,Helvetica,sans-serif;" valign="top" width="359" height="18"><font color="black">*************************************************</font></td>
</tr>
<tr>
<td valign="top" width="359" height="778">*<font color="#0000ff">*Diamond
Engagement Ring**</font>
<p> </p>
<p align="center"><strong><font size="4" color="#FF0000"><i>This
ring is offered with out a
center stone.  Center
stone of your choice will
be priced separately. 
Please visit our Loose
Diamond Selection in our
eBay Store for your
diamond selection.</i></font></strong></p>
<p align="justify"><font face="Verdana" size="2">This
auction is for a <font color="#008080"><b><i>BRAND
NEW </i></b></font>DIAMOND
ENGAGEMENT RING.</font></p>
<p align="justify"><font face="Verdana" size="2">We
use <b><u><font color="#008080">14kt
white gold</font></u></b>
for all our diamond
rings.  All our
jewelry selections are
available in <b><u><font color="#008080">14kt
yellow gold</font></u></b>
and <font color="#008080"><u><b>PLATINUM</b></u></font>. 
This auction price is for
white gold, other metal
prices are indicated in
the auction details above
for each metal.</font></p>
<p align="justify"><font face="Verdana" size="2">The
diamonds we use have an excellent
polish and symmetry and
are simply incredible.
They are clear and bright
with exceptional
brilliance and fire. The
clarity is truly amazing,
and this diamond sparkles
immensely, the shape and
cut are perfect. They are
gauged and measured for
the best fit.</font></p>
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
are set perfectly. We
recommend that our clients
obtain insurance for their
jewelry items, buyer
is responsible for lost
diamonds or gems.</font></font></p>
</td>
</tr>
<tr>
<td valign="top" width="359" height="1"><center>
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
<td width="414" height="212"><img src="'.$ringImage.'" width="400" height="325"></td>
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
purchase price. </font></p>
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
laboratories, you may visit EGL at <a href="http://vi.ebaydesc.com/ws/_http://www.eglusa.com/customerlogin.html" >www.eglusa.com</a></font></p></a></font></p>
<u><font face="Verdana" color="#000000" size="4">Satisfied
Client</font></u></a></p>
<font color="#000000">We
aim for the highest customer
satisfaction in our industry. Our
feedback speaks for itself. If you
have any questions, please ask
before you bid.</font></a></font></p>
<u><b><font face="Verdana" size="3" color="#000000">Clarity</font></b></u></a></font></p>
<font color="#000000">The
following table explains the diamond
clarity (inside the diamond):</font><br>
</a></font></p>
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
</td>
</tr>
<tr>
<td class="basic10" colspan="2" height="394"><u><b><font face="Verdana" size="3" color="#000000">Color</font></b></u></a></font>
<p>While
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
</p>
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
</div>
</td>
</tr>
</tbody>
</table>

</body>

</html>';
	//echo $watchDetail;
	//die('ppt');
		if(get_magic_quotes_gpc()) {
            // print "stripslashes!!! <br>\n";
            $requestArray['itemDescription'] = stripslashes($watchDetail);
        } else {
            $requestArray['itemDescription'] = $watchDetail;
        }
		
		$requestArray['ItemSpecification'] = $this->getItemRingSpecification($detail);
		$requestArray['listingDuration'] = 'Days_10';
		$requestArray['dispatchTime'] = '10';
        $requestArray['startPrice']      = $price;//$detail['price'];
        $requestArray['buyItNowPrice']   = '0.0';
        //$requestArray['quantity']        = $detail['quantity'];
		$requestArray['quantity']        = '1';
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
		$requestArray['image'] = $ringImage; //config_item('base_url').'images/rings/'.$detail['small_image'];

		$requestArray['storeCategoryID'] = 3;
		//print_R($requestArray);
		$requestArray['replace_gurantee'] = 'Days_30';

		if($detail['ebayid']=='' || $detail['ebayid']==0) {	
			$itemID = $this->sendRequestEbay($requestArray,'rings');
		} else {
			$itemID = $this->updateRequestEbay($requestArray, 'rings');
			//$itemID = $this->updateEbayItem($requestArray);
		}

		return $itemID;
	}
	
	function getItemRingSpecification($detail){
		
		$remArr = array(" ", "ct.", "ct tw");
		$total_carats = str_replace($remArr, "", $detail['total_carats']);

		$specification = '<ItemSpecifics> 
					  <NameValueList> 
						<Name>Style</Name> 
						<Value>Engagement</Value>
					  </NameValueList> 
					  <NameValueList> 
						<Name>Condition</Name> 
						<Value>New</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Metal</Name> 
						<Value>White Gold</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Ring Size</Name> 
						<Value>Size Selectable</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Exact Carat Total Weight</Name> 
						<Value>'.$total_carats.'</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Main Stone Treatment</Name> 
						<Value>Not Enhanced</Value> 
					  </NameValueList> 
					 <NameValueList> 
						<Name>Metal Purity</Name> 
						<Value>14k</Value> 
					  </NameValueList> 
				</ItemSpecifics>';
					  
		return $specification;

	}

	function getItemWatchSpecification($detail){
		
		$remArr = array(" ", "ct.", "ct tw");
		$total_carats = str_replace($remArr, "", $detail['total_carats']);
		$gender = ($detail['gender'] == 'men') ? "Men's" : "Women's";
		$metalarray =  array('gold_ss'=>'Stainless Steel &amp; Gold', 'ss'=>'Stainless Steel', 'gold'=>'Gold'); 
		$style  = ($detail['style'] == 'preowned') ? "Used" : "New";
		$specification = '<ItemSpecifics> 
					  <NameValueList> 
						<Name>Type</Name> 
						<Value>Na</Value>
					  </NameValueList> 
					  <NameValueList> 
						<Name>Brand</Name> 
						<Value>'.$detail['brand'].'</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Gender</Name> 
						<Value>'.$gender.'</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Age</Name> 
						<Value>Na</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Movement</Name> 
						<Value>Na</Value> 
					  </NameValueList> 
					  <NameValueList> 
						<Name>Condition</Name> 
						<Value>'.$style.'</Value> 
					  </NameValueList> 
					 <NameValueList> 
						<Name>Band Material</Name> 
						<Value>'.$metalarray[$detail['metal']].'</Value> 
					  </NameValueList> 
				</ItemSpecifics>';
					  
		return $specification;

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
								<StoreCategoryID>'.$requestArray['storeCategoryID'].'</StoreCategoryID>  
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
		//echo $requestXmlBody;
		//die('tt');
        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		//echo '<pre>';
		//print_r($responseXml);
		//die('tt');
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DomDocument();
		$responseDoc->loadXML($responseXml);
		
		//	print_r($responseXml);
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
              
              $linkBase = "http://cgi.sandbox.ebay.com/ws/eBayISAPI.dll?ViewItem&item=";
              $status = "<a href=$linkBase" . $itemID . ">".$requestArray['itemTitle']."</a> <BR />";
			  
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
 }
 ?>