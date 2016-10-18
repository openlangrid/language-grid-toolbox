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
include_once( 'class/apfilesharing.textsanitizer.php' ) ;

include_once( 'class/folderManager.php' );
include_once( 'class/folder.php' );
$error_msg = "";
$myts =& MyAlbumTextSanitizer::getInstance() ;

$userGroup = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
$groups = get_groups();



$folderM = new FolderManager();
$folder = new Folder();
$folder->setId( !is_numeric( @$_GET['cid'] ) ? 0 : intval( @$_GET['cid'] ) );
$folder->setParentId( !is_numeric( @$_GET['pid'] ) ? 1 : intval( @$_GET['pid'] ) );
$folder->setTitle('');
$folder->setDescription('');
$folder->setUserId($my_uid);
$folder->setEditType('public');
$folder->setReadType('public');


$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$folder->getParentId();
if(isset($_POST['ref'])){
	$ref = $_POST['ref'];
}

//------------------------------------------------------------------------------------
// Do Delete
if( ! empty( $_POST['do_delete'] ) ) {
	$folder = $folderM->getFolder($folder->getId());
	$folder_type_user = get_edit_type_and_user($folder->getId());
	$e_type = $folder_type_user['e_type'];
	$e_user = $folder_type_user['e_user'];
	if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($folder->getId()))){
		$can_delete = true;
		if(!$isadmin){
			if(!check_folder_recurrently($folder->getId(),$my_uid)){
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
			if($folder->getId() <= 1 ) die( "Invalid folder id." ) ;

			//delete_folder_recurrently( $folder->getId() ) ;
			$folderM->deleteFolder($folder->getId());
			redirect_header( $mod_url.'/?page=viewcat&cid='.$folder->getParentId() , 3 , _MD_ALBM_DELETINGFILE ) ;
			exit ;
		}
	}else{
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit();
	}

}

// Confirm Delete
if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {
	$folder_type_user = get_edit_type_and_user($folder->getId());
	$e_type = $folder_type_user['e_type'];
	$e_user = $folder_type_user['e_user'];
	if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($folder->getId()))){
		$op_mode = "delete";
		$can_delete = true;
		
		//íœ‚Å‚«‚éƒ†[ƒU[‚ª”»’è
		if(!$isadmin){
			if(!check_folder_recurrently($folder->getId(),$my_uid)){
				$can_delete = false;
				$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
			}
		}
		
		$folder = $folderM->getFolder($folder->getId());
		

		$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$folder->getParentId();

		$parent_folder = _MD_ALBM_MAIN;
		$ftree = get_folder_tree($folder->getId());
		foreach($ftree as $folders){
			foreach($folders as $val){
				if($val['selected']){
					if($val['id'] != $folder->getId()){
						$parent_folder .= ' > '.$val['title'];
					}
				}
			}
		}

		include( XOOPS_ROOT_PATH . "/header.php" ) ;
		$xoopsOption['template_main'] = "apfilesharing_folderdelconf.html" ;
			$tmp=array(
				'error_msg' => $error_msg,
				'can_delete' => $can_delete,
				'folder' => folder_to_array($folder),
				'parent_folder' => $parent_folder,
				'op_mode' => $op_mode,
				'ref' => $ref,
				'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
			);
		

		
		$xoopsTpl->assign(
			$tmp
		);
		
		include( XOOPS_ROOT_PATH . "/footer.php" ) ;
		exit ;
	}else{
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit();
	}
}
//-----------------------------------------------------------------------------------


if( $folder->getId() > 0 ) {

	if(!is_exists_folder($folder->getId())){
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
	$permission_id = $folder->getId();
}else if($op_mode == 'create'){
	$permission_id = $folder->getParentId();
}

$e_type_and_user = get_edit_type_and_user($permission_id);
$e_type = $e_type_and_user['e_type'];
$e_user = $e_type_and_user['e_user'];


if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($permission_id))){
//
}else{
	redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
	exit();
}


$error_msg = "";
if( ! empty( $_POST['submit'] ) ) {	
	if($op_mode=="edit"){
		$folder = $folderM->getFolder($folder->getId());
	}
	if($op_mode=="create"||($op_mode=="edit"&&($isadmin||$folder->getUserId()==$my_uid))){
		$edit_per_post=@$_POST["edit_permission"];
		$read_per_post=@$_POST["read_permission"];
		
		if(is_array($edit_per_post)&&count(array_diff($edit_per_post,is_array($read_per_post)?$read_per_post:array()))!=0){
			$error_msg .= MD_FSA_ERROR_PERMISSION."<br>";
		}
		$e_permission = 'public';
		$select_edit_group = array();

		if(is_array($edit_per_post)){
			if(in_array('public',$edit_per_post )) {
				$e_permission = 'public';
			}else{
				foreach($edit_per_post as $ep_val) {
					if(is_numeric($ep_val)){
						$select_edit_group[] = $ep_val;
					}
				}
				if(count($select_edit_group)==0){
					$e_permission = 'user';
				}else{
					$e_permission = 'protected';
				}
			}
		}else{
			$e_permission = 'user';
		}
				
		$r_permission = 'public';
		$select_read_group = array();
		if(is_array($read_per_post)){
			if(in_array('public', $read_per_post)) {
				$r_permission = 'public';
			}else{
				foreach($read_per_post as $rp_val) {
					if(is_numeric($rp_val)){
						$select_read_group[] = $rp_val;
					}
				}
				if(count($select_read_group)==0){
					$r_permission = 'user';
				}else{
					$r_permission = 'protected';
				}
			}
		}else{
			$r_permission = 'user';
		}
		
		$folder->setEditType($myts->stripSlashesGPC($e_permission)) ;
		$folder->setReadType($myts->stripSlashesGPC($r_permission)) ;
		
	}
	$old_folder_parent_id =  $folder->getParentId();
	$folder->setParentId( @$_POST['cid']);
	$folder->setTitle(trim($myts->stripSlashesGPC( @$_POST["folder_name"] ))) ;
	$folder->setDescription(trim($myts->stripSlashesGPC( @$_POST["desc_text"] ))) ;

	
	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	switch($dummy){
		default:
			$newid = 0;
			$cid = $folder->getId();
			$pid = $folder->getParentId();
			$userId = $my_uid ;
			$title = $folder->getTitle() ;
			$desc_text = $folder->getDescription();
			
			if(trim($title) == ""){
				$error_msg .= _MD_ALBM_NO_FOLDER_NAME_ERROR."<br>";
			}else{
				if(!check_samename_folder($pid,$title,$cid)){
					$error_msg .= _MD_ALBM_FOLDER_SAME_NAME_ERROR."<br>";
				}
			}
			
			if($pid == 1){
				if(!$isadmin && $apfilesharing_rootedit == 0&&$old_folder_parent_id!=$pid){

					$error_msg .= _MD_ALBM_NOT_PARENT_FOLDER_PERMISSION."<br>";
				}
			}else{
				$e_type_and_user = get_edit_type_and_user($pid);

				$e_type = $e_type_and_user['e_type'];
				$e_user = $e_type_and_user['e_user'];

				if($isadmin || $e_type == 'public' ||$old_folder_parent_id==$pid||(($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($pid))){
					//can create
				}else{
					$error_msg .= _MD_ALBM_NOT_PARENT_FOLDER_PERMISSION."<br>";
				}
				if($op_mode == "create"&&!check_folder_accessible($pid,$r_permission,$userId,$select_read_group)){
					$error_msg .= MD_FSA_NOT_PARENT_FOLDER_PERMISSION_FOLDER;
				}
				if(($isadmin||$folder->getUserId()==$my_uid)&&$op_mode == "edit"&&!check_folder_accessible($pid,$r_permission,$userId,$select_read_group)){
					$error_msg .= MD_FSA_NOT_PARENT_FOLDER_PERMISSION_FOLDER;
				}
			}
		
	
			if($op_mode == "edit"){
				// Check if cid is valid
				if( $cid <= 0 ) {
					$error_msg = 'Category is not specified.';
					break;
				}
				if(($isadmin||$folder->getUserId()==$my_uid)){
					if(!check_folder_children($folder->getId(),$r_permission,$userId,$select_read_group)){
						$error_msg .= MD_FSA_NOT_CHILDREN_FOLDER_PERMISSION;
					}
				}
			}
			
			if($error_msg != ""){
				break;
			}else{
				if($op_mode == "create"){
					
					$folder->setDescription($desc_text);
					$folder->setUserId($userId);					
					$folder->setCreateDate(time());
					$folder->setEditDate(time());

					$id = $folderM->createFolder($folder);
					$folderM->createReadPermission($id,$select_read_group);
					$folderM->createEditPermission($id,$select_edit_group);
				}elseif($op_mode == "edit"){
					
					$folder->setId($cid);
					$folder->setDescription($desc_text);
					$folder->setEditDate(time());
					$folderM->updateFolder($folder);
					
					if($isadmin||$folder->getUserId() == $my_uid){
						$folderM->deleteReadPermission($folder->getId());
						$folderM->createReadPermission($folder->getId(),$select_read_group);
						$folderM->deleteEditPermission($folder->getId());
						$folderM->createEditPermission($folder->getId(),$select_edit_group);
					}
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

$xoopsOption['template_main'] = "apfilesharing_createfolder.html" ;



if($op_mode == "edit"){
	$folder = $folderM->getFolder($folder->getId());
	$xoopsTpl->assign('owner_name' ,apfilesharing_get_name_from_uid( $folder->getUserId() )) ;
	$xoopsTpl->assign('owner_info' , XOOPS_URL."/userinfo.php?uid=".$folder->getUserId());
}	
$xoopsTpl->assign('op_mode' , $op_mode );
$xoopsTpl->assign('isadmin' , $isadmin);
$xoopsTpl->assign('my_uid' , $my_uid);


$folder_select = get_folder_select($folder->getParentId(),$folder->getId());
$xoopsTpl->assign(
	array(
		'error_msg' => $error_msg,
		'folder' => folder_to_array($folder),
		'folder_select' => $folder_select,
		'parent_folder' => folder_to_array($folderM->getFolder($folder->getParentId())),
		'parent_read_permission' => @$folderM->getReadPermission($folder->getParentId()),
		'read_permission' => @$folderM->getReadPermission($folder->getId()),
		'edit_permission' => @$folderM->getEditPermission($folder->getId()),
		'user_groups' => $userGroup,
		'groups' => $groups,
		'cid' => @$_GET["cid"],
		'ref' => $ref,
		'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
	)
);


include( XOOPS_ROOT_PATH . "/footer.php" ) ;


?>