<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require $mydirpath.'/mytrustdirname.php' ; // set $mytrustdirname

eval( ' function xoops_module_update_'.$mydirname.'( $module ) { return d3forum_onupdate_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'd3forum_onupdate_base' ) ) {

function d3forum_onupdate_base( $module , $mydirname )
{
	// transations on module update

	global $msgs ;

	// for Cube 2.1
	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'd3forum_message_append_onupdate' ) ;
		$msgs = array() ;
	} else {
		if( ! is_array( $msgs ) ) $msgs = array() ;
	}

	$db =& Database::getInstance() ;
	$mid = $module->getVar('mid') ;

	//0.33
	$db->query('ALTER table '.$db->prefix($mydirname."_posts").' MODIFY post_order INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_categories").' ADD create_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_categories").' ADD update_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_categories").' ADD delete_flag CHAR(1) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_forums").' ADD create_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_forums").' ADD update_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_forums").' ADD delete_flag CHAR(1) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_topics").' ADD create_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_topics").' ADD update_date INT(11) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_topics").' ADD delete_flag CHAR(1) NOT NULL default 0');
	$db->query('ALTER table '.$db->prefix($mydirname."_posts").' ADD delete_flag CHAR(1) NOT NULL default 0');

	// 0.37
	$db->query('ALTER table '.$db->prefix($mydirname."_forums").' ADD uid INT(11) NOT NULL default 0');

	// 0.39
	$db->query('ALTER table '.$db->prefix($mydirname."_category_access").' ADD `all` tinyint(1) NOT NULL default 0');

	// 0.55
	$db->query('ALTER table '.$db->prefix($mydirname."_categories_body").' MODIFY `description` text');
	$db->query('ALTER table '.$db->prefix($mydirname."_forums_body").' MODIFY `description` text');
	$db->query('ALTER table '.$db->prefix($mydirname."_topics_body").' MODIFY `description` text');
	$db->query('ALTER table '.$db->prefix($mydirname."_posts_body").' MODIFY `description` text');

	// 0.60
	$db->query('
		CREATE TABLE '.$db->prefix($mydirname).'_bbs_correct_edit_history (
		  bbs_id INT(10) NOT NULL,
		  bbs_item_type_cd CHAR(2) NOT NULL,
		  language_code VARCHAR(16) NOT NULL,
		  history_count INT(11) NOT NULL,
		  proc_type_cd CHAR(1) NOT NULL,
		  bbs_text text,
		  user_id MEDIUMINT(8) NOT NULL,
		  create_date INT(11) NOT NULL,
		  delete_flag CHAR(1) NOT NULL DEFAULT \'0\',
		  PRIMARY KEY (bbs_id, bbs_item_type_cd, language_code, history_count)
		) TYPE=MyISAM
	');

	$db->query('ALTER table '.$db->prefix($mydirname."_posts").' ADD update_date INT(11) NOT NULL default 0');

	// 0.96
	$db->query('
		CREATE TABLE '.$db->prefix($mydirname).'_topic_access_log (
		  topic_id int(8) NOT NULL DEFAULT 0,
		  user_id INT(11) NOT NULL DEFAULT 0,
		  last_access_time INT(11) NOT NULL DEFAULT 0,
		  PRIMARY KEY (topic_id, user_id)
		) TYPE=InnoDB
	');

	$db->query('ALTER table '.$db->prefix($mydirname."_posts_body").' ADD update_time INT(11) NOT NULL default 0');

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_post_file (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            post_id INT(11) NOT NULL DEFAULT 0,
            file_name VARCHAR(255) NOT NULL DEFAULT '',
            file_data LONGBLOB NOT NULL DEFAULT '',
            file_size INT(11) NOT NULL DEFAULT 0
            )TYPE=InnoDB";
    $result=$db->query($sql);

	$db->query('ALTER table '.$db->prefix($mydirname."_post_file").' MODIFY file_data LONGBLOB NOT NULL default \'\'');

	//1.01
	$db->query('ALTER table '.$db->prefix($mydirname."_posts").' ADD reply_post_id int(10) default null');
	$db->query('CREATE INDEX idx_post_time ON '.$db->prefix($mydirname."_posts").'(post_time)');
	$db->query('CREATE INDEX idx_post_order ON '.$db->prefix($mydirname."_posts").'(post_order)');
	$db->query('CREATE INDEX idx_reply_post_id ON '.$db->prefix($mydirname."_posts").'(reply_post_id)');

	//1.02
    $sql="CREATE TABLE ".$db->prefix($mydirname)."_posted_notice_config (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id MEDIUMINT(8) NOT NULL,
            language_code VARCHAR(16) NOT NULL,
            notice_type INT(1) NOT NULL DEFAULT 0
            )TYPE=MyISAM";
    $result=$db->query($sql);

	//1.04
    $sql="CREATE TABLE ".$db->prefix($mydirname)."_posted_notice_config (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id MEDIUMINT(8) NOT NULL,
            language_code VARCHAR(16) NOT NULL,
            notice_type INT(1) NOT NULL DEFAULT 0
            )TYPE=MyISAM";
    $result=$db->query($sql);

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_tag_sets (
			tag_set_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (tag_set_id)
            )TYPE=MyISAM";
    $result=$db->query($sql);

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_tag_set_expressions (
            tag_set_id INT(11) UNSIGNED NOT NULL,
            language_code VARCHAR(30) NOT NULL,
            expression TEXT,
            PRIMARY KEY (tag_set_id, language_code)
            )TYPE=MyISAM";
    $result=$db->query($sql);

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_tags (
            tag_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            tag_set_id INT(11) UNSIGNED NOT NULL,
            PRIMARY KEY (tag_id),
            KEY (tag_set_id)
            )TYPE=MyISAM";
    $result=$db->query($sql);

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_tag_expressions (
            tag_id INT(11) UNSIGNED NOT NULL,
            language_code VARCHAR(30) NOT NULL,
            expression	VARCHAR(255),
            PRIMARY KEY (tag_id, language_code)
            )TYPE=MyISAM";
    $result=$db->query($sql);

    $sql="CREATE TABLE ".$db->prefix($mydirname)."_tag_relations (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            tag_set_id INT(11) UNSIGNED NOT NULL,
            tag_id INT(11) UNSIGNED NOT NULL,
            post_id INT(11) UNSIGNED NOT NULL,
            PRIMARY KEY (id)
            )TYPE=MyISAM";
    $result=$db->query($sql);
		
		

	// TABLES (write here ALTER TABLE etc. if necessary)

	// configs (Though I know it is not a recommended way...)
//	$check_sql = "SHOW COLUMNS FROM ".$db->prefix("config")." LIKE 'conf_title'" ;
//	if( ( $result = $db->query( $check_sql ) ) && ( $myrow = $db->fetchArray( $result ) ) && @$myrow['Type'] == 'varchar(30)' ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix("config")." MODIFY `conf_title` varchar(255) NOT NULL default '', MODIFY `conf_desc` varchar(255) NOT NULL default ''" ) ;
//	}

	// 0.1x -> 0.2x
//	$check_sql = "SELECT cat_unique_path FROM ".$db->prefix($mydirname."_categories") ;
//	if( ! $db->query( $check_sql ) ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_categories")." ADD cat_unique_path text NOT NULL default '' AFTER cat_path_in_tree" ) ;
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_forums")." ADD forum_external_link_format varchar(255) NOT NULL default '' AFTER cat_id" ) ;
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_topics")." ADD topic_external_link_id int(10) unsigned NOT NULL default 0 AFTER forum_id, ADD KEY (`topic_external_link_id`)" ) ;
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_posts")." ADD path_in_tree text NOT NULL default '' AFTER order_in_tree , ADD unique_path text NOT NULL default '' AFTER order_in_tree" ) ;
//	}

	// 0.3x -> 0.4x
//	$check_sql = "SELECT subject_waiting FROM ".$db->prefix($mydirname."_posts") ;	if( ! $db->query( $check_sql ) ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_posts")." ADD subject_waiting varchar(255) NOT NULL default '' AFTER `subject`, ADD post_text_waiting text NOT NULL AFTER `post_text`, ADD uid_hidden mediumint(8) unsigned NOT NULL default 0 AFTER `uid`, DROP hide_uid" ) ;
//	}

	// 0.4x/0.6x -> 0.7x
//	$check_sql = "SHOW COLUMNS FROM ".$db->prefix($mydirname."_topics")." LIKE 'topic_external_link_id'" ;
//	if( ( $result = $db->query( $check_sql ) ) && ( $myrow = $db->fetchArray( $result ) ) && substr( @$myrow['Type'] , 0 , 3 ) == 'int' ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_topics")." MODIFY topic_external_link_id varchar(255) NOT NULL default ''" ) ;
//	}
//	$check_sql = "SELECT COUNT(*) FROM ".$db->prefix($mydirname."_post_histories") ;
//	if( ! $db->query( $check_sql ) ) {
//		$db->queryF( "CREATE TABLE ".$db->prefix($mydirname."_post_histories")." ( history_id int(10) unsigned NOT NULL auto_increment, post_id int(10) unsigned NOT NULL default 0, history_time int(10) NOT NULL default 0, data text, PRIMARY KEY (history_id), KEY (post_id) ) TYPE=MyISAM" ) ;
//	}
//
//	$check_sql = "SELECT `all` FROM ".$db->prefix($mydirname."_topic_access") ;
//	if( ! $db->query( $check_sql ) ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_topic_access")." ADD `all` CHAR(1) NOT NULL default 0" ) ;
//	}
//	$check_sql = "SELECT `all` FROM ".$db->prefix($mydirname."_forum_access") ;
//	if( ! $db->query( $check_sql ) ) {
//		$db->queryF( "ALTER TABLE ".$db->prefix($mydirname."_forum_access")." ADD `all` CHAR(1) NOT NULL default 0" ) ;
//	}

	// TEMPLATES (all templates have been already removed by modulesadmin)
	$tplfile_handler =& xoops_gethandler( 'tplfile' ) ;
	$tpl_path = dirname(__FILE__).'/templates' ;
	if( $handler = @opendir( $tpl_path . '/' ) ) {
		while( ( $file = readdir( $handler ) ) !== false ) {
			if( substr( $file , 0 , 1 ) == '.' ) continue ;
			$file_path = $tpl_path . '/' . $file ;
			if( is_file( $file_path ) ) {
				$mtime = intval( @filemtime( $file_path ) ) ;
				$tplfile =& $tplfile_handler->create() ;
				$tplfile->setVar( 'tpl_source' , file_get_contents( $file_path ) , true ) ;
				$tplfile->setVar( 'tpl_refid' , $mid ) ;
				$tplfile->setVar( 'tpl_tplset' , 'default' ) ;
				$tplfile->setVar( 'tpl_file' , $mydirname . '_' . $file ) ;
				$tplfile->setVar( 'tpl_desc' , '' , true ) ;
				$tplfile->setVar( 'tpl_module' , $mydirname ) ;
				$tplfile->setVar( 'tpl_lastmodified' , $mtime ) ;
				$tplfile->setVar( 'tpl_lastimported' , 0 ) ;
				$tplfile->setVar( 'tpl_type' , 'module' ) ;
				if( ! $tplfile_handler->insert( $tplfile ) ) {
					$msgs[] = '<span style="color:#ff0000;">ERROR: Could not insert template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b> to the database.</span>';
				} else {
					$tplid = $tplfile->getVar( 'tpl_id' ) ;
					$msgs[] = 'Template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b> added to the database. (ID: <b>'.$tplid.'</b>)';
					// generate compiled file
					include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php' ;
					include_once XOOPS_ROOT_PATH.'/class/template.php' ;
					if( ! xoops_template_touch( $tplid ) ) {
						$msgs[] = '<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b>.</span>';
					} else {
						$msgs[] = 'Template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b> compiled.</span>';
					}
				}
			}
		}
		closedir( $handler ) ;
	}
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php' ;
	include_once XOOPS_ROOT_PATH.'/class/template.php' ;
	xoops_template_clear_module_cache( $mid ) ;

	return true ;
}

function d3forum_message_append_onupdate( &$module_obj , &$log )
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