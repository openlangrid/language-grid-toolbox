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
include_once( 'class/myuploader.php' ) ;
include_once( 'class/apfilesharing.textsanitizer.php' ) ;

$myts =& MyAlbumTextSanitizer::getInstance() ;

//permission group
$userGroup = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
$groups = get_groups();

// check Categories exist
$result = $xoopsDB->query( "SELECT count(folder_id) as count FROM $table_folder" ) ;
list( $count ) = $xoopsDB->fetchRow( $result ) ;
if( $count < 1 ) {
	redirect_header( XOOPS_URL."/modules/$mydirname/" , 2 , _MD_ALBM_MUSTADDCATFIRST ) ;
	exit ;
}

// check file_uploads = on
if( ! ini_get( "file_uploads" ) ) $file_uploads_off = true ;

// get flag of safe_mode
$safe_mode_flag = ini_get( "safe_mode" ) ;

// check or make s_dir
if( ! is_dir( $files_dir ) ) {
	if( $safe_mode_flag ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",10,"At first create & chmod 777 '$files_dir' by ftp or shell.");
		exit ;
	}

	$rs = mkdir( $files_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",10,"$files_dir is not a directory");
		exit ;
	} else @chmod( $files_dir , 0777 ) ;
}

// check or make thumbs_dir
if( $apfilesharing_makethumb && ! is_dir( $thumbs_dir ) ) {
	if( $safe_mode_flag ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",10,"At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
		exit ;
	}

	$rs = mkdir( $thumbs_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",10,"$thumbs_dir is not a directory");
		exit ;
	} else @chmod( $thumbs_dir , 0777 ) ;
}

// check or set permissions of files_dir
if( ! is_writable( $files_dir ) || ! is_readable( $files_dir ) ) {
	$rs = chmod( $files_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",5,"chmod 0777 into $files_dir failed");
		exit ;
	}
}

// check or set permissions of thumbs_dir
if( $apfilesharing_makethumb && ! is_writable( $thumbs_dir ) ) {
	$rs = chmod( $thumbs_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",5,"chmod 0777 into $thumbs_dir failed");
		exit ;
	}
}

include_once( 'class/file.php' );

$file = new File();
$file->setFolderId( empty( $_GET['cid'] ) ? 1 : intval( $_GET['cid'] ) );
$file->setDescription('');
$file->setTitle('');
$file->setEditType('public');
$file->setReadType('public');


$folder_type_user = get_edit_type_and_user($file->getFolderId());
$e_type = $folder_type_user['e_type'];
$e_user = $folder_type_user['e_user'];
if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($file->getFolderId()))){
	
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$file->getFolderId();
	if(isset($_POST['ref'])){
		$ref = $_POST['ref'];
	}

	$error_msg = "";

	if( ! empty( $_POST['submit'] ) ) {
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
		
		$file->setFolderId( @$_POST['cid']);
		$file->setDescription(trim($myts->stripSlashesGPC( @$_POST["desc_text"] )));
		$file->setEditType($myts->stripSlashesGPC( $e_permission )) ;
		$file->setReadType($myts->stripSlashesGPC( $r_permission )) ;
		
		// Ticket Check
		if ( ! $xoopsGTicket->check() ) {
			redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
		}
		

		
		switch($dummy){
			default:

				$user_id = $my_uid ;
				$file->setFolderId(!is_numeric($file->getFolderId()) ? 1 : intval( $file->getFolderId() )) ;
				$folder_id = $file->getFolderId();
				$newid = $xoopsDB->genId( $table_files."_lid_seq" ) ;
				
				// Check if folder_id is valid
				if( $folder_id <= 0 ) {
					$error_msg .= 'Category is not specified.<br>';
				}
				
				if($folder_id == 1){
					if(!$isadmin && $apfilesharing_rootedit == 0){
						$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
					}
				}else{
					$folder_type_user = get_edit_type_and_user($folder_id);
					$e_type = $folder_type_user['e_type']; 
					$e_user = $folder_type_user['e_user'];
					if($isadmin || $e_type == 'public' || (($e_type == 'user' ||$e_type == 'protected') && $e_user == $my_uid)||($e_type == 'protected' && check_edit_permission_group($folder_id))){
						//can create
					}else{
						$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
					}
				}

				// Check if upload file name specified
				$field = @$_POST["xoops_upload_file"][0] ;
				
				
				if( empty( $field ) || $field == "" ) {
					$error_msg .= "UPLOAD error: file name not specified<br>";
				}
				
				if( $_FILES[$field]['name'][0] == "" ) {
					$error_msg .= _MD_ALBM_NOIMAGESPECIFIED."<br>";
					break;
				}else{
					if(!check_samename_file($folder_id,$_FILES[$field]['name'][0])){
						$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
					}
				}
				
				if( $_FILES[$field]['tmp_name'] == "" ) {
					$error_msg .= _MD_ALBM_FILEERROR."<br>";
					break;
				}
				if(!check_file_accessible($folder_id,$r_permission,$user_id,$select_read_group)){
					$error_msg .= MD_FSA_NOT_PARENT_FOLDER_PERMISSION_FILE;
				}
				
				
				if($error_msg != ""){break;}

				$ErrCode = 0;
				switch($dummy){
					default:
						if($_FILES[$field]["error"]){$ErrCode=1;break;}
						if(!is_uploaded_file($_FILES[$field]["tmp_name"])){$ErrCode=2;break;}
						if($_FILES[$field]["size"] > ($apfilesharing_fsize*1000000)){$ErrCode=2;break;}
					break;
				}
				if($ErrCode != 0){
					$error_msg .= _MD_ALBM_FILEERROR."<br>";
				}
				for($i=0;$i<count($_FILES["upload_file"]["name"]);$i++){
					set_time_limit(0);
					$uploader = new MyXoopsMediaUploader( $files_dir , $array_allowed_mimetypes , ($apfilesharing_fsize*1000000) , $apfilesharing_width , $apfilesharing_height , $array_allowed_exts ) ;
				
					$uploader->setPrefix( 'tmp_' ) ;

					if( $uploader->fetchMedia( $field,$i) && $uploader->upload() ) {


					// Succeed to upload
						$title = $uploader->getMediaName() ;
						$tmp_name = $uploader->getSavedFileName() ;


					} else {
						@unlink( $uploader->getSavedDestination() ) ;
						$error_msg = $uploader->getErrors();

						continue;
					}
					
					
					if( ! is_readable( "$files_dir/$tmp_name" ) ) {
						$error_msg = _MD_ALBM_FILEREADERROR;
						break;
					}
					
					if(!check_samename_file($folder_id,$title[$i])){
						$error_msg = _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
						continue;
					}

					$desc_text = $file->getDescription() ;
					$date = time() ;
					$status = 1 ;
					$ext = substr( strrchr( $tmp_name , '.' ) , 1 );
					
					$insertFile = new File();
					$insertFile->setFolderId($folder_id);
					$insertFile->setTitle($title);
					$insertFile->setExtension($ext);
					$insertFile->setUserId($user_id);
					$insertFile->setStatus($status);
					$insertFile->setDate($date);
					$insertFile->setDescription($desc_text);
					$insertFile->setCreateDate($date);
					$insertFile->setEditDate($date);
					$insertFile->setEditType($file->getEditType());
					$insertFile->setReadType($file->getReadType());

					require_once( 'class/fileManager.php' );
					$fileM = new FileManager();
					$newid = $fileM->createFile($insertFile);
					$fileM->createReadPermission($newid,$select_read_group);
					$fileM->createEditPermission($newid,$select_edit_group);

					apfilesharing_modify_file( "$files_dir/$tmp_name" , "$files_dir/$newid.$ext" ) ;
				}
					// Clear tempolary files
					apfilesharing_clear_tmp_files( $files_dir ) ;
					//}
					$redirect_uri = "?page=viewcat&amp;cid=$folder_id" ;
					redirect_header( $redirect_uri , 2 , _MD_ALBM_RECEIVED ) ;
			
					exit ;
				break;
			}
	}


	// Editing Display

	include( XOOPS_ROOT_PATH . "/header.php" ) ;
	include_once( "../../class/xoopsformloader.php" ) ;
	include_once( "../../include/xoopscodes.php" ) ;

	$xoopsOption['template_main'] = "apfilesharing_submit.html" ;
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	$maxfilesize = $apfilesharing_fsize . ( empty( $file_uploads_off ) ? "" : ' &nbsp; <b>"file_uploads" off</b>' );



	$folder_select = get_folder_select($file->getFolderId());
	$apfilesharing_fsize_byte = $apfilesharing_fsize * 1000000;
	
	
	include_once( 'class/folderManager.php' );
	$folderM = new FolderManager();
	$folder = $folderM->getFolder($file->getFolderId());
	$xoopsTpl->assign(
		array(
			'error_msg' => $error_msg,
			'maxfilesize' => $maxfilesize,
			'file' => file_to_array($file),
			'folder_select' => $folder_select,
			'parent_folder' => folder_to_array($folder),
			'parent_read_permission' => $folderM->getReadPermission($file->getFolderId()),
			'apfilesharing_fsize' => $apfilesharing_fsize,
			'apfilesharing_fsize_byte' => $apfilesharing_fsize_byte,
			'user_groups' => $userGroup,
			'groups' => $groups,
			'cid' => @$_GET["cid"],
			'ref' => $ref,
			'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
		)
	);

}else{
	redirect_header( $mod_url) ;
	exit();
}
include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>