<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
$mytrustdirname = "collabtrans";
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/CTTranslation_manager.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/text-processor.php';
try{
echo '<p>TranslationManager Test start</p>';
echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;

$manager = new TranslationManager();
//$result = $manager -> translateByUserDefault('ja', 'en', 'テスト', 'COLLABTRANS');

//$result = $manager -> translateAsAdmin('ja', 'en', '今日', 'COLLABTRANS');

$result = $manager -> translateByDefault('ja', 'en', '明日', 'COLLABTRANS');
//var_dump($result);
/*
assertEquals('Test', $result['contents'][0] -> result);

$contents= array();
$lang = "ja";
$sentences = preprocessOriginal($lang, "今日はもう眠い。しかしながら楽しい。");
while($sentences != ''){
	$parsed = get_first_sentence($lang, $sentences);
	$contents[]  = $parsed['first'];
	$sentences = $parsed['remain'];
}
echo var_dump($contents);
//$sentences = $parsed['remain'];
*/

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';

} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
echo '<br>';
?>
