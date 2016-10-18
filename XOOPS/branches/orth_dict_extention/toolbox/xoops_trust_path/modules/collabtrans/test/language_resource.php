<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';

require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/language_resource.php';

echo '<p>LanguageResource Test start</p>';
try{
	
$dicts = Dictionary::findAll(array(
	"language" => "ja"
));

foreach($dicts as $dict) {
	assertEquals(true, $dict -> hasLanguage("ja"));
}

$dictNames = Dictionary::getDictionaryNames("ja");

$langs = $dicts[0] -> getLanguages("ja");
assertEquals(false, in_array("ja", $langs));

$langs = $dicts[0] -> getLanguages("en");
assertEquals(false, in_array("en", $langs));

$langs = $dicts[0] -> getLanguages();
assertEquals(true, in_array("ja", $langs));

$langPair = $dicts[0] -> getLanguagesPair("en");

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
	
?>
