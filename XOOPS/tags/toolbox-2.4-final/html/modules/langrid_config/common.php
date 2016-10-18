<?php
function debugLog($message) {
	$a = XOOPS_TRUST_PATH.'/log/toolbox-debug.'.date('Ymd').'.log';

	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date("s", $Unixtime);
	$dt = date("Y-m-d g:i:", $Unixtime).$sec;

	error_log($dt.' - '.$message . PHP_EOL, 3, $a);
}
debugLog('loaded common.php');
function log_debug($message) {
	$a = XOOPS_TRUST_PATH.'/log/lc-toolbox-debug.'.date('Ymd').'.log';

	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date("s", $Unixtime);
	$dt = date("Y-m-d g:i:", $Unixtime).$sec;

	error_log($dt);
	error_log(print_r($message, 1), 3, $a);
}
?>