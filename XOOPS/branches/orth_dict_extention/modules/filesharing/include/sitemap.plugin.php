<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

eval( '

function b_sitemap_'.$mydirname.'(){
	$xoopsDB =& Database::getInstance();

    $block = sitemap_get_categoires_map($xoopsDB->prefix("filesharing_cat"), "cid", "pid", "title", "?page=viewcat&cid=", "title");

	return $block;
}

' ) ;


?>