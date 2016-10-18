<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once '../../../mainfile.php';
require_once '../config.php';
require_once '../mytrustdirname.php' ; // set $mytrustdirname
require_once '../helper.php';

// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}


function assertEquals($a, $b, $msg = null) {
	if($a != $b) {
		if($a === null) $a = "null";
		if($b === null) $b = "null";
		throw new Exception("{$msg} Expected was {$a} but was {$b}");
	}
}

?>
