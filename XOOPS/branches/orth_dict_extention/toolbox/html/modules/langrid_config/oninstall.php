<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
//$mydirname = basename(dirname( __FILE__ ));
//$mydirpath = dirname( __FILE__ ) ;
//$module = basename(dirname( __FILE__ ));
//
//eval( ' function xoops_module_install_'.$mydirname.'( $module ) { return langrid_config_oninstall_base( $module , "'.$mydirname.'" ) ; } ' ) ;
//
//
//if( ! function_exists( 'langrid_config_oninstall_base' ) ) {

function xoops_module_install_langrid_config( $module )
{
	// transations on module update

	global $msgs ;

	// for Cube 2.1
	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst('Langrid_config') . '.Success', 'langrid_config_message_append_oninstall' ) ;
		$msgs = array() ;
	} else {
		if( ! is_array( $msgs ) ) $msgs = array() ;
	}

	$db =& Database::getInstance() ;
	$mid = $module->getVar('mid') ;

	$msgs[] = 'begin by translation binding set name is modify.';

	$rep = array(
		'BBS' => 'SITE',
		'TEXT_TRANSLATION' => 'USER'
	);

	$table = $db->prefix('translation_set');
	$sql = 'UPDATE %s set `set_name` = \'%s\', `update_time` = \''.time().'\' WHERE `set_name` = \'%s\'';

	foreach ($rep as $key => $val) {
		$q = sprintf($sql, $table, $val, $key);
		$msgs[] = $q;
		$db->query($q);
	}

	return true ;
}

function langrid_config_message_append_oninstall( &$module_obj , &$log )
{
	if( is_array( @$GLOBALS['msgs'] ) ) {
		foreach( $GLOBALS['msgs'] as $message ) {
			$log->add( strip_tags( $message ) ) ;
		}
	}
}

//}

?>