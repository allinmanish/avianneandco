<?php 
			 
 echo("<pre>");
	//    print_r($pairdiamond); 
	  echo("</pre>"); 
	//if(sizeof($pairdiamond)>1){
		$sidestone1 = $side1;
		$sidestone2 = $side2;
	//}elseif (sizeof($pairdiamond)<=1){
	//	$sidestone1 = $sidestone2;
	//		$sidestone2 = $sidestone2;
	//}
	$totalpriceofsidestone = $sidestone1['price'] + $sidestone2['price'];
	$totalcarat = $sidestone1['carat'] + $sidestone2['carat'];
	
		
	$hyperlink = '#';
	if($addoption == 'addsidestone')				{$hyperlink = config_item('base_url').'engagement/threestoneringsettings/'.$centerlot.'/'.$sidestone1['lot'].'/'.$sidestone2['lot'].'/'. $addoption;}
	elseif($addoption == 'addpendantsetings3stone')	{
		//$hyperlink = config_item('base_url').'diamonds/addthisdiamond/'.$addoption.'/'.$centerlot.'/'. $pendantsettingsid.'/'.$sidestone1['lot'].'/'.$sidestone2['lot'];
		$hyperlink = '##';
		$totalpendantprice = $centerlotprice + $totalpriceofsidestone;
		$onclickfunction = "addtocart('".$addoption."',".$centerlot.",".$sidestone1['lot'].",".$sidestone2['lot'].",".$pendantsettingsid.",".$totalpendantprice.")";
	}
																			     
			
	   
?>

<div class="floatl pad05">
  		<div class="bodytop"></div>
	  	<div class="bodymid">
	  	
	  			
	  		 
		  			<div class="column floatl">
			  				<p class="newtileheader">Your Diamond Pair has a Total Carat Weight of [<?=$totalcarat ?>] Carat</p><br>
			  				<p>These carefully selected diamonds work well together because of their near-identical cut, color, clarity and size. To compare the specific diamond details, see the charts and information below.</p><br>
			  				<p><b>Price: </b>$<?php echo number_format( round($totalpriceofsidestone),',');?>(<small><i>including both diamonds</i></small>)</p>
		  			</div>
		  			<div class="column floatl">
			  				<img src="<?php echo config_item('base_url');?>images/tamal/choose_02.jpg">
			  				<div class="floatl">
			  	      				<div class="floatl blurleft"></div>
			  	      				<div class="floatl bluemiddle"><a href="<?php echo $hyperlink;?>" onclick="<?php echo ($hyperlink == '##') ? $onclickfunction : '';?>">Add Sidestones to Pendant</a></div>
			  	      				<div class="floatl blueright"></div>
			  	      				<div class="clear"></div>
		  	      			</div>
		  	      			<div class="floatl">
		  	      				<div class="floatl brownleft"></div>
	      	      				<div class="floatl brownmiddle"><a href="#" onclick="$.facebox.close()">Back to Search</a></div>
	      	      				<div class="floatl brownright"></div>
	      	      				<div class="clear"></div>
		  	      			</div>
		  	      			<div class="clear"></div>
		  			</div>
		  			<div class="clear"></div>
		  			<div class="dbr"></div>
		  			
		  			<div>
		  			<?php 
		  		 
		  				   //$sidestone1 = $sidestone1[0];
		  				   //$sidestone2 = $sidestone2;//isset($sidestone[1]) ? $sidestone[1] : $sidestone[0];
		  			?>
		  				
		  				<table width="500px" style="border-collapse: separate;">
		  				 	<tr class="tablaheader">
		  						<td>Details</td>
		  						<td>diamond 1</td>
		  						<td>diamond 2</td>
		  					</tr>
		  					
		  					<!--<tr class="brownback">
		  						<td>Certificates</td>
		  						<td><?php echo $side1['Cert']; ?></td>
		  						<td><?php echo $side2['Cert']; ?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Images</td>
		  						<td></td>
		  						<td></td>
		  					</tr>-->
		  					<tr><td colspan="3"><br></td> </tr>
		  					<tr class="brownback">
		  						<td>Stock No.</td>
		  						<td><?php echo $sidestone1['lot']?></td>
		  						<td><?php echo $sidestone2['lot']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Price</td>
		  						<td>$<?php echo number_format( round($sidestone1['price']),',')?></td>
		  						<td>$<?php echo number_format( round($sidestone2['price']),',')?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Carat weight:</td>
		  						<td><?php echo $sidestone1['carat']?></td>
		  						<td><?php echo $sidestone2['carat']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Cut:</td>
		  						<td><?php echo $sidestone1['cut']?></td>
		  						<td><?php echo $sidestone2['cut']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Color:</td>
		  						<td><?php echo $sidestone1['color']?></td>
		  						<td><?php echo $sidestone2['color']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Clarity:</td>
		  						<td><?php echo $sidestone1['clarity']?></td>
		  						<td><?php echo $sidestone2['clarity']?></td>
		  					</tr>
		  					<tr><td colspan="3"><br></td> </tr>
		  					<tr class="brownback">
		  						<td>Depth %:</td>
		  						<td><?php echo $sidestone1['Depth']?></td>
		  						<td><?php echo $sidestone2['Depth']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Table %:</td>
		  						<td><?php echo $sidestone1['TablePercent']?></td>
		  						<td><?php echo $sidestone2['TablePercent']?></td>
		  					</tr>		  					
		  					<tr class="brownback">
		  						<td>Polish:</td>
		  						<td><?php echo $sidestone1['Polish']?></td>
		  						<td><?php echo $sidestone2['Polish']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Symmetry:</td>
		  						<td><?php echo $sidestone1['Sym']?></td>
		  						<td><?php echo $sidestone2['Sym']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Girdle:</td>
		  						<td><?php echo $sidestone1['Girdle']?></td>
		  						<td><?php echo $sidestone2['Girdle']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Culet:</td>
		  						<td><?php echo $sidestone1['Culet']?></td>
		  						<td><?php echo $sidestone2['Culet']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Fluorescence:</td>
		  						<td><?php echo $sidestone1['Flour']?></td>
		  						<td><?php echo $sidestone2['Flour']?></td>
		  					</tr>
		  					<tr class="brownback">
		  						<td>Measurements:</td>
		  						<td><?php echo $sidestone1['Meas']?> mm</td>
		  						<td><?php echo $sidestone2['Meas']?> mm</td>
		  					</tr>
		  					
		  				</table>		  			
		  			</div>
			   
	  	
	  	</div>	
	  	
	  	<div class="bodybottom"></div>
</div>
<div class="clear"></div>