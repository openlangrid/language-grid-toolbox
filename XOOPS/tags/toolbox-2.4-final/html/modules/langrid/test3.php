<?php
require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');

require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');

$api = new LangridAccessClient();

$dist = $api->backTranslate('en', 'de', 'How are you?', 'TEXT_TRANSLATION');

echo '<pre>';
print_r($dist);
echo '</pre>';
function echoTime($label = "") {
	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date("s", $Unixtime); // 秒"s"とマイクロ秒を足す
	echo $label.':'. date("Y-m-d g:i:", $Unixtime).$sec;
	echo '<br>';
}
?>