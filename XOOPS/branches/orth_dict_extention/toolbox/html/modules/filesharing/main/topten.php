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

include("header.php");
$myts =& MyTextSanitizer::getInstance() ; // MyTextSanitizer object
include_once( XOOPS_ROOT_PATH."/class/xoopstree.php" ) ;
$cattree = new XoopsTree( $table_cat , "cid" , "pid" ) ;

$xoopsOption['template_main'] = "filesharing_topten.html" ;

include( XOOPS_ROOT_PATH . "/header.php" ) ;

include( 'include/assign_globals.php' ) ;
$xoopsTpl->assign( $filesharing_assign_globals ) ;

//generates top 10 charts by rating and hits for each main category

if( ! empty( $_GET['rate'] ) && ( $global_perms & GPERM_RATEVIEW ) ) {
	$lang_sortby = _MD_ALBM_RATING;
	$odr = "rating DESC";
} else {
	$lang_sortby = _MD_ALBM_HITS;
	$odr = "hits DESC";
}

$xoopsTpl->assign( 'lang_sortby' , $lang_sortby ) ;
$xoopsTpl->assign( 'lang_rank' , _MD_ALBM_RANK ) ;
$xoopsTpl->assign( 'lang_title' , _MD_ALBM_FILENAME ) ;
$xoopsTpl->assign( 'lang_category' , _MD_ALBM_CATEGORY ) ;
$xoopsTpl->assign( 'lang_hits' , _MD_ALBM_HITS ) ;
$xoopsTpl->assign( 'lang_rating' , _MD_ALBM_RATING ) ;
$xoopsTpl->assign( 'lang_vote' , _MD_ALBM_VOTE ) ;

$crs = $xoopsDB->query( "SELECT cid,title FROM $table_cat WHERE pid=0 ORDER BY title" ) ;
$rankings = array() ;
$i = 0;
while( list( $cid , $cat_title ) = $xoopsDB->fetchRow( $crs ) ) {

	$rankings[$i] = array(
		'title' => sprintf( _MD_ALBM_TOP10 , $myts->htmlSpecialChars( $cat_title ) ) ,
		'count' => $i
	) ;

	// get all child cat ids for a given cat id
	$children = $cattree->getAllChildId( $cid ) ;
	$whr_cid = 'cid IN (' ;
	foreach( $children as $child ) {
		$whr_cid .= "$child," ;
	}
	$whr_cid .= "$cid)" ;

	$sql = "SELECT lid, cid, title, submitter, hits, rating, votes FROM $table_files WHERE status>0 AND ($whr_cid) ORDER BY $odr";
	$prs = $xoopsDB->query( $sql , 10 , 0 ) ;
	$rank = 1 ;
	while( list ( $lid , $cid , $title , $submitter , $hits , $rating , $votes ) = $xoopsDB->fetchRow( $prs ) ) {
		$catpath = $cattree->getPathFromId( $cid , "title" ) ;
		$catpath = substr( $catpath , 1 ) ;
		$catpath = str_replace( "/" , " <span class='fg2'>&raquo;&raquo;</span> " , $catpath ) ;
		$title = $myts->makeTboxData4Show( $title ) ;
		$rankings[$i]['file'][] = array( 'lid' => $lid , 'cid' => $cid , 'rank' => $rank , 'title' => $title , 'submitter' => $submitter , 'submitter_name' => filesharing_get_name_from_uid( $submitter ) , 'category' => $catpath , 'hits' => $hits , 'rating' => number_format( $rating , 2) , 'votes' => $votes ) ;
		$rank ++ ;
	}

	$i++ ;
}

$xoopsTpl->assign_by_ref( 'rankings' , $rankings ) ;

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>