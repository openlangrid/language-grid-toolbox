<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This preserves contents
// entered in forms.
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

eval( ' function xoops_module_install_'.$mydirname.'( $module ) { return ScreenInfo_oninstall_base( $module , "'.$mydirname.'" ) ; } ' ) ;

if(!function_exists( 'ScreenInfo_oninstall_base' ) ) {
	function ScreenInfo_oninstall_base( $module , $mydirname ){
		global $ret ; // TODO :-D
		$ret[] = 'success...';
		// for Cube 2.1
		if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
			$root =& XCube_Root::getSingleton();
			$root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleInstall.' . ucfirst($mydirname) . '.Success' , 'ScreenInfo_message_append_oninstall' ) ;
			$ret = array() ;
		} else {
			if( ! is_array( $ret ) ) $ret = array() ;
		}
		//$filepath = dirname(__FILE__)."/LoginSuccessAction.class.php";
		//if(file_exists($filepath)){
		//	if(!@copy($filepath,XOOPS_ROOT_PATH."/preload/LoginSuccessAction.class.php")){
		//		$ret[] = '<span style="color:#ff0000;">ERROR: Failed copy file <b>'.$filepath.'</b>.</span><br />';
		//	}else{
		//		$ret[] = 'File <b>'.htmlspecialchars($filepath).'</b> copied.</span><br />';
		//	}
		//}
		return true;
	}

	function ScreenInfo_message_append_oninstall( &$module_obj , &$log ){
		if( is_array( @$GLOBALS['ret'] ) ) {
			foreach( $GLOBALS['ret'] as $message ) {
				$log->add( strip_tags( $message ) ) ;
			}
		}
	}
}
?>