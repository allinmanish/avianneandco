<?php 

	$this->load->helper('t');

?>

<div class="floatl pad05 body">
  <div class="bodytop"></div>
	  <div class="bodymid">  
	  			
	  
			  	<div class="pad10">
			  			
			  			<div class="divheader">Diamond Demo Search Result For : <?php echo $inputvalue;?></div>
			  			<div class="dbr"></div>
			  			
			  			<?php 
			  			if($diamonddetails != ''){?>
			  				<div class="resultheader">
			  						<a href="<?php echo config_item('base_url')?>diamonds/diamonddetails/<?php echo $diamonddetails['lot'];?>">Diamond of Lot# <?php echo $diamonddetails['lot'];?></a>
			  				</div>
			  				<div class="fakaline">
			  						This is <?php echo $diamonddetails['cut'];?>-cut, <?php echo $diamonddetails['color'];?>-color, and <?php echo $diamonddetails['clarity'];?>-clarity diamond...... <a href="<?php echo config_item('base_url')?>diamonds/diamonddetails/<?php echo $diamonddetails['lot'];?>">(see more)</a>

			  				</div>
			  				<br>
			  			<?php } 
			  			 
			  			if($jewelrydetails !=''){?>
			  				<div class="resultheader">
			  						<a href="<?php echo config_item('base_url')?>engagement/addtobasket/false/<?php echo $jewelrydetails['stock_number'];?>/addtoring">Jewelry of Stock# <?php echo $jewelrydetails['stock_number'];?></a> 
			  				</div>
			  				<div class="fakaline">
			  						<?php echo substr($jewelrydetails['description'],0,60);?>....<a href="<?php echo config_item('base_url')?>engagement/addtobasket/false/<?php echo $jewelrydetails['stock_number'];?>/addtoring">(see more)</a>

			  				</div>
			  				<br>
			  			<?php }
			  			$keyhtml = '';
			  			if(isset($keydetails) || sizeof($keydetails)>0){
			  				
			  				foreach ($keydetails as $key){
			  					 
			  					$keyhtml .= '
			  									<div class="resultheader">
			  											<a href="'.$key['pagelink'].'">'.$key['headline'].'</a>
			  									</div>
			  									<div class="fakaline">'.substr($key['description'],0,100).'....<a href="'.$key['pagelink'].'">(see more)</a></div>	
			  					
			  					';
			  			  }
			  			   echo $keyhtml;
			  			}

			  			if($diamonddetails == '' && $jewelrydetails == '' && sizeof($keydetails) == 0){
			  			?>
			  					
			  					<p class="noresult">No result found for <?php echo $inputvalue?>!</p>
			  					<p class="fakalinr">Plese search with appropriate keyword</p>
			  			
			  			<?php 	
			  			}
			  			?>
			  			
			  			
			  			
			    </div>
			    
	  </div>
 <div class="bodybottom"></div>
</div>