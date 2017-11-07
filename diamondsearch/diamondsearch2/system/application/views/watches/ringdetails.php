
<div class="floatl pad05 body">
  		<div class="bodytop"></div>
	  	<div class="bodymid">
			<div class="topdiv">
				<?php echo $top_ads;?>
			</div>	   				 
			
			<div class="floatr divheader m10">Build Your Own Ring</div>
			<div style="height: 60px;"></div>
			 
				  			
  			<?php echo $tabhtml;?>
  			
  			<div class="clear"></div>
			
			<div class="floatl detailcontainer">
				<div class="dbr"></div>
				<div class="newtileheader">Petite Trellis Ring in Platinum</div>
				<div class="detaails">
					 
					<?php echo $details['description']?>
					
				</div>
				<div>
					<div class="floatl w100px">
										
							<div class="floatr blueright"></div>
							<div class="floatr bluemiddle"><a href="<?php echo config_item('base_url')?>engagement/addtobasket/<?php echo $lotno;?>/<?php echo $stockno;?>">Select this ring</a></div>
							<div class="floatr blurleft"></div>
							<div class="clear"></div>
					</div>
					<div class="floatl w100px">
							<div class="floatr brownright"></div>
		      				<div class="floatr brownmiddle"><a href="#">back to search</a></div>
		      				<div class="floatr brownleft"></div>
		      				<div class="clear"></div>
		      		</div>
		      		<div class="clear"></div>
				</div>
				
  	      				
      	      		
				<div class="floatr">
					Price : $<?php echo $details['price']?>
										
				</div>
				<div class="clear"></div>
				<div class="dbr"></div>
				<div>
					<div class="prints floatr">
						<div class="printbox floatl"></div>
						<div class="floatl">&nbsp;<a href="#" style="text-decoration:underline;">print</a></div>
						<div class="clear"></div>
					</div>
					<div class="emails floatr">
						<div class="emailbox floatl"></div>
						<div class="floatl">&nbsp;<a href="#" style="text-decoration:underline;">e-mail to a friend</a></div>
						<div class="clear"></div>
					</div>					 
					<div class="clear"></div>
				</div>
			</div>
					
			<div class="floatl detailcontainer">
				<div class="dbr"></div>
				<div class="floatl mainring">
					<img src="<?php echo config_item('base_url')?>images/rings<?php echo $details['big_image'] ?>" width="188px" height="169px" id="ringbigimage">
				</div>
				
				<div class="clear"></div>
				<div class="zoombox">
					<div class="floatl">
						<SELECT ID="vdiamonds" SIZE="1" class="vdiamonds" >
							<OPTION VALUE="1">View with Diamond</OPTION>
							<OPTION VALUE="2">View with Diamond</OPTION>
							<OPTION VALUE="3" SELECTED>View with Diamond</OPTION>
						</SELECT>
					</div>
					<div class="floatr">
							<div class="dbr floatl"></div>
							<div class="brownleft floatl"></div>
							<div class="brownmiddle floatl">&nbsp;<a href="#">Zoom in</a></div>
							<div class="brownright floatl"></div>
					</div>
					<div class="clear"></div>				
				</div>
			</div>
  			<div class="clear"></div>
				
				<div>
					<div class="floatl detailcontainers padl10">
						<div class="floatl">
						<img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" onclick="showhide('productdetails', 'true')">
							<img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="minimize" style="display:none;" onclick="showhide('productdetails', 'false')">
							</div>
						<div class="infobox01 floatl bigcontainerL">Product Details</div>
						<div class="clear"></div>
						
						<div id="productdetails" style="display:none;">
							<div class="divheader font12 padt10">Setting information</div>
							<table cellpadding="0" cellspacing="0" border="0">
								<tbody>
									<tr>
										<td width="120" class="brownback">Stock number</td>
										<td width="120" class="brownback">&nbsp;<?php echo $details['stock_number']; ?></td>
									</tr>
									<tr>
										<td width="120" class="brownback"><a href="#"><b><u>Metal</u></b></a></td>
										<td width="120" class="brownback">&nbsp;<?php echo $details['metal']; ?></td>
									</tr>
									<tr>
										<td width="120" class="brownback"><a href="#"><b><u>Width</u></b></a></td>
										<td width="120" class="brownback">&nbsp;<?php echo $details['width']; ?></td>
									</tr>
									<tr>
										<td width="120" class="brownback"><a href="#"><b><u>Prong metal</u></b></a></td>
										<td width="120" class="brownback">&nbsp;<?php echo $details['prong_metal']; ?></td>
									</tr>
									<tr>
										<td width="120" class="brownback">Available in sizes</td>
										<td width="120" class="brownback">&nbsp;4-8<br />&nbsp;<a href="#"><u>find your ring size</u></a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					
					<div class="floatl detailcontainers padl10">
						<div class="floatl">
						<img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg">
							
							</div>
						<div class="infobox01 floatl wid180">Shipping Information</div>
						<div class="clear"></div>
						<div class="brownback">
							This ring usally arrives in 2-4 business days. Arrival depends on the diamond selected.
							<br>
							<div style="border:1px #fff solid;"></div>
							Free shpping via<br>
							<b>FedEx Priority Overnight</b>
						</div>
						<div><br></div>
						<div class="clear"></div>
						<div class="floatl">
							<img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand1" onclick="showdescription('relatives', 'true','view','expand1','minimize1')">
							<img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="minimize1" style="display:none;" onclick="showdescription('relatives', 'false','view','expand1','minimize1')">
						</div>
						<div class="infobox01 floatl bigcontainerL">Related Items</div>
						<div class="clear"></div>
							<div id="view" class="padt10">
								<div class="floatl width75px textcenter">
									<img src="<?php echo config_item('base_url')?>images/ruman/engagement/bala_01.jpg">
									<a href="#"><u>View</u></a>
								</div>
								<div class="floatl width75px textcenter">
									<img src="<?php echo config_item('base_url')?>images/ruman/engagement/bala_02.jpg">
									<a href="#"><u>View</u></a>
								</div>
								<div class="floatl width75px textcenter">
									<img src="<?php echo config_item('base_url')?>images/ruman/engagement/bala_03.jpg">
									<a href="#"><u>View</u></a>
								</div>
								<div id="description" class="floatr padt10"><u onclick="showdescription('relatives', 'true','view','expand1','minimize1')" class="hand">View with description</u>&nbsp;&nbsp;<img src="<?php echo config_item('base_url')?>images/tamal/arrow_bg2.jpg"></div>
								<div class="clear"></div>
							</div>
							<div id="relatives" style="display:none;" class="padt10">
								<div class="floatl width75px textcenter">
									Demo description Demo description 
								</div>
								<div class="floatl width75px textcenter">
									Demo description Demo description 
								</div>
								<div class="floatl width75px textcenter">
									Demo description Demo description 
								</div>
								<div class="clear"></div>
							</div>
					</div>
					<div class="clear"></div>
				</div>
				<div><br /></div>
				<div class="border02"></div>
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
						<div class="floatl padt70">
								<div class="floatr blueright"></div>
								<div class="floatr bluemiddle"><a href="#">Select this ring</a></div>
								<div class="floatr blurleft"></div>
								<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div><br /></div>
				<div class="border02"></div>
				<div><br /></div>
	  			<div class="floatl detailcontainers padl10">
						<div class="floatl">
							<b>Note: </b> Prices and avilability are subject to change without notice<br />
							View our product policies for more information.<br />
							<br />
							<br />
							This setting available only with purchase of a diamond.
						</div>
						
						<div class="clear"></div>
				</div>
				<div class="clear"></div>
	  	</div>
	  	<div class="bodybottom"></div>
</div>