<?php
$xoopsTpl -> assign('mod_url', XOOPS_URL.'/modules/'.$GLOBALS['mydirname']);

require_once dirname(__FILE__).'/../class/shop_header.php';

$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];
$languageManager = new BilingualManager();

if (isset($_GET['mainLanguage'])) {
	$mainLanguage = $_GET['mainLanguage'];
	$languageManager -> setSelectedLanguage($mainLanguage);
}

if (isset($_GET['subLanguage'])) {
	$subLanguage = $_GET['subLanguage'];
	$languageManager -> setSelectedSubLanguage($subLanguage);
}

$shopHeader = new ShopHeader("category",$_GET['resourceName']);

$renderOption['type'] = 'noheader';

if (isset($_GET['url'])) {
	$destUrl = $_GET['url'];
} else {
	$destUrl = XOOPS_URL.'/modules/'.$GLOBALS['mytrustdirname'].'/category/?resourceName=' . $resourceName;
}

redirect_header($destUrl);

?>