<div>
		<?php if($action == 'add' || $action == 'edit'){
	       	$this->load->helper('custom','form');
			     	$id         	= isset($id) ? $id : '';
			?>
			<div>
					<h1 class="hbb" align="center">
								<?=ucfirst($action) ?> Template   
					
					</h1>
					
					<br/>
					<div align="center">
					 
						 <form name="" action="<?php echo config_item('base_url');?>admin/edittemplate/<?php echo $action; echo ($action == 'edit') ? '/' .$id : '';?>" method="post">
							   <textarea style="width: 700px;height: 500px;" name="content"><?php echo isset($content) ? $content : '';?>
							   </textarea>
							   <br>
							   <input type="submit"  name="<?=$action;?>btn" value="<?=ucfirst($action);?>" class="adminbutton"  /> <a href="<?php echo config_item('base_url')?>admin/edittemplate" class="adminbutton"> Cancel</a>
							 
							 
						</form>
					</div>
			</div>
			<?php }else{?>
		 
			<div>
					<table id="results" style="display:none; "></table>
			</div>
		<?php }?>
</div>
 

 
