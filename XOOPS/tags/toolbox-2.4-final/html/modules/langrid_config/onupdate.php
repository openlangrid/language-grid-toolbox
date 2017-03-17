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

        $msgs[] = 'Starting update.php '.$mid;

	//0.46
	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix('langrid_config_voice_setting').' (';
	$sql .= '  id     int(11)    NOT NULL auto_increment,';
	$sql .= '  user_id        int(8)     NOT NULL,';
	$sql .= '  language         varchar(35)    NOT NULL,';
	$sql .= '  service_id    varchar(255),';
	$sql .= '  PRIMARY KEY    (id)';
	$sql .= ') ENGINE = MYISAM';
        $db->queryF($sql);
        $msgs[] = 'created table.langrid_config_voice_setting';

	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix('langrid_config_ebmt_learning').' (';
	$sql .= '  id  int(11) NOT NULL auto_increment,';
	$sql .= '  token  varchar(128),';
	$sql .= '  ebmt_service  varchar(255) NOT NULL,';
	$sql .= '  user_dictionary_id  int(11) NOT NULL,';
	$sql .= '  user_dictionary_name varchar(255) NOT NULL,';
	$sql .= '  source_lang  varchar(10) NOT NULL,';
	$sql .= '  target_lang  varchar(10) NOT NULL,';
	$sql .= '  status  varchar(50),';
	$sql .= '  create_time int(11),';
	$sql .= '  PRIMARY KEY (id)';
	$sql .= ') ENGINE = MYISAM';
        $db->queryF($sql);
        $msgs[] = 'created table. langrid_config_ebmt_learning';

	$dumpData = array();
	$sql = 'SELECT * FROM '.$db->prefix('langrid_services').' WHERE allowed_app_provision = \'IMPORTED\'';
	$result = $db->query($sql);
	if (!$result) {
		$sql = 'SELECT * FROM '.$db->prefix('langrid_services').' WHERE service_type like \'%IMPORTED%\'';
		$result = $db->query($sql);
	}
	while ($row = $db->fetchArray($result)) {
		$dumpData[] = $row;
	}

	$sql = 'DROP TABLE '.$db->prefix('langrid_services');
	$db->queryF($sql);
	$msgs[] = 'Droped table "langrid_services"';

	$sql = '';
	$sql .= 'CREATE TABLE '.$db->prefix('langrid_services').' (';
	$sql .= '  id int(11) NOT NULL auto_increment,';
	$sql .= '  service_id varchar(255) NOT NULL default \'\',';
	$sql .= '  service_type varchar(128) NOT NULL default \'\',';
	$sql .= '  allowed_app_provision varchar(50) NOT NULL default \'\',';
	$sql .= '  service_name varchar(255) NOT NULL default \'\',';
	$sql .= '  endpoint_url varchar(255) NOT NULL default \'\',';
	$sql .= '  supported_languages_paths TEXT,';
	$sql .= '  organization varchar(255) default \'\',';
	$sql .= '  copyright varchar(255) default \'\',';
	$sql .= '  license varchar(500)  default \'\',';
	$sql .= '  description text,';
	$sql .= '  registered_date varchar(30) default \'\',';
	$sql .= '  updated_date varchar(30) default \'\',';
	$sql .= '  PRIMARY KEY (id)';
	$sql .= ') ENGINE = MYISAM;';
	$db->queryF($sql);
	$msgs[] = 'Created table "langrid_services"';

	$fArr = array(
		'service_id',
//		'service_type',
//		'allowed_app_provision',
		'service_name',
		'endpoint_url',
		'supported_languages_paths',
		'organization',
		'copyright',
		'license',
		'description',
		'registered_date',
		'updated_date'
	);

	$sql = 'INSERT INTO '.$db->prefix('langrid_services').' (`service_type`, `allowed_app_provision`, %s) VALUES (\'%s\', \'%s\', %s)';
	foreach ($dumpData as $row) {
		$fstr = '';
		$vstr = '';
		foreach ($fArr as $f) {
			$fstr .= '`'.$f.'`, ';
			$vstr .= '\''.$row[$f].'\', ';
		}
		$fstr = substr($fstr, 0, -2);
		$vstr = substr($vstr, 0, -2);

		$st = $row['service_type'];
		if ($st == 'IMPORTED_DICTIONARY') {
			$st = 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH';
		}
		if ($st == 'IMPORTED_TRANSLATION') {
			$st = 'TRANSLATION';
		}

		$at = 'IMPORTED';

		$msgs[] = 'registed "IMPORTED" service '.$row['service_name'].' .';
		$db->queryF(sprintf($sql, $fstr, $st, $at, $vstr));
	}

	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD misc_basic_userid varchar(128)');
	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD misc_basic_passwd varchar(128)');

	// since 1.10
	$res = $db->query('ALTER table '.$db->prefix("langrid_config_voice_setting").' ADD set_id INT(11) NOT NULL DEFAULT 0');
	if ($res) {
		$db->query('UPDATE '.$db->prefix("langrid_config_voice_setting").' SET set_id=7');
	}

//	$db->query('ALTER table '.$db->prefix("langrid_services").' ADD allowed_app_provision varchar(50) default \'\'');
//	$db->query('ALTER table '.$db->prefix("langrid_services").' MODIFY COLUMN service_type varchar(128) NOT NULL');
//	$db->query('ALTER table '.$db->prefix("langrid_services").' DROP COLUMN now_active');
//	$db->query('ALTER table '.$db->prefix("langrid_services").' DROP COLUMN create_date');
//	$db->query('ALTER table '.$db->prefix("langrid_services").' DROP COLUMN edit_date');
//	$db->query('ALTER table '.$db->prefix("langrid_services").' DROP COLUMN delete_flag');

	$file = XOOPS_ROOT_PATH.'/modules/langrid_config/class/manager/GlobalServicesLoader.class.php';
	if (file_exists($file)) {
		require_once $file;
		$globalLoader = new GlobalServicesLoader();
		$globalLoader->refresh();
	}

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
