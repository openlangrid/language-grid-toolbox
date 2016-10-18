<?php

// for older files
function apfilesharing_header()
{
	global $mod_url;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_url' => $mod_url ) ) ;
	$tpl->display( "db:apfilesharing_header.html" ) ;
}


// for older files
function apfilesharing_footer()
{
	global $mod_copyright ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_copyright' => $mod_copyright ) ) ;
	$tpl->display( "db:apfilesharing_footer.html" ) ;
}


// returns appropriate name from uid
function apfilesharing_get_name_from_uid( $uid )
{
	global $apfilesharing_nameoruname ;

	if( $uid > 0 ) {
		$member_handler =& xoops_gethandler( 'member' ) ;
		$poster =& $member_handler->getUser( $uid ) ;

		if( is_object( $poster ) ) {
			if( $apfilesharing_nameoruname == 'uname' || trim( $poster->name() ) == '' ) {
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
function apfilesharing_get_array_for_file_assign( $fetched_result_array , $summary = false )
{
	global $my_uid , $isadmin , $global_perms ,$xoopsDB,$table_folder,$table_files;
	global $files_url , $thumbs_url , $thumbs_dir , $mod_url , $mod_path ;
	global $apfilesharing_makethumb , $apfilesharing_thumbsize , $apfilesharing_popular , $apfilesharing_newdays , $apfilesharing_normal_exts ;

	include_once( "$mod_path/class/apfilesharing.textsanitizer.php" ) ;
	$myts =& MyAlbumTextSanitizer::getInstance() ;

	extract( $fetched_result_array ) ;


	// Submitter's name
	$submitter_name = apfilesharing_get_name_from_uid( $user_id ) ;

	// Summarize description
	if( $summary ) $description = $myts->extractSummary( $description ) ;
	
	if($isadmin || $read_permission_type == 'public' || (($read_permission_type == 'user' ||$read_permission_type == 'protected')&& $user_id == $my_uid)||($read_permission_type == 'protected'&& check_read_permission_group($id,$ftype) )){
		$can_read = true;
	}else{
		$can_read = false;
	}
	
	if($isadmin || $edit_permission_type == 'public' || (($edit_permission_type == 'user' ||$edit_permission_type == 'protected')&& $user_id == $my_uid)||($edit_permission_type == 'protected'&& check_edit_permission_group($id,$ftype) )){
		$can_edit = true;
	}else{
		$can_edit = false;
	}
	

	$can_delete = $can_edit;
	


	return array(
		'ftype' => $ftype ,
		'id' => $id ,
		'title' => $myts->makeTboxData4Show( $title ) ,
		'datetime' => formatTimestamp( $date , 'm' ) ,
		'description' => $myts->displayTarea( $description , 0 , 1 , 1 , 1 , 1 , 1 ) ,
		'can_delete' => $can_delete ,
		'can_read' => $can_read ,
		'can_edit' => $can_edit ,
		'submitter' => $user_id ,
		'submitter_name' => $submitter_name ,
		'submitter_info' => XOOPS_URL."/userinfo.php?uid=".$user_id ,
		'is_newfile' => ( $date > time() - 86400 * $apfilesharing_newdays && $status == 1 ) , 
		'is_updatedfile' => ( $date > time() - 86400 * $apfilesharing_newdays && $status == 2 )
	);
}






/* // Get file's array to assign into template (light version)
function apfilesharing_get_array_for_file_assign_light( $fetched_result_array , $summary = false )
{
	global $my_uid , $isadmin , $global_perms ;
	global $files_url , $thumbs_url , $thumbs_dir ;
	global $apfilesharing_makethumb , $apfilesharing_thumbsize , $apfilesharing_normal_exts ;

	$myts =& MyTextSanitizer::getInstance() ;
	
	extract( $fetched_result_array ) ;

	if( in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) ) {
		$imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
		$imgsrc_file = "$files_url/$lid.$ext" ;
		$is_normal_image = true ;
		// Width of thumb
		$width_spec = "width='$apfilesharing_thumbsize'" ;
		if( $apfilesharing_makethumb && $ext != 'gif' ) {
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
function apfilesharing_get_sub_categories( $parent_id , $cattree )
{
	global $xoopsDB , $table_folder ;

	$myts =& MyTextSanitizer::getInstance() ;

	$ret = array() ;

	$crs = $xoopsDB->query( "SELECT cid, title FROM ".$table_folder." WHERE pid=".$parent_id." ORDER BY title") or die( "Error: Get Category." ) ;

	while( list( $cid , $title , $imgurl ) = $xoopsDB->fetchRow( $crs ) ) {

		// Show first child of this category
		$subcat = array() ;
		$arr = $cattree->getFirstChild( $cid , "title" ) ;
		foreach( $arr as $child ) {
			$subcat[] = array(
				'cid' => $child['cid'] ,
				'title' => $myts->makeTboxData4Show( $child['title'] ) ,
				'file_small_sum' => apfilesharing_get_file_small_sum_from_cat( $child['cid'] , "status>0" ) ,
				'number_of_subcat' => sizeof( $cattree->getFirstChildId( $child['cid'] ) )
			) ;
		}

		// Category's banner default
		if( $imgurl == "http://" ) $imgurl = '' ;

		// Total sum of files
		$cids = $cattree->getAllChildId( $cid ) ;
		array_push( $cids , $cid ) ;
		$file_total_sum = apfilesharing_get_file_total_sum_from_cats( $cids , "status>0" ) ;

		$ret[] = array(
			'cid' => $cid ,
			'imgurl' => $myts->makeTboxData4Edit( $imgurl ) ,
			'file_small_sum' => apfilesharing_get_file_small_sum_from_cat( $cid , "status>0" ) ,
			'file_total_sum' => $file_total_sum ,
			'title' => $myts->makeTboxData4Show( $title ) ,
			'subcategories' => $subcat
		) ;
	}

	return $ret ;
}


// get attributes of <img> for preview image
function apfilesharing_get_img_attribs_for_preview( $preview_name )
{
	global $files_url , $mod_url , $mod_path , $apfilesharing_normal_exts , $apfilesharing_thumbsize ;

	$ext = substr( strrchr( $preview_name , '.' ) , 1 ) ;

	if( in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) ) {
		return array( "$files_url/$preview_name" , "width='$apfilesharing_thumbsize'" , "$files_url/$preview_name" ) ;

	} else {
		if( file_exists( "$mod_path/icons/$ext.gif" ) ) {
			return array( "$mod_url/icons/mp3.gif" , '' , "$files_url/$preview_name" ) ;
		} else {
			return array( "$mod_url/icons/default.gif" , '' , "$files_url/$preview_name" ) ;
		}
	}
} */

?>