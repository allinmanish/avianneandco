<form method="POST" action="#">
<div class="floatl ">
  <div class="bodytop"></div>
  <div class="bodymid">
  		<div class="pad10">
  		  <div class="hr clear"></div>
			
		
			
			<?php
			 $this->load->helper('t');
	$ringhtml = '';
	$threestonehtml = '';
	$earringhtml = '';
	$diamondstudhtml = '';
	$pendenthtml = '';
	$threestonependanthtml = '';
	$subtotal = 0;
	$cchtml='';		
						 $this->load->model('adminmodel');
						$orderfrom='';
						$cinfo='';
						$customerinfo='';
						$paymentmethod='';
				$loosdiamondhtml='';		
				$loosdiamondhtml .= '<div style=" font-size:16px; text-align:center;" class="commonheader">Order Information</div>'; 
					  
			  
			    $itemcount=0;
			    foreach ($orderdetails as $item){
				  $paymentmethod = $item['paymentmethod'];
				  $itemcount++;
				switch ($item['addoption']){
					case 'addloosediamond':
						$details = getDetailsByLot($item['lot']);
						$loosdiamondhtml .= '	<div class="commonheader">Item '.$itemcount.' </div> 
												<div style="margin-top:10px">
														<div class="floatl w125px m2"><img src="'.config_item('base_url').'images\rings/90degree.jpg" width="55"></div>
														<div class="floatl w200px m2"><b>Diamond:</b><br>
																'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br>Lot #: '.$details['lot'].'<br>
																Quantity: '.$item['quantity'].'
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
														<div class="floatl w50px m2"><b>Size</b> </div>
																<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
														<div class="clear"></div> 
														
														 				
														<div class="floatr w85px m2 txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
												<div class="commonheader">Item '.$itemcount.' </div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Diamond</b>:<br>
																		'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br> Lot #: '.$details['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
																		<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Ring</b>:<br>
																		'.$ring['style'].' Ring in '.$ring['metal'].' <br> Stock #: '.$item['ringsetting'].'<br>
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
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
												<div class="commonheader">Item '.$itemcount.' </div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Diamond</b>:<br>
																		'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br> Lot #: '.$details['lot'].'<br>
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
																		<b>Sided Stones</b>:<br>
																		'.$sidestone1['cut'].' cut, '.$sidestone1['color'].' color, '.$sidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																		<br> Lot #: '.$sidestone1['lot'].' <br> Lot #: '.$sidestone2['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$sidestoneprice * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Ring</b>:<br>
																		'.$ring['style'].' Ring in '.$ring['metal'].' <br> Stock #: '.$item['ringsetting'].'<br>
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
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
												<div class="commonheader">Item '.$itemcount.' </div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w350px m2">
															<div>
																<div class="floatl w200px m2">
																		<b>Diamonds</b>:<br>
																		'.$esidestone1['cut'].' cut, '.$esidestone1['color'].' color, '.$esidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																		<br> Lot #: '.$esidestone1['lot'].' <br> Lot #: '.$esidestone2['lot'].'<br>
																		Quantity: '.$item['quantity'].'
																</div>
																<div class="floatl w50px m2"><b>Price</b> </div>
																<div class="floatl w85px m2 txtright"> $'.$esidestoneprice * $item['quantity'].' </div>
																<div class="clear"></div>
															</div>
															<div>
																<div class="floatl w200px m2">
																		<b>Ring</b>:<br>
																		'.$esettings['style'].' Earring in '.$esettings['metal'].' <br> Stock #: '.$item['earringsetting'].'<br>
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
															<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
												<div class="commonheader">Item '.$itemcount.' </div>
												<div style="margin-top:10px">
														<div class="floatl w125px m2"></div>
														<div class="floatl w200px m2"><b>Earring:</b><br>
																'.$details['collection'].'<br>
																Quantity: '.$item['quantity'].' 
														</div>
														<div class="floatl w50px m2"><b>Price</b> </div>
														<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
														<div class="floatl w50px m2"><b>Size</b> </div>
														<div class="floatl w85px m2 txtright"> '.$item['dsize'].' </div>
														
														<div class="clear"></div> 
														 				
														<div class="floatr w85px m2 txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
													<div class="commonheader">Order Information</div>
													<div style="margin-top:10px">
															<div class="floatl w125px m2"></div>
															<div class="floatl w350px m2">
																<div>
																	<div class="floatl w200px m2">
																			<b>Diamond</b>:<br>
																			'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br> Lot #: '.$details['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright"> $'.$details['price'] * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Ring</b>:<br>
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
																<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
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
													<div class="commonheader">Item '.$itemcount.' </div>
													<div style="margin-top:10px">
															<div class="floatl w125px m2"></div> 
															<div class="floatl w350px m2">
																<div>
																	<div class="floatl w200px m2">
																			<b>Diamond</b>:<br>
																			'.$details['cut'].' cut, '.$details['color'].' color, '.$details['clarity'].' clarity '.$details['shape'].' shape '.$details['carat'].'-Carat Diamond <br> Lot #: '.$details['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright">  $'.$details['price'] * $item['quantity'].'  </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Sided Stones</b>:<br>
																			'.$sidestone1['cut'].' cut, '.$sidestone1['color'].' color, '.$sidestone1['clarity'].' clarity Total Carat Weight: .50 <br>
																			<br> Lot #: '.$sidestone1['lot'].' <br> Lot #: '.$sidestone2['lot'].'<br>
																			Quantity: '.$item['quantity'].'
																	</div>
																	<div class="floatl w50px m2"><b>Price</b> </div>
																	<div class="floatl w85px m2 txtright"> $'.$sidestoneprice * $item['quantity'].' </div>
																	<div class="clear"></div>
																</div>
																<div>
																	<div class="floatl w200px m2">
																			<b>Ring</b>:<br>
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
																<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$'.number_format($item['totalprice'],',').'</b></div>
																<div class="floatr w50px m2"><b>Total</b></div>
																<div class="clear"></div>
															</div>
													</div>				
							
							';
							$subtotal = $subtotal + $item['totalprice'];
							break;
						
					default:
						break;
				
			    }
			}
				
			
			if(count($customer)>0)
			{
			  $customerinfo = '<div class="commonheader">Customer Information</div>
												<div style="margin-top:10px">
														
														<div class="floatl w350px m2">
															<b>Name: </b>'. $customer[0]['fname'].' '.$customer[0]['lname'].'													
																<div class="clear"></div>
															<b>Address: </b>'. $customer[0]['address'] .'
															    <div class="clear"></div>
															<b>Phone : </b>'. $customer[0]['phone'].'
															   <div class="clear"></div>
															<b>Email : </b>'. $customer[0]['email'].'
															</div>
														</div>
														<div class="clear"></div>';
			}
			if(count($shipping) > 0)
			{
				$cinfo = '<div class="commonheader">Credit Card Information</div>
												<div style="margin-top:10px">
														
														<div class="floatl w350px m2">
															<b>Credit Card Type: </b>'. $shipping[0]['creditcardname'] .'													
																<div class="clear"></div>
															<b>Credit Card Number: </b>'. $shipping[0]['creditcardno'] .'
															    <div class="clear"></div>
															<b>Exp Date : </b>'. $shipping[0]['cardexpirydate'].'
															</div>
														</div>
														<div class="clear"></div>';
			
				
				
				$orderfrom = '<div class="commonheader">Ship To:</div>
												<div style="margin-top:10px">
														
														<div class="floatl w350px m2">
															<b>Name: </b>'. $shipping[0]['billfname'].' '.$shipping[0]['billlname'].'													
																<div class="clear"></div>
															<b>Address: </b>'. $shipping[0]['billaddress'] .'
															    <div class="clear"></div>
															<b>City : </b>'. $shipping[0]['billcity'].'
															     <div class="clear"></div>
															<b>Zip Code : </b>'. $shipping[0]['billpostcode'].'
															     <div class="clear"></div>
															<b>Country : </b>'. $shipping[0]['billcountry'].'
																
															</div>
														</div>
														<div class="clear"></div>';
				}
				
				$orderto = '<div class="commonheader">Order To</div>
												<div style="margin-top:10px">
														
														<div class="floatl w350px m2">
															<b>Name: Fred Jones</b>													
																<div class="clear"></div>
															<b>Address: </b> New York
															    <div class="clear"></div>
															<b>City : </b> New York
															     <div class="clear"></div>
															<b>Zip Code : </b> 12500
															     <div class="clear"></div>
															<b>Country : </b> USA
																
															</div>
														</div>
														<div class="clear"></div>';
				
				
				if(strcmp(trim($paymentmethod),'creditcard')===0)
				{
				    $taxedamount = ($subtotal* 3)/100;
					
				    $cchtml ='<div>						
				<div class="floatr w85px  txtright" style="margin-right:15px;"><b>+ $ '.number_format($taxedamount,',').'</b></div>
				<div class="floatr w50px m2"><b>3% Tax </b></div>
				<div class="clear"></div>
			</div> 
			<div> <div class="hr"></div>						
				<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$ '.number_format($taxedamount+$subtotal,',').'</b></div>
				<div class="floatr w50px m2"><b>Total</b></div>
				<div class="clear"></div>
			</div>'; 

				}
				$subtotalhtml='<div class="hr"></div>
			<div>						
				<div class="floatr w85px  txtright" style="margin-right:15px;"><b>$ '.number_format($subtotal,',').'</b></div>
				<div class="floatr w50px m2"><b>Subtotal</b></div>
				<div class="clear"></div>
			</div>';
				echo $loosdiamondhtml.$ringhtml.$threestonehtml.$earringhtml.$diamondstudhtml.$pendenthtml.$threestonependanthtml.$subtotalhtml.$cchtml.$customerinfo.$cinfo.$orderfrom;
			
			
			
			
			?> 
			
			
			<div class="hr"></div>
			<div>
			  <div class="floatr w50px m2"><b></b></div>
				
				
			
			</div>
			
			<div class="dbr"></div>
			
			
  	</div>
  
  </div>
  <div class="clear"></div>
 <div class="bodybottom"></div>
</div>

</form>