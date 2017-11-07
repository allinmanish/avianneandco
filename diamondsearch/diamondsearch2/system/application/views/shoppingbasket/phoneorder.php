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
?>

<div class="floatl pad05 body">
  <div class="bodytop"></div>
  <div class="bodymid">
  
  
  
  
  	<div class="pad10">
  	
  	         <div class="divheader" style="text-align:center;background:#81ae33;color:#FFFFFF;padding:5px 2px"><strong>Order Submitted Successfully</strong></div> 
  			<div class="floatl divheader">Call for One-on-One Guidence</div> 
			<div class="dbr clear"></div>
			
			<p>
				When you are ready to make a purchase, contact our customer service staff at 877-342-7464. Please be ready to:
			</p>
			
			<p class="pad10">
				1. Give this order number:<span class="bgblue"><?php echo $orderid;?></span> <br><br>
				2. Provide us with the stock numbers of the products you wish to purchase (listed below), your credit card number, and shipping address.
			</p>
			<br>
			<P>Please note that to complete this purchase you must make payment through Customer Service. Orders are not placed on hold and are subject to sale until payment has been completed.</P>
  			
			<?php foreach ($myallitems as $item){
				switch ($item['addoption']){
					case 'addloosediamond':
						$details = getDetailsByLot($item['lot']);
						$loosdiamondhtml .= '	<div class="commonheader">Your Diamond </div> 
												<div style="margin-top:10px">
														<div class="floatl w125px m2"><img src="'.config_item('base_url').'images\rings/90degree.jpg" width="55"></div>
														<div class="floatl w200px m2"><b>Your Diamond:</b><br>
																'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br>Lot #: '.$details['lot'].'<br>
																Quantity: '.$item['quantity'].'
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
														<div class="floatl w50px m2"><b>Size</b> </div>
																<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
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
																<div class="floatl w50px m2"><b>Size</b> </div>
																<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
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
																		'.$ring['style'].' Ring in '.$ring['metal'].' Stock #: '.$item['ringsetting'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>					
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px txtright m2">  $'.$ring['price'] * $item['quantity'].' </div>
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
														<div class="floatl w50px m2"><b>Size</b> </div>
														<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
														
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
			</div>
			
			 

  	</div>
  	<div class="dbr"></div>
  
  </div>
 <div class="bodybottom"></div>
</div>