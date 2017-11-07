<?php $lotno =  ($this->session->userdata('mydiamondid')) ? $this->session->userdata('mydiamondid') : '';?> 
<div class="pad10">
   <h1 class="hr pageheader">Ring Details ( Price : $<?php echo $details['price']?>) </h1>
   <div class="pad10">
     <h3 class="newtileheader">Petite Trellis Ring in Platinum</h3><br />
      <table>
	        <tr>
		         <td>
                 		<?php 
													/*$flash1 = '180.swf';
													$flash2 = '90.swf';
													$flash3 = '45.swf';*/
													
													$image45 	= config_item('base_url').'images/rings/icons/45/45degree.jpg';
			$image90 	= config_item('base_url').'images/rings/icons/90/90degree.jpg';
			$image180 	= config_item('base_url').'images/rings/icons/180/180degree.jpg';
			
			$image45_bg 	= config_item('base_url').'images/rings/icons/45/45.jpg';
			$image90_bg 	= config_item('base_url').'images/rings/icons/90/90.jpg';
			$image180_bg 	= config_item('base_url').'images/rings/icons/180/180.jpg';
													
			$flash1 	= config_item('base_url').'flash/45.swf';
			$flash2 	= config_item('base_url').'flash/90.swf';
			$flash3 	= config_item('base_url').'flash/180.swf';			
													
													/*if(isset($flashfiles) & is_array($flashfiles)){
														$flash1 = file_exists(config_item('base_path').'flash/'.$flashfiles['flash1']) ? $flashfiles['flash1'] : $flash1;
														$flash2 = file_exists(config_item('base_path').'flash/'.$flashfiles['flash2']) ? $flashfiles['flash2'] : $flash2;
														$flash3 = file_exists(config_item('base_path').'flash/'.$flashfiles['flash3']) ? $flashfiles['flash3'] : $flash3;
													}*/
													
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
											?>
				         <div class="detailcontainer " style="width:395px"> 
							<table >
							      <tr>
							        <td style="width: 140px;" align="right">
							              <img src="<?=$image180?>" onclick="writebigimg(180,'<?=$image180_bg?>')" alt="180 degree" style="margin: 0px 5px 5px 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br />
							              <img src="<?=$image90?>" onclick="writebigimg(90,'<?=$image90_bg?>')" alt="90 degree"  style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;"><br />
							              <img src="<?=$image45?>" onclick="writebigimg(45,'<?=$image45_bg?>')" alt="45 Degree" style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;">
							        </td>
							        <td style="width: 250px;" align="left">
							            <div id="flashanimation"></div>
							             
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
                                        <script language="javascript">
			     function setSize()
				 {
				    var dsize = $("#dsize").val();
					
					 window.location.href = "<?php echo config_item('base_url')?>engagement/addtobasket//<?php echo $centreid;?>/<?php echo $stockno;?>/tothreestone/<?php echo $sidestoneid1;?>/<?php echo $sidestoneid2;?>/"+dsize;
					//return false;
					
				 }
			 </script>
							        </td>
							      </tr>
							</table>
						</div>
		         </td>
		         <td valign="top">
		         		<p class="detaails">
		         		    <?php echo $details['description'];?>
							
						</p>
						<p>
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
																	<td width="120" class="brownback">Stock number</td>
																	<td width="120" class="brownback"><?php echo $details['stock_number']; ?></td>
																</tr>
																<tr>
																	<td width="120" class="brownback"><a href="#"><b><u>Metal</u></b></a></td>
																	<td width="120" class="brownback"><?php echo $details['metal']; ?></td>
																</tr> 
																<!--<tr>
																	<td width="120" class="brownback">Available in sizes</td>
																	<td width="120" class="brownback">4-8<br /></td>
																</tr>-->
                                                                <tr>
																	<td width="120" class="brownback">Available Sizes</td>
																  <td width="120" class="brownback">
																  <select id="dsize" name="dsize">
																    <option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	  </select>
																  </td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
						        </td></tr>
						        </table>
						        			
												
						
						</p>
		         </td>
	        </tr>
	        <tr>
	        	<td colspan="2" width="100%">
	        	      <div class="floatl padl10">
														<div class="floatl">
														<img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg">
															
															</div>
														<div class="infobox01 floatl w600px">Shipping Information</div>
														<div class="clear"></div>
														<div class="brownback">
															This ring usally arrives in 2-4 business days. Arrival depends on the diamond selected.
															<br>
															<div style="border:1px #fff solid;"></div>
															Free shpping via<br>
															<b>FedEx Priority Overnight</b>
														</div>
														<div class="clear"></div><br>
														
													
	        	</td>
	        </tr>
	        <tr>
		        <td colspan="2" align="right">
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
  