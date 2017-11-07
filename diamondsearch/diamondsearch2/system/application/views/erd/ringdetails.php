<div class="pad10">
   <h1 class="hr pageheader">Ring Details ( Price : $<?php echo $details['price']?>) </h1>
   <div class="pad10">
     <h3 class="newtileheader"><?php echo isset($details['collection']) ? $details['collection'] : '';?></h3><br />
      <table>
	        <tr>
		         <td valign="top">
				         <div class="detailcontainer " style="width:305px;padding-top:0px;" id="detailcontainer"> 
							<table >
							      <tr>
							        <td style="width: 140px;" align="right" valign="top">
									
							        	<?php
	        $image45 	= config_item('base_url').'images/rings/icons/45/45degree.jpg';
			$image90 	= config_item('base_url').'images/rings/icons/90/90degree.jpg';
			$image180 	= config_item('base_url').'images/rings/icons/180/180degree.jpg';
			
			$image45_bg 	= config_item('base_url').'images/rings/icons/45/45.jpg';
			$image90_bg 	= config_item('base_url').'images/rings/icons/90/90.jpg';
			$image180_bg 	= config_item('base_url').'images/rings/icons/180/180.jpg';
													
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
			
			if($flashfiles['image45_bg'])
			{
			   $image45_bg =  'images/rings/icons/45'.$flashfiles['image45_bg']; 
			  $image45_bg =(file_exists(config_item('base_path').$image45_bg)) ?  config_item('base_url').$image45_bg : config_item('base_url').'images/rings/icons/45/45.jpg';            
			}
			
			if($flashfiles['image90_bg'])
			{
			   $image90_bg 	= 'images/rings/icons/90'.$flashfiles['image90_bg'];
			   $image90_bg =(file_exists(config_item('base_path').$image90_bg)) ?  config_item('base_url').$image90_bg : config_item('base_url').'images/rings/icons/90/90.jpg';
			}
			
			if($flashfiles['image180_bg'])
			{
			  $image180_bg 	= 'images/rings/icons/180'.$flashfiles['image180_bg'];
			  $image180_bg =(file_exists(config_item('base_path').$image180_bg)) ?  config_item('base_url').$image180_bg : config_item('base_url').'images/rings/icons/180/180.jpg';
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
		
													
		//var_dump($image45);
			//exit(0);											
													
											?>
							              <img src="<?=$image180?>" onclick="writebigimg(180,'<?=$image180_bg?>')" alt="180 degree" style="margin: 0px 5px 5px 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br />
							              <img src="<?=$image90?>" onclick="writebigimg(90,'<?=$image90_bg?>')" alt="90 degree"  style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br />
							              <img src="<?=$image45?>" onclick="writebigimg(45,'<?=$image45_bg?>')" alt="45 Degree" style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;">
							        </td>
							        <td   align="left" valign="top">
							          
							            	<div id="flashanimation" style="border:1px solid #eee; "></div>
							            
							            	<div id="bigflash" style="display:none;"> </div>
								            <div id="smallflash">
								              	<center><input type="button" class="tbutton3" id="toggleflash" onclick="if(this.value == 'Large View') {this.value ='Close(X)';writeswf(2);}else{ this.value='Large View'; writeswf(1);}" value="Large View" /> </center>
								            </div>
							            
							             
										<script type="text/javascript">
											// <![CDATA[
											 
											function writeswf(swfid){
																	if(swfid == 1){
																			so = new SWFObject("<?=$flash2?>", "test", "245", "195", "8", "#fff");
																			so.write("flashanimation");
																			$('#smallflash').show();
																			$('#flashanimation').show();
																			$('#bigflash').hide();
																			$('#detailcontainer').css('width','350px');
																	}	
																	if(swfid == 2){
																			so = new SWFObject("<?=$flash3?>", "test", "400", "295", "8", "#fff");
																			so.write("bigflash");
																			$('#bigflash').show();
																			$('#smallflash').hide();
																			$('#flashanimation').hide();
																			$('#detailcontainer').css('width','450px');
																	}
																	
																	 
											}
											writeswf(1); 
											
											
											// ]]>
										</script>
                                        <script type="text/javascript">
// <![CDATA[
			     function setSize() {
				    var dsize = $("#dsize").val();
				     var metaltype = $("#metaltype").val();
				 
				 window.location.href = "<?php echo config_item('base_url')?>engagement/addtobasket/<?php echo $lot;?>/<?php echo $stockno;?>/addtoring/false/false/"+dsize+"/"+metaltype;
				 }
				 // ]]>
			 </script>
							        </td>
							      </tr>
							</table>
						</div>
		         </td> 
		         <td valign="top">
		         	 <? // print_r($details);?>
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
																	<td width="120" class="brownback">Ring Name</td>
																	<td width="120" class="brownback"><?php echo $details['name']; ?></td>
																</tr>
																<tr>
																	<td width="120" class="brownback">Lot #</td>
																	<td width="120" class="brownback"><?php echo $details['stock_number']; ?></td>
																</tr>
																
																 <?php if(isset($details['section'])){?>
																<tr>
																	<td width="120" class="brownback">Section</td>
																	<td width="120" class="brownback"><?php echo $details['section'];?><br /></td>
																</tr>
																<?php }?>
																	 <?php if(isset($details['style'])){?>
																<tr>
																	<td width="120" class="brownback">Style</td>
																	<td width="120" class="brownback"><?php echo $details['style'];?><br /></td>
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
																
																 <?php if(isset($products['carat'])){?>
																<tr>
																	<td width="120" class="brownback">Center Diamonds</td>
																	<td width="120" class="brownback"><?php echo $products['carat'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($details['total_carats'])){?>
																<tr>
																	<td width="120" class="brownback">Total Side Carats</td>
																	<td width="120" class="brownback"><?php echo $details['total_carats'];?><br /></td>
																</tr>
																<?php }?>	

																 <?php // if(isset($details['metal'])){?>
																<!--tr>
																	<td width="120" class="brownback">Metal</td>
																	<td width="120" class="brownback"><?php // echo $details['metal'];?><br /></td>
																</tr-->
																<?php // }?>
																
																 <?php if(isset($details['metal'])){?>
																<tr>
																	<td width="120" class="brownback">Metal</td>
																	<td width="120" class="brownback">  
																	<select id="metaltype" name="metaltype">
																	    <option value="platinum">Platinum</option>
																		<option value="yellowgold">Yellow Gold</option>
																		<option value="whitegold">White Gold</option>
																  </select></td>
																</tr>
																<?php }?>
																														 
																 <?php if(isset($details['finger_size'])){?>
																<tr>
																	<td width="120" class="brownback">Available Sizes</td>
																  <td width="120" class="brownback">
																  <select id="dsize" name="dsize">
																    <option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																  </select>
																  </td>
																</tr>
																<?php }?>
															</tbody>
														</table>
														<br>
													</div>
												</div>
						        </td></tr>
						        </table>
						        			
						 
		         </td>
	        </tr>
	        </table>
	        
	        <table width="100%"> 
	        <tr>
	        	<td colspan="2" width="100%" class="infobox01 pad2" >Description</td>
	        </tr>
	        <tr>
	        <td>  
				 
				<p class="detaails brownback pad10">
		         		    <?php echo $details['description'];?>
				</p>
		 
														
													
	        	</td>
	        </tr>
	         </table>
	         <br>
			 
	        <table width="100%">
	        <tr>
	        	<td style="width:20px;"><img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg"></td>
	        	<td  class="infobox01 pad2" >Shipping Information </td>
	       </tr>
	       <tr>
	       <td colspan="2" class="brownback pad10">
		        <p class="pad10">
		              i.      Fed-x <br>
                     ii.      The ring Will arrive in 2-4 Businness Days.<font color="red">Arrival depends on the specific diamond selected.</font><br />

                                                            iii.      always offers Free Shipping via FedEX.
				 <a href="<?php echo config_item('base_url');?>site/page/freefedEx">
				<b>FedEx Priority Overnight</b></a> <br><br>
				</p>
	        </td>
	        </tr>
	        
	        
	        <tr>
		        <td colspan="2" align="right"><br> 
		        										<div>
															<div class="floatr w100px">
																	<div class="floatr blueright"></div>
																	<div class="floatr bluemiddle"><a href="#" onclick="setSize();">Select this ring</a></div>
																	<div class="floatr blurleft"></div>
																	<div class="clear"></div>
															</div>
															<div class="floatl w100px">
																	<div class="floatr brownright"></div>
												      				<div class="floatr brownmiddle"><a href="#" onclick="$.facebox.close()">back to search</a></div>
												      				<div class="floatr brownleft"></div>
												      				<div class="clear"></div>
												      		</div>
												      		<div class="clear"></div>
														</div>
		        </td>
	        </tr>
      </table>
      
      											
						    
     		
				
   </div>
</div>
  