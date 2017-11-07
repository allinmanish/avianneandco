<?php 	$this->load->helper('custom');?>
<div class="pagecontent">
          <form action="<?php echo config_item('base_url');?>admin/globalvariables" method="POST" name="globalvariableform">
	         Select Global Variable :  <select name="globalvariableid" id="globalvariableid" onchange="document.globalvariableform.submit()">
	                 <?php 
	                 $globalvariables .= '<option value="">-- -- -- -- -- </option>';
	                 echo makeOptionSelected($globalvariables , $globalvariableid);
	                 ?>
	                  
	                  
	                  
	           </select>
	           
	          
	          
           </form> 
           <br>
           
           
          <form action="<?php echo config_item('base_url');?>admin/globalvariables" method="POST">
	           <input type="hidden" name="globalvariableid" value="<?php echo $globalvariableid;?>">
	            
	           <?php if($content != ''){ ?>
	           <textarea name="contenthtml" id="contenthtml" style="width: 1000px;height: 300px;"><?php echo $content;?></textarea>
	           <br>
	           <input type="submit" value="Save" class="adminbutton" >  
	           <?php }?>
	           
	          
           </form> 
           
           
           
           
</div>