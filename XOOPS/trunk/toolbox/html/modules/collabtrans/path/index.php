<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require '../../../mainfile.php' ;
require_once '../config.php';
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

define('SUB_MODULE', 'path');

require dirname( __FILE__ ).'/../mytrustdirname.php' ; // set $mytrustdirname
require '../helper.php';
require_all_classes($mytrustdirname);

$allow_actions = array( "_show", "_set_default", "_target_languages" );
$delegator = get_delegator($allow_actions, "_show");
$delegator -> execute();

?>
