<div class="floatl pad05 body">
		<div class="bodytop"></div>
		<div class="bodymid">
		
				<div class="topdiv">
					<?php echo $top_ads;?>
				</div>
		 		<?php echo $tabhtml;?>
		 		<div class="dbr"></div>
				<?php if(isset($threestonerings)){
					foreach ($threestonerings as $threestone){?>
						<div class="floatl ringbox txtcenter">							
							<a href="#" onclick="viewRingsDetails(<?php echo $threestone['stock_number'];?>,false)">									
									<img id="ringbigimage<?php echo $threestone['stock_number']?>" src="<?php echo file_exists(config_item('base_path').'images/rings/'.$threestone['small_image']) ? config_item('base_url').'images/rings/'.$threestone['small_image'] : config_item('base_url').'images/rings/noimage.jpg';?>" width="70" >
							</a>
							<p><?php echo substr($threestone['description'],0,60);?>......</p><br>
							<p><b>Price:</b> $<?php echo $threestone['price'];?></p>
							<p><a href="javascript:void(0)" class="search" onclick="viewRingsDetails(<?php echo $threestone['stock_number'];?>,false)">View Details</a></p>
						</div>
				<?php }}?>
				<div class="clear"></div>
		
		</div>
		<div class="bodybottom"></div>
</div>
	   	 