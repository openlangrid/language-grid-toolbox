<?php
require_once dirname(__FILE__).'/../../class/shop_header.php';

$resourceName = @$_GET['resourceName'];
$shopHeader = new ShopHeader("question", urldecode($resourceName));

require_once (dirname(__FILE__).'/_list.php');

$xoopsTpl -> assign('header',$shopHeader->getHeader());
$xoopsTpl -> assign('divHeader',$shopHeader->getDivHeader());
