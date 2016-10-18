<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/simple_tool_data.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/DictionaryClient.class.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/GlossaryClient.class.php';


echo '<p>Dictionary Test start</p>';
try{
//
//$client = new DictionaryClient();
//$exp = new ToolboxVO_Resource_Expression();
//$exp -> language = 'ja';
//$exp -> expression = 'ほげテストワード';
//$exp2 = new ToolboxVO_Resource_Expression();
//$exp2 -> language = 'en';
//$exp2 -> expression = 'hoge test word';
//
//$client -> addRecord("test dict", array($exp, $exp2));
//
//$dicts = DictionaryItem::findAll(array(
//	"keyword" => "ほげ",
//	"language"    => "ja",
//	"method" => "PREFIX",
//	"targetLanguage" => "en"
//));
//
//assertEquals('hoge test word', $dicts[0] -> getExpressionForTargetLang());

echo '<pre>';
$records = GlossaryItem::findAll(array(
	"sourceLang" => "en",
	"targetLang" => "ja",
	"keyword" => "week",
	"method" => "partial"
));


foreach($records as $r) {
	var_dump($r->getDifinitionPairs());
}


echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>
