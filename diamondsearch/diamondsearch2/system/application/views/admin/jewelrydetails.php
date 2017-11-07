<div class="floatr">
	<a href="<?php echo config_item('base_url');?>admin/jewelries/<?php echo isset($id) ? 'edit/'.$id : '#'?>" class="edit"><small>Edit </small></a>
</div><div class="clear"></div>	
<div class="hr"></div>
<div class="pad10">
   <h1 class="hr pageheader">Ring Details ( Price : $<?php echo $details['price']?>) </h1>
   <div class="pad10">
     <h3 class="newtileheader">Diamond Demo Collection<?php //echo isset($details['collection']) ? $details['collection'] : '';?></h3><br />
      <table>
	        <tr>
		         <td valign="top">
				         <div class="detailcontainer " style="width:395px"> 
							<table >
							      <tr>
							        <td style="width: 140px;" align="right">
	<?php 
	        $image45 	= config_item('base_url').'images/rings/icons/45/45degree.jpg';
			$image90 	= config_item('base_url').'images/rings/icons/90/90degree.jpg';
			$image180 	= config_item('base_url').'images/rings/icons/180/180degree.jpg';
			$flash1 	= config_item('base_url').'flash/45.swf';
			$flash2 	= config_item('base_url').'flash/90.swf';
			$flash3 	= config_item('base_url').'flash/180.swf';
			
	if(count($flashfiles)>0)
	{
	    if($flashfiles['image45'])
		{
			$image45 =  'images/rings/icons/45'.$flashfiles['image45']; 
			$image45 =(file_exists(config_item('base_path').$image45)) ?  config_item('base_url').$image45 : config_item('base_url').'images/rings/icons/45/45degree.jpg';            
		}
		
		if($flashfiles['image90'])
		{ 	
			$image90 	= 'images/rings/icons/90'.$flashfiles['image90'];
			$image90 =(file_exists(config_item('base_path').$image90)) ?  config_item('base_url').$image90 : config_item('base_url').'images/rings/icons/90/90degree.jpg';
		}
		
		if($flashfiles['image180'])
		{	
			$image180 	= 'images/rings/icons/180'.$flashfiles['image180'];
			$image180 =(file_exists(config_item('base_path').$image180)) ?  config_item('base_url').$image180 : config_item('base_url').'images/rings/icons/180/180degree.jpg';
		}
		
		if($flashfiles['flash1'])
		{
			$flash1 	= $flashfiles['flash1'];
			$flash1 =(file_exists(config_item('base_path').$flash1)) ?  config_item('base_url').$flash1 : config_item('base_url').'flash/45.swf';
		}
		
		if($flashfiles['flash2'])
		{	
			$flash2 	= $flashfiles['flash2'];
			$flash2 =(file_exists(config_item('base_path').$flash2)) ?  config_item('base_url').$flash2 : config_item('base_url').'flash/90.swf';
		}
		
		if($flashfiles['flash3'])
		{	
			$flash3 	= $flashfiles['flash3'];
			$flash3 =(file_exists(config_item('base_path').$flash3)) ?  config_item('base_url').$flash3 : config_item('base_url').'flash/180.swf';
		}
	}
										/*	
													if(isset($flashfiles) & is_array($flashfiles)){
														$flash1 = (file_exists(config_item('base_path').$flashfiles['flash1']) && ($flashfiles['flash1'] != '')) ? $flashfiles['flash1'] : $flash1;
														$flash2 = (file_exists(config_item('base_path').$flashfiles['flash2']) && ($flashfiles['flash2'] != '')) ? $flashfiles['flash2'] : $flash2;
														$flash3 = (file_exists(config_item('base_path').$flashfiles['flash3']) && ($flashfiles['flash3'] != '')) ? $flashfiles['flash3'] : $flash3;
														
														$image45 = ((file_exists(config_item('base_path').'images/rings/icons/45/'.$flashfiles['image45']))  && $flashfiles['image45'] !='') ? $flashfiles['image45'] : $image45; 
														$image90 = ((file_exists(config_item('base_path').'images/rings/icons/90/'.$flashfiles['image90']))  && $flashfiles['image90'] !='') ? $flashfiles['image90'] : $image90; 
														$image180 = ((file_exists(config_item('base_path').'images/rings/icons/180/'.$flashfiles['image180']))  && $flashfiles['image180'] !='') ? $flashfiles['image180'] : $image180; 
														
													}*/
											?>
											
											
							              <img src="<?=$image180?>" onclick="writeswf(180)" alt="180 degree" style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br />
					                <img src="<?=$image90?>" onclick="writeswf(90)" alt="90 degree"  style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br /></td>
							        <td style="width: 250px;" align="left">
							            <div id="flashanimation" style="border:1px solid #eee; margin: 5px;"></div>
							             
										<script type="text/javascript">
											// <![CDATA[
										 	
											function writeswf(swfid){
																	if(swfid == 180){
																			so = new SWFObject("<?=$flash3?>", "test", "245", "195", "8", "#fff");
																			so.write("flashanimation");
																	}
																	if(swfid == 90){
																			so = new SWFObject("<?=$flash2?>", "test", "245", "195", "8", "#fff");
																			so.write("flashanimation");
																	}
																	if(swfid == 45){
																			so = new SWFObject("<?=$flash1?>", "test", "245", "195", "8", "#fff");
																			so.write("flashanimation");
																	}
											}
											writeswf(180);
											
											
											// ]]>
										</script>
							        </td>
							      </tr>
							</table>
						</div>
		         </td>
		         <td valign="top">
		         		
						<table>
						       <tr>
						        <td valign="top">
						        		<div class="floatl detailcontainers padl10">
													<div class="floatl">
													<img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" style="display:none;" onclick="showhide('productdetails', 'true')">
														<img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="minimize" onclick="showhide('productdetails', 'false')">
														</div>
													<div class="infobox01 floatl bigcontainerL">Product Details</div>
													<div class="clear"></div>
													
													<div id="productdetails" style="display:block;">
														<div class="divheader font12 padt10">Setting information</div>
														<table cellpadding="0" cellspacing="0" border="0">
															<tbody>
															    <tr>
																	<td width="120" class="brownback">Name</td>
																	<td width="120" class="brownback"><?php echo $details['name']; ?></td>
																</tr>
																<tr>
																	<td width="120" class="brownback">Lot #</td>
																	<td width="120" class="brownback"><?php echo $details['stock_number']; ?></td>
																</tr>
																<?php if(isset($details['section'])){?>
																<tr>
																	<td width="120" class="brownback">Section</td>
																	<td width="120" class="brownback"><?php // echo $details['section'];?><br /></td>
																</tr>
																<?php }?>
																
																 
																	 <?php if(isset($details['style'])){?>
																<tr>
																	<td width="120" class="brownback">Style</td>
																	<td width="120" class="brownback"><?php echo $details['style'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($details['metal'])){?>
																<tr>
																	<td width="120" class="brownback">Metal</td>
																	<td width="120" class="brownback"><?php echo $details['metal'];?><br /></td>
																</tr>
																<?php }?>
																
																<?php if(isset($details['platinum_price'])){?>
																<tr>
																	<td width="120" class="brownback">Platinum Price</td>
																	<td width="120" class="brownback"><?php echo $details['platinum_price'];?><br /></td>
																</tr>
																<?php }?>
																<?php if(isset($details['white_gold_price'])){?>
																<tr>
																	<td width="120" class="brownback">White Gold Price</td>
																	<td width="120" class="brownback"><?php echo $details['white_gold_price'];?><br /></td>
																</tr>
																<?php }?>
																<?php if(isset($details['yellow_gold_price'])){?>
																<tr>
																	<td width="120" class="brownback">Yellow Gold Price</td>
																	<td width="120" class="brownback"><?php echo $details['yellow_gold_price'];?><br /></td>
																</tr>
																<?php }?>
																
																 <?php if(isset($details['diamond_count'])){?>
																<tr>
																	<td width="120" class="brownback">Diamond Count</td>
																	<td width="120" class="brownback"><?php echo $details['diamond_count'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($details['total_carats'])){?>
																<tr>
																	<td width="120" class="brownback">Total Carats</td>
																	<td width="120" class="brownback"><?php echo $details['total_carats'];?><br /></td>
																</tr>
																<?php }?>
																
																																									 
																 <?php if(isset($details['finger_size'])){?>
																<tr>
																	<td width="120" class="brownback">Available in sizes</td>
																	<td width="120" class="brownback"><?php echo $details['finger_size'];?><br /></td>
																</tr>
																<?php }?>
															</tbody>
														</table>
													</div>
												</div>
						        </td></tr>
						        </table>
						  
		         </td>
	        </tr>
	        <?php if(isset($details['description']) && $details['description'] != ''){?>
	        <tr>
	        <td colspan="2"><fieldset>
	        <legend>Details </legend>
	         <p class="detaails">
		         		    <?php echo $details['description'];?>
						</p>
	        </fieldset>
	                   
	        </td>
	        </tr>
	        <?php }?>
	        
	        <tr>
	        <td colspan="2">
	        		<table width="100%"><tr><td valign="top">
												<fieldset style="background: #fff;">
												<legend>Small Image</legend>
													<center>
												 <?php 
														$small_img 		= isset($details['small_image'])   	? $details['small_image'] : '';
													    $carat_image	= isset($details['carat_image']) 	? $details['carat_image'] : '';
													     {
												  
												    	if(file_exists(config_item('base_path').'images/rings/'.$small_img) && $small_img !='')echo '<img width="120" height="120" src="'.config_item('base_url').'images/rings/'.$small_img.'"><br />';
												    	else echo '<img src="'.config_item('base_url').'images/rings/noringimage.png"><br />';
												    	 
												    }   
												 ?>
													 
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
												    	 
												    }   
												 ?>
													 
													</center>
												</fieldset>	
										</td>
									  </tr>
							  		</table>
	        
	        
	        </td>
	        </tr>
	         
      </table>
      
      											
						    
     		
				
   </div>
</div>
  