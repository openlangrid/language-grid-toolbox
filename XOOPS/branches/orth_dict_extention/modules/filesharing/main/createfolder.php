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

include( 'header.php' ) ;
//include_once( XOOPS_ROOT_PATH . '/class/xoopstree.php' ) ;
//include_once( 'class/myuploader.php' ) ;
include_once( 'class/filesharing.textsanitizer.php' ) ;
$error_msg = "";
$myts =& MyAlbumTextSanitizer::getInstance() ;
//$cattree = new XoopsTree( $table_cat , "cid" , "pid" ) ;

$folder = array(
	'cid' => ( !is_numeric( @$_GET['cid'] ) ? 0 : intval( @$_GET['cid'] ) ) ,
	'pid' => ( !is_numeric( @$_GET['pid'] ) ? 1 : intval( @$_GET['pid'] ) ) ,
	'title' => '' ,
	'description' => '' ,
	'user_id' => $my_uid,
	'edit_type' => 'public',
	'read_type' => 'public'
) ;
$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$folder['pid'];
if(isset($_POST['ref'])){
	$ref = $_POST['ref'];
}


// Do Delete
if( ! empty( $_POST['do_delete'] ) ) {
	$folder = get_folder_result($xoopsDB,$folder['cid']);

	$can_delete = true;
	if(!$isadmin){
		if(!check_folder_recurrently($folder['cid'],$my_uid)){
			$can_delete = false;
			$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
			$_POST['conf_delete'] = 1;
		}
	}
	
	if($can_delete){
		// Ticket Check
		if ( ! $xoopsGTicket->check() ) {
			redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
		}

		// get and check lid is valid
		if($folder['cid'] <= 1 ) die( "Invalid folder id." ) ;

		delete_folder_recurrently( $folder['cid'] ) ;

		redirect_header( $mod_url.'/?page=viewcat&cid='.$folder['pid'] , 3 , _MD_ALBM_DELETINGFILE ) ;
		exit ;
	}
}

// Confirm Delete
if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {
	$op_mode = "delete";
	$can_delete = true;
	
	if(!$isadmin){
		if(!check_folder_recurrently($folder['cid'],$my_uid)){
			$can_delete = false;
			$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
		}
	}
	
	$folder = get_folder_result($xoopsDB,$folder['cid']);
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$folder['pid'];

	$parent_folder = _MD_ALBM_MAIN;
	$ftree = get_folder_tree($folder['cid']);
	foreach($ftree as $folders){
		foreach($folders as $val){
			if($val['selected']){
				if($val['id'] != $folder['cid']){
					$parent_folder .= ' > '.$val['title'];
				}
			}
		}
	}

	include( XOOPS_ROOT_PATH . "/header.php" ) ;
	$xoopsOption['template_main'] = "filesharing_folderdelconf.html" ;
	$xoopsTpl->assign(
		array(
			'error_msg' => $error_msg,
			'can_delete' => $can_delete,
			'folder' => $folder,
			'parent_folder' => $parent_folder,
			'op_mode' => $op_mode,
			'ref' => $ref,
			'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
		)
	);
	
	include( XOOPS_ROOT_PATH . "/footer.php" ) ;
	exit ;
}

//-----------------------------------------------------------------------------------

if( $folder['cid'] > 0 ) {
	$sql  = '';
	$sql .= 'SELECT COUNT(cid) FROM '.$table_cat.' WHERE cid = '.$folder['cid'].' ';
	$prs = $xoopsDB->query($sql);
	list( $is_Exists ) = $xoopsDB->fetchRow( $prs );
	if($is_Exists == 0){
		redirect_header($ref,3,'');
	}
	
	$op_mode = "edit";
}else{
	$op_mode = "create";
}

if( ! empty( $_POST['op_mode'])) {
	$op_mode = $_POST['op_mode'];
}

if($op_mode == "edit"){
	$folder = get_folder_result($xoopsDB,$folder['cid']);
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$folder['pid'];
}

$error_msg = "";
if( ! empty( $_POST['submit'] ) ) {
	$folder['pid'] = @$_POST['cid'];
	$folder['title'] = trim($myts->stripSlashesGPC( @$_POST["folder_name"] )) ;
	$folder['description'] = trim($myts->stripSlashesGPC( @$_POST["desc_text"] )) ;
	$folder['edit_type'] = $myts->stripSlashesGPC( @$_POST["edit_permission"] ) ;
	$folder['read_type'] = $myts->stripSlashesGPC( @$_POST["read_permission"] ) ;
	
	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	switch($dummy){
		default:
			$newid = 0;
			$cid = $folder['cid'];
			$pid = $folder['pid'];
			$submitter = $my_uid ;
			$title = $folder['title'] ;
			$desc_text = $folder['description'];

			if(trim($title) == ""){
				$error_msg .= _MD_ALBM_NO_FOLDER_NAME_ERROR."<br>";
			}else{
				if(!check_samename_folder($pid,$title,$cid)){
					$error_msg .= _MD_ALBM_FOLDER_SAME_NAME_ERROR."<br>";
				}
			}
			
			if($pid == 1){
				if(!$isadmin && $filesharing_rootedit == 0){
					$error_msg .= _MD_ALBM_NOT_PARENT_FOLDER_PERMISSION."<br>";
				}
			}else{
				$sql  = " SELECT edit_permission_type,edit_permission_user ";
				$sql .= " FROM ".$table_cat." ";
				$sql .= " WHERE cid = ".$pid." ";
				$rs = $xoopsDB->query( $sql );
				list($e_type,$e_user) = $xoopsDB->fetchRow( $rs );
				if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
					//can create
				}else{
					$error_msg .= _MD_ALBM_NOT_PARENT_FOLDER_PERMISSION."<br>";
				}
			}
			
			if($op_mode == "edit"){
				// Check if cid is valid
				if( $cid <= 0 ) {
					$error_msg = 'Category is not specified.';
					break;
				}
				
				$pre_folder = get_folder_result($xoopsDB,$folder['cid']);
				if($pre_folder['pid'] != $folder['pid']){	//move
					if(!$isadmin){
						if(!check_folder_recurrently($folder['cid'],$my_uid)){
							$error_msg = _MD_ALBM_NOT_MOVE_FOLDER;
							break;
						}
					}
				}
			}
			
			if($error_msg != ""){
				break;
			}else{
				if($op_mode == "create"){
					$sql1 = "";$sql2 = "";
					$sql1 .= "pid,";                      $sql2 .= "".$pid.",";
					$sql1 .= "title,";                    $sql2 .= "'".addslashes($title)."',";
					$sql1 .= "description,";              $sql2 .= "'".addslashes($desc_text)."',";
					$sql1 .= "create_date,";              $sql2 .= "".time().",";
					$sql1 .= "edit_date,";                $sql2 .= "".time().",";
					$sql1 .= "user_id,";                  $sql2 .= "".$submitter.",";
					$sql1 .= "read_permission_type,";     $sql2 .= "'".trim($folder['read_type'])."',";
					$sql1 .= "read_permission_user,";     $sql2 .= "".$submitter.",";
					$sql1 .= "edit_permission_type,";     $sql2 .= "'".trim($folder['edit_type'])."',";
					$sql1 .= "edit_permission_user ";     $sql2 .= "".$submitter." ";
					
					$sql  = "INSERT INTO ".$table_cat."(".$sql1.")VALUES(".$sql2.")";
					$xoopsDB->query( $sql ) or die( "DB error: INSERT folder table" ) ;
				}elseif($op_mode == "edit"){
					$sql  = "UPDATE ".$table_cat." SET ";
					$sql .= "pid          = ".$pid.",";
					$sql .= "title        = '".addslashes($title)."',";
					$sql .= "description  = '".addslashes($desc_text)."',";
					if(trim($folder['read_type']) != ""){
						$sql .= "read_permission_type  = '".trim($folder['read_type'])."',";
					}
					if(trim($folder['edit_type']) != ""){
						$sql .= "edit_permission_type  = '".trim($folder['edit_type'])."',";
					}
					$sql .= "edit_date    = ".time()." ";
					$sql .= "WHERE cid = ".$folder['cid'];
					$xoopsDB->query( $sql ) or die( "DB error: UPDATE folder table" ) ;
				}
				$redirect_uri = "?page=viewcat&cid=".$pid ;
				redirect_header( $redirect_uri , 2 , _MD_ALBM_RECEIVED ) ;
				exit ;
			}
		break;
	}
}

// Editing Display

include( XOOPS_ROOT_PATH . "/header.php" ) ;
include_once( "../../class/xoopsformloader.php" ) ;
include_once( "../../include/xoopscodes.php" ) ;

$xoopsOption['template_main'] = "filesharing_createfolder.html" ;

if($op_mode == "edit"){
	$sql  = " SELECT edit_permission_type,edit_permission_user ";
	$sql .= " FROM ".$table_cat." ";
	$sql .= " WHERE cid = ".$folder['cid']." ";
	$rs = $xoopsDB->query( $sql );
	list($e_type,$e_user) = $xoopsDB->fetchRow( $rs );
	if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
		//can edit
	}else{
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit();
	}
	$xoopsTpl->assign('owner_name' ,filesharing_get_name_from_uid( $folder['user_id'] )) ;
	$xoopsTpl->assign('owner_info' , XOOPS_URL."/userinfo.php?uid=".$folder['user_id']);
}
$xoopsTpl->assign('op_mode' , $op_mode );
$xoopsTpl->assign('isadmin' , $isadmin);
$xoopsTpl->assign('my_uid' , $my_uid);

/*
$cat_select = "<option value=''>----</option>\n"; ;
$tree = $cattree->getChildTreeArray( 0 , "title" ) ;
foreach( $tree as $leaf ) {
	$leaf['prefix'] = substr( $leaf['prefix'] , 0 , -1 ) ;
	$leaf['prefix'] = str_replace( "." , "--" , $leaf['prefix'] ) ;
	$cat_select .= "<option value='".$leaf['cid']."'";
	if($leaf['cid'] == $filder['cid']){$cat_select .= " selected";}
	$cat_select .= ">".$leaf['prefix'] . $leaf['title']."</option>\n"; ;
}
*/
$folder_select = get_folder_select($folder['pid'],$folder['cid']);

$xoopsTpl->assign(
	array(
		'error_msg' => $error_msg,
		'folder' => $folder,
		'folder_select' => $folder_select,
		'cid' => @$_GET["cid"],
		'ref' => $ref,
		'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
	)
);

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

function get_folder_result(&$xoopsDB,$cid){
	global $table_cat;
	
	$sql = "";
	$sql .= " SELECT  ";
	$sql .= "  cid, ";
	$sql .= "  pid, ";
	$sql .= "  title, ";
	$sql .= "  description,";
	$sql .= "  user_id, ";
	$sql .= "  read_permission_type as read_type,";
	$sql .= "  read_permission_user as read_user,";
	$sql .= "  edit_permission_type as edit_type,";
	$sql .= "  edit_permission_user as edit_user ";
	$sql .= " FROM ".$table_cat." ";
	$sql .= " WHERE cid=".intval($cid)." ";

	$result = $xoopsDB->query($sql) ;
	return $xoopsDB->fetchArray( $result ) ;
	
}
?>