<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a store
// staff to show Q&As to foreign customers at the counter.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //


if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH into mainfile.php' ) ;

$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
require $mydirpath.'/mytrustdirname.php' ; // set $mytrustdirname

require XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/onupdate.php' ;

?>
