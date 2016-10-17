<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

include dirname(dirname(__FILE__)).'/include/common_prepend.php' ;

// get cookie path
$xoops_cookie_path = defined('XOOPS_COOKIE_PATH') ? XOOPS_COOKIE_PATH : preg_replace( '?http://[^/]+(/.*)$?' , "$1" , XOOPS_URL ) ;
if( $xoops_cookie_path == XOOPS_URL ) $xoops_cookie_path = '/' ;

// update cookie
setcookie( $mydirname.'_postorder' , intval( $_GET['postorder'] ) , time() + 86400 * 30 , $xoops_cookie_path ) ;

$allowed_identifiers = array( 'post_id' , 'topic_id' , 'forum_id' ) ;

if( in_array( $_GET['ret_name'] , $allowed_identifiers ) ) {
	$ret_request = $_GET['ret_name'] . '=' . intval( $_GET['ret_val'] ) ;
} else {
	$ret_request = "topic_id=$topic_id" ;
}

header( "Location: ".XOOPS_URL."/modules/$mydirname/index.php?$ret_request" ) ;
exit ;

?>