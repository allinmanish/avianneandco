
<br>
 
 <h3 class="bludebackhead"><img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#reportsforsellers').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#reportsforsellers').hide();}"> Sellers Diamonds</h3>
 
 <div id="reportsforsellers" style="display:none">
 
			<table width="100%" class="report" align="center">
			  <tr class="bludebackhead">
			    <td>Owner/Sellers</td>
			    <td>Diamonds Count</td>  
			  </tr>
			  
			  <?php if(isset($diamondscountforsellers)){$i = 0; foreach ($diamondscountforsellers as $row){?>
			  <tr <?php if($i%2 == 0)echo 'class="odd"'; $i++;?>>
			    <td><?php echo strtoupper($row['owner']);?> </td>
			   
			    <td><?php echo $row['total'];?></td>
			  </tr><?php }}?>
			  
			  </table>
			  
 
 </div>
 
 <br> <br>
 
 <h3 class="bludebackhead"><img src="<?php echo config_item('base_url')?>/images/tamal/expand.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#reportsonsellers').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#reportsonsellers').hide();}"> Diamonds count with shape for sellers</h3>
 
 <div id="reportsonsellers" style="display:none">
 
			<table width="100%" class="report" align="center">
			  <tr class="bludebackhead">
			    <td>Owner/Sellers</td>
			    <td style="width: 50px;">Shape</td>
			    <td>Diamonds Count</td>  
			  </tr>
			  
			  <?php if(isset($diamondscountbysellerswithshape)){$i= 0; foreach ($diamondscountbysellerswithshape as $row){?>
			  <tr <?php if($i%2 == 0)echo 'class="odd"'; $i++;?>>
			    <td><?php echo strtoupper($row['owner']);?> </td>
			    <td>
			    <?php switch ($row['shape']){
			    				case 'B':
						  		?><img src="<?php echo config_item('base_url');?>images/diamonds/round.jpg" alt="round" /><?php 
						  		break;
						  	case 'PR':
						  		?><img src="<?php echo config_item('base_url');?>images/diamonds/princess.jpg" alt="princess" /> <?php
						  		break;
						  	case 'R':
						  		?> <img src="<?php echo config_item('base_url');?>images/diamonds/radiant.jpg" alt="radiant" /><?php
						  		break;
						  	case 'E':
						  		?>  <img src="<?php echo config_item('base_url');?>images/diamonds/emerald.jpg" alt="emerald" /><?php
						  		break;
						  	case 'AS':
						  		?> <img src="<?php echo config_item('base_url');?>images/diamonds/asscher.jpg" alt="asscher" /><?php
						  		break;
						  	case 'O':
						  		?><img src="<?php echo config_item('base_url');?>images/diamonds/oval.jpg" alt="oval" /> <?php
						  		break;
						  	case 'M':
						  		?> <img src="<?php echo config_item('base_url');?>images/diamonds/marquise.jpg" alt="marquise" /><?php
						  		break;
						  	case 'P':
						  		?> <img src="<?php echo config_item('base_url');?>images/diamonds/pear.jpg" alt="pear" /><?php
						  		break;
						  	case 'H':
						  		?> <img src="<?php echo config_item('base_url');?>images/diamonds/heart.jpg" alt="heart" /><?php
						  		break;
						  	case 'C':
						  		?><img src="<?php echo config_item('base_url');?>images/diamonds/cushion.jpg" alt="cushion" /> <?php
						  		break;								  	
						  	default:
						  		 
						  		break;
						  		
			    }?>
			    
			    </td>
			    <td><?php echo $row['total'];?></td>
			  </tr><?php }}?>
			  
			  </table>
			  
 
 </div>
 
 
