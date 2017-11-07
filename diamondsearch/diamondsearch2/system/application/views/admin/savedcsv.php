<h3 class="bludebackhead"><img src="<?php echo config_item('base_url');?>/images/tamal/minimize.jpg" id="expand" onclick="if(this.src == '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'){this.src = '<?php echo config_item('base_url')?>/images/tamal/minimize.jpg'; $('#csvsaved').show();}else {this.src = '<?php echo config_item('base_url')?>/images/tamal/expand.jpg'; $('#csvsaved').hide();}"> Previous Saved Expots CSV</h3>
<div id="csvsaved" style="display:;padding: 15px;">
<?php
	echo '<table class="report">';
  					searchRecursiveFile(config_item('base_path').'exports');
	echo '</table>';  					

 function searchRecursiveFile($path)
	{		  		 			  
		      $dir_handle = @opendir($path) or die("Unable to open $path");
		      $dirname = end(explode("/", $path));
		      while (false !== ($file = readdir($dir_handle))){if($file!="." && $file!="..")
		      {
		      	if(is_dir($path."/".$file)){
		      			self::searchRecursiveFile($path."/".$file);
		      	}
		      	else{
		      		    $filepath = $path.'/'.$file;
		      			echo "<tr><td style=\"width: 650px;\"><a href=\"".config_item('base_url')."exports/".$file."\" target=\"_blank\">".$file."</a> </td><td style=\"width: 90px;\"> <a href=\"".config_item('base_url')."exports/".$file."\" target=\"_blank\"> Download </a> </td><td style=\"width: 90px;\"> <a href=\"javascript:void(0)\" onclick=\"f= confirm('Are You Sure?');if(f){window.location.href ='".config_item('base_url')."admin/savedcsv/delete/".$file."';}\">Delete</a></td</tr>";}
		      }
		      }
		      			//echo "</ul>\n";echo "</li>\n";
		      			closedir($dir_handle);
		      			
	}
?>

</div>