<div  style="width:400px;margin:20px auto;">
	<form action="#" method="POST" id="pageupdate">
		<?php
	
		foreach ($pageinfo as $inpage){
			?>
			Title:
			<textarea id="pagedescription" name="pagedescription" style="width:400px; height:300px"><?php echo $inpage['description']; ?></textarea> 
			<br>
			<div class="floatr" style="text-align:center;width:100px;">
				<input type="button" name="submit" value="Submit" onclick="updatepageinfodata(<?php echo $inpage['pageid'];?>, '<?php echo $inpage['pageposition']; ?>')" />
			</div>
			<div class="clear"></div>
		<?php } ?>
		
	</form>

</div>