 

<?php 
	$this->load->helper('custom');
?>

 
<?php 

if(isset($addform)){?>
 <div class="t" style="width: 500px;">
 	<div class="b">
 		<div class="l">
 			<div class="r">
 				<div class="bl">
 					<div class="br">
 						<div class="tl">
 							<div class="tr">
								
 							<form action="<?php echo $base_url;?>admin/managecity/<?php echo $addform; if($addform == 'edit') echo '/'. $id;?>" method="POST" name="new_city_form">
									  <div class="formfield">
									    <div class="fieldlabel">
									     	City Name : 
									    </div>
									    <div class="fieldinput1">
									      	<input type="text" name="cityname" id="newcity" onchange="require('newcity')" onblur="require('newcity')" maxlength="200" value="<?php if(isset($newcity)) echo $newcity;?>">
									    </div> 
									    <div class="clear"></div>
									  </div>
									  
									 <div class="formfield">
									    <div class="fieldlabel">
									      	State : 
									    </div>
									    <div class="fieldinput1">
									     <select name="optstate" id="state"><!--id="parentcategory"-->
									     <!--<option value="0">Uncatagorized</option>-->
									     	<?php echo makeOptionSelected($statelist , $stateid);?>
									     </select>
									    </div>
									      
									    <div class="clear"></div>
									  </div>
									  
									  <div class="formfield">
									    <div class="fieldlabel">
									      	Zip : 
									    </div>
									    <div class="fieldinput1">
									      	<input type="text" name="zip" id="zip" value="<?php if(isset($zip)) echo $zip;?>">
									    </div>
									      
									    <div class="clear"></div>
									  </div>
									  
									  <div class="formfield">
									    <div class="fieldlabel">
									      	Active Status : 
									    </div>
									    <div class="fieldinput1">
									     <select name="optActive" id="state">
									     
									     <?php 
									     	$activeblockopt = '<option value="0">Block</option>
									    	 					<option value="1">Actve</option>';
												echo makeOptionSelected($activeblockopt , $isenabled);?>
									    	  
									     </select>
									    </div>
									      
									    <div class="clear"></div>
									  </div>
									  
									  
																	
									<div id="requirefielderror"></div>
									<center>
										<input type="submit" name="addbutton" value="<?php echo ucfirst($addform);?> City" class="button"/>
										
										<input class="button" type="button" onclick="location.href='<?php echo $base_url;?>admin/managecity';" value="Cancel">
									</center>
									     
								 </form>
 
</div></div></div></div></div></div></div></div> <!-- round div -->

 <br> <br>
 
 <?php } 
 else
  {?>
 	<a href="<?php echo $base_url;?>admin/managecity/add"><img src="<?php echo $image_base_url;?>add.jpg" alt="Add ">Add New City</a>
 <?php }?> 
									         
 <table width="100%" class="grid" >
   <thead>
  		 <tr>
     		<th>City </th> 
     		<th> State</th>
     		<th> Zip</th>
     		<th> Status</th>
     		<th>Action</th> 
         </tr>
    </thead> 
     <?php if(isset($states)){
     	$i = 0;
      	foreach ($cities as $city){
     		$i = ($i == 0) ? 1 : 0;
     		echo '<tr class="'.togglerow($i) .'">
     			  	  <td>' . $city['name'] . '</td>
     			  	  <td>' .getState($city['stateid']) . '</td>
     			  	  <td>' . $city['zip'] . '</td><td>'
     			  	  ;
     			  	  echo ($city['isenabled']) ? 'Active' : 'Blocked';
     			  	  echo '</td>
	     			  <td>
	     			  		<a href="'. $base_url . 'admin/managecity/edit/' .$city['id'] . '">
	     			  		<img src="'.$image_base_url.'edit.jpg" alt="Edit"></a> &nbsp;&nbsp;&nbsp;
	     			  		<a href="javascript:void(0)" onclick="var f= confirm(\'Are you sure !! Delete city ' . $city['name'] . '\'); if(f) location.href=\''. $base_url . 'admin/managecity/delete/' .$city['id'] . '\';else return false;" >
	     			  		<img src="'.$image_base_url.'delete.jpg" alt="Delete"></a></td>
	     		</tr>';
     	}
     }?>
    
 </table>