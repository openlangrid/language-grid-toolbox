<?php

$xoopsTpl -> assign('mod_url', XOOPS_URL.'/modules/'.$GLOBALS['mydirname']);

require_once dirname(__FILE__).'/../../class/shop_header.php';

$shopHeader = new ShopHeader("lang",$_GET['resourceName']);

$renderOption['type'] = 'noheader';

$xoopsTpl -> assign('header',$shopHeader->getHeader());
$xoopsTpl -> assign('divHeader',$shopHeader->getDivHeader());

$xoopsTpl->assign('url',$_SERVER["HTTP_REFERER"]);

require_once dirname(__FILE__).'/_list.php';

?>