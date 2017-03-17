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
include_once( 'class/filesharing.textsanitizer.php' ) ;

$myts =& MyAlbumTextSanitizer::getInstance() ;
//$cattree = new XoopsTree( $table_cat , "cid" , "pid" ) ;

// check Categories exist
$result = $xoopsDB->query( "SELECT count(cid) as count FROM $table_cat" ) ;
list( $count ) = $xoopsDB->fetchRow( $result ) ;
if( $count < 1 ) {
	redirect_header( XOOPS_URL."/modules/$mydirname/" , 2 , _MD_ALBM_MUSTADDCATFIRST ) ;
	exit ;
}

// check file_uploads = on
if( ! ini_get( "file_uploads" ) ) $file_uploads_off = true ;

// get flag of safe_mode
$safe_mode_flag = ini_get( "safe_mode" ) ;

// check or make files_dir
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
if( $filesharing_makethumb && ! is_dir( $thumbs_dir ) ) {
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
if( $filesharing_makethumb && ! is_writable( $thumbs_dir ) ) {
	$rs = chmod( $thumbs_dir , 0777 ) ;
	if( ! $rs ) {
		redirect_header(XOOPS_URL."/modules/$mydirname/",5,"chmod 0777 into $thumbs_dir failed");
		exit ;
	}
}

$file = array(
	'cid' => ( empty( $_GET['cid'] ) ? 1 : intval( $_GET['cid'] ) ) ,
	'description' => '' ,
	'title' => '',
	'edit' => 'public',
	'read' => 'public',
) ;
$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$file['cid'];
if(isset($_POST['ref'])){
	$ref = $_POST['ref'];
}

$error_msg = "";

if( ! empty( $_POST['submit'] ) ) {
	$file['cid'] = @$_POST['cid'];
	$file['description'] = trim($myts->stripSlashesGPC( @$_POST["desc_text"] )) ;
	$file['edit'] = $myts->stripSlashesGPC( @$_POST["edit_permission"] ) ;
	$file['read'] = $myts->stripSlashesGPC( @$_POST["read_permission"] ) ;

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	switch($dummy){
		default:
			$submitter = $my_uid ;
			$file['cid'] = !is_numeric($file['cid']) ? 1 : intval( $file['cid'] ) ;
			$cid = $file['cid'];
			$newid = $xoopsDB->genId( $table_files."_lid_seq" ) ;
			
			// Check if cid is valid
			if( $cid <= 0 ) {
				$error_msg .= 'Category is not specified.<br>';
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

			// Check if upload file name specified
			$field = @$_POST["xoops_upload_file"][0] ;
			if( empty( $field ) || $field == "" ) {
				$error_msg .= "UPLOAD error: file name not specified<br>";
			}

			if( $_FILES[$field]['name'] == '' ) {
				$error_msg .= _MD_ALBM_NOIMAGESPECIFIED."<br>";
				break;
			}else{
				if(!check_samename_file($cid,$_FILES[$field]['name'][0])){
					$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
				}
			}
			
			if( $_FILES[$field]['tmp_name'] == "" ) {
				$error_msg .= _MD_ALBM_FILEERROR."<br>";
				break;
			}

			if($error_msg != ""){break;}

			$ErrCode = 0;
			switch($dummy){
				default:
					if($_FILES[$field]["error"]){$ErrCode=1;break;}
					if(!is_uploaded_file($_FILES[$field]["tmp_name"])){$ErrCode=2;break;}
					if($_FILES[$field]["size"] > ($filesharing_fsize*1000000)){$ErrCode=2;break;}
				break;
			}
			if($ErrCode != 0){
				$error_msg .= _MD_ALBM_FILEERROR."<br>";
			}
			
			for($i=0;$i<count($_FILES["upload_file"]["name"]);$i++){
				set_time_limit(0);
				$uploader = new MyXoopsMediaUploader( $files_dir , $array_allowed_mimetypes , ($filesharing_fsize*1000000) , $filesharing_width , $filesharing_height , $array_allowed_exts ) ;
			
							//var_dump($_FILES);
				//var_dump($_POST);
					//			if(!check_samename_file($cid,$_FILES[$field]['name'][$i])){
					//	$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
				//					continue;
				//	}
				//echo "<pre>";
				//var_dump($_FILES);
				//var_dump($field);
				//echo "</pre>";
				$uploader->setPrefix( 'tmp_' ) ;
				//if( $uploader->fetchMedia( $field ) && $uploader->upload() ) {
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
				
				if(!check_samename_file($cid,$title[$i])){
					$error_msg = _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
					continue;
				}

				$desc_text = $file['description'] ;
				$date = time() ;
				$ext = substr( strrchr( $tmp_name , '.' ) , 1 ) ;
				$status = 1 ;

				//$sql = "INSERT INTO $table_files 
				//(lid, cid, title, ext, submitter, status, date, hits, rating, votes, comments) 
				//VALUES ($newid, $cid, '".addslashes($title)."', '$ext', 
				//$submitter, $status, $date, 0, 0, 0, 0)";
				$sql1 = "";$sql2 = "";
				$sql1 .= "lid,";                    $sql2 .= "".$newid.",";
				$sql1 .= "cid,";                    $sql2 .= "".$cid.",";
				$sql1 .= "title,";                  $sql2 .= "'".addslashes($title)."',";
				$sql1 .= "ext,";                    $sql2 .= "'".$ext."',";
				$sql1 .= "submitter,";              $sql2 .= "".$submitter.",";
				$sql1 .= "status,";                 $sql2 .= "".$status.",";
				$sql1 .= "date,";                   $sql2 .= "".time().",";
				$sql1 .= "description,";            $sql2 .= "'".addslashes($desc_text)."',";
				$sql1 .= "create_date,";            $sql2 .= "".time().",";
				$sql1 .= "edit_date,";              $sql2 .= "".time().",";
				$sql1 .= "read_permission_type,";   $sql2 .= "'".trim($file['read'])."',";
				$sql1 .= "read_permission_user,";   $sql2 .= "".$submitter.",";
				$sql1 .= "edit_permission_type,";   $sql2 .= "'".trim($file['edit'])."',";
				$sql1 .= "edit_permission_user ";   $sql2 .= "".$submitter." ";
				
				$sql  = "INSERT INTO ".$table_files."(".$sql1.")VALUES(".$sql2.")";
				$xoopsDB->query( $sql ) or die( "DB error: INSERT file table" ) ;
					
				if( $newid == 0 ) {
					$newid = $xoopsDB->getInsertId();
				}
					//var_dump($sql2);

				filesharing_modify_file( "$files_dir/$tmp_name" , "$files_dir/$newid.$ext" ) ;
				$newid = $xoopsDB->genId( $table_files."_lid_seq" ) ;
				/*
				if( ! filesharing_create_icon( "$files_dir/$newid.$ext" , $newid , $ext ) ) {
					$xoopsDB->query( "DELETE FROM $table_files WHERE lid=$newid" ) ;
					$error_msg = _MD_ALBM_FILEREADERROR;
					break;
				}
				*/
				
				// Update User's Posts (Should be modified when need admission.)
				/*
				$user_handler =& xoops_gethandler('user') ;
				$submitter_obj =& $user_handler->get( $submitter ) ;
				if( is_object( $submitter_obj ) ) {
					for( $i = 0 ; $i < $filesharing_addposts ; $i ++ ) {
						$submitter_obj->incrementPost() ;
					}
				}
				*/
				
				// Trigger Notification
				/*
				if( $status ) {
					$notification_handler =& xoops_gethandler( 'notification' ) ;

					// Global Notification
					$notification_handler->triggerEvent( 'global' , 0 , 'new_file' , array( 'FILE_TITLE' => $title , 'FILE_URI' => "$mod_url/?page=file&lid=$newid&cid=$cid" ) ) ;

					// Category Notification
					$rs = $xoopsDB->query( "SELECT title FROM $table_cat WHERE cid=$cid" ) ;
					list( $cat_title ) = $xoopsDB->fetchRow( $rs ) ;
					$notification_handler->triggerEvent( 'category' , $cid , 'new_file' , array( 'FILE_TITLE' => $title , 'CATEGORY_TITLE' => $cat_title , 'FILE_URI' => "$mod_url/?page=file&lid=$newid&cid=$cid" ) ) ;
				}
				*/
				}
				// Clear tempolary files
				filesharing_clear_tmp_files( $files_dir ) ;
				//}
				$redirect_uri = "?page=viewcat&amp;cid=$cid" ;
				redirect_header( $redirect_uri , 2 , _MD_ALBM_RECEIVED ) ;
		
				exit ;
			break;
		}
}


// Editing Display

include( XOOPS_ROOT_PATH . "/header.php" ) ;
include_once( "../../class/xoopsformloader.php" ) ;
include_once( "../../include/xoopscodes.php" ) ;

$xoopsOption['template_main'] = "filesharing_submit.html" ;
echo "<pre>";
print_r($_POST);
echo "</pre>";
$maxfilesize = $filesharing_fsize . ( empty( $file_uploads_off ) ? "" : ' &nbsp; <b>"file_uploads" off</b>' );
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
$folder_select = get_folder_select($file['cid']);
$filesharing_fsize_byte = $filesharing_fsize * 1000000;
$xoopsTpl->assign(
	array(
		'error_msg' => $error_msg,
		'maxfilesize' => $maxfilesize,
		'file' => $file,
		'folder_select' => $folder_select,
		'filesharing_fsize' => $filesharing_fsize,
		'filesharing_fsize_byte' => $filesharing_fsize_byte,
		'cid' => @$_GET["cid"],
		'ref' => $ref,
		'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
	)
);


include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>