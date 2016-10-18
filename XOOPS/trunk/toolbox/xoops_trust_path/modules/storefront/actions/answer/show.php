<?php
$renderOption['type'] = 'noheader';

// _show
require_once '_show.php';

// header
$header = new ShopHeader(ShopHeader::ANSWER, @$_GET['resourceName']);

$xoopsTpl->assign(array(
	'header' => $header
));
