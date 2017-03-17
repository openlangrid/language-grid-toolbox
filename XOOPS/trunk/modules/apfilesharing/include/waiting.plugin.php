<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

eval( '

function b_waiting_'.$mydirname.'(){
	return b_waiting_apfilesharing_base( "'.$mydirname.'" ) ;
}

' ) ;

if( ! function_exists( 'b_waiting_apfilesharing_base' ) ) {

function b_waiting_apfilesharing_base( $mydirname )
{
	$xoopsDB =& Database::getInstance();
	$block = array();

	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("apfilesharing_files")." WHERE status=0");
	if ( $result ) {
		$block['adminlink'] = XOOPS_URL."/modules/{$mydirname}/admin/admission.php";
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_WAITINGS ;
	}

	return $block;
}

}

?>