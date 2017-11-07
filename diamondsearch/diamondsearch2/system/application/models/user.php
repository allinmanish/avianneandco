<?php  
   class User extends Model {
   	function User()
   {
   	parent::Model();
   }
   
    function loginhtml($page = ''){
	$loginlink  = '';
	if($this->session->isLoggedin()) {
			 $user = $this->session->userdata('user');
			 if($page =='admin')$loginlink = 'Welcome, <a href="' .config_item('base_url') . 'account/myaccount">'. ucfirst(substr($user->fname , 0, 30)) .'</a> [<a href="' .config_item('base_url') . 'account/signout"><strong> logout </strong></a>] | <a href="'.config_item('base_url').'">View Site</a>';
			 else {
			 	$loginlink = 'Welcome, <a href="' .config_item('base_url') . 'account/myaccount">'. ucfirst(substr($user->fname , 0, 30)) .'</a> [<a href="' .config_item('base_url') . 'account/signout"><strong> logout </strong></a>] | <a href="'.config_item('base_url').'shoppingbasket/mybasket">Shopping Basket</a>';
			 	if($this->session->userdata('usertype') == 'admin')$loginlink .= ' | <a href="' . config_item('base_url') . 'admin">Site Admin</a>'; 
			 }
			 
		}else {
			 $loginlink = '<a href="' .config_item('base_url') . 'account/signin">Sign In</a> | <a href="'.config_item('base_url').'shoppingbasket/mybasket">Shopping Basket</a>';
			 
		}
		return $loginlink;
		
		 
   }     
   
   	function userExist($userid = '')
	{
		    $sql = "SELECT * FROM ".$this->config->item('table_perfix')."user WHERE userid= '$userid'";
            $query = $this->db->query($sql);
	        
            if($query->num_rows()){
				return true;
			}else {
				return false;
			}
	}
	
	function giveuserid($userid){
		    $sql = "SELECT userid FROM ".$this->config->item('table_perfix')."user WHERE id= '$userid'";
            $query = $this->db->query($sql);
	        $user = $query->result();
			
	        return  ($query->num_rows()) ? $user[0]->userid : '';
	        
	}
	
	function adduser($post , $regtype ='')	{
    	$regretuen  = array();
    	 
			    	$regretuen['error'] = '';
			    	$userid 	= $this->input->post('email');
					$name 		= $this->input->post('name');
					$password 	= md5($this->input->post('password'));
					
					$now = date('Y-m-d H:s:i');
				    $ip =  $this->input->ip_address();
					
				    if($regretuen['error'] == '')	{
					$isinsert = $this->db->insert($this->config->item('table_perfix').'user',
					array('userid' 		=>$userid,
						  'usertype'	=> 'user',
						  'fname'		=> $name,
						  'lname'		=> '',
						  'password' 	=>$password,
						  'lastlogintime'	=>$now,
						  'lastloginip' =>$ip,
						  'status'		=> 'activationwaiting'
						));
						if(!$isinsert)  $regretuen['error'] .= '<br>User Information not saved.';
						$isinsert = $this->db->insert($this->config->item('table_perfix').'profile',
						array('userid' 		=>$userid,
						      'country'     => $post['country']
							));
						$regretuen['error'] .= $this->sendactivationkey($userid,$name);  
						  
				    }
    	 
			
		return $regretuen;
    }
    
    function sendactivationkey($userid ='shahinbdboy@yahoo.com' ,$fname = 'shahin'){
       	    $regretuen  = '';
    	    $activationkey 		  = md5(microtime());
			$isinsert 			  = $this->db->insert($this->config->item('table_perfix').'activation',
			array('email' 		  =>$userid,
				  'activationkey' =>$activationkey 
				   ));
			 if(!$isinsert) {
			 	$regretuen  .= '<br>Activation key not stored';  
			 }else{
					  
				    $this->load->library('email');
					$this->email->set_mailtype('html');
					
		
		            $this->email->from(config_item('site_email'), config_item('site_name'));
					$this->email->to($userid,$fname);
					
					$this->email->subject(config_item('site_name').'  - User account Activation ');
					$this->email->message('Thank you for registration with '.config_item('site_name').'. Click on the following link for activating your user account. <br><br><br><br> <a href="' . config_item('base_url') . 'useraccount/activate/' . str_ireplace('.' , '_omasters_newuser_',str_ireplace('@' , '_at_the_rate_omasters_',$userid)) . '/' .$activationkey . '" target="_blank">Activate Your Account </a><br><br><br><br><br><br>Your User ID: ' . $userid . ' <br><br>Regards<br>oMasters Team');
					
					$this->email->send();

			 }
			return $regretuen; 
			 
    }
    
    function resendactivationkey($id){
    	    $regretuen  = '';
        	$activationkey 		  = md5(microtime());
        	$userid = $this->giveuserid($id);
        		
        	if($userid != ''){
        		  $sql = "SELECT * FROM ".$this->config->item('table_perfix')."activation  WHERE   email= ?";
		            $binds = array($userid);
		            $query = $this->db->query($sql, $binds);
		            
		           if($query->num_rows()){
				           	$this->db->where('email', $userid);
							$this->db->update($this->config->item('table_perfix').'activation',
							array('activationkey' =>$activationkey ));
		           }
		           else {
		           	
						           $isinsert 			  = $this->db->insert($this->config->item('table_perfix').'activation',
									array('email' 		  =>$userid, 'activationkey' =>$activationkey  ));

									if(!$isinsert) {
									 	$regretuen  .= 'Activation key not stored';  
								
									 }
		           }
				        $this->load->library('email');
					    $this->email->set_mailtype('html');
					
			            $this->email->from(config_item('site_email'), config_item('site_name'));
						$this->email->to($userid,'');
						$this->email->subject(config_item('site_name').'  - User account Activation ');
					$this->email->message('Thank you for registration with '.config_item('site_name').'. Click on the following link for activating your user account. <br><br><br><br> <a href="' . config_item('base_url') . 'useraccount/activate/' . str_ireplace('.' , '_omasters_newuser_',str_ireplace('@' , '_at_the_rate_omasters_',$userid)) . '/' .$activationkey . '" target="_blank">Activate Your Account </a><br><br><br><br><br><br>Your User ID: ' . $userid . ' <br><br>Regards<br>oMasters Team');
					
						$this->email->send();
						 
						 
        	} else $regretuen  .= 'No user found';  
       	    
    	    
			return $regretuen; 
    }
    
    function login($username ='' , $userpass = ''){
		    $loginreturn = array();
		    $loginreturn['error'] = '';
		    $sql = "SELECT * FROM ".$this->config->item('table_perfix')."user WHERE  userid= ? AND password  = ? ";
            $binds = array($username, (md5($userpass)));
            $query = $this->db->query($sql, $binds);
            
		if($query->num_rows()){
			$user = $query->result();
			switch ($user[0]->status)
			{
				case 'active':
					$now = date('Y-m-d H:s:i');
					$ip =  $this->input->ip_address();
					
					//var_dump($now);
					$this->db->where('userid',$user[0]->userid);
					$t = $this->db->update($this->config->item('table_perfix').'user',array('lastlogintime'=>$now,'lastloginip'=>$ip));
					
					$this->session->set_userdata('user',$user[0]);
					$this->session->set_userdata('loggedin','1');
					$this->session->set_userdata('usertype',$user[0]->usertype);
					break;
				case 'block':
					$user = array();
					$this->session->set_userdata('user',$user);
					$this->session->set_userdata('loggedin','0');
					$this->session->set_userdata('usertype','guest');
					$loginreturn['error'] .= '<b>Your account is blocked.</b> ';
					break;
				case 'suspended':
					$user = array();
					$this->session->set_userdata('user',$user);
					$this->session->set_userdata('loggedin','0');
					$this->session->set_userdata('usertype','guest');
					$loginreturn['error'] .= '<b>Your Account is suspended .<br /> Contact System administration for more details.</b> ';
					break;
				case 'activationwaiting':
					$id = $user[0]->id;
					$user = array();
					$this->session->set_userdata('user',$user);
					$this->session->set_userdata('loggedin','0');
					$this->session->set_userdata('usertype','guest');
					$loginreturn['error'] .= '<b>Account need to activate <br />Please check your email for activation link.</b><br> <a href="'.config_item('base_url').'useraccount/resendactivationkey/'.$id.'">Resend Activation Key</a> ';
					break;
				default:
					$user = array();
					$this->session->set_userdata('user',$user);
					$this->session->set_userdata('loggedin','0');
					$this->session->set_userdata('usertype','guest');
					$loginreturn['error'] .= '<b>User ID</b> / <b>Password</b> was incorrect<br>Unable to login';
					break;
				
			}
			 
		}else {
			$user = array();
			//var_dump($this->errors);
	        $this->session->set_userdata('user',$user);
			$this->session->set_userdata('loggedin','0');
			$this->session->set_userdata('usertype','guest');
			$loginreturn['error'] .= '<b>User ID</b> / <b>Password</b> was incorrect<br>Unable to login';
			
		}
		
		return $loginreturn;
	}

	function logout(){
		$user = array();
		//var_dump($this->errors);
        $this->session->set_userdata('user',$user);
		$this->session->set_userdata('loggedin','0');
		$this->session->set_userdata('usertype','guest');
		$this->session->destroy();
	}
	
	function isactivate($userid = '' , $activationkey = '')
    {
            
    	 
    	    $sql = "SELECT * FROM ".$this->config->item('table_perfix')."activation  WHERE   email= ? AND activationkey  = ? ";
            $binds = array($userid, $activationkey);
            $query = $this->db->query($sql, $binds);
           // $row = $query->row();
           
            
		if($query->num_rows()){
			$user = $query->result();
			$now = date('Y-m-d H:s:i');
			$this->db->where('userid',$user[0]->email);
			$updated = $this->db->update($this->config->item('table_perfix').'user',array('activation_date'=>$now,'status'=>'active'));
			
						 
			if($updated)$this->db->delete($this->config->item('table_perfix').'activation', array('email' => $user[0]->email)); 
			return true;
		}
		else {
			return  false;
		}
    }
   
	function forgotpassword($userid)
	{
		$ret     = array('error' => '' , 'success' => '');
		$activationkey 		  = md5(microtime());
		$msg = 'This is an email to reset your password. To reset
                 your password click on the following link.  <br><br><br><br> 
				 <a href="' . config_item('base_url') . 'masters/resetpassword/' . str_ireplace('.' , '_omasters_forgotpass_',str_ireplace('@' , '_at_the_rate_omasters_',$userid)) . '/' .$activationkey . '" target="_blank"> Reset your password </a>
                  <br><br>If you are not requesting then plz let us contact about account security or simply do nothing, this link will be invalid after 24hours';
																			
		$now = date('Y-m-d');
		$ip =  $this->input->ip_address();
		                                      $this->db->query("DELETE FROM ".$this->config->item('table_perfix')."forgotpasswordkey WHERE reqdate < ".$now);
											  $sql = "SELECT * FROM ".$this->config->item('table_perfix')."user WHERE userid= '$userid'";
									          $query = $this->db->query($sql);
										        
									            if($query->num_rows()){
									            	             
																 
										            	         $sql1 = "SELECT * FROM ".$this->config->item('table_perfix')."forgotpasswordkey WHERE userid= '$userid'";
													             $query1 = $this->db->query($sql1);
														         
													             if($query1->num_rows()){
													                            $this->db->where('userid',$userid);
																				$t = $this->db->update($this->config->item('table_perfix').'forgotpasswordkey',array('reqdate'=>$now,'reqip'=>$ip ,'reqkey' => $activationkey));
																				if($t){
																					        $this->send_email($userid , 'Forgot Password' , $msg);
																							$ret['success'] = 'An Email has been sent. Please check your email inbox / bulk';
																							}else{
																								$ret['error'] .= '<br>Error ! User id / email is invalid.';	   
																							}
																				
													             }else{
													             	    
													             		$isinsert 			  = $this->db->insert($this->config->item('table_perfix').'forgotpasswordkey',
																		array('userid' 		  => $userid , 'reqdate'=>$now, 'reqip'=>$ip ,'reqkey' => $activationkey));
																		
																		if($isinsert){
																			
																			
																			$this->send_email($userid , 'Forgot Password' , $msg);
																			$ret['success'] = 'An Email has been sent to user.Please check your email inbox / bulk';
																		}else{
																			$ret['error'] .= '<br>Error Sending forgot password email.';	   
																		}
													             }
										            	     	 
														 
												}else {
									                   $ret['error'] = 'Invalid User. Please  <a href="'.config_item('base_url').'masters/register">Register</a>';
												}
		 
			
	 return $ret;	
	}
	
    function varifyforgotpass($userid ='' , $key = ''){
    	 $now = date('Y-m-d');
		 $ip =  $this->input->ip_address();
    	 $sql = "SELECT * FROM ".$this->config->item('table_perfix')."forgotpasswordkey WHERE userid= '$userid' AND reqdate ='$now' AND reqip='$ip' AND reqkey='$key'";
	     $query = $this->db->query($sql);
	     if($query->num_rows())
	     {
	     	
	     	$newpassword = substr(md5(microtime()),0,8);
	     	$password    = md5($newpassword);
	     	$this->db->where('userid',$userid);
			$t = $this->db->update($this->config->item('table_perfix').'user',array('password'=>$password));
			if($t){
					$msg = 'Your Password has been succefully reset. Your new reset password is <br><br><br>User ID :'.$userid . '<br>Password: ' . $newpassword .' <br><br> Thanks <br>'. config_item('site_name') .' Team';
							
					$this->send_email($userid , 'Your New Password' , $msg);
					$this->db->query("DELETE FROM ".$this->config->item('table_perfix')."forgotpasswordkey WHERE userid = '$userid' ");
			      	return true;
			 }else{return false;}
	     	
	     }else {
	      	return false;
	      }
									            
									            
    }
  
	function getprofile($id){
		$profile = array();
      
		$sql = 'SELECT u.id,
					   u.fname ,
					   u.lname,
					   u.lastlogintime ,
					   u.usertype,
					   u.status ,
					   u.activation_date ,
					   u.rating ,
					   p.sex,
					   p.url,
					   p.imageurl,
					   p.businessname ,
					   p.personaladdress ,
					   p.f_paddress,
					   p.businessaddress ,
					   p.f_baddress,
					   p.country ,
					   p.state ,
					   p.city ,
					   p.street ,
					   p.f_location,
					   p.phoneno ,
					   p.f_phoneno,
					   p.pphone,
					   p.f_pphone,
					   p.busnesshour ,
					   p.description,
					   p.f_getemailfromuser,
					   p.fax,
					   p.dateofbirth,
					   p.f_dateofbirth,
					   p.latitude,
					   p.longitude,
					   p.gmapzoom FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'profile as p on u.userid = p.userid 
					   where u.id=\''. $id .'\' and u.status =\'active\' and  u.userid != \'admin@omasters.com\'';
		 
		$result = $this->db->query($sql);
		$profile  = $result->result_array();	
    	 
    	return $profile[0];
	}
	
	function send_email($to = 'shahinbdboy@gmail.com' , $subject = 'oMasters Email Fails' , $msg = ''){
		 			$this->load->library('email');
					$this->email->set_mailtype('html');
					$this->email->from(config_item('site_email'), config_item('site_name'));
					$this->email->to($to,'');
					$this->email->subject(config_item('site_name').$subject);
					$this->email->message($msg);
					$this->email->send();
	}
	 
	function uploadphoto($post){
		$ret     = array('error' => '' , 'success' => '');
		if($this->session->isLoggedin()){
		$user     = $this->session->userdata('user');
																							
														$extsupport = 'jpeg,gif,jpg,bmp,png'; 
														$maxuploadSize = 5500000; // max file site in bytes
														$attachExtension = '';
														$max_file = "1148576"; 						// Approx 1MB
														$max_width = "100";							// Max width allowed for the large image
														 
														if($_FILES['mfile']['name'] != ''){
																 
																		$supportExt = explode(',',$extsupport);
																		$fileExt = explode('.',$_FILES['mfile']['name']);
													
													
																		if($_FILES['mfile']['size'] <= $maxuploadSize && $_FILES['mfile']['size'] > 0)
																		{
																			if(in_array(strtolower($fileExt[1]),$supportExt))
																			{
																			 
																			  	$attachFileName = $_FILES['mfile']['tmp_name'];
																				$attachExtension = strtolower($fileExt[1]);
																				
																						$saveTo = config_item('base_path') . 'uploads/userprofile/' .$user->id . '.' . $attachExtension; 
																						$imageurl =  config_item('base_url') . 'uploads/userprofile/' .$user->id . '.' . $attachExtension; 
																						$ismove = move_uploaded_file($attachFileName, $saveTo);
																						chmod($saveTo, 0777);
																						
																						$width = $this->getWidth($saveTo);
																						$height = $this->getHeight($saveTo);
																						//Scale the image if it is greater than the width set above
																						if ($width > $max_width){
																							$scale = $max_width/$width;
																							$uploaded = $this->resizeImage($saveTo,$width,$height,$scale , $attachExtension );
																						}else{
																							$scale = 1;
																							$uploaded = $this->resizeImage($saveTo,$width,$height,$scale , $attachExtension);
																						}
																						 		
																									
																					if($ismove){
																								   $this->db->where('userid',$user->userid);
																									$t = $this->db->update($this->config->item('table_perfix').'profile',array('imageurl' => $imageurl));
													   												if($t){
																										$ret['success'] = $imageurl;
																									}else{
																										  $ret['error'] = 'ERROR ! Image/avatar Not saved';
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
																		else {
																			$ret['error'] = '<br>File size too big (' . $_FILES['mfile']['size'] . ')';
																			
													
																		}
																	}
											 
										            	     	
																
														 
		}	
			
	 return $ret;	
		
	}
	
	function resizeImage($image,$width,$height,$scale,$type) {
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	
	switch ($type){
		case 'gif':
				$source = imagecreatefromgif($image);
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				imagejpeg($newImage,$image,90);//imagegif($newImage,$image,90);
				break;
		case 'png':
				$source = imagecreatefrompng($image);
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				imagejpeg($newImage,$image,90);//imagepng($newImage,$image,90);
				break;

		case 'bmp':
				$source = imagecreatefromwbmp($image);
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				imagejpeg($newImage,$image,90);//imagewbmp($newImage,$image,90);
				break;				
		case 'jpeg':
		default:
				$source = imagecreatefromjpeg($image);
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				imagejpeg($newImage,$image,90);
				 
				break;
	}
	
	chmod($image, 0777);
	return $image;
	}
	
	function getHeight($image) {
		$sizes = getimagesize($image);
		$height = $sizes[1];
		return $height;
	}
	
	function getWidth($image) {
		$sizes = getimagesize($image);
		$width = $sizes[0];
		return $width;
	}
	
	function getprofiles($start = 0 , $limit = 18 , $orderby = 'activation_date'){
		$profile = array();
      
		$sql = 'SELECT u.id,
					   u.fname ,
					   u.lname,
					   u.usertype,
					   u.status ,
					   u.activation_date ,
					   u.rating ,
					   p.sex,
					   p.url,
					   p.imageurl,
					   p.businessname ,
					   p.personaladdress ,
					   p.f_paddress,
					   p.businessaddress ,
					   p.f_baddress,
					   p.country ,
					   p.state ,
					   p.city ,
					   p.street ,
					   p.f_location,
					   p.pphone,
					   p.f_pphone,
					   p.busnesshour ,
					   p.f_getemailfromuser,
					   p.fax,
					   p.dateofbirth,
					   p.f_dateofbirth,
					   p.latitude,
					   p.longitude FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'profile as p on u.userid = p.userid where u.status =\'active\' and u.userid != \'admin@omasters.com\' 
					    order by '. $orderby . ' limit '. $start . ', '. $limit;
		 
		$result = $this->db->query($sql);
		$profile  = $result->result_array();	
    	 
    	return $profile;
	}
	
	function savelocation($post){
		$regretuen  = array();
    	   	$regretuen['error'] = '';
    	   	$regretuen['success'] = '';
    	   	
		if(is_array($post)){
			$lat = isset($post['latbox']) ? $post['latbox'] : '0';
			$lng = isset($post['lngbox']) ? $post['lngbox'] : '0';
			$zoom = isset($post['gmapzoom']) ? $post['gmapzoom'] : '1';
			$user     = $this->session->userdata('user');
						$this->db->where('userid',$user->userid);
						$t = $this->db->update($this->config->item('table_perfix').'profile',array('latitude'  => $lat, 'longitude' => $lng , 'gmapzoom' => $zoom));
						if($t){
							$regretuen['success'] = 'New Location Saved';
						}else{
							  $regretuen['error'] = 'ERROR ! Location Not saved';
						} 
			
		}
		return  $regretuen;
	}
	
	function editprofile($post){
		$retuen  = array();
	   	$retuen['error'] = '';
	   	$retuen['success'] = '';
    	if(is_array($post)){   	
		$fname 				= $post['fname'];
	  	$lname 				= $post['lname'];
	  	$sex 				= $post['sex'];
	  	$businessname 		= $post['businessname'];
	  	$businessaddress 	= $post['businessaddress'];
	  	$f_baddress 		= $post['f_baddress'];
	  	$personaladdress 	= $post['personaladdress'];
	  	$f_paddress			= $post['f_paddress'];
	  	$country 			= $post['country'];
		$city 				= $post['city'];
		$street 			= $post['street'];
		$phone 				= $post['phone'];
		$pphone 			= $post['pphone'];
	    $description    	= $post['description'];
	    $busnesshour    	= $post['busnesshour'];
		$url           		= $post['url'];
		$dateofbirth    	= $post['dateofbirth'] ;
		$f_dateofbirth  	= $post['f_dateofbirth'] ;
		$f_paddress     	= $post['f_paddress'] ;
		$f_baddress     	= $post['f_baddress'] ;
		$f_location     	= $post['f_location'] ;
		$f_phoneno    		= $post['f_phoneno'] ;
		$f_pphone     		= $post['f_pphone'] ;
		$fax 				= $post['fax'];
		$f_getemailfromuser = isset($post['f_getemailfromuser']) ? '1' : '0' ;
		
		
		$user     = $this->session->userdata('user');
						$this->db->where('userid',$user->userid);
						$t = $this->db->update($this->config->item('table_perfix').'user',
						array('fname'  			=> $fname,
							  'lname' 			=> $lname));
						$this->db->where('userid',$user->userid);
						$t = $this->db->update($this->config->item('table_perfix').'profile',
						array('sex' 			=> $sex,
							  'businessname' 	=> $businessname,
							  'businessaddress' => $businessaddress,
							  'f_baddress' 		=> $f_baddress,
							  'personaladdress' => $personaladdress,
							  'f_paddress' 		=> $f_paddress,
							  'country' 		=> $country,
							  'city' 			=> $city,
							  'street' 			=> $street,
							  'phoneno' 			=> $phone,
							  'description' 	=> $description,
							  'busnesshour'  	=> $busnesshour,
							  'url' 			=> $url,
							  'f_dateofbirth' 	=> $f_dateofbirth,
							  'f_paddress' 		=> $f_paddress,
							  'f_baddress' 		=> $f_baddress,
							  'f_location' 		=> $f_location,
							  'f_phoneno' 		=> $f_phoneno,
							  'f_pphone' 		=> $f_pphone,
							  'dateofbirth' 	=> $dateofbirth));
							  
						if($t){
							  $retuen['success'] = 'Your Information has been saved';
						}else{
							  $retuen['error'] = 'ERROR ! Information not saved';
						} 
		
		
    	}
		
    	return $retuen;
		
		
	}
	
	function getEducation($id = '' , $page =1 , $rp = 10 ,$sortname = 'todate' ,$sortorder = 'desc' ,$query= '' , $qtype = 'school' , $eduid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND e.$qtype LIKE '%$query%' ";
			if($eduid != '') $qwhere .= " AND e.id = $eduid";
			 
      
		$sql = 'SELECT e.id,e.fromdate, e.todate, e.school,e.degree, e.areaofstudy,e.result, e.details FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'education as e on u.id = e.userid 
					   where e.userid=\''. $id .'\' and u.status =\'active\' and  u.userid != \'admin@omasters.com\' '. $qwhere . ' ' . $sort . ' '. $limit;
		$result = $this->db->query($sql);
		$results['education']  = $result->result_array();	
		$sql2 = 'SELECT e.id FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'education as e on u.id = e.userid 
					   where e.userid=\''. $id .'\' and u.status =\'active\' and  u.userid != \'admin@omasters.com\' '. $qwhere;
		$result2 = $this->db->query($sql2);
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
	}
	
	function addEditEducation($post , $action = '' , $id = ''){
		$retuen  = array();
	   	$retuen['error'] = '';
	   	$retuen['success'] = '';
	   	if($action == 'delete'){
	   		$items = rtrim($_POST['items'],",");
			$sql = "DELETE FROM ".$this->config->item('table_perfix')."education WHERE  id IN ($items) and userid = ".$this->session->userdata('user')->id ;
			$total = count(explode(",",$items)); 
			$result = $this->db->query($sql);
			$retuen['total'] = $total;
			
	   		
	   	}else{
			   	if(is_array($post)){
			   		 $user 			= $this->session->userdata('user');
			   		 $fromdate		= isset($post['fromdate']) 	? $post['fromdate'] : '';
				  	 $todate 		= isset($post['todate']) 	? $post['todate'] : '';
				  	 $school 		= isset($post['school'])  	? $post['school'] : '';
					 $areaofstudy 	= isset($post['areaofstudy']) ? $post['areaofstudy'] : '';
					 $result 		= isset($post['result']) 	? $post['result'] : '';
					 $degree 		= isset($post['degree']) 	? $post['degree'] : '';
					 $details 		= isset($post['details']) 	? $post['details'] : '';
				 	 			
					 			if($action == 'edit'){
					 								    $this->db->where('userid',$user->id);
					 								    $this->db->where('id',$id);
					 								    $t = $this->db->update($this->config->item('table_perfix').'education',
														array('fromdate' 		=> $fromdate,
															  'todate' 			=> $todate,
															  'school' 			=> $school,
															  'result'			=> $result,
															  'degree' 			=> $degree,
															  'areaofstudy' 	=> $areaofstudy,
															  'details' 		=> $details
															  ));
														
					 			}
					 			if($action == 'add'){
														$t = $this->db->insert($this->config->item('table_perfix').'education',
														array('userid' 			=> $user->id,
															  'fromdate' 		=> $fromdate,
															  'todate' 			=> $todate,
															  'school' 			=> $school,
															  'result'			=> $result,
															  'degree' 			=> $degree,
															  'areaofstudy' 	=> $areaofstudy,
															  'details' 		=> $details
															  ));
					 			}
					 			
					 			
															  
								if($t){
									  $retuen['success'] .= 'Your Information has been saved';
								}else{
									  $retuen['error'] .= 'ERROR ! Information not saved';
								} 
								
			   	}
	   	}
	   	
	   	
	   	return $retuen;
	}
	
	function getExperiences($id = '' , $page =1 , $rp = 10 ,$sortname = 'todate' ,$sortorder = 'desc' ,$query= '' , $qtype = 'school' , $eduid = ''){
		$results = array();
		 
			$sort = "ORDER BY $sortname $sortorder";
			
			$start = (($page-1) * $rp);
			
			$limit = "LIMIT $start, $rp";
			 
			$qwhere = "";
			if ($query) $qwhere .= " AND e.$qtype LIKE '%$query%' ";
			if($eduid != '') $qwhere .= " AND e.id = $eduid";
			 
      
		$sql = 'SELECT e.id,e.fromdate, e.todate, e.companyname,e.companyurl, e.position,e.industry, e.details,e.iscurrent,e.experiencetype FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'experiences as e on u.id = e.userid 
					   where e.userid=\''. $id .'\' and u.status =\'active\' and  u.userid != \'admin@omasters.com\' '. $qwhere . ' ' . $sort . ' '. $limit;
		$result = $this->db->query($sql);
		$results['experience']  = $result->result_array();	
		$sql2 = 'SELECT e.id FROM '
						. $this->config->item('table_perfix').'user as u join '. $this->config->item('table_perfix').'experiences as e on u.id = e.userid 
					   where e.userid=\''. $id .'\' and u.status =\'active\' and  u.userid != \'admin@omasters.com\' '. $qwhere ;
		$result2 = $this->db->query($sql2);
		
    	$results['count']  = $result2->num_rows();
 		
    	return $results;
		
	}
	
	function addEditExperiences($post , $action = '' , $id = ''){
		$retuen  = array();
	   	$retuen['error'] = '';
	   	$retuen['success'] = '';
	   	if($action == 'delete'){
	   		$items = rtrim($_POST['items'],",");
			$sql = "DELETE FROM ".$this->config->item('table_perfix')."experiences WHERE  id IN ($items) and userid = ".$this->session->userdata('user')->id;
			$total = count(explode(",",$items)); 
			$result = $this->db->query($sql);
			$retuen['total'] = $total;
			
	   		
	   	}else{
			   	if(is_array($post)){
			   		 $user 			= $this->session->userdata('user');
			   		 $fromdate		= isset($post['fromdate']) 	? $post['fromdate'] : '';
				  	 $todate 		= isset($post['todate']) 	? $post['todate'] : '';
				  	 $companyname 	= isset($post['companyname'])  	? $post['companyname'] : '';
					 $companyurl 	= isset($post['companyurl']) ? $post['companyurl'] : '';
					 $position 		= isset($post['position']) 	? $post['position'] : '';
					 $industry 		= isset($post['industry']) 	? $post['industry'] : '';
					 $details 		= isset($post['details']) 	? $post['details'] : '';
					 $iscurrent     = isset($post['iscurrent']) ? true : false;
					 $experiencetype= isset($post['experiencetype']) ? $post['experiencetype'] : '';
				 	 			
					 			if($action == 'edit'){
					 								    $this->db->where('userid',$user->id);
					 								    $this->db->where('id',$id);
					 								    $t = $this->db->update($this->config->item('table_perfix').'experiences',
														array('fromdate' 		=> $fromdate,
															  'todate' 			=> $todate,
															  'companyname' 	=> $companyname,
															  'companyurl'		=> $companyurl,
															  'position' 		=> $position,
															  'industry' 		=> $industry,
															  'details' 		=> $details,
															  'iscurrent'		=> $iscurrent,
															  'experiencetype'  => $experiencetype
															  ));
														
					 			}
					 			if($action == 'add'){
														$t = $this->db->insert($this->config->item('table_perfix').'experiences',
														array('userid' 			=> $user->id,
															  'fromdate' 		=> $fromdate,
															  'todate' 			=> $todate,
															  'companyname' 	=> $companyname,
															  'companyurl'		=> $companyurl,
															  'position' 		=> $position,
															  'industry' 		=> $industry,
															  'details' 		=> $details,
															  'iscurrent'		=> $iscurrent,
															  'experiencetype'  => $experiencetype
															  ));
					 			}
					 			
					 			
															  
								if($t){
									  $retuen['success'] .= 'Your Information has been saved';
								}else{
									  $retuen['error'] .= 'ERROR ! Information not saved';
								} 
								
			   	}
	   	}
	   	
	   	
	   	return $retuen;
	}
		
	 
	 
}
?>