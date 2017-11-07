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
				   		$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."jewelries WHERE stock_number IN ($items)";
						$result = $this->db->query($sql);
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
				 																																	
				 		
				 				  
				 if($isinsert) $retuen['success'] .= '<h1 class="success">Ring info Successfully '.ucfirst($action).'ed .</h1><br /><br /><small> <a href="'.config_item('base_url').'admin/jewelries/edit/'.$rid.'">Click Here </a> To View/Edit</small>';
         }			
		 
  	}
  	
    return $retuen;
  
    
 }
  
    function uploadfile($_FILES, $formfilenvar = 'myfile', $imageurlbase = '' , $extsupport = 'jpeg,gif,jpg,bmp,png,swf' ,  $base_savepath = 'uploads/', $filename = 'error.error', $dbtable = 'error', $idfield = 'id' , $idvalue = '1', $tablefield ='msg'){
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
				   		$items = rtrim($_POST['items'],",");
						$sql = "DELETE FROM ".$this->config->item('table_perfix')."watches WHERE watches IN ($items)";
						$result = $this->db->query($sql);
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
				
	            
				if($isinsert) $retuen['success'] .= '<h1 class="success">Watch info Successfully '.ucfirst($action).'ed .</h1><br /><br /><small> <a href="'.config_item('base_url').'admin/rolex/edit/'.$rid.'">Click Here </a> To View/Edit</small>';
         }			
		 
  	}
  	
    return $retuen;
  
    
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
 }
 ?>