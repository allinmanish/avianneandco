<?php if(empty($_REQUEST['submit'])):?>
<html>
<head>
<title>Amazon Inventory Feed updater</title>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset style="width:300px;">
			<legend>Amazon Inventory Feed file</legend>
			<input type="file" name="amazon" value="" /><br />
			<input type="submit" name="submit" value="submit" />
		</fieldset>
	</form>
</body>
</html>
<?php else: 
	ini_set("auto_detect_line_endings", true);
	$filename = 'tmp/OfficialNewAmazonFeedExcelCompleteCSV.csv';
	if(is_uploaded_file($_FILES["amazon"]["tmp_name"])) {
		if (file_exists($filename)) {
			unlink($filename);
		}
		move_uploaded_file($_FILES["amazon"]["tmp_name"], $filename);
	} else {
		die("File upload error");
	}
	$feed_array = array();
	$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/AviannePovadaMolds.csv', 'r');
	while ( ($data = fgetcsv($handle,null,",")) !== FALSE) {
		$feed_array[$data[0]] = $data[1];
	}
	fclose($handle);
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . array_pop(explode("/", $filename)));
	header("Pragma: no-cache");
	header("Expires: 0");
	$i=0;
//	$size_array = array(5,6,7,8,9,10,11,12);
	$size_array = array(5,5.5,6,6.5,7,7.5,8,8.5,9,9.5,10,10.5,11,11.5,12,12.5);
	$fp = fopen($filename, 'r');
	$outstream = fopen("php://output", "w");
	while ( ($data = fgetcsv($fp,null,",",'"')) !== FALSE) {
		if ($i>1) {
			if (in_array($data[0], array_keys($feed_array))) {
				$data[7] = $feed_array[$data[0]];
				unset($feed_array[$data[0]]);
			} else {
				$data[7] = 0;
			}
			if ($data[4] == 'FineRing' && strtolower($data[5]) == 'child' && empty($data[53])) {
				$buf = $data;
				$buf[5] = 'Parent';
				$buf[6] = '';
				$buf[7] = '';
				$buf[55] = 'RingSize';
				$buf[89] = '';
				$buf[188] = '';
				fputcsv($outstream, $buf,"\t");
				unset($buf);
				for ($i = 0; $i < count($size_array); $i++) {
					$buf = $data;
					$buf[0] = trim($data[0]) . '-' . $i;
					$buf[1] = trim($data[1]) . ' - ' . $size_array[$i];
					$buf[5] = 'Child';
					$buf[53] = $data[0];
					$buf[54] = 'variation';
					$buf[55] = 'RingSize';
					$buf[89] = $size_array[$i];
					fputcsv($outstream, $buf,"\t");
					unset($buf);
				}
			} else {
				fputcsv($outstream, $data,"\t");
			}
		} else {
			fputcsv($outstream, $data,"\t");
		}
		$i++;
	}
	fclose($outstream);
	fclose($fp);
	if(count($feed_array)>0) {
		//$to      = 'i.gnedych@websitemovers.com';
		$to      = 'avianneandco@gmail.com';
		$subject = 'Amazon Inventory Feed file updated';
		$message = 'Amazon Inventory Feed file was updated successfully.
		
Items that are not presented on the Amazon file but exist on AviannePovadaMolds:
'.implode(", ", array_keys($feed_array));
		$headers = 'From: webmaster@avianneandco.com' . "\r\n" .
				'Reply-To: noreply@avianneandco.com' . "\r\n";
		
		mail($to, $subject, $message, $headers);
		exit();
	}
endif;