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
include_once( XOOPS_ROOT_PATH."/class/xoopstree.php" ) ;
include_once( "../../../class/xoopsformloader.php" ) ;
include_once( "../../../include/xoopscodes.php" ) ;

$myts =& MyTextSanitizer::getInstance() ;
$cattree = new XoopsTree( $table_cat , "cid" , "pid" ) ;

// GPCS vars
$submitter = empty( $_POST['submitter'] ) ? $my_uid : intval( $_POST['submitter']  ) ;
if( isset( $_POST['cid'] ) ) $cid = intval( $_POST['cid'] ) ;
else if( isset( $_GET['cid'] ) ) $cid = intval( $_GET['cid'] ) ;
else $cid = 0 ;
$dir4edit = isset( $_POST['dir'] ) ? $myts->makeTboxData4Edit( $_POST['dir'] ) : '' ;
$title4edit = isset( $_POST['title'] ) ? $myts->makeTboxData4Edit( $_POST['title'] ) : '' ;
$desc4edit = isset( $_POST['desc'] ) ? $myts->makeTareaData4Edit( $_POST['desc'] ) : '' ;


// reject Not Admin
if( ! $isadmin ) {
	redirect_header( $mod_url.'/' , 2 , _MD_ALBM_MUSTREGFIRST ) ;
	exit ;
}

// check Categories exist
$result = $xoopsDB->query( "SELECT count(cid) as count FROM $table_cat" ) ;
list( $count ) = $xoopsDB->fetchRow( $result ) ;
if( $count < 1 ) {
	redirect_header(XOOPS_URL."/modules/$mydirname/admin/index.php",2,_MD_ALBM_MUSTADDCATFIRST);
	exit();
}


if( isset( $_POST['submit'] ) && $_POST['submit'] != "" ) {
	ob_start() ;

	// Check Directory
	$dir = $myts->stripSlashesGPC( $_POST['dir'] ) ;
	if( empty( $dir ) || ! is_dir( $dir ) ) {
		if( ord( $dir ) != 0x2f ) $dir = "/$dir" ;
		$prefix = XOOPS_ROOT_PATH ;
		while( strlen( $prefix ) > 0 ) {
			if( is_dir( "$prefix$dir" ) ) {
				$dir = "$prefix$dir" ;
				break ;
			}
			$prefix = substr( $prefix , 0 , strrpos( $prefix , '/' ) ) ;
		}
		if( ! is_dir( $dir ) ) {
			redirect_header( 'batch.php' , 3 , _MD_ALBM_MES_INVALIDDIRECTORY . "<br />$dir4edit" ) ;
			exit ;
		}
	}
	if( substr( $dir , -1 ) == '/' ) $dir = substr( $dir , 0 , -1 ) ;

	$title4save = $myts->makeTboxData4Save( $_POST['title'] ) ;
	$desc4save = $myts->makeTareaData4Save( $_POST['desc'] ) ;

	$date = strtotime( $_POST['post_date'] ) ;
	if( $date == -1 ) $date = time() ;

	$dir_h = opendir( $dir ) ;
	if( $dir_h === false ) {
		redirect_header( 'batch.php' , 3 , _MD_ALBM_MES_INVALIDDIRECTORY . "<br />$dir4edit" ) ;
		exit ;
	}
	// get all file_names from the directory.
	$file_names = array() ;
	while( $file_name = readdir( $dir_h ) ) {
		$file_names[] = $file_name ;
	}
	sort( $file_names ) ;

	$filecount = 1 ;
	foreach( $file_names as $file_name ) {

		// Skip '.' , '..' and hidden file
		if( substr( $file_name , 0 , 1 ) == '.' ) continue ;

		$ext = substr( strrchr( $file_name , '.' ) , 1 ) ;
		$node = substr( $file_name , 0 , - strlen( $ext ) - 1 ) ;
		$file_path = "$dir/$node.$ext" ;

		$title = empty( $_POST['title'] ) ? addslashes( $node ) : "$title4save $filecount" ;

		if( is_readable( $file_path ) && in_array( strtolower( $ext ) , $array_allowed_exts ) ) {
			$lid = $xoopsDB->genId( $table_files."_lid_seq" ) ;
			if( in_array( strtolower( $ext ) , $filesharing_normal_exts ) ) {
				list( $w , $h ) = getimagesize( $file_path ) ;
			} else {
				list( $w , $h ) = array( 0 , 0 ) ;
			}
			$sql = "INSERT INTO $table_files SET lid='$lid', cid='$cid', title='$title', ext='$ext', res_x='$w', res_y='$h', submitter='$submitter', status=1, date='$date'" ;
			$xoopsDB->query( $sql ) or die( "DB error: INSERT files table." ) ;
			if( $lid == 0 ) {
				$lid = $xoopsDB->getInsertId() ;
			}
			print " &nbsp; <a href='../?page=file&lid=$lid' target='_blank'>$file_path</a>\n" ;
			copy( $file_path , "$files_dir/$lid.$ext" ) ;
			filesharing_create_icon( "$files_dir/$lid.$ext" , $lid , $ext ) ;
			$xoopsDB->query( "INSERT INTO $table_text SET lid='$lid', description='$desc4save'" ) ;
			echo _AM_MB_FINISHED."<br />\n" ;

			$filecount ++ ;
		}
	}
	closedir( $dir_h ) ;

	if( $filecount <= 1 ) {
		echo "<p>$dir4edit : "._MD_ALBM_MES_BATCHNONE."</p>" ;
	} else {
		printf( "<p>"._MD_ALBM_MES_BATCHDONE."</p>" , $filecount - 1 ) ;
	}

	$result_str = ob_get_contents() ;
	ob_end_clean() ;
}


// Make form objects
$form = new XoopsThemeForm( _MD_ALBM_FILEBATCHUPLOAD , "batchupload" , "batch.php" ) ;

$title_text = new XoopsFormText( "" , "title" , 50 , 255 , $title4edit ) ;
$title_tray = new XoopsFormElementTray( _AM_TH_TITLE , '<br /><br />' ) ;
$title_tray->addElement( $title_text ) ;
$title_tray->addElement( new XoopsFormLabel( "" , _MD_ALBM_BATCHBLANK ) ) ;

$cat = new XoopsFormSelect( _AM_TH_CATEGORIES , "cid" , $cid ) ;
$tree = $cattree->getChildTreeArray( 0 , 'title' ) ;
foreach( $tree as $leaf ) {
	$leaf['prefix'] = substr( $leaf['prefix'] , 0 , -1 ) ;
	$leaf['prefix'] = str_replace( "." , "--" , $leaf['prefix'] ) ;
	$cat->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
}

$submitter_select = new XoopsFormSelectUser( _AM_TH_SUBMITTER , 'submitter' , false , $submitter ) ;

$date_text = new XoopsFormText( _AM_TH_DATE , 'post_date' , 20 , 20 , formatTimestamp( time() , _MD_ALBM_DTFMT_YMDHI ) ) ;

$dir_tray = new XoopsFormElementTray( _MD_ALBM_TEXT_DIRECTORY , '<br /><br />' ) ;
$dir_text = new XoopsFormText( _MD_ALBM_FILEPATH , "dir", 50, 255 , $dir4edit ) ;
$dir_tray->addElement( $dir_text ) ;
$dir_tray->addElement( new XoopsFormLabel( _MD_ALBM_DESC_FILEPATH ) ) ;
$desc_tarea = new XoopsFormDhtmlTextarea( _AM_TH_DESCRIPTION , 'desc' , $desc4edit , 10 , 50 ) ;
$submit_button = new XoopsFormButton( '' , "submit" , _SUBMIT , 'submit' ) ;


// Render forms
xoops_cp_header();
include( './mymenu.php' ) ;

echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_BATCHREGISTER,$xoopsModule->name())."</h3>\n" ;
filesharing_opentable();
$form->addElement( $title_tray ) ;
$form->addElement( $desc_tarea ) ;
$form->addElement( $cat ) ;
$form->addElement( $dir_tray ) ;
$form->addElement( $submitter_select ) ;
$form->addElement( $date_text ) ;
$form->addElement( $submit_button ) ;
$form->setRequired( $dir_text ) ;
$form->display() ;
filesharing_closetable();

if( isset( $result_str ) ) {
	echo "<br />\n" ;
	echo $result_str ;
}

xoops_cp_footer() ;

?>