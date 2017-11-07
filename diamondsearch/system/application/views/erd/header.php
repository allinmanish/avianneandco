<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo (isset($meta_tags)) ? $meta_tags : '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';?>
<title><?php echo isset($title) ? $title : config_item('site_name'); ?></title>

<link type="text/css" href="<?php echo config_item('base_url') ?>css/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo config_item('base_url') ?>css/tamal.css" rel="stylesheet" />
<link type="text/css" href="<?php echo config_item('base_url') ?>css/ruman.css" rel="stylesheet" />
<link type="text/css" href="<?php echo config_item('base_url')?>css/tabs.css" rel="stylesheet" />
<script>var base_url = '<?php echo config_item('base_url');?>';</script>
<script src="<?php echo config_item('base_url');?>js/jquery.js" type="text/javascript"></script>
<script src="<?php echo config_item('base_url') ?>js/facebox.js" type="text/javascript"></script>
<script src="<?php echo config_item('base_url')?>js/jquery.corner.js" type="text/javascript"></script>
<script src="<?php echo config_item('base_url')?>js/function.js" type="text/javascript"></script>
<script src="<?php echo config_item('base_url')?>js/t.js" type="text/javascript"></script>
<script src="<?php echo config_item('base_url')?>js/r.js" type="text/javascript"></script>


<?php echo isset($extraheader) ? $extraheader : '';?>
<script>jQuery(document).ready(function() {
 <?php echo isset($onloadextraheader) ? $onloadextraheader : '';?>
  $(".roundcorner").corner("round 3px");
  closetimer = 0;if($("#mainmenu")) {
		$("#mainmenu b").mouseover(function() {
		clearTimeout(closetimer);
			if(this.className.indexOf("clicked") != -1) {
				$(this).parent().next().slideUp(300);
				$(this).removeClass("clicked");
			}
			else {
				$("#mainmenu b").removeClass();
				$(this).addClass("clicked");
				$("#mainmenu ul:visible").slideUp(300);
				$(this).parent().next().slideDown(300);
			}
			return false;
		});
		$("#mainmenu").mouseover(function() {
		clearTimeout(closetimer);
		});
		$("#mainmenu").mouseout(function() {
			closetimer = window.setTimeout(function(){
			$("#mainmenu ul:visible").slideUp(300);
			$("#mainmenu b").removeClass("clicked");
			}, 500);
		});
	}

});


</script>
<!--[if lt IE 7]>
<style>
.mainmenu3,.mainmenu2,.mainmenu,.amainmenu3,.amainmenu2,.amainmenu{
margin-left: 5px;
}
.pad10{
padding: 5px;
}
</style>
<![endif]-->
</head>
<body>
<?php if(isset($usetips)){?>
<script type="text/javascript" src="<?php echo config_item('base_url'); ?>third_party/dhtmltooltips/wz_tooltip.js"></script>
<script type="text/javascript" src="<?php echo config_item('base_url'); ?>third_party/dhtmltooltips/tip_balloon.js"></script>
<script type="text/javascript" src="<?php echo config_item('base_url'); ?>third_party/dhtmltooltips/tip_centerwindow.js"></script>
<script type="text/javascript" src="<?php echo config_item('base_url'); ?>third_party/dhtmltooltips/tip_followscroll.js"></script>
<?php }?>
