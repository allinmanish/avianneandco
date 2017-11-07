<?php
	$shape = '';
	if(($this->session->userdata('shape'))) $shape =  $this->session->userdata('shape');
	$shapes = array();
	$shapes = explode('.' , strtoupper($shape));
?>

<table align="center">
<?php
if (preg_match('|/diamond/(\d+)|', $_SERVER['REQUEST_URI'], $match)) {
	$diamondID = $match[1];
	?>
	<p style="color:white;font-size:20pt;"> Please wait...</p>
	<script>
		function viewCurrentDiamond() {
			viewDiamondDetails(<?php echo $diamondID?>,'<?php echo $diamondID?>',false);
		}
		setTimeout('viewCurrentDiamond()', 10);
	</script>
<?php  } else { ?>
 <tr>
 	<td valign="top">
 		<div class="boxdiv w195px p10">
				   	  <h1>Narrow Your Results</h1>
				   	  <div class="dbr"></div>
				   	  <table>
				   	     <tr>
				   	     	<td colspan="2">Diamonds shape</td>
				   	     </tr>
				   	     <tr>
				   	     <td colspan="2">
				   	        <div class="searchdiamondsshape">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/round.jpg" onclick="toggleopacity(this, true)" alt="round" id="B"  <?php if(in_array('B', $shapes)) echo 'class="selected"'?> onmouseover="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/round.jpg\' class=\'w50px\'></td><td> Round Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/princess.jpg" onclick="toggleopacity(this, true)" alt="princess" id="PR" <?php if(in_array('PR', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/princess.jpg\' class=\'w50px\'></td><td>Princess Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/emerald.jpg" onclick="toggleopacity(this, true)" alt="emerald" id="E" <?php if(in_array('E', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/emerald.jpg\' class=\'w50px\'></td><td>Emerald Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <?php if($option != 'tothreestone'){?>
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/asscher.jpg" onclick="toggleopacity(this, true)" alt="asscher" id="AS" <?php if(in_array('AS', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/asscher.jpg\' class=\'w50px\'></td><td>Asscher Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/cushion.jpg" onclick="toggleopacity(this, true)" alt="cushion" id="C" <?php if(in_array('C', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/cushion.jpg\' class=\'w50px\'></td><td>Cushion Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	 <?   if(!$this->session->userdata('ispremium')){  ?>
						    <img src="<?php echo config_item('base_url');?>images/diamonds/radiant.jpg" onclick="toggleopacity(this, true)" alt="radiant" id="R" <?php if(in_array('R', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/radiant.jpg\' class=\'w50px\'></td><td>Radiant Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/heart.jpg" onclick="toggleopacity(this, true)" alt="heart" id="H" <?php if(in_array('H', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/heart.jpg\' class=\'w50px\'></td><td>Heart Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/marquise.jpg" onclick="toggleopacity(this, true)" alt="marquise" id="M" <?php if(in_array('M', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/marquise.jpg\' class=\'w50px\'></td><td>Marquise Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/oval.jpg" onclick="toggleopacity(this, true)" alt="oval" id="O" <?php if(in_array('O', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/oval.jpg\' class=\'w50px\'></td><td>Oval Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <img src="<?php echo config_item('base_url');?>images/diamonds/pear.jpg" onclick="toggleopacity(this, true)" alt="pear" id="P" <?php if(in_array('P', $shapes)) echo 'class="selected"'?>  onmousemove="Tip('<div class=\'tips\'><table><tr><td><img src=\'<?php echo config_item('base_url');?>/images/diamonds/pear.jpg\' class=\'w50px\'></td><td>Pear Diamonds</td></tr></table></div>', BALLOON, true, ABOVE, false, OFFSETX, 2, PADDING, 15)" onmouseout="UnTip()">
				   	        <?php } }?>
				   	        </div>
				   	     </td>
				   	     </tr>
				   	     <tr>
				   	     	<td colspan="2">Select your price range</td>
				   	     </tr>
				   	     <tr>
					   	     <td  colspan="2">
					   	     	<div id='pricerange' class='ui-slider-2'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>
								</div>
							 </td>
						</tr>
						<tr>
						   	  <td align="left"><small>Min</small><input type="text" value="<?php echo $minprice ?>" id="pricerange1" name="pricerange1" class="w50px price" onchange="modifyresult('searchminprice',this.value)"> </td>
						   	  <td align="right"><small>Max</small><input type="text" value="<?php echo $maxprice ?>" id="pricerange2" name="pricerange2" class="w50px price" onchange="modifyresult('searchmaxprice',this.value)"></td>
				   	    </tr>



				   	     <tr>
				   	     	<td colspan="2">Select your Carat</td>
				   	     </tr>
				   	     <tr>
					   	     <td colspan="2">
					   	     	<div id='carat' class='ui-slider-2'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>
								</div>
							 </td>
						</tr>
						<tr>
						   	  <td align="left"><small>Min</small><input type="text" value="<?php echo $caratminmax['min'];?>" id="caratmin" class="w50px" onchange="modifyresult('caratmin',this.value)"> </td>
						   	  <td align="right"><small>Max</small><input type="text" value="<?php echo $caratminmax['max'];?>" id="caratmax" class="w50px" onchange="modifyresult('caratmax',this.value)""></td>
				   	    </tr>



				   	    <tr>
				   	     	<td colspan="2"> <?php if(($this->session->userdata('ispremium'))){ echo '<div style="display:none;">';}else '<div>';?> Select your Cut<?php echo '</div>'; ?></td>
				   	     </tr>
				   	     <tr>
					   	     <td colspan="2">
					   	     <?php if(($this->session->userdata('ispremium'))){ echo '<div style="display:none;">';}else '<div>';?>
					   	     	<div id='cut' class='ui-slider-2'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>
								</div>
								<?php echo '</div>';?>
							 </td>
						</tr>

						<tr>

							  <td colspan="2">  <?php if(($this->session->userdata('ispremium'))){ echo '<div style="display:none;">';}else '<div>';?>
							  		<table style="font-size:8px;">
						   	  			<tr align="left">
						   	  				<td width="45px">Good</td>
						   	  				<td width="45px">Very<br>Good</td>
						   	  				<td width="45px">Ideal</td>
						   	  				<td width="45px">Premium</td>
						   	  			</tr>
						   	  		</table><?php echo '</div>'; ?>
							  </td>

				   	    </tr>

				   	    <tr>
				   	    		<td align="left"><input type="hidden" value="0" id="cutmin" class="w50px" onchange="modifyresult('cutmin',this.value)"></td>
						   	  	<td align="right"><input type="hidden" value="3" id="cutmax" class="w50px" onchange="modifyresult('cutmax',this.value)""></td>
				   	    </tr>
				   	    </tr>


				   	    <tr>
				   	     	<td colspan="2">Select your Color</td>
				   	     </tr>
				   	     <tr>

							  <td colspan="2">
							  		<table style="font-size:9px;">
						   	  			<tr align="left">
						   	  				<td width="27px">D</td>
						   	  				<td width="27px">E</td>
						   	  				<td width="27px">F</td>
						   	  				<td width="27px">G</td>
						   	  				<td width="27px">H</td>
						   	  				<td width="27px">I</td>
						   	  				<td width="27px">J</td>
						   	  			</tr>
						   	  		</table>
							  </td>

				   	    </tr>
				   	    <tr>
					   	     <td colspan="2">
					   	     	<div id='color' class='ui-slider-3'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>
								</div>
							 </td>
						</tr>

				   	    <tr>
				   	    		<td align="left"><input type="hidden" value="0" id="colormin" class="w50px" onchange="modifyresult('colormin',this.value)"></td>
						   	  	<td align="right"><input type="hidden" value="6" id="colormax" class="w50px" onchange="modifyresult('colormax',this.value)""></td>
				   	    </tr>
				   	    </tr>


				   	    <tr>
				   	     	<td colspan="2">Select Clarity</td>
				   	     </tr>
				   	     <tr>

							  <td colspan="2">
							  		<table style="font-size:9px;">
						   	  			<tr align="left">
						   	  				<td width="22px">IF</td>
						   	  				<td width="22px">VVS1</td>
						   	  				<td width="22px">VVS2</td>
						   	  				<td width="22px">VS1</td>
						   	  				<td width="22px">VS2</td>
						   	  				<td width="22px">SI1</td>
						   	  				<td width="22px">SI2</td>
						   	  				<td width="22px">I1</td>
						   	  			</tr>
						   	  		</table>
							  </td>

				   	    </tr>
				   	    <tr>
					   	     <td colspan="2">
					   	     	<div id='clarity' class='ui-slider-3'>
									<div class='ui-slider-handle'></div>
									<div class='ui-slider-handle' style="left: 168px;"></div>
								</div>
							 </td>
						</tr>

				   	    <tr>
				   	    		<td align="left"><input type="hidden" value="0" id="claritymin" class="w50px" onchange="modifyresult('claritymin',this.value)"></td>
						   	  	<td align="right"><input type="hidden" value="7" id="claritymax" class="w50px" onchange="modifyresult('claritymax',this.value)""></td>
				   	    </tr>
				   	    </tr>

					</table>


		</div>

		<script type="text/javascript">

			$(function(){
							if(document.getElementById('polish').checked == true){showhide('advancesearch', 'true');showhiderow('polish','polishrow', 'polis',0,4,8);}
							if(document.getElementById('symmetry').checked == true){showhide('advancesearch', 'true');showhiderow('symmetry','symmetryrow','symmet',0,4,12);}
							if(document.getElementById('depthx').checked == true){showhide('advancesearch', 'true');showhiderow('depthx','depthrow','depth',0,100,7);}
							if(document.getElementById('tablex').checked == true){showhide('advancesearch', 'true');showhiderow('tablex','tablerow','table',0,100,9);}
							if(document.getElementById('fluorescence').checked == true){showhide('advancesearch', 'true');showhiderow('fluorescence','flurorow','fluro',0,5,10);}
							/*if(document.getElementById('pricePerCarat').checked == true){showhide('advancesearch', 'true');showhiderow('pricePerCarat','pricePerCaratrow','pricePerCarat',<pp echo $minpricepercrt>,<pp echo $maxpricepercrt >,13);}*/

						});

		</script>
		<div class="boxdiv w195px p10">
				<div>
						<div class="floatl"><h1> Advance Search</h1></div>
						<div class="floatr">
							<img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" onclick="showhide('advancesearch', 'true')">
							<img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="minimize" style="display:none;" onclick="showhide('advancesearch', 'false')">
						</div>
						<div class="clear"></div>
						 <div class="dbr"></div>
				 </div>
				 <table width="180" class="tilecontainer" style="display:none;" id="advancesearch">
					 	<tr>
					 		<td valign="top">
					 			<input type="checkbox" name="polish" id="polish" onclick="showhiderow('polish','polishrow', 'polis',0,4,9)" ><label for="polish">Polish</label> <br>
								<input type="checkbox" name="symmetry" id="symmetry" onclick="showhiderow('symmetry','symmetryrow','symmet',0,4,13)"  ><label for="symmetry">Symmetry</label> <br>
								<input type="checkbox" name="depthx" id="depthx" onclick="showhiderow('depthx','depthrow','depth',0,100,8)" ><label for="depthx">Depth</label> <br>

					 		</td>
					 		<td valign="top">
					 			<input type="checkbox" name="tablex" id="tablex" onclick="showhiderow('tablex','tablerow','table',0,100,10)" ><label for="tablex">Table</label> <br>
					 			<input type="checkbox" name="fluorescence" id="fluorescence" onclick="showhiderow('fluorescence','flurorow','fluro',0,5,11)"   ><label for="fluorescence">Fluorescence</label> <br>

					 		</td>
					 	</tr>

					 	<tr><td colspan="2"></td> </tr>


					 	<tr id="polishrow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Polish</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='polis' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<tr>

										  <td colspan="2">
										  		<table style="font-size:8px;">
									   	  			<tr align="center">
									   	  				<td width="42px">EX</td>
									   	  				<td width="42px">VG</td>
									   	  				<td width="42px">GD</td>
									   	  				<td width="42px">F</td>
									   	  			</tr>
									   	  			<tr align="center">
									   	  				<td width="42px" class="polisbrow">ID</td>
									   	  				<td width="42px" class="polisbrow">EX</td>
									   	  				<td width="42px" class="polisbrow">VG</td>
									   	  				<td width="42px" class="polisbrow">GD</td>

									   	  			</tr>
									   	  		</table>
										  </td>

							   	    </tr>
							   	    <tr>
							   	    		<td align="left"><input type="hidden" maxlength="1" value="0" id="polismin" class="w50px" onchange="modifyresult('polismin',this.value)"></td>
									   	  	<td align="right"><input type="hidden" maxlength="1" value="4" id="polismax" class="w50px" onchange="modifyresult('polismax',this.value)"></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>

					 	<tr id="symmetryrow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Symmetry</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='symmet' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<tr>

										  <td colspan="2">
										  		<table style="font-size:8px;">
									   	  			<tr align="center">
									   	  				<td width="42px">EX</td>
									   	  				<td width="42px">VG</td>
									   	  				<td width="42px">GD</td>
									   	  				<td width="42px">F</td>
									   	  			</tr>
									   	  			<tr align="center">
									   	  				<td width="42px" class="polisbrow">ID</td>
									   	  				<td width="42px" class="polisbrow">EX</td>
									   	  				<td width="42px" class="polisbrow">VG</td>
									   	  				<td width="42px" class="polisbrow">GD</td>
									   	  			</tr>
									   	  		</table>
										  </td>

							   	    </tr>
							   	    <tr>
							   	    		<td align="left"><input type="hidden" maxlength="1" value="10" id="symmetmin" class="w50px" onchange="modifyresult('symmetmin',this.value)"></td>
									   	  	<td align="right"><input type="hidden" maxlength="1" value="100" id="symmetmax" class="w50px" onchange="modifyresult('symmetmax',this.value)"></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>


					 	<tr id="depthrow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Depth</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='depth' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<?  ?>
									<tr>
									   	  <td align="left"><small>Min%</small><input type="text" value="<?=$depthmin?>" id="depthmin" class="w50px" onchange="modifyresult('depthmin',this.value)"> </td>
									   	  <td align="right"><small>Max%</small><input type="text" value="<?=$depthmax?>" id="depthmax" class="w50px" onchange="modifyresult('depthmax',this.value)"></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>

					 	<tr id="tablerow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Table</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='tablerange' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<tr>
									   	  <td align="left"><small>Min%</small><input type="text" value="<?=$tablemin?>" id="tablemin" class="w50px" onchange="modifyresult('tablemin',this.value)"> </td>
									   	  <td align="right"><small>Max%</small><input type="text" value="<?=$tablemax?>" id="tablemax" class="w50px" onchange="modifyresult('tablemax',this.value)""></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>


					 	<tr id="flurorow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Fluroscence</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='fluro' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<tr>

										  <td colspan="2">
										  		<table style="font-size:8px;">
									   	  			<tr align="left">
									   	  				<td width="30px">NO</td>
									   	  				<td width="30px">F Blue</td>
									   	  				<td width="30px">Med Blue</td>
									   	  				<td width="30px">Moderate</td>
									   	  				<td width="30px">ST Blue</td>
									   	  				<td width="30px">VST Blue</td>
									   	  			</tr>
									   	  		</table>
										  </td>

							   	    </tr>
							   	    <tr>
							   	    		<td align="left"><input type="hidden" value="0" id="fluromin" class="w50px" onchange="modifyresult('fluromin',this.value)"></td>
									   	  	<td align="right"><input type="hidden" value="5" id="fluromax" class="w50px" onchange="modifyresult('fluromax',this.value)"></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>



					 	<tr id="pricePerCaratrow" style="display:none;">
					 		<td colspan="2">
						 		<table>
						 			<tr>
							   	     	<td colspan="2">Select Price/Carat</td>
							   	     </tr>
							   	     <tr>
								   	     <td colspan="2">
								   	     	<div id='pricePerCaratRange' class='ui-slider-2'>
												<div class='ui-slider-handle'></div>
												<div class='ui-slider-handle' style="left: 168px;"></div>
											</div>
										 </td>
									</tr>
									<tr>
									   	  <td align="left"><small>Min</small><input type="text" value="<?php echo $minpricepercrt ?>" id="pricePerCaratmin" class="w50px price" onchange="modifyresult('pricePerCaratmin',this.value)"> </td>
									   	  <td align="right"><small>Max</small><input type="text" value="<?php echo $maxpricepercrt ?>" id="pricePerCaratmax" class="w50px price" onchange="modifyresult('pricePerCaratmax',this.value)"></td>
							   	    </tr>
						 		</table>
						 	</td>
					 	</tr>


				 </table>
		</div>


 	</td>
 	<td valign="top">
 	   <table id="searchresult" style="display:none; "></table>
 	</td>
 </tr>



</table>
<?php } ?>