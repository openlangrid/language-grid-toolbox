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

	$tbl=$db->prefix("user_sub_profile_data");
	$sql="CREATE TABLE IF NOT EXISTS `".$tbl."` (
  `uid` int(11) NOT NULL,
  `sub1_value` text,
  `sub2_value` text,
  `sub3_value` text,
  `sub4_value` text
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->query($sql);

	$tbl=$db->prefix("user_sub_profiles");
	$sql="CREATE TABLE IF NOT EXISTS `".$tbl."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub1_display` tinyint(1) DEFAULT NULL,
  `sub1_title` text,
  `sub1_length` int(11) DEFAULT NULL,
  `sub1_default` text,
  `sub2_display` tinyint(1) DEFAULT NULL,
  `sub2_title` text,
  `sub2_length` int(11) DEFAULT NULL,
  `sub2_default` text,
  `sub3_display` tinyint(1) DEFAULT NULL,
  `sub3_title` text,
  `sub3_length` int(11) DEFAULT NULL,
  `sub3_default` text,
  `sub4_display` tinyint(1) DEFAULT NULL,
  `sub4_title` text,
  `sub4_length` int(11) DEFAULT NULL,
  `sub4_default` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
$db->query($sql);

$sql="INSERT INTO `".$tbl."` (`id`,
							`sub1_display`, `sub1_title`, `sub1_length`, `sub1_default`,
							`sub2_display`, `sub2_title`, `sub2_length`, `sub2_default`,
							`sub3_display`, `sub3_title`, `sub3_length`, `sub3_default`,
							`sub4_display`, `sub4_title`, `sub4_length`, `sub4_default`)
						VALUES(1,
								0, '[ja]サブ1タイトル[/ja][en]sub1title[/en]', 0, '',
								0, '[ja]サブ2タイトル[/ja][en]sub2title[/en]', 0, '',
								0, '[ja]サブ3タイトル[/ja][en]sub3title[/en]', 0, '',
								0, '[ja]サブ4タイトル[/ja][en]sub4title[/en]', 0, '')";
$db->query($sql);
//	// TABLES (write here ALTER TABLE etc. if necessary)
//
//	// configs (Though I know it is not a recommended way...)
//	$check_sql = "SHOW COLUMNS FROM ".$db->prefix("config")." LIKE 'conf_title'" ;
//	if( ( $result = $db->query( $check_sql ) ) && ( $myrow = $db->fetchArray( $result ) ) && @$myrow['Type'] == 'varchar(30)' ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix("config")." MODIFY `conf_title` varchar(255) NOT NULL default '', MODIFY `conf_desc` varchar(255) NOT NULL default ''" ) ;
//	}
//	list( , $create_string ) = $db->fetchRow( $db->query( "SHOW CREATE TABLE ".$db->prefix("config") ) ) ;
//	foreach( explode( 'KEY' , $create_string ) as $line ) {
//		if( preg_match( '/(\`conf\_title_\d+\`) \(\`conf\_title\`\)/' , $line , $regs ) ) {
//			$db->query( "ALTER TABLE ".$db->prefix("config")." DROP KEY ".$regs[1] ) ;
//		}
//	}
//	$db->query( "ALTER TABLE ".$db->prefix("config")." ADD KEY `conf_title` (`conf_title`)" ) ;
//
//	// 2.x -> 3.0
//	list( , $create_string ) = $db->fetchRow( $db->query( "SHOW CREATE TABLE ".$db->prefix($mydirname."_log") ) ) ;
//	if( preg_match( '/timestamp\(/i' , $create_string ) ) {
//		$db->query( "ALTER TABLE ".$db->prefix($mydirname."_log")." MODIFY `timestamp` DATETIME" ) ;
//	}
//

	return true ;
}
?>