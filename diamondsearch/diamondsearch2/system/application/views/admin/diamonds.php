<div>
		<?php if($action == 'add' || $action == 'edit'){
	       /*	$this->load->helper('custom','form');
			$shapeoptions = '<option value=""> Select Shape </option>
			                 <option value="Asscher">Asscher </option>
			                 <option value="Cushion">Cushion </option>
			                 <option value="Emerald">Emerald </option>
			                 <option value="Heart">Heart </option>
			                 <option value="Marquise">Marquise </option>
			                 <option value="Oval">Oval </option>
			                 <option value="Pear">Pear </option>
			                 <option value="Princess">Princess </option>
			                 <option value="Radiant">Radiant </option>
			                 <option value="Round">Round </option>
			                 ';
			
			//$collectionoptions = '<option value=""> Select Collection </option>'.$collectionoptions;			
			$sectionoptions = '<option value=""> Select Section </option>
							   <option value="Earrings">Earrings </option>
							   <option value="EngagementRings">Engagement Rings </option>
							   <option value="Jewelry">Jewelry</option>
							   <option value="Pendants">Pendants</option>
								'; 
			
			$metaloptions = '<option value=""> Select Metal </option>
				                 <option value="18ktWhiteGold">18kt. white gold </option>
				                 <option value="Platinum">Platinum </option>
				                 <option value="WhiteGold">White Gold </option>
				                 <option value="YellowGold">Yellow Gold </option>
				                 '; 
			$styleoptions      = '<option value=""> Select Style </option>
				                 <option value="sidestones">Sidestones </option>
				                 <option value="Pave">Pave </option>
				                 <option value="solitaire">Solitaire </option>
				                 <option value="three stones">Three stones </option>
				                  ';
			 
			
			 if(isset($details)){
							 	$price 			= isset($details['price']) 			? $details['price'] 		: 0;
							 	$section 		= isset($details['section']) 		? $details['section'] 		: '';
							 	$collection   	= isset($details['collection']) 	? $details['collection'] 	: '';
							 	$carat   		= isset($details['carat']) 			? $details['carat'] 		: '';
							 	$shape   		= isset($details['shape']) 			? $details['shape'] 		: '';
							 	$metal   		= isset($details['metal']) 			? $details['metal'] 		: '';
							 	$finger_size   	= isset($details['finger_size']) 	? $details['finger_size'] 	: '';
							 	$diamond_count  = isset($details['diamond_count']) 	? $details['diamond_count'] : '';
							 	$diamond_size   = isset($details['diamond_size']) 	? $details['diamond_size'] 	: '';
								$total_carats   = isset($details['total_carats']) 	? $details['total_carats'] 	: '';
								$pearl_lenght  	= isset($details['pearl_lenght']) 	? $details['pearl_lenght'] 	: '';
								$pearl_mm   	= isset($details['pearl_mm']) 		? $details['pearl_mm'] 		: '';
								$semi_mounted   = isset($details['semi_mounted']) 	? $details['semi_mounted'] 	: '';
								$side  			= isset($details['side']) 			? $details['side'] 			: '';
								$description 	= isset($details['description']) 	? $details['description'] 	: '';
							    $small_img 		= isset($details['small_image'])   	? $details['small_image'] 	: '';
							    $big_img 		= isset($details['big_image'])   	? $details['big_image'] 	: '';
							    $carat_image	= isset($details['carat_image']) 	? $details['carat_image'] 	: '';
							    $style          = isset($details['style'])			? $details['style'] 		: '';
							    $ringtype		= isset($details['ringtype'])		? $details['ringtype'] 		: '';
							 	
			                    if(isset($animations)){
			                    	$image45	= isset($animations['image45']) 	? $animations['image45'] : '';
			                    	$image90	= isset($animations['image90']) 	? $animations['image90'] : '';
			                    	$image180	= isset($animations['image180']) 	? $animations['image180'] : '';
			                    	
			                    	$image45_bg	= isset($animations['image45_bg']) 			? $animations['image45_bg'] : '';
			                    	$image90_bg	= isset($animations['image90_bg']) 			? $animations['image90_bg'] : '';
			                    	$image180_bg	= isset($animations['image180_bg']) 	? $animations['image180_bg'] : '';
			                    	
			                    	$animation1 = isset($animations['flash1']) 		? $animations['flash1'] : '';
			                    	$animation2 = isset($animations['flash2']) 		? $animations['flash2'] : '';
			                    	$animation3 = isset($animations['flash3']) 		? $animations['flash3'] : '';
			                    }else {
			                    	$image45 	=  '';
			                    	$image90 	=  '';
			                    	$image180 	=  '';
			                    	
			                    	$image45_bg 	=  '';
			                    	$image90_bg 	=  '';
			                    	$image180_bg 	=  '';
			                    	
			                    	$animation1 =  '';
					            	$animation2 =  '';
					            	$animation3 =  '';
			                    }
			                    
			    
			 }else{
			 	 	$price 			= 0;
				 	$section 		= '';
				 	$collection   	= '';
				 	$carat   		= '';
				 	$shape   		= '';
				 	$metal   		= '';
				 	$finger_size   	= '';
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
				 	
			 	 	$image45 		=  '';
	            	$image90 		=  '';
	            	$image180 		=  '';
	            	
	            	$image45_bg 	=  '';
                	$image90_bg 	=  '';
                	$image180_bg 	=  '';
				                    	
					$animation1 	=  '';
	            	$animation2 	=  '';
	            	$animation3 	=  '';
			    
			 }
			  		$id         	= isset($id) ? $id : '';
			
			?>
			<div>
					<h1 class="hbb" align="center">
								<?=ucfirst($action) ?> Rings
					
					</h1>
					
					<br/>
					<div align="center">
					 
						 <form name="" action="<?php echo config_item('base_url');?>admin/jewelries/<?php echo $action; echo ($action == 'edit') ? '/' .$id : '';?>" method="post" enctype="multipart/form-data" >
							
						 			<div class="lebelfield floatl">Price:</div>
									<div class="inputfield floatl">
											<input type="text" name="price" value="<?php echo $price;?>" maxlength="15" class="price" /><?php echo form_error('price'); ?> 											
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Section:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="section" id="section" onchange="setcollection(); setringtype()">
												
											<?php echo makeOptionSelected($sectionoptions , $section);?>
											</select> <?php echo form_error('section'); ?> 
									</div>
									<div class="clear"></div>
									
									 
									
									<div class="lebelfield floatl">Collection:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="collection" id="collection" onchange="setringtype()">	
												<option value="">Select Collection</option>
												<?php echo  makeOptionSelected($collections,$collection);?>
													 						
											</select> <?php echo form_error('collection'); ?> 
									</div>
									<div class="clear"></div>
									
									
									<div id="typediv" <?php echo ($collection == 'WomensAnniversaryRing' ? 'style="display:block;"' : 'style="display:none;"')?>>
											<div class="lebelfield floatl">Type:</div>
											<div class="inputfield floatl">
													<select class="commondropdown" name="ringtype" id="ringtype">
														<option value="<?php echo $ringtype;?>"><?php echo $ringtype;?></option>
													</select>
													<?php echo form_error('ringtype'); ?> 
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div id="shapediv">
											<div class="lebelfield floatl">shape:</div>
											<div class="inputfield floatl">
													<select class="commondropdown" name="shape">
													<?php echo makeOptionSelected($shapeoptions , $shape);?>
													</select> <?php echo form_error('shape'); ?>
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div class="lebelfield floatl">Metal:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="metal">
											<?php echo makeOptionSelected($metaloptions , $metal);?>
											</select> <?php echo form_error('metal'); ?> 
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Style:</div>
									<div class="inputfield floatl">
											<select class="commondropdown" name="style">
											<?php echo makeOptionSelected($styleoptions , $style);?>
											</select> <?php echo form_error('style'); ?> 
									</div>
									<div class="clear"></div>
									
									
									<div id="caratdiv">
											<div class="lebelfield floatl">Carat:</div>
											<div class="inputfield floatl">
													<input type="text" name="carat" value="<?php echo $carat;?>" maxlength="50"  />
											</div>
											<div class="clear"></div>
									</div>
									
									
									
									<div id="totalcaratdiv">
											<div class="lebelfield floatl">Total Carat:</div>
											<div class="inputfield floatl">
													<input type="text" name="total_carats" value="<?php echo $total_carats;?>" maxlength="50"  />
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div class="lebelfield floatl">Description:</div>
									<div class="inputfield floatl">
											<textarea name="description" style="width: 400px;height: 60px;"><?php echo $description;?></textarea> 
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Finger Size:</div>
									<div class="inputfield floatl">
											<input type="text" name="finger_size" value="<?php echo $finger_size;?>"  />  
									</div>
									<div class="clear"></div>
									
									
									<div id="diamondcountdiv">
											<div class="lebelfield floatl">Diamond Count:</div>
											<div class="inputfield floatl">
													<input type="text" name="diamond_count" value="<?php echo $diamond_count;?>"  /> 
											</div>
											<div class="clear"></div>
									</div>
									
									
									
									<div id="diamondsizediv">
											<div class="lebelfield floatl">Diamond Size:</div>
											<div class="inputfield floatl">
													<input type="text" name="diamond_size" value="<?php echo $diamond_size;?>"   />
											</div>
											<div class="clear"></div>
									</div>

																
									
									
									<div id="pearllength">
											<div class="lebelfield floatl">Pearl Lenght:</div>
											<div class="inputfield floatl">
													<input type="text" name="pearl_lenght" id="pearl_lenght" value="<?php echo $pearl_lenght;?>"   /> 
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div id="pearlmm">
											<div class="lebelfield floatl" id="pearlmmlabel">Pearl mm:</div>
											<div class="inputfield floatl">
													<input type="text" name="pearl_mm" id="pearl_mm" value="<?php echo $pearl_mm;?>" /> 
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div id="semi">
											<div class="lebelfield floatl">Semi Mounted:</div>
											<div class="inputfield floatl">
													<input type="text" name="semi_mounted" id="semi_mounted" value="<?php echo $semi_mounted;?>" />
											</div>
											<div class="clear"></div>
									</div>
									
									
									<div id="sidediv">
											<div class="lebelfield floatl">Side:</div>
											<div class="inputfield floatl">
													<input type="text" name="side" id="side" value="<?php echo $side;?>" />
											</div>
											<div class="clear"></div>
									</div>
						 
						 
						 	
						 
						 	<table 	 border="0" align="left" width="100%"/>
							     
							   
							 
							  
							  
							  <tr>
								<td colspan="2"> 
									<table width="100%"><tr><td valign="top">
												<fieldset style="background: #fff;">
												<legend>Small Image</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/'.$small_img) && $small_img !='')echo '<img width="120" height="120" src="'.config_item('base_url').'images/rings/'.$small_img.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="image_small" id="file1" value=""  /> 
													</center>
												</fieldset>	
											</td>
										<td>
												<fieldset style="background: #fff;">
												<legend>Carat Image</legend>
													<center>
												 <?php  {
												  
												    	if(file_exists(config_item('base_path').'images/rings/carat'.$carat_image) && $carat_image !='')echo '<img width="120" height="120" src="'.config_item('base_url').'images/rings/carat'.$carat_image.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png"><br />';
												    	echo '<small>Upload new image will replace the old image</small><br />'; 
												    }   
												 ?>
													<input type="file" name="carat_image" id="carat_image" value=""  /> 
													</center>
												</fieldset>	
										</td>
									  </tr>
							  		</table>
							  </td></tr>
							  
							  
							   <tr>
								<td colspan="2">
								<fieldset style="background: #fff;">
									<legend>Rings Animations Icons</legend>
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
											      			   <div id="animation1"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation1) && $animation1 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation1."\", \"Animation1\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/45.swf\", \"Animation1\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation1\");</script><br />";
															    	echo '<small>Upload small animated picture of the ring</small><br />'; 
													    	 
													    	 	?>
													    	 	</script>
													        <input type="file" name="animation1" id="animation1" value=""  /> 
											     </td> 
											      <td align="center"> 
											                     <div id="animation2"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation2) && $animation2 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation2."\", \"Animation2\", \"405\", \"290\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/90.swf\", \"Animation2\", \"405\", \"290\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation2\");</script><br />";
															    	echo '<small>Upload Larger animated picture of the ring</small><br />'; 
													    	 
													    	 	?></script>
													        <input type="file" name="animation2" id="animation2" value=""  /> 
											     </td> 
											      <!--<td align="center">
											      		 
											      			   <div id="animation3"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation3) && $animation3 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation3."\", \"Animation3\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/180.swf\", \"Animation3\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation3\");</script><br />"
															    	
													    	 
													    	 	?></script>
													        <input type="file" name="animation3" id="animation3" value=""  /> 
											     </td> -->
											      
										</tr>
								       </table>
								       <?php if($action == 'edit'){?>
									   <small><font color="Red">** Upload new image will replace the old Animation</font> -- Please refresh your browser (Ctrl+R) / clear your cache to view changes if u show the old image after upload</small><br />
									   <?php }?>
								</fieldset>
								
								</td>
							  </tr>
							  <tr>
							  <td></td><td><br>
							   <input type="submit"  name="<?=$action;?>btn" value="<?=ucfirst($action);?>" class="adminbutton"  /> <a href="<?php echo config_item('base_url')?>admin/jewelries" class="adminbutton"> Cancel</a>
										 
							  </td>
							  </tr>
							</table>
							 
						</form>
					</div>
			</div>
			<?php*/ }else{?>
		 
			<div>
					<table id="results" style="display:none; "></table>
			</div>
		<?php }?>
</div>
 

 
