<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a store
// staff to show Q&As to foreign customers at the counter.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require '../../../mainfile.php' ;
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

define('SUB_MODULE', 'contents');

require dirname( __FILE__ ).'/../mytrustdirname.php' ; // set $mytrustdirname
require '../helper.php';
require_all_classes($mytrustdirname);

$allow_actions = array( "_list", "select", "_save" );
$delegator = get_delegator($allow_actions, "list");
$delegator -> execute();

?>
