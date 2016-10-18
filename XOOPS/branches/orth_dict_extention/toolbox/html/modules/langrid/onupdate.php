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
$mydirname = basename(dirname( __FILE__ ));
$mydirpath = dirname( __FILE__ ) ;
$module = basename(dirname( __FILE__ ));

eval( ' function xoops_module_update_'.$mydirname.'( $module ) { return langrid_onupdate_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'langrid_onupdate_base' ) ) {

function langrid_onupdate_base( $module , $mydirname )
{
	// transations on module update

	global $msgs ;

	// for Cube 2.1
	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'langrid_message_append_onupdate' ) ;
		$msgs = array() ;
	} else {
		if( ! is_array( $msgs ) ) $msgs = array() ;
	}

	$db =& Database::getInstance() ;
	$mid = $module->getVar('mid') ;

	//0.45
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD organization varchar(255) default \'\'');
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD copyright varchar(255) default \'\'');
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD license varchar(255) default \'\'');
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD description text default \'\'');

	$db->query('ALTER table '.$db->prefix("translation_path_setting").' ADD dictionary_flag int(1) NOT NULL default 0');

//	$db->query('
//		CREATE TABLE '.$db->prefix("default_dictionaries").'(
//		  id int(11) NOT NULL auto_increment,
//		  user_id int(8) NOT NULL default 0,
//		  tool_type varchar(64) NOT NULL default \'all\',
//		  bind_global_dict_ids text,
//		  bind_local_dict_ids text,
//		  bind_user_dict_ids text,
//		  create_date timestamp,
//		  edit_date timestamp,
//		  delete_flag char(1) NOT NULL default \'0\',
//		  PRIMARY KEY  (id)
//		) ENGINE=MyISAM
//	');
	$db->query('delete from '.$db->prefix("translation_path_setting").' where translator_service_1 = \'default\'');

	//0.46
	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix('default_dictionary_setting').' (';
	$sql .= '  setting_id     int(11)    NOT NULL auto_increment,';
	$sql .= '  user_id        int(8)     NOT NULL,';
	$sql .= '  set_id         int(11)    NOT NULL,';
	$sql .= '  create_date    int(11),';
	$sql .= '  edit_date      int(11),';
	$sql .= '  delete_flag    char(1)    NOT NULL default \'0\',';
	$sql .= '  PRIMARY KEY    (setting_id)';
	$sql .= ') ENGINE=MyISAM';
	$db->query($sql);

	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix('default_dictionary_bind').' (';
	$sql .= '  setting_id   int(11)         NOT NULL,';
	$sql .= '  bind_id      int(11)         NOT NULL auto_increment,';
	$sql .= '  bind_type    char(1)         NOT NULL,';
	$sql .= '  bind_value   varchar(2000)   NOT NULL,';
	$sql .= '  create_date  int(11),';
	$sql .= '  edit_date    int(11),';
	$sql .= '  delete_flag  char(1)         NOT NULL default \'0\',';
	$sql .= '  PRIMARY KEY  (setting_id,bind_id)';
	$sql .= ') ENGINE=MyISAM';
	$db->query($sql);

	//0.46
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD registered_date varchar(30) default \'\'');
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD updated_date varchar(30) default \'\'');

	return true ;
}

function langrid_message_append_onupdate( &$module_obj , &$log )
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