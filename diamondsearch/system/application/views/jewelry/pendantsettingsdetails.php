
<div class="floatl pad05">
  		<div class="bodytop"></div>
	  	<div class="bodymid">
	  		
	  			<?php $img = file_exists(config_item('base_path').$details['small_image']) ? config_item('base_url').$details['small_image'] : config_item('base_url').'images/rings/noimage_sm.jpg';  ?>
	  			<div class="column floatl">
	  					<center> <img src="<?php echo $img;?>" width="110"></center>
	  			</div>
	  			
	  			<div class="column floatl">
		  			 <p class="newtileheader">Pendant</p><br>
		  			 <p><?php echo $details['description'];?></p>
		  			 <br>
		  			 <p><b>Price:</b> $<?php echo $details['price']?></p>
		  			 <div class="dbr"></div>
		  			 <?php $paramoption = ($style == 'threestone') ? 'addpendantsetings3stone' : 'addpendantsetings'?>
		  			 <div class="column floatr">		  				
		  				<div class="floatl">
		  	      				<div class="floatl blurleft"></div>
		  	      				<div class="floatl bluemiddle"><a href="<?php echo config_item('base_url')?>diamonds/search/false/false/<?php echo $paramoption;?>/false/<?php echo $details['id'];?>">add this settings</a></div>
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
<div class="clear"></div>