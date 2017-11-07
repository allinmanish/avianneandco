<div  style="width:400px;margin:20px auto;">
	<form action="#" method="POST" id="pageupdate">
		<?php
	
		foreach ($pageinfo as $inpage){
			?>
			Page Title : 
			<input id="pagetitle" type="text" value="<?php echo $inpage['pagetitle']; ?>" name="pagetitle" />
			<br>
			Page Url:
			<input id="httpaddress" style="width:200px;" type="text" value="<?php echo $inpage['httpaddress']; ?>" name="httpaddress" />
			<br>
			<div class="floatr" style="text-align:center;width:100px;">
				<input type="button" name="submit" value="Submit" onclick="pageinfoupdate(<?php echo $inpage['pageid']; ?>)" />
			</div>
			<div class="clear"></div>
		<?php } ?>
		
	</form>

</div>