<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

      <title><?php echo config_item('site_name');?></title>
	  <link href="<?php echo config_item('base_url');?>css/style.css" rel="stylesheet" type="text/css" />
	  <link href="<?php echo config_item('base_url');?>css/admin.css" rel="stylesheet" type="text/css" />
	   <link href="<?php echo config_item('base_url');?>css/ruman.css" rel="stylesheet" type="text/css" />
	    <link href="<?php echo config_item('base_url');?>css/tamal.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo config_item('base_url');?>js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo config_item('base_url')?>js/jquery.corner.js" type="text/javascript"></script>
		<script src="<?php echo config_item('base_url')?>js/jquery.ui.js" type="text/javascript"></script>
		<script>var base_url = '<?php echo config_item('base_url');?>';</script>
		<script src="<?php echo config_item('base_url') ?>js/facebox.js" type="text/javascript"></script>
		<script src="<?php echo config_item('base_url') ?>js/function.js" type="text/javascript"></script>
		<script src="<?php echo config_item('base_url') ?>js/admin.js" type="text/javascript"></script>
 
		<?php echo isset($extraheader) ? $extraheader : '';?> 
		<script>jQuery(document).ready(function() {
		 <?php echo isset($onloadextraheader) ? $onloadextraheader : '';?>
		  $(".roundcorner").corner("round 3px");
		  <?php if(isset($success)) echo '$.facebox(\''.$success.'\');';?>  
		  <?php if(isset($error)) echo '$.facebox(\'<div class="error">'.$error.'</div>\');';?>  
		});
		
		
		</script>
     
       
	  <?php if(isset($headerinclude)) echo $headerinclude; ?>
	  <?php if(isset($use_tinymce)) {?>
	  <script language="javascript" type="text/javascript" src="<?php echo config_item('base_url');?>third_party/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak,imagemanager",
		theme_advanced_buttons1_add_before : "save,separator",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_buttons4 : "moveforward,movebackward,absolute,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "/example_data/example_full.css",
	    plugin_insertdate_dateFormat : "%m-%d-%Y",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		external_link_list_url : "example_data/example_link_list.js",
		external_image_list_url : "example_data/example_image_list.js",
		flash_external_list_url : "example_data/example_flash_list.js",
		template_external_list_url : "example_data/example_template_list.js",
		theme_advanced_resize_horizontal : false,
		remove_script_host : false,
		relative_urls : false,
		theme_advanced_resizing : true,
		apply_source_formatting : true,
		spellchecker_languages : "+English=en"
	});
	
	function toggleEditor(id) {
	if (!tinyMCE.getInstanceById(id))
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
	}
		</script>
	  <?php }?>
	 
      
</head>
<body>
<div class="content center" style="width: 1260px;">
 <table align="center" cellpadding="0" cellspacing="0" style="width: 1260px;">
  <tr>
  	<td colspan="2" valign="top">
  		<div class="floatl pad5"><a href="<?php echo config_item('base_url');?>"><img src="<?php echo config_item('base_url')?>images/logo.jpg"></a></div>
  		<div class="pad10 white floatr"><?php echo isset($loginlink) ? $loginlink : '';?></div>
  		<div class="clear"></div>
  	</td>
  </tr>
   
  <tr>
   	<td valign="top">
   	   <div class="page">
 		<div class="pad5">	
 		<h1 class="txtcenter"><a href="<?php echo config_item('base_url');?>admin">Admin Panel</a></h1> <div class="dbr"></div>
         <div class="cbg pad5 roundcorner w195px floatl minh500px"> 
             <?php echo isset($leftmenus) ? $leftmenus : '';?>
         </div>
          
         <div class="cbg pad5 roundcorner floatl minh500px " style="width:1030px; "> 
         
 		
 		 	
 
 
 