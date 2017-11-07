<?php 	
	$this->load->helper('custom');
?>
<div class="pagecontent">

		<form method="POST" action="<?php echo config_item('base_url');?>admin/managesearchresult" name="searchresultform">
		
				Select Global Variable :  <select name="searchkey" id="searchkey" onchange="document.searchresultform.submit()">
										<?php 
										$searchkeys .= '<option value="">-- -- -- -- -- </option>';
	                 					echo makeOptionSelected($searchkeys , $keyid);
										?>
										</select>
				
		</form>
		<br>
		
		<?php if($keyid != ''){?>
		<form method="POST" action="<?php echo config_item('base_url');?>admin/managesearchresult"> 
		
			<input type="hidden" name="keyid" value="<?php echo $keyid;?>">
		
		
			<table>
					<tr>
						<td>Key Filed :</td>
						<td><input type="text" name="keyfield" style="width: 300px;"></td>
					</tr>
					<tr>
						<td>Headline :</td>
						<td><input type="text" name="headline" style="width: 300px;"></td>
					</tr>
					<tr>
						<td>Description :</td>
						<td><textarea name="description" id="description" style="width: 500px;height: 100px;"></textarea></td>
					</tr>
					<tr>
						<td>Page Link :</td>
						<td><input name="pagelink" id="pagelink" style="width: 300px;"></td>
					</tr>
			
			
			</table>
				
				 
		
		</form>
		<?php }?>



</div>