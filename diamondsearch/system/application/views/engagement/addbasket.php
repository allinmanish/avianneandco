<?php 
	
	$onclickfunction = '';
	$totalprice = '';
	$metalprice = '';
	
	switch ($metaltype){
		case 'platinum':
			$metalprice = $ringdetails['price'];
			break;
		case 'yellowgold':
			$metalprice = $ringdetails['yellow_gold_price'];
			break;
		case 'whitegold':
			$metalprice = $ringdetails['white_gold_price'];
			break;
	}
	
	switch ($addoption){
		case 'addtoring':
			//$totalprice = $diamonddetails['price'] + $ringdetails['price'] ;
			$totalprice = $diamonddetails['price'] + $metalprice ;
			//$totalprice = $metalprice;
			$onclickfunction = "addtocart('".$addoption."',". $lotno.",false,false,".$stockno.",".$totalprice.",".$dsize.")";
			break;
			
		case 'tothreestone':
			//$totalprice = $diamonddetails['price'] + $ringdetails['price'] + $side1['price'] + $side2['price'];
			$totalprice = $diamonddetails['price'] + $metalprice + $side1['price'] + $side2['price'];
			$onclickfunction = "addtocart('".$addoption."',". $lotno.",". $sidestone1.",". $sidestone2.",".$stockno.",".$totalprice.",".$dsize.")";
			break;
	}
	
	echo $diamonddetails['price'];
?>

<div class="floatl pad05 body">
  		<div class="bodytop"></div>
	  	<div class="bodymid"> 
	    		
	  			<h1 class="pageheader hr">Add To Your Basket</h1>
	  			      
	  			<?php echo $tabhtml?>
	  			
	  			<?php  echo $ringdetails['id']; ?>
	  			<div class="floatl detailcontainer">
				<div class="dbr"></div>
				<div class="newtileheader">Petite Trellis Ring in Platinum</div>
				<div class="detaails">
					<!--This Striking platinum solutaire setting offers a more delicate intepretation of Trellis Engagement Ring. A narrower shank gently countours to
					cradle your choice of round, pricess or asscher-cut center diamond with the same classic trellis design.-->
					<?php echo $ringdetails['description']?>
				</div>
				
				
				<div class="clear"></div>
				<div class="dbr"></div>
			 
			</div>
					
			<div class="floatl" style="padding: 10px 2px 10px 2px;">
			     <table>
				     <tr>
					     <td>
					        <div id="flashanimation"></div>
					        
					       
					     </td>
				     </tr>
				     <tr>
				     	<td>
				     	   
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
													
												/*	if(isset($flashfiles) & is_array($flashfiles)){
														$flash1 = (file_exists(config_item('base_path').$flashfiles['flash1']) && ($flashfiles['flash1'] != '')) ? $flashfiles['flash1'] : $flash1;
														$flash2 = (file_exists(config_item('base_path').$flashfiles['flash2']) && ($flashfiles['flash2'] != '')) ? $flashfiles['flash2'] : $flash2;
														$flash3 = (file_exists(config_item('base_path').$flashfiles['flash3']) && ($flashfiles['flash3'] != '')) ? $flashfiles['flash3'] : $flash3;
														
														$image45 = ((file_exists(config_item('base_path').'images/rings/icons/45/'.$flashfiles['image45']))  && $flashfiles['image45'] !='') ? $flashfiles['image45'] : $image45; 
														$image90 = ((file_exists(config_item('base_path').'images/rings/icons/90/'.$flashfiles['image90']))  && $flashfiles['image90'] !='') ? $flashfiles['image90'] : $image90; 
														$image180 = ((file_exists(config_item('base_path').'images/rings/icons/180/'.$flashfiles['image180']))  && $flashfiles['image180'] !='') ? $flashfiles['image180'] : $image180; 
														
														$image45_bg = ((file_exists(config_item('base_path').'images/rings/icons/45/'.$flashfiles['image45_bg']))  && $flashfiles['image45_bg'] !='') ? $flashfiles['image45_bg'] :  '/'.$image45_bg; 
														$image90_bg = ((file_exists(config_item('base_path').'images/rings/icons/90/'.$flashfiles['image90_bg']))  && $flashfiles['image90_bg'] !='') ? $flashfiles['image90_bg'] : '/'.$image90_bg; 
														$image180_bg = ((file_exists(config_item('base_path').'images/rings/icons/180/'.$flashfiles['image180_bg']))  && $flashfiles['image180_bg'] !='') ? $flashfiles['image180_bg'] : '/'.$image180_bg; 
														
														$image45_bg = 'images/rings/icons/45'.$image45_bg;
														$image90_bg = 'images/rings/icons/90'.$image90_bg;
														$image180_bg = 'images/rings/icons/180'.$image180_bg;
														*/
													}
											?>
										 <center>
										 <a href="javascript:void(0)" onclick="showbigflash()">Larger View </a> <br>
							              <img src="<?=$image180?>" onclick="writebigimg2(180,'<?=$image180_bg?>')" alt="180 degree" style="margin: 0px 5px 5px 5px;width:58px;height:58px;border:1px solid #0B81A5;"> 
							              <img src="<?=$image90?>" onclick="writebigimg2(90,'<?=$image90_bg?>')" alt="90 degree"  style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;"> 
							              <img src="<?=$image45?>" onclick="writebigimg2(45,'<?=$image45_bg?>')" alt="45 Degree" style="margin: 5px;width:58px;height:58px;border:1px solid #0B81A5;">
							             </center> 
							                   
										<script type="text/javascript">
											// <![CDATA[
											 
											function writeswf(swfid){
																	if(swfid == 1){
																			so = new SWFObject("<?=$flash2?>", "test", "245", "195", "8", "#fff");
																			so.addParam("wmode", "transparent");
																			so.write("flashanimation");
																			 
																	}	
																	if(swfid == 2){
																			so = new SWFObject("<?=$flash3;?>", "test", "400", "295", "8", "#fff");
																			so.addParam("wmode", "transparent");
																			so.write("bigflash");
																			 
																	}
																	
																	 
											}
											writeswf(1); 
											
											
											// ]]>
										</script>
				     	</td>
				     </tr>
			     </table>
			
			     
			 	 <div>
			 	    <div class="floatr" style="padding-top: 5px;">
			 	  
						&nbsp; Total : $<?php echo round($totalprice);?>&nbsp;&nbsp;  <?php // echo $metaltype; ?><br>
				 	</div>
					
					<div class="floatr blueright"></div>
					<form  action="/store/checkout/cart/addloose?product=6316&options[16571]=<?php echo $ringdetails['stock_number'] ?>&options[16572]=<?php echo $metaltype; ?>&options[16568]=Milano collection&options[16569]=<?php echo $ringdetails['shape'];?>&options[16570]=<?php echo $ringdetails['style'];?>&options[16574]=<?php echo $dsize;?>&options[16573]=<?php  echo round($totalprice);?>&options[16635]=<?php  echo $lotno; ?>" method="post" name="cartForm" target="_top">
						<div class="floatr bluemiddle"><a href="#" onclick="document.cartForm.submit()">Add to shopping bag</a></div>
						<input type="hidden" value="<?php echo round($totalprice) ?>" name="ownsuiteprice"  />
					</form>
					<div class="floatr blurleft"></div>
					
					<div class="clear"></div>
				</div>
			</div>
  			<div class="clear"></div>
				
				<div>
					<div class="floatl  padl10">
						<div class="floatl">
						<img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" onclick="showhide('productdetails', 'true')" style="display:none;">
							<img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="minimize" onclick="showhide('productdetails', 'false')">
							</div>
						<div class="infobox01">Product Details</div>
						<div class="clear"></div>
						
						<div id="productdetails" style="display:;">
							<div class="divheader font12 padt10">Setting information</div>
							<table cellpadding="0" cellspacing="0" border="0">
								<tbody>
								 
									<tr>
																	<td width="120" class="brownback">Lot #</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['stock_number']; ?></td>
									 
																 <?php if(isset($ringdetails['metal'])){?>
																 
																	<td width="120" class="brownback">Metal</td>
																	<td width="120" class="brownback">
																	<?php // echo $ringdetails['metal'];
																	  echo $metaltype; 
																	  ?>
																	</td>
																</tr>
																<?php }?>
																 <?php if(isset($ringdetails['section'])){?>
																<tr>
																	<td width="120" class="brownback">Section</td>
																	<td width="120" class="brownback">Milano collection<?php //echo $ringdetails['section'];?><br /></td>
															 
																<?php }?>
																	 <?php if(isset($ringdetails['style'])){?>
																 
																	<td width="120" class="brownback">Style</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['style'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($ringdetails['carat'])){?>
																<tr>
																	<td width="120" class="brownback">Shape</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['shape'];?></td>
																 
																<?php }?>
																 <?php if(isset($ringdetails['total_carats'])){?>
																 
																	<td width="120" class="brownback">Total Side Carats</td>
																	<td width="120" class="brownback"><?php echo $diamonddetails['carat'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($ringdetails['shape'])){?>
																<tr>
																	<td width="120" class="brownback">Center Carat</td>
																  <td width="120" class="brownback"><?php echo $ringdetails['diamond_size'];?></td>
																 
																<?php }?>
																 <?php if(isset($ringdetails['diamond_count'])){?>
																 
																	<td width="120" class="brownback">Selected Size</td>
																	<td width="120" class="brownback"><?=$dsize?></td>
																</tr>
																<?php }?>
																 <?php if(isset($ringdetails['diamond_size'])){?>
																<tr>
																	<td width="120" class="brownback">Diamond Count</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['diamond_count'];?><br /></td>
																  <td width="120" class="brownback"></td>
																	<td width="120" class="brownback">																	</td>
															 
																<?php }?>
																
																 <!--<?php if(isset($ringdetails['pearl_lenght'])){?>
																 
																	<td width="120" class="brownback">Pearl_lenght</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['pearl_lenght'];?><br /></td>
																</tr>
																<?php }?>
																 <?php if(isset($ringdetails['pearl_mm'])){?>
																<tr>
																	<td width="120" class="brownback">Pearl_mm</td>
																	<td width="120" class="brownback"><?php echo $ringdetails['pearl_mm'];?><br /></td>
																 
																<?php }?>-->
																
																 <?php if(isset($ringdetails['side'])){?>
																
																<?php }?>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="clear"></div>
				 <div class="padl10">
				 <br>
					  <table width="100%">
				        <tr>
				        	<td style="width:20px;"><img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg"></td>
				        	<td  class="infobox01 pad2" > Shipping Information </td>
				       </tr>
						 <tr>
					       <td colspan="2" class="brownback pad10">
						   <div class="floatl padl10 margin10">
							<ul>
								<li>i.&nbsp;&nbsp;Fed-x</li>
								<li>ii.&nbsp;The ring Will arrive in 2-4 Businness Days. Arrival depends on the specific diamond selected.</li>
								<li>iii.Always offers Free Shipping via FedEX.</li>
								
							</ul>
						</div>
						        
					        </td>
					        </tr>
					        
						</table>
					
					</div>
				</div>
				   
				<br><div class="border02"></div>
				<div><br /></div>
				<div>
					<div class="floatl detailcontainers padl10">
						<div class="newtileheader">This Item Comes With :</div>
						<div class="floatl padl10 margin10">
							<ul>
								<li><a href="#">Free FedEx shipping</a></li>
								<li><a href="#">Appraisal Included</a></li>
								<li><a href="#">Free gift packaging</a></li>
								<li><a href="#">Expanded holiday returns</a></li>
							</ul>
						</div>
					</div>
					
					<div class="floatl detailcontainers padl10">
						<div class="floatl">
							<img src="<?php echo config_item('base_url')?>images/ruman/engagement/gift_box.jpg" border="0" />
						</div>
						 
					</div>
					<div class="clear border02"></div>
					</div>
				 
	  			<div class="floatl padl10">
						<div class="floatl">
							<b>Note: </b> Prices and avilability are subject to change without notice  View our product policies for more information.  This setting available only with purchase of a diamond.
						</div>
						
						<div class="clear"></div>
				</div>
				<div class="clear"></div>
	  			
	  			
	  			
	  			
	  			
	  			
	  			  			
	  			  	
	  	</div>
	  	<div class="bodybottom"></div>
</div>