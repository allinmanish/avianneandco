<?php $this->load->helper('custom');?>
<div style="margin: 0px auto; width: 680px;">
	<form action="<?php echo config_item('base_url');?>admin/pricescopestructure" method="POST">
			<table class="report" align="center" width="100%">
					<tr class="bludebackhead">
						<td>Name of ERD Table Field</td>
						<td>Export -- 
						 Export Column Order</td>
						<td>Export Column Name</td>
					</tr>
					
					<?php 
						 $orderoptions = '';
					     for($i=0;$i<30;$i++) $orderoptions .= '<option value="'.$i.'"> '.$i.' </option>';
					     $i = 0;
						 foreach ($structure as $row){
					?>
							<tr <?php if($i%2 == 0) echo 'class="odd"';?>>
								<td><?php echo $row['erdfield'];?></td>
								<td><input type="checkbox" name="isexport<?php echo $i;?>" <?php if($row['isexport']) echo 'checked';?>>
								 -- <select name="exportorder<?php echo $i;?>">
								<?php echo makeoptionselected($orderoptions, $row['exportorder']);?>
								</select></td>
								<td><input type="text" name="exportname<?php echo $i;?>" maxlength="120" value="<?php if(isset($row['exportname'])) echo $row['exportname'];?>"> </td>
							</tr>
					<?php $i++ ;} ?>
					
					
			</table>
			
			<input type="hidden" name="totalrows" value="<?php echo $i;?>">
			<input type="submit" value="Save" class="adminbutton" name="savepricescopest">
	</form>
</div>