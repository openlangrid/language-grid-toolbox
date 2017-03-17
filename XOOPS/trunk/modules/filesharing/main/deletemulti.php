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

$lid = empty( $_POST["delete"] ) ? array() :  $_POST["delete"] ;

$error_msg = "";

// Get the record
@$folder_array = $_POST["cid"];
@$file_array = $_POST["lid"];

@$samplefile = get_file_result(&$xoopsDB,$file_array[0]);
if($samplefile != null){
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$samplefile['cid'];
}
else{
	@$samplefolder = get_folder_result(&$xoopsDB,$folder_array[0]);
	$ref = XOOPS_URL."/modules/".$mydirname."/?page=viewcat&cid=".$samplefolder['pid'];
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
		$lid = $file_array[$i];
		$file = get_file_result(&$xoopsDB,$lid);

	// get and check lid is valid
		if( $lid < 1 ) die( "Invalid file id." ) ;

		$whr = "lid=$lid" ;
		if( !$isadmin && ($file['edit_type'] == 'user' && $file['edit_user'] != $my_uid)) {
			$whr .= " AND submitter=$my_uid" ;
		}
	
		filesharing_delete_files( $whr ) ;
	}
	
	//delete folders
	for($i =0; $i <count($folder_array);$i++){
		$cid = $folder_array[$i];
		$folder = get_folder_result($xoopsDB,$cid);
			$can_delete = true;
	if(!$isadmin){
		if(!check_folder_recurrently($folder['cid'],$my_uid)){
			$can_delete = false;
			$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
			$_POST['conf_delete'] = 1;
		}
	}
	
	if($can_delete){

		// get and check lid is valid
		if($folder['cid'] <= 1 ) die( "Invalid folder id." ) ;

		delete_folder_recurrently( $folder['cid'] ) ;

	}
	}
	
if($file != null){
	redirect_header( $mod_url.'/?page=viewcat&cid='.$file['cid'] , 3 , _MD_ALBM_DELETINGFILE ) ;
}
else{
	
	redirect_header( $mod_url.'/?page=viewcat&cid='.$folder['pid'] , 3 , _MD_ALBM_DELETINGFILE ) ;
}
	
	//@redirect_header( $mod_url) ;
	exit ;
}


// Confirm Delete
	if( ! empty( $_POST['conf_delete']) ||  ! empty( $_GET['conf_delete'])) {
		
		//Confirm files
		$file_for_tpl = array();
		for($i =0; $i <count($file_array);$i++){
			$lid = $file_array[$i];
			$file = get_file_result(&$xoopsDB,$lid);

		$file_for_tpl[$i] = get_array_for_file_assign($file) ;

	if( !$isadmin && ($file['edit_type'] == 'user' && $file['edit_user'] != $my_uid)) {
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit ;
	}

		$parent_folder = _MD_ALBM_MAIN;
		$ftree = get_folder_tree($file['cid']);
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
		$cid = $folder_array[$i];
		$folder = get_folder_result($xoopsDB,$cid);
		$folder_for_tpl[$i] = $folder ;
		$can_delete = true;
	
		if(!$isadmin){
			if(!check_folder_recurrently($folder['cid'],$my_uid)){
				$can_delete = false;
				$error_msg = _MD_ALBM_NOT_DELETE_PERMISSION;
			}
		}
	
		$folder = get_folder_result($xoopsDB,$folder['cid']);
		
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
	}
		
	include( XOOPS_ROOT_PATH . "/header.php" ) ;
	$xoopsOption['template_main'] = "filesharing_multidelconf.html" ;
	$xoopsTpl->assign(
		//‚±‚±‚ÌŠ„‚è“–‚Ä‚ðl‚¦‚éD
		array(
			'error_msg' => $error_msg,
			'file_for_tpl' => $file_for_tpl,
			'folder_for_tpl' => $folder_for_tpl,
			'parent_folder' => $parent_folder,
			'lid' => $lid,
			'ref' => $ref,
			'gticket' => $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )
		)
	);
	
	//echo "
	//<h4>"._MD_ALBM_FILEDEL."</h4>
	//<div>
	//	<img src='$thumbs_url/$lid.$ext' />
	//	<br />
	//	<form action='?page=editfile&lid=$lid' method='post'>
	//		".$xoopsGTicket->getTicketHtml( __LINE__ )."
	//		<input type='submit' name='do_delete' value='"._YES."' />
	//		<input type='submit' name='cancel_delete' value="._NO." />
	//	</form>
	//</div>
	//\n" ;
	
	
	include( XOOPS_ROOT_PATH . "/footer.php" ) ;
		
	exit ;
	}
	


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

function get_array_for_file_assign(&$file){
	$file['owner_name'] = filesharing_get_name_from_uid( $file['submitter']);
	$file['owner_info'] = XOOPS_URL."/userinfo.php?uid=".$file['submitter'];
	
	return $file;
}
?>