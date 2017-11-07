<div class="floatl pad05 body">
  		<div class="bodytop"></div>
	  	<div class="bodymid">
	  	
	  		<div class="topdiv">
				<p class="txtcenter">Build Your Own Diamond Jewelry</p>
				<?php echo $top_ads;?>
			</div>
			
			<div class="divheader txtright pad10">
					Build Your Own Three-Stone Ring
			</div>
			<div class="dbr"></div>
			
			<div>					
					<?php echo $tabhtml;?>
			</div>
			
			<div>			  			
			  		  <div class="floatl bigcontainerR">
						      <p class="fakaline pad10">Directloose diamonds make it easy to design your own three stone ring. Follow the three-step process to find the perfect diamond and settings.</p>						     						     
				      </div>
				      <div class="floatl bigcontainerL">
			  				<img src="<?php echo config_item('base_url')?>/images/tamal/threestonering_bg.jpg">
			  		  </div>			  		  
				      <div class="clear"></div>
				      
				      <div>			  		  		
				  		  <table width="95%" class="bgblue" cellpadding="5px" cellspacing="5px" style="font-size:10px;">
					  		  	<tr>
					  		  		<td>1.Pick a Center Diamond</td>
					  		  		<td>2.Select Your Sidestones</td>
					  		  		<td>3.Choose Your Settings</td>
					  		  		<td>4.Add To Basket</td>
					  		  	</tr>					  		  	
				  		  </table>
				      </div>
			</div>
			
			<div class="hr"></div>
			 
			
			<form action="<?php echo config_item('base_url');?>diamonds/search/true/false/tothreestone" method="POST">
				      <div>
				            <div class="dbr"></div>
				            <p class="newtileheader fakaline pad10">Select one or more diamond shapes</p>
						    <p class="pad10"><a href="<?php echo config_item('base_url');?>education/jewelry/shape" class="underline">Learn About Shapes</a></p> 
				            
				            <br>
				            <div>
					            <div class="floatl  w50px vsmall txtcenter"><label for="B">Round<img src="<?php echo config_item('base_url');?>images/diamonds/round.jpg" alt="round" /></label><input type="checkbox" value="round" name="diamondshape" id="B" onclick="toggleopacity(this, false)"></div>
					      		<!--<div class="floatl  w50px vsmall txtcenter"><label for="H">Heart<img src="<?php echo config_item('base_url');?>images/diamonds/heart.jpg" alt="heart" /></label><input type="checkbox" value="heart" name="diamondshape" id="H" onclick="toggleopacity(this, false)"></div>-->
					      		<div class="floatl  w50px vsmall txtcenter"><label for="E">Emerald<img src="<?php echo config_item('base_url');?>images/diamonds/emerald.jpg" alt="emerald" /></label><input type="checkbox" value="emerald" name="diamondshape" id="E" onclick="toggleopacity(this, false)"></div>
					      		<div class="floatl  w50px vsmall txtcenter"><label for="PR">Princess<img src="<?php echo config_item('base_url');?>images/diamonds/princess.jpg" alt="princess" /></label><input type="checkbox" value="princess" name="diamondshape" id="PR" onclick="toggleopacity(this, false)"></div>
					      		<!--<div class="floatl  w50px vsmall txtcenter"><label for="R">Radiant<img src="<?php echo config_item('base_url');?>images/diamonds/radiant.jpg" alt="radiant" /></label><input type="checkbox" value="radiant" name="diamondshape" id="R" onclick="toggleopacity(this, false)"></div>
					      		<div class="floatl  w50px vsmall txtcenter"><label for="O">Oval<img src="<?php echo config_item('base_url');?>images/diamonds/oval.jpg" alt="oval" /></label><input type="checkbox" value="oval" name="diamondshape" id="O" onclick="toggleopacity(this, false)"></div>
					      		<div class="floatl  w50px vsmall txtcenter"><label for="M">Marquise<img src="<?php echo config_item('base_url');?>images/diamonds/marquise.jpg" alt="marquise" /></label><input type="checkbox" value="marquise" name="diamondshape" id="M" onclick="toggleopacity(this, false)"></div>-->
					      		<div class="floatl  w50px vsmall txtcenter"><label for="AS">Asscher<img src="<?php echo config_item('base_url');?>images/diamonds/asscher.jpg" alt="asscher" /></label><input type="checkbox" value="asscher" name="diamondshape" id="AS" onclick="toggleopacity(this, false)"></div>      		
					      		<div class="floatl  w50px vsmall txtcenter"><label for="P">Pear<img src="<?php echo config_item('base_url');?>images/diamonds/pear.jpg" alt="pear" /></label><input type="checkbox" value="pear" name="diamondshape" id="P" onclick="toggleopacity(this, false)"></div>      		
					      		<div class="floatl  w50px vsmall txtcenter"><label for="C">Cushion<img src="<?php echo config_item('base_url');?>images/diamonds/cushion.jpg" alt="cushion" /></label><input type="checkbox" value="cushion" name="diamondshape" id="C" onclick="toggleopacity(this, false)"></div>
					      		<div class="clear"></div>
				      		</div>  		
				      </div>
				      
				       <div class="hr"></div>
				       		      			
				      <div>
				            <div class="dbr"></div>
				            <p class="newtileheader fakaline pad10">Select Price range(Optional)</p>
				            <br>
				            <span class="floatl">
Price From <input type="text" name="minprice" id='minprice' value="<?php echo number_format($minprice,',');?>" class="w100px price" maxlength="9" onchange="checkMinMaxPrice(this,'maxprice',<?php echo $minprice, ",",$maxprice.",'min'";?>)">-to-<input type="text" name="maxprice" id="maxprice" value="<?php echo number_format($maxprice,','); ?>" class="w100px price" maxlength="9" onchange="checkMinMaxPrice(this,'minprice',<?php echo $minprice, ",",$maxprice.",'max'";?>)">
				            </span>
				            <span class="floatl">
				            	<span> we have over <b><?=number_format(round($totaldiamond),',')?> round diamonds ranging</b> in price from $<?=number_format(round($minprice),',')?> to $<?=number_format(round($maxprice),',')?></span>
				            </span>
				             <div class="clear"></div>
				      </div>
				      
		      		  <div class="dbr hr"></div>
		      	      <br /><br />
		      	      
		      	      <div class="txtcenter">
		      			<input type="submit" value="" class="searchdiamondbtn" name="searchdiamonds" title="Start Search">		      			
		      		</div>  
		      		<div class="dbr"></div>
		      		<div class="hr"></div>
		      		<!--<div class="txtcenter"><input type="submit" value="Search Diamonds" class="button" name="searchdiamonds"></div> -->
            </form>
			
		</div>
	  	<div class="bodybottom"></div>
</div>