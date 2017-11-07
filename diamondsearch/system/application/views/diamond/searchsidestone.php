<?php 

	$shape = '';
	switch ($diamond['shape']){
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
	  echo("<pre>");
	  // print_r($diamond); 
	  echo("</pre>"); 
?>
<div class="floatl pad05 body">
		<div class="bodytop"></div>
		<div class="bodymid">
		
				<div class="topdiv">
					<?php echo $top_ads;?>
				</div>	
				<?php echo $tabhtml;?>
				<div class="dbr"></div>
				<div class="floatl column">
						<p class="infobox01">Your Center Stone details</p>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Shape :</a>
								</div>
								<div class="floatl bigcontainerL">
									<?php echo $shape; ?>
								</div>
								<div class="clear"></div>
						</div>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Price:</a>
								</div>
								<div class="floatl bigcontainerL">$
									<?php echo (number_format(round($diamond['price']),',')); ?>
								</div>
								<div class="clear"></div>								
						</div>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Cut:</a>
								</div>
								<div class="floatl bigcontainerL">
									<?php echo $diamond['cut'];?> 
								</div>
								<div class="clear"></div>								
							
						</div>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Color:</a>
								</div>
								<div class="floatl bigcontainerL">
									<?php echo $diamond['color']?>
								</div>
								<div class="clear"></div>
						</div>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Clarity:</a>
								</div>
								<div class="floatl bigcontainerL">
									<?php echo $diamond['clarity']?>
								</div>
								<div class="clear"></div>							
						</div>
						<div class="brownback ml4">
								<div class="w50px floatl">
									<a href="#" class="underline">Carat:</a>
								</div>
								<div class="floatl bigcontainerL">
									<?php echo $diamond['carat']
									?>
								</div>
								<div class="clear"></div>														
						</div>
				</div>
				<div class="floatl column">
						<p class=" pad10"> To speak to an experienced jewelry professional, please call <b>888-243-4344</b> </P>
				</div>
				<div class="clear"></div>
				<div class="hr"></div>
				<div class="dbr"></div>
				<p>Your search result</p>
				
				<input type="hidden" id="hlot" value="<?php echo $hlot;?>">
				<input type="hidden" id="hsettings" value="<?php echo $pendantsettingsid;?>">
				<input type="hidden" id="haddoption" value="<?php echo $addoption;?>">
				
				<div id="sidestoneresult">
					 
				</div>
				
			
		</div>
		<div class="bodybottom"></div>
</div>
	   	 
