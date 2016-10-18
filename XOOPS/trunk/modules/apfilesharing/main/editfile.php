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
include_once 'class/apfilesharing.textsanitizer.php' ;

$myts =& MyAlbumTextSanitizer::getInstance() ;

$userGroup = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
$groups = get_groups();

$file_id = empty( $_GET['lid'] ) ? 0 : intval( $_GET['lid'] ) ;

$error_msg = "";

// Get the record
require_once( 'class/fileManager.php' );
require_once( 'class/file.php' );

$fileM = new FileManager();
$file = $fileM->getFile($file_id);
$e_type = $file->getEditType();
$user_id = $file->getUserId();
$old_folder_id = $file->getFolderId();
if($isadmin || $e_type == 'public' || (($e_type == 'user' || $e_type == 'protected' )&& $user_id == $my_uid)||($e_type == 'protected' && check_edit_permission_group($file_id,2))){
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$file->getFolderId();
	if(isset($_POST['ref'])){
		$ref = $_POST['ref'];
	}

	// Do Delete
	if( ! empty( $_POST['do_delete'] ) ) {

		// Ticket Check
		if ( ! $xoopsGTicket->check() ) {
			redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
		}

		// get and check file_id is valid
		if( $file_id < 1 ) die( "Invalid file id." ) ;

		$whr = " file_id=$file_id" ;
		if( !$isadmin && ($e_type == 'user' && $user_id != $my_uid)) {
			$whr .= " AND user_id=$my_uid" ;
		}
		
		$fileM->deleteFileCond( $whr ) ;

		redirect_header( $mod_url.'/?page=viewcat&cid='.$file->getFolderId() , 3 , _MD_ALBM_DELETINGFILE ) ;
		exit ;
	}


	// Confirm Delete
	if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {
		
		$file_for_tpl = get_array_for_file_assign( $file ) ;

		if( !$isadmin && ($e_type == 'user' && $user_id != $my_uid)) {
			redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
			exit ;
		}

		$parent_folder = _MD_ALBM_MAIN;
		$ftree = get_folder_tree($file->getFolderId());
		foreach($ftree as $folders){
			foreach($folders as $val){
				if($val['selected']){
					$parent_folder .= ' > '.$val['title'];
				}
			}
		}
		include( XOOPS_ROOT_PATH . "/header.php" ) ;
		$xoopsOption['template_main'] = "apfilesharing_delconf.html" ;
		$xoopsTpl->assign(
			array(
				'error_msg' => $error_msg,
				'file_for_tpl' => $file_for_tpl,
				'parent_folder' => $parent_folder,
				'lid' => $file_id,
				'ref' => $ref,
				'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
			)
		);

		
		include( XOOPS_ROOT_PATH . "/footer.php" ) ;
		exit ;
	}

	// Do Modify
	if( ! empty( $_POST['submit'] ) ) {
		if($isadmin||$user_id==$my_uid){
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
			
			$file->setEditType($myts->stripSlashesGPC( $e_permission )) ;
			$file->setReadType($myts->stripSlashesGPC( $r_permission )) ;
		}
		$file->setFolderId(!is_numeric(@$_POST['cid']) ? 1 : intval(@$_POST['cid']));
		$file->setDescription(trim($myts->stripSlashesGPC( @$_POST["desc_text"] ))) ;


		// Ticket Check
		if ( ! $xoopsGTicket->check() ) {
			redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
		}
		switch($dummy){
			default:
				$folder_id = $file->getFolderId();
				
				// Check if cid is valid
				if( $folder_id <= 0 ) {
					$error_msg .= 'Category is not specified.<br>';
				}
				
				if(!check_samename_file($folder_id,$file->getTitle(),$file->getId())){
					$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
				}
				
				if($folder_id == 1){
					if(!$isadmin && $apfilesharing_rootedit == 0 && $old_folder_id!=$folder_id){
						$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
					}
				}else{
					$folder_type_user = get_edit_type_and_user($folder_id);
					$e_type = $folder_type_user['e_type']; 
					$e_user = $folder_type_user['e_user'];
					if($isadmin || $e_type == 'public' ||$old_folder_id==$folder_id||(($e_type == 'user'||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($folder_id))){
						//can create
					}else{
						$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
					}
				}
				
				if(($isadmin||$user_id==$my_uid)&&!check_file_accessible($folder_id,$r_permission,$user_id,$select_read_group)){
					$error_msg .= MD_FSA_NOT_PARENT_FOLDER_PERMISSION_FILE;
				}
				
				if($error_msg != ""){break;}
								
				$fileM->updateFile($file);
				$fileM->deleteReadPermission($file_id);
				$fileM->createReadPermission($file_id,$select_read_group);
				$fileM->deleteEditPermission($file_id);
				$fileM->createEditPermission($file_id,$select_edit_group);
				
				$redirect_uri = "?page=viewcat&amp;cid=$folder_id" ;
				redirect_header( $redirect_uri , 2 , _MD_ALBM_RECEIVED ) ;
				break;
			break;
		}
	}


	// Editing Display
	include(XOOPS_ROOT_PATH."/header.php");
	include_once( "../../class/xoopsformloader.php" ) ;
	include_once( "../../include/xoopscodes.php" ) ;

	$xoopsOption['template_main'] = "apfilesharing_editfile.html" ;

	$xoopsTpl->assign('isadmin' , $isadmin);
	$xoopsTpl->assign('my_uid' , $my_uid);

	$file_for_tpl = get_array_for_file_assign( $file ) ;
	$folder_select = get_folder_select($file->getFolderId());


	$xoopsTpl->assign(
		array(
			'error_msg' => $error_msg,
			'file_for_tpl' => $file_for_tpl,
			'folder_select' => $folder_select,
			'user_groups' => $userGroup,
			'groups' => $groups,
			'read_permission' => $fileM->getReadPermission($file_id),
			'edit_permission' => $fileM->getEditPermission($file_id),
			'lid' => $file_id,
			'ref' => $ref,
			'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
		)
	);
}else{
	redirect_header($mod_url);
}
include( XOOPS_ROOT_PATH . "/footer.php" ) ;

// function get_file_result(&$xoopsDB,$lid){
	// global $table_files,$table_text,$table_folder;
	
	// $sql = "";
	// $sql .= " SELECT  ";
	// $sql .= "  lid, ";
	// $sql .= "  folder_id, ";
	// $sql .= "  title, ";
	// $sql .= "  ext, ";
	// $sql .= "  submitter, ";
	// $sql .= "  description,";
	// $sql .= "  read_permission_type as read_type,";
	// $sql .= "  read_permission_user as read_user,";
	// $sql .= "  edit_permission_type as edit_type,";
	// $sql .= "  edit_permission_user as edit_user ";
	// $sql .= " FROM ".$table_files." ";
	// $sql .= " WHERE lid=".intval($lid)." ";

	// $result = $xoopsDB->query($sql) ;
	// return $xoopsDB->fetchArray( $result ) ;
// }

function get_array_for_file_assign($file){
	
	$fileArray = file_to_array($file); 
	$fileArray['owner_name'] = apfilesharing_get_name_from_uid( $file->getUserId());
	$fileArray['owner_info'] = XOOPS_URL."/userinfo.php?uid=".$file->getUserId();
	
	return $fileArray;
}
?>