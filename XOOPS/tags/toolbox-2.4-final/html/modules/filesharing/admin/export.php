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

include "admin_header.php" ;
include_once XOOPS_ROOT_PATH . '/modules/system/constants.php' ;

// GPCS vars

$myts =& MyTextSanitizer::getInstance() ;

// reject Not Admin
if( ! $isadmin ) {
	redirect_header( $mod_url.'/' , 2 , _MD_ALBM_MUSTREGFIRST ) ;
	exit ;
}


//
// Exec Part
//


// To imagemanager
else if( ! empty( $_POST['imagemanager_export'] ) && ! empty( $_POST['imgcat_id'] ) && ! empty( $_POST['cid'] ) ) {

	// authority check
	$sysperm_handler =& xoops_gethandler('groupperm');
	if( ! $sysperm_handler->checkRight('system_admin', XOOPS_SYSTEM_IMAGE, $xoopsUser->getGroups() ) ) exit ;

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	// get dst information
	$dst_cid = intval( $_POST['imgcat_id'] ) ;
	$dst_table_files = $xoopsDB->prefix( "image" ) ;
	$dst_table_cat = $xoopsDB->prefix( "imagecategory" ) ;

	// get src information
	$src_cid = intval( $_POST['cid'] ) ;
	$src_table_files = $table_files ;
	$src_table_cat = $table_cat ;

	// get storetype of the imgcat
	$crs = $xoopsDB->query( "SELECT imgcat_storetype,imgcat_maxsize FROM $dst_table_cat WHERE imgcat_id='$dst_cid'" ) or die( 'Invalid imgcat_id.' ) ;
	list( $imgcat_storetype,$imgcat_maxsize ) = $xoopsDB->fetchRow( $crs ) ;

	// mime type look up
	$mime_types = array( 'gif' => 'image/gif' , 'png' => 'image/png' , 'jpg' => 'image/jpeg' , 'jpg' => 'image/jpeg' ) ;

	// INSERT loop
	$srs = $xoopsDB->query( "SELECT lid,ext,title,date,status FROM $src_table_files WHERE cid='$src_cid'" ) ;
	$export_count = 0 ;
	while( list( $lid,$ext,$title,$date,$status ) = $xoopsDB->fetchRow( $srs ) ) {

		$dst_node = uniqid( 'img' ) ;
		$dst_file = XOOPS_UPLOAD_PATH . "/{$dst_node}.{$ext}" ;
		$src_file = empty( $_POST['use_thumb'] ) ? "$files_dir/{$lid}.{$ext}" : "$thumbs_dir/{$lid}.{$ext}" ;

		if( $imgcat_storetype == 'db' ) {
			$fp = fopen( $src_file , "rb" ) ;
			if( $fp == false ) continue ;
			$body = addslashes( fread( $fp , filesize( $src_file ) ) ) ;
			fclose( $fp ) ;
		} else {
			if( ! copy( $src_file , $dst_file ) ) continue ;
			$body = '' ;
		}

		// insert into image table
		$image_display = $status ? 1 : 0 ;
		$xoopsDB->query( "INSERT INTO $dst_table_files SET image_name='{$dst_node}.{$ext}',image_nicename='".addslashes($title)."',image_created='$date',image_mimetype='{$mime_types[$ext]}',image_display='$image_display',imgcat_id='$dst_cid'" ) or die( "DB error: INSERT image table" ) ;
		if( $body ) {
			$image_id = $xoopsDB->getInsertId() ;
			$xoopsDB->query( "INSERT INTO ".$xoopsDB->prefix("imagebody")." SET image_id='$image_id',image_body='$body'" ) ;
		}

		$export_count ++ ;
	}

	redirect_header( 'export.php' , 2 , sprintf( _AM_FMT_EXPORTSUCCESS , $export_count ) ) ;
	exit ;
}





//
// Form Part
//
xoops_cp_header() ;
include( './mymenu.php' ) ;
echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_EXPORTTO,$xoopsModule->name())."</h3>\n" ;


// To imagemanager
$sysperm_handler =& xoops_gethandler('groupperm');
if( $sysperm_handler->checkRight('system_admin', XOOPS_SYSTEM_IMAGE, $xoopsUser->getGroups() ) ) {
	// only when user has admin right of system 'imagemanager'

	$irs = $xoopsDB->query( "SELECT c.imgcat_id,c.imgcat_name,c.imgcat_storetype,COUNT(i.image_id) AS imgcat_sum FROM ".$xoopsDB->prefix("imagecategory")." c NATURAL LEFT JOIN ".$xoopsDB->prefix("image")." i GROUP BY c.imgcat_id ORDER BY c.imgcat_weight" ) ;
	$imgcat_options = '' ;
	while( list( $imgcat_id,$imgcat_name,$imgcat_storetype,$imgcat_sum ) = $xoopsDB->fetchRow( $irs ) ) {
		$imgcat_options .= "<option value='$imgcat_id'>$imgcat_storetype : $imgcat_name ($imgcat_sum)</option>\n" ;
	}

	// Options for Selecting a category in myAlbum-P
	$filesharing_cat_options = filesharing_get_cat_options( 'title' , 0 , '--' , '----' ) ;

	filesharing_opentable() ;
	echo "
		<h4>"._AM_FMT_EXPORTTOIMAGEMANAGER."</h4>
		<form name='ImageManager' action='export.php' method='POST'>
		".$xoopsGTicket->getTicketHtml( __LINE__ )."
		<select name='cid'>
			$filesharing_cat_options
		</select>
		"._AM_FMT_EXPORTIMSRCCAT."
		&nbsp; -> &nbsp; 
		<select name='imgcat_id'>
			$imgcat_options
		</select>
		"._AM_FMT_EXPORTIMDSTCAT."
		<br />
		<br />
		<input type='checkbox' name='use_thumb' value='1' checked='checked' />"._AM_CB_EXPORTTHUMB."
		<br />
		<br />
		<input type='submit' name='imagemanager_export' value='"._GO."' onclick='return confirm(\""._AM_MB_EXPORTCONFIRM."\");' />
		</form>\n" ;
	filesharing_closetable() ;
	echo "<br />" ;
}



xoops_cp_footer() ;

?>