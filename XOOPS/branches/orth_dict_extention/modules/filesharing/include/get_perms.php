<?php
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$global_perms = 0 ;
/*
if( is_object( $xoopsDB ) ) {
	if( ! is_object( $xoopsUser ) ) {
		$whr_groupid = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
	} else {
		$groups = $xoopsUser->getGroups() ;
		$whr_groupid = "gperm_groupid IN (" ;
		foreach( $groups as $groupid ) {
			$whr_groupid .= "$groupid," ;
		}
		$whr_groupid = substr( $whr_groupid , 0 , -1 ) . ")" ;
	}
//	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$filesharing_mid' AND gperm_name='filesharing_global' AND ($whr_groupid)" ) ;
	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." LEFT JOIN ".$xoopsDB->prefix("modules")." m ON gperm_modid=m.mid WHERE m.dirname='$mydirname' AND gperm_name='filesharing_global' AND ($whr_groupid)" ) ;
	while( list( $itemid ) = $xoopsDB->fetchRow( $rs ) ) {
		$global_perms |= $itemid ;
	}
}
*/
$global_perms |= 1;
$global_perms |= 3;
$global_perms |= 12;
$global_perms |= 48;
$global_perms |= 256;
$global_perms |= 768;
$global_perms |= 1024;

?>