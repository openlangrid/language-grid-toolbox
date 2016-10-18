<?php

eval( ' function xoops_module_update_'.$mydirname.'( $module ) { return storefront_onupdate_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'storefront_onupdate_base' ) ) {

function storefront_onupdate_base( $module , $mydirname )
{
	// transations on module update

	global $msgs ; // TODO :-D

	// for Cube 2.1
	if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'storefront_message_append_onupdate' ) ;
		$msgs = array() ;
	} else {
		if( ! is_array( $msgs ) ) $msgs = array() ;
	}

	$db =& Database::getInstance() ;
	$mid = $module->getVar('mid') ;

	// TABLES (write here ALTER TABLE etc. if necessary)
	$prefix_mod = $db->prefix();
	
	if( file_exists( XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ) ) {
		include_once XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ;
		$sqlutil = new OldSqlUtility ;
	} else {
		include_once XOOPS_ROOT_PATH.'/class/database/sqlutility.php' ;
		$sqlutil = new SqlUtility ;
	}
	
	// check table existence
	$tableExistsCheckSql = "select * from " . $prefix_mod . "_storefront_associate_glossaries";
	$result = $db -> query($tableExistsCheckSql);
	if ($result == FALSE) {
		
		$create_sql = "CREATE TABLE IF NOT EXISTS " . $prefix_mod . "_storefront_associate_glossaries (" .
					"	associate_glossary_id   INT NOT NULL AUTO_INCREMENT," .
					"   resource_name           VARCHAR(255) NOT NULL," .
					"	dictionary_name         VARCHAR(255) NOT NULL," .
					"	create_date             INT NOT NULL," .
					"	CONSTRAINT PRIMARY KEY (associate_glossary_id)" .
					") TYPE=ENGINE";
		
		$sql_query = trim($create_sql);
		$sqlutil->splitMySqlFile( $pieces , $sql_query );
		$created_tables = array() ;
		
		$msgs[] = implode("," , $pieces);
		
		if( is_array( $pieces ) ) {
			foreach( $pieces as $crate_query ) {
				
				if(!$crate_query) {
					$msgs[] = "Invalid SQL <b>".htmlspecialchars($piece)."</b><br />";
					return false ;
				}
				if(!$db->query( $crate_query )) {
					$msgs[] = '<b>'.htmlspecialchars( $db->error() ).'</b><br />' ;
					//var_dump( $db->error() ) ;
					return false ;
				} else {
					$msgs[] = 'Table created.<b>';
				}
			}
		}
	} else {
		// Nothing to do;
	}
		
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

function storefront_message_append_onupdate( &$module_obj , &$log )
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