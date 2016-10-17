<?php

// for older files
function filesharing_header()
{
	global $mod_url;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_url' => $mod_url ) ) ;
	$tpl->display( "db:filesharing_header.html" ) ;
}


// for older files
function filesharing_footer()
{
	global $mod_copyright ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_copyright' => $mod_copyright ) ) ;
	$tpl->display( "db:filesharing_footer.html" ) ;
}


// returns appropriate name from uid
function filesharing_get_name_from_uid( $uid )
{
	global $filesharing_nameoruname ;

	if( $uid > 0 ) {
		$member_handler =& xoops_gethandler( 'member' ) ;
		$poster =& $member_handler->getUser( $uid ) ;

		if( is_object( $poster ) ) {
			if( $filesharing_nameoruname == 'uname' || trim( $poster->name() ) == '' ) {
				$name = htmlspecialchars( $poster->uname() , ENT_QUOTES ) ;
			} else {
				$name = htmlspecialchars( $poster->name() , ENT_QUOTES ) ;
			}
		} else {
			$name = _MD_ALBM_CAPTION_GUESTNAME ;
		}

	} else {
		$name = _MD_ALBM_CAPTION_GUESTNAME ;
	}

	return $name ;
}


// Get file's array to assign into template (heavy version)
function filesharing_get_array_for_file_assign( $fetched_result_array , $summary = false )
{
	global $my_uid , $isadmin , $global_perms ,$xoopsDB,$table_cat,$table_files;
	global $files_url , $thumbs_url , $thumbs_dir , $mod_url , $mod_path ;
	global $filesharing_makethumb , $filesharing_thumbsize , $filesharing_popular , $filesharing_newdays , $filesharing_normal_exts ;

	include_once( "$mod_path/class/filesharing.textsanitizer.php" ) ;

	$myts =& MyAlbumTextSanitizer::getInstance() ;

	extract( $fetched_result_array ) ;

	/*
	if( in_array( strtolower( $ext ) , $filesharing_normal_exts ) ) {
		$imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
		$imgsrc_file = "$files_url/$lid.$ext" ;
		$ahref_file = "$files_url/$lid.$ext" ;
		$is_normal_image = true ;

		// Width of thumb
		$width_spec = "width='$filesharing_thumbsize'" ;
		if( $filesharing_makethumb ) {
			list( $width , $height , $type ) = getimagesize( "$thumbs_dir/$lid.$ext" ) ;
			// if thumb images was made, 'width' and 'height' will not set.
			if( $width <= $filesharing_thumbsize ) $width_spec = '' ;
		}
	} else {
		$imgsrc_thumb = "$thumbs_url/$lid.gif" ;
		$imgsrc_file = "$thumbs_url/$lid.gif" ;
		$ahref_file = "$files_url/$lid.$ext" ;
		$is_normal_image = false ;
		$width_spec = '' ;
	}

	// Voting stats
	if( $rating > 0 ) {
		if( $votes == 1 ) {
			$votestring = _MD_ALBM_ONEVOTE ;
		} else {
			$votestring = sprintf( _MD_ALBM_NUMVOTES , $votes ) ;
		}
		$info_votes = number_format( $rating , 2 )." ($votestring)";
	} else {
		$info_votes = '0.00 ('.sprintf( _MD_ALBM_NUMVOTES , 0 ) . ')' ;
	}
	*/
	// Submitter's name
	$submitter_name = filesharing_get_name_from_uid( $owner ) ;

	// Category's title
	//$cat_title = empty( $cat_title ) ? '' : $cat_title ;

	// Summarize description
	if( $summary ) $description = $myts->extractSummary( $description ) ;
	
	if($isadmin || $read_permission_type == 'public' || ($read_permission_type == 'user' && $read_permission_user == $my_uid)){
		$can_read = true;
	}else{
		$can_read = false;
	}
	
	if($isadmin || $edit_permission_type == 'public' || ($edit_permission_type == 'user' && $edit_permission_user == $my_uid)){
		$can_edit = true;
	}else{
		$can_edit = false;
	}
	
	/*
	if($ftype == 1){	//folder
		if($can_edit){
			$sql  = '';
			$sql .= 'SELECT t1.cnt + t2.cnt ';
			$sql .= 'FROM (SELECT COUNT(cid) as cnt FROM '.$table_cat.' WHERE pid = '.$id.') AS t1, ';
			$sql .= '(SELECT COUNT(lid) as cnt FROM '.$table_files.' WHERE status > 0 AND cid = '.$id.') as t2 ';
			$prs = $xoopsDB->query($sql);
			list( $file_num_total ) = $xoopsDB->fetchRow( $prs );
			if($file_num_total == 0){
				$can_delete = true;
			}else{
				$can_delete = false;
			}
		}else{
			$can_delete = false;
		}
	}else{	//file
		$can_delete = $can_edit;
	}
	*/
	$can_delete = $can_edit;
	
	/*
	return array(
		'lid' => $lid ,
		'cid' => $cid ,
		'ext' => $ext ,
		'res_x' => $res_x ,
		'res_y' => $res_y ,
		'window_x' => $res_x + 16 ,
		'window_y' => $res_y + 16 ,
		'title' => $myts->makeTboxData4Show( $title ) ,
		'datetime' => formatTimestamp( $date , 'm' ) ,
		'description' => $myts->displayTarea( $description , 0 , 1 , 1 , 1 , 1 , 1 ) ,
		'imgsrc_thumb' => $imgsrc_thumb ,
		'imgsrc_file' => $imgsrc_file ,
		'ahref_file' => $ahref_file ,
		'width_spec' => $width_spec ,
		'can_edit' => ( ( $global_perms & GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
		'submitter' => $submitter ,
		'submitter_name' => $submitter_name ,
		'submitter_info' => XOOPS_URL."/userinfo.php?uid=".$submitter ,
		'hits' => $hits ,
		'rating' => $rating ,
		'rank' => floor( $rating - 0.001 ) ,
		'votes' => $votes ,
		'info_votes' => $info_votes ,
		'comments' => $comments ,
		'is_normal_image' => $is_normal_image ,
		'is_newfile' => ( $date > time() - 86400 * $filesharing_newdays && $status == 1 ) , 
		'is_updatedfile' => ( $date > time() - 86400 * $filesharing_newdays && $status == 2 ) , 
		'is_popularfile' => false ,//( $hits >= $filesharing_popular ) ,
		'info_morefiles' => sprintf( _MD_ALBM_MOREFILES , $submitter_name ) ,
		'cat_title' => $myts->makeTboxData4Show( $cat_title )
	) ;
	*/

	return array(
		'ftype' => $ftype ,
		'id' => $id ,
		'title' => $myts->makeTboxData4Show( $title ) ,
		'datetime' => formatTimestamp( $date , 'm' ) ,
		'description' => $myts->displayTarea( $description , 0 , 1 , 1 , 1 , 1 , 1 ) ,
		'can_delete' => $can_delete ,
		'can_read' => $can_read ,
		'can_edit' => $can_edit ,
		'submitter' => $owner ,
		'submitter_name' => $submitter_name ,
		'submitter_info' => XOOPS_URL."/userinfo.php?uid=".$owner ,
		'is_newfile' => ( $date > time() - 86400 * $filesharing_newdays && $status == 1 ) , 
		'is_updatedfile' => ( $date > time() - 86400 * $filesharing_newdays && $status == 2 )
	);
}


// Get file's array to assign into template (light version)
function filesharing_get_array_for_file_assign_light( $fetched_result_array , $summary = false )
{
	global $my_uid , $isadmin , $global_perms ;
	global $files_url , $thumbs_url , $thumbs_dir ;
	global $filesharing_makethumb , $filesharing_thumbsize , $filesharing_normal_exts ;

	$myts =& MyTextSanitizer::getInstance() ;

	extract( $fetched_result_array ) ;

	if( in_array( strtolower( $ext ) , $filesharing_normal_exts ) ) {
		$imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
		$imgsrc_file = "$files_url/$lid.$ext" ;
		$is_normal_image = true ;
		// Width of thumb
		$width_spec = "width='$filesharing_thumbsize'" ;
		if( $filesharing_makethumb && $ext != 'gif' ) {
			// if thumb images was made, 'width' and 'height' will not set.
			$width_spec = '' ;
		}
	} else {
		$imgsrc_thumb = "$thumbs_url/$lid.gif" ;
		$imgsrc_file = "$thumbs_url/$lid.gif" ;
		$is_normal_image = false ;
		$width_spec = "" ;
	}

	return array(
		'lid' => $lid ,
		'cid' => $cid ,
		'ext' => $ext ,
		'res_x' => $res_x ,
		'res_y' => $res_y ,
		'window_x' => $res_x + 16 ,
		'window_y' => $res_y + 16 ,
		'title' => $myts->makeTboxData4Show( $title ) ,
		'imgsrc_thumb' => $imgsrc_thumb ,
		'imgsrc_file' => $imgsrc_file ,
		'width_spec' => $width_spec ,
		'can_edit' => ( ( $global_perms & GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
		'hits' => $hits ,
		'rating' => $rating ,
		'rank' => floor( $rating - 0.001 ) ,
		'votes' => $votes ,
		'comments' => $comments ,
		'is_normal_image' => $is_normal_image
	) ;
}


// get list of sub categories in header space
function filesharing_get_sub_categories( $parent_id , $cattree )
{
	global $xoopsDB , $table_cat ;

	$myts =& MyTextSanitizer::getInstance() ;

	$ret = array() ;

	$crs = $xoopsDB->query( "SELECT cid, title FROM ".$table_cat." WHERE pid=".$parent_id." ORDER BY title") or die( "Error: Get Category." ) ;

	while( list( $cid , $title , $imgurl ) = $xoopsDB->fetchRow( $crs ) ) {

		// Show first child of this category
		$subcat = array() ;
		$arr = $cattree->getFirstChild( $cid , "title" ) ;
		foreach( $arr as $child ) {
			$subcat[] = array(
				'cid' => $child['cid'] ,
				'title' => $myts->makeTboxData4Show( $child['title'] ) ,
				'file_small_sum' => filesharing_get_file_small_sum_from_cat( $child['cid'] , "status>0" ) ,
				'number_of_subcat' => sizeof( $cattree->getFirstChildId( $child['cid'] ) )
			) ;
		}

		// Category's banner default
		if( $imgurl == "http://" ) $imgurl = '' ;

		// Total sum of files
		$cids = $cattree->getAllChildId( $cid ) ;
		array_push( $cids , $cid ) ;
		$file_total_sum = filesharing_get_file_total_sum_from_cats( $cids , "status>0" ) ;

		$ret[] = array(
			'cid' => $cid ,
			'imgurl' => $myts->makeTboxData4Edit( $imgurl ) ,
			'file_small_sum' => filesharing_get_file_small_sum_from_cat( $cid , "status>0" ) ,
			'file_total_sum' => $file_total_sum ,
			'title' => $myts->makeTboxData4Show( $title ) ,
			'subcategories' => $subcat
		) ;
	}

	return $ret ;
}


// get attributes of <img> for preview image
function filesharing_get_img_attribs_for_preview( $preview_name )
{
	global $files_url , $mod_url , $mod_path , $filesharing_normal_exts , $filesharing_thumbsize ;

	$ext = substr( strrchr( $preview_name , '.' ) , 1 ) ;

	if( in_array( strtolower( $ext ) , $filesharing_normal_exts ) ) {
		return array( "$files_url/$preview_name" , "width='$filesharing_thumbsize'" , "$files_url/$preview_name" ) ;

	} else {
		if( file_exists( "$mod_path/icons/$ext.gif" ) ) {
			return array( "$mod_url/icons/mp3.gif" , '' , "$files_url/$preview_name" ) ;
		} else {
			return array( "$mod_url/icons/default.gif" , '' , "$files_url/$preview_name" ) ;
		}
	}
}

?>