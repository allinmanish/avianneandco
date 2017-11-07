<div>
		<?php if($action == 'add' || $action == 'edit'){
	       	$this->load->helper('custom','form');
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
			
			$prongmetaloptions = '<option value=""> Select Metal </option>
				                 <option value="18kt. white gold">18kt. white gold </option>
				                 <option value="Platinum">Platinum </option>
				                 <option value="White Gold">White Gold </option>
				                 <option value="Yellow Gold">Yellow Gold </option>
				                 '; 
			$styleoptions      = '<option value=""> Select Style </option>
				                 <option value="sidestones">Sidestones </option>
				                 <option value="Pave">Pave </option>
				                 <option value="solitaire">Solitaire </option>
				                 <option value="three stones">Three stones </option>
				                  ';
			 
			
			 if(isset($details)){
							 	$price 			= isset($details['price']) ? $details['price'] : 0;
							    $width 			= isset($details['width']) ? $details['width'] : '';
							    $metal 			= isset($details['metal']) ? $details['metal'] : '';
							    $description 	= isset($details['description']) ? $details['description'] : '';
							    $small_img 		= isset($details['image'])   ? $details['image'] : '';
							    $big_img 		= isset($details['big_image'])   ? $details['big_image'] : '';
							    $prong_metal 	= isset($details['prong_metal']) ? $details['prong_metal'] : '';
							 	$shape 			= $details['shape'];
							 	$style 			= $details['style'];
			                    if(isset($animations)){
			                    	$animation1 = isset($animations['flash1']) ? $animations['flash1'] : '';
			                    	$animation2 = isset($animations['flash2']) ? $animations['flash2'] : '';
			                    	$animation3 = isset($animations['flash3']) ? $animations['flash3'] : '';
			                    }else {
			                    	$animation1 =  '';
					            	$animation2 =  '';
					            	$animation3 =  '';
			                    }
			                    
			    
			 }else{
			 	$price = 0;
			    $width = 0;
			    $metal = '';
			    $description = '';
			    $small_img = '';
			    $big_img = '';
			    $prong_metal = '';
			 	$shape = '';
			 	$style = '';
				$animation1 =  '';
            	$animation2 =  '';
            	$animation3 =  '';
			    
			 }
			  $id            = isset($id) ? $id : '';
			
			?>
			<div>
					<h1 class="hbb" align="center">
								<?=ucfirst($action) ?> Rings
					
					</h1>
					
					<br/>
					<div align="center">
					 
						 <form name="" action="<?php echo config_item('base_url');?>admin/rings/<?php echo $action; echo ($action == 'edit') ? '/' .$id : '';?>" method="post" enctype="multipart/form-data" >
							<table 	 border="0" align="left" width="100%"/>
							  <tr>
								<td width="130">Price</td>
								<td>	<input type="text" name="price" value="<?php echo $price;?>" maxlength="15" class="price" /><?php echo form_error('price'); ?> </td>
							  </tr>
							  <tr>
								<td>shape</td>
								<td> <select name="shape">
								<?php echo makeOptionSelected($shapeoptions , $shape);?>
								</select> <?php echo form_error('shape'); ?> </td>
							  </tr> 
							  <tr>
								<td>width</td>
								<td><input type="text" name="width" value="<?php echo $width;?>" maxlength="6"  /> </td>
							  </tr>
							  <tr>
								<td>metal</td>
								<td> <input type="text" name="metal" value="<?php echo $metal;?>"  /> </td>
							  </tr>
							  <tr>
								<td>Prong Metal</td>
								<td> <select name="prong_metal">
								<?php echo makeOptionSelected($prongmetaloptions , $prong_metal);?>
								</select><?php echo form_error('prong_metal'); ?> </td>
							  </tr>
							  <tr>
								<td>Style</td>
								<td> <select name="style">
									<?php echo makeOptionSelected($styleoptions , $style);?>
								</select> <?php echo form_error('style'); ?> </td>
							  </tr>
							  <tr>
								<td>Description</td>
								<td> <textarea name="description" style="width: 400px;height: 60px;"><?php echo $description;?></textarea> </td>
							  </tr>
							  <tr>
								<td colspan="2">
								<fieldset style="background: #fff;">
								<legend>Small Image</legend>
									<center>
								 <?php  {
								  
								    	if(file_exists(config_item('base_path').'images/rings/'.$small_img) && $small_img !='')echo '<img src="'.config_item('base_url').'images/rings/'.$small_img.'"><br />';
								    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png"><br />';
								    	echo '<small>Upload new image will replace the old image</small><br />'; 
								    }   
								 ?>
									<input type="file" name="image_small" id="file1" value=""  /> 
									</center>
								</fieldset>	
								</td>
							  </tr>
							   <tr>
								<td colspan="2">
								<fieldset style="background: #fff;">
								<legend>Rings Animations</legend>
								       <table align="center"  width="100%">
								       <tr>
											      <td align="center"><img src="<?php echo config_item('base_url')?>images/rings/45degree.jpg"><br />
											      			   <div id="animation1"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation1) && $animation1 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation1."\", \"Animation1\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/45.swf\", \"Animation1\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation1\");</script><br />"
															    	
													    	 
													    	 	?>
													        <input type="file" name="animation1" id="animation1" value=""  /> 
											     </td> 
											      <td align="center"><img src="<?php echo config_item('base_url')?>images/rings/90degree.jpg"><br />
											                     <div id="animation2"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation2) && $animation2 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation2."\", \"Animation2\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/90.swf\", \"Animation2\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation2\");</script><br />"
															    	
													    	 
													    	 	?>
													        <input type="file" name="animation2" id="animation2" value=""  /> 
											     </td> 
											      <td align="center">
											      			<img src="<?php echo config_item('base_url')?>images/rings/180degree.jpg"><br />
											      			   <div id="animation3"></div>
															    <script> 
											      				<?php 
														     
															    	if(file_exists(config_item('base_path').$animation3) && $animation3 != '')
															    		echo "so = new SWFObject(\"".config_item('base_url').$animation3."\", \"Animation3\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	else echo "so = new SWFObject(\"".config_item('base_url')."flash/180.swf\", \"Animation3\", \"205\", \"150\", \"8\", \"#fff\"); ";
															    	echo "so.write(\"animation3\");</script><br />"
															    	
													    	 
													    	 	?>
													        <input type="file" name="animation3" id="animation3" value=""  /> 
											     </td> 
											      
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
							   <input type="submit"  name="<?=$action;?>btn" value="<?=ucfirst($action);?>" class="adminbutton"  /> <a href="<?php echo config_item('base_url')?>admin/rings" class="adminbutton"> Cancel</a>
										 
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
 

 
