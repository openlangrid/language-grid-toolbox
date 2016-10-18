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

include "admin_header.php" ;
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php' ;
include_once XOOPS_ROOT_PATH.'/class/pagenav.php' ;
include_once '../class/apfilesharing.textsanitizer.php' ;


// initialization of Xoops vars
$cattree = new XoopsTree( $table_folder , "cid" , "pid" ) ;
$myts =& MyAlbumTextSanitizer::getInstance() ;


// GET vars
$pos = empty( $_GET[ 'pos' ] ) ? 0 : intval( $_GET[ 'pos' ] ) ;
$num = empty( $_GET[ 'num' ] ) ? 20 : intval( $_GET[ 'num' ] ) ;
$txt = empty( $_GET[ 'txt' ] ) ? '' : $myts->stripSlashesGPC( trim( $_GET[ 'txt' ] ) ) ;


if( ! empty( $_POST['action'] ) && $_POST['action'] == 'admit' && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {

	// Do admission
	$whr = "" ;
	foreach( $_POST[ 'ids' ] as $id ) {
		$id = intval( $id ) ;
		$whr .= "lid=$id OR " ;
	}
	$xoopsDB->query( "UPDATE $table_files SET status=1 WHERE $whr 0" ) ;

	// Trigger Notification
	$notification_handler =& xoops_gethandler( 'notification' ) ;
	$rs = $xoopsDB->query( "SELECT l.lid,l.cid,l.submitter,l.title,c.title FROM $table_files l LEFT JOIN $table_folder c ON l.cid=c.cid WHERE $whr 0" ) ;
	while( list( $lid , $cid , $submitter , $title , $cat_title ) = $xoopsDB->fetchRow( $rs ) ) {
		// Global Notification
		$notification_handler->triggerEvent( 'global' , 0 , 'new_file' , array( 'FILE_TITLE' => $title , 'FILE_URI' => "$mod_url/?page=file&lid=$lid&cid=$cid" ) ) ;

		// Category Notification
		$notification_handler->triggerEvent( 'category' , $cid , 'new_file' , array( 'FILE_TITLE' => $title , 'CATEGORY_TITLE' => $cat_title , 'FILE_URI' => "$mod_url/?page=file&lid=$lid&cid=$cid" ) ) ;
	}

	redirect_header( 'admission.php' , 2 , _MD_ALBM_AM_ADMITTING ) ;
	exit ;

} else if( ! empty( $_POST['action'] ) && $_POST['action'] == 'delete' && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {

	// remove records

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	foreach( $_POST['ids'] as $lid ) {
		apfilesharing_delete_files( "lid=".intval( $lid ) ) ;
	}
	redirect_header( "admission.php" , 2 , _MD_ALBM_DELETINGFILE ) ;
	exit ;
}


// extracting by free word
$whr = "l.status<=0 " ;
if( $txt != "" ) {
	$keywords = explode( " " , $txt ) ;
	foreach( $keywords as $keyword ) {
		$whr .= "AND (CONCAT( l.title , l.ext , c.title ) LIKE '%" . addslashes( $keyword ) . "%') " ;
	}
}

// query for listing
$rs = $xoopsDB->query( "SELECT count(l.lid) FROM $table_files l LEFT JOIN $table_folder c ON l.cid=c.cid WHERE $whr" ) ;
list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;
$prs = $xoopsDB->query( "SELECT l.lid, l.cid, l.title, l.submitter, l.ext, t.description FROM $table_files l LEFT JOIN $table_folder c ON l.cid=c.cid LEFT JOIN $table_text t ON l.lid=t.lid WHERE $whr ORDER BY l.lid DESC LIMIT $pos,$num" ) ;

// Page Navigation
$nav = new XoopsPageNav( $numrows , $num , $pos , 'pos' , "num=$num&txt=" . urlencode($txt) ) ;
$nav_html = $nav->renderNav( 10 ) ;


// beggining of Output
xoops_cp_header();
include( './mymenu.php' ) ;

// check $xoopsModule
if( ! is_object( $xoopsModule ) ) redirect_header( XOOPS_URL.'/user.php' , 1 , _NOPERM ) ;
echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_ADMISSION,$xoopsModule->name())."</h3>\n" ;

echo "
<p><font color='blue'>".(isset($_GET['mes'])?$_GET['mes']:"")."</font></p>
<table width='95%' border='0' cellpadding='4' cellspacing='0'><tr><td>
<form action='' method='GET' style='margin-bottom:0px;text-align:right'>
  <input type='hidden' name='num' value='$num'>
  <input type='text' name='txt' value='".htmlspecialchars($txt,ENT_QUOTES)."'>
  <input type='submit' value='"._MD_ALBM_AM_BUTTON_EXTRACT."' /> &nbsp; 
  $nav_html &nbsp; 
</form>
<form name='MainForm' action='' method='POST' style='margin-top:0px;'>
".$xoopsGTicket->getTicketHtml( __LINE__ )."
<input type='hidden' name='action' value='' />
<table width='95%' class='outer' cellpadding='4' cellspacing='1'>
  <tr valign='middle'>
    <th width='5'><input type='checkbox' name='dummy' onclick=\"with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=this.checked;}}}\" /></th>
    <th></th>
    <th>"._AM_TH_SUBMITTER."</th>
    <th>"._AM_TH_TITLE."</th>
    <th>"._AM_TH_DESCRIPTION."</th>
    <th>"._AM_TH_CATEGORIES."</th>
  </tr>
" ;

// Listing
$oddeven = 'odd' ;
while( list( $lid , $cid , $title , $submitter , $ext , $description ) = $xoopsDB->fetchRow( $prs ) ) {
	$oddeven = ( $oddeven == 'odd' ? 'even' : 'odd' ) ;
	$title = $myts->makeTboxData4Show( $title ) ;
	$description = $myts->displayTarea( $description , 0 , 1 , 1 , 0 , 1 , 1 ) ;
	$cat = $cattree->getNicePathFromId( $cid , "title", "../?page=viewcat&" ) ;
	$editbutton = "<a href='".XOOPS_URL."/modules/$mydirname/?page=editfile&lid=$lid' target='_blank'><img src='".XOOPS_URL."/modules/$mydirname/images/editicon2.gif' border='0' alt='"._MD_ALBM_EDITTHISFILE."' title='"._MD_ALBM_EDITTHISFILE."' /></a>  ";

	echo "
  <tr>
    <td class='$oddeven'><input type='checkbox' name='ids[]' value='$lid' /></td>
    <td class='$oddeven'>$editbutton</td>
    <td class='$oddeven'>".$xoopsUser->getUnameFromId($submitter)."</td>
    <td class='$oddeven'><a href='$files_url/{$lid}.{$ext}' target='_blank'>$title</a></td>
    <td class='$oddeven' width='100%'>$description</td>
    <td class='$oddeven'>$cat</td>
  </tr>\n" ;
}

echo "
  <tr>
    <!-- <td colspan='4' align='left'>"._MD_ALBM_AM_LABEL_ADMIT."<input type='submit' name='admit' value='"._MD_ALBM_AM_BUTTON_ADMIT."' /></td> -->
    <td colspan='8' align='left'>"._MD_ALBM_AM_LABEL_ADMIT."<input type='button' value='"._MD_ALBM_AM_BUTTON_ADMIT."' onclick='document.MainForm.action.value=\"admit\"; submit();' /></td>
  </tr>
  <tr>
    <td colspan='8' align='left'>"._MD_ALBM_AM_LABEL_REMOVE."<input type='button' value='"._MD_ALBM_AM_BUTTON_REMOVE."' onclick='if(confirm(\""._MD_ALBM_AM_JS_REMOVECONFIRM."\")){document.MainForm.action.value=\"delete\"; submit();}' /></td>
  </tr>
</table>
</form>
</td></tr></table>
" ;

xoops_cp_footer();
?>
