<?php 
/*
===================================
© Copyright Webgility LLC 2007-2012
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per 
the terms & conditions of Webgility License Agreement. You may not alter or remove 
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.

===================================
*/
ini_set("display_errors","off");
require_once('ecc-config.php'); 
if(file_exists('lib/D.WgCommon.php'))
{
	require_once('lib/D.WgCommon.php');
}
if(file_exists('D.WgCommon.php'))
{
	require_once('D.WgCommon.php');
}


class moduleUpgrade extends WgCommon
{
	
	function getVersions($username,$password,$others)
		{

				global $storeMduleVersion;
				$pos = strrpos(strtolower($storeMduleVersion), "c");

				$WgBaseResponse = new WgBaseResponse();	
				if($pos > 0 ){
					$WgBaseResponse->setStatusCode('1');
					$WgBaseResponse->setStatusMessage('Your eCC store module seems to be customized. So please contact webgility support.');
				}
				else if($storeMduleVersion!="")
				{
					$WgBaseResponse->setStatusCode('0');
					$WgBaseResponse->setStatusMessage('All Ok');
					$WgBaseResponse->setVersion($storeMduleVersion);
				}
				else
				{
					$WgBaseResponse->setStatusCode('1');
					$WgBaseResponse->setStatusMessage('Sorry we are unable to detect your eCC store module version. Please contact webgility support.');
				}

				$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;

		}
		
	function UpgradeVersions($username,$password,$others,$url)
		{	

			$application_path	=	getcwd().'/';
			$WgBaseResponse = new WgBaseResponse();	
			if (!is_writable(dirname($application_path."/upgrade.php"))) {
			
					$WgBaseResponse->setStatusCode('1');
					$WgBaseResponse->setStatusMessage('Please provide proper permisions on FTP to upgrade store module.');
					$response=$this->response($WgBaseResponse->getBaseResponse());
					return $response;
					
			} 
			else
			{

					$ignore_file	=	'upgrade.php';
					$cart_name		=	'magento';
					$download_port	=	443;
					
					$backup_dir = $this->get_backup_dir_name();
					
					$dst	=	$application_path.$backup_dir.'/';
							
					if(is_dir($dst)) {
							//delete_directory($dst);
					}
				
					mkdir($dst, 0777);
					chmod($dst, 0777);
					
					$dir_files_array		=	array();//We are using this array to rollback upgrade functionality.
					$upgrade_files_array	=	array();
					$upgrade_files_flag		=	true;
						
					/**************************************************************************/
					/***				Move content in backup directory					***/
					/**************************************************************************/
					$this->recurse_copy($application_path, $dst, $ignore_file, $backup_dir);
					
					/**************************************************************************/
					/***					Code to download zip file						***/
					/**************************************************************************/
					$str	=	base64_decode($url);
					$fp = fopen($application_path.'file.zip', 'w+');
					fwrite($fp, $str);
					fclose($fp);
					/*$url="https://portal.webgility.com/cdb_upgrade.php?cart=18&type=getzip";
					if (!copy($url,'file.zip')) {
					   // echo "failed to copy $file...\n";
						$action_message	=	'Error in copy service module zip file.';
						$style_class	=	'error';
						$code=1;
					}*/
			
					//Code to extract zip file
					$zip = new ZipArchive;
					if ($zip->open('file.zip') === TRUE) {
						$zip->extractTo($application_path);
						$zip->close();
						//echo 'Service module has been successfully upgraded.';
					} else {

						//$this->recurse_copy($dst, $application_path, 'upgrade.php', $backup_dir);//Rollback backup dir to application dir
						$action_message	=	'There seems to be some problem with store module. Please contact webgility support (support@webgility.com).';
						$style_class	=	'error';
						$code=1;
						//exit();
					}
					
					$dir_name='';
					$copy_to_dir=$this->get_dir_names($dir_name);

					foreach($copy_to_dir as $value)
					{
						$final_dir.=$value."/";
					}

					if(!$this->recurse_copy($application_path.$final_dir, $application_path, $ignore_file, $backup_dir))
					{

							//$this->delete_directory($application_path.'com_ecc/');	
							$this->delete_directory($application_path.'file.zip');	
							$action_message	=	'Succesfully updated';
							$style_class	=	'error';
							$code=0;
					}
					else
					{

							$this->recurse_copy($dst, $application_path, 'upgrade.php', $backup_dir);//Rollback backup dir to application dir
							$this->delete_directory($dst);	
							//$this->delete_directory($application_path.'com_ecc/');
							$this->delete_directory($application_path.'file.zip');	
							$action_message	=	'There seems to be some permission issue on your FTP. Please give write permission on installation directory/file(s) and try again.';
							//Error while copying the files. Please contact webgility support.
							$style_class	=	'error';
							$code=1;
					}
					
					
					
			}	
						$WgBaseResponse->setStatusCode($code);
						$WgBaseResponse->setStatusMessage($action_message);
						$response=$this->response($WgBaseResponse->getBaseResponse());
						return $response;
		}			

	function get_dir_names($file_name) {

		static $dir_names_array	=	array();
		if($file_name=="")
		{
			$path_for_folder	=	getcwd().'/';
		}
		else
		{
			foreach($dir_names_array as $value)
			{
				$final=$value."/";
			}
			$path_for_folder	=	getcwd().'/'.$final;		
		}	
		if (is_dir($path_for_folder)) {
			if ($dh = opendir($path_for_folder)) {
				while (($file = readdir($dh)) !== false) {
				
					if(substr($file, 0, strpos($file, '_')) !== 'backup' && ( $file != '.' ) && ( $file != '..' ) && $file!="lib" && $file!="admin" && $file!="images"){
						
						if(is_dir($path_for_folder.$file))
						{	
							array_push($dir_names_array,$file);
							$this->get_dir_names($file);
						}
						
					}
					
				}
				closedir($dh);
			}
			
		}

		return $dir_names_array;
	}
	
	function get_backup_dir_name() {
			
			$temp_dir = 'backup_'.date('m-d-Y');
			$folders_array	=	$this->get_restore_dir_names();
			$i = 1;
			//print_r($folders_array);
			if(is_array($folders_array) && count($folders_array) > 0) {
				foreach($folders_array as $folder_name) {
					if(!in_array($temp_dir, $folders_array)) {
						return $temp_dir;
					}elseif(!in_array($temp_dir.'_'.$i, $folders_array)) {
						return $temp_dir.'_'.$i;
					}
					$i++;
				}
			} else {return $temp_dir;}
		}
		
	function get_restore_dir_names() {
		$dir_names_array	=	array();
		$path_for_folder	=	getcwd().'/';
	
		if (is_dir($path_for_folder)) {
			if ($dh = opendir($path_for_folder)) {
				while (($file = readdir($dh)) !== false) {
					if(filetype($file) !== 'file' && substr($file, 0, strpos($file, '_')) == 'backup'){
						$dir_names_array[]	=	$file;
					}
					
				}
				closedir($dh);
			}
			
		}
		
		sort($dir_names_array);
		
		return $dir_names_array;
	}
//print_r($dir_names_array);
		
		function copyemz($file1,$file2){
			   $contentx =@file_get_contents($file1);
			   $openedfile = fopen($file2, "w");
			   fwrite($openedfile, $contentx);
			   fclose($openedfile);
				if ($contentx === FALSE) {
				$status=false;
				}else $status=true;
			   
				return $status;
				//return copy($file1,$file2);
		} 
		
		function download_zip_by_url($url,$path = NULL) {
			//$url  = 'http://www.example.com/a-large-file.zip';
			//$path = '/path/to/a-large-file.zip';

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
			$data = curl_exec($ch);
		 
			curl_close($ch);
		 
			file_put_contents($path, $data);
		}
		
		function download($url,$file_name = NULL){
		
			global $download_port;
		
		  if($file_name == NULL){ $file_name = basename($url);}
		
		  $url_stuff = parse_url($url);
		  
		  $port = isset($url_stuff['port']) ? $url_stuff['port'] : $download_port;
		
		  $fp = fsockopen($url_stuff['host'], $port);
		  if(!$fp){ return false;}
		
		  $query  = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n";
		  $query .= 'Host: ' . $url_stuff['host'];
		  $query .= "\n\n";
		
		  fwrite($fp, $query);
		
		  while ($tmp = fread($fp, 8192))   {
			$buffer .= $tmp;
		  }
		
		  preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
		  $file_binary = substr($buffer, - $parts[1]);
		  if($file_name == NULL){
			$temp = explode(".",$url);
			$file_name = $temp[count($temp)-1];
		  }
		  $file_open = fopen($file_name,'w');
		  if(!$file_open){ return false;}
		  fwrite($file_open,$file_binary);
		  return true;
		}
		
		function recurse_copy($src, $dst, $ignore_file, $ignore_dir) {
			$dir = opendir($src);
			@mkdir($dst);
			
			$backup_folders_array	=	$this->get_restore_dir_names();
			
			while(false !== ( $file = readdir($dir)) ) {

				if($file == $ignore_file || $file == $ignore_dir){continue;}
			
				if(in_array($file, $backup_folders_array)){continue;}

				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						$this->recurse_copy($src . '/' . $file,$dst . '/' . $file, $ignore_file, $ignore_dir);
					}
					else {
							
							if(!copy($src . '/' . $file,$dst . '/' . $file))
							{
								
								return true;	
								break;			

							}

					}
				}
			}
			closedir($dir);
		} 
		//-------------------------------------------------------------------------------------------------
		// delete folder reccursively
		//-------------------------------------------------------------------------------------------------
		function delete_directory($dirname){
			// check whether $dirname is a directory
			if  (is_dir($dirname))
				// change its mode to 755 (rwx,rw,rw)
				chmod($dirname, 0775);
		
			// open the directory, the script cannot open the directory then stop
			$dir_handle  =  opendir($dirname);
			if  (!$dir_handle)
				return  false;
		
			// traversal for every entry in the directory
			while (($file = readdir($dir_handle)) !== false){
				// ignore '.' and '..' directory
				if  ($file  !=  "."  &&  $file  !=  "..")  {
		
					// if entry is directory then go recursive !
					if  (is_dir($dirname."/".$file)){
							  $this->delete_directory($dirname.'/'.$file);
		
					// if file then delete this entry
					} else {
						  unlink($dirname."/".$file);
					}
				}
			}
			// chose the directory
			closedir($dir_handle);
			
			// delete directory
			rmdir($dirname);
		}
}
if(isset($_REQUEST['request'])) {
	$objWgCommon =	new moduleUpgrade();
	$objWgCommon->parseRequest();

}
?>