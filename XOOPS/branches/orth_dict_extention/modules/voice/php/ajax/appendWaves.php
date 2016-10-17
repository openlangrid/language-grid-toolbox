<?php
error_reporting(0);
require_once('../../../../mainfile.php');

$files = json_decode(stripslashes($_POST['files']));
$sentences = json_decode(stripslashes($_POST['sentences']));


//256kbpsなので，0.25秒間停止
define('BYTE_ZEROS', 8192);
//0.25の倍数
define('ZERO_ITER', 2);



//ファイルをひらく
$fpArray = array();

for($i=0; $i<count($files); $i++) { 
	$fpArray[] = fopen('../../wav/'.$files[$i], 'rb');
}
$now = getMTime();
$filename = $now.'_all.wav';
$outfp = fopen('../../wav/'.$filename, 'wb');


//ヘッダ生成
$asciiArray = array();
$sizeArray = array();
for($i=0; $i<count($files); $i++) {
	$buf = fread($fpArray[$i], 56);
	$asciiArray[] = getASCII($buf);
	$sizeArray[] = getSize($asciiArray[$i]);
}
$wholeSize = array_sum($sizeArray)+(count($sizeArray)-1)*BYTE_ZEROS*ZERO_ITER+56;

$header = makeheader($wholeSize, $asciiArray[0]);
$headerStr = getString($header);

//書き込み
fwrite($outfp, $headerStr);


$zeroArray = array();
for($i=0; $i<BYTE_ZEROS; $i++) {
	$zeroArray[] = 0;
}
$zeroStr = getString($zeroArray);

//波形書き込み
for($i=0; $i<count($files); $i++) {
	while(!feof($fpArray[$i])) {
		$wave = fread($fpArray[$i], 8192);
		fwrite($outfp, $wave);
	}
	
	if($i!=count($files)-1) {
		for($j=0; $j<ZERO_ITER; $j++) {
			//空白
			fwrite($outfp, $zeroStr);	
		}
	}
}

//ファイルを閉じる
for($i=0; $i<count($files); $i++) {
	fclose($fpArray[$i]);
}
fclose($outfp);

echo $filename;

function makeheader($wholeSize, $temp) {
	$ret = $temp;
	
	$size1 = $wholeSize-8;
	$size2 = $wholeSize-56;
	
	//8桁の16進数にする
	$sizeStr1 = dechex($size1);
	while(strlen($sizeStr1)<8) {
		$sizeStr1 = '0' . $sizeStr1;
	}
	
	$sizeStr2 = dechex($size2);
	while(strlen($sizeStr2)<8) {
		$sizeStr2 = '0' . $sizeStr2;
	}
	
	$ret[7] = substr($sizeStr1, 0, 2);
	$ret[6] = substr($sizeStr1, 2, 2);
	$ret[5] = substr($sizeStr1, 4, 2);
	$ret[4] = substr($sizeStr1, 6, 2);
	
	$ret[55] = substr($sizeStr2, 0, 2);
	$ret[54] = substr($sizeStr2, 2, 2);
	$ret[53] = substr($sizeStr2, 4, 2);
	$ret[52] = substr($sizeStr2, 6, 2);
	
	return $ret;
}

//波形部分のbyte数
function getSize($header) {
	return 	 256*256*256*hexdec($header[7])
			+    256*256*hexdec($header[6])
			+        256*hexdec($header[5])
			+            hexdec($header[4])
			-48;
}

function getASCII($str) {
	$ret = array();
	for($i=0; $i<strlen($str); $i++) {
		$ret[] = dechex(ord(substr($str, $i, 1)));
	}
	
	return $ret;
}

function getString($array) {
	$ret = '';
	
	for($i=0; $i<count($array); $i++) {
		$ret.= chr(hexdec($array[$i]));
	}
	
	return $ret;
}

function getMTime() {
	$time = microtime();
	
	$split = explode(' ', $time);
	
	$mili = substr($split[0], 2, 4) ;
	
	return $split[1] . $mili; 
}


?>