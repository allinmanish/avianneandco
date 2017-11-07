 
<div class="floatl pad05 body">
  <div class="bodytop"></div>
  <div class="bodymid">
  
  			<div class="topdiv">
				<?php echo $top_ads;?>
			</div>
			<div class="dbr"></div>
  			 
  
  			<?php $img = file_exists(config_item('base_path').$details['small_image']) ? config_item('base_url').$details['small_image'] : config_item('base_url').'images/rings/noimage_sm.jpg';  ?>
  			<div class="column floatl">
  					<center> <img src="<?php echo $img;?>" width="250px"></center>
  			</div>
  			  			
  			<div class="column floatl">
	  			 <p class="newtileheader">Pendant</p><br>
	  			 <p><?php echo $details['description'];?></p>
	  			 <br>
	  			 <p>
	  			 
	  			 		<table width="200">
	  			 				<tr>
	  			 						<td><b>Diamond Price:</b></td>
	  			 						<td align="right">$<?php echo floatval($diamondprice);?></td>
	  			 				</tr>
	  			 				<tr>
	  			 						<td><b>Settings Price:</b></td>
	  			 						<td align="right">$<?php echo floatval($settingsprice);?></td>
	  			 				</tr>
	  			 				<?php if($side1price != 0 || $side2price != 0){?>
	  			 				<tr>
	  			 						<td><b>Stone1 Price:</b> </td>
	  			 						<td align="right">$<?php echo floatval($side1price);?></td>
	  			 				</tr>
	  			 				<tr>
	  			 						<td><b>Stone2 Price:</b></td>
	  			 						<td align="right">$<?php echo floatval($side2price);?></td>
	  			 				</tr>
	  			 				<?php }?>
	  			 				<tr>
	  			 						<td colspan="2"> </td> 
	  			 				</tr>
	  			 				<tr>
	  			 						<td><b>Total:</b></td>
	  			 						<td align="right">$<b><?php echo floatval($totalprice);?></b></td>
	  			 				</tr>
	  			 		</table>
	  			 
	  			 </p>
	  			  
	  			 <div class="dbr"></div>
	  			  
	  			 <div class="column floatr">		  				
	  				<div class="floatl">
	  	      				<div class="floatl blurleft"></div>
	  	      				<div class="floatl bluemiddle"><a href="<?php echo $nexturl;?>" onclick="<?php echo ($nexturl == '#') ? $onclickfunction : '';?>">add to basket</a></div>
	  	      				<div class="floatl blueright"></div>
	  	      				<div class="clear"></div>
  	      			</div>
  	      			<div class="floatr">
  	      				<div class="floatl brownleft"></div>
  	      				<div class="floatl brownmiddle"><a href="#" onclick="$.facebox.close()">back to search</a></div>
  	      				<div class="floatl brownright"></div>
  	      				<div class="clear"></div>
  	      			</div>
  	      			<div class="clear"></div>
  			</div>
  			</div>
  			<div class="clear"></div>
  
  
   </div>
 <div class="bodybottom"></div>
</div>