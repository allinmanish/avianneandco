<?php $this->load->helper('custom');?>
<style>
table.report{
font-size: 10px;
}
</style>
<h3 class="bludebackhead"><img src="<?php echo config_item('base_url');?>/images/tamal/expand.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#csvdiv').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#csvdiv').hide();}"> Pricescope CSV Export Orders</h3>
<div id="csvdiv" style="display:none;">
<div style="width: 1000px; overflow:auto;height: 70px; border: 5px #e4e5f4 solid;">
<?php $isbasic = false; if(isset($basic)) $isbasic = true;
      echo $exportstr;
 ?>
			 

</div>
<font color="Green">*  ERD DB Column Name ( order )</font><br>
<font color="red">*  Pricescope DB Column Name ( order )</font>
</div>

<br>
<br>

<h3 class="bludebackhead"><img src="<?php echo config_item('base_url');?>/images/tamal/<?php if($isbasic) echo 'minimize';else echo 'expand';?>.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#pricescopecsv').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#pricescopecsv').hide();}"> Pricescope CSV Export</h3>

		
<form action="<?php echo config_item('base_url');?>admin/pricescopecsv<?php if($isbasic) echo '/setbasicvalue';?>" method="POST">
<div id="pricescopecsv" style="display: <?php if(!$isbasic) echo 'none';?>;">
			<table style="background: #fff;" class="report" align="center">
			  <tr class="bludebackhead">
			    <td colspan="2">Shape</td>
			    <td style="width: 115px;">Carat</td>  
			    <td style="width: 115px;">Table</td>
			    <td style="width: 115px;">Depth</td>    
			    <td style="width: 90px;">Color</td>
			    <td>Cert</td>
			    <td>Symmertry</td>  
			    <td>Polish</td>
			    <td style="width: 205px;">Flour</td>    
			    
			  </tr>
			  
			  <?php 
			   	    $coloroptions = '<option value="0">D</option>
			   	    				 <option value="1">E</option>
			   	    				 <option value="2">F</option>
			   	    				 <option value="3">G</option>
			   	    				 <option value="4">H</option>
			   	    				 <option value="5">I</option>
			   	    				 <option value="6">J</option>
			   	    				 ';
			   	    
			   	    
			   	    $floroptions = '<option value="0">NO</option>
			   	    				 <option value="1">FB</option>
			   	    				 <option value="2">MED</option>
			   	    				 <option value="3">MB</option>
			   	    				 <option value="4">ST BLUE</option>
			   	    				 <option value="5">VST BLUE</option>
			   	    				 ';
                   
	                
	                
	                			   	    
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
			 /* for($i= 0; $i< 10; $i++){
			  ?>
			  <tr <?php if($i%2 == 0) echo 'class="odd"';?>>
			    <td style="width: 50px;"> <img src="<?php echo config_item('base_url');?>images/diamonds/<?php echo $shapeimage[$shape[$i]];?>.jpg" alt="<?php echo ucfirst($shapeimage[$shape[$i]]);?>" /></td>
			    <td style="width: 85px; text-align:left;"><input type="checkbox" name="shape<?php echo $i;?>" <?php echo ($isbasic) ? 'checked' : '';?>> <?php echo ucfirst($shapeimage[$shape[$i]]);?></td>
			    <td>
			        <?php if($isbasic){ $ret = getMinMaxWithShape($shape[$i],'carat', true); $caratmin = isset($ret[0]['min']) ? $ret[0]['min'] : '0';    $caratmax = isset($ret[0]['max']) ? $ret[0]['max'] : '0'; } else { $caratmin = ''; $caratmax = '';} ?>
			        <input type="text" style="width: 45px;" maxlength="5" name="carat1<?php echo $i;?>" value="<?php echo $caratmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="carat2<?php echo $i;?>" value="<?php echo $caratmax;?>"> 
			    </td>  
			    <td>
			    	<?php if($isbasic){ $ret = getMinMaxWithShape($shape[$i],'table', true); $tablepercentmin = isset($ret[0]['min']) ? $ret[0]['min'] : '0';    $tablepercentmax = isset($ret[0]['max']) ? $ret[0]['max'] : '0'; } else { $tablepercentmin = ''; $tablepercentmax = '';} ?>
			        <input type="text" style="width: 45px;" maxlength="5" name="tablepercent1<?php echo $i;?>" value="<?php echo $tablepercentmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="tablepercent2<?php echo $i;?>" value="<?php echo $tablepercentmax;?>"> 
			    </td>
			    <td>
			    	<?php if($isbasic){ $ret = getMinMaxWithShape($shape[$i],'depth', true); $depthmin = isset($ret[0]['min']) ? $ret[0]['min'] : '0';    $depthmax = isset($ret[0]['max']) ? $ret[0]['max'] : '0'; } else { $depthmin = ''; $depthmax = '';} ?>
			        <input type="text" style="width: 45px;" maxlength="5" name="depth1<?php echo $i;?>" value="<?php echo $depthmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="depth2<?php echo $i;?>" value="<?php echo $depthmax;?>"> 
			        
			    </td>    
			    <td>
					<?php if($isbasic){ $ret = getMinMaxWithShape($shape[$i],'color', true); $colormin = isset($ret[0]['min']) ? $ret[0]['min'] : '0';    $colormax = isset($ret[0]['max']) ? $ret[0]['max'] : '0'; } else { $colormin = '0'; $colormax = '6';} ?>			           
 			    	 <select name="color1<?php echo $i;?>"> <?php echo makeOptionSelected($coloroptions , $colormin);?></select> - <select name="color2<?php echo $i;?>"> <?php echo makeOptionSelected($coloroptions , $colormax);?></select>
			    </td>
			    <td align="center">
			    	<div><?php $ret = getMinMaxWithShape($shape[$i],'cert', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'cert[]" value="'.$row['fields'].'" ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div>';} ?><div class="clear"></div></div> 
			    </td>
			    <td align="center">
			    	 <div><?php $ret = getMinMaxWithShape($shape[$i],'Sym', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'sym[]" value="'.$row['fields'].'"  ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div>';} ?><div class="clear"></div></div> 
			    	
			    </td>  
			    <td align="center">
			    	<div><?php $ret = getMinMaxWithShape($shape[$i],'polish', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'polish[]" value="'.$row['fields'].'"  ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div> ';} ?><div class="clear"></div></div> 
			    	
			    </td>
			    <td align="center">
					 <?php if($isbasic){ $ret = getMinMaxWithShape($shape[$i],'flour', true); $flourmin = isset($ret[0]['min']) ? $ret[0]['min'] : '0';    $flourmax = isset($ret[0]['max']) ? $ret[0]['max'] : '0'; } else { $flourmin = '0'; $flourmax = '6';} ?>			           			    	 
					 <select name="flour1<?php echo $i;?>" style="width: 85px;"> <?php echo makeOptionSelected($floroptions , $flourmin);?></select> - <select style="width: 85px;" name="flour2<?php echo $i;?>"> <?php echo makeOptionSelected($floroptions , $flourmax);?></select>			    	
			    </td>    
			    
			  </tr>
					      		
			  <?php }              */   ?>
			  
			   <?php  
			       for($i= 0; $i< 10; $i++){  
			   		$result = PricescopeExport($shape[$i]);
			   		?>
			   	 <tr <?php if($i%2 == 0) echo 'class="odd"';?>>
			   		<td style="width: 50px;"> <img src="<?php echo config_item('base_url');?>images/diamonds/<?php echo $shapeimage[$shape[$i]];?>.jpg" alt="<?php echo ucfirst($shapeimage[$shape[$i]]);?>" /></td>
				    <td style="width: 85px; text-align:left;"><input type="checkbox" name="shape<?php echo $i;?>" <?php if($isbasic){ echo ($result[0]['export']) ? 'checked' : ''; }?>> <?php echo ucfirst($shapeimage[$shape[$i]]);?></td>
				    <td>
				        <?php if($isbasic){   $caratmin = $result[0]['carat1'];    $caratmax = $result[0]['carat2'];} else { $caratmin = ''; $caratmax = '';} ?>
				        <input type="text" style="width: 45px;" maxlength="5" name="carat1<?php echo $i;?>" value="<?php echo $caratmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="carat2<?php echo $i;?>" value="<?php echo $caratmax;?>"> 
				    </td>  
				    <td>
				    	<?php if($isbasic){  $tablepercentmin = $result[0]['table1'];     $tablepercentmax = $result[0]['table2'];  } else { $tablepercentmin = ''; $tablepercentmax = '';} ?>
				        <input type="text" style="width: 45px;" maxlength="5" name="tablepercent1<?php echo $i;?>" value="<?php echo $tablepercentmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="tablepercent2<?php echo $i;?>" value="<?php echo $tablepercentmax;?>"> 
				    </td>
				    <td>
				    	<?php if($isbasic){  $depthmin = $result[0]['depth1'];   $depthmax =$result[0]['depth2']; } else { $depthmin = ''; $depthmax = '';} ?>
				        <input type="text" style="width: 45px;" maxlength="5" name="depth1<?php echo $i;?>" value="<?php echo $depthmin;?>"> - <input type="text" style="width: 45px;" maxlength="5" name="depth2<?php echo $i;?>" value="<?php echo $depthmax;?>"> 
				        
				    </td>    
				    <td>
						<?php if($isbasic){  $colormin = $result[0]['color1'];    $colormax = $result[0]['color2']; } else { $colormin = '0'; $colormax = '6';} ?>			           
	 			    	 <select name="color1<?php echo $i;?>"> <?php echo makeOptionSelected($coloroptions , $colormin);?></select> - <select name="color2<?php echo $i;?>"> <?php echo makeOptionSelected($coloroptions , $colormax);?></select>
				    </td>
				    <td align="center">
				    	<div><?php $ret = getMinMaxWithShape($shape[$i],'cert', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'cert[]" value="'.$row['fields'].'" ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div>';} ?><div class="clear"></div></div> 
				    </td>
				    <td align="center">
				    	 <div><?php $ret = getMinMaxWithShape($shape[$i],'Sym', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'sym[]" value="'.$row['fields'].'"  ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div>';} ?><div class="clear"></div></div> 
				    	
				    </td>  
				    <td align="center">
				    	<div><?php $ret = getMinMaxWithShape($shape[$i],'polish', false); foreach ($ret as $row){echo '<div class="tborder floatl"><input type="checkbox" name="'.$shape[$i].'polish[]" value="'.$row['fields'].'"  ';if($isbasic)echo 'checked'; echo '><br>'.$row['fields'].'</div> ';} ?><div class="clear"></div></div> 
				    	
				    </td>
				    <td align="center">
						 <?php if($isbasic){  $flourmin = $result[0]['flour1'];     $flourmax = $result[0]['flour2']; } else { $flourmin = '0'; $flourmax = '5';} ?>			           			    	 
						 <select name="flour1<?php echo $i;?>" style="width: 85px;"> <?php echo makeOptionSelected($floroptions , $flourmin);?></select> - <select style="width: 85px;" name="flour2<?php echo $i;?>"> <?php echo makeOptionSelected($floroptions , $flourmax);?></select>			    	
				    </td>   
			   
			     
			   
			   </tr>
			   <?php }?>
			   
			   
			</table>
			
 </div>
   
 <br>  <br> Export File Name : <input type="text" value="csvfilename" name="csvfilename" value="" style="width: 400px;"> 
 							   <input type="submit" name="exportcsv" value="Export" class="adminbutton"> 
 							   <input type="reset" value="Reset" name="resetbtn" class="adminbutton"> &nbsp;
 							   <input type="submit" name="savebasicsettings" value="Save as Basic Value" class="adminbutton"> &nbsp;
 							   <input type="button" value="SET BASIC VALUES" name="basicbtn" class="adminbutton" onclick="window.location = '<?php echo config_item('base_url');?>admin/pricescopecsv/setbasicvalue';">
 </form>
 
 
 <br>
<br>
