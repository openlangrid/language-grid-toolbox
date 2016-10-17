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
include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
$cattree = new XoopsTree( $table_folder , "cid" , "pid" ) ;

// GET variables
$lid = empty( $_GET['lid'] ) ? 0 : intval( $_GET['lid'] ) ;
$cid = empty( $_GET['cid'] ) ? 0 : intval( $_GET['cid'] ) ;

$xoopsOption['template_main'] = "apfilesharing_file.html" ;

include XOOPS_ROOT_PATH . "/header.php" ;

if( $global_perms & GPERM_INSERTABLE ) $xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE ) ;
$xoopsTpl->assign( 'lang_album_main' , _MD_ALBM_MAIN ) ;
include( 'include/assign_globals.php' ) ;
$xoopsTpl->assign( $apfilesharing_assign_globals ) ;

// update hit count
$xoopsDB->queryF( "UPDATE $table_files SET hits=hits+1 WHERE lid='$lid' AND status>0" ) ;

$prs = $xoopsDB->query( "SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description FROM $table_files l LEFT JOIN $table_text t ON l.lid=t.lid WHERE l.lid=$lid AND status>0" ) ;
$p = $xoopsDB->fetchArray( $prs ) ;
if( $p == false ) {
	redirect_header( $mod_url.'/' , 3 , _MD_ALBM_NOMATCH ) ;
	exit ;
}
$file = apfilesharing_get_array_for_file_assign( $p ) ;

// <title></title>
$xoopsTpl->assign( 'xoops_pagetitle' , $file['title'] ) ;

// Middle size calculation
$file['width_height'] = '' ;
list( $max_w , $max_h ) = explode( 'x' , $apfilesharing_middlepixel ) ;
if( ! empty( $max_w ) && ! empty( $p['res_x'] ) ) {
	if( empty( $max_h ) ) $max_h = $max_w ;
	if( $max_h / $max_w > $p['res_y'] / $p['res_x'] ) {
		if( $p['res_x'] > $max_w ) $file['width_height'] = "width='$max_w'" ;
	} else {
		if( $p['res_y'] > $max_h ) $file['width_height'] = "height='$max_h'" ;
	}
}
$xoopsTpl->assign_by_ref( 'file' , $file ) ;

// Category Information
$cid = empty( $p['cid'] ) ? $cid : $p['cid'] ;
$xoopsTpl->assign( 'category_id' , $cid ) ;
$cids = $cattree->getAllChildId( $cid ) ;
$sub_title = preg_replace( "/\'\>/" , "'><img src='$mod_url/images/folder16.gif' alt='' />" , $cattree->getNicePathFromId( $cid , 'title' , "?page=viewcat&num=" . intval( $apfilesharing_perpage )  ) ) ;
$sub_title = preg_replace( "/^(.+)folder16/" , '$1folder_open' , $sub_title ) ;
$xoopsTpl->assign( 'album_sub_title' , $sub_title ) ;

// Orders
include XOOPS_ROOT_PATH."/modules/$mydirname/include/file_orders.php" ;
if( isset( $_GET['orderby'] ) && isset( $apfilesharing_orders[ $_GET['orderby'] ] ) ) $orderby = $_GET['orderby'] ;
else if( isset( $apfilesharing_orders[ $apfilesharing_dorder ] ) ) $orderby = $apfilesharing_dorder ;
else $orderby = 'lidA' ;

// create category navigation
$fullcountresult = $xoopsDB->query( "SELECT lid FROM $table_files WHERE cid=$cid AND status>0 ORDER BY {$apfilesharing_orders[$orderby][0]}" ) ;
$ids = array() ;
while( list( $id ) = $xoopsDB->fetchRow( $fullcountresult ) ) {
	$ids[] = $id ;
}

$file_nav = "" ;
$numrows = count( $ids ) ;
$pos = array_search( $lid , $ids ) ;
if( $numrows > 1 ) {
	// prev mark
	if( $ids[0] != $lid ) {
		$file_nav .= "<a href='?page=file&lid=".$ids[0]."'><b>[&lt; </b></a>&nbsp;&nbsp;";
		$file_nav .= "<a href='?page=file&lid=".$ids[$pos-1]."'><b>"._MD_ALBM_PREVIOUS."</b></a>&nbsp;&nbsp;";
	    
	}

	$nwin = 7 ;
	if( $numrows > $nwin ) { // window
		if( $pos > $nwin / 2 ) {
			if( $pos > round( $numrows - ( $nwin / 2 ) - 1 ) ) {
				$start = $numrows - $nwin + 1 ;
			} else {
				$start = round( $pos - ( $nwin / 2 ) ) + 1 ;
			}
		} else {
			$start = 1 ;
		}
	} else {
		$start = 1 ;
	}
	
	for( $i = $start; $i < $numrows + 1 && $i < $start + $nwin ; $i++ ) {
		if( $ids[$i-1] == $lid ) {
			$file_nav .= "$i&nbsp;&nbsp;";
		} else {
			$file_nav .= "<a href='?page=file&lid=".$ids[$i-1]."'>$i</a>&nbsp;&nbsp;";
		}
	}

	// next mark
	if( $ids[$numrows-1] != $lid ) {
		$file_nav .= "<a href='?page=file&lid=".$ids[$pos+1]."'><b>"._MD_ALBM_NEXT."</b></a>&nbsp;&nbsp;" ;
		$file_nav .= "<a href='?page=file&lid=".$ids[$numrows-1]."'><b> &gt;]</b></a>" ;
	}
}

$xoopsTpl->assign( 'file_nav' , $file_nav ) ;

// comments

include XOOPS_ROOT_PATH.'/include/comment_view.php';

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>