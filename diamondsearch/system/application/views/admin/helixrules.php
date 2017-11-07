<fieldset>
<legend>Helix Rules : - From Rapnet</legend>
 	
 <form action="<?php echo config_item('base_url');?>admin/helixrules" method="POST">
	 	<p><font color="red">
	 	 	Include Sellers rapnet id seperating with (,) and no new line and space between them. <font color="Green"><b>Green Sellers</b></font>
	 	</font></p><br>
 		<textarea name="helixinclude" style="width: 1000px; height: 100px;"><?php echo isset($helixinclude) ? $helixinclude : '';?></textarea> 
 		
 		<br><br><p><font color="red">
	 		Exclude Sellers rapnet id seperating with (,) and no new line and space between them. Those are the sellers id, who's diamonds are not listed.<br> Those are <font color="Red"><b>Red Sellers</b></font>.
	 	</font></p><br>
 		<textarea name="helixexclude" style="width: 1000px; height: 100px;"><?php echo isset($helixexclude) ? $helixexclude : '';?></textarea> 
 		
 		<br><br><input type="submit" name="savehelixrules" value="Save Helix Rules" class="tbutton3" >
 </form>
</fieldset>

 