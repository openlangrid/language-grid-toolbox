<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
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
$mydirname = basename(dirname( __FILE__ ));
$mydirpath = dirname( __FILE__ ) ;
$module = basename(dirname( __FILE__ ));

eval( ' function xoops_module_update_'.$mydirname.'( $module ) { return apfilesharing_onupdate_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'apfilesharing_onupdate_base' ) ) {

function apfilesharing_onupdate_base( $module , $mydirname )
{
	// transations on module update

	global $msgs ;

	// for Cube 2.1
	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'apfilesharing_message_append_onupdate' ) ;
		$msgs = array() ;
	} else {
		if( ! is_array( $msgs ) ) $msgs = array() ;
	}

	$db =& Database::getInstance() ;
	$mid = $module->getVar('mid') ;

	$db->query('ALTER table '.$db->prefix("apfilesharing_cat").' ADD user_id int(11) default 0');
	$db->query('ALTER table '.$db->prefix("apfilesharing_cat").' ADD create_date int(11) default 0');
	$db->query('ALTER table '.$db->prefix("apfilesharing_cat").' ADD edit_date int(11) default 0');

	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix("apfilesharing_cat_permission").'(';
	$sql .= '  cid int(5) unsigned NOT NULL,';
	$sql .= '  permission_type varchar(30) default \'public\',';
	$sql .= '  permission_type_id  int(11) default 0,';
	$sql .= '  read  tinyint(1) default 0,';
	$sql .= '  edit  tinyint(1) default 0,';
	$sql .= '  PRIMARY KEY  (cid)';
	$sql .= ') TYPE=MyISAM';
	$db->query($sql);

	$db->query('ALTER table '.$db->prefix("apfilesharing_files").' ADD description text');
	$db->query('ALTER table '.$db->prefix("apfilesharing_files").' ADD create_date int(11) default 0');
	$db->query('ALTER table '.$db->prefix("apfilesharing_files").' ADD edit_date int(11) default 0');

	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix("apfilesharing_files_permission").'(';
	$sql .= '  lid int(11) unsigned NOT NULL,';
	$sql .= '  permission_type varchar(30) default \'public\',';
	$sql .= '  permission_type_id  int(11) default 0,';
	$sql .= '  read  tinyint(1) default 0,';
	$sql .= '  edit  tinyint(1) default 0,';
	$sql .= '  PRIMARY KEY  (lid)';
	$sql .= ') TYPE=MyISAM';
	$db->query($sql);

	return true ;
}

function apfilesharing_message_append_onupdate( &$module_obj , &$log )
{
	if( is_array( @$GLOBALS['msgs'] ) ) {
		foreach( $GLOBALS['msgs'] as $message ) {
			$log->add( strip_tags( $message ) ) ;
		}
	}

	// use mLog->addWarning() or mLog->addError() if necessary
}

}

?>