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
	if ( file_exists( "$mod_path/language/$language/filesharing_constants.php" ) ) {
		include_once "$mod_path/language/$language/filesharing_constants.php" ;
	} else {
		include_once "$mod_path/language/english/filesharing_constants.php" ;
		$language = "english" ;
	}

	// read from xoops_config
	// get my mid
	$rs = $xoopsDB->query( "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='$mydirname'" ) ;
	list( $filesharing_mid ) = $xoopsDB->fetchRow( $rs ) ;

	// read configs from xoops_config directly
	$rs = $xoopsDB->query( "SELECT conf_name,conf_value FROM ".$xoopsDB->prefix('config')." WHERE conf_modid=$filesharing_mid" ) ;
	while( list( $key , $val ) = $xoopsDB->fetchRow( $rs ) ) {
		$filesharing_configs[ $key ] = $val ;
	}
	
	//default config
	$filesharing_configs['filesharing_thumbspath'] = dirname($filesharing_configs['filesharing_filespath'])."/icons";
	if(!is_dir(XOOPS_ROOT_PATH.$filesharing_configs['filesharing_thumbspath'])){
		@mkdir(XOOPS_ROOT_PATH.$filesharing_configs['filesharing_thumbspath']);
	}
	$filesharing_configs['filesharing_width'] = '1024';
	$filesharing_configs['filesharing_height'] = '1024';
	$filesharing_configs['filesharing_middlepixel'] = '480x480';
	$filesharing_configs['filesharing_makethumb'] = '0';
	$filesharing_configs['filesharing_thumbsize'] = '140';
	$filesharing_configs['filesharing_thumbrule'] = 'w';
	$filesharing_configs['filesharing_catonsubmenu'] = '0';
	$filesharing_configs['filesharing_viewcattype'] = 'list';
	$filesharing_configs['filesharing_colsoftableview'] = '4';
	$filesharing_configs['filesharing_allowedexts'] = 'jpg|jpeg|gif|png';
	$filesharing_configs['filesharing_allowedmime'] = 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png';
	$filesharing_configs['filesharing_usesiteimg'] = '0';
	$filesharing_configs['filesharing_usesiteimg'] = 'name';
	$filesharing_configs['filesharing_addposts'] = '1';
	
	foreach( $filesharing_configs as $key => $val ) {
		if( strncmp( $key , "filesharing_" , 8 ) == 0 ) $$key = $val ;
	}

	// User Informations
	if( empty( $xoopsUser ) ) {
		$my_uid = 0 ;
		$isadmin = false ;
	} else {
		$my_uid = $xoopsUser->uid() ;
		$isadmin = $xoopsUser->isAdmin( $filesharing_mid ) ;
	}
	if (!$my_uid || $my_uid == 0) {
		redirect_header(XOOPS_URL.'/');
	}
	
	// Value Check
	$filesharing_addposts = intval( $filesharing_addposts ) ;
	if( $filesharing_addposts < 0 ) $filesharing_addposts = 0 ;

	// Path to Main File & Thumbnail ;
	if( ord( $filesharing_filespath ) != 0x2f ) $filesharing_filespath = "/$filesharing_filespath" ;
	if( ord( $filesharing_thumbspath ) != 0x2f ) $filesharing_thumbspath = "/$filesharing_thumbspath" ;
	$files_dir = XOOPS_ROOT_PATH . $filesharing_filespath ;
	$files_url = XOOPS_URL . $filesharing_filespath ;
	$filesharing_makethumb = false;
	if( $filesharing_makethumb ) {
		$thumbs_dir = XOOPS_ROOT_PATH . $filesharing_thumbspath ;
		$thumbs_url = XOOPS_URL . $filesharing_thumbspath ;
	} else {
		$thumbs_dir = $files_dir ;
		$thumbs_url = $files_url ;
	}

	// DB table name
	$table_files = $xoopsDB->prefix( "filesharing_files" ) ;
	$table_cat = $xoopsDB->prefix( "filesharing_folder" ) ;
	//$table_text = $xoopsDB->prefix( "filesharing_text" ) ;
	//$table_votedata = $xoopsDB->prefix( "filesharing_votedata" ) ;
	//$table_comments = $xoopsDB->prefix( "xoopscomments" ) ;

	// Pipe environment check
	$filesharing_canrotate = false ;
	$filesharing_canresize = false ;

	// Normal Extensions of Image
	//$filesharing_normal_exts = array( 'jpg' , 'jpeg' , 'gif' , 'png' ) ;
	$filesharing_normal_exts = array() ;
	
	// Allowed extensions & MIME types
	if( empty( $filesharing_allowedexts ) ) {
		$array_allowed_exts = $filesharing_normal_exts ;
	} else {
		$array_allowed_exts = explode( '|' , $filesharing_allowedexts ) ;
	}
	if( empty( $filesharing_allowedmime ) ) {
		$array_allowed_mimetypes = array() ;
	} else {
		$array_allowed_mimetypes = explode( '|' , $filesharing_allowedmime ) ;
	}
?>
