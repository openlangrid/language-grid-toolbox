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

	if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

	global $xoopsConfig , $xoopsDB , $xoopsUser ;

	// module information
	$mod_url = XOOPS_URL . "/modules/$mydirname" ;
	$mod_path = XOOPS_ROOT_PATH . "/modules/$mydirname" ;
	$mod_copyright = "<a href='http://xoops.peak.ne.jp/'><strong>myAlbum-P 2.9</strong></a> &nbsp; <span style='font-size:0.8em;'>(<a href='http://bluetopia.homeip.net/'>original</a>)</span>" ;

	// global langauge file
	$language = $xoopsConfig['language'] ;
	if ( file_exists( "$mod_path/language/$language/apfilesharing_constants.php" ) ) {
		include_once "$mod_path/language/$language/apfilesharing_constants.php" ;
	} else {
		include_once "$mod_path/language/english/apfilesharing_constants.php" ;
		$language = "english" ;
	}

	// read from xoops_config
	// get my mid
	$rs = $xoopsDB->query( "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='$mydirname'" ) ;
	list( $apfilesharing_mid ) = $xoopsDB->fetchRow( $rs ) ;

	// read configs from xoops_config directly
	$rs = $xoopsDB->query( "SELECT conf_name,conf_value FROM ".$xoopsDB->prefix('config')." WHERE conf_modid=$apfilesharing_mid" ) ;
	while( list( $key , $val ) = $xoopsDB->fetchRow( $rs ) ) {
		$apfilesharing_configs[ $key ] = $val ;
	}
	
	//default config
	$apfilesharing_configs['apfilesharing_thumbspath'] = dirname($apfilesharing_configs['apfilesharing_fpath'])."/icons";
	if(!is_dir(XOOPS_ROOT_PATH.$apfilesharing_configs['apfilesharing_thumbspath'])){
		@mkdir(XOOPS_ROOT_PATH.$apfilesharing_configs['apfilesharing_thumbspath']);
	}
	$apfilesharing_configs['apfilesharing_width'] = '1024';
	$apfilesharing_configs['apfilesharing_height'] = '1024';
	$apfilesharing_configs['apfilesharing_middlepixel'] = '480x480';
	$apfilesharing_configs['apfilesharing_makethumb'] = '0';
	$apfilesharing_configs['apfilesharing_thumbsize'] = '140';
	$apfilesharing_configs['apfilesharing_thumbrule'] = 'w';
	$apfilesharing_configs['apfilesharing_catonsubmenu'] = '0';
	$apfilesharing_configs['apfilesharing_viewcattype'] = 'list';
	$apfilesharing_configs['apfilesharing_colsoftableview'] = '4';
	$apfilesharing_configs['apfilesharing_allowedexts'] = 'jpg|jpeg|gif|png';
	$apfilesharing_configs['apfilesharing_allowedmime'] = 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png';
	$apfilesharing_configs['apfilesharing_usesiteimg'] = '0';
	$apfilesharing_configs['apfilesharing_usesiteimg'] = 'name';
	$apfilesharing_configs['apfilesharing_addposts'] = '1';
	
	foreach( $apfilesharing_configs as $key => $val ) {
		if( strncmp( $key , "apfilesharing_" , 8 ) == 0 ) $$key = $val ;
	}

	// User Informations
	if( empty( $xoopsUser ) ) {
		$my_uid = 0 ;
		$isadmin = false ;
	} else {
		$my_uid = $xoopsUser->uid() ;
		$isadmin = $xoopsUser->isAdmin( $apfilesharing_mid ) ;
	}
	if (!$my_uid || $my_uid == 0) {
		redirect_header(XOOPS_URL.'/');
	}
	
	// Value Check
	$apfilesharing_addposts = intval( $apfilesharing_addposts ) ;
	if( $apfilesharing_addposts < 0 ) $apfilesharing_addposts = 0 ;

	// Path to Main File & Thumbnail ;
	if( ord( $apfilesharing_fpath ) != 0x2f ) $apfilesharing_fpath = "/$apfilesharing_fpath" ;
	if( ord( $apfilesharing_thumbspath ) != 0x2f ) $apfilesharing_thumbspath = "/$apfilesharing_thumbspath" ;
	$files_dir = XOOPS_ROOT_PATH . $apfilesharing_fpath ;
	$files_url = XOOPS_URL . $apfilesharing_fpath ;
	$apfilesharing_makethumb = false;
	if( $apfilesharing_makethumb ) {
		$thumbs_dir = XOOPS_ROOT_PATH . $apfilesharing_thumbspath ;
		$thumbs_url = XOOPS_URL . $apfilesharing_thumbspath ;
	} else {
		$thumbs_dir = $files_dir ;
		$thumbs_url = $files_url ;
	}

	// DB table name
	$table_files = $xoopsDB->prefix( "apfilesharing_files" ) ;
	$table_folder = $xoopsDB->prefix( "apfilesharing_folder" ) ;
	$table_files_r_permission  = $xoopsDB->prefix( "apfilesharing_files_read_permission" ) ;
	$table_folder_r_permission  = $xoopsDB->prefix( "apfilesharing_folder_read_permission" ) ;
	$table_files_e_permission  = $xoopsDB->prefix( "apfilesharing_files_edit_permission" ) ;
	$table_folder_e_permission  = $xoopsDB->prefix( "apfilesharing_folder_edit_permission" ) ;
	$table_groups = $xoopsDB->prefix("groups"); 
	//$table_text = $xoopsDB->prefix( "apfilesharing_text" ) ;
	//$table_votedata = $xoopsDB->prefix( "apfilesharing_votedata" ) ;
	//$table_comments = $xoopsDB->prefix( "xoopscomments" ) ;

	// Pipe environment check
	$apfilesharing_canrotate = false ;
	$apfilesharing_canresize = false ;

	// Normal Extensions of Image
	//$apfilesharing_normal_exts = array( 'jpg' , 'jpeg' , 'gif' , 'png' ) ;
	$apfilesharing_normal_exts = array() ;
	
	// Allowed extensions & MIME types
	if( empty( $apfilesharing_allowedexts ) ) {
		$array_allowed_exts = $apfilesharing_normal_exts ;
	} else {
		$array_allowed_exts = explode( '|' , $apfilesharing_allowedexts ) ;
	}
	if( empty( $apfilesharing_allowedmime ) ) {
		$array_allowed_mimetypes = array() ;
	} else {
		$array_allowed_mimetypes = explode( '|' , $apfilesharing_allowedmime ) ;
	}
?>
