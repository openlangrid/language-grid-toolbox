<?php

global $xoopsUser;

header('Content-Type: application/json; charset=utf-8;');

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit;
}

if (@$_POST['mode'] != 'STORE_FRONT') {
	die('Modes are different for Store front.');
}

$resourceName    = @$_GET['resourceName'] ? urldecode($_GET['resourceName']) : urldecode(@$_POST['resourceName']);
$dictionaryNames = @$_POST['user_dict_ids'];

$result = GlossaryList::deleteInsertGlossaryDictionaries($resourceName, $dictionaryNames);

if ($result) {
	$glossaries = GlossaryList::findSelectedDefaultGlossaryDictionaries($resourceName);
	echo implode(',', $glossaries);
} else {
	echo "";
}

?>