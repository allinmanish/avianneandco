 

<?php 
	$this->load->helper('custom');
?>

 
<?php if(isset($addform)){?>
 <div class="t" style="width: 500px;"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
<form action="<?php echo $base_url;?>admin/managecategory/<?php echo $addform; if($addform == 'edit') echo '/'. $catid;?>" method="POST" name="new_category_form">
	  <div class="formfield">
	    <div class="fieldlabel">
	      Add New category : 
	    </div>
	    <div class="fieldinput1">
	      <input type="text" name="newcategory" id="newcategory" onchange="require('newcategory')" onblur="require('newcategory')" maxlength="200" value="<?php if(isset($newcategory)) echo $newcategory;?>">
	    </div>
	      
	    <div class="clear"></div>
	  </div>
	  
	 <div class="formfield">
	    <div class="fieldlabel">
	      category Parent : 
	    </div>
	    <div class="fieldinput1">
	     <select name="parentcategory" id="parentcategory">
	     <!--<option value="0">Uncatagorized</option>-->
	     	<?php echo makeOptionSelected($categorieslist , $parentid);?> 
	     </select>
	    </div>
	      
	    <div class="clear"></div>
	  </div>
	  
	  
									
	<div id="requirefielderror"></div>
	<center>
		<input class="button" type="button" value="<?php echo ucfirst($addform);?> category" onclick="add_new_category()">
		<input class="button" type="button" onclick="location.href='<?php echo $base_url;?>admin/managecategory';" value="Cancel">
	</center>
	     
 </form>
 
</div></div></div></div></div></div></div></div> <!-- round div -->

 <br> <br>
 <?php } else {?>
 <a href="<?php echo $base_url;?>admin/managecategory/add"><img src="<?php echo $image_base_url;?>add.jpg" alt="Add ">New Category</a>
 <?php }?>
 
									         
 <table width="100%" class="grid" >
   <thead>
  		 <tr>
     		<th>Category </th> <th colspan="2"> Category Parent</th> 
         </tr>
    </thead> 
     <?php if(isset($categories)){
     	$i = 0;
      	foreach ($categories as $category){
     		$i = ($i == 0) ? 1 : 0;
     		echo '<tr class="'.togglerow($i) .'">
     			  <td>' . $category['catname'] . '</td><td>' . CategoryPath($category['catid'] ) . '</td>
     			  <td>
     			  		<a href="'. $base_url . 'admin/managecategory/edit/' .$category['catid'] . '">
     			  		<img src="'.$image_base_url.'edit.jpg" alt="Edit"></a> &nbsp;&nbsp;&nbsp;
     			  		<a href="javascript:void(0)" onclick="var f= confirm(\'Are you sure !! Delete category ' . $category['catname'] . '\'); if(f) location.href=\''. $base_url . 'admin/managecategory/delete/' .$category['catid'] . '\';else return false;" >
     			  		<img src="'.$image_base_url.'delete.jpg" alt="Delete"></a></td></tr>';
     	}
     }?>
    
 </table>