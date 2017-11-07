<div>
		<?php if($action == 'add' || $action == 'edit'){
	       	$this->load->helper('custom','form');

			$genderoptions = '<option value=""> Select Gender </option>
			                 <option value="men">Male</option>
			                 <option value="ladies">Ladies </option>
			                 ';
			
			$brandoptions = '<option value=""> Select Brand </option>'.$brandoptions.'<option value="-1"> Other </option>';			

			$metaloptions = '<option value=""> Select Metal </option>
				                 <option value="ss">Stainless Steel </option>
				                 <option value="gold_ss">Stainless Steel and Gold</option>
				                 <option value="gold">Gold</option>
				                 '; 
			$styleoptions      = '<option value=""> Select Style </option>
				                 <option value="new">New</option>
				                 <option value="preowned">Pre Owned</option>
				                 ';
			 
			
			 if(isset($details)){
			                    $name 			= isset($details['productName']) 			? $details['productName'] 		: '';
							 	$price 			= isset($details['price1']) 			? $details['price1'] 		: 0;
							 	$uprice 		= isset($details['price2']) 		? $details['price2'] 		: '';
							 	$sku   			= isset($details['SKU']) 	? $details['SKU'] 	: '';
							 	$gender   		= isset($details['gender']) 			? $details['gender'] 		: '';
								$brand   		= isset($details['brand']) 			? $details['brand'] 		: '';
							 	$metal   		= isset($details['metal']) 			? $details['metal'] 		: '';
								$description 	= isset($details['productDescription']) 	? $details['productDescription'] 	: '';
							    $small_img 		= isset($details['thumb'])   	? $details['thumb'] 	: '';
							    $big_img 		= isset($details['large'])   	? $details['large'] 	: '';
								$small_img2 	= isset($details['image_small2']) ? $details['image_small2'] 	: '';
							    $big_img2 		= isset($details['image_big2'])   	? $details['image_big2'] 	: '';
							    $carat_image	= isset($details['carat_image']) 	? $details['carat_image'] 	: '';
							    $style          = isset($details['style'])			? $details['style'] 		: '';
							   	$model_number          = isset($details['model_number'])			? $details['model_number'] 	: '';

								$warranty          = isset($details['warranty'])			? $details['warranty'] 	: '';
								$papers          = isset($details['papers'])			? $details['papers'] 	: '';
								$box          = isset($details['box'])			? $details['box'] 	: '';
								$lugwidth          = isset($details['lugwidth'])			? $details['lugwidth'] 	: '';
								$thickness          = isset($details['thickness'])			? $details['thickness'] 	: '';
								$height          = isset($details['height'])			? $details['height'] 	: '';
								$width          = isset($details['width'])			? $details['width'] 	: '';
								$calibre          = isset($details['calibre'])			? $details['calibre'] 	: '';
								$movement          = isset($details['movement'])			? $details['movement'] 	: '';
								$crystal          = isset($details['crystal'])			? $details['crystal'] 	: '';
								$features          = isset($details['features'])			? $details['features'] 	: '';
								$bezel          = isset($details['bezel'])			? $details['bezel'] 	: '';
								$markers          = isset($details['markers'])			? $details['markers'] 	: '';
								$hands          = isset($details['hands'])			? $details['hands'] 	: '';
								$dial          = isset($details['dial'])			? $details['dial'] 	: '';
								$band          = isset($details['band'])			? $details['band'] 	: '';
							 	
			                    if(isset($animations)){
			                    	$image45	= isset($animations['image45']) 	? $animations['image45'] : '';
			                    	$image90	= isset($animations['image90']) 	? $animations['image90'] : '';
			                    	$image180	= isset($animations['image180']) 	? $animations['image180'] : '';
			                    	
			                    	$image45_bg	= isset($animations['image45_bg']) 			? $animations['image45_bg'] : '';
			                    	$image90_bg	= isset($animations['image90_bg']) 			? $animations['image90_bg'] : '';
			                    	$image180_bg	= isset($animations['image180_bg']) 	? $animations['image180_bg'] : '';
			                    	
			                    	//$animation1 = isset($animations['flash1']) 		? $animations['flash1'] : '';
			                    	$animation2 = isset($animations['flash2']) 		? $animations['flash2'] : '';
			                    	$animation3 = isset($animations['flash3']) 		? $animations['flash3'] : '';
			                    }else {
			                    	$image45 	=  '';
			                    	$image90 	=  '';
			                    	$image180 	=  '';
			                    	
			                    	$image45_bg 	=  '';
			                    	$image90_bg 	=  '';
			                    	$image180_bg 	=  '';
			                    	
			                    	//$animation1 =  '';
					            	$animation2 =  '';
					            	$animation3 =  '';
			                    }
			                    
			    
			 }else{
			 	 	$name 			= '';
					$price 			= 0;
				 	$section 		= '';
				 	$collection   	= '';
				 	$carat   		= '';
				 	$shape   		= '';
				 	$metal   		= '';
				 //	$finger_size   	= '';
				 	$diamond_count  = '';
				 	$diamond_size   = '';
					$total_carats   = '';
					$pearl_lenght  	= '';
					$pearl_mm   	= '';
					$semi_mounted   = '';
					$side  			= '';
					$description 	= '';
				    $small_img 		= '';
				    $big_img 		= '';
				    $carat_image	= '';
				 	$style			= '';
				 	$ringtype		= '';
					$platinum_price = 0;
					$white_gold_price = 0;
					$yellow_gold_price = 0;
				 	
			 	 	$image45 		=  '';
	            	$image90 		=  '';
	            	$image180 		=  '';
	            	
	            	$image45_bg 	=  '';
                	$image90_bg 	=  '';
                	$image180_bg 	=  '';
				                    	
					//$animation1 	=  '';
	            	$animation2 	=  '';
	            	$animation3 	=  '';
			    
			 }
			  		$id         	= isset($id) ? $id : '';
			
			?>
			<div>
					<h1 class="hbb" align="center">
								<?=ucfirst($action) ?> Watch
					
					</h1>
					
					<br/>
					<div align="center">
					 
						 <form name="" action="<?php echo config_item('base_url');?>admin/rolex/<?php echo $action; echo ($action == 'edit') ? '/' .$id : '';?>" method="post" enctype="multipart/form-data" >
							
						 			<div class="lebelfield floatl">Price:</div>
									<div class="inputfield floatl">
											<input type="text" name="price" value="<?php echo $price;?>" maxlength="15" class="price" /><?php echo form_error('price'); ?> 											
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">User Price:</div>
									<div class="inputfield floatl">
											<input type="text" name="uprice" value="<?php echo $uprice;?>" maxlength="15" class="price" /><?php echo form_error('uprice'); ?> 											
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Watch Name:</div>
									<div class="inputfield floatl">
											<input type="text" name="name" value="<?php echo $name;?>" maxlength="55" /><?php echo form_error('name'); ?> 											
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">BWID:</div>
									<div class="inputfield floatl">
											<input type="text" name="model_number" value="<?php echo $model_number;?>" maxlength="15" /><?php echo form_error('model_number'); ?> 											
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Brand:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="brand" id="brand" onchange="displayother(this.value);">
												
											<?php echo makeOptionSelected($brandoptions , $brand);?>
											</select> <?php echo form_error('brand'); ?> 
											<div id="otherbrand" style='display:none;'>
												<input type="text" name="otherbrandname" id = 'otherbrandname' maxlength="15" />
											</div>
									</div>
									<div class="clear"></div>
									
																		
									
									<div class="lebelfield floatl">Case:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="metal">
											<?php echo makeOptionSelected($metaloptions , $metal);?>
											</select> <?php echo form_error('metal'); ?> 
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Grade:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="style">
											<?php echo makeOptionSelected($styleoptions , $style);?>
											</select> <?php echo form_error('style'); ?> 
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Type:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="gender">
											<?php echo makeOptionSelected($genderoptions , $gender);?>
											</select> <?php echo form_error('gender'); ?> 
									</div>
									<div class="clear"></div>
																		
									<div class="lebelfield floatl">Band:</div>
									<div class="inputfield floatl">
											<input type="text" name="band" value="<?php echo $band;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Dial:</div>
									<div class="inputfield floatl">
											<input type="text" name="dial" value="<?php echo $dial;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Hands:</div>
									<div class="inputfield floatl">
											<input type="text" name="hands" value="<?php echo $hands;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Markers:</div>
									<div class="inputfield floatl">
											<input type="text" name="markers" value="<?php echo $markers;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Bezel:</div>
									<div class="inputfield floatl">
											<input type="text" name="bezel" value="<?php echo $bezel;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Features:</div>
									<div class="inputfield floatl">
											<input type="text" name="features" value="<?php echo $features;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Crystal:</div>
									<div class="inputfield floatl">
											<input type="text" name="crystal" value="<?php echo $crystal;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Movement:</div>
									<div class="inputfield floatl">
											<input type="text" name="movement" value="<?php echo $movement;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Calibre:</div>
									<div class="inputfield floatl">
											<input type="text" name="calibre" value="<?php echo $calibre;?>" maxlength="15" /><?php echo form_error('model_number'); ?> 											
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Width:</div>
									<div class="inputfield floatl">
											<input type="text" name="width" value="<?php echo $width;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Height:</div>
									<div class="inputfield floatl">
											<input type="text" name="height" value="<?php echo $height;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Thickness:</div>
									<div class="inputfield floatl">
											<input type="text" name="thickness" value="<?php echo $thickness;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Lug Width:</div>
									<div class="inputfield floatl">
											<input type="text" name="lugwidth" value="<?php echo $lugwidth;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Box:</div>
									<div class="inputfield floatl">
											<input type="text" name="box" value="<?php echo $box;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Papers:</div>
									<div class="inputfield floatl">
											<input type="text" name="papers" value="<?php echo $papers;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">Warranty:</div>
									<div class="inputfield floatl">
											<input type="text" name="warranty" value="<?php echo $warranty;?>" maxlength="50"  />
									</div>
									<div class="clear"></div>
																	
									<div class="lebelfield floatl">Description:</div>
									<div class="inputfield floatl">
											<textarea name="description" style="width: 400px;height: 60px;"><?php echo $description;?></textarea> 
									</div>
									<div class="clear"></div>
								
											
									
									
						 
						 
						 	
						 
						 	<table 	 border="0" align="left" width="100%"/>
							     
							   
							 
							  
							  
							  <tr>
								<td colspan="2"> 
									<table width="100%"><tr><td valign="top">
												<fieldset style="background: #fff;">
												<legend>Image 1</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').$small_img) && $small_img !='')echo '<img width="120" height="120" src="'.config_item('base_url').$small_img.'" style="width: 80px; height: 80px;"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png" style="width: 80px; height: 80px;"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image_small" id="file1" value=""  /> 
													</center>
												</fieldset>	
											</td>
										<td>
												<fieldset style="background: #fff;">
												<legend>Image 2</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').$big_img) && $big_img !='')echo '<img src="'.config_item('base_url').$big_img.'" style="width: 80px; height: 80px;"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png" style="width: 80px; height: 80px;"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="big_image" id="big_image" value=""  /> 
													</center>
												</fieldset>	
										</td>
									  </tr>
									   <tr>
								<td colspan="2"> 
									<table width="100%"><tr><td valign="top">
												<fieldset style="background: #fff;">
												<legend>Image 3</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').$small_img2) && $small_img2 !='')echo '<img width="120" height="120" src="'.config_item('base_url').$small_img2.'" style="width: 80px; height: 80px;"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png" style="width: 80px; height: 80px;"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image_small2" id="file3" value=""  /> 
													</center>
												</fieldset>	
											</td>
										<td>
												<fieldset style="background: #fff;">
												<legend>Image 4</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').$big_img2) && $big_img2 !='')echo '<img src="'.config_item('base_url').$big_img2.'" style="width: 80px; height: 80px;"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png" style="width: 80px; height: 80px;"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="big_image2" id="file4" value=""  /> 
													</center>
												</fieldset>	
										</td>
									  </tr>
							  		</table>
							  </td></tr>
							  
							  
							   <tr>
								<td colspan="2">
								<!-- <fieldset style="background: #fff;">
									<legend>Rings Animations Icons ( 55 x 55 px)</legend>
								       <table align="center"  width="100%">
								       <tr>
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/45/'.$image45) && $image45 !='')echo '<img width="58" height="58" src="'.config_item('base_url').'images/rings/icons/45/'.$image45.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/45/45degree.jpg"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image45" id="image45" value=""  /> 
													</center>
								              </td>
								              
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/90/'.$image90) && $image90 !='')echo '<img width="58" height="58" src="'.config_item('base_url').'images/rings/icons/90/'.$image90.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/90/90degree.jpg"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image90" id="image90" value=""  /> 
													</center>
								              </td>
								              
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/180/'.$image180) && $image180 !='')echo '<img width="58" height="58" src="'.config_item('base_url').'images/rings/icons/180/'.$image180.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/180/180degree.jpg"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image180" id="image180" value=""  /> 
													</center>
								              </td>
								               
								       </tr>
								       
								       </table>
								     </fieldset>
								     
								     <br>
								     
								     
								     <fieldset style="background: #fff;">
									<legend>Rings Images (Bigger)</legend>
								       <table align="center"  width="100%">
								       <tr> 
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/45/'.$image45_bg) && $image45_bg !='')echo '<img width="158" height="158" src="'.config_item('base_url').'images/rings/icons/45/'.$image45_bg.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/45/45.jpg" width="158" height="158" ><br />';
												    	echo '<small>Upload image of the ring from 45&deg; angle</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image45_bg" id="image45_bg" value=""  /> 
													</center>
								              </td>
								              
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/90/'.$image90_bg) && $image90_bg !='')echo '<img width="158" height="158" src="'.config_item('base_url').'images/rings/icons/90/'.$image90_bg.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/90/90.jpg" width="158" height="158" ><br />';
												    	echo '<small>Upload image of the ring from 90&deg; angle</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image90_bg" id="image90_bg" value=""  /> 
													</center>
								              </td>
								              
								              <td>
								              <center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/icons/180/'.$image180_bg) && $image180_bg !='')echo '<img width="158" height="158" src="'.config_item('base_url').'images/rings/icons/180/'.$image180_bg.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/icons/180/180.jpg" width="158" height="158" ><br />';
												    	echo '<small>Upload image of the ring from 180&deg; angle</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image180_bg" id="image180_bg" value=""  /> 
													</center>
								              </td>
								               
								       </tr>
								       
								       </table>
								     </fieldset>
								     
								     
								     <br>
								     
								     
								       <fieldset style="background: #fff;">
										<legend>Rings Animations</legend>
								       <table align="center"  width="100%">
								       <tr>
											      <td align="center"> 
											      			   <div id="animation2"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation2) && $animation2 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation2."\", \"Animation2\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/90.swf\", \"Animation2\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation2\");</script><br />";
															    	echo '<small>Upload small animated picture of the ring</small><br />'; 
													    	 
													    	 	?>
													    	 	</script>
													        <input type="file" name="animation2" id="animation2" value=""  /> 
											     </td> 
												 
												 
											      <td align="center"> 
											                     <div id="animation3"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation3) && $animation3 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation3."\", \"Animation3\", \"405\", \"290\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/180.swf\", \"Animation3\", \"405\", \"290\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation3\");</script><br />";
															    	echo '<small>Upload Larger animated picture of the ring</small><br />'; 
													    	 
													    	 	?></script>
													        <input type="file" name="animation3" id="animation3" value=""  /> 
											     </td>  
											     <td align="center">
											      		 
											      			   <div id="animation3"></div>
															  
													        <input type="file" name="animation3" id="animation3" value=""  /> 
											     </td>
											      
										</tr>
								       </table>
								       <?php if($action == 'edit'){?>
									   <small><font color="Red">** Upload new image will replace the old Animation</font>  Please refresh your browser (Ctrl+R) / clear your cache to view changes if u show the old image after upload</small><br />
									   <?php }?>
								</fieldset> -->
								
								</td>
							  </tr>
							  <tr>
							  <td></td><td><br>
							   <input type="submit"  name="<?=$action;?>btn" value="<?=ucfirst($action);?>" class="adminbutton"  /> <a href="<?php echo config_item('base_url')?>admin/rolex" class="adminbutton"> Cancel</a>
										 
							  </td>
							  </tr>
							</table>
							 
						</form>
					</div>
			</div>
			<?php }else{?>
		 
			<div>
					<table id="results" style="display:none; "></table>
			</div>
		<?php }?>
</div>
 

 
