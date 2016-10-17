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

include( "admin_header.php" ) ;
include_once( "../../../class/xoopsformloader.php" ) ;

// get and check $_POST['size']
$start = isset( $_POST['start'] ) ? intval( $_POST['start'] ) : 0 ;
$size = isset( $_POST['size'] ) ? intval( $_POST['size'] ) : 10 ;
if( $size <= 0 || $size > 10000 ) $size = 10 ;

$forceredo = isset( $_POST['forceredo'] ) ? intval( $_POST['forceredo'] ) : false ;
$removerec = isset( $_POST['removerec'] ) ? intval( $_POST['removerec'] ) : false ;
$resize = isset( $_POST['resize'] ) ? intval( $_POST['resize'] ) : false ;

// get flag of safe_mode
$safe_mode_flag = ini_get( "safe_mode" ) ;

// even if makethumb is off, it is treated as makethumb on
if( ! $apfilesharing_makethumb ) {
	$apfilesharing_makethumb = 1 ;
	$thumbs_dir = XOOPS_ROOT_PATH . $apfilesharing_thumbspath ;
	$thumbs_url = XOOPS_URL . $apfilesharing_thumbspath ;
}

// check if the directories of thumbs and files are same.
if( $thumbs_dir == $files_dir ) die( "The directory for thumbnails is same as for files." ) ;

// check or make thumbs_dir
if( $apfilesharing_makethumb && ! is_dir( $thumbs_dir ) ) {
	if( $safe_mode_flag ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/admin/",10,"At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
		exit ;
	}

	$rs = mkdir( $thumbs_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",10,"$thumbs_dir is not a directory");
		exit ;
	} else @chmod( $thumbs_dir , 0777 ) ;
}

if( ! empty( $_POST['submit'] ) ) {
	ob_start() ;

	$result = $xoopsDB->query( "SELECT lid , ext , res_x , res_y FROM $table_files ORDER BY lid LIMIT $start , $size") or die( "DB Error" ) ;
	$record_counter = 0 ;
	while( list( $lid , $ext , $w , $h ) = $xoopsDB->fetchRow( $result ) ) {
		$record_counter ++ ;
		echo ( $record_counter + $start - 1 ) . ") " ;
		printf( _AM_FMT_CHECKING , "$lid.$ext" ) ;

		// Check if the main image exists
		if( ! is_readable( "$files_dir/$lid.$ext" ) ) {
			echo _AM_MB_FILENOTEXISTS." &nbsp; " ;
			if( $removerec ) {
				apfilesharing_delete_files( "lid='$lid'" ) ;
				echo _AM_MB_RECREMOVED."<br />\n" ;
			} else {
				echo _AM_MB_SKIPPED."<br />\n" ;
			}
			continue ;
		}

		// Check if the file is normal image
		if( ! in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) ) {
			if( $forceredo || ! is_readable( "$thumbs_dir/$lid.gif" ) ) {
				apfilesharing_create_icon( "$files_dir/$lid.$ext" , $lid , $ext ) ;
				echo _AM_MB_CREATEDTHUMBS."<br />\n" ;
			} else {
				echo _AM_MB_SKIPPED."<br />\n" ;
			}
			continue ;
		}

		// Size of main file
		list( $true_w , $true_h ) = getimagesize( "$files_dir/$lid.$ext" ) ;
		echo "{$true_w}x{$true_h} .. " ;

		// Check and resize the main file if necessary
		if( $resize && ( $true_w > $apfilesharing_width || $true_h > $apfilesharing_height ) ) {
			$tmp_path = "$files_dir/apfilesharing_tmp_file" ;
			@unlink( $tmp_path ) ;
			rename( "$files_dir/$lid.$ext" , $tmp_path ) ;
			apfilesharing_modify_file( $tmp_path , "$files_dir/$lid.$ext" ) ;
			@unlink( $tmp_path ) ;
			echo _AM_MB_FILERESIZED." &nbsp; " ;
			list( $true_w , $true_h ) = getimagesize( "$files_dir/$lid.$ext" ) ;
		}

		// Check and repair record of the file if necessary
		if( $true_w != $w || $true_h != $h ) {
			$xoopsDB->query( "UPDATE $table_files SET res_x=$true_w, res_y=$true_h WHERE lid=$lid" ) or die( "DB error: UPDATE file table." ) ;
			echo _AM_MB_SIZEREPAIRED." &nbsp; " ;
		}

		// Create Thumbs
		if( is_readable( "$thumbs_dir/$lid.$ext" ) ) {
			list( $thumb_w , $thumb_h ) = getimagesize( "$thumbs_dir/$lid.$ext" ) ;
			echo "{$thumb_w}x{$thumb_h} ... " ;
			if( $forceredo ) {
				$retcode = apfilesharing_create_icon( "$files_dir/$lid.$ext" , $lid , $ext ) ;
			} else {
				$retcode = 3 ;
			}
		} else {
			if( $apfilesharing_makethumb ) {
				$retcode = apfilesharing_create_icon( "$files_dir/$lid.$ext" , $lid , $ext ) ;
			} else {
				$retcode = 3 ;
			}
		}

		switch( $retcode ) {
			case 0 : 
				echo _AM_MB_FAILEDREADING."<br />\n" ;
				break ;
			case 1 : 
				echo _AM_MB_CREATEDTHUMBS."<br />\n" ;
				break ;
			case 2 : 
				echo _AM_MB_BIGTHUMBS."<br />\n" ;
				break ;
			case 3 : 
				echo _AM_MB_SKIPPED."<br />\n" ;
				break ;
		}
	}
	$result_str = ob_get_contents() ;
	ob_end_clean() ;

	$start += $size ;
}

// Make form objects
$form = new XoopsThemeForm( _AM_FORM_RECORDMAINTENANCE , "batchupload" , "redothumbs.php" ) ;
$form->setExtra( "enctype='multipart/form-data'" ) ;

$start_text = new XoopsFormText( _AM_TEXT_RECORDFORSTARTING , "start" , 20 , 20 , $start ) ;
$size_text = new XoopsFormText( _AM_TEXT_NUMBERATATIME."<br /><br /><span style='font-weight:normal'>"._AM_LABEL_DESCNUMBERATATIME."</span>", "size" , 20 , 20 , $size ) ;
$forceredo_radio = new XoopsFormRadioYN( _AM_RADIO_FORCEREDO , 'forceredo' , $forceredo ) ;
$removerec_radio = new XoopsFormRadioYN( _AM_RADIO_REMOVEREC , 'removerec' , $removerec ) ;
$resize_radio = new XoopsFormRadioYN( _AM_RADIO_RESIZE." ({$apfilesharing_width}x{$apfilesharing_height})" , 'resize' , $resize ) ;

if( isset( $record_counter ) && $record_counter < $size ) {
	$submit_button = new XoopsFormLabel( "" , _AM_MB_FINISHED." &nbsp; <a href='redothumbs.php'>"._AM_LINK_RESTART."</a>" ) ;
} else {
	$submit_button = new XoopsFormButton( "" , "submit" , _AM_SUBMIT_NEXT , "submit" ) ;
}


// Render forms
xoops_cp_header() ;
include( './mymenu.php' ) ;

// check $xoopsModule
if( ! is_object( $xoopsModule ) ) redirect_header( "$mod_url/" , 1 , _NOPERM ) ;
echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_RECORDMAINTENANCE,$xoopsModule->name())."</h3>\n" ;

apfilesharing_opentable() ;
$form->addElement( $start_text ) ;
$form->addElement( $size_text ) ;
$form->addElement( $forceredo_radio ) ;
$form->addElement( $removerec_radio ) ;
$form->addElement( $resize_radio ) ;
$form->addElement( $submit_button ) ;
$form->display() ;
apfilesharing_closetable() ;

if( isset( $result_str ) ) {
	echo "<br />\n" ;
	echo $result_str ;
}

xoops_cp_footer() ;

?>