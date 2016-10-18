<?php

$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];
$glossaryDictionary = @$_GET['temp_dict_1'] ? $_GET['temp_dict_1'] : @$_POST['temp_dict_1'];


$gloassaries = implode(", " ,$glossaryDictionary);


$_GET['temp_dict_1'] = $gloassaries;
require_once (XOOPS_ROOT_PATH.'/modules/langrid/ajax/save-storefront-setting.php');
$xoopsTpl->assign('glossaryDictionary', $gloassaries);

// TODO get glossary dictionary


?>