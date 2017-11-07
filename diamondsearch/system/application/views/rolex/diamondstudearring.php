<?php $this->load->helper('t');?>
<div class="floatl pad05 body">
  <div class="bodytop"></div>
	  <div class="bodymid">	  
	  		
	  		<div class="pad10">
	  
	  		<?php foreach ($collections as $collection){?>
	  			<div class="dbr"></div>
		  		<div class="tileheader"><?php echo $collection['collection']?></div><br>		  		
		  		<div><?php echo $collection['description']?> </div>
		  		<?php $earrings = getAllByCollectionName($collection['collection']);
		  			foreach ($earrings as $earring){
		  		?>
		  				<div class="floatl borderrigth">
		  						<center>		  						
		  							<?php $img = file_exists(config_item('base_path').'images/'.$earring['carat_image']) ? config_item('base_url').'images/'.$earring['carat_image'] : config_item('base_url').'images/rings/noimage_sm.jpg';  ?>
		  							
		  							<!--<img src="<?php echo config_item('base_url')?>images/rings/<?php echo $earring['carat_image']?>"><br>-->		  							
		  							<img src="<?php echo $img; ?>"><br>			  						
			  						<?php echo '$'.$earring['price'];?><br>
			  						<a href="#" onclick="viewearringstuddetails('<?php echo $earring['stock_number']?>','<?php echo $earring['price']?>')" class="underline">View</a>
		  						</center>
		  				</div>
	  		<?php }
	  					echo '<div class="clear"></div>';
	  					echo '<div class="hr"></div>';
	  		} ?>
	  		
	  		</div>
	  		
	  
	  </div>
 <div class="bodybottom"></div>
</div>
	   	 