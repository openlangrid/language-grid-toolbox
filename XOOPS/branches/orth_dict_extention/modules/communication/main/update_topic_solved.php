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

$topic_id = intval( @$_GET['topic_id'] ) ;

// get&check this topic ($topic4assign, $topic_row, $forum_id), count topic_view up, get $prev_topic, $next_topic
include dirname(dirname(__FILE__)).'/include/process_this_topic.inc.php' ;

// get&check this forum ($forum4assign, $forum_row, $cat_id, $isadminormod), override options
if( ! include dirname(dirname(__FILE__)).'/include/process_this_forum.inc.php' ) die( _MD_D3FORUM_ERR_READFORUM ) ;

// get&check this category ($category4assign, $category_row), override options
if( ! include dirname(dirname(__FILE__)).'/include/process_this_category.inc.php' ) die( _MD_D3FORUM_ERR_READCATEGORY ) ;

// special check for update_topic_solved
if( ! $isadminormod ) die( _MD_D3FORUM_ERR_MODERATETOPIC ) ;

if( empty( $xoopsModuleConfig['use_solved'] ) ) {
	// force topic_solved=1 if "solved" is disable
	$db->queryF( "UPDATE ".$db->prefix($mydirname."_topics")." SET topic_solved=1 WHERE topic_id=$topic_id" ) ;
} else {
	// flip topic_solved
	$db->queryF( "UPDATE ".$db->prefix($mydirname."_topics")." SET topic_solved = ! topic_solved WHERE topic_id=$topic_id" ) ;
}

$allowed_identifiers = array( 'post_id' , 'topic_id' , 'forum_id' , 'cat_ids' ) ;

if( in_array( $_GET['ret_name'] , $allowed_identifiers ) ) {
	$ret_request = $_GET['ret_name'] . '=' . preg_replace( '/[^0-9,]/' , '' , $_GET['ret_val'] ) ;
} else {
	$ret_request = "topic_id=$topic_id" ;
}

redirect_header( XOOPS_URL."/modules/$mydirname/index.php?$ret_request" , 0 , _MD_D3FORUM_MSG_UPDATED ) ;
exit ;

?>