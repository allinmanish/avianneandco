<?php 
//var_dump($details);
 
	$shape = '';
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
				  		$shape = 'Ascher';
				  		break;
				  	case 'O':
				  		$shape = 'Oval';
				  		break;
				  	case 'M':
				  		$shape = 'Marquise';
				  		break;
				  	case 'P':
				  		$shape = 'Pear shape';
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
<div class="floatl pad05 body">
  		<div class="bodytop"></div>
	  	<div class="bodymid">  
	    		
	  			<h1 class="pageheader hr">Details View</h1>
	  			
	  			<div class="floatl selectedtab"><a href="<?php echo config_item('base_url');?>engagement/search/diamonds" class="gray">Select Your Diamonds</a></div>
	  			<div class="floatl tabheader"><a href="<?php echo config_item('base_url');?>engagement/search/ring" class="gray">Choose Your Settings</a></div>
	  			<div class="floatl tabheader"><a href="<?php echo config_item('base_url');?>engagement/search/addbasket" class="gray">Add To Basket</a></div>
	  			<div class="clear"></div>
	  			
	  			
	  			<div>
			  			<div class="floatl bigcontainerL">
			  				<div style="display:block;">
			  					<img src="<?php echo config_item('base_url')?>/images/tamal/detail_side.jpg">
			  					<div class="tablapercent txtsmall">Table percent</div>
			  				</div>
			  			</div>
			  			<div class="floatl bigcontainerR">
						      <p><strong><?php echo $details['carat'] ?>-Carat <?php echo $shape ?> Shape Diamond</strong> </p>
						      <p>This <i><?php echo $details['cut']?></i>-cut, <i><?php echo $details['color']?></i>-color, and <i><?php echo $details['clarity']?></i>-clarity diamond comes accompanied by a diamond grading report from the <?php echo $details['Cert']?>.</p>
						      <div class="dbr"></div>
						      <p><strong>Price:</strong> $<?php echo $details['price']?></p>
				      </div>
				      <div class="clear"></div>
				</div>
		      <form action="<?php echo config_item('base_url');?>diamonds/search/true" method="POST">
				     <div>				             		
				      </div>		      
		      
		      
		      	      <br /><br />
		      	      
		      	      		<!--<div class="txtcenter"><input type="submit" value="Search Diamonds" class="button" name="searchdiamonds"></div>-->
		      	      		<div class="txtcenter"><input type="submit" value="Add This Diamond" class="button" name="searchdiamonds"></div>  
		      	      
		      </form>
		      
	  	
	  	
	  	</div>
	  	<div class="bodybottom"></div>
</div>