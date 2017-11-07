<?php 	$this->load->helper('custom');?>
<div class="pagecontent">
          <form action="<?php echo config_item('base_url');?>admin/commonpagetemplate" method="POST" name="templateidchooser">
	         Select Template Page:  <select name="templateid" id="templateid" onchange="document.templateidchooser.submit()">
	                 <?php 
	                 $pages .= '<option value="">-- -- -- -- -- </option>';
					 echo makeOptionSelected($pages , $templateid);
	                 ?>
	                  
	                  
	                  
	           </select>
	           
	          
	          
           </form> 
           <br>
           
           
          <form action="<?php echo config_item('base_url');?>admin/commonpagetemplate" method="POST">
	           <input type="hidden" name="templateid" value="<?php echo $templateid;?>">
	            
	           <?php if($content != ''){ ?>
	           <textarea name="contenthtml" id="contenthtml" cols="70" rows="20"><?php echo $content;?></textarea>
	           <br><a href="javascript:toggleEditor('contenthtml')">Toggle HTML/Plain Mode</a>
	           <?php }?>
	           <br>
	             
	          
           </form> 
           
           
           
           
</div>