<?php
require_once dirname(__FILE__).'/../../class/shop_header.php';

$renderOption['type'] = 'noheader';

$resourceName = urldecode(@$_GET['resourceName']);

require_once (dirname(__FILE__). '/_list.php');

$shopHeader = new ShopHeader("category", $resourceName);

$xoopsTpl->assign(array(
	'mod_url' =>  XOOPS_MODULE_URL.'/'.$GLOBALS['mydirname'],
	'header' => $shopHeader->getHeader(),
	'divHeader' => $shopHeader->getDivHeader()
));

