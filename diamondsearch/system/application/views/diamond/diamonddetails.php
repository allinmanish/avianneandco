<?php

	//print_r($details);


	$shape = '';
	//print_r($details);
	
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
				  		$shape = 'Asscher';
				  		break;
				  	case 'O':
				  		$shape = 'Oval';
				  		break;
				  	case 'M':
				  		$shape = 'Marquise';
				  		break;
				  	case 'P':
				  		$shape = 'Pear';
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
<style type="text/css">
    .diamonddetails{
	background:url(<?=config_item('base_url');?>/images/tamal/diamond/top_<?=$shape?>.jpg) no-repeat;
	width:190px;
	position:
}
.brownback img {width:800px}
</style>
<div class="floatl pad05">
  		<div class="bodytop_detail">

</div>
	  	<div class="bodymid_detail">
				<div class="topdiv">
					<?php echo $top_ads;?>
				</div>

	  			<h1 class="pageheader hr"><?php echo $pageheader; ?></h1>

	  			<div class="dbr"></div>

	  			<div>
			  			<div class="floatl tile0">
			  				<div id="diamonddetails" style="display:block;" class="diamonddetails">

			  					<table>
			  						<tr>
			  							<td></td>
			  							<td>
			  									<div class="sidedetail">
				  										<div class="depthpercent txtsmall">Depth%: <?php echo $details['Depth'] ?>%</div>
				  										<div class="tablepercent txtsmall">Table%: <?php echo $details['TablePercent'] ?>%</div>
					  									<div class="depth txtsmall">Depth</div>
					  									<div class="culet txtsmall">Culet:<?php echo $details['Culet'] ?></div>
			  									</div>
			  							</td>
			  						</tr>
			  					</table>

			  				</div>
			  			</div>
			  			<div class="floatl width285">
						      <p><strong><?php echo $details['carat'] ?>-Carat <?php echo $shape ?> Shape Diamond</strong> </p>
						      <p>This <i><?php echo $details['cut']?></i>-cut, <i><?php echo $details['color']?></i>-color, and <i><?php echo $details['clarity']?></i>-clarity diamond comes accompanied by a diamond grading report from the <?php echo $details['Cert']?>.</p>
						      <div class="dbr"></div>
						      <p><strong>Price:</strong> $<?php echo round($details['price']) ?></p>
						      <br /><br />
							  <?php if ($nexturl=="#"): ?>
                                 <?php if (strtolower($shape)=="round") :?>
                                 <form  action="/store/checkout/cart/addloose?product=6335&options[16575]=<?php echo $details['lot']?>&options[16579]=<?php echo $details['cut']?>&options[16578]=<?php echo $details['clarity']?>&options[16577]=<?php echo $details['color']?>&options[16576]=<?php echo $details['carat']?>" method="post" target="_top">
                                     <?php elseif((strtolower($shape)=="princess")):?>
                                 
                                 	    <form  action="/store/checkout/cart/addloose?product=6336&options[16580]=<?php echo $details['lot']?>&options[16584]=<?php echo $details['cut']?>&options[16583]=<?php echo $details['clarity']?>&options[16582]=<?php echo $details['color']?>&options[16581]=<?php echo $details['carat']?>" method="post" target="_top">
                                                                                
                                     <?php elseif((strtolower($shape)=="radiant")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6344&options[16620]=<?php echo $details['lot']?>&options[16624]=<?php echo $details['cut']?>&options[16623]=<?php echo $details['clarity']?>&options[16622]=<?php echo $details['color']?>&options[16621]=<?php echo $details['carat']?>" method="post" target="_top">
                                     
                                     <?php elseif((strtolower($shape)=="emerald")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6341&options[16605]=<?php echo $details['lot']?>&options[16609]=<?php echo $details['cut']?>&options[16608]=<?php echo $details['clarity']?>&options[16607]=<?php echo $details['color']?>&options[16606]=<?php echo $details['carat']?>" method="post" target="_top">   
                                        
                                      <?php elseif((strtolower($shape)=="asscher")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6342&options[16610]=<?php echo $details['lot']?>&options[16614]=<?php echo $details['cut']?>&options[16613]=<?php echo $details['clarity']?>&options[16612]=<?php echo $details['color']?>&options[16611]=<?php echo $details['carat']?>" method="post" target="_top">   
                                        
                                           
                                       <?php elseif((strtolower($shape)=="oval")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6338&options[166590]=<?php echo $details['lot']?>&options[16594]=<?php echo $details['cut']?>&options[16593]=<?php echo $details['clarity']?>&options[16592]=<?php echo $details['color']?>&options[16591]=<?php echo $details['carat']?>" method="post" target="_top">  
                                        
                                        <?php elseif((strtolower($shape)=="marquise")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6339&options[166595]=<?php echo $details['lot']?>&options[16599]=<?php echo $details['cut']?>&options[16598]=<?php echo $details['clarity']?>&options[16597]=<?php echo $details['color']?>&options[16596]=<?php echo $details['carat']?>" method="post" target="_top">  
                                        
                                         <?php elseif((strtolower($shape)=="Pear")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6337&options[166585]=<?php echo $details['lot']?>&options[16589]=<?php echo $details['cut']?>&options[16588]=<?php echo $details['clarity']?>&options[16587]=<?php echo $details['color']?>&options[16586]=<?php echo $details['carat']?>" method="post" target="_top"> 
                                         
                                         <?php elseif((strtolower($shape)=="heart")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6340&options[16600]=<?php echo $details['lot']?>&options[16604]=<?php echo $details['cut']?>&options[16603]=<?php echo $details['clarity']?>&options[16602]=<?php echo $details['color']?>&options[16601]=<?php echo $details['carat']?>" method="post" target="_top">  
                                        
                                         <?php elseif((strtolower($shape)=="cushion")):?>
                                 	    <form  action="/store/checkout/cart/addloose?product=6343&options[16615]=<?php echo $details['lot']?>&options[16619]=<?php echo $details['cut']?>&options[16618]=<?php echo $details['clarity']?>&options[16617]=<?php echo $details['color']?>&options[16616]=<?php echo $details['carat']?>" method="post" target="_top">  
                                          
                                           
                                                                               
                                 <?php endif; ?>
                              <?php else: ?>
                                 <form  action="<?php echo $nexturl ?>" method="post" >
                              <?php endif; ?>
                              <?php 
							  /* echo config_item('base_url');?>engagement/search/ring/true */ ?>
						    
						       		<table cellpadding="0" cellspacing="0" border="0" width="100%">
						       			<tbody><tr>
							      	      	<td width="130px">
							      	      		<div id="adddiamond" class="floatl textleft">
							      	      			<div class="floatl">
							      	      				<!--div class="floatl blurleft"></div-->
							      	      				<?php if (false) {?>
							      	      				<div class="floatl bluemiddle"><a href="<?php echo $nexturl;?>" onclick="<?php echo ($nexturl == '#') ? "$('#add_diamond_list').toggle()" : $onclickfunction ;?>">Add Diamond to Cart</a></div>-->
							      	      				<?php } ?>
														
							      	      				<?php /* option 58 holds diamond ID, product 58 is special product that fetches diamond price 
							      	      				<!--div class="floatl bluemiddle"><a target="_top" href="javascript:submit()">Add Diamond to Cart</a></div-->*/ ?>
														
														<button onclick="submit()" type="submit" class="form-buttonz"><span style="background:url(http://www.avianneandco.com/store/skin/frontend/default/blue/images/bkg_btn2.gif) repeat-x scroll 0 100%;">
                                                         <?php if ($nexturl=="#"): ?>
                                                        		Add to Shopping Bag
                                                         <?php else: ?>
                                                         		Add this Diamond
                                                         <?php endif; ?>
                                                        
                                                        </span></button>
														
							      	      				<?php /*<!--div class="floatl blueright"></div--> */ ?>
							      	      				<div class="clear"></div>
							      	      			</div>
							      	      			<div class="clear"></div>
							      	      			<?php echo $linkhtml;?>
							      	      		</div>
							      	      	</td>
							      	      	<td width="120" valign="top">
							      	      		<div class="floatl w125px">
						      	      				<div class="floatr brownright"></div>
						      	      				<div class="floatr brownmiddle"><a href="#" onclick="$.facebox.close()">back to search</a></div>
						      	      				<div class="floatr brownleft"></div>
						      	      				<div class="clear"></div>
							      	      		</div>
						      	      		</td>
					      	      		</tr></tbody>
				      	      		</table>
				      	      </form>
						      <!--<div>
						      	<div class="dimaond floatl">
						      		<div class="diamondbox floatl"></div>
									<div class="floatl">&nbsp;<a class="vsmall" href="#" style="text-decoration:underline;">find similar</a></div>
									<div class="clear"></div>
								</div>


								<div class="emails floatl">
									<div class="emailbox floatl"></div>
									<div class="floatl">&nbsp;<a class="vsmall" href="#" style="text-decoration:underline;">e-mail to a friend</a></div>
									<div class="clear"></div>
								</div>

								<div class="prints floatl">
									<div class="printbox floatl"></div>
									<div class="floatl">&nbsp;<a class="vsmall" href="#" style="text-decoration:underline;">print</a></div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>	-->

				      </div>
				      <div class="clear"></div>
				</div>

				<div class="dbr"></div>
				<div>
					<div class="floatl padl10">
						<div class="floatl">
						       <img src="<?php echo config_item('base_url')?>/images/tamal/minimize.jpg" id="expand" onclick="$('#productdetails').toggle(); if( this.src == '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg') this.src='<?php echo config_item('base_url')?>/images/tamal/expand.jpg' ;else  this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg';">
						</div>
						<div class="infobox01 floatl width450px">Product Details</div>
						<div class="clear"></div>

						<div id="productdetails" style="display:block; padding:0px;">
							<div class="infoheader padt10">Diamond information</div>
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tbody><tr>
										<td width="230" valign="top">
											<table cellpadding="0" cellspacing="0" border="0" width="90%">
												<tbody>
													<tr>
														<td width="120" class="brownback"><u>Lot #:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['lot'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Carat:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['carat'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Cut:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['cut'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Color:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['color'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Clarity:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['clarity'] ?></u></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td width="240" valign="top">
											<table cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td width="120" class="brownback"><u>Depth %:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Depth'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Table %:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['TablePercent'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Symmetry:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Sym'] ?></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Polish:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Polish'] ?></u></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Girdle:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Girdle'] ?></u></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Culet:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Culet'] ?></u></td>
													</tr>
													<tr>
														<td width="120" class="brownback"><u>Fluorescence:</u></td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Flour'] ?></u></td>
													</tr>
													<tr>
														<td width="120" class="brownback">Measurements:</td>
														<td width="120" class="brownback">&nbsp;<?php echo $details['Meas'] ?></u></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr></tbody>
								</table>
						</div>


			<?php if(trim($details['certimage'])!='') {
				  $chart = trim($details['certimage']);

				  $linktype = explode('.',$chart);
				  $imagetype=array('jpg','jpeg','png','gif','pjpeg');
				  $imgext = $linktype[(count($linktype)-1)];
				 // exit;
				  if(!in_array($imgext,$imagetype))  {
					$chart=@file_get_contents($chart);

				  }
				  else
				  {
					 $chart= '<img src="'.$chart.'"  />';
				  }
				  ?>
				<div class="dbr"></div>

						<div class="floatl">
							<div class="floatl">
								<img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg">
							</div>
							<div class="infobox01 floatl width800px">Diamond Certificate Information</div>
							<div class="clear"></div>
							<div class="smallheight"></div>
							<div class="brownback width800px" style="overflow:scroll"> <?=$chart?> &nbsp;</div>
							<div><br></div>
							<div class="clear"></div>

						</div>


					<? } ?>





						<div class="dbr"></div>
						<div class="floatl">
							<div class="floatl">
								<img src="<?php echo config_item('base_url')?>/images/ruman/engagement/truck.jpg">
							</div>
							<div class="infobox01 floatl width800px">Shipping Information</div>
							<div class="clear"></div>
							<div class="smallheight"></div>
							<div class="brownback width800px">
								This ring usally arrives in 2-4 business days. Arrival depends on the diamond selected.

								<div style="border:1px #fff solid;"></div>
															Free shpping via  <a href="<?php echo config_item('base_url');?>site/page/freefedEx">
															<b>FedEx Priority Overnight</b></a>
								</div>
							<div><br></div>
							<div class="clear"></div>

						</div>

					</div>
      	     		 <div class="clear"></div>
				</div>



	  	</div>
	  	<div class="bodybottom_detail"></div>
</div>
<div class="clear"></div>
