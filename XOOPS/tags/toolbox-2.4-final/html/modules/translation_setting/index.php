<?php
require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');

$mydirname = basename(dirname(__FILE__));
$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] );
if( file_exists(dirname(__FILE__).'/page/'.$page.'.php')) {
	include dirname(__FILE__).'/page/'.$page.'.php';
} else {
	include dirname(__FILE__).'/page/index.php';
}

require_once(XOOPS_ROOT_PATH.'/footer.php');
?>