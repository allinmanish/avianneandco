<div>
		<?php if($action == 'add' || $action == 'edit'){
	       	$this->load->helper('custom','form');
			 if(isset($details)){
							 	$pricefrom  = isset($details['pricefrom'])? $details['pricefrom'] 		: 0;
							 	$priceto 	= isset($details['priceto'])  ? $details['priceto'] 		: 0;
							 	$rate   	= isset($details['rate']) 	  ? $details['rate'] 	: 1;
							 	
			                    
			                    
			    
			 }else{
			 	 					$pricefrom  =  0;
								 	$priceto 	=  0;
								 	$rate   	= 1;
			    
			 }
			  		$id         	= isset($id) ? $id : '';
			
			?>
			<div>
					<h1 class="hbb" align="center">
								<?=ucfirst($action) ?> Helix Price Rules
					
					</h1>
					
					<br/>
					<div align="center">
					 
						 <form name="" action="<?php echo config_item('base_url');?>admin/helixprice/<?php echo $action; echo ($action == 'edit') ? '/' .$id : '';?>" method="post">
							
						 			<div class="lebelfield floatl">Price From:</div>
									<div class="inputfield floatl">
											<input type="text" name="pricefrom" value="<?php echo $pricefrom;?>" maxlength="15" class="price" /><?php echo form_error('pricefrom'); ?> 											
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Price To:</div>
									<div class="inputfield floatl">
											<input type="text" name="priceto" value="<?php echo $priceto;?>" maxlength="15" class="price" /><?php echo form_error('priceto'); ?> 											
									</div>
									<div class="clear"></div>
									
									
									<div class="lebelfield floatl">Price Rate(%):</div>
									<div class="inputfield floatl">
											<input type="text" name="rate" value="<?php echo $rate;?>" maxlength="10" class="" /><?php echo form_error('rate'); ?> 											
									</div>
									<div class="clear"></div>
									
									<div class="lebelfield floatl">&nbsp;</div>
									<div class=" floatl">
									<input type="submit"  name="<?=$action;?>btn" value="<?=ucfirst($action);?>" class="adminbutton"  /> <a href="<?php echo config_item('base_url')?>admin/helixprice" class="adminbutton"> Cancel</a> 
							 		</div>
									<div class="clear"></div>
									
									
						   
						</form>
					</div>
			</div>
			<?php }else{?>
		 
			<div>
					<table id="results" style="display:none; "></table>
			</div>
		<?php }?>
</div>
 

 
