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

include( "admin_header.php" ) ;

$myts =& MyTextSanitizer::getInstance() ;

// GPCS vars
$max_col = 4 ;
$cid = empty( $_GET[ 'cid' ] ) ? 0 : intval( $_GET[ 'cid' ] ) ;
$pos = empty( $_GET[ 'pos' ] ) ? 0 : intval( $_GET[ 'pos' ] ) ;
$num = empty( $_GET[ 'num' ] ) ? 20 : intval( $_GET[ 'num' ] ) ;
$txt = empty( $_GET[ 'txt' ] ) ? '' : $myts->stripSlashesGPC( trim( $_GET[ 'txt' ] ) ) ;


// Database actions
if( ! empty( $_POST['action'] ) && $_POST['action'] == 'delete' && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {

	// remove records

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	foreach( $_POST['ids'] as $lid ) {
		apfilesharing_delete_files( "lid=".intval( $lid ) ) ;
	}
	redirect_header( "filemanager.php?num=$num&cid=$cid" , 2 , _MD_ALBM_DELETINGFILE ) ;
	exit ;

} else if( isset( $_POST['update'] ) && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {

	// batch update

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	// set clause for text table
	if( ! empty( $_POST['new_desc_text'] ) ) {
		$set_for_text = "description='".$myts->makeTareaData4Save( $_POST['new_desc_text'] )."'" ;
	}

	// set clause for files table
	$set = '' ;

	// new_title
	if( ! empty( $_POST['new_title'] ) ) {
		$set .= "title='".$myts->makeTboxData4Save( $_POST['new_title'] )."'," ;
	}

	// new_cid
	if( ! empty( $_POST['new_cid'] ) ) {
		$set .= "cid='".intval( $_POST['new_cid'] )."'," ;
	}

	// new_submitter
	if( ! empty( $_POST['new_submitter'] ) ) {
		$set .= "submitter='".intval( $_POST['new_submitter'] )."'," ;
	}

	// new_post_date
	if( ! empty( $_POST['new_post_date'] ) ) {
		$new_date = strtotime( $_POST['new_post_date'] ) ;
		if( $new_date != -1 ) $set .= "date='$new_date'," ;
	}

	if( $set ) $set = substr( $set , 0 , -1 ) ;

	// $whr clause
	$whr = "lid IN (" ;
	foreach( $_POST[ 'ids' ] as $lid ) {
		$whr .= intval( $lid ) . ',' ;
	}
	$whr = substr( $whr , 0 , -1 ) . ')' ;

	if( $set ) $xoopsDB->query( "UPDATE $table_files SET $set WHERE $whr" ) ;
	if( ! empty( $set_for_text ) ) $xoopsDB->query( "UPDATE $table_text SET $set_for_text WHERE $whr" ) ;

	redirect_header( "filemanager.php?num=$num&cid=$cid" , 2 , _MD_ALBM_DBUPDATED ) ;
	exit ;

}


// make 'WHERE'
$whr = "1 " ;

// Limitation by category's id
if( $cid != 0 ) {
	$whr .= "AND l.cid=$cid " ;
}

// Search by free word
if( $txt != "" ) {
	$keywords = explode( " " , $txt ) ;
	foreach( $keywords as $keyword ) {
		$whr .= "AND (CONCAT( l.title , l.ext , c.title ) LIKE '%" . addslashes( $keyword ) . "%') " ;
	}
}

// Query
$rs = $xoopsDB->query( "SELECT count(l.lid) FROM $table_files l LEFT JOIN $table_folder c ON l.cid=c.cid WHERE $whr" ) ;
list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;
$prs = $xoopsDB->query( "SELECT l.lid, l.title, l.submitter, l.ext, l.res_x, l.res_y, l.status FROM $table_files l LEFT JOIN $table_folder c ON l.cid=c.cid WHERE $whr ORDER BY l.lid DESC LIMIT $pos,$num" ) ;

// Page Navigation
include XOOPS_ROOT_PATH.'/class/pagenav.php';
$nav = new XoopsPageNav( $numrows , $num , $pos , 'pos' , "num=$num&cid=$cid&txt=" . urlencode($txt) ) ;
$nav_html = $nav->renderNav( 10 ) ;

// Information of page navigating
$last = $pos + $num ;
if( $last > $numrows ) $last = $numrows ;
$filenavinfo = sprintf( _MD_ALBM_AM_FILENAVINFO , $pos + 1 , $last , $numrows ) ;

// Options for the number of files in a display
$numbers = explode( '|' , $apfilesharing_perpage ) ;
$num_options = '' ;
foreach( $numbers as $number ) {
	$number = intval( $number ) ;
	if( $number < 1 ) continue ;
	$selected = $number == $num ? "selected='selected'" : "" ;
	$num_options .= "<option value='$number' $selected>".sprintf(_MD_ALBM_FMT_FILENUM,$number)."</option>\n" ;
}

apfilesharing_get_cat_options() ;

// Options for Selecting a category
$cat_options = apfilesharing_get_cat_options( 'title' , $cid , '--' , '----' ) ;
$cat_options_for_update = apfilesharing_get_cat_options( 'title' , 0 , '--' , _AM_OPT_NOCHANGE ) ;

// Options for Selecting a user
$user_options = "<option value='0'>"._AM_OPT_NOCHANGE."</option>\n" ;
$urs = $xoopsDB->query( "SELECT uid,uname FROM ".$xoopsDB->prefix("users")." ORDER BY uname" ) ;
while( list( $uid , $uname ) = $xoopsDB->fetchRow( $urs ) ) {
	$user_options .= "<option value='$uid'>".htmlspecialchars($uname,ENT_QUOTES)."</option>\n" ;
}


// Start of outputting
xoops_cp_header();
include( './mymenu.php' ) ;

// check $xoopsModule
if( ! is_object( $xoopsModule ) ) redirect_header( "$mod_url/" , 1 , _NOPERM ) ;
echo "<h3 style='text-align:left;'>".sprintf(_AM_H3_FMT_FILEMANAGER,$xoopsModule->name())."</h3>\n" ;

apfilesharing_opentable() ;

echo "
<p><font color='blue'>".(isset($_GET['mes'])?$_GET['mes']:"")."</font></p>
<form action='' method='GET' style='margin-bottom:0px;'>
  <table border='0' cellpadding='0' cellspacing='0' style='width:100%;'>
    <tr>
      <td align='left'>
        <select name='num' onchange='submit();'>
          $num_options
        </select>
        <select name='cid' onchange='submit();'>
          $cat_options
        </select>
        <input type='text' name='txt' value='".htmlspecialchars($txt,ENT_QUOTES)."'>
        <input type='submit' value='"._MD_ALBM_AM_BUTTON_EXTRACT."'> &nbsp;
      </td>
      <td align='right'>
        $nav_html &nbsp;
      </td>
    </tr>
  </table>
</form>
<p align='center' style='margin:0px;'>
  $filenavinfo
<!--  <a href='../?page=submit&cid=$cid'><img src='../images/pictadd.gif' width='18' height='15' alt='"._AM_CAT_LINK_ADDFILES."' title='"._AM_CAT_LINK_ADDFILES."' /></a>-->
</p>
<form name='MainForm' action='?num=$num&cid=$cid' method='POST' style='margin-top:0px;'>
".$xoopsGTicket->getTicketHtml( __LINE__ )."
<table width='100%' border='0' cellspacing='0' cellpadding='4'>
<tr>
<td align='center' colspan='2'>
	<table style='width:100%;border:none;margin:10px 0px;text-align:left;'>
" ;

// list part
while( list( $lid , $title , $submitter , $ext , $w , $h , $status ) = $xoopsDB->fetchRow( $prs ) ) {
	$title = $myts->makeTboxData4Show( $title ) ;

	if( in_array( strtolower( $ext ) , $apfilesharing_normal_exts ) ) {
		$imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
		$ahref_file = "$files_url/$lid.$ext" ;
		$widthheight = $w > $h ? "width='$apfilesharing_thumbsize'" : "height='$apfilesharing_thumbsize'" ;
	} else {
		$imgsrc_thumb = "$thumbs_url/$lid.gif" ;
		$ahref_file = "$files_url/$lid.$ext" ;
		$widthheight = '' ;
	}

	$bgcolor = $status ? "#FFFFFF" : "#FFEEEE" ;

	$editbutton = "<a href='".XOOPS_URL."/modules/$mydirname/?page=editfile&lid=$lid' target='_blank'><img src='".XOOPS_URL."/modules/$mydirname/images/editicon2.gif' border='0' alt='"._MD_ALBM_EDITTHISFILE."' title='"._MD_ALBM_EDITTHISFILE."' /></a>  ";
	$deadlinkbutton = is_readable( "$files_dir/{$lid}.{$ext}" ) ? "" : "<img src='".XOOPS_URL."/modules/$mydirname/images/deadlink.gif' border='0' alt='"._MD_ALBM_AM_DEADLINKMAINFILE."' title='"._MD_ALBM_AM_DEADLINKMAINFILE."' />" ;

	$line = "";
	$line .= "<tr><td style='border:1px solid #6766A2;padding:3px;'>";
	$line .= "<label>";
	$line .= "<input type='checkbox' name='ids[]' value='".$lid."' style='border:none;'>";
	$line .= "<span style='font-size:10pt;'>".$title."</span>";
	$line .= "</label>";
	$line .= "&nbsp;&nbsp;&nbsp;&nbsp;";
	//$line .= $editbutton." ";
	$line .= $deadlinkbutton." ";
	$line .= "</td></tr>\n";
	echo $line;
	/*
	echo "
		<td align='center' style='background-color:$bgcolor; margin: 0 px; padding: 3 px; border-width:0px 2px 2px 0px; border-style: solid; border-color:black;'>
			<table border='0' cellpadding='0' cellmargin='0'>
				<tr>
					<td></td>
					<td><img src='../images/pixel_trans.gif' width='$apfilesharing_thumbsize' height='1' alt='' /></td>
					<td></td>
				</tr>
				<tr>
					<td><img src='../images/pixel_trans.gif' width='1' height='$apfilesharing_thumbsize' alt='' /></td>
					<td align='center'><a href='$ahref_file' target='_blank'><img src='$imgsrc_thumb' $widthheight border='0' alt='$title' title='$title' /></a></td>
					<td><img src='../images/pixel_trans.gif' width='1' height='$apfilesharing_thumbsize' alt='' /></td>
				</tr>
				<tr>
					<td></td>
					<td align='center'>$editbutton $deadlinkbutton <span style='font-size:10pt;'>$title <input type='checkbox' name='ids[]' value='$lid' style='border:none;'></span></td>
					<td></td>
				</tr>
			</table>

		</td>
	\n" ;
	*/
	//if( ++ $col >= $max_col ) { echo "\t</tr>\n" ; $col = 0 ; }

}

echo "
	</table>
</td>
</tr>
<tr>
	<td align='left'>
		<input type='button' value='"._MD_ALBM_BTN_SELECTNONE."' onclick=\"with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=false;}}}\" />
		&nbsp;
		<input type='button' value='"._MD_ALBM_BTN_SELECTALL."' onclick=\"with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=true;}}}\" />
	</td>
	<td align='right'>
		<input type='hidden' name='action' value='' />
		"._MD_ALBM_AM_LABEL_REMOVE."<input type='button' value='"._MD_ALBM_AM_BUTTON_REMOVE."' onclick='if(confirm(\""._MD_ALBM_AM_JS_REMOVECONFIRM."\")){document.MainForm.action.value=\"delete\"; submit();}' />
	</td>
</tr>
</table>
<br />
<table class='outer' style='width:100%;'>
	<tr>
		<th colspan='2'>"._AM_TH_BATCHUPDATE."</th>
	</tr>
";
//	<tr>
//		<td class='head'>"._AM_TH_TITLE."</td>
//		<td class='even'><input type='text' name='new_title' size='50' /></td>
//	</tr>
//	<tr valign='top'>
//		<td class='head'>"._AM_TH_DESCRIPTION."</td>
//		<td class='even'><textarea name='new_desc_text' cols='50' rows='5'></textarea></td>
//	</tr>

echo "
	<tr valign='top'>
		<td class='head'>"._AM_TH_DESCRIPTION."</td>
		<td class='even'><input type='text' name='new_desc_text' size='50' /></td>
	</tr>
	<tr>
		<td class='head'>"._AM_TH_CATEGORIES."</td>
		<td class='even'>
			<select name='new_cid'>
				$cat_options_for_update
			</select>
		</td>
	</tr>
	<tr>
		<td class='head'>"._AM_TH_SUBMITTER."</td>
		<td class='even'>
			<select name='new_submitter'>
				$user_options
			</select>
		</td>
	</tr>
	<tr valign='top'>
		<td class='head'>"._AM_TH_DATE."</td>
		<td class='even'><input type='text' name='new_post_date' size='20' value='".formatTimestamp(time(),_MD_ALBM_DTFMT_YMDHI)."'></textarea></td>
	</tr>
	<tr>
		<td class='head'></td>
		<td class='even'><input type='submit' name='update' value='"._MD_ALBM_AM_BUTTON_UPDATE."' onclick='return confirm(\""._AM_JS_UPDATECONFIRM."\")' tabindex='1' /></td>
	</tr>
</table>
</form>
" ;


apfilesharing_closetable() ;
xoops_cp_footer();
?>
