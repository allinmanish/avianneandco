<?php 
 $this->load->helper('custom');
?>
<form method="POST" name="pageForm" action="<?=$base_url?>admin/managestate"> 
<div>
	<h1 class="hbb">Manage States</h1>
	<table class="adminlist">
		<tr>
			<td colspan="4">
				<input type="submit" name="activebutton" value="Active" class="button"/>
				<input type="submit" name="blockbutton" value="Block" class="button"/>										
			</td>
		</tr>
		<tr>			
			<th><input type="checkbox" name="checkall" id="checkall" onclick="checkAll(<?php echo sizeof($states)?>)" value=""/></th>			
			<th><?php echo createsortlink($base_url.'admin/managestate/0/20', 'name' , $orderby, '','State Name')?></th>
			<th>Abbreviation</th>
			<th>Status</th>
		</tr>
		<tr>
			
		<?php
			$cc = 0;
			foreach ($states as $state)
			{
				$status = 	($state['isenabled'] == '0') ? 'Blocked' : 'Active' ;
				$cols = '<td><input type="checkbox" name="checksingle[]" value="'.$state['id']  . '"></td>
						<td>'.$state['name'] .'</td>
						<td>'.$state['abbrev'].'</td>
						<td>'.$status .'</td>';
							
				$cc = ($cc== 1) ? 0 : 1;
				
				if($state['isenabled'] == '0') 
					echo '<tr' . $cc .' style="background-color :#faa202;">'.$cols.'</tr>';
				else
					echo '<tr' . $cc .'">'.$cols.'</tr>';						
									
			}
		?>
		
		</tr>
		<tr>
			<td colspan="4"><?php echo $paginglink; ?></td>
		</tr>
		
		<tr>
			<td colspan="4">
				<input type="submit" name="activebutton" value="Active" class="button"/>
				<input type="submit" name="blockbutton" value="Block" class="button"/>								
			</td>
		</tr>
	</table>
</div>
</form>
