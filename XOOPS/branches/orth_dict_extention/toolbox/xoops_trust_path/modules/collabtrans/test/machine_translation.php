<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/machine_translation.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/translation_path.php';

echo '<p>TranslationManager Test start</p>';
try{

echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;
$pathes = TranslationPath::findAll(getLoginUserUID(), "ja", "en");
$pathIdAry = array();
foreach($pathes as $pa) $pathIdAry[] = $pa -> getPathId();
$defPath = DefaultTranslationPath::craeteFromTranslationPath($pathes[0]);
$defPath -> insert();



$translations = MachineTranslation::translateAll("ja", "en", "今日");

assertEquals(count($pathes), count($translations));
foreach($translations as $tran) {
	assertEquals(true, in_array($tran->getPathId(), $pathIdAry));
	if($defPath -> getPathId() == $tran->getPathId()) {
		assertEquals(true, $tran->isDefault());
	} else {
		assertEquals(false, $tran->isDefault());
	}
}
 
	

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
echo '<br>';
?>
