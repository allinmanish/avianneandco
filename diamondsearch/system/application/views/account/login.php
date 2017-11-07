<?php $this->load->helper('form');  ?>
<div class="center" style="width: 300px;">
<div class="dbr"></div>
<h1>Administration Login Only</h1>
<div class="pad10">
      			     <?php echo (isset($loginerror)) ? '<div class="error">'. $loginerror.'</div><hr />' : '';?>
	      			 <?php echo form_open('account/signin'); ?>
	           		   	<table class="tborder pad10">
	           		   	<tr><td valign="top">User ID:</td><td> 
	          			<input type="text" name="username" value="<?php echo $username;?>" style="width: 200px;"> <?php echo form_error('username'); ?><br />
	          			</td></tr>
	          			<tr><td valign="top">Password:</td><td>
	          			<input type="password" name="password" value="<?php echo $password;?>" style="width: 200px;"> <?php echo form_error('password'); ?><br /><br />
	          			</td></tr>
	          			<tr><td colspan="2" align="center">  
	          			 <input type="submit" name="loginbtn" value="Signin" class="button"> <a href="<?php echo config_item('base_url')?>account/forgotpassword">Forgot Password</a>
	          			</td></tr></table>
	          		<?php echo form_close();?>
          		</div>	

</div>