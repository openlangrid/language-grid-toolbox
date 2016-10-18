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

include 'header.php' ;
include_once 'class/myuploader.php' ;
include_once 'class/filesharing.textsanitizer.php' ;

$myts =& MyAlbumTextSanitizer::getInstance() ;

$lid = empty( $_POST["delete"] ) ? array() :  $_POST["delete"] ;

$error_msg = "";

// Get the record
$file = array();
$ref = array();
for($i =0; $i <count($lid);$i++){
	$file[$i] = get_file_result(&$xoopsDB,intval($lid[$i]));

	$ref[$i] = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$file[$i]['cid'];
	if(isset($_POST['ref'])){
		$ref[$i] = $_POST['ref'];
	}
}



// Do Delete
if( ! empty( $_POST['do_delete'] ) ) {

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}
	
	for($i =0; $i <count($lid);$i++){

	// get and check lid is valid
		if( $lid < 1 ) die( "Invalid file id." ) ;

		$whr = "lid=$lid" ;
		if( !$isadmin && ($file[$i]['edit_type'] == 'user' && $file[$i]['edit_user'] != $my_uid)) {
			$whr .= " AND submitter=$my_uid" ;
		}
	
		filesharing_delete_files( $whr ) ;

		redirect_header( $mod_url.'/?page=view	cat&cid='.$file[$i]['cid'] , 3 , _MD_ALBM_DELETINGFILE ) ;
		
	}
exit ;
}


// Confirm Delete
	if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {

		for($i =0; $i <count($lid);$i++){

		$file_for_tpl = get_array_for_file_assign( $file[$i] ) ;

	if( !$isadmin && ($file[$i]['edit_type'] == 'user' && $file[$i]['edit_user'] != $my_uid)) {
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit ;
	}

		$parent_folder = _MD_ALBM_MAIN;
		$ftree = get_folder_tree(@$file[$i]['cid']);
		foreach($ftree as $folders){
			foreach($folders as $val){
				if($val['selected']){
					$parent_folder .= ' > '.$val['title'];
				}
			}
		}
	}
	include( XOOPS_ROOT_PATH . "/header.php" ) ;
	$xoopsOption['template_main'] = "filesharing_delconf.html" ;
	$xoopsTpl->assign(
		array(
			'error_msg' => $error_msg,
			'file_for_tpl' => $file_for_tpl,
			'parent_folder' => $parent_folder,
			'lid' => $lid[$i],
			'ref' => $ref[$i],
			'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
		)
	);
	/*
	echo "
	<h4>"._MD_ALBM_FILEDEL."</h4>
	<div>
		<img src='$thumbs_url/$lid.$ext' />
		<br />
		<form action='?page=editfile&lid=$lid' method='post'>
			".$xoopsGTicket->getTicketHtml( __LINE__ )."
			<input type='submit' name='do_delete' value='"._YES."' />
			<input type='submit' name='cancel_delete' value="._NO." />
		</form>
	</div>
	\n" ;
	*/
	
	include( XOOPS_ROOT_PATH . "/footer.php" ) ;
		
	exit ;
	}
	
// Do Modify
if( ! empty( $_POST['submit'] ) ) {
	$file['cid'] = !is_numeric(@$_POST['cid']) ? 1 : intval(@$_POST['cid']) ;
	$file['description'] = trim($myts->stripSlashesGPC( @$_POST["desc_text"] )) ;
	$file['edit_type'] = $myts->stripSlashesGPC( @$_POST["edit_permission"] ) ;
	$file['read_type'] = $myts->stripSlashesGPC( @$_POST["read_permission"] ) ;

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}
	switch($dummy){
		default:
			$cid = $file['cid'];
			
			// Check if cid is valid
			if( $cid <= 0 ) {
				$error_msg .= 'Category is not specified.<br>';
			}
			
			if(!check_samename_file($cid,$file['title'],$file['lid'])){
				$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
			}
			
			if($cid == 1){
				if(!$isadmin && $filesharing_rootedit == 0){
					$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
				}
			}else{
				$sql  = " SELECT edit_permission_type,edit_permission_user ";
				$sql .= " FROM ".$table_cat." ";
				$sql .= " WHERE cid = ".$cid." ";
				$rs = $xoopsDB->query( $sql );
				list($e_type,$e_user) = $xoopsDB->fetchRow( $rs );
				if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
					//can create
				}else{
					$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
				}
			}
			
			if($error_msg != ""){break;}
			
			$sql  = "";
			$sql .= "UPDATE ".$table_files." SET ";
			$sql .= "cid = ".$file['cid'].",";
			$sql .= "description = '".addslashes($file['description'])."',";
			if(trim($file['read_type']) != ""){
				$sql .= "read_permission_type  = '".trim($file['read_type'])."',";
			}
			if(trim($file['edit_type']) != ""){
				$sql .= "edit_permission_type  = '".trim($file['edit_type'])."',";
			}
			$sql .= "edit_date = ".time().", ";
			$sql .= "status = 2 ";
			$sql .= "WHERE lid = ".$file['lid'];
			$xoopsDB->query( $sql ) or die( "DB error: UPDATE folder table" ) ;
			
			$redirect_uri = "?page=viewcat&amp;cid=$cid" ;
			redirect_header( $redirect_uri , 2 , _MD_ALBM_RECEIVED ) ;
			break;
		break;
	}
}


// Editing Display
include(XOOPS_ROOT_PATH."/header.php");
include_once( "../../class/xoopsformloader.php" ) ;
include_once( "../../include/xoopscodes.php" ) ;

$xoopsOption['template_main'] = "filesharing_editfile.html" ;

$xoopsTpl->assign('isadmin' , $isadmin);
$xoopsTpl->assign('my_uid' , $my_uid);

$file_for_tpl = get_array_for_file_assign( $file ) ;
$folder_select = get_folder_select($file['cid']);
/*
$cat_select = "<option value=''>----</option>\n"; ;
$tree = $cattree->getChildTreeArray( 0 , "title" ) ;
foreach( $tree as $leaf ) {
	$leaf['prefix'] = substr( $leaf['prefix'] , 0 , -1 ) ;
	$leaf['prefix'] = str_replace( "." , "--" , $leaf['prefix'] ) ;
	$cat_select .= "<option value='".$leaf['cid']."'";
	if($leaf['cid'] == $file['cid']){$cat_select .= " selected";}
	$cat_select .= ">".$leaf['prefix'] . $leaf['title']."</option>\n"; ;
}
*/
/*
$is_delete = false;
if( $global_perms & GPERM_DELETABLE ) {
	$is_delete = true;
}
$status_hidden = "";
if( $isadmin ) {
	$status_hidden = new XoopsFormHidden( "old_status" , $file['status'] ) ;
}
*/

$xoopsTpl->assign(
	array(
		'error_msg' => $error_msg,
		'file_for_tpl' => $file_for_tpl,
		'folder_select' => $folder_select,
		'lid' => $lid,
		'ref' => $ref,
		'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
	)
);

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

function get_file_result(&$xoopsDB,$lid){
	global $table_files,$table_text,$table_cat;
	
	$sql = "";
	$sql .= " SELECT  ";
	$sql .= "  lid, ";
	$sql .= "  cid, ";
	$sql .= "  title, ";
	$sql .= "  ext, ";
	$sql .= "  submitter, ";
	$sql .= "  description,";
	$sql .= "  read_permission_type as read_type,";
	$sql .= "  read_permission_user as read_user,";
	$sql .= "  edit_permission_type as edit_type,";
	$sql .= "  edit_permission_user as edit_user ";
	$sql .= " FROM ".$table_files." ";
	$sql .= " WHERE lid=".intval($lid)." ";

	$result = $xoopsDB->query($sql) ;
	return $xoopsDB->fetchArray( $result ) ;
}

function get_array_for_file_assign(&$file){
	$file['owner_name'] = filesharing_get_name_from_uid( $file['submitter']);
	@$file['owner_info'] = XOOPS_URL."/userinfo.php?uid=".$file['submitter'];
	
	return $file;
}
?>