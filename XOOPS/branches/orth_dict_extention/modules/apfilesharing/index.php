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
include XOOPS_ROOT_PATH . "/header.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

$xoopsOption['template_main'] = "apfilesharing_index.html";
$xoopsTpl->assign( 'gticket' , $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ ) );


require_once dirname(__FILE__).'/class/contentManager.php';
$contentManager = new ContentManager($xoopsDB,$table_folder,$table_files );
$file_num_total = $contentManager -> getContentCount(1);

//ファイルのアップ・フォルダの作成の許可
if($isadmin || $apfilesharing_rootedit == 1){
	$xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );
	$xoopsTpl->assign( 'lang_add_folder' , _MD_ALBM_ADDFOLDER );
}
// Navigation
$lsize = empty( $_GET['num'] ) ? $apfilesharing_perpage : intval( $_GET['num'] );
if( $lsize < 1 ) $lsize = $apfilesharing_perpage;
$pos = empty( $_GET['pos'] ) ? 0 : intval( $_GET['pos'] );
if( $pos >= $file_num_total ) $pos = 0;

if(isset($_GET['sortkey'])){
	$sortkey = intval($_GET['sortkey']);
}else{
	$sortkey = 0;
}

if( $file_num_total > $lsize ) {
	include_once( XOOPS_ROOT_PATH . '/class/pagenav.php' );
	$nav = new XoopsPageNav( $file_num_total , $lsize , $pos , 'pos' , "num=$lsize&sortkey=$sortkey" );
	$nav_html = $nav->renderNav( 10 );
	include_once("./include/pager.php");
	$nav_html = format_pager($nav_html);
	
	$last = $pos + $lsize;
	if( $last > $file_num_total ) $last = $file_num_total;
	$filenavinfo = sprintf( _MD_ALBM_AM_FILENAVINFO , $pos + 1 , $last , $file_num_total );
	$xoopsTpl->assign( 'filenavdisp' , true );
	$xoopsTpl->assign( 'filenav' , $nav_html );
	$xoopsTpl->assign( 'filenavinfo' , $filenavinfo );
} else {
	$xoopsTpl->assign( 'filenavdisp' , false );
}



require_once './class/sortheader.php';
$sortheader = new SortHeader(5,$sortkey);
$xoopsTpl->assign( 'nowpos' , $pos );
$xoopsTpl->assign( 'sortheader' , $sortheader );

$files = $contentManager->getContentList(1,$lsize,$pos,$sortkey,$my_uid);
$xoopsTpl->assign( 'files' , $files );

include( XOOPS_ROOT_PATH . "/footer.php" );
?>