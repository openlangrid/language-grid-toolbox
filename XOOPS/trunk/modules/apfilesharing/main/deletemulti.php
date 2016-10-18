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

include_once 'class/fileManager.php' ;
include_once 'class/folderManager.php' ;
include_once 'class/file.php' ;
include_once 'class/folder.php' ;


$folderM = new FolderManager();
$fileM = new FileManager();

$file_id = empty( $_POST["delete"] ) ? null :  $_POST["delete"] ;

	$error_msg = "";

	// Get the record
	@$folder_array = $_POST["cid"];
	@$file_array = $_POST["lid"];
if(!(empty($folder_array)&&empty($file_array))){
	@$samplefile = $fileM->getFile($file_array[0]);
	if($samplefile != null){
		$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$samplefile->getFolderId();
	}
	else{
		@$samplefolder = $folderM->getFolder($folder_array[0]);
		$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$samplefolder->getParentId();
	}
		
	if(isset($_POST['ref'])){
		$ref = $_POST['ref'];
	}



	// Do Delete
	if( ! empty( $_GET['do_delete'] ) ) {

		// Ticket Check
		if ( ! $xoopsGTicket->check() ) {
			redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
		}
			
		//Delete files
		for($i =0; $i <count($file_array);$i++){
			$file_id = $file_array[$i];
			
			$file = $fileM->getFile($file_id);
			

		// get and check file_id is valid
			if( $file_id < 1 ) die( "Invalid file id." ) ;
			
			$e_type= $file->getEditType();
			if($isadmin || $e_type == 'public' || (($e_type == 'user' || $e_type == 'protected' )&& $file->getUserId() == $my_uid)||($e_type == 'protected' && check_edit_permission_group($file_id,2))){
				$whr = "file_id = $file_id" ;
				$fileM->deleteFileCond( $whr ) ;
			}
		}
		
		//delete folders
		for($i =0; $i <count($folder_array);$i++){
			$folder_id = $folder_array[$i];
			$folder = $folderM->getFolder($folder_id);
			$can_delete = true;
			$fol_id = $folder->getId();
			if(!$isadmin){
				if(!check_folder_recurrently($fol_id,$my_uid)){
					$can_delete = false;
					$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
					$_POST['conf_delete'] = 1;
				}
			}
		
			if($can_delete){

				// get and check lid is valid
				if($fol_id <= 1 ) die( "Invalid folder id." ) ;

				$folderM->deleteFolder( $fol_id ) ;

			}
		}
		
		if($file != null){
			redirect_header( $mod_url.'/?page=viewcat&cid='.$file->getFolderId() , 3 , _MD_ALBM_DELETINGFILE ) ;
		}else{
			redirect_header( $mod_url.'/?page=viewcat&cid='.$folder->getParentId() , 3 , _MD_ALBM_DELETINGFILE ) ;
		}
		
		//@redirect_header( $mod_url) ;
		exit ;
	}


	// Confirm Delete
	if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {
		
		//Confirm files
		$file_for_tpl = array();
		for($i =0; $i <count($file_array);$i++){
			$file_id = $file_array[$i];
			$file = $fileM->getFile($file_id);

			$file_for_tpl[$i] = get_array_for_file_assign(file_to_array($file)) ;
			$e_type = $file->getEditType();

			
			if(!($isadmin || $e_type == 'public' || (($e_type == 'user' || $e_type == 'protected' )&& $file->getUserId() == $my_uid)||($e_type == 'protected' && check_edit_permission_group($file_id,2)))){
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
		}
		//confirm folders
		$folder_for_tpl = array();
		for($i=0;$i< count($folder_array);$i++){
			$folder_id = $folder_array[$i];
			$folder = $folderM->getFolder($folder_id);
			$folder_for_tpl[$i] = folder_to_array($folder) ;
			$can_delete = true;
			$e_type = $folder->getEditType();
			$e_user = $folder->getUserId();
			
			if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($folder_id))){
				if(!$isadmin){
					if(!check_folder_recurrently($folder_id,$my_uid)){
						$can_delete = false;
						$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
					}
				}
			}else{
				redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
				exit ;
			}
			$folder = $folderM->getFolder($folder->getId());
			
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
		}
		
		include( XOOPS_ROOT_PATH . "/header.php" ) ;
		$xoopsOption['template_main'] = "apfilesharing_multidelconf.html" ;
		$xoopsTpl->assign(
			array(
				'error_msg' => $error_msg,
				'file_for_tpl' => $file_for_tpl,
				'folder_for_tpl' => $folder_for_tpl,
				'parent_folder' => $parent_folder,
				'lid' => $file_id,
				'ref' => $ref,
				'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
			)
		);



		include( XOOPS_ROOT_PATH . "/footer.php" ) ;
		
		exit ;
	}
	
}else{
	redirect_header( $mod_url) ;
	exit();
}




function get_array_for_file_assign(&$file){
	$file['owner_name'] = apfilesharing_get_name_from_uid( $file['user_id']);
	$file['owner_info'] = XOOPS_URL."/userinfo.php?uid=".$file['user_id'];
	
	return $file;
}
?>