<?php
require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');

//if (strpos(strtolower(PHP_OS), 'win') === false) {
//	echo 'りな';
//} else {
//	echo 'げいつ';
//}
//die();

require_once(dirname(__FILE__).'/php/langrid-client.php');
echoTime("全体の開始");

$client =& new LangridClient();
//$client->setSourceLanguage('en');
//$client->setTargetLanguage('ja');
//$client->setApplicationName('翻訳の実行速度計測');
//$dist =& $client->translate('The source text and source language is empty.');

$client->setSourceLanguage('ja');
$client->setTargetLanguage('en');
$client->setApplicationName('翻訳の実行速度計測');
$dist =& $client->translate('入力文章と翻訳元言語は空である。');

print_r($dist['contents']);

require_once XOOPS_ROOT_PATH . "/footer.php";

echoTime("全体の終了");


function echoTime($label = "") {
list($micro, $Unixtime) = explode(" ", microtime());
$sec = $micro + date("s", $Unixtime); // 秒"s"とマイクロ秒を足す
echo $label.':'. date("Y-m-d g:i:", $Unixtime).$sec;
echo '<br>';
}
?>