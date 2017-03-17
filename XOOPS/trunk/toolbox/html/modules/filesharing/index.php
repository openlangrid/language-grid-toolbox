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
error_reporting(E_ALL);
$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] );
$mode = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['mode'] );
if( file_exists(dirname(__FILE__).'/main/'.$page.'.php') && $mode != "admin") {
	include dirname(__FILE__).'/main/'.$page.'.php';
}

include "header.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

$xoopsOption['template_main'] = "filesharing_index.html";
$xoopsTpl->assign( 'gticket' , $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ ) );

include XOOPS_ROOT_PATH . "/header.php";

$sql  = '';
$sql .= 'SELECT t1.cnt + t2.cnt ';
$sql .= 'FROM (SELECT COUNT(cid) as cnt FROM '.$table_cat.' WHERE pid = 1) AS t1, ';
$sql .= '(SELECT COUNT(lid) as cnt FROM '.$table_files.' WHERE status > 0 AND cid = 1) as t2 ';
$prs = $xoopsDB->query($sql);
list( $file_num_total ) = $xoopsDB->fetchRow( $prs );

//$prs = $xoopsDB->query( "SELECT COUNT(lid) FROM ".$table_files." WHERE status > 0" );
//list( $file_num_total ) = $xoopsDB->fetchRow( $prs );

//$xoopsTpl->assign( 'file_global_sum' , sprintf( _MD_ALBM_THEREARE , $file_num_total ) );
//if( $global_perms & GPERM_INSERTABLE ) $xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );

/**************************************************
$sql  = '';
$sql .= 'SELECT ';
$sql .= 'description, ';
$sql .= 'read_permission_type, ';
$sql .= 'read_permission_user, ';
$sql .= 'edit_permission_type, ';
$sql .= 'edit_permission_user ';
$sql .= 'FROM '.$table_cat.' ';
$sql .= 'where cid = 1 ';	//root folder
$rs = $xoopsDB->query( $sql );
list($folder_desc,$r_type,$r_user,$e_type,$e_user) = $xoopsDB->fetchRow( $rs );
if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
	$xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );
	$xoopsTpl->assign( 'lang_add_folder' , _MD_ALBM_ADDFOLDER );
}
//$xoopsTpl->assign( 'folder_description' , $myts->makeTboxData4show($folder_desc) );
****************************************************/
if($isadmin || $filesharing_rootedit == 1){
	$xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );
	$xoopsTpl->assign( 'lang_add_folder' , _MD_ALBM_ADDFOLDER );
}
// Navigation
$num = empty( $_GET['num'] ) ? $filesharing_perpage : intval( $_GET['num'] );
if( $num < 1 ) $num = $filesharing_perpage;
$pos = empty( $_GET['pos'] ) ? 0 : intval( $_GET['pos'] );
if( $pos >= $file_num_total ) $pos = 0;

if(isset($_GET['sortkey'])){
	$sortkey = intval($_GET['sortkey']);
}else{
	$sortkey = 0;
}

if( $file_num_total > $num ) {
	include_once( XOOPS_ROOT_PATH . '/class/pagenav.php' );
	$nav = new XoopsPageNav( $file_num_total , $num , $pos , 'pos' , "num=$num" ."&sortkey=$sortkey" );
	$nav_html = $nav->renderNav( 10 );
	include_once("./include/pager.php");
	$nav_html = format_pager($nav_html);
	
	$last = $pos + $num;
	if( $last > $file_num_total ) $last = $file_num_total;
	$filenavinfo = sprintf( _MD_ALBM_AM_FILENAVINFO , $pos + 1 , $last , $file_num_total );
	$xoopsTpl->assign( 'filenavdisp' , true );
	$xoopsTpl->assign( 'filenav' , $nav_html );
	$xoopsTpl->assign( 'filenavinfo' , $filenavinfo );
} else {
	$xoopsTpl->assign( 'filenavdisp' , false );
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
	/*
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
	*/
	$OrderSQL.='ftype ASC, title ASC ';
}
require_once './class/sortheader.php';
$sortheader = new SortHeader(5,$sortkey);
$xoopsTpl->assign( 'nowpos' , $pos );
$xoopsTpl->assign( 'sortheader' , $sortheader );



// Assign Latest Files
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
$sql .= 'where pid = 1) ';
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
$sql .= 'and cid = 1) ';
$sql .= $OrderSQL;

$prs = $xoopsDB->queryF( $sql , $num , $pos );
$files = array();
while( $fetched_result_array = $xoopsDB->fetchArray( $prs ) ) {
	$files[] = filesharing_get_array_for_file_assign( $fetched_result_array , true );
}

$xoopsTpl->assign( 'files' , $files );

include( XOOPS_ROOT_PATH . "/footer.php" );
?>