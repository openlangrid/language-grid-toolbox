<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
$mytrustdirname = "collabtrans";
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/translation_path.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';

try{

//DefaultTranslationPath::truncate('DefaultTranslationPath');

echo '<p>DefaultTranslationPath Test start</p>';
echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;


$path = TranslationPath::findDefault(getLoginUserUid(), 'ja', 'en');
//assertEquals(203, $path -> getPathId());

$defaultPath = DefaultTranslationPath::craeteFromTranslationPath($path);
$defaultPath -> insert();
/*
$path2 = $defaultPath -> getTranslationPath();
assertEquals($path -> getSourceLang(), $path2 -> getSourceLang());
assertEquals($path -> getTargetLang(), $path2 -> getTargetLang());


$defaultPath2 = DefaultTranslationPath::find(getLoginUserUid(), 'ja', 'en');
assertEquals('ja', $defaultPath2 -> getSourceLang());
assertEquals('en', $defaultPath2 -> getTargetLang());


$langs = TranslationPath::getTargetLangs(getLoginUserUid(), 'ja');

*/

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
echo '<br>';
?>
