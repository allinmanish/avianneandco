<?php $this->load->helper('custom');?>
<h3 class="bludebackhead"><img src="<?php echo config_item('base_url');?>/images/tamal/expand.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#reportsonshapes').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#reportsonshapes').hide();}"> Diamond Reports on Shapes</h3>
														 
<div id="reportsonshapes" style="display:none">
			<table width="100%" class="report" align="center">
			  <tr class="bludebackhead">
			    <td colspan="2">Shape</td>
			    <td>Carat</td>  
			    <td>Table</td>
			    <td>Depth</td>    
			    <td>Color</td>
			    <td>Cert</td>
			    <td>Symmertry</td>  
			    <td>Polish</td>
			    <td>Flour</td>    
			    
			  </tr>
			  
			  <?php 
			  		$shape = array(0 => 'B',
			  		               1 =>'PR',
			  		               2 =>'R',
			  		               3 =>'E',
			  		               4 =>'AS',
			  		               5 =>'O',
			  		               6 =>'M',
			  		               7 =>'P',
			  		               8 =>'H',
			  		               9 =>'C');
			  		$shapeimage = array('B' => 'round',
			  							'PR' => 'princess',
			  							'R' => 'radiant',
			  							'E' => 'emerald',
			  							'AS' => 'asscher',
			  							'O' => 'oval',
			  							'M' => 'marquise',
			  							'P' => 'pear',
			  							'H' => 'heart',
			  							'C' =>'cushion');
			  for($i= 0; $i< 10; $i++){
			  ?>
			  <tr <?php if($i%2 == 0) echo 'class="odd"';?>>
			    <td style="width: 50px;"> <img src="<?php echo config_item('base_url');?>images/diamonds/<?php echo $shapeimage[$shape[$i]];?>.jpg" alt="<?php echo ucfirst($shapeimage[$shape[$i]]);?>" /></td><td style="width: 80px;"> <?php echo ucfirst($shapeimage[$shape[$i]]);?></td>
			    <td>
			        <?php $ret = getMinMaxWithShape($shape[$i],'carat', true);  echo isset($ret[0]['min']) ? $ret[0]['min'] : '0'; echo ' - ';  echo isset($ret[0]['max']) ? $ret[0]['max'] : '0'; ?>
			    </td>  
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'tablepercent', true); echo isset($ret[0]['min']) ? $ret[0]['min'] : '0'; echo ' - ';  echo isset($ret[0]['max']) ? $ret[0]['max'] : '0'; ?>
			    </td>
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'depth', true); echo isset($ret[0]['min']) ? $ret[0]['min'] : '0'; echo ' - ';  echo isset($ret[0]['max']) ? $ret[0]['max'] : '0'; ?>
			    </td>    
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'color', false); echo isset($ret[0]['fields']) ? $ret[0]['fields'] : ''; echo ' - '; echo (isset($ret[sizeof($ret)-1]['fields'])) ? $ret[sizeof($ret)-1]['fields'] : ''; ?>
			    </td>
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'cert', false); foreach ($ret as $row){echo $row['fields'].' ';} ?>
			    </td>
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'Sym', false); foreach ($ret as $row){echo $row['fields'].' ';} ?>
			    </td>  
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'polish', false); foreach ($ret as $row){echo $row['fields'].' ';} ?>
			    </td>
			    <td>
			    	<?php $ret = getMinMaxWithShape($shape[$i],'flour', false); foreach ($ret as $row){echo $row['fields'].' ';} ?>
			    </td>    
			    
			  </tr>
					      		
			  <?php }?>
			   
			  
			</table>
			
 </div>
   
 <br> 