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

include "header.php" ;
$myts =& MyTextSanitizer::getInstance() ; // MyTextSanitizer object

// GET variables
$cid = empty( $_GET['cid'] ) ? 1 : intval( $_GET['cid'] ) ;
$num = empty( $_GET['num'] ) ? intval( $filesharing_perpage ) : intval( $_GET['num'] ) ;
if( $num < 1 ) $num = $filesharing_perpage ;
$pos = empty( $_GET['pos'] ) ? 0 : intval( $_GET['pos'] ) ;
$view = empty( $_GET['view'] ) ? $filesharing_viewcattype : $_GET['view'] ;

$xoopsOption['template_main'] = "filesharing_viewcat_list.html" ;

include( XOOPS_ROOT_PATH . "/header.php" ) ;

include( 'include/assign_globals.php' ) ;
$xoopsTpl->assign( $filesharing_assign_globals ) ;

if( $cid > 1 ) {
	$sql  = '';
	$sql .= 'SELECT t1.cnt + t2.cnt ';
	$sql .= 'FROM (SELECT COUNT(cid) as cnt FROM '.$table_cat.' WHERE pid = '.$cid.') AS t1, ';
	$sql .= '(SELECT COUNT(lid) as cnt FROM '.$table_files.' WHERE status > 0 AND cid = '.$cid.') as t2 ';
	$prs = $xoopsDB->query($sql);
	list( $file_small_sum ) = $xoopsDB->fetchRow( $prs );
	$xoopsTpl->assign( 'file_small_sum' , $file_small_sum ) ;
	
	$sql  = '';
	$sql .= 'SELECT ';
	$sql .= 'title, ';
	$sql .= 'description, ';
	$sql .= 'read_permission_type, ';
	$sql .= 'read_permission_user, ';
	$sql .= 'edit_permission_type, ';
	$sql .= 'edit_permission_user ';
	$sql .= 'FROM '.$table_cat.' ';
	$sql .= 'where cid = '.$cid.' ';
	$rs = $xoopsDB->query( $sql );
	list($folder_title,$folder_desc,$r_type,$r_user,$e_type,$e_user) = $xoopsDB->fetchRow( $rs );
	if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
		$xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );
		$xoopsTpl->assign( 'lang_add_folder' , _MD_ALBM_ADDFOLDER );
	}
	$xoopsTpl->assign( 'folder_title' , $myts->makeTboxData4show($folder_title) );
	$xoopsTpl->assign( 'folder_description' , $myts->makeTboxData4show($folder_desc) );
	$xoopsTpl->assign( 'lang_album_main' , _MD_ALBM_MAIN ) ;

	// Category Specified
	$xoopsTpl->assign( 'category_id' , $cid ) ;
	//$xoopsTpl->assign( 'subcategories' , filesharing_get_sub_categories( $cid , $cattree ) ) ;
	//$xoopsTpl->assign( 'category_options' , filesharing_get_cat_options('title',$cid) ) ;

/*
	$cids = $cattree->getAllChildId( $cid ) ;
	array_push( $cids , $cid ) ;
	$file_total_sum = filesharing_get_file_total_sum_from_cats( $cids , "status > 0" ) ;

	//$sub_title = preg_replace( "/\'\>/" , "'><img src='$mod_url/images/folder16.gif' alt='' />" , $cattree->getNicePathFromId( $cid , 'title' , "?page=viewcat&num=$num" ) ) ;
	//$sub_title = preg_replace( "/^(.+)folder16/" , '$1folder_open' , $sub_title ) ;

	$nolink = explode("/",$cattree->getPathFromId($cid, "title"));
	array_shift($nolink);
	$link = explode("&nbsp;:&nbsp;",$cattree->getNicePathFromId( $cid , 'title' , "?page=viewcat&num=$num" ));
	array_pop($link);
	array_shift($link);
	
	$bread_list = array();
	foreach($nolink as $k => $v){
		$kuzu = "";
		if($k == (count($nolink)-1)){
			//$kuzu = "<img src='".$mod_url."/images/folder16.gif' alt='' />".$v;
			$kuzu = $v;
			$sub_title = $v;
		}else{
			$kuzu = $link[$k];
			//$kuzu = preg_replace( "/\'\>/" , "'><img src='".$mod_url."/images/folder16.gif' alt='' />" , $kuzu ) ;
			//$kuzu = preg_replace( "/^(.+)folder16/" , '$1folder_open' , $kuzu ) ;
		}
		$bread_list[] = $kuzu;
	}
*/
	$bread_list = array();
	$ftree = get_folder_tree($cid);
	foreach($ftree as $folders){
		foreach($folders as $val){
			if($val['selected']){
				if($val['id'] == $cid){
					$bread_list[] = $val['title'];
				}else{
					$bread_list[] = '<a href="?page=viewcat&cid='.$val['id'].'">'.$val['title'].'</a>';
				}
			}
		}
	}
	$xoopsTpl->assign( 'bread_list' , $bread_list ) ;

	if( $file_small_sum > 0 ) {
		if(isset($_GET['sortkey'])){
			$sortkey = intval($_GET['sortkey']);
		}else{
			$sortkey = 0;
		}
		$OrderSQL = ' ORDER BY ';
		if($sortkey > 0 && $sortkey < 11){
			switch($sortkey){
				case  1:
				case  2:$OrderSQL.='title';break;
				case  3:
				case  4:$OrderSQL.='description';break;
				case  5:
				case  6:$OrderSQL.='read_permission';break;
				case  7:
				case  8:$OrderSQL.='edit_permission';break;
				case  9:
				case 10:$OrderSQL.='date';break;
			}
			if($sortkey % 2 == 0){
				$OrderSQL .= ' ASC ';
			}else{
				$OrderSQL .= ' DESC ';
			}
		}else{
			switch(trim($filesharing_defaultorder)){
				case "titleA":$OrderSQL.='title ASC ';break;
				case "titleD":$OrderSQL.='title DESC ';break;
				case "descriptionA":$OrderSQL.='description ASC ';break;
				case "descriptionD":$OrderSQL.='description DESC ';break;
				case "readA":$OrderSQL.='read_permission ASC ';break;
				case "readD":$OrderSQL.='read_permission DESC ';break;
				case "editA":$OrderSQL.='edit_permission ASC ';break;
				case "editD":$OrderSQL.='edit_permission DESC ';break;
				case "dateA":$OrderSQL.='date ASC ';break;
				case "dateD":$OrderSQL.='date DESC ';break;
				default:$OrderSQL.='date DESC ';break;
			}
		}
		require_once './class/sortheader.php';
		$sortheader = new SortHeader(5,$sortkey);
		$xoopsTpl->assign( 'nowpos' , $pos );
		$xoopsTpl->assign( 'sortheader' , $sortheader );

		$sql  = '';
		$sql .= '(SELECT ';
		$sql .= '1 as ftype,';
		$sql .= 'cid as id,';
		$sql .= 'title,';
		$sql .= 'description,';
		$sql .= 'read_permission_type,';
		$sql .= 'read_permission_user,';
		$sql .= 'IF((read_permission_type = \'public\' OR (read_permission_type = \'user\' and read_permission_user ='.$my_uid.')),1,0) as read_permission,';
		$sql .= 'edit_permission_type,';
		$sql .= 'edit_permission_user,';
		$sql .= 'IF((edit_permission_type = \'public\' OR (edit_permission_type = \'user\' and edit_permission_user ='.$my_uid.')),1,0) as edit_permission,';
		$sql .= 'user_id as owner,';
		$sql .= 'edit_date as date ';
		$sql .= 'from '.$table_cat.' ';
		$sql .= 'where pid = '.$cid.') ';
		$sql .= ' UNION ';
		$sql .= '(SELECT ';
		$sql .= '2 as ftype,';
		$sql .= 'lid as id,';
		$sql .= 'title,';
		$sql .= 'description,';
		$sql .= 'read_permission_type,';
		$sql .= 'read_permission_user,';
		$sql .= 'IF((read_permission_type = \'public\' OR (read_permission_type = \'user\' and read_permission_user ='.$my_uid.')),1,0) as read_permission,';
		$sql .= 'edit_permission_type,';
		$sql .= 'edit_permission_user,';
		$sql .= 'IF((edit_permission_type = \'public\' OR (edit_permission_type = \'user\' and edit_permission_user ='.$my_uid.')),1,0) as edit_permission,';
		$sql .= 'submitter as owner,';
		$sql .= 'edit_date as date ';
		$sql .= 'from '.$table_files.' ';
		$sql .= 'where status > 0 ';
		$sql .= 'and cid = '.$cid.') ';
		$sql .= $OrderSQL;

		$prs = $xoopsDB->queryF( $sql , $num , $pos );

		//if 2 or more items in result, num the navigation menu
		if( $file_small_sum > 1 ) {

			// Assign navigations like order and division
			//$xoopsTpl->assign( 'show_nav' ,  true ) ;
			//$xoopsTpl->assign( 'lang_sortby' , _MD_ALBM_SORTBY ) ;
			//$xoopsTpl->assign( 'lang_title' , _MD_ALBM_FILENAME ) ;
			//$xoopsTpl->assign( 'lang_date' , _MD_ALBM_DATE ) ;
			//$xoopsTpl->assign( 'lang_rating' , _MD_ALBM_RATING ) ;
			//$xoopsTpl->assign( 'lang_popularity' , _MD_ALBM_POPULARITY ) ;
			//$xoopsTpl->assign( 'lang_cursortedby' , sprintf( _MD_ALBM_CURSORTEDBY , $filesharing_orders[$orderby][1] ) ) ;

			include_once( XOOPS_ROOT_PATH . '/class/pagenav.php' );
			$nav = new XoopsPageNav( $file_small_sum , $num , $pos , 'pos' , "page=viewcat&cid=".$cid."&num=".$num."&sortkey=$sortkey" ) ;
			$nav_html = $nav->renderNav( 10 ) ;
			include_once("./include/pager.php");
			$nav_html = format_pager($nav_html);
			
			$last = $pos + $num ;
			if( $last > $file_small_sum ) $last = $file_small_sum ;
			$filenavinfo = sprintf( _MD_ALBM_AM_FILENAVINFO , $pos + 1 , $last , $file_small_sum ) ;
			$xoopsTpl->assign( 'filenav' , $nav_html ) ;
			$xoopsTpl->assign( 'filenavinfo' , $filenavinfo ) ;
		}

		// Display files
		$count = 1 ;
		$files = array();
		while( $fetched_result_array = $xoopsDB->fetchArray( $prs ) ) {
			$files[] = filesharing_get_array_for_file_assign( $fetched_result_array , true );
			//$file = $function_assigning( $fetched_result_array ) + array( 'count' => $count ++ , true ) ;
			//$xoopsTpl->append( 'files' , $file ) ;
		}
		$xoopsTpl->assign( 'files' , $files );

	}

/*
} else if( $uid != 0 ) {

	// This means 'my file'
	if( $uid < 0 ) {
		$xoopsModule->getInfo() ;
		$where = "submitter=".$my_uid ;
		$get_append = "uid=-1" ;
		$xoopsTpl->assign( 'uid' , -1 ) ;
		$xoopsTpl->assign( 'bread_list' , array($sub_title) ) ;
		$xoopsTpl->assign( 'album_sub_title' , _MD_ALBM_TEXT_SMNAME4 ) ;
		$xoopsTpl->assign( 'xoops_pagetitle' , _MD_ALBM_TEXT_SMNAME4 ) ;
	// uid Specified
	} else {
		$where = "submitter=".$uid ;
		$get_append = "uid=".$uid  ;
		$xoopsTpl->assign( 'uid' , $uid ) ;

		$sub_title = "<img src='".$mod_url."/images/myfiles.gif' alt='' />".filesharing_get_name_from_uid( $uid );

		$xoopsTpl->assign( 'bread_list' , array($sub_title) ) ;
		$xoopsTpl->assign( 'album_sub_title' , $sub_title ) ;
		$xoopsTpl->assign( 'xoops_pagetitle' , filesharing_get_name_from_uid( $uid ) ) ;
	}
	$join_append = " LEFT JOIN ".$table_cat." c USING(cid) " ;
	$select_append = ', c.title AS cat_title' ;
*/
} else {
	redirect_header($mod_url);
	//$where = "cid=0";
	//$get_append = "cid=0";
	//$join_append = '' ;
	//$select_append = '' ;
	//$xoopsTpl->assign( 'album_sub_title' , 'error: category id not specified' ) ;

}


include( XOOPS_ROOT_PATH . "/footer.php" ) ;
?>