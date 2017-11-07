<?php 
	 $this->load->helper('t');
	$loosdiamondhtml = '';
	$ringhtml = '';
	$threestonehtml = '';
	$earringhtml = '';
	$diamondstudhtml = '';
	$pendenthtml = '';
	$threestonependanthtml = '';
	$subtotal = 0;
	//var_dump($myallitems); 
	$shippingArray = getShippinginfo($orderid, $customerid);
	//print_r($shippingArray);
	
?> 
 
<form method="POST" action="<?php echo config_item('base_url');?>shoppingbasket/billingandshipping">
<div class="floatl pad05 body">
  <div class="bodytop"></div>
  <div class="bodymid">
  		<div class="pad10">
  		
  			<div class="floatl pageheader">Order Review</div> 
			<div class="hr clear"></div>
			<div class="dbr"></div>
			<div class="floatl divheader">You choosed the following item(s):</div> 
			<div class="clear"></div>
			
			
			<?php
	//print_r($myallitems);
	
			 foreach ($myallitems as $item){
				switch ($item['addoption']){
					case 'addloosediamond':
						$details = getDetailsByLot($item['lot']);
						$loosdiamondhtml .= '	<div class="commonheader">Your Diamond </div> 
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w200px m2"><b>Your Diamond:</b><br>
																'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br>Lot #: '.$details['lot'].'<br>
																Quantity: '.$item['quantity'].'
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
														
														<div class="clear"></div> 
														 				
														<div class="floatr w85px m2 txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
														<div class="floatr w50px m2"><b>Total</b></div>
														<div class="clear"></div>
														 
												</div>
						
						';
						$subtotal = $subtotal + $item['totalprice'];
						break;
						
					case 'addtoring':
						$details = getDetailsByLot($item['lot']);
						$ring = getAllByStock($item['ringsetting']);
						
						$ringhtml .= ' 
												<div class="commonheader">Your Ring</div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Your Diamond</b>:<br>
																		'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond Lot #: '.$details['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Your Setting</b>:<br>
																		'.$ring['style'].' Ring in '.$ring['metal'].' Stock #: '.$item['ringsetting'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>					
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px txtright m2"> $'.$ring['price'] * $item['quantity'].' </div>
																<div class="floatl w50px m2"><b>Size</b> </div>
																<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
																<div class="clear"></div>
															</div>
														</div>
														<div class="clear"></div>
														
														<div>						
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
															<div class="floatr w50px m2"><b>Total</b></div>
															<div class="clear"></div>
														</div>
												</div>
						
						';
						$subtotal = $subtotal + $item['totalprice'];
						
						break;
						
					case 'tothreestone':
						$details = getDetailsByLot($item['lot']);
						$sidestone1 = getDetailsByLot($item['sidestone1']);
						$sidestone2 = getDetailsByLot($item['sidestone2']);
						$sidestoneprice = $sidestone1['price'] + $sidestone2['price'];
						$ring = getAllByStock($item['ringsetting']);						
						
						$threestonehtml .= '
												<div class="commonheader">Your Three-Stone Ring</div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Your Diamond</b>:<br>
																		'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond Lot #: '.$details['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright">  $'.$details['price'] * $item['quantity'].'  </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Your Sidedstones</b>:<br>
																		'.$sidestone1['cut'].' cut, '.$sidestone1['color'].' color, '.$sidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																		Lot #: '.$sidestone1['lot'].' Lot #: '.$sidestone2['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$sidestoneprice * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Your Setting</b>:<br>
																		'.$ring['style'].' Ring in '.$ring['metal'].' Stock #: '.$item['ringsetting'].' Size: '.$ring['finger_size'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>					
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px txtright m2">  $'.$ring['price'] * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
														</div>
														<div class="clear"></div>
														
														<div>						
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
															<div class="floatr w50px m2"><b>Total</b></div>
															<div class="clear"></div>
														</div>
												</div>				
						
						';
						$subtotal = $subtotal + $item['totalprice']; 
						break;
						
						case 'toearring':
							$esidestone1 = getDetailsByLot($item['sidestone1']);
							$esidestone2 = getDetailsByLot($item['sidestone2']); 
							$esidestoneprice = $esidestone1['price'] + $esidestone2['price'];
							$esettings = getEarringSettingsById($item['earringsetting']);
							
							$earringhtml = '
												<div class="commonheader">Your Earring</div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Your Diamonds</b>:<br>
																		'.$esidestone1['cut'].' cut, '.$esidestone1['color'].' color, '.$esidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																		Lot #: '.$esidestone1['lot'].' Lot #: '.$esidestone2['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$esidestoneprice * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Your Setting</b>:<br>
																		'.$esettings['style'].' Earring in '.$esettings['metal'].' Stock #: '.$item['earringsetting'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>					
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px txtright m2"> $'.$esettings['price'] * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
														</div>
														<div class="clear"></div>
														
														<div>						
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
															<div class="floatr w50px m2"><b>Total</b></div>
															<div class="clear"></div>
														</div>
												</div>							
							';
							$subtotal = $subtotal + $item['totalprice'];
							break;
							
						case 'addearringstud':
							$details = getAllByStock($item['studearring']);
							
							$diamondstudhtml .= '
												<div class="commonheader">Your Earring - Stud</div> 
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w200px m2"><b>Your Earring:</b><br>
																'.$details['collection'].'<br>
																Quantity: '.$item['quantity'].' 
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
														<div class="clear"></div> 
														 				
														<div class="floatr w85px m2 txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
														<div class="floatr w50px m2"><b>Total</b></div>
														<div class="clear"></div> 
												</div>
							
							';
							$subtotal = $subtotal + $item['totalprice'];
							
							break;
							
						case 'addpendantsetings':
							
							$details = getDetailsByLot($item['lot']);
							$setting = getPendentSettingsById($item['pendantsetting']);
							
							$pendenthtml .= ' 
													<div class="commonheader">Your Pendant</div>
													<div style="margin-top:10px">
															<div class="floatl w125px m2"></div>
															<div class="floatl w350px m2">
																<div>
																	<div class="floatl w200px m2">
																			<b>Your Diamond</b>:<br>
																			'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond Lot #: '.$details['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Your Setting</b>:<br>
																			'.$setting['description'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>					
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px txtright m2"> $'.$setting['price'] * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
															</div>
															<div class="clear"></div>
															
															<div>						
																<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
																<div class="floatr w50px m2"><b>Total</b></div>
																<div class="clear"></div>
															</div>
													</div>
							
							';
							$subtotal = $subtotal + $item['totalprice'];
							break;
							
						case 'addpendantsetings3stone':
							
								$details = getDetailsByLot($item['lot']);
								$sidestone1 = getDetailsByLot($item['sidestone1']);
								$sidestone2 = getDetailsByLot($item['sidestone2']);
								$sidestoneprice = $sidestone1['price'] + $sidestone2['price'];
								$setting = getPendentSettingsById($item['pendantsetting']);
							
								$threestonependanthtml .= '
													<div class="commonheader">Your Three-Stone Pendant</div>
													<div style="margin-top:10px">
															<div class="floatl w125px m2"></div>
															<div class="floatl w350px m2">
																<div>
																	<div class="floatl w200px m2">
																			<b>Your Diamond</b>:<br>
																			'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond Lot #: '.$details['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright">  $'.$details['price'] * $item['quantity'].'  </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Your Sidedstones</b>:<br>
																			'.$sidestone1['cut'].' cut, '.$sidestone1['color'].' color, '.$sidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																			Lot #: '.$sidestone1['lot'].' Lot #: '.$sidestone2['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright"> $'.$sidestoneprice * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Your Setting</b>:<br>
																			'.$setting['description'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>					
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px txtright m2">  $'.$setting['price'] * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
															</div>
															<div class="clear"></div>
															
															<div>						
																<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
																<div class="floatr w50px m2"><b>Total</b></div>
																<div class="clear"></div>
															</div>
													</div>				
							
							';
							$subtotal = $subtotal + $item['totalprice'];
							break;
				case 'addwatch':
						$details = getWatchByID($item['watchid']);
						$loosdiamondhtml .= '	<div class="commonheader">Your Watch </div> 
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w200px m2"><b>'.$details['productName'].'</b><br>
																'.$details['brand'].' Brand, '.$details['style'].' Style, '.$details['gender'].' Gender '.$details['band'].' Band '.$details['dial'].'-Dial <br>SKU #: '.$details['SKU'].'<br>
																Quantity: '.$item['quantity'].'
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$item['price'] * $item['quantity'].' </div>
														
														<div class="clear"></div> 
														 				
														<div class="floatr w85px m2 txtright" style="margin-right:15px;"><b>$'.$item['totalprice'].'</b></div>
														<div class="floatr w50px m2"><b>Total</b></div>
														<div class="clear"></div>
														 
												</div>
						
						';
						$subtotal = $subtotal + $item['totalprice'];
						break;
					default:
						break;
				
				
				?>
				
			<?php }}
				echo $loosdiamondhtml.$ringhtml.$threestonehtml.$earringhtml.$diamondstudhtml.$pendenthtml.$threestonependanthtml;
			?> 
			
			<div class="hr"></div>
			<div>						
				<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$<?php echo $subtotal;?></b></div>
				<div class="floatr w50px m2"><b>Subtotal</b></div>
				<div class="clear"></div>
				<div style="margin-right:15px;text-align:right"><b> 3% &nbsp;&nbsp; + &nbsp; $<?php echo round((($subtotal*3)/100),0);?></b></div> 
		
				<div style="margin-right:15px;text-align:right"><b>Shpping Charges&nbsp;&nbsp; + &nbsp; $<?php echo $shippingArray['shipping_amount'];?></b></div>
					<div class="clear">
			</div> <hr />
				<div style="margin-right:15px;text-align:right"><b> Total &nbsp;&nbsp;&nbsp; $<?php echo round($subtotal+(($subtotal*3)/100),0)+$shippingArray['shipping_amount'];?></b></div> 
			<!-- 	<div style="margin-right:15px;text-align:right"><b> Total &nbsp;&nbsp;&nbsp; $<?php echo round($subtotal+(($subtotal*3)/100),0);?></b></div> -->
			 <div class="clear"></div>
			
			</div>
			
			<div class="dbr"></div>
			<div>
				<input type="submit" name="confirmorder" value="Confirm Order" style="font-size:12px; font-weight:bold;" class="tbutton3">
			<!--	<input type="submit" name="cancelorder" value="Cancel" style="font-size:12px; font-weight:bold;" class="tbutton3"> -->
			</div>
			
  	</div>
  
  </div>
 <div class="bodybottom"></div>
</div>

</form>