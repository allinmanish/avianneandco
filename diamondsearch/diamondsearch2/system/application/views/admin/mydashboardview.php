<div>
	<div>
		<h1 class="hbb">My Dashboard</h1>
	</div>  

	<?php
$contractor='';
$customer='';
        foreach ($usersByStatus as $userstatus){
        	if($userstatus['usertype']=='user'){
			 	$contractor .='<li>' . ucfirst(str_replace('waiting', ' Waiting', $userstatus['status'])) .'(' . $userstatus['cnt'] . ')</li>';  }
	 if($userstatus['usertype']=='golduser'){
			 	$customer .='<li>' . ucfirst(str_replace('waiting', ' Waiting', $userstatus['status'])) .'(' . $userstatus['cnt'] . ')</li>';  }
}
?> 
	<div class="bucket">
    	<div class="bucket-top">
        	<h1 class="buckettop">Total Summerry of Contractors</h1>
        </div> 
         <div class="padd10"> 
            <ul class="bulletul">
        		<?php echo	$contractor; ?>			 	
           </ul>
        </div>
        <div class="bucket-cl"></div>
    </div>
    
    
    <div class="bucket">
    	<div class="bucket-top">
        	<h1 class="buckettop">Total Summerry of Customers</h1>
        </div> 
         <div class="padd10"> 
            <ul class="bulletul"> 
			 <?php echo	$customer; ?>
           </ul>
        </div>
        <div class="bucket-cl"></div>
    </div>
    
    <div class="clear"></div>
</div>
