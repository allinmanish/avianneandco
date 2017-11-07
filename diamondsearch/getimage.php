<?php

	$imgfile = '../skin/frontend/blank/theme081/images/'.$_GET['img'];
	if (file_exists($imgfile)) header('Location: ../../' . $imgfile);

	$ch = curl_init('http://directloosediamonds.com/images/'.$_GET['img']);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	$header = curl_exec($ch);
	preg_match('/Content-Type:(.*)\n/', $header, $match);

	$ch = curl_init('http://directloosediamonds.com/images/'.$_GET['img']);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$content = curl_exec($ch);

	$imgfile = 'images/'.$_GET['img'];
	@mkdir(dirname($imgfile), 0777, true);
	if (!file_exists($imgfile)) {
		$f = fopen($imgfile, 'w');
		fwrite($f, $content);
		fclose($f);
		if (!getimagesize($imgfile)) unlink($imgfile);
	}

	header('Content-Type: ' . $match[1]);
	header('Content-Length: ' . strlen($content));
	echo $content;

?>