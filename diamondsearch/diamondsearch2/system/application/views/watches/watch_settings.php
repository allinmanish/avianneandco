 <?php 
 	$shape = '';
	switch ($details['shape']){
				  	case 'B':
				  		$shape = 'Round';
				  		break;
				  	case 'PR':
				  		$shape = 'Princess';
				  		break;
				  	case 'R':
				  		$shape = 'Radiant';
				  		break;
				  	case 'E':
				  		$shape = 'Emerald';
				  		break;
				  	case 'AS':
				  		$shape = 'Ascher';
				  		break;
				  	case 'O':
				  		$shape = 'Oval';
				  		break;
				  	case 'M':
				  		$shape = 'Marquise';
				  		break;
				  	case 'P':
				  		$shape = 'Pear shape';
				  		break;
				  	case 'H':
				  		$shape = 'Heart';
				  		break;
				  	case 'C':
				  		$shape = 'Cushion';
				  		break;								  	
				  	default:
				  		$shape = $details['shape'];
				  		break;
				  }			
 		
 ?>
<div class="pad05 ringbody">
  		
	  	<div>
			<div class="topdiv">
				<?php echo $top_ads;?>
			</div>	   				 
			
			<div class="floatr divheader m10"><!--<img src="<?php echo config_item('base_url');?>/images/ruman/engagement/build_you_own_ring.jpg">--></div>
				<div class="dbr"></div>
				<div class="dbr"></div>
				<div class="dbr"></div>	  			
	  			
	  			<div class="width520px left235px">		  			
		  			<?php if(isset($tabhtml)) echo $tabhtml;?>
	  			</div>
  			</div>

			
			
			<div class="ringcontainer ringbody">
				<div class="ringleft tile0 floatl minh500px">
				<?php if($details){?>
					<div>
						<h5 class="textcenter"><font class="bgblue">Your Diamond</font></h4>
					</div>
					<div>
						<table width="220" cellpadding="0" cellspacing="0" style="border: 1px solid #e9e9e9;">
							<tbody>
								<tr>
									<td width="60">
									    <img src="<?php echo config_item('base_url')?>images/diamonds/<?php echo strtolower($shape);?>.jpg">
									</td>
									<td width="160">
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tbody>
												<tr class=" font9px"><td align="right">Lot #: </td><td align="left">&nbsp;<?php echo $details['lot'] ;?></td></tr>
												<tr class=" font9px"><td align="right">Shape : </td><td align="left">&nbsp;<?php echo $shape; ?></td></tr>
												<tr class=" font9px"><td align="right">Carat : </td><td align="left">&nbsp;<?php echo $details['carat']; ?></td></tr>
												<tr class=" font9px"><td align="right">Price : </td><td align="left">&nbsp;<?php echo $details['price']; ?></td></tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						
						</table>
						
					</div>
					<div class="dbr"></div>
					<?php }?> 
					
					
					<div class="textcenter font12 botomborder"><font class="bgblue1">Select Your Design:</font></div>
					<div class="dbr"></div>
					<div class="bigcontainerL marginauto">
					<form name="design">
					<?php $i=1;
					foreach($brand AS $brandArr){?>
						<div  class="floatl rolex">
							<input name="marktchk" id = "<?php echo $brandArr['brand'];?>" type="checkbox" onclick="getwatchresults()" checked="checked" value = "<?php echo $brandArr['brand'];?>">
							 <label for="marktchk" align='bottom'><a class="hiddendiv" ></a></label> 
						</div>	
					<?php						
						$brandstart = '<div class="clear"></div>';
						$brandheading .= '<div class="floatl w60px textcenter"><label for="marktchk">'.$brandArr['brand'].'</label> </div>';
						$brandend = '<div class="clear"></div>'; 
						if($i==count($brand)){
							echo $brandstart.$brandheading.$brandend;
							$brandheading = '';
							break;
						}
						if($i%2==0){
							echo $brandstart.$brandheading.$brandend;
							$brandheading = '';
						}
						$i++;
					}?> 
						</form>
						<!-- <div class="clear"></div>
						<div class="floatl w60px textcenter"><label for="daussichk">&nbsp;</label></div>
						<div class="floatl w60px textcenter"><label for="antiquechk">&nbsp;</label></div> -->
						
						<div class="clear"></div>
						
					</div>
					
					
					
					<div class="dbr"></div>
					<div class="textcenter font12 botomborder"><font class="bgblue1">Select setting style(s)</font></div>
					<div class="dbr"></div>
					<div class="bigcontainerL marginauto">
					
						<div  class="floatl rolex">
							<input id="pavsechk" type="checkbox" onclick="getwatchresults()" checked >
							<label for="pavsechk"><a class="hiddendiv" ></a></label>
						</div>						
						<div id="solitaire" class="floatl rolex">
							<input id="solitairechk" type="checkbox" onclick="getwatchresults()" checked>
							<label for="solitairechk"><a class="hiddendiv" ></a></label>
						</div>
<!-- 						<div id="sidestones" class="floatl sidestones">
							<input id="sidestoneschk" type="checkbox" onclick="getringresults()" checked>
							<label for="sidestoneschk"><a class="hiddendiv" ></a></label>
						</div>
 -->					
						
						<div class="clear"></div>
						<div class="floatl w60px textcenter"><label for="pavsechk">Preowned</label> </div>
						<div class="floatl w60px textcenter"><label for="solitairechk">New</label></div>
<!-- 						<div class="floatl w60px textcenter"><label for="sidestoneschk">Sidestone</label></div>
 -->						<div class="clear"></div>
						
						
						
						
						<!-- <div  class="floatl Threestone">
							<input id="threestonechk" type="checkbox" onclick="getringresults()" checked >
							<label for="threestonechk"><a class="hiddendiv" ></a></label>
						</div>						
						<div id="halo" class="floatl halo">
							<input id="halochk" type="checkbox" onclick="getringresults()" checked>
							<label for="halochk"><a class="hiddendiv" ></a></label>
						</div>
						<div id="mathing" class="floatl mathing">
							<input id="mathingchk" type="checkbox" onclick="getringresults()" checked="checked">
							<label for="mathingchk"><a class="hiddendiv" ></a></label>
						</div>
					
						
						<div class="clear"></div>
						<div class="floatl w60px textcenter"><label for="threestonechk">Three Stone Rings</label> </div>
						<div class="floatl w60px textcenter"><label for="halochk">Halo</label></div>
						<div class="floatl w60px textcenter"><label for="mathingchk">Matching Sets</label></div>
						<div class="clear"></div>
						
						<div  class="floatl halo">
							<input id="anniversarychk" type="checkbox" onclick="getringresults()" checked >
							<label for="anniversarychk"><a class="hiddendiv" ></a></label>
						</div>	
						<div class="clear"></div>
						<div class="floatl w60px textcenter"><label for="anniversarychk">Anniversary</label> </div>
						<div class="clear"></div> -->
					</div>
					
					
					<br>
					<div class="textcenter font12 botomborder"><font class="bgblue1">Select Metal Type(s)</font></div>
					<div><br></div>
					<div class="bigcontainerL1 marginauto">	
										
						<div  class="floatl rolexpatinum" style='width:60px'>
							<input id="patinumchk" type="checkbox"  onclick="getwatchresults()" checked >
							<label for="patinumchk"><a class="hiddendiv" ></a></label>
						</div>
						<div id="goldss" class="floatl rolexgoldring" style='width:60px'>
							<input id="goldsschk" type="checkbox"  onclick="getwatchresults()">
							<label for="goldsschk"><a class="hiddendiv" ></a></label>
						</div>
						<div id="gold" class="floatl rolexgoldring" style='width:60px'>
							<input id="goldchk" type="checkbox"  onclick="getwatchresults()">
							<label for="goldchk"><a class="hiddendiv" ></a></label>
						</div>
						<!-- <div id="whitegold" class="floatl whitegold">
							<input id="whitegoldchk" type="checkbox"  onclick="getringresults()" checked>
							<label for="whitegoldchk"><a class="hiddendiv" ></a></label>
						</div> -->
						
						<div class="clear"></div>
						<div class="floatl w60px textcenter"><label for="patinumchk">Stainless steel</label></div>
						<div class="floatl w60px textcenter"><label for="goldsschk">Stainless Steel and Gold</label></div>
						<div class="floatl w60px textcenter"><label for="goldchk">Gold</label></div>

<!-- 						<div class="floatl w60px textcenter"><label for="whitegoldchk">White Gold</label></div>
 -->						<div class="clear"></div>
					</div>
					
					<br>
					<div class="textcenter font12 botomborder"><font class="bgblue1">Setting Price</font></div>
					<br>
					<div class="bigcontainerL marginauto">
						<table>
				 			<tr>
					   	     <td colspan="2">
					   	     	<div id='pricerange' class='ui-slider-2'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>	
								</div>
							 </td>
							</tr>
							<tr>
							   	  <td align="left"><small>Min</small><input type="text" value="<?php echo $minprice;?>" id="pricerange1" name="pricerange1" class="w50px price"> </td>
							   	  <td align="right"><small>Max</small><input type="text" value="<?php echo $maxprice;?>" id="pricerange2" name="pricerange2" class="w50px price"></td>
					   	    </tr>
				 		</table>
						
					</div>
					
					<br>
					 
<!-- 					<div class="bigcontainerL marginauto">
						Select Shape <select name="shape" id="ringshape" onchange="getringresults()">
							<option value="all" selected>All Shape</option>
						    <option value="Round">Round</option>
						    <option value="Princess">Princess</option>
						    <option value="Radiant">Radiant</option>
						    <option value="Emerald">Emerald</option>
						    <option value="Ascher">Ascher</option>
						    <option value="Oval">Oval</option>
						    <option value="Marquise">Marquise</option>
						    <option value="Pear shape">Pear shape</option>
						    <option value="Heart">Heart</option>
						    <option value="Cushion">Cushion</option>
						     
				  	 
				 
						 </select>
						
					</div>
 -->					
					
					
				</div> <!--end  roundcorner-->
				
				<input type="hidden" id="hlot" name="hlot" value="<?php echo isset($lot) ? $lot : 0 ;?>"/>
				
				<div class="floatl pad10 width650px">
					  <div id="searchresult"></div> 
				</div>
				<div class="clear"></div>
			
			</div> 
				
				
	  	</div>
	  	
</div>