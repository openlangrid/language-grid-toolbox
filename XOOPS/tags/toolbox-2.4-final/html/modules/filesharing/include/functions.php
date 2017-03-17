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


function filesharing_get_thumbnail_wh( $width , $height )
{
	global $filesharing_thumbsize , $filesharing_thumbrule ;

	switch( $filesharing_thumbrule ) {
		case 'w' :
			$new_w = $filesharing_thumbsize ;
			$scale = $width / $new_w ;
			$new_h = intval( round( $height / $scale ) ) ;
			break ;
		case 'h' :
			$new_h = $filesharing_thumbsize ;
			$scale = $height / $new_h ;
			$new_w = intval( round( $width / $scale ) ) ;
			break ;
		case 'b' :
			if( $width > $height ) {
				$new_w = $filesharing_thumbsize ;
				$scale = $width / $new_w ; 
				$new_h = intval( round( $height / $scale ) ) ;
			} else {
				$new_h = $filesharing_thumbsize ;
				$scale = $height / $new_h ; 
				$new_w = intval( round( $width / $scale ) ) ;
			}
			break ;
		default :
			$new_w = $filesharing_thumbsize ;
			$new_h = $filesharing_thumbsize ;
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
function filesharing_create_icon( $src_path , $node , $ext )
{
	global $filesharing_makethumb , $filesharing_normal_exts ;

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


	//if( ! in_array( strtolower( $ext ) , $filesharing_normal_exts ) ) {
	//	return filesharing_copy_thumb_from_icons( $src_path , $node , $ext ) ;
	//}

	//if( ! $filesharing_makethumb ) return 3 ;
}


// Copy Thumbnail from directory of icons
function filesharing_copy_thumb_from_icons( $src_path , $node , $ext )
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
function filesharing_modify_file( $src_path , $dst_path )
{
	rename( $src_path , $dst_path ) ;
}

// Clear templorary files
function filesharing_clear_tmp_files( $dir_path , $prefix = 'tmp_' )
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
function filesharing_updaterating( $lid )
{
	global $xoopsDB , $table_votedata , $table_files ;

	$query = "SELECT rating FROM $table_votedata WHERE lid=$lid" ;
	$voteresult = $xoopsDB->query( $query ) ;
	$votesDB = $xoopsDB->getRowsNum( $voteresult ) ;
	$totalrating = 0 ;
	while( list( $rating ) = $xoopsDB->fetchRow( $voteresult ) ) {
		$totalrating += $rating ;
	}
	$finalrating = number_format( $totalrating / $votesDB , 4 ) ;
	$query = "UPDATE $table_files SET rating=$finalrating, votes=$votesDB WHERE lid = $lid" ;

	$xoopsDB->query( $query ) or die( "Error: DB update rating." ) ;
}


// Returns the number of files included in a Category
function filesharing_get_file_small_sum_from_cat( $cid , $whr_append = "" )
{
	global $xoopsDB , $table_files ;

	if( $whr_append ) $whr_append = "AND ($whr_append)" ;

	$sql = "SELECT COUNT(lid) FROM $table_files WHERE cid=$cid $whr_append" ;
	$rs = $xoopsDB->query( $sql ) ;
	list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

	return $numrows ;
}


// Returns the number of whole files included in a Category
function filesharing_get_file_total_sum_from_cats( $cids , $whr_append = "" )
{
	global $xoopsDB , $table_files ;

	if( $whr_append ) $whr_append = "AND ($whr_append)" ;

	$whr = "cid IN (" ;
	foreach( $cids as $cid ) {
		$whr .= "$cid," ;
	}
	$whr = "$whr 0)" ;

	$sql = "SELECT COUNT(lid) FROM ".$table_files." WHERE .".$whr." ".$whr_append." " ;
	$rs = $xoopsDB->query( $sql ) ;
	list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

	return $numrows ;
}


// Update a file 
function filesharing_update_file( $lid , $cid , $title , $desc , $valid = null , $ext = "" , $x = "" , $y = "" )
{
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	global $table_files , $table_text , $table_cat , $mod_url , $isadmin ;

	if( isset( $valid ) ) {
		$set_status = ",status='$valid'" ;

		// Trigger Notification
		if( $valid == 1 ) {
			$notification_handler =& xoops_gethandler( 'notification' ) ;

			// Global Notification
			$notification_handler->triggerEvent( 'global' , 0 , 'new_file' , array( 'FILE_TITLE' => $title , 'FILE_URI' => "$mod_url/?page=file&lid=$lid&cid=$cid" ) ) ;

			// Category Notification
			$rs = $xoopsDB->query( "SELECT title FROM $table_cat WHERE cid=$cid" ) ;
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
		$xoopsDB->query("UPDATE $table_files SET cid='$cid',title='".addslashes($title)."' $set_status $set_date WHERE lid='$lid' $whr_status") ;
	} else {
		// modify text and image
		$xoopsDB->query("UPDATE $table_files SET cid='$cid',title='".addslashes($title)."', ext='$ext',res_x='$x',res_y='$y' $set_status $set_date WHERE lid='$lid' $whr_status");
	}

	$xoopsDB->query("UPDATE $table_text SET description='".addslashes($desc)."' WHERE lid='$lid'");

	//redirect_header( "?page=editfile&lid=$lid" , 0 , _MD_ALBM_DBUPDATED ) ;
}


// Delete files hit by the $whr clause
function filesharing_delete_files( $whr )
{
	global $xoopsDB ;
	global $files_dir , $thumbs_dir , $filesharing_mid ;
	global $table_files , $table_text , $table_votedata ;

	$prs = $xoopsDB->query("SELECT lid, ext FROM $table_files WHERE $whr" ) ;
	while( list( $lid , $ext ) = $xoopsDB->fetchRow( $prs ) ) {

		xoops_comment_delete( $filesharing_mid , $lid ) ;
		xoops_notification_deletebyitem( $filesharing_mid , 'file' , $lid ) ;

		//$xoopsDB->query( "DELETE FROM $table_votedata WHERE lid=$lid" ) or die( "DB error: DELETE votedata table." ) ;
		//$xoopsDB->query( "DELETE FROM $table_text WHERE lid=$lid" ) or die( "DB error: DELETE text table." ) ;
		$xoopsDB->query( "DELETE FROM $table_files WHERE lid=$lid" ) or die( "DB error: DELETE file table." ) ;
	
		@unlink( "$files_dir/$lid.$ext" ) ;
		@unlink( "$files_dir/$lid.gif" ) ;
		@unlink( "$thumbs_dir/$lid.$ext" ) ;
		@unlink( "$thumbs_dir/$lid.gif" ) ;
	}
}


// Substitution of opentable()
function filesharing_opentable()
{
	echo "<div style='border: 2px solid #2F5376;padding:8px;width:95%;' class='bg4'>\n" ;
}


// Substitution of closetable()
function filesharing_closetable()
{
	echo "</div>\n" ;
}


// returns extracted string for options from table with xoops tree
function filesharing_get_cat_options( $order = 'title' , $preset = 0 , $prefix = '--' , $none = null , $table_name_cat = null , $table_name_files = null )
{
	global $xoopsDB ;

	$myts =& MyTextSanitizer::getInstance() ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;
	if( empty( $table_name_files ) ) $table_name_files = $GLOBALS['table_files'] ;

	$cats[0] = array( 'cid' => 0 , 'pid' => -1 , 'next_key' => -1 , 'depth' => 0 , 'title' => '' , 'num' => 0 ) ;

	$rs = $xoopsDB->query( "SELECT c.title,c.cid,c.pid,COUNT(p.lid) AS num FROM $table_name_cat c LEFT JOIN $table_name_files p ON c.cid=p.cid GROUP BY c.cid ORDER BY pid ASC,$order DESC" ) ;

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
			$folder_select .= '<select id="cid'.$cnt.'" name="cid" onchange="check_next(this,'.$cnt.')" '.$disable.'>';
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

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;
	
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
		$sql .= 'SELECT cid,title FROM '.$table_name_cat.' WHERE pid = '.intval($V).' ORDER BY title ';
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

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;
	
	if(!is_numeric($cid)){$cid=1;}

	$sql = '';
	$sql .= 'SELECT pid FROM '.$table_name_cat.' WHERE cid = '.intval($cid).' ';
	$rs = $xoopsDB->query($sql);
	list( $parent_id ) = $xoopsDB->fetchRow( $rs );

	return $parent_id;
}

function sub_folder_exists($cid,$myid = null){
	global $xoopsDB ;

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;

	if(!is_numeric($cid)){$cid=1;}
	if(!is_numeric($myid)){$myid=0;}
	
	$sql = '';
	$sql .= 'SELECT count(cid) FROM '.$table_name_cat.' WHERE pid = '.intval($cid).' AND cid != '.intval($myid).' ';
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

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;

	if(!is_numeric($myid)){$myid=0;}
	
	$sql  = '';
	$sql .= 'SELECT cid,title FROM '.$table_name_cat.' WHERE pid = '.intval($pid).' AND cid != '.intval($myid).' ORDER BY title ';
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

	if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;
	if( empty( $table_name_files ) ) $table_name_files = $GLOBALS['table_files'] ;
	
	$ret = true;
	
	$sql  = '';
	$sql .= "SELECT count(lid) ";
	$sql .= "FROM ".$table_name_files." ";
	$sql .= "WHERE cid = ".$cid." ";
	$sql .= "AND edit_permission_type = 'user' ";
	$sql .= "AND edit_permission_user != ".$uid." ";
	$rs = $xoopsDB->query($sql);
	list( $file_count) = $xoopsDB->fetchRow($rs);
	
	if($file_count > 0){
		return false;
	}else{
		$sql  = '';
		$sql .= 'SELECT ';
		$sql .= 'cid,';
		$sql .= 'edit_permission_type,';
		$sql .= 'edit_permission_user ';
		$sql .= 'FROM '.$table_name_cat.' ';
		$sql .= 'WHERE pid = '.$cid.' ';

		$rs = $xoopsDB->query($sql);
		while( $row = $xoopsDB->fetchArray( $rs ) ) {
			if($row['edit_permission_type'] == 'user' && $row['edit_permission_user'] != $uid){
				return false;
			}else{
				if(!check_folder_recurrently($row['cid'],$uid)){
					return false;
				}
			}
		}
	}
		
	return true;
}


function delete_folder_recurrently($cid){
	global $xoopsDB ;
	global $table_files , $table_cat ;

	$sql  = '';
	$sql .= "SELECT lid ";
	$sql .= "FROM ".$table_files." ";
	$sql .= "WHERE cid = ".$cid." ";
	$rs = $xoopsDB->query($sql);
	while( $row = $xoopsDB->fetchArray( $rs ) ) {
		$whr = "lid=".$row['lid'] ;
		filesharing_delete_files( $whr ) ;
	}
	$xoopsDB->freeRecordSet($rs);

	$sql  = '';
	$sql .= 'SELECT ';
	$sql .= 'cid ';
	$sql .= 'FROM '.$table_cat.' ';
	$sql .= 'WHERE pid = '.$cid.' ';

	$rs = $xoopsDB->query($sql);
	while( $row = $xoopsDB->fetchArray( $rs ) ) {
		delete_folder_recurrently($row['cid']);
	}
	$xoopsDB->freeRecordSet($rs);
	
	$xoopsDB->query( "DELETE FROM ".$table_cat." WHERE cid=".$cid) or die( "DB error: DELETE folder table." ) ;
}

function check_samename_folder($pid,$title,$myid = null){
	global $xoopsDB ;
	global $table_cat;

	$sql  = "SELECT COUNT(cid) ";
	$sql .= "FROM ".$table_cat." ";
	$sql .= "WHERE pid = ".intval($pid)." ";
	$sql .= "AND title = '".addslashes($title)."' ";
	if(is_numeric($myid)){
		$sql .= "AND cid != ".intval($myid)." ";
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

	$sql  = "SELECT COUNT(lid) ";
	$sql .= "FROM ".$table_files." ";
	$sql .= "WHERE cid = ".intval($cid)." ";
	$sql .= "AND title = '".addslashes($title)."' ";
	if(is_numeric($myid)){
		$sql .= "AND lid != ".intval($myid)." ";
	}
	$prs = $xoopsDB->query($sql);
	list( $cnt ) = $xoopsDB->fetchRow( $prs );
	if($cnt > 0){
		return false;
	}else{
		return true;
	}
}


?>