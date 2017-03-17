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

// constants
define( 'PIPEID_GD' , 0 ) ;
define( 'PIPEID_IMAGICK' , 1 ) ;
define( 'PIPEID_NETPBM' , 2 ) ;

include_once( 'class/file.php' ) ;
include_once( 'class/folder.php' ) ;
include_once( 'class/fileManager.php' ) ;
include_once( 'class/folderManager.php' ) ;
function apfilesharing_get_thumbnail_wh( $width , $height )
{
	global $apfilesharing_thumbsize , $apfilesharing_thumbrule ;

	switch( $apfilesharing_thumbrule ) {
		case 'w' :
			$new_w = $apfilesharing_thumbsize ;
			$scale = $width / $new_w ;
			$new_h = intval( round( $height / $scale ) ) ;
			break ;
		case 'h' :
			$new_h = $apfilesharing_thumbsize ;
			$scale = $height / $new_h ;
			$new_w = intval( round( $width / $scale ) ) ;
			break ;
		case 'b' :
			if( $width > $height ) {
				$new_w = $apfilesharing_thumbsize ;
				$scale = $width / $new_w ; 
				$new_h = intval( round( $height / $scale ) ) ;
			} else {
				$new_h = $apfilesharing_thumbsize ;
				$scale = $height / $new_h ; 
				$new_w = intval( round( $width / $scale ) ) ;
			}
			break ;
		default :
			$new_w = $apfilesharing_thumbsize ;
			$new_h = $apfilesharing_thumbsize ;
			break ;
	}

	return array( $new_w , $new_h ) ;
}


// create_thumb Wrapper
// return value
//   0 : read fault
//   1 : complete created
//   2 : copied
//   3 : skipped
//   4 : icon gif (not normal exts)
function apfilesharing_create_icon( $src_path , $node , $ext )
{
	global $apfilesharing_makethumb , $apfilesharing_normal_exts ;

	global $mod_path , $thumbs_dir ;

	@unlink( $thumbs_dir."/".$node.".gif" ) ;
	$copy_success = false;
	if( file_exists( $mod_path."/icons/".$ext.".gif") ) {
		$copy_success = copy( $mod_path."/icons/".$ext.".gif" , $thumbs_dir."/".$node.".gif" ) ;
	}
	if( !$copy_success ) {
		@copy( $mod_path."/icons/default.gif" , $thumbs_dir."/".$node.".gif" ) ;
	}

	return 4 ;


	//if( ! in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) ) {
	//	return apfilesharing_copy_thumb_from_icons( $src_path , $node , $ext ) ;
	//}

	//if( ! $apfilesharing_makethumb ) return 3 ;
}


// Copy Thumbnail from directory of icons
function apfilesharing_copy_thumb_from_icons( $src_path , $node , $ext )
{
	global $mod_path , $thumbs_dir ;

	@unlink( "$thumbs_dir/$node.gif" ) ;
	if( file_exists( "$mod_path/icons/$ext.gif" ) ) {
		$copy_success = copy( "$mod_path/icons/$ext.gif" , "$thumbs_dir/$node.gif" ) ;
	}
	if( empty( $copy_success ) ) {
		@copy( "$mod_path/icons/default.gif" , "$thumbs_dir/$node.gif" ) ;
	}

	return 4 ;
}


// modifyFile Wrapper
function apfilesharing_modify_file( $src_path , $dst_path )
{
	rename( $src_path , $dst_path ) ;
}

// Clear templorary files
function apfilesharing_clear_tmp_files( $dir_path , $prefix = 'tmp_' )
{
	// return if directory can't be opened
	if( ! ( $dir = @opendir( $dir_path ) ) ) {
		return 0 ;
	}

	$ret = 0 ;
	$prefix_len = strlen( $prefix ) ;
	while( ( $file = readdir( $dir ) ) !== false ) {
		if( strncmp( $file , $prefix , $prefix_len ) === 0 ) {
			if( @unlink( "$dir_path/$file" ) ) $ret ++ ;
		}
	}
	closedir( $dir ) ;

	return $ret ;
}


//updates rating data in itemtable for a given item
function apfilesharing_updaterating( $lid )
{
	global $xoopsDB , $table_votedata , $table_files ;

	$query = "SELECT rating FROM $table_votedata WHERE file_id=$lid" ;
	$voteresult = $xoopsDB->query( $query ) ;
	$votesDB = $xoopsDB->getRowsNum( $voteresult ) ;
	$totalrating = 0 ;
	while( list( $rating ) = $xoopsDB->fetchRow( $voteresult ) ) {
		$totalrating += $rating ;
	}
	$finalrating = number_format( $totalrating / $votesDB , 4 ) ;
	$query = "UPDATE $table_files SET rating=$finalrating, votes=$votesDB WHERE file_id = $lid" ;

	$xoopsDB->query( $query ) or die( "Error: DB update rating." ) ;
}


// Returns the number of files included in a Category
function apfilesharing_get_file_small_sum_from_cat( $cid , $whr_append = "" )
{
	global $xoopsDB , $table_files ;

	if( $whr_append ) $whr_append = "AND ($whr_append)" ;

	$sql = "SELECT COUNT(file_id) FROM $table_files WHERE folder_id=$cid $whr_append" ;
	$rs = $xoopsDB->query( $sql ) ;
	list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

	return $numrows ;
}


// Returns the number of whole files included in a Category
function apfilesharing_get_file_total_sum_from_cats( $cids , $whr_append = "" )
{
	global $xoopsDB , $table_files ;

	if( $whr_append ) $whr_append = "AND ($whr_append)" ;

	$whr = "folder_id IN (" ;
	foreach( $cids as $cid ) {
		$whr .= "$cid," ;
	}
	$whr = "$whr 0)" ;

	$sql = "SELECT COUNT(file_id) FROM ".$table_files." WHERE .".$whr." ".$whr_append." " ;
	$rs = $xoopsDB->query( $sql ) ;
	list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

	return $numrows ;
}

function apfilesharing_delete_files( $whr ) {
	global $xoopsDB ;
	global $files_dir , $thumbs_dir , $apfilesharing_mid ;
	global $table_files , $table_text , $table_votedata ;

	$prs = $xoopsDB->query("SELECT file_id, ext FROM $table_files WHERE $whr" ) ;
	while( list( $file_id , $ext ) = $xoopsDB->fetchRow( $prs ) ) {

		xoops_comment_delete( $apfilesharing_mid , $lid ) ;
		xoops_notification_deletebyitem( $apfilesharing_mid , 'file' , $lid ) ;

		$sql = "DELETE FROM $table_files WHERE file_id=%d";
		$sql = $sprintf($sql,intval($file_id));
		
		$xoopsDB->query($sql) or die( "DB error: DELETE file table." ) ;
	
		@unlink( "$files_dir/$lid.$ext" ) ;
		@unlink( "$files_dir/$lid.gif" ) ;
		@unlink( "$thumbs_dir/$lid.$ext" ) ;
		@unlink( "$thumbs_dir/$lid.gif" ) ;
	}
}


// Update a file (Not Use) 
function apfilesharing_update_file( $lid , $cid , $title , $desc , $valid = null , $ext = "" , $x = "" , $y = "" )
{
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	global $table_files , $table_text , $table_folder , $mod_url , $isadmin ;

	if( isset( $valid ) ) {
		$set_status = ",status='$valid'" ;

		// Trigger Notification
		if( $valid == 1 ) {
			$notification_handler =& xoops_gethandler( 'notification' ) ;

			// Global Notification
			$notification_handler->triggerEvent( 'global' , 0 , 'new_file' , array( 'FILE_TITLE' => $title , 'FILE_URI' => "$mod_url/?page=file&lid=$lid&cid=$cid" ) ) ;

			// Category Notification
			$rs = $xoopsDB->query( "SELECT title FROM $table_folder WHERE folder_id=$cid" ) ;
			list( $cat_title ) = $xoopsDB->fetchRow( $rs ) ;
			$notification_handler->triggerEvent( 'category' , $cid , 'new_file' , array( 'FILE_TITLE' => $title , 'CATEGORY_TITLE' => $cat_title , 'FILE_URI' => "$mod_url/?page=file&lid=$lid&cid=$cid" ) ) ;
		}
	} else {
		$set_status = ",status=2" ;
	}

	$set_date = empty( $_POST['store_timestamp'] ) ? ",date=UNIX_TIMESTAMP()" : "" ;

	// not admin can only touch files status>0
	$whr_status = $isadmin ? '' : 'AND status>0' ;

	if( $ext == "" ) {
		// modify only text
		$xoopsDB->query("UPDATE $table_files SET folder_id='$cid',title='".addslashes($title)."' $set_status $set_date WHERE file_id='$lid' $whr_status") ;
	} else {
		// modify text and image
		$xoopsDB->query("UPDATE $table_files SET folder_id='$cid',title='".addslashes($title)."', ext='$ext',res_x='$x',res_y='$y' $set_status $set_date WHERE file_id='$lid' $whr_status");
	}

	$xoopsDB->query("UPDATE $table_text SET description='".addslashes($desc)."' WHERE file_id='$lid'");

	//redirect_header( "?page=editfile&lid=$lid" , 0 , _MD_ALBM_DBUPDATED ) ;
}


// Delete files hit by the $whr clause


// Substitution of opentable()
function apfilesharing_opentable()
{
	echo "<div style='border: 2px solid #2F5376;padding:8px;width:95%;' class='bg4'>\n" ;
}


// Substitution of closetable()
function apfilesharing_closetable()
{
	echo "</div>\n" ;
}


// returns extracted string for options from table with xoops tree
function apfilesharing_get_cat_options( $order = 'title' , $preset = 0 , $prefix = '--' , $none = null , $table_name_cat = null , $table_name_files = null )
{
	global $xoopsDB ;

	$myts =& MyTextSanitizer::getInstance() ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_folder'] ;
	if( empty( $table_name_files ) ) $table_name_files = $GLOBALS['table_files'] ;

	$cats[0] = array( 'cid' => 0 , 'pid' => -1 , 'next_key' => -1 , 'depth' => 0 , 'title' => '' , 'num' => 0 ) ;

	$rs = $xoopsDB->query( "SELECT c.title,c.folder_id,c.parent_id,COUNT(p.file_id) AS num FROM $table_name_cat c LEFT JOIN $table_name_files p ON c.folder_id=p.folder_id GROUP BY c.folder_id ORDER BY parent_id ASC,$order DESC" ) ;

	$key = 1 ;
	while( list( $title , $cid , $pid , $num ) = $xoopsDB->fetchRow( $rs ) ) {
		$cats[ $key ] = array( 'cid' => intval( $cid ) , 'pid' => intval( $pid ) , 'next_key' => $key + 1 , 'depth' => 0 , 'title' => $myts->makeTboxData4Show( $title ) , 'num' => intval( $num ) ) ;
		$key ++ ;
	}
	$sizeofcats = $key ;

	$loop_check_for_key = 1024 ;
	for( $key = 1 ; $key < $sizeofcats ; $key ++ ) {
		$cat =& $cats[ $key ] ;
		$target =& $cats[ 0 ] ;
		if( -- $loop_check_for_key < 0 ) $loop_check = -1 ;
		else $loop_check = 4096 ;

		while( 1 ) {
			if( $cat['pid'] == $target['cid'] ) {
				$cat['depth'] = $target['depth'] + 1 ;
				$cat['next_key'] = $target['next_key'] ;
				$target['next_key'] = $key ;
				break ;
			} else if( -- $loop_check < 0 ) {
				$cat['depth'] = 1 ;
				$cat['next_key'] = $target['next_key'] ;
				$target['next_key'] = $key ;
				break ;
			} else if( $target['next_key'] < 0 ) {
				$cat_backup = $cat ;
				array_splice( $cats , $key , 1 ) ;
				array_push( $cats , $cat_backup ) ;
				-- $key ;
				break ;
			}
			$target =& $cats[ $target['next_key'] ] ;
		}
	}

	if( isset( $none ) ) $ret = "<option value=''>$none</option>\n" ;
	else $ret = '' ;
	$cat =& $cats[ 0 ]  ;
	for( $weight = 1 ; $weight < $sizeofcats ; $weight ++ ) {
		$cat =& $cats[ $cat['next_key'] ] ;
		$pref = str_repeat( $prefix , $cat['depth'] - 1 ) ;
		$selected = $preset == $cat['cid'] ? "selected='selected'" : '' ;
		$ret .= "<option value='{$cat['cid']}' $selected>$pref {$cat['title']} ({$cat['num']})</option>\n" ;
	}

	return $ret ;
}


function get_folder_select($cid,$myid = null){
	if(!is_numeric($cid)){$cid=1;}
	if(!is_numeric($myid)){$myid=0;}

	$folder_select  = '<div id="select_panels">';
	$folder_select .= '<div id="panel0" class="folder_sel"><input type="hidden" id="cid0" name="cid" value="1">'._MD_ALBM_MAIN.'</div>';
	$ftree = get_folder_tree($cid,$myid);
	$lastselect = null;
	$cnt = 0;
	foreach($ftree as $K => $options){
		$option = "";
		foreach($options as $val){
			if($val['id'] != $myid){
				$option .= "<option value='".$val['id']."'";
				if($val['selected']){
					$option .= " selected";
					$lastselect = $val['id'];
				}
				$option .= ">".$val['title']."</option>\n";
			}
		}
		if($option != ""){
			$cnt++;
			if(!isset($ftree[($K+1)])){
				$btn_class = "btn";
				$disable = "";
			}else{
				$btn_class = "btn-disable";
				$disable = " disabled";
			}
			$folder_select .= '<div id="panel'.$cnt.'" class="folder_sel"> &gt; ';
			if($cnt % 3 == 0){$folder_select .= "<br>";}
			$folder_select .= '<a id="minus'.$cnt.'" class="'.$btn_class.' btn-tgr" href="javascript:del_select('.$cnt.');"><img src="./img/icn_minus.gif"></a>';
			$folder_select .= '<select id="cid'.$cnt.'" name="cid" onchange="check_next(this,'.$cnt.'); " '.$disable.'>';
			$folder_select .= $option;
			$folder_select .= '</select></div>';
		}
	}
	if(sub_folder_exists($lastselect,$myid)){
		$cnt++;
		$folder_select .= '<div id="panel'.$cnt.'" class="folder_sel">';
		$folder_select .= '<a id="plus'.$cnt.'" class="btn btn-tgr" href="javascript:add_select('.$cnt.');"><img src="./img/icn_plus.gif"></a>';
		$folder_select .= '</div>';
	}
	$folder_select .= '</div>';
	
	return $folder_select;
}

function get_folder_tree($cid){
	if(!is_numeric($cid)){$cid=1;}

	if($cid == 1){return array();}
	
	global $xoopsDB ;
	$myts =& MyTextSanitizer::getInstance() ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_folder'] ;
	
	$parents = array();
	$isLast = false;
	$nowCid = $cid;
	while(!$isLast){
		$ret = get_parent_folder($nowCid);
		$parents[] = $ret;
		$nowCid = $ret;
		if($ret == 1 || $ret == null){$isLast = true;}
	}
	
	$options = array();
	//$parents = array_reverse($parents);
	$preParent = $cid;
	foreach($parents as $K => $V){
		$sql  = '';
		$sql .= 'SELECT folder_id,title FROM '.$table_name_cat.' WHERE parent_id = '.intval($V).' ORDER BY title ';
		$rs = $xoopsDB->query($sql);
		while( list($id,$title) = $xoopsDB->fetchRow($rs)){
			$tmp['id'] = $id;
			$tmp['selected'] = false;
			if($id == $preParent){ $tmp['selected'] = true;}
			$tmp['title'] = $myts->makeTboxData4Show( $title );
			
			$options[$K][] = $tmp;
		}
		$preParent = $V;
	}
	
	$options = array_reverse($options);
	
	return $options;
}

function get_parent_folder($cid){
	global $xoopsDB ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_folder'] ;
	
	if(!is_numeric($cid)){$cid=1;}

	$sql = '';
	$sql .= 'SELECT parent_id FROM '.$table_name_cat.' WHERE folder_id = '.intval($cid).' ';
	$rs = $xoopsDB->query($sql);
	list( $parent_id ) = $xoopsDB->fetchRow( $rs );

	return $parent_id;
}

function sub_folder_exists($cid,$myid = null){
	global $xoopsDB ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_folder'] ;

	if(!is_numeric($cid)){$cid=1;}
	if(!is_numeric($myid)){$myid=0;}
	
	$sql = '';
	$sql .= 'SELECT count(folder_id) FROM '.$table_name_cat.' WHERE parent_id = '.intval($cid).' AND folder_id != '.intval($myid).' ';
	$rs = $xoopsDB->query($sql);
	list( $cnt ) = $xoopsDB->fetchRow( $rs );
	
	if($cnt > 0){
		return true;
	}else{
		return false;
	}
}

function get_sub_folder($pid,$myid = null){
	if(!is_numeric($pid)){$pid=1;}
	
	global $xoopsDB ;
	$myts =& MyTextSanitizer::getInstance() ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_folder'] ;

	if(!is_numeric($myid)){$myid=0;}
	
	$sql  = '';
	$sql .= 'SELECT folder_id,title FROM '.$table_name_cat.' WHERE parent_id = '.intval($pid).' AND folder_id != '.intval($myid).' ORDER BY title ';
	$rs = $xoopsDB->query($sql);
	$options = array();
	while( list($id,$title) = $xoopsDB->fetchRow($rs)){
		$tmp['id'] = $id;
		$tmp['title'] = $myts->makeTboxData4Show( $title );
		
		$options[] = $tmp;
	}
	return $options;
}

function check_folder_recurrently($cid,$uid){
	global $xoopsDB ;
	global $table_folder;
	global $table_files;
	
	$ret = true;
	
	$sql  = '';
	$sql .= "SELECT file_id,edit_permission_type,user_id ";
	$sql .= "FROM ".$table_files." ";
	$sql .= "WHERE folder_id = ".intval($cid)." ";
	$rs = $xoopsDB->query($sql);
	
	$file_count = 0;
	$is_file_exist = false;
	while($row = $xoopsDB->fetchArray($rs)){
		$is_file_exist = true;
		$e_type = $row['edit_permission_type'];
		
		if(($e_type == 'user' && $row['user_id'] != $uid) || $e_type == 'protected'&&!check_edit_permission_group($row['file_id'],2)){
			$file_count++;
		}
	}
	
	if($is_file_exist&&$file_count > 0){
		return false;
	}else{
		$sql  = '';
		$sql .= 'SELECT ';
		$sql .= 'folder_id,';
		$sql .= 'edit_permission_type,';
		$sql .= 'user_id ';
		$sql .= 'FROM '.$table_folder.' ';
		$sql .= 'WHERE parent_id = '.$cid.' ';
		$rs = $xoopsDB->query($sql);
		while( $row = $xoopsDB->fetchArray( $rs ) ) {
			$e_type = $row['edit_permission_type'];

			if(($e_type == 'user' && $row['user_id'] != $uid) ||($e_type == 'protected'&&!check_edit_permission_group($row['folder_id']))){
				return false;
			}else{
				if(!check_folder_recurrently($row['folder_id'],$uid)){
					return false;
				}
			}
		}
	}
		
	return true;
}

function check_samename_folder($pid,$title,$myid = null){
	global $xoopsDB ;
	global $table_folder;

	$sql  = "SELECT COUNT(folder_id) ";
	$sql .= "FROM ".$table_folder." ";
	$sql .= "WHERE parent_id = ".intval($pid)." ";
	$sql .= "AND title = '".addslashes($title)."' ";
	if(is_numeric($myid)){
		$sql .= "AND folder_id != ".intval($myid)." ";
	}
	$prs = $xoopsDB->query($sql);
	list( $cnt ) = $xoopsDB->fetchRow( $prs );
	if($cnt > 0){
		return false;
	}else{
		return true;
	}
}

function check_samename_file($cid,$title,$myid = null){
	global $xoopsDB ;
	global $table_files;

	$sql  = "SELECT COUNT(file_id) ";
	$sql .= "FROM ".$table_files." ";
	$sql .= "WHERE folder_id = ".intval($cid)." ";
	$sql .= "AND title = '".addslashes($title)."' ";
	if(is_numeric($myid)){
		$sql .= "AND file_id != ".intval($myid)." ";
	}
	$prs = $xoopsDB->query($sql);
	list( $cnt ) = $xoopsDB->fetchRow( $prs );
	if($cnt > 0){
		return false;
	}else{
		return true;
	}
}

function file_to_array($file){
	$fileArray = array(
		'lid' 	  => $file->getId(),
		'cid'   => $file->getFolderId(),
		'title' 	  => $file->getTitle(),
		'description' => $file->getDescription(),
		'user_id' 	  => $file->getUserId(),
		'edit_type'	  => $file->getEditType(),
		'read_type'   => $file->getReadType()
	);
	return $fileArray;
}

function folder_to_array($folder){
	$folderArray = array(
		'cid'		  => $folder->getId(),
		'pid'		  => $folder->getParentId(),
		'user_id'	  => $folder->getUserId(),
		'title'		  => $folder->getTitle(),
		'description' => $folder->getDescription(),
		'edit_type'	  => $folder->getEditType(),
		'read_type'	  => $folder->getReadType()
	);
	return $folderArray;
}

function get_groups(){
	global $xoopsDB ;
	global $table_groups ;
	
	$sql  = ' ';
	$sql .= ' SELECT groupid , description ';
	$sql .= ' FROM '.$table_groups.' ';
	
	$result = $xoopsDB->query($sql);
	$groups = array();
	while($row=$xoopsDB->fetchArray($result)){
		$groups[$row['groupid']] = $row['description'];
	}
	
	return $groups;
}

//type => 1:folder 2:file
function check_read_permission_group($id,$ftype=1){
	global $xoopsDB;
	global $table_folder_r_permission;
	global $table_files_r_permission;
	
	$userGroup = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
	$sql  = '';
	$sql .= ' SELECT group_id ';
	if($ftype==1){
		$sql .= ' FROM '.$table_folder_r_permission.' ';
		$sql .= ' WHERE folder_id = %d ';
	}else{
		$sql .= ' FROM '.$table_files_r_permission.' ';
		$sql .= ' WHERE file_id = %d ';
	}
	$sql  = sprintf($sql, intval($id));
	$result = $xoopsDB->query($sql);
	$cGroup = array();
	while($row = $xoopsDB->fetchArray( $result )){
		$cGroup[] =$row['group_id'];
	};
	
	if(count(array_intersect($cGroup,$userGroup)) > 0){
		return true;
	}else{
		return false;
	}
}
//type => 1:folder 2:file
function check_edit_permission_group($id,$ftype=1){
	global $xoopsDB;
	global $table_folder_e_permission;
	global $table_files_e_permission;
	
	$userGroup = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
		
	$sql  = '';
	$sql .= ' SELECT group_id ';
	if($ftype==1){
		$sql .= ' FROM '.$table_folder_e_permission.' ';
		$sql .= ' WHERE folder_id = %d ';
	}else{
		$sql .= ' FROM '.$table_files_e_permission.' ';
		$sql .= ' WHERE file_id = %d ';
	}

	$sql  = sprintf($sql, intval($id));
	$result = $xoopsDB->query($sql);
	$cGroup = array();
	while($row = $xoopsDB->fetchArray( $result )){
		$cGroup[] =$row['group_id'];
	};
	
	if(count(array_intersect($cGroup,$userGroup)) > 0){
		return true;
	}else{
		return false;
	}
}

function get_edit_type_and_user($folder_id){
	global $xoopsDB ;
	global $table_folder;
	
	$sql  = '';
	$sql .= ' SELECT edit_permission_type,user_id ';
	$sql .= ' FROM '.$table_folder.' ';
	$sql .= ' WHERE folder_id = %d ';
	$sql = sprintf($sql , intval($folder_id));
	
	$rs = $xoopsDB->query( $sql ) ;
	list( $e_type,$e_user ) = $xoopsDB->fetchRow( $rs ) ;
	
	return array('e_type'=>$e_type,'e_user'=>$e_user);
}

function check_folder_children($folder_id,$read_type,$user_id,$read_group){
	global $xoopsDB ;
	global $table_folder;
	global $table_files;
		
	$sql  = '';
	$sql .= ' SELECT ';
	$sql .= ' file_id ';
	$sql .= ' FROM '.$table_files.' ';
	$sql .= ' WHERE folder_id = '.intval($folder_id).' ';
	$rs = $xoopsDB->query($sql);
		
	while($row = $xoopsDB->fetchArray($rs)){
		if(!check_folder_ch_file_accessible($row['file_id'],$read_type,$user_id,$read_group)){	
			return false;
		}
	}
	
	$sql  = '';
	$sql .= ' SELECT ';
	$sql .= ' folder_id ';
	$sql .= ' FROM '.$table_folder.' ';
	$sql .= ' WHERE parent_id = '.intval($folder_id).' ';
	$rs = $xoopsDB->query($sql);
	
	while( $row = $xoopsDB->fetchArray( $rs ) ) {
		if(!check_folder_ch_folder_accessible($row['folder_id'],$read_type,$user_id,$read_group)){
			return false;
		}
	}
	return true;
}

function check_folder_ch_folder_accessible($chfolder_id,$folder_r_type,$create_user,$folder_read_permission){
	include_once( 'class/folderManager.php' );
	include_once( 'class/folder.php' );
	
	$folderM = new FolderManager();
	$chfolder = $folderM->getFolder($chfolder_id);

	$folder_read_type = $folder_r_type;
	$chfolder_read_type = $chfolder->getReadType();
	
	$folder_user = $create_user;
	$chfolder_user = $chfolder->getUserId();
		
	$return_flag = true;
	switch($chfolder_read_type){
		case 'public':
			if($folder_read_type == 'public'){
				$return_flag = true;
				break;
			}else{
				$return_flag = false;
				break;
			}
		case 'protected':
			if($folder_read_type=='public'){
				$return_flag = true;
				break;
			}else if($folder_read_type=='protected'){
				$folder_group = $folder_read_permission;
				$chfolder_group = $folderM->getReadPermission($chfolder_id);
				if(count(array_diff($chfolder_group,$folder_group))==0){
					$return_flag = true;
					break;
				}else{
					$return_flag = false;
					break;
				}
			}else if($folder_read_type=='user'){
				$return_flag = false;
				break;
			}
			$return_flag = false;
			break;
		case 'user':
			if($folder_read_type=='public'){
				$return_flag = true;
				break;
			}else if(($folder_read_type=='protected'||$folder_read_type=='user')&&$folder_user==$chfolder_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		default :
			$return_flag = true;
			break;
	}
	
	return $return_flag;
}

function check_folder_ch_file_accessible($file_id,$folder_r_type,$create_user,$folder_read_permission){
	include_once( 'class/fileManager.php' );
	include_once( 'class/file.php' );
	include_once( 'class/folderManager.php' );
	include_once( 'class/folder.php' );
	
	$folderM = new FolderManager();
	$fileM = new FileManager();
	
	$file = $fileM->getFile($file_id);
	
	$folder_read_type = $folder_r_type;
	$file_read_type = $file->getReadType();
	
	$folder_user = $create_user;
	$file_user = $file->getUserId();
	
	
	$return_flag = true;
	
	switch($file_read_type){
		case 'public':
			if($folder_read_type == 'public'){
				$return_flag = true;
				break;
			}else{
				$return_flag = false;
				break;
			}
		case 'protected':
			if($folder_read_type=='public'){
				$return_flag = true;
				break;
			}else if($folder_read_type=='protected'){
				$folder_group = $folder_read_permission;
				$file_group = $fileM->getReadPermission($file_id);

				if(count(array_diff($file_group,$folder_group))==0){
					$return_flag = true;
					break;
				}else{
					$return_flag = false;
					break;
				}
			}else if($folder_read_type=='user'){
				$return_flag = false;
				break;
			}
			$return_flag = false;
			break;
		case 'user':
			if($folder_read_type=='public'){
				$return_flag = true;
				break;
			}else if(($folder_read_type=='protected'||$folder_read_type=='user')&&$folder_user==$file_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		default :
			$return_flag = true;
			break;
	}
	
	return $return_flag;
}

function check_folder_accessible($check_id,$folder_r_type,$create_user,$folder_read_permission){
	include_once( 'class/folderManager.php' );
	include_once( 'class/folder.php' );
	$folderM = new FolderManager();
	
	$check_folder = $folderM->getFolder($check_id);
	
	
	$check_read_type = $check_folder->getReadType();
	$folder_read_type = $folder_r_type;
	$check_user = $check_folder->getUserId();
	$folder_user = $create_user;
		
	$return_flag = true;
	switch($check_read_type){
		case 'public':

			$return_flag = true;
			break;
		case 'protected':
			if($folder_read_type=='public'){
				$return_flag = false;
				break;
			}else if($folder_read_type=='protected'){
				$check_group = $folderM->getReadPermission($check_id);				
				$folder_group = $folder_read_permission;

				if(count(array_diff($folder_group,$check_group))==0){
					$return_flag = true;
					break;
				}else{
					$return_flag = false;
					break;
				}
			}else if($folder_read_type=='user'&&$check_user==$folder_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		case 'user':
			if($folder_read_type=='public'||$folder_read_type=='protected'){
				$return_flag = false;
				break;
			}else if($check_user==$folder_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		default :
			$return_flag = true;
			break;
	}
	
	return $return_flag;
}

function check_file_accessible($check_id,$file_r_type,$create_user,$file_read_permission){
	include_once( 'class/folderManager.php' );
	include_once( 'class/folder.php' );
	
	$folderM = new FolderManager();
		
	$check_folder = $folderM->getFolder($check_id);
	
	$check_read_type = $check_folder->getReadType();
	$file_read_type = $file_r_type;
	
	$check_user = $check_folder->getUserId();
	$file_user = $create_user;
		
	$return_flag = true;
	switch($check_read_type){
		case 'public':
			$return_flag = true;
			break;
		case 'protected':
			if($file_read_type=='public'){
				$return_flag = false;
				break;
			}else if($file_read_type=='protected'){
				$check_group = $folderM->getReadPermission($check_id);			
				$file_group = $file_read_permission;
				if(count(array_diff($file_group,$check_group))==0){
					$return_flag = true;
					break;
				}else{
					$return_flag = false;
					break;
				}
			}else if($file_read_type=='user'&&$check_user==$file_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		case 'user':
			if($file_read_type=='public'||$file_read_type=='protected'){
				$return_flag = false;
				break;
			}else if($check_user==$file_user){
				$return_flag = true;
				break;
			}
			$return_flag = false;
			break;
		default :
			$return_flag = true;
			break;
	}
	
	return $return_flag;
}

function is_exists_folder($folder_id){
	global $xoopsDB ;
	global $table_folder;
	$sql  = '';
	$sql .= 'SELECT COUNT(folder_id) FROM '.$table_folder.' WHERE folder_id = '.$folder_id.' ';
	
	$prs = $xoopsDB->query($sql);
	list( $is_Exists ) = $xoopsDB->fetchRow( $prs );
	
	if($is_Exists!=0){
		return true;
	}else{
		return false;
	}
}

//----------------------------------------
//Test use only
/* function var_dump_txt($filename,$element,$mode='w'){
ob_start();
echo "var:\n";
var_dump($element);
$out=ob_get_contents();
ob_end_clean();
$fp = fopen( $filename, $mode );
fputs( $fp, $out );
fclose( $fp );
} */
//-------------------------------------------


?>