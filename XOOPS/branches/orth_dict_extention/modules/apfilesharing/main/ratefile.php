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
$myts =& MyTextSanitizer::getInstance() ;

if( ! ( $global_perms & GPERM_RATEVOTE ) ) {
	redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
	exit ;
}

$lid = empty( $_GET['lid'] ) ? 0 : intval( $_GET['lid'] ) ;

if( ! empty( $_POST['submit'] ) ) {

	$ratinguser = $my_uid ;

	//Make sure only 1 anonymous from an IP in a single day.
	$anonwaitdays = 1 ;
	$ip = getenv( "REMOTE_ADDR" ) ;
	$lid = intval( $_POST['lid'] ) ;
	$rating = intval( $_POST['rating'] ) ;
	// Check if rating is valid
	if( $rating <= 0 || $rating > 10 ) {
		redirect_header( "rate?page=file&lid=$lid" , 4 , _MD_ALBM_NORATING ) ;
		exit ;
	}

	if( $ratinguser != 0 ) {

		// Check if File POSTER is voting
		$rs = $xoopsDB->query( "SELECT COUNT(*) FROM $table_files WHERE lid=$lid AND submitter=$ratinguser" ) ;
		list( $is_my_file ) = $xoopsDB->fetchRow( $rs ) ;
		if( $is_my_file ) {
			redirect_header( "index.php" , 4 , _MD_ALBM_CANTVOTEOWN ) ;
			exit ;
		}

		// Check if REG user is trying to vote twice.
		$rs = $xoopsDB->query( "SELECT COUNT(*) FROM $table_votedata WHERE lid=$lid AND ratinguser=$ratinguser" ) ;
		list( $has_already_rated ) = $xoopsDB->fetchRow( $rs ) ;
		if( $has_already_rated ) {
			redirect_header( "index.php" , 4 , _MD_ALBM_VOTEONCE2 ) ;
			exit ;
		}

	} else {
		// Check if ANONYMOUS user is trying to vote more than once per day.
		$yesterday = ( time() - (86400 * $anonwaitdays ) ) ;
		$rs = $xoopsDB->query( "SELECT COUNT(*) FROM $table_votedata WHERE lid=$lid AND ratinguser=0 AND ratinghostname='$ip' AND ratingtimestamp > $yesterday");
		list( $anonvotecount ) = $xoopsDB->fetchRow( $rs ) ;
		if( $anonvotecount ) {
			redirect_header( "index.php" , 4 , _MD_ALBM_VOTEONCE2 ) ;
			exit ;
		}
	}

	// All is well.  Add to Line Item Rate to DB.
	$newid = $xoopsDB->genId( $table_votedata . "_ratingid_seq" ) ;
	$datetime = time() ;
	$xoopsDB->query( "INSERT INTO $table_votedata (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $rating, '$ip', $datetime)") or die( "DB error: INSERT votedata table" ) ;

	//All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
	apfilesharing_updaterating( $lid ) ;
	$ratemessage = _MD_ALBM_VOTEAPPRE."<br />".sprintf( _MD_ALBM_THANKURATE , $xoopsConfig['sitename'] ) ;
	if( ! empty( $_SESSION["{$mydirname}_uri4return"] ) ) {
		redirect_header( $_SESSION["{$mydirname}_uri4return"] , 2 , $ratemessage ) ;
		unset( $_SESSION["{$mydirname}_uri4return"] ) ;
	} else {
		redirect_header( "index.php" , 2 , $ratemessage ) ;
	}
	exit ;

} else {

	$xoopsOption['template_main'] = "apfilesharing_ratefile.html" ;
	include( XOOPS_ROOT_PATH."/header.php" ) ;

	// store the referer
	if( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		$_SESSION["{$mydirname}_uri4return"] = $_SERVER['HTTP_REFERER'] ;
	}

	$rs = $xoopsDB->query( "SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description FROM $table_files l LEFT JOIN $table_text t ON l.lid=t.lid  WHERE l.lid='$lid' AND l.status>0") ;
	if( $rs == false || $xoopsDB->getRowsNum( $rs ) <= 0 ) {
		redirect_header( "index.php" , 2 , _MD_ALBM_NOMATCH ) ;
		exit ;
	}

	$fetched_result_array = $xoopsDB->fetchArray( $rs ) ;
	$xoopsTpl->assign( 'file' , apfilesharing_get_array_for_file_assign( $fetched_result_array ) ) ;

	include( 'include/assign_globals.php' ) ;
	$xoopsTpl->assign( $apfilesharing_assign_globals ) ;

	$xoopsTpl->assign( 'lang_voteonce' , _MD_ALBM_VOTEONCE ) ;
	$xoopsTpl->assign( 'lang_ratingscale' , _MD_ALBM_RATINGSCALE ) ;
	$xoopsTpl->assign( 'lang_beobjective' , _MD_ALBM_BEOBJECTIVE ) ;
	$xoopsTpl->assign( 'lang_donotvote' , _MD_ALBM_DONOTVOTE ) ;
	$xoopsTpl->assign( 'lang_rateit' , _MD_ALBM_RATEIT ) ;
	$xoopsTpl->assign( 'lang_cancel' , _CANCEL ) ;

	include( XOOPS_ROOT_PATH . "/footer.php" ) ;

}
?>