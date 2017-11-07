<?php
/*

© Copyright Webgility LLC 2012
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per 
the terms & conditions of Webgility License Agreement. You may not alter or remove 
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.

File last updated: 08/23/2011
*/
require_once('ecc-config.php'); 
require_once('D.WgCommon.php'); 
$objWgCommon =	new WgCommon();

ini_set("display_errors","On");
error_reporting(E_ALL && ~E_NOTICE && '~E_STRICT');

$urltopost = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$urltopost = str_replace('test.php','ecc-magento.php',$urltopost);

//if(!extension_loaded("curl")){echo 'Curl disabled ! Required it.';}

$response_result =0;
$geterr="";
$status_code="";

$required_ext = false;

//include_once "Varien/Profiler.php";
$functions = array(	"Check Access Information"=>'checkAccessInfo',
					"Get Company Information"=>'getCompanyInfo',
					"Get Category"=>'getCategory',
					"Get Order Status"=>'getOrderStatus',
					"Get Payment Methods"=>'getPaymentMethods',
					"Get Shipping Methods"=>'getShippingMethods',
					"Get Taxes"=>'getTaxes',
					"Get Categories"=>'getCategory',
					"Get Manufacturers"=>"getManufacturers",
					"Get Shipping Methods"=>'getShippingMethods',
					"Get Items"=>'getItems');
					

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "phpinfo":
			phpinfo();
		break;
	}
	die();
}

//print_r($_POST);
$loginTableFalg = true;
if (isset($_POST["btncheck"]) && ($_POST["btncheck"]=="Test" || $_POST["btncheck"]=="Mail To Webgility")) {
	if (trim($_POST["uname"])!="" && trim($_POST["pwd"])!="") {
		$username=trim($_POST["uname"]);
		$password=trim($_POST["pwd"]); 	
		if(isset($_POST['sendmail']) && $_POST['sendmail']=='Y') {
			$subject="eCCtest Information(Cubecart)";
			$mailto = "support@webgility.com";
			$mailfrom = "bugs@webgility.com";
			$headers = "From:".$mailfrom."\r\nContent-Type: text/html; charset=iso-8859-1";
			$body = '<html>
					<head><title>eCC test Report</title></head>
					<body>	
						<table width=644 border=0 cellspacing=2 cellpadding=0 align=center>
							<tr> 
								<td width=266 height=30  > <p >StoreType:</p></td>
								<td width=372 height=30 ><b>Cubecart</b></td>
							</tr>
							<tr> 
								<td  height=30 >eCC test URL:</td>
								<td height=30 ><b> '. $_SERVER['HTTP_REFERER'] .'</b></td>
							</tr>
							<tr bgcolor=#E2E6E9> 
								<td height=25 colspan=2 align=center bgcolor=#E2E6E9 class=Text8PTGreyColor> 
									<div align=center><p>Copyright &copy; 2008-2009 Webgility LLC</p></div>
								</td>
							</tr>
							<tr><td colspan=2></td></tr>
						</table>
					</body>
				</html>';
				@mail($mailto, $subject, $body, $headers);
		}
		ini_set("max_execution_time",5000);
		
		$arr['method']						=	'checkAccessInfo';
		$arr['username']					=	$_POST['uname'];
		$arr['password']					=	$_POST['pwd'];
		$obj = $objWgCommon->response($arr);
		/*?>
		<script src="http://code.jquery.com/jquery-1.5.js"></script>
		<script language="javascript">
		$.post("http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php", { request: "<?php echo trim($obj);?>" },
		function(data) {
		 <?php $contents = '<script>data</script>';?>
		});
		</script>
		<?php*/
		//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
		$datatopost = array("request" => $obj);
		
		$ch = curl_init ($urltopost);
		//print_r($ch);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec ($ch);
		curl_close($ch);
		
		$contents = $objWgCommon->getRequestData($contents);
		$contents = json_decode($contents,true);
		foreach($contents as $k=>$v) {
			$returnArray[$k]	=	$v;
		}
		$status_code	=	$returnArray['StatusCode'];					
		
		if ($returnArray['StatusCode'] != 0) {
			$error	=	$returnArray['StatusMessage'];
		} else {
			
			$companyinfoFlag = 1;
			$response_result=1;
			
			$arr['method']						=	'getCompanyInfo';
			$arr['username']					=	$_POST['uname'];
			$arr['password']					=	$_POST['pwd'];
			
			
			$obj = $objWgCommon->response($arr);	
			
			//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
			$datatopost = array("request" => $obj);
			
			$ch = curl_init ($urltopost);
			//print_r($ch);
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec ($ch);
			curl_close($ch);
			
			$contents = $objWgCommon->getRequestData($contents);
			$contents = json_decode($contents,true);
			//print_r($contents);
			//die();
			foreach($contents as $k=>$v) {
				$returnArray[$k]	=	$v;
			}
			
			$msg	=	$returnArray['StatusMessage'];
			
			if($returnArray['StatusCode'] == 0) {	
				$loginTableFalg = false;
			}
			/*?><pre><?php print_r($returnArray);?></pre><?php */
		}
	} else {
		echo "<br><center><strong>Please enter username and password!</strong> </center>";
	}	
} else if(isset($_POST["UpdateItem"]) && ($_POST["UpdateItem"]=="Update")) {

	$arr['method']			=	'synchronizeItems';
	$arr['username']		=	$_POST['uname'];
	$arr['password']		=	$_POST['pwd'];
	$arr['storeid']			=	0;
	$arr['version']			=	'';
	$arr['ShouldCompress']	=	true;
	
	$price_array			=	$_POST['price_new']; 
	$qty_array				=	$_POST['qty_new'];
	//echo count($qty_array);
	/*print_r($_POST['ProductID']);
	echo '<br/>';
	print_r($qty_array);
	echo '<br/>';*/
	foreach($qty_array as $key=>$valu) {
		if(strlen($valu) > 0) {
			$data[]	=	array('ProductID'=>$_POST['ProductID'][$key], 'Sku'=>'', 'ProductName'=>'', 'Qty'=>$valu, 'Price'=>'', 'Status'=>'', 'ItemVariants'=>'');
			$itemCount++;
		}
	}
	foreach($price_array as $key=>$valu) {
		if(strlen($valu) > 0) {
			$data[]	=	array('ProductID'=>$_POST['ProductID'][$key], 'Sku'=>'', 'ProductName'=>'', 'Qty'=>'', 'Price'=>$valu, 'Status'=>'', 'ItemVariants'=>'');
		}
	}	
	
	//die();
	/*{"data":[{"ProductID":"1","Sku":"TESA31","ProductName":"Test Product","Qty":"-3","Price":10,"Status":"","ItemVariants":null},{"ProductID":"2","Sku":"100","ProductName":"Nokia E70","Qty":"-25","Price":0,"Status":"","ItemVariants":null},{"ProductID":"3","Sku":"100","ProductName":"P4","Qty":"-25","Price":0,"Status":"","ItemVariants":null},{"ProductID":"5","Sku":"AC#","ProductName":"AC#","Qty":"391","Price":0,"Status":"","ItemVariants":null}],}*/
	$arr['data']	=	$data;
	//print_r($arr);die();
	$obj = $objWgCommon->response($arr);	
	
	//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
	$datatopost = array("request" => $obj);
	
	$ch = curl_init ($urltopost);
	//print_r($ch);
	curl_setopt ($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$contents = curl_exec ($ch);
	curl_close($ch);
	
	$contents = $objWgCommon->getRequestData($contents);
	$contents = json_decode($contents,true);
	//print_r($contents);
	//die();
	foreach($contents as $k=>$v) {
		$returnArray[$k]	=	$v;
	}
	//print_r($returnArray);
	$StatusCode		=	$returnArray['StatusCode'];
	$StatusMessage	=	$returnArray['StatusMessage'];
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>eCC Test Page</title> 
<meta name="DATE" content="08/03/2007" /> 
<link rel="icon" href="http://www.webgility.com/favicon.png" type="x-icon" /> 
<style>
html, body {height:100%; border:0; background:none;}
body {background:none font-family:Arial, Helvetica, sans-serif;}
#content {width:1000px; overflow:hidden; background:none; margin:0 auto;}
td{
	font-weight:normal;
	text-decoration:none;
	color:#000000;
	font-size:12px;
    text-align:center;
}
td div{
	margin:10px 0;
}
td input{
	border:1px solid #CCCCCC;
}

.red_tr{ background-color:#FF6600; color:#FFFFFF; padding:5px 5px 5px 5px; margin-top:10px; text-align:left;}
select{
	border:1px solid #CCCCCC;
	color:#000000;
	font-size:12px;
    text-align:left;
}


#wrap {
	text-align:center;
	padding:10px;
	font-size:16px;
	color:#000;
	font-weight: bold;
} 

#footer {
	width:100%;
	text-align:center;
	color:#CCCCCC;
}

</style>

	<script language="javascript1.2" type="text/javascript">
			function checklogin() {
				if (document.frmlogin.uname.value=="" || document.frmlogin.pwd.value=="") {
					alert("Please enter username and password");
					return false;
				}
				return true;
			}
			function validateform() {
			if (document.getElementById('methodname').value == 0) {
				alert("Please Select method name");
				return false;
			} else if(document.getElementById('1').style.display=='block' && document.getElementById('uname').value=='') {
				alert("Please enter user name");
				return false;
			} else if(document.getElementById('2').style.display=='block' && document.getElementById('pwd').value=='') {
				alert("Please enter password");
				return false;
			} else if(document.getElementById('3').style.display=='block' && document.getElementById('start').value=='') {
				alert("Please enter start");
				return false;
			} else if(document.getElementById('4').style.display=='block' && document.getElementById('limit').value=='') {
				alert("Please enter limit");
				return false;
			} else if(document.getElementById('5').style.display=='block' && document.getElementById('start_order_no').value=='') {
				alert("Please enter start order no");
				return false;
			}/*else if(document.getElementById('6').style.display=='block' && document.getElementById('ecc_excl_list').value=='') {
				alert("Please enter ecc excl list");
				return false;
			}*/else if(document.getElementById('7').style.display=='block' && document.getElementById('order_per_response').value=='') {
				alert("Please enter order per response");
				return false;
			}
			return true;
		}

		function showdiv(div) {
			//alert(div);
			document.getElementById(div).style.display = ''; 
		}
		function hidediv(div) {
			document.getElementById(div).style.display = 'none'; 
			//document.getElementById('hideshow')
		}	  
		function showoptions(method) {
			//alert(method);
			var toption = document.getElementById('methodname').options.length;
			for(k=0;k<toption;k++) {
			if(document.getElementById('methodname').options[k].text==method) {
				//alert(document.getElementById('methodname').options[k].index);
				document.getElementById('methodname').selectedIndex = document.getElementById('methodname').options[k].index; 
			}
		}
		switch(method) {
			case "checkAccessInfo": var ids = ["1","2"];break;
			case "getCompanyInfo": var ids = ["1","2"];break;
			case "getCompanyInfo": var ids = ["1","2"];break;
			case "getOrders": var ids = ["1","2","5","6","7"];break;
			case "getItems": var ids = ["1","2","3","4"];break;
			case "getPaymentMethods": var ids = ["1","2"];break;
			case "getCategory": var ids = ["1","2"];break;
			case "getOrderStatus": var ids = ["1","2"];  break;            
			case "getTaxes": var ids = ["1","2"];  break;            
			case "getManufacturers": var ids = ["1","2"];  break;            
			case "getShippingMethods": var ids = ["1","2"];  break;            
			case "getStores": var ids = ["1","2"];   break;
			default: var ids = ["1","2"];  
		}	
		for(j=0;j<=7;j++) {document.getElementById(j).style.display = 'none'; }
		for ( var i in ids ) {document.getElementById(ids[i]).style.display = 'block'; }
	}	

	function viewdiv(id) {
		var ids = ["0","1","2"];  
		for ( var i in ids ) {
			document.getElementById("div"+ids[i]).style.display = 'none'; 
		}
		document.getElementById("div"+id).style.display = 'block'; 
	}
	function valtowrite(id) {
		document.getElementById("txt"+id).focus();
		document.getElementById("txt"+id).select();
	}
</script>
</head>

<body style="font-size:13px;">
		<div id="wrap">eCC Store Module (v<?php echo $storeMduleVersion; ?>) - Test Page</div>
	
		<div id="content" align="center">

			
			<?php 
			if(extension_loaded("curl") && extension_loaded("json") && extension_loaded("mcrypt") && phpversion()>5){ $required_ext = true;}
			
			if(!$required_ext){?>
				<div id="centerdiv">
			<div id="information"> <h2><strong>Required Extensions:</strong></h2></div>
										
										<?php if(extension_loaded("curl") && extension_loaded("json") && extension_loaded("mcrypt") && phpversion()>5) {?>
										<div class="green_tr"><span style="font-size:15px; ">Requirements for store module.</span></div>
										<?php } else {?>	
										<div class="green_tr">You need to enable following extensions to successfully installed service module.</div>
										<?php }?>
										
										<?php if(extension_loaded("curl")){?>
										<div class="green_tr">PHP Curl &nbsp;:&nbsp;<span style=" color:#006600; padding:55px;" > Ok </span></div>
										<?php }else{?>
										<div class="red_tr">PHP Curl &nbsp;:&nbsp; Required. </div>
										<?php } ?>
										
										<?php if(extension_loaded("json")){?>
										<div class="green_tr">PHP Json &nbsp;:&nbsp; <span style=" color:#006600; padding:50px;" > Ok </span> </div>
										<?php }else{?>
										<div class="red_tr">PHP Json &nbsp;:&nbsp; Required. </div>
										<?php } ?>
										
										<?php if(extension_loaded("mcrypt")){?>
										<div class="green_tr">PHP Mcrypt &nbsp;:&nbsp; <span style=" color:#006600; padding:40px;" > Ok </span> </div>
										<?php }else{?>
										<div class="red_tr">PHP Mcrypt &nbsp;:&nbsp; Required. </div>
										<?php } ?>
										
										<?php if(phpversion()>5){?>
										<div class="green_tr">PHP Version <?php echo phpversion();?> &nbsp;:&nbsp; <span style=" color:#006600"> Ok </span> </div>
										<?php }else{?>
										<div class="green_tr">PHP Version <?php echo phpversion();?> &nbsp;:&nbsp; Must be greater than PHP 5.0 </div>
										<?php } ?>
										
										 
								
									<div id="information"> <h2>Needed Information</h2></div>
									<div class="green_tr"> Memory Limit <?php echo "(" . ini_get("memory_limit") .")";?></div>	  
									<div>&nbsp;</div>
				</div>		
			
			<?php } ?>
			
			<?php if($required_ext) {?>
				<!--Content Area-->
				<?php if($_REQUEST['mode']!='debug' && $required_ext){ ?>
				<!--User Name & Password -->
				<?php if($loginTableFalg && $required_ext) { ?>
				<form action="test.php" method="post" name="frmlogin" onSubmit="return checklogin()">
				
					 <table cellpadding="2" cellspacing="0" border="0" width="500" align="center" height="150" style="margin-top:15px; "> 
						<tr>
							<td colspan="2"><span style="color:#009900; font-size:14px;">Store module has been installed successfully.</span></td>
						</tr>
						<tr>
							<td colspan="2">Enter your store admin username and password below</td>
						</tr>
						<tr>
							<td align="left">Username</td>
							<td><input type="text" name="uname" style="border:1px solid #CCCCCC;"/></td>
						</tr>
						<tr>
						<td>Password</td>
						<td><input type="password" name="pwd" style="border:1px solid #CCCCCC;"/></td>
						
						</tr>
						<tr>
							<td colspan="2" ><input type="submit" value="Test" name="btncheck" style="cursor:pointer; font-weight:bold;" class="red_tr"/><input type="hidden" name="mode" value="normal" /></td>
						</tr>
						
				  </table>  
				</form>
				<?php }?>
				<?php if($response_result==1) {
							ob_start();phpinfo();
							$phpinfo = array('phpinfo' => array());
							if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
								foreach($matches as $match)
									if(strlen($match[1])) $phpinfo[$match[1]] = array();
									elseif(isset($match[3])) $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
									else $phpinfo[end(array_keys($phpinfo))][] = $match[2];
							?>
							
							 <table cellpadding="2" cellspacing="0" border="0" width="500" align="center" height="150" style="margin-top:15px; "> 
								
								<tr>
									<td style="text-align:right;" >Status Code</td>
									<td><?php echo $status_code; ?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >Message</td>
									<td style="color:#009900;"><?php echo $msg; ?></td>
								</tr>
								<?php if ($companyinfoFlag==1) { ?>		
								
								<tr>
									<td style="text-align:right;" >Store Name</td>
									<td><?php echo $returnArray['StoreName']; ?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >Store Address</td>
									<td><?php echo $returnArray['Address']." ".$returnArray['city']. $returnArray['State']." ". $returnArray['Country']."&nbsp;". $returnArray['Zipcode'] ; ?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >Phone</td>
									<td><?php echo $returnArray['Phone']; ?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >website</td>
									<td><?php echo $returnArray['Website']; ?></td>
								</tr>
								<?php if($status_code==0) {?>
									
								<tr>
									<td style="text-align:right;" >System</td>
									<td><?php echo "{$phpinfo['phpinfo']['System']}<br />\n"; ?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >Server Software</td>
									<td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
								</tr>
								<tr>
									<td style="text-align:right;" >PHP Version</td>
									<td><?php echo phpversion();?></td>
								</tr>
								<?php if(extension_loaded('mysql')) { ?>        			
								
								<tr>
									<td style="text-align:right;" >mysql Version</td>
									<td><?php echo "{$phpinfo['mysql']['Client API version']}";?></td>
								</tr>
								
								<?php } else { ?>
								
								<tr>
									<td style="text-align:right;" >mysql Version</td>
									<td>Not Found</td>
								</tr>
								<?php }?>
								
								<tr>
									<td colspan="2" headers="10">&nbsp;</td>
								</tr>
								
									<?php  
										$i=0;
										foreach($functions as $key=>$val) {
											if($val=='getItems') {
												$arr['method']					=	'getItems';
												$arr['username']				=	$username;
												$arr['password']				=	$password;
												$arr['start']					=	0;
												$arr['limit']					=	10;
												
												
												$obj = $objWgCommon->response($arr);	
												
												//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
												$datatopost = array("request" => $obj);
												
												$ch = curl_init ($urltopost);
												//print_r($ch);
												curl_setopt ($ch, CURLOPT_POST, true);
												curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
												curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
												$contents = curl_exec ($ch);
												curl_close($ch);
												
												$contents = $objWgCommon->getRequestData($contents);
												$contents = json_decode($contents,true);
												//print_r($contents);
												//die();
												foreach($contents as $k=>$v) {
													$returnArray[$k]	=	$v;
												}
												//return json_encode($returnArray);
												$StatusCode		=	$returnArray['StatusCode'];
												$StatusMessage	=	$returnArray['StatusMessage'];
											} else if($val=='getOrders') {
											
												$arr['method']					=	'getOrders';
												$arr['username']				=	$username;
												$arr['password']				=	$password;
												$arr['datefrom']				=	'2007-01-10';
												$arr['start_order_no']			=	0;
												$arr['ecc_excl_list']			=	'';
												$arr['order_per_response']		=	20;
												
												
												$obj = $objWgCommon->response($arr);	
												$filename = "http://cart.nova1.webgility.com/interspire_6_0_14/admin/ecc/interspire.php?request=".$obj; 
												$handle = fopen($filename, "rb");
												$contents = '';
												while (!feof($handle)) {
													$contents .= fread($handle, 8192);
												}
												fclose($handle);//echo $contents;
												//echo $contents;
												//die();
												$contents = $objWgCommon->getRequestData($contents);
												$contents = json_decode($contents,true);
												//print_r($contents);
												//die();
												foreach($contents as $k=>$v) {
													$returnArray[$k]	=	$v;
												}
												//return json_encode($returnArray);
												$StatusCode		=	$returnArray['StatusCode'];
												$StatusMessage	=	$returnArray['StatusMessage'];
												
											} else {
												
												$arr['method']					=	trim($val);
												$arr['username']				=	$username;
												$arr['password']				=	$password;
												
												$obj = $objWgCommon->response($arr);	
												
												//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
												$datatopost = array("request" => $obj);
												
												$ch = curl_init ($urltopost);
												//print_r($ch);
												curl_setopt ($ch, CURLOPT_POST, true);
												curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
												curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
												$contents = curl_exec ($ch);
												curl_close($ch);
												
												
												$contents = $objWgCommon->getRequestData($contents);
												$contents = json_decode($contents,true);
												//print_r($contents);
												//die();
												foreach($contents as $k=>$v) {
													$returnArray[$k]	=	$v;
												}
												//return json_encode($returnArray);
												$StatusCode		=	$returnArray['StatusCode'];
												$StatusMessage	=	$returnArray['StatusMessage'];
												
											}
											$i++;
											
											?>
											
												<tr>
													<td style="text-align:right;" ><?php echo $key;?></td>
													<td><?php if($StatusCode == 0){ echo '<span style="color:#009900;">Ok</span>'; } else {echo '<span style="color:#FF0000;">Error</span>'; }?></td>
												</tr>
											
											
											<?php
											
										}
										?>
										<tr>
									<td colspan="2" style="padding-top:10px; text-align:center;">
									<form action="test.php" method="post" name="frmlogin">
										<input type="hidden" name="pwd" value="<?php echo $password ;?>"/>
										<input type="hidden" name="uname" value="<?php echo $username;?>"/>
										<input type="hidden" name="sendmail" value="Y"/>
										<input type="submit" value="Mail To Webgility" name="btncheck"  style="cursor:pointer; font-weight:bold;" class="red_tr"/>
									</form>
									</td>
								</tr>
										
		  </table>
										<?php
									}
								}
							} elseif($status_code!=0) { //echo "YES"; ?>
							
							
							 <table cellpadding="2" cellspacing="0" border="0" width="500" align="center" height="150" style="margin-top:15px; "> 
								<!--tr>
									<td colspan="2"><strong>Result</strong></td>
									
								</tr-->
								<tr>
									<td>Error</td>
									<td ><?php echo $error;?></td>
								</tr>
								<tr>
									<td>Error Message</td>
									<td><textarea cols="20" rows="5"><?php echo htmlspecialchars($error, ENT_QUOTES);  ?></textarea></td>
								</tr>
		  </table>
						<?php 
						}
				
				if($status_code=="0") { ?>
					 <table cellpadding="2" cellspacing="0" border="0" width="500" align="center" style="margin-top:15px; "> 
						<tr><td align="left" width="33%">&nbsp;&nbsp;</td></tr>
						<tr>
							<td align="left" width="33%"><a href="http://www.webgility.com" target="_blank">www.webgility.com</a></td>
							<td align="center" width="33%"><a href="mailto:support@webgility.com">email:support@webgility.com</a></td>
							<td align="right" width="33%"><a href="test.php?action=phpinfo">Php Info</a> </td>
						</tr>
					</table>
				<?php }
			} else {
				//print_r($_POST);
			?>
	
			  <form action="test.php" method="post" name="frmlogin" >
			 <table cellpadding="2" cellspacing="0" border="0" width="500" align="center" height="150" style="margin-top:15px; "> 
				<tr>
				  <td align="left">Method</td>
				  <td align="left"><select name="methodname" id="methodname" onChange="showoptions(this.value);">
				  <option id="0" value="0">Select Method</option>		  
				  <option id="checkAccessInfo" value="checkAccessInfo" >checkAccessInfo</option>
				  <option id="getCompanyInfo" value="getCompanyInfo">getCompanyInfo</option>
				  <option id="getOrders" value="getOrders">getOrders</option>
				  <option id="getItems" value="getItems">getItems</option>
				  <option id="getPaymentMethods" value="getPaymentMethods">getPaymentMethods</option>
				  <option id="getCategory" value="getCategory">getCategory</option>
				  <option id="getOrderStatus" value="getOrderStatus">getOrderStatus</option>
				  <option id="getTaxes" value="getTaxes">getTaxes</option>
				  <option id="getManufacturers" value="getManufacturers">getManufacturers</option>
				  <option id="getShippingMethods" value="getShippingMethods">getShippingMethods</option>
				  </select>
				  </td>  
				 <td width="5%" align="center"> <strong><img src="http://www.webgility.com/images/right_16.png" border="0"  /></strong></td>         
				  <td align="left">
					  <table align="center" width="100%">
					  <tr id="1" style="display:none"><td width="100px;">User name</td><td width="5%">:</td><td width="65%"><input type="text" name="uname" id="uname" value="<?php echo $_POST['uname']; ?>"/></td></tr>
					  <tr id="2" style="display:none"><td width="100px;">Password</td><td width="5%">:</td><td width="65%"><input type="password" name="pwd" id="pwd" value="<?php echo $_POST['pwd']; ?>" /></td></tr>
					  <tr id="3" style="display:none"><td width="100px;">Start</td><td width="5%">:</td><td width="65%"><input type="text" name="start" id="start" value="<?php echo $_POST['start']; ?>" /></td></tr>
					  <tr id="4" style="display:none"><td width="100px;">Limit</td><td width="5%">:</td><td width="65%"><input type="text" name="limit" id="limit" value="<?php echo $_POST['limit']; ?>" /></td></tr>
					  <tr id="5" style="display:none"><td width="100px;">Start order no</td><td width="5%">:</td><td width="65%"><input type="text" name="start_order_no" id="start_order_no" value="<?php echo $_POST['start_order_no']; ?>" /></td></tr>
					  <tr id="6" style="display:none"><td width="100px;">Ecc excl list</td><td width="5%">:</td><td width="65%"><input type="text" name="ecc_excl_list" id="ecc_excl_list" value="<?php echo $_POST['ecc_excl_list']; ?>" /></td></tr>
					  <tr id="7" style="display:none"><td nowrap="nowrap">Order per response</td><td>:</td><td width="65%"><input type="text" name="order_per_response" id="order_per_response" value="<?php echo $_POST['order_per_response']; ?>" /></td></tr>	
					  <tr><td colspan="3" width="100%" align="left"><input type="submit" value="Get Result" name="btncheck"  style="cursor:pointer; font-weight:bold;" class="red_tr" onClick="return validateform();" /><input type="hidden" name="mode" value="debug" /></td></tr>	  
					  </table>	  
				  </td>
				  <td width="55%">
				  
				  
				  <?php
				  if(isset($_POST['methodname']) && $_POST['methodname']!='') {
				  
						$val = $_POST['methodname'];	
						switch ($val) {
						
							case "checkAccessInfo":
							case 'getCompanyInfo':
							case 'getPaymentMethods':
							case 'getCategory':
							case 'getOrderStatus':
							case 'getTaxes':
							case 'getManufacturers':
							case 'getShippingMethods':
							
								$arr['method']						=	$val;
								$arr['username']					=	$_POST['uname'];
								$arr['password']					=	$_POST['pwd'];
								
								
								$obj = $objWgCommon->response($arr);	
								$request = $objWgCommon->getRequestData($obj);
								
								
								//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
								$datatopost = array("request" => $obj);
								
								$ch = curl_init ($urltopost);
								//print_r($ch);
								curl_setopt ($ch, CURLOPT_POST, true);
								curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
								curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
								$contents = curl_exec ($ch);
								curl_close($ch);
								
								
								$contents = $objWgCommon->getRequestData($contents);$response = $contents;
								$contents = json_decode($contents,true);
								
								//print_r($contents);
								//die();
								foreach($contents as $k=>$v) {
									$returnArray[$k]	=	$v;
								}
								//print_r($returnArray);
								$StatusCode		=	$returnArray['StatusCode'];
								$result	=	$returnArray['StatusMessage'];
								
								break;
							
							case "getItems":
							
								$arr['method']					=	'getItems';
								$arr['username']				=	$_POST['uname'];
								$arr['password']				=	$_POST['pwd'];
								$arr['start_item_no']			=	$_POST['start'];
								$arr['limit']					=	$_POST['limit'];
								
								$hidden_fieds = '<input type="hidden" name="methodname" value="'.$val.'" />
												<input type="hidden" name="uname" value="'.$_POST['uname'].'" />
												<input type="hidden" name="pwd" value="'.$_POST['pwd'].'" />
												<input type="hidden" name="start" value="'.$_POST['start'].'" />
												<input type="hidden" name="limit" value="'.$_POST['limit'].'" />';
	
								$obj = $objWgCommon->response($arr);	
								$request = $objWgCommon->getRequestData($obj);
								
								//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
								$datatopost = array("request" => $obj);
								
								$ch = curl_init ($urltopost);
								//print_r($ch);
								curl_setopt ($ch, CURLOPT_POST, true);
								curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
								curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
								$contents = curl_exec ($ch);
								curl_close($ch);
								
								
								$contents = $objWgCommon->getRequestData($contents);$response = $contents;
								$contents = json_decode($contents,true);
								
								//print_r($contents);
								//die();
								foreach($contents as $k=>$v) {
									$returnArray[$k]	=	$v;
								}
								//return json_encode($returnArray);
								$StatusCode		=	$returnArray['StatusCode'];
								$result	=	$returnArray['StatusMessage'];
								
								break;
								
							case "getOrders":
							
								$arr['method']					=	'getOrders';
								$arr['username']				=	$_POST['uname'];
								$arr['password']				=	$_POST['pwd'];
								$arr['datefrom']				=	'2007-01-10';
								$arr['start_order_no']			=	$_POST['start_order_no'];
								$arr['ecc_excl_list']			=	$_POST['ecc_excl_list'];
								$arr['order_per_response']		=	$_POST['order_per_response'];
								
								
								$obj = $objWgCommon->response($arr);	
								$request = $objWgCommon->getRequestData($obj);
								
								
								//$urltopost = "http://cart.nova1.webgility.com/cubecart_4_4_1/admin/ecc/cubecart.php";
								$datatopost = array("request" => $obj);
								
								$ch = curl_init ($urltopost);
								//print_r($ch);
								curl_setopt ($ch, CURLOPT_POST, true);
								curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
								curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
								$contents = curl_exec ($ch);
								curl_close($ch);
								
								$contents = $objWgCommon->getRequestData($contents);
								$response = $contents;
								$contents = json_decode($contents,true);
								
								//print_r($contents);
								//die();
								foreach($contents as $k=>$v) {
									$returnArray[$k]	=	$v;
								}
								//return json_encode($returnArray);
								$StatusCode		=	$returnArray['StatusCode'];
								$result	=	$returnArray['StatusMessage'];
								
								break;
							
							default:
								$param=array($_POST['uname'],$_POST['pwd']);
								break;
						
						}  
							
				  }		  
				  ?>
				  
				  
				  <br/><br/>
				  <a href="#" onClick="viewdiv(0);">Result</a> | <a href="#" onClick="viewdiv(1);" >Request</a> | <a href="#" onClick="viewdiv(2);">Response</a>
				  <div id="div0" >
				  <textarea id="txt0" class="resultdiv"><?php echo $result;?></textarea>		  
				  <input value="Select" type="button" onClick="valtowrite('0')"; style="cursor:pointer; font-weight:bold;" class="red_tr"/>
				  </div>		  		  
				  <div id="div1" style="display:none">
				  <textarea id="txt1" class="resultdiv" ><?php echo trim(htmlspecialchars($request, ENT_QUOTES));?></textarea>		  
				  <input value="Select All" type="button" onClick="valtowrite('1')";style="cursor:pointer; font-weight:bold;" class="red_tr" />
				  </div>
				  <div id="div2" style="display:none" >
				  <textarea id="txt2" class="resultdiv"><?php echo $response;?></textarea>		  
				  <input value="Select All" type="button" onClick="valtowrite('2')";  style="cursor:pointer; font-weight:bold;" class="red_tr"/>
				  </div>		  
				  </td>
				</tr>
			  </table>
			  </form>	
			  <form action="test.php?mode=debug" method="post" name="update_store">
				<div>
				<?php
				//echo $_POST['methodname'];
				//print_r($returnArray);
				if($_POST['methodname'] == 'getItems'){echo displayItems($returnArray, $hidden_fieds);} 
				else if($_POST['methodname'] == 'getOrders'){echo displayOrders($returnArray);}
				else if($_POST['methodname'] == 'getCompanyInfo'){echo displayCompanyInfo($returnArray);}
				else if($_POST['methodname'] == 'getPaymentMethods'){echo displayPaymentMethods($returnArray);}
				else if($_POST['methodname'] == 'getCategory'){echo displayCategory($returnArray);}
				else if($_POST['methodname'] == 'getOrderStatus'){echo displayOrderStatus($returnArray);}
				else if($_POST['methodname'] == 'getTaxes'){echo displayTaxes($returnArray);}
				else if($_POST['methodname'] == 'getManufacturers'){echo displayManufacturers($returnArray);}
				else if($_POST['methodname'] == 'getShippingMethods'){echo displayShippingMethods($returnArray);}
			}	
			?>
			<!--End Content Area-->
			<?php }//End required ext check?>
			
			</div>
		</form>	
	</div>
	</div>
	<div style="clear: both;"></div>
	<div id="footer">
		<p>copyright 2011 &copy; webgility llc. all rights reserved.</p>
	</div>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1754661-1']);
	  _gaq.push(['_setDomainName', '.webgility.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</body>
</html>
<script language="javascript1.2" type="text/javascript">
<?php if(isset($_POST['methodname']) && $_POST['methodname']!=='') {echo "showoptions('".$_POST['methodname']."')";}?>
</script>
<?php
function displayItems($returnArray, $hidden_fieds){
/*?><pre><?php print_r($returnArray);?></pre><?php*/

	echo $hidden_fieds;

	$res ='';
	$class ='even';
	$i = 1;
	$countItem = 0;
	$res .= '<table class="grid"><tr class="title"><td align="left">ItemCode</td><td align="left">ItemDescription</td><td align="left">Quantity</td><td>New Quantity</td><td align="left">ListPrice</td><td>New Price</td><td align="left">Weight</td></tr>';
	foreach($returnArray as $Items) {	
	
		if(count($Items) > 0) {
			$countItem++;
			foreach($Items as $Item) {
				if($Item['ItemCode'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';  
														
																
					$res .= '<tr class="'.$class.'"><td align="left"><input type="hidden" name="ProductID[]" value="'.$Item['ItemID'].'" /><input type="hidden" name="Sku[]" value="'.$Item['ItemCode'].'" /><input type="hidden" name="ProductName[]" value="'.$Item['ItemDescription'].'" />'.$Item['ItemCode'].'</td><td align="left">'.$Item['ItemDescription'].'</td><td align="left">'.$Item['Quantity'].'</td><td><input type="text" name="qty_new[]" /></td><td align="left">'.ucfirst($Item['ListPrice']).'</td><td><input type="text" name="price_new[]" /></td><td align="left">'.$Item['Weight'].'</td></tr>';
				}
			}
		}  else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="8">Items not exist !</td></tr>';
		}	
	}
	if($countItem > 0) {
		$res .= '<tr class="title"><td align="right" colspan="6"><input type="submit" name="UpdateItem" value="Update"  style="cursor:pointer; font-weight:bold;" class="red_tr"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;</td></tr>';
	}
	return $res .= '</table>';
	
}

function displayOrders($returnArray) {
	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">OrderId</td><td align="left">Name</td><td align="left">Date</td><td align="left">Status</td><td align="left">Shipping (+)</td><td align="left">Tax (+)</td><td align="left">Discount (-)</td><td align="left">Total</td></tr>';
	foreach($returnArray as $Orders) {	
	
		if(count($Orders) > 0) {
	
			foreach($Orders as $Order) {
				if($Order['OrderId'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$Order['OrderId'].'</td><td align="left">'.$Order['Title'].' '.$Order['FirstName'].' '.$Order['LastName'].'</td><td align="left">'.$Order['Date'].'</td><td align="left">'.$Order['Status'].'</td><td align="left">$'.ucfirst($Order['Charges']['Shipping']).'</td><td align="left">$'.ucfirst($Order['Charges']['Tax']).'</td><td align="left">$'.ucfirst($Order['Charges']['Discount']).'</td><td align="left">$'.$Order['Charges']['Total'].'</td></tr>';
				}
			}
			
		}  else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="8">Orders not exist !</td></tr>';
		}	
	}
	return $res .= '</table>';
}



function displayCompanyInfo($returnArray) {
	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">StoreID</td><td align="left">StoreName</td><td align="left">Address</td><td align="left" width="100px">Fax</td><td align="left">Email</td><td align="left">Website</td></tr>';
	if($returnArray['StoreID'] != '')	 {
		if($class =='poiner')
			$class = 'even';
		else
			$class = 'poiner';
			
		$res .= '<tr class="'.$class.'"><td align="left">'.$returnArray['StoreID'].'</td><td align="left">'.$returnArray['StoreName'].'</td><td align="left">'.$returnArray['Address'].', '.$returnArray['State'].' '.$returnArray['Country'].'-'.$returnArray['Zipcode'].'</td><td align="left">'.$returnArray['Fax'].'</td><td align="left">'.strtolower($returnArray['Email']).'</td><td align="left">'.strtolower($returnArray['Website']).'</td></tr>';
	}
	return $res .= '</table>';
}


function displayPaymentMethods($returnArray){
	/*?><pre><?php print_r($returnArray);?></pre><?php*/ //die('reached');
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">MethodId</td><td align="left">Method</td><td align="left">Detail</td></tr>';
	foreach($returnArray as $PaymentMethods) {	
		if(count($PaymentMethods) > 0) {
			foreach($PaymentMethods as $PaymentMethod) {
				if($PaymentMethod['MethodId'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$PaymentMethod['MethodId'].'</td><td align="left">'.$PaymentMethod['Method'].'</td><td align="left">'.$PaymentMethod['Detail'].'</td></tr>';
				}
			}
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="4">Payment methods not exist !</td></tr>';
		}	
	}
	echo  $res .= '</table>';
}
function displayCategory($returnArray){
	
	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">CategoryID</td><td align="left">CategoryName</td><td align="left">ParentID</td></tr>';
	foreach($returnArray as $Categories) {
	
		if(count($Categories) > 0) {
			
			foreach($Categories as $Category) {
				if($Category['CategoryID'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$Category['CategoryID'].'</td><td align="left">'.$Category['CategoryName'].'</td><td align="left">'.$Category['ParentID'].'</td></tr>';
				}
			}
		
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="3">Categories not exist !</td></tr>';
		}
	}
	return $res .= '</table>';

}
function displayOrderStatus($returnArray){

	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">OrderStatusID</td><td align="left">OrderStatusName</td></tr>';
	foreach($returnArray as $OrderStatuses) {
	
		if(count($OrderStatuses) > 0) {	
			foreach($OrderStatuses as $OrderStatus){
				if($OrderStatus['OrderStatusID'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$OrderStatus['OrderStatusID'].'</td><td align="left">'.$OrderStatus['OrderStatusName'].'</td></tr>';
				}
			}
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="2">Order statuses not exist !</td></tr>';
		}
	}
	return $res .= '</table>';

}
function displayTaxes($returnArray){
	
	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">TaxID</td><td align="left">TaxName</td></tr>';

	foreach($returnArray as $Taxes) {	
	
		if(count($Taxes) > 0) {
	
			foreach($Taxes as $Tax){
			
				if($Tax['TaxId'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$Tax['TaxId'].'</td><td align="left">'.$Tax['TaxName'].'</td></tr>';
				}
			}
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="2">Taxes not exist !</td></tr>';
		}
	}
	return $res .= '</table>';

}



function displayManufacturers($returnArray){

	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">ManufacturerID</td><td align="left">ManufacturerName</td></tr>';
	foreach($returnArray as $Manufacturers) {	
		if(count($Manufacturers) > 0) {
			foreach($Manufacturers as $Manufacturer){
				if($Manufacturer['ManufacturerID'] != '')	 {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$Manufacturer['ManufacturerID'].'</td><td align="left">'.$Manufacturer['ManufacturerName'].'</td></tr>';
				}
			}
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="2">Manufacturer not exist !</td></tr>';
		}
	}
	return $res .= '</table>';

}
function displayShippingMethods($returnArray){
	
	/*?><pre><?php print_r($returnArray);?></pre><?php*/
	$res ='';
	$class ='even';
	$i = 1;
	
	$res .= '<table class="grid"><tr class="title"><td align="left">Carrier</td><td align="left">Methods</td></tr>';
	foreach($returnArray as $ShippingMethods) {	
	
		if(count($ShippingMethods) > 0) {
			$i=0;
			foreach($ShippingMethods as $ShippingMethod) {
				if($ShippingMethod['Carrier'] != '') {
					if($class =='poiner')
						$class = 'even';
					else
						$class = 'poiner';
					$res .= '<tr class="'.$class.'"><td align="left">'.$ShippingMethod['Carrier'].'</td><td align="left">'.$ShippingMethod['Methods'][$i].'</td></tr>';
				}
				$i++;
			}
		
		} else {
			$res .= '<tr class="'.$class.'"><td align="left" colspan="2">Shipping methods not exist !</td></tr>';
		}
		
	}
	return $res .= '</table>';

}
?>

<!--<p><a href="test.php?mode=debug" target="_blank">Debug Mode</a></p>-->