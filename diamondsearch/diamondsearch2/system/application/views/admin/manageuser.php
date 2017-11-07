<?php 
 $this->load->helper('custom');
?>
<form method="POST" name="pageForm" action="<?=$base_url?>admin/manageuser/<?=$usertype?>"> 
<div>
	<h1 class="hbb">&nbsp;&nbsp;&nbsp;<?= ucfirst($usertype) ?>s</h1>
	<table class="adminlist">
		<tr>
			<td colspan="<?php echo ($usertype == 'contractor') ? 7 : 5 ?>">
				<input type="submit" name="activebutton" value="Active" class="button"/>
				<input type="submit" name="blockbutton" value="Block" class="button"/>
				<input type="submit" name="suspendbutton" value="Suspend" class="button"/>
				<input type="submit" name="deletebutton" value="Delete" class="button" onclick="return confirm('Are you sure delete');"/>								
			</td>
		</tr>
		<tr>			
			<th><input type="checkbox" name="checkall" id="checkall" onclick="checkAll(<?php echo sizeof($users)?>)" value=""/></th>
			<?php  $nameoderby = ($usertype == 'contractor') ? 'businessname' :  'fname'   ;?>
			<th><?php echo createsortlink($base_url.'admin/manageuser/'.$usertype . '/0/5', $nameoderby , $orderby, '','Name')?></th>
			<th><?php echo createsortlink($base_url.'admin/manageuser/'.$usertype . '/0/5', 'userid' , $orderby, '','Email')?></th>
			<th><?php echo createsortlink($base_url.'admin/manageuser/'.$usertype . '/0/5', 'status' , $orderby,'','Status')?></th>
	<?php 
	if($usertype=='contractor')
	{
	  echo '<th>Hourly Rate</th>
		    <th>Per Project Rate</th>' ;
	}
	?>		
			
			<th>State</th>			
		</tr>
		<tr>
			
		<?php
			$cc = 0;
			foreach ($users as $user)
			{
				$companyname = ($user['usertype'] == 'user') ? $user['businessname'] : $user['fname'].' '.$user['lname'] ;
				
				$cc = ($cc== 1) ? 0 : 1;
				echo '<tr' . $cc .'">
							<td><input type="checkbox" name="checksingle[]" value="'.$user['id']  . '"></td>
							<td>'.$companyname.'</td>
							<td>'.$user['userid'].'</td>
							<td>'.$user['status'].'</td>
							';
					if($usertype=='contractor'){
					  echo '<td>'.$user['hourlyrate'].'</td>				
							<td>'.$user['projectrate'].'</td>	
							';
					}
										
					  echo '<td>'.getState($user['state']).'</td>
					</tr>';				
			}
		?>
		
		</tr>
		<tr>
			<td colspan="7"><?php echo $paginglink; ?></td>
		</tr>
		
		<tr>
			<td colspan="7">
				<input type="submit" name="activebutton" value="Active" class="button"/>
				<input type="submit" name="blockbutton" value="Block" class="button"/>
				<input type="submit" name="suspendbutton" value="Suspend" class="button"/>
				<input type="submit" name="deletebutton" value="Delete" class="button" onclick="return confirm('Are you sure delete');"/>								
			</td>
		</tr>
	</table>
</div>
</form>
