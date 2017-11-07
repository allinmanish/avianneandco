<div class="pad10">
   <h1 class="hr pageheader">Watch Details ( Price : $<?php echo $details['price2']?>) </h1>
   <div class="pad10">
<!--       <h3 class="newtileheader"><?php echo isset($details['style']) ? ucfirst($details['style']).' '.$details['brand'] : '';?></h3><br />
             
 -->  <? //print_r($details);
		$image1 =  $details['thumb']; 
		$image1 =(file_exists(config_item('base_path').$image1)) ?  config_item('base_url').$image1 : config_item('base_url').'images/rings/noimage.jpg';       
		
		$image2 =  $details['large']; 
		$image2 =(file_exists(config_item('base_path').$image2)) ?  config_item('base_url').$image2 : config_item('base_url').'images/rings/noimage.jpg';       

		$image3 =  $details['image_small2']; 
		$image3 =(file_exists(config_item('base_path').$image3)) ?  config_item('base_url').$image3 : config_item('base_url').'images/rings/noimage.jpg';       
		$image4 =  $details['image_big2']; 
		$image4 =(file_exists(config_item('base_path').$image4)) ?  config_item('base_url').$image4 : config_item('base_url').'images/rings/noimage.jpg';       
		
		$metalarray =  array('gold_ss'=>'Gold & Stainless Steel', 'ss'=>'Stainless Steel', 'gold'=>'Gold'); 
  ?>
  <br>
  <br>
  <br>
		<div class="detailcontainer " style="width:900px;margin-left:auto;margin-right:auto" id="detailcontainer">
		<div class="xboxcontent" style="height:auto" >
			<table  border="0">
				<div align="left">
					<tr>
						<td  valign="top" >
							<table border="0">
							<tr>
								<td >
									<table width="100%">
									<!--  <tr>
										  <td colspan="2">Reference: 114210 <br /></td>
									</tr> -->
									<tr>
										 <td valign="top">
											<b>Retail:</b> $<?php echo $details['price1'];?> <br />
											<b>Our Price: $<?php echo $details['price2'];?></b><br />
											<span class="bwid"><b>BWID:</b> <?php echo $details['model_number'];?></span>
										 </td>
									</tr>
								</table>
							<br />
							</td>
						</tr>
						<tr>
							<td valign="top">
							<div style="margin-left: 2em">
								<table>
									<tr><td> <span ><b>Size:</b></span> </td><td> <?php echo ucfirst($details['gender']);?></td></tr>
									<tr><td> <span><b>Case:</b></span> </td><td>  <?php echo $metalarray[$details['metal']];?></td></tr>
									<tr><td> <span><b>Band:</b></span> </td><td> <?php echo ucfirst($details['band']);?></td>
										<div class="floatr ">
											<div class="floatl blurleft"></div>
											<div class="floatl bluemiddle"><a href="#" onclick="addtocart(<?php echo "'".$addoption."'";?>,false,false,false,<?php echo $details['productID'];?>,<?php echo $details['price2'];?>)">add this to basket</a></div>
											<div class="floatl blueright"></div>
											<div class="clear"></div>
										</div>
									</tr>
									<tr><td> <span><b>Dial:</b></span> </td><td>  <?php echo ucfirst($details['dial']);?></td></tr>
									<tr><td> <!--<span class="standout">Hands:</span>--> </td><td> <?php echo ucfirst($details['hands']);?></td></tr>
									<tr><td> <!--<span class="standout">Markers:</span>--> </td><td><?php echo ucfirst($details['markers']);?></td></tr>
									<tr><td align=left> <span><b>Bezel:</b></span> </td><td align=left><?php echo ucfirst($details['bezel']);?></td></tr>
									<tr><td> <span><b>Features:</b></span> </td><td><?php echo ucfirst($details['features']);?></td></tr>
									<tr><td> <span><b>Crystal:</b></span> </td><td><?php echo ucfirst($details['crystal']);?></td></tr>
									<tr><td> <span><b>Movement:</b></span> </td><td><?php echo ucfirst($details['movement']);?></td></tr>
									<tr><td> <span><b>Calibre:</b></span> </td><td><?php echo ucfirst($details['calibre']);?></td></tr>
									<tr><td> <span><b>Width:</b></span> </td><td><?php echo ucfirst($details['width']);?></td></tr>
									<tr><td> <span><b>Height:</b></span> </td><td><?php echo ucfirst($details['height']);?></td></tr>
									<tr><td> <span><b>Thickness:</b></span> </td><td><?php echo ucfirst($details['thickness']);?></td></tr>
									<tr><td> <span><b>Lug Width:</b></span> </td><td><?php echo ucfirst($details['lugwidth']);?></td></tr>
									<tr><td> <span><b>Grade:</b></span> </td><td><?php echo ucfirst($details['style']);?></td></tr>
									<tr><td> <span><b>Box:</b></span> </td><td><?php echo ucfirst($details['box']);?></td></tr>
									<tr><td> <span><b>Papers:</b></span> </td><td><?php echo ucfirst($details['papers']);?></td></tr>
									<tr><td> <span><b>Warranty:</b></span> </td><td><?php echo ucfirst($details['warranty']);?> </td></tr>
								</table>
							</div>
						<br />
							<div class="ProdImage">
								<img src="<?=$image1?>"  alt="Image 1" style="margin: 0px 5px 5px 5px;border:1px solid #0B81A5;" height="291" width="450"><br />
								<img src="<?=$image2?>"  alt="Image 2" style="margin: 0px 5px 5px 5px;border:1px solid #0B81A5;" height="290" width="450">
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top" width="455">
				<div class="ProdImage">
					<img src="<?=$image3?>"  alt="Image 3" style="margin: 0px 5px 5px 5px;border:1px solid #0B81A5;" height="674" width="450"><br />
					<img src="<?=$image4?>"  alt="Image 4" style="margin: 0px 5px 5px 5px;border:1px solid #0B81A5;" height="157" width="450">
				</div>
				 <table width="100%">
					<tr>
						<td style="width:20px;"><img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg"></td>
						<td  class="infobox01 pad2" >Shipping Information </td>
				   </tr>
				   <tr>
				   <td colspan="2" class="brownback pad10">
						<p class="pad10">
							  i.      Fed-x <br>
							 ii.      The watch Will arrive in 2-4 Business Days.<!-- <font color="red"> Arrival depends on the specific diamond selected.</font> --> <br />

						   iii.      always offers Free Shipping via FedEX.
						  <a href="<?php echo config_item('base_url');?>site/page/freefedEx">
						<b><font color="red">FedEx Priority Overnight</b></font></a><br><br>
						</p>
					</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<!--END BUBBLE--> 	</div><b class="xb7"></b><b class="xb6"></b><b class="xb5"></b><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
	</div>

</div> 
				
   </div>
</div>
  