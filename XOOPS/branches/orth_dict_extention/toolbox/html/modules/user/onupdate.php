<?php
function xoops_module_update_user()
{
	echo 'START UPDATE MODULES FOR USER.<br />';
//	// transations on module update
//
//	global $msgs ; // TODO :-D
//
//	// for Cube 2.1
//	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
//		$root =& XCube_Root::getSingleton();
//		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . 'user' . '.Success', 'protector_message_append_onupdate' ) ;
//		$msgs = array() ;
//	} else {
//		if( ! is_array( $msgs ) ) $msgs = array() ;
//	}

	$db =& Database::getInstance() ;
//	$mid = $module->getVar('mid') ;

	$tbl = $db->prefix('groups');

	$db->query('ALTER TABLE '.$tbl.' ADD lg_user varchar(255) default \'\';');
	$db->query('ALTER TABLE '.$tbl.' ADD lg_passwd varchar(255) default \'\';');

	// allow user to register
	$db->query('UPDATE '.$db->prefix('config').' SET `conf_value` = 1 WHERE `conf_title` = \'_MI_USER_CONF_ALLOW_REGISTER\'  ');

	return true ;
}
?>