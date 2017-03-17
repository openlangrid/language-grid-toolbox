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

include("admin_header.php");

include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");

xoops_cp_header();
include( './mymenu.php' ) ;

// check $xoopsModule
if( ! is_object( $xoopsModule ) ) redirect_header( "$mod_url/" , 1 , _NOPERM ) ;
echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_MODULECHECKER,$xoopsModule->name())."</h3>\n" ;

apfilesharing_opentable() ;

// Initialize
$netpbm_pipes = array( "jpegtopnm" , "giftopnm" , "pngtopnm" , 
	 "pnmtojpeg" , "pnmtopng" , "ppmquant" , "ppmtogif" ,
	 "pnmscale" , "pnmflip" ) ;

// PATH_SEPARATOR
if( ! defined( 'PATH_SEPARATOR' ) ) {
	if( DIRECTORY_SEPARATOR == '/' ) define( 'PATH_SEPARATOR' , ':' ) ;
	else define( 'PATH_SEPARATOR' , ';' ) ;
}

//
// ENVIRONTMENT CHECK
//
echo "<h4>"._AM_H4_ENVIRONMENT."</h4>\n" ;

echo _AM_MB_PHPDIRECTIVE." 'safe_mode' ("._AM_MB_BOTHOK."): &nbsp; " ;
$safe_mode_flag = ini_get( "safe_mode" ) ;
if( ! $safe_mode_flag ) echo "<font color='#00FF00'><b>off</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>on</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'file_uploads' ("._AM_MB_NEEDON."): &nbsp; " ;
$rs = ini_get( "file_uploads" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>off</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>on</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'register_globals' ("._AM_MB_BOTHOK."): &nbsp; " ;
$rs = ini_get( "register_globals" ) ;
if( ! $rs ) echo "<font color='#00FF00'><b>off</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>on</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'upload_max_filesize': &nbsp; " ;
$rs = ini_get( "upload_max_filesize" ) ;
echo "<font color='#00FF00'><b>$rs byte</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'post_max_size': &nbsp; " ;
$rs = ini_get( "post_max_size" ) ;
echo "<font color='#00FF00'><b>$rs byte</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'open_basedir': &nbsp; " ;
$rs = ini_get( "open_basedir" ) ;
if( ! $rs ) echo "<font color='#00FF00'><b>nothing</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>$rs</b></font><br />\n" ;

echo _AM_MB_PHPDIRECTIVE." 'upload_tmp_dir': &nbsp; " ;
$tmp_dirs = explode( PATH_SEPARATOR , ini_get( "upload_tmp_dir" ) ) ;
foreach( $tmp_dirs as $dir ) {
	if( $dir != "" && ( ! is_writable( $dir ) || ! is_readable( $dir ) ) ) {
		echo "<font color='#FF0000'><b>Error: upload_tmp_dir ($dir) is not writable nor readable .</b></font><br />\n" ;
		$error_upload_tmp_dir = true ;
	}
}
if( empty( $error_upload_tmp_dir ) ) echo "<font color='#00FF00'><b>ok ".ini_get("upload_tmp_dir")."</b></font><br />\n" ;


//
// TABLE CHECK
//
echo "<h4>"._AM_H4_TABLE."</h4>\n" ;

echo _AM_MB_FILESTABLE.": $table_files &nbsp; " ;
$rs = $xoopsDB->query( "SELECT COUNT(lid) FROM $table_files" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>Error</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>ok</b></font><br />\n" ;
list( $num_file ) = $xoopsDB->fetchRow( $rs ) ;
echo _AM_MB_NUMBEROFFILES.": $num_file<br /><br />\n" ;

echo _AM_MB_DESCRIPTIONTABLE.": $table_text &nbsp; " ;
$rs = $xoopsDB->query( "SELECT COUNT(lid) FROM $table_text" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>Error</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>ok</b></font><br />\n" ;
list( $num_text ) = $xoopsDB->fetchRow( $rs ) ;
echo _AM_MB_NUMBEROFDESCRIPTIONS.": $num_text<br /><br />\n" ;

echo _AM_MB_CATEGORIESTABLE.": $table_folder &nbsp; " ;
$rs = $xoopsDB->query( "SELECT COUNT(cid) FROM $table_folder" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>Error</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>ok</b></font><br />\n" ;
list( $num_cat ) = $xoopsDB->fetchRow( $rs ) ;
echo _AM_MB_NUMBEROFCATEGORIES.": $num_cat<br /><br />\n" ;

echo _AM_MB_VOTEDATATABLE.": $table_votedata &nbsp; " ;
$rs = $xoopsDB->query( "SELECT COUNT(lid) FROM $table_votedata" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>Error</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>ok</b></font><br />\n" ;
list( $num_votedata ) = $xoopsDB->fetchRow( $rs ) ;
echo _AM_MB_NUMBEROFVOTEDATA.": $num_votedata<br /><br />\n" ;

echo _AM_MB_COMMENTSTABLE.": $table_comments &nbsp; " ;
$rs = $xoopsDB->query( "SELECT COUNT(com_id) FROM $table_comments WHERE com_modid=$apfilesharing_mid" ) ;
if( ! $rs ) echo "<font color='#FF0000'><b>Error</b></font><br />\n" ;
else echo "<font color='#00FF00'><b>ok</b></font><br />\n" ;
list( $num_comments ) = $xoopsDB->fetchRow( $rs ) ;
echo _AM_MB_NUMBEROFCOMMENTS.": $num_comments<br /><br />\n" ;


//
// CONFIG CHECK
//
echo "<h4>"._AM_H4_CONFIG."</h4>\n" ;

// directory
echo _AM_MB_DIRECTORYFORFILES.": ".XOOPS_ROOT_PATH."$apfilesharing_fpath &nbsp; " ;
if( substr( $apfilesharing_fpath , -1 ) == '/' ) {
	echo "<font color='#FF0000'><b>"._AM_ERR_LASTCHAR."</b></font><br />\n" ;
} else if( ord( $apfilesharing_fpath ) != 0x2f ) {
	echo "<font color='#FF0000'><b>"._AM_ERR_FIRSTCHAR."</b></font><br />\n" ;
} else if( ! is_dir( $files_dir ) ) {
	if( $safe_mode_flag ) {
		echo "<font color='#FF0000'><b>"._AM_ERR_PERMISSION."</b></font><br />\n" ;
	} else {
		$rs = mkdir( $files_dir , 0777 ) ;
		if( ! $rs ) echo "<font color='#FF0000'><b>"._AM_ERR_NOTDIRECTORY."</b></font><br />\n" ;
		else echo "<font color='#00FF00'><b>ok</b></font> &nbsp; <br />\n" ;
	}
} else if( ! is_writable( $files_dir ) || ! is_readable( $files_dir ) ) {
	echo "<font color='#FF0000'><b>"._AM_ERR_READORWRITE."</b></font><br />\n" ;
} else {
	echo "<font color='#00FF00'><b>ok</b></font> &nbsp; <br />\n" ;
}
echo "<br />\n" ;

// thumbs
if( $apfilesharing_makethumb ) {
	echo _AM_MB_DIRECTORYFORTHUMBS.": ".XOOPS_ROOT_PATH."$apfilesharing_thumbspath &nbsp; " ;
	if( substr( $apfilesharing_thumbspath , -1 ) == '/' ) {
		echo "<font color='#FF0000'><b>"._AM_ERR_LASTCHAR."</b></font><br />\n" ;
	} else if( ord( $apfilesharing_thumbspath ) != 0x2f ) {
		echo "<font color='#FF0000'><b>"._AM_ERR_FIRSTCHAR."</b></font><br />\n" ;
	} else if( ! is_dir( $thumbs_dir ) ) {
		if( $safe_mode_flag ) {
			echo "<font color='#FF0000'><b>"._AM_ERR_PERMISSION."</b></font><br />\n" ;
		} else {
			$rs = mkdir( $thumbs_dir , 0777 ) ;
			if( ! $rs ) echo "<font color='#FF0000'><b>"._AM_ERR_NOTDIRECTORY."</b></font><br />\n" ;
			else echo "<font color='#00FF00'><b>ok</b></font> &nbsp; <br />\n" ;
		}
	} else if( $apfilesharing_thumbspath == $apfilesharing_fpath ) {
		echo "<font color='#FF0000'><b>"._AM_ERR_SAMEDIR."</b></font><br />\n" ;
	} else if( ! is_writable( $thumbs_dir ) || ! is_readable( $thumbs_dir ) ) {
		echo "<font color='#FF0000'><b>"._AM_ERR_READORWRITE."</b></font><br />\n" ;
	} else {
		echo "<font color='#00FF00'><b>ok</b></font> &nbsp; <br />\n" ;
	}
	echo "<br />\n" ;
}


//
// CONSISTEMCY CHECK
//
echo "<h4>"._AM_H4_FILELINK."</h4>\n" ;

$dead_files = 0 ;
$dead_thumbs = 0 ;
echo _AM_MB_NOWCHECKING ;
$rs = $xoopsDB->query( "SELECT lid,ext FROM $table_files" ) ;
// check loop
while( list( $lid , $ext ) = $xoopsDB->fetchRow( $rs ) ) {
	echo ". " ;
	if( ! is_readable( "$files_dir/$lid.$ext" ) ) {
		printf( "<br />"._AM_FMT_FILENOTREADABLE."\n" , "$files_dir/$lid.$ext" ) ;
		$dead_files ++ ;
	}
	if( $apfilesharing_makethumb && in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) && ! is_readable( "$thumbs_dir/$lid.$ext" ) ) {
		printf( "<br />"._AM_FMT_THUMBNOTREADABLE."\n" , "$thumbs_dir/$lid.$ext" ) ;
		$dead_thumbs ++ ;
	}
}
// show result
if( $dead_files == 0 ) {
	if( ! $apfilesharing_makethumb || $dead_thumbs == 0 ) {
		echo "<font color='#00FF00'><b>ok</b></font> &nbsp; <br />\n" ;
	} else {
		printf( "<br /><font color='#FF0000'><b>"._AM_FMT_NUMBEROFDEADTHUMBS."</b></font><br />\n" , $dead_thumbs ) ;
		echo "
			<form action='redothumbs.php' method='post'>
				<input type='submit' value='"._AM_LINK_REDOTHUMBS."' />
			</form>\n" ;
	}
} else {
	printf( "<br /><font color='#FF0000'><b>"._AM_FMT_NUMBEROFDEADFILES."</b></font><br />\n" , $dead_files ) ;
	echo "
		<form action='redothumbs.php' method='post'>
			<input type='hidden' name='removerec' value='1' />
			<input type='submit' value='"._AM_LINK_TABLEMAINTENANCE."' />
		</form>\n" ;
}

// Clear tempolary files
$removed_tmp_num = apfilesharing_clear_tmp_files( $files_dir ) ;
if( $removed_tmp_num > 0 ) printf( "<br />"._AM_FMT_NUMBEROFREMOVEDTMPS."<br />\n" , $removed_tmp_num ) ;

apfilesharing_closetable() ;
xoops_cp_footer();

?>