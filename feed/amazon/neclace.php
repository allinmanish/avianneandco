<?php if(empty($_REQUEST['submit'])):?>
<html>
<head>
<title>Amazon Inventory Feed updater</title>
</head>
<body>

<p>NOTE: Use input file as .txt, Save as output file .txt, not CSV (which is default save option) </p>
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
	$filename = 'tmp/OfficialNewAmazonFeedExcelCompleteCSV-neclace.csv';
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
	$fp = fopen($filename, 'r');
	$outstream = fopen("php://output", "w");
	while ( ($data = fgetcsv($fp, null, "\t", '"')) !== FALSE ) {

//var_dump('<pre>', $data);

		if ($i>2) {
			if (in_array($data[0], array_keys($feed_array))) {
				$data[7] = $feed_array[$data[0]];
				unset($feed_array[$data[0]]);
			} else {
				$data[7] = is_int($data[7]) ? 0 : $data[7];
			}
			if (trim($data[4]) == 'FineNecklaceBraceletAnklet' && trim($data[8]) == 'chain-necklaces' && strtolower(trim($data[5])) == 'child' && empty($data[53])) {
//echo'<hr>'.$i.'<hr>';
				$buf = $data;
				$buf[5] = 'Parent';
				$buf[6] = '';	// ItemPrice
				$buf[7] = '';	// Quantity
				$buf[55] = 'ChainLength';
				$buf[72] = '';	// Length
				$buf[73] = '';	// TotalMetalWeight
				$buf[75] = ''; 	// TotalDiamondWeight
				
				$length_array = array();
				$length = $data[72] - toMm(6);
				for ($j=0; $j<20; $j=$j+2) {
					$length_array[] = round($length + toMm($j));
				}
				
				fputcsv($outstream, $buf, "\t");
				unset($buf);

				for ($j = 0; $j < count($length_array); $j++) {
					$buf = $data;
					$buf[0]  = trim($data[0]) . '-' . $j;
					$buf[1]  = trim($data[1]) . ' - ' . $length_array[$j];
					$buf[5]  = 'Child';
					$buf[53] = $data[0];
					$buf[54] = 'variation';
					$buf[55] = 'ChainLength';
					$buf[72] = $length_array[$j];	// Length
					$buf[6]  = ceil( $data[6] / $data[72] * $length_array[$j] );	// Price
//var_dump($buf[6], $data[6], $data[72], $length_array[$j]);
					$buf[73] = round($data[73] / $data[72] * $length_array[$j], 2);	// TotalMetalWeight
					$buf[75] = round($data[75] / $data[72] * $length_array[$j], 2);	// TotalDiamondWeight
					
					fputcsv($outstream, $buf,"\t");
//var_dump('<hr>', $buf);
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
		$to      = 'avianneandco@gmail.com';
//		$to 	 = 'd.pelogenko@websitemovers.com';
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

function toMm($in) {
	return $in * 25.4;
}
function toIn($mm) {
	return $mm / 25.4;
}
