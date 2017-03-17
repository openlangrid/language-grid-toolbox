<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = @$_SESSION['filesharing_mydirname'] ;
if( empty( $mydirname ) ) $mydirname = basename(dirname(dirname(__FILE__)));
// TODO
include_once XOOPS_ROOT_PATH."/modules/$mydirname/language/english/filesharing_constants.php" ;

if(!is_dir(XOOPS_ROOT_PATH."/uploaded_files")){
	@mkdir(XOOPS_ROOT_PATH."/uploaded_files");
}
if(!is_dir(XOOPS_ROOT_PATH."/uploaded_files/data")){
	@mkdir(XOOPS_ROOT_PATH."/uploaded_files/data");
}

eval( '
function xoops_module_install_'.$mydirname.'( $module )
{
	$modid = $module->getVar("mid") ;
	$gperm_handler = xoops_gethandler("groupperm");

	$global_perms_array = array(
		GPERM_INSERTABLE => _MD_ALBM_GPERM_G_INSERTABLE ,
		GPERM_SUPERINSERT | GPERM_INSERTABLE => _MD_ALBM_GPERM_G_SUPERINSERT ,
//		GPERM_EDITABLE => _MD_ALBM_GPERM_G_EDITABLE ,
		GPERM_SUPEREDIT | GPERM_EDITABLE => _MD_ALBM_GPERM_G_SUPEREDIT ,
//		GPERM_DELETABLE => _MD_ALBM_GPERM_G_DELETABLE ,
		GPERM_SUPERDELETE | GPERM_DELETABLE => _MD_ALBM_GPERM_G_SUPERDELETE ,
		GPERM_RATEVIEW => _MD_ALBM_GPERM_G_RATEVIEW ,
		GPERM_RATEVOTE | GPERM_RATEVIEW => _MD_ALBM_GPERM_G_RATEVOTE ,
		GPERM_TELLAFRIEND => _MD_ALBM_GPERM_G_TELLAFRIEND ,
	) ;

	foreach( $global_perms_array as $perms_id => $perms_name ) {
		$gperm =& $gperm_handler->create();
		$gperm->setVar("gperm_groupid", XOOPS_GROUP_ADMIN);
		$gperm->setVar("gperm_name", "filesharing_global");
		$gperm->setVar("gperm_modid", $modid);
		$gperm->setVar("gperm_itemid", $perms_id );
		$gperm_handler->insert($gperm) ;
		unset($gperm);
	}
	
	return true ;
}

' ) ;
?>
