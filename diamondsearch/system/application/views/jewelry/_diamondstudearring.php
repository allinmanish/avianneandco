<?php $this->load->helper('t');?>
<div class="floatl pad05 body">
  <div class="bodytop"></div>
	  <div class="bodymid">	  
	  		
	  		<div class="pad10">
	  
	  		<?php foreach ($collections as $collection){?>
	  			<div class="dbr"></div>
				<div class="floatl " valign='middle' align='center' style='padding:10px'>
					<?php $img = file_exists(config_item('base_path').'images/'.$collection['big_image']) ? config_item('base_url').'images/'.$collection['big_image'] : config_item('base_url').'images/rings/noimage.jpg';  ?>
			  		<img src="<?php echo $img; ?>" ><br>	
		  		</div>

		  		<div class="tileheader" style='padding-left:10px'><?php echo $collection['collection']?></div><br>		  		
		  		<div style='padding-left:10px'><?php echo $collection['description']?> </div>
		  		<?php $earrings = $this->jewelrymodel->getByCollectionName($collection['collection']);
		  			foreach ($earrings as $earring){
		  		?>
						<a href="javascript:void(0)"onclick="viewearringstuddetails('<?php echo $earring['stock_number']?>','<?php echo $earring['price']?>')">
		  				<div class="floatl borderrigth">
						
		  						<center>		  						
		  							<?php $img = file_exists(config_item('base_path').'images/'.$earring['carat_image']) ? config_item('base_url').'images/'.$earring['carat_image'] : config_item('base_url').'images/rings/noimage.jpg';  ?>
		  							
		  							<!--<img src="<?php echo config_item('base_url')?>images/rings/<?php echo $earring['carat_image']?>"><br>-->		  							
		  							<img src="<?php echo $img; ?>"><br>			  						
			  						<?php echo '$'.$earring['price'];?><br>
			  						<a href="#" onclick="viewearringstuddetails('<?php echo $earring['stock_number']?>','<?php echo $earring['price']?>')" class="underline">View</a>
		  						</center>
		  				</div>
						</a>

	  		<?php }
	  					echo '<div class="clear"></div>';
	  					echo '<div class="hr"></div>';
	  		} ?>
	  		</div>
	  		
	  
	  </div>
 <div class="bodybottom"></div>
</div>
	   	 