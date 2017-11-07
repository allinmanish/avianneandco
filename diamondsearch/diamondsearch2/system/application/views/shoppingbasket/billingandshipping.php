<?php 
	$this->load->helper('t');
	$countryoption = '';
	$countries = getAllCountry();
	
	if(isset($countries)){
		foreach ($countries as $country){
			if($country['iso'] == 'US')
				$countryoption .='<option value="'.$country['iso'].'" selected>'.$country['printable_name'].'</option>';
			else
				$countryoption .='<option value="'.$country['iso'].'">'.$country['printable_name'].'</option>';
		}	
	}
	
	$states = getStateLists();
	
	if(isset($states)){
		foreach ($states as $state){
			$stateoption .='<option value="'.$state['id'].'">'.$state['name'].'</option>';
		}	
	}	
?>
<?php //echo form_open(config_item('base_url').'shoppingbasket/billingandshipping')?>
<form method='post' name='billing' action="<?php echo config_item('base_url').'shoppingbasket/billingandshipping';?>" onsubmit="return validate_form();">

<div class="floatl pad05 body">
  <div class="bodytop"></div>
  <div class="bodymid">
  
  		<div class="pad10">
  		
  			<div class="floatl divheader">Billing & Shipping Information</div> 
			<div class="dbr clear"></div>
  			
			<div class="commonheader">Enter Credit Card Information</div>
			<div class="dbr"></div>
			
			<div class="lebelfield floatl">Card Type:</div>
			<div class="inputfield floatl">
					<select name="cardtype">
						<option value="visa">Visa</option>
						<option value="mastercard">Master Card</option>
						<option value="americanexpress">American Express</option>
					</select>
					<span class="required">*</span>
					<?php echo form_error('cardtype');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Number:</div>
			<div class="inputfield floatl">
					<input type="text" name="creditcardno" value="<?php echo $creditcardno;?>">
					<span class="required">*</span>
					<?php echo form_error('creditcardno');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Expiration Date:</div>
			<div class="inputfield floatl">
					<select name="expmonth">
						<option value="">--</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
					<select name="expyear">
						<option value="">--</option>
						<?php 
							$y = date(Y);
							for($i = 0; $i<15; $i++){
								echo '<option value="'.$y.'">'.$y.'</option>';
								$y++;
							}
							?> 
					</select>
					<span class="required">*</span>
					<?php echo form_error('expmonth');echo form_error('expyear');?>
			</div>
			<div class="clear"></div> 
			
			
			<div class="lebelfield floatl">CVV Code:</div>
			<div class="inputfield floatl">
					<input type="text" name="cvvcode" style="width:50px;" value="<?php echo $cvvcode;?>">
					<span class="required">*</span>
					<?php echo form_error('cvvcode');?>
					<div>
							<small>For your protection, we have instituted a new security measure advocated by major credit card 
							companies. Please enter your validation code (CVV) found on your Visa, MasterCard, Diner's Club, Discover, or 
							American Express card in the location indicated below. If you cannot find this code, please call customer service at 877-342-7464.</small> 
					</div>
			</div>
			<div class="clear"></div> 
			
			<div class="w200px m10 floatl" >
				<img src="<?php echo config_item('base_url');?>images/tamal/creditcard.jpg">
			</div>
  			<div class="inboxcolumn floatl m10">
  					<small><b>Visa, MasterCard, Discover, and Diner's Club:</b> On the back of the card in the top-right corner of the 
  					signature box. Enter the three-digit number following the credit card number.</small>
  			</div>
  			<div class="inboxcolumn floatl m10">
  					<small>A<b>merican Express:</b> On the front of the card. Enter the four-digit number on the right directly above 
  					the credit card number. On the Optima card, look on the left directly above the credit card number.</small>
  			</div>
  			<div class="clear"></div>
  			<br>
  			<div class="lebelfield floatl"></div>
			<div class="inputfield floatl"> 
					<small><b>Pay with multiple credit cards</b><br>
					If you would like to pay for your purchase with more than one credit card, please call customer service at 
					877-342-7464 for more information.</small> 
			</div>
			<div class="clear"></div>
			
			
			
			
			
			<div class="commonheader">Enter Your Billing Address</div>
			<div class="dbr"></div>
			<div class="lebelfield floatl"></div>
			<div class="inputfield floatl">
					<small>Enter the address where you receive bank and credit card statements.</small>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">First Name:</div>
			<div class="inputfield floatl">
					<input type="text" name="fname" value="<?php echo $fname;?>">
					<span class="required">*</span>
					<?php echo form_error('fname');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Last Name:</div>
			<div class="inputfield floatl">
					<input type="text" name="lname" value="<?php echo $lname;?>">
					<span class="required">*</span>
					<?php echo form_error('lname');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Company:</div>
			<div class="inputfield floatl">
					<input type="text" name="company">
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Address:</div>
			<div class="inputfield floatl">
					<input type="text" id="address1" name="address1" value="<?php echo $address1;?>"><span class="required">*</span><br>
					<input type="text" id="address2" name="address2" value="<?php echo $address2;?>">
					<?php echo form_error('address1');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Country:</div>
			<div class="inputfield floatl">
					<select id="country" name="country" onchange='displaystate(this);'>
						<option value="">Select Country</option>
						<?php echo $countryoption; ?>
					</select>
					<!-- <input type="text" name="country" value="<?php echo $countryx;?>"> -->
					<span class="required">*</span>
					<?php echo form_error('country');?>
			</div>
			<div class="clear"></div>

			<div class="lebelfield floatl">State:</div>
			<div class="inputfield floatl" id='citydiv' style='display:block'>
					<select name="state" id="state" >
						<?php echo $stateoption; ?>
					</select>
					<!-- <input type="text" name="country" value="<?php echo $countryx;?>"> -->
					<span class="required">*</span>
					<?php echo form_error('state');?>
			</div>
			<div class="inputfield floatl" id='citydivtxt' style='display:none' >
					<input type="text" name="statetxt" id="statetxt" value="<?php echo $cityx;?>">
					<span class="required">*</span>
					<?php echo form_error('statetxt');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">City:</div>
			<div class="inputfield floatl">
					<input type="text" name="city" id="city" value="<?php echo $city;?>">
					<span class="required">*</span>
					<?php echo form_error('city');?>
			</div>
			<div class="clear"></div>
			
			<div class="lebelfield floatl">Post Code:</div>
			<div class="inputfield floatl">
					<input type="text" name="postcode" id="postcode" style="width:80px" value="<?php echo $postcode;?>">
					<span class="required">*</span>
					<?php echo form_error('postcode');?>
			</div>
			<div class="clear"></div>
			
			
			<!--<div class="lebelfield floatl">Country:</div>
			<div class="inputfield floatl">
					<select name="country">
						<option value="">Select a Country</option>
						<?php echo $countryoption;?>
					</select>
			</div>
			<div class="clear"></div>-->
			
			
			<div class="lebelfield floatl">Phone Number<span class="required">*</span>:</div>
			<div class="inputfield floatl">
					<div class="w80px floatl m5">
						<input type="text" name="phonecode" style="width:80px;" value="<?php echo $phonecode;?>"> <small><i>(country code)</i> </small>
					</div>
					<div class="w125px floatl m5">
						<input type="text" name="phone" style="width:125px;" value="<?php echo $phone;?>"> <small><i>(phone number)</i> </small>
					</div>
					<div class="w80px floatl m5">
						<input type="text" name="extension" style="width:80px;" value="<?php echo $extension;?>"> <small><i>(extension)</i> </small>
					</div>
					<div class="clear"></div>
					<?php echo form_error('phone');?>
					
			</div>
			<div class="clear"></div>
			
			
			<div class="lebelfield floatl"></div>
			<div class="inputfield floatl">
					<small>Enter the phone number on file with your bank and credit card company.</small>
			</div>
			<div class="clear"></div>
			
			
			<div class="commonheader">Shipment Destination</div>
			<div>Orders cannot be shipped to a P.O. Box. Please provide a street address where a signature can be obtained for receipt.</div>
			<br>
			
			
			
			
			
			<div class="lebelfield floatl">Ship to billing address?:</div>
			<div class="inputfield floatl">
					<!-- <input type="radio" name="shipaddress" id="yes" value="yes" checked onclick="genericshowhide('receiverdetails', 'false'); clearreceiverdetails()"><label for="yes">Yes</label>
					<input type="radio" name="shipaddress" id="no" value="no" onclick="genericshowhide('receiverdetails', 'true')"><label for="no">No, ship to a different address.</label> -->
					<input type="radio" name="shipaddress" id="shipaddress" value="yes" checked onclick="genericshowhide('receiverdetails', 'false'); clearreceiverdetails();shippingDiv();"><label for="yes" >Yes</label>
					<input type="radio" name="shipaddress" id="shipaddressn" value="no" onclick="genericshowhide('receiverdetails', 'true');shippingDiv();"><label for="no">No, ship to a different address.</label>
			</div>			
			<div class="clear"></div>
			<div id="receiverdetails" style="display:none;">
									<div class="lebelfield floatl">Name:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvname" id="rcvname" value="<?php echo $rcvname;?>">
											<span class="required">*</span>
											<?php echo form_error('rcvname');?>
									</div>
									<div class="clear"></div>
									
									<!--<div class="lebelfield floatl">Last Name:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvlname" id="rcvlname">
									</div>
									<div class="clear"></div>-->
									
									<div class="lebelfield floatl">Company:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvcompany" id="rcvcompany">
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Address:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvaddress1" id="rcvaddress1" value="<?php echo $rcvaddress1;?>">
											<span class="required">*</span><br> 
											<input type="text" name="rcvaddress2" id="rcvaddress2" value="<?php echo $rcvaddress2;?>">
											<?php echo form_error('rcvaddress1');?>
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Country:</div>
									<div class="inputfield floatl">
											<select name="rcvcountry" id="rcvcountry" onchange='displaystate(this);'>
												<option value="">Select Country</option>
												<?php echo $countryoption; ?>
											</select>
											<!-- <input type="text" name="rcvcountry" id="rcvcountry" value="<?php echo $rcvcountry;?>"> -->
											<span class="required">*</span>
											<?php echo form_error('rcvcountry');?>
									</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">State:</div>
										<div class="inputfield floatl" id='rcvcitydiv' style='display:block'>
												<select name="rcvstate" >
													<?php echo $stateoption; ?>
												</select>
												<!-- <input type="text" name="country" value="<?php echo $countryx;?>"> -->
												<span class="required">*</span>
												<?php echo form_error('rcvstate');?>
										</div>
										<div class="inputfield floatl" id='rcvcitydivtxt' style='display:none' >
												<input type="text" name="rcvstatetxt" id="rcvstatetxt" value="<?php echo $cityx;?>">
												<span class="required">*</span>
												<?php echo form_error('rcvstatetxt');?>
										</div>
									<div class="clear"></div>

									<div class="lebelfield floatl">City:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvcity" id="rcvcity" value="<?php echo $rcvcity;?>">
											<span class="required">*</span>
											<?php echo form_error('rcvcity');?>
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">Post Code:</div>
									<div class="inputfield floatl">
											<input type="text" name="rcvpostcode" id="rcvpostcode" style="width:80px">
									</div>
									<div class="clear"></div>
									
									
									<!--<div class="lebelfield floatl">Country:</div>
									<div class="inputfield floatl">
											<select name="rcvcountry">
											<option value="">Select a Country</option>
											<?php echo $countryoption;?>
											</select>
									</div>
									<div class="clear"></div>-->
									
									
									<div class="lebelfield floatl">Phone Number<span class="required">*</span>:</div>
									<div class="inputfield floatl">
											<div class="w80px floatl m5">
												<input type="text" name="rcvphonecode" id="rcvphonecode" style="width:80px;" value="<?php echo $rcvphonecode;?>"> <small><i>(country code)</i> </small>
											</div>
											<div class="w125px floatl m5">
												<input type="text" name="rcvphone" id="rcvphone" style="width:125px;" value="<?php echo $rcvphone;?>"> <small><i>(phone number)</i> </small>
											</div>
											<div class="w80px floatl m5">
												<input type="text" name="rcvextension" id="rcvextension" style="width:80px;" value="<?php echo $rcvextension;?>"> <small><i>(extension)</i> </small>
											</div>
											<div class="clear"></div>
											<?php echo form_error('rcvphone');?>
											
									</div>
									<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
			<div class="commonheader">Choose Your Method of Shipping</div>
					<div class="dbr"></div>
					<div id='local' style='display:block;'>
						<center>
							<table width="450">
									<tr>
										<td width="250"><b>FedEx Priority Overnight</b>&reg;(free)</td>
										<td width="200"><input type="radio" name="shippingmethod" id="fedex" value="fedex" checked></td>
										
									</tr>
									 
							</table>
						</center>	
					</div>
					<div id='international' style='display:none;'>
						<center>
							<table width="450">
									<tr>
										<td width="250"><b>International Priority  Freight</b></td>
										<td width="200" align='left'><input type="radio" name="shippingmethod" id="INTERNATIONALPRIORITY" value="INTERNATIONAL_PRIORITY" onclick ="clearValues();"></td>
									</tr>
									<tr>
										<td width="250"><b>International Economy Freight</b></td>
										<td width="200"><input type="radio" name="shippingmethod" id="INTERNATIONALECONOMY" value="INTERNATIONAL_ECONOMY" onclick ="clearValues();"></td>
									</tr>
									<tr>
										<td width="250"><b>Estimated Shipping Cost</b></td>
										<td width="250"><input type="text" class='price' name="shipping_amoount" id="shipping_amoount" value="0" readonly size=10></td>
									</tr>
									 
							</table>
						</center>
						<div class="floatl" id='shipping_details' style='display:block;'>
						</div>
						<div class="floatr"> 
								<input type="button" name="calculate shipping" class="tbutton3" value="Estimate Shipping" style="font-weight:bold;" onclick='validate_shipping();'> 
						</div> 
					</div>
				</div> 
				<div class="dbr"></div>
			<div class="inboxcolumn floatl m2">
					<div class="commonheader">Enter Your Reference Code<small><i>(optional)</i> </small> </div>
					<div class="lebelfieldsmall floatl"><small>Reference Code:</small> </div>
					<div class="inputfieldsmall floatl">
							 <input type="text" name="referencecode" >
					</div>
					<div class="clear"></div> 
			</div>
			<div class="inboxcolumn floatl m2">
					<div class="commonheader">Gift Certificate<small><i>(optional)</i> </small> </div>
					<div class="lebelfieldsmall floatl"><small>Gift Certificate Number:</small> </div>
					<div class="inputfieldsmall floatl">
							 <input type="text" name="giftcertificate" >
					</div>
					<div class="clear"></div> 
					<br>
					<div>View your <a href="#">gift certificate balance</a> , or read our gift certificate <a href="#">policies</a>.</div>
			
			</div>
			<div class="clear"></div>
			
			
			<div class="commonheader">International Shipping Policies:</div>
			<div class="m5">
					<input type="checkbox" name="intshipping" id="intshipping"><label for="intshipping">I understand and agree to the following:</label>
					
					<div class="pad10">
					 	<ul class="arrowlist">
					 		<li>All customs fees, import duties, taxes and other charges are my responsibility.</li>
					 		<li>In the case of any loss or damage to my order, it is my responsibility to work with the insurance company to file a claim. (Insurance company name and contact details will be provided in the order confirmation email.)</li>
					 		<li>My order is an international shipment subject to Directloose diamonds International Shipping Policies.</li>
					 	</ul>
					</div>
				   <?php echo form_error('intshipping');?>
					
			</div>
			<!-- <div class="floatr">
					<input type="button" name="shipping" class="tbutton3" value="Estimate Shipping" style="font-weight:bold;" onclick='validate_shipping();'>
			</div> -->
			
			
			
			<div class="commonheader">How Did You Hear About Directloosediamonds.com? <small><i>(optional)</i> </small> </div>
			<div class="dbr"></div>
			
			<select name="howdidyouknow">
					<option value="">Select</option>
					<option value="Referal from freind and family member">Referal from freind and family member</option>
					<option value="E-mail">E-mail</option>
					<option value="MSN">MSN</option>
					<option value="Newspaper, Magazine or TV coverage">Newspaper, Magazine or TV coverage</option>
					<option value="Yahoo">Yahoo</option>
					<option value="Google">Google</option>
					<option value="Other">Other</option>
			</select>
			
			<div class="floatr">
					<input type="submit" name="continuebillingandshipping" class="tbutton3" value="Continue" style="font-weight:bold;">
			</div>
			<div class=" clear"></div> 
  		
  		</div>
  
  </div>
 <div class="bodybottom"></div>
</div>
<?php //echo form_close();?>
</form>