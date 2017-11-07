<div class="floatl pad05">
  		<div class="bodytop"></div>
	  	<div class="bodymid">
	  		
	  			<?php $img = file_exists(config_item('base_path').'images/'.$details['small_image']) ? config_item('base_url').'images/'.$details['small_image'] : config_item('base_url').'images/rings/noimage_sm.jpg';  ?>
	  			<div class="column floatl">
	  					<center> <img src="<?php echo $img;?>"></center>
	  			</div>
	  			
	  			<div class="column floatl">
		  			 <p class="newtileheader"><?php echo $details['collection'];?> </p><br>
		  			 <p><?php echo $details['description'];?></p>
		  			 <br>
		  			 <p><b>Price:</b> $<?php echo $details['price']?></p>
	  			</div>
	  			<div class="clear"></div>
	  			<div class="dbr"></div>
	  			
	  			<div class="column floatr">		  				
		  				<div class="floatl">
		  	      				<div class="floatl blurleft"></div>
		  	      				<div class="floatl bluemiddle"><a href="#" onclick="addtocart(<?php echo "'".$addoption."'";?>,false,false,false,<?php echo $details['stock_number'];?>,<?php echo $details['price'];?>)">add this to basket</a></div>
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
	  			<div class="clear"></div>
	  	
	  	</div>	  	
	  	<div class="bodybottom"></div>
</div>
<div class="clear"></div>