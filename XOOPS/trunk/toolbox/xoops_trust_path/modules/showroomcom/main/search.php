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

if( ! isset( $_GET['submit'] ) ) {

	$results4assign = array() ;

} else {

	if( ! empty( $_GET['keyword'] ) ) {
		if( @$_GET['andor'] == 'or' ) {
			$andor4sql = '|| ' ;
			$andor_selected = 'or' ;
		} else {
			$andor4sql = '&& ' ;
			$andor_selected = 'and' ;
		}

		$keyword = $myts->stripSlashesGPC( $_GET['keyword'] ) ;
		$keyword4disp = htmlspecialchars( $keyword , ENT_QUOTES ) ;
		if( defined( '_MD_D3FORUM_MULTIBYTESPACES' ) ) {
			$keyword = str_replace( explode( ',' , _MD_D3FORUM_MULTIBYTESPACES ) , ' ' , $keyword ) ;
		}
		$words = explode( ' ' , $keyword ) ;
		$whr_keyword = '' ;
		foreach( $words as $word ) {
			$word4sql = addslashes( $word ) ;
			switch( @$_GET['target'] ) {
				default:
				case 'both':
					$whr_keyword .= " (p.subject LIKE '%$word4sql%' OR p.post_text LIKE '%$word4sql%') $andor4sql";
					$target_selected = 'both' ;
					break;
				case 'subject':
					$whr_keyword .= " (p.subject LIKE '%$word4sql%') $andor4sql";
					$target_selected = 'subject' ;
					break;
				case 'body':
					$whr_keyword .= " (p.post_text LIKE '%$word4sql%') $andor4sql";
					$target_selected = 'body' ;
					break;
			}
		}
		$whr_keyword = substr( $whr_keyword , 0 , -3 ) ;
	} else {
		$whr_keyword = '1' ;
		$keyword4disp = '' ;
	}

	// forum_id
	$forum_id = intval( @$_GET['forum_id'] ) ;
	if( ! empty( $forum_id ) ) {
		$whr_forum = "f.forum_id=$forum_id" ;
	} else {
		$whr_forum = '1' ;
	}

	// uname
	if( ! empty( $_GET['search_username'] ) ) {
		$uname = $myts->stripSlashesGPC( $_GET['search_username'] ) ;
		$uname4disp = htmlspecialchars( $uname , ENT_QUOTES ) ;
		$uname4sql = addslashes( $uname ) ;
		$whr_uname = "u.uname='$uname4sql'" ;
	} else {
		$whr_uname = '1' ;
		$uname4disp = '' ;
	}

	$allowed_sortbys = array(
		"p.uid" ,
		"p.uid desc" ,
		"p.post_time" ,
		"p.post_time desc" ,
		"t.topic_title" ,
		"t.topic_title desc" ,
		"t.topic_views" ,
		"t.topic_views desc" ,
		"t.topic_sticky" ,
		"t.topic_sticky desc" ,
		"t.topic_locked" ,
		"t.topic_locked desc" ,
		"t.topic_solved" ,
		"t.topic_solved desc" ,
		"t.topic_posts_count" ,
		"t.topic_posts_count desc" ,
		"f.forum_id",
		"f.forum_id desc",
		"f.forum_title",
		"f.forum_title desc",
		"c.cat_id",
		"c.cat_id desc",
		"c.cat_title",
		"c.cat_title desc",
		"u.uname" ,
		"u.uname desc" ,
	) ;
	$sortby = in_array( @$_GET['sortby'] , $allowed_sortbys ) ? $_GET['sortby'] : "p.post_time desc" ;

	$sql = 'SELECT u.uid,u.uname,p.post_id,p.subject,p.post_time,p.icon,LENGTH(p.post_text) AS body_length,p.votes_count,p.votes_sum,t.topic_id,t.topic_title,t.topic_views,t.topic_posts_count,f.forum_id,f.forum_title,c.cat_id,c.cat_title FROM '.$db->prefix($mydirname.'_posts').' p LEFT JOIN '.$db->prefix('users').' u ON p.uid=u.uid LEFT JOIN '.$db->prefix($mydirname.'_topics').' t ON p.topic_id = t.topic_id LEFT JOIN '.$db->prefix($mydirname.'_forums').' f ON t.forum_id = f.forum_id LEFT JOIN '.$db->prefix($mydirname.'_categories')." c ON f.cat_id = c.cat_id WHERE ($whr_keyword) AND ($whr_forum) AND ($whr_uname) AND ($whr_read4forum) AND ($whr_read4cat) ORDER BY $sortby" ;

	// TODO :-)
	if( ! $result = $db->query( $sql , 100 , 0 ) ) {
		die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
	}
	$results4assign = array() ;
	$hits_count = $db->getRowsNum( $result ) ;
	while ( $row = $db->fetchArray( $result ) ) {
		$results4assign[] = array(
			'forum_title' => $myts->makeTboxData4Show($row['cat_title']) ,
			'forum_id' => intval( $row['cat_id'] ) ,
			'forum_title' => $myts->makeTboxData4Show($row['forum_title']) ,
			'forum_id' => intval( $row['forum_id'] ) ,
			'topic_title' => $myts->makeTboxData4Show($row['topic_title']) ,
			'topic_id' => intval( $row['topic_id'] ) ,
			'topic_replies' => $row['topic_posts_count'] - 1 ,
			'topic_views' => intval( $row['topic_views'] ) ,
			'post_id' => intval( $row['post_id'] ) ,
			'subject' => $myts->makeTboxData4Show( $row['subject'] ) ,
			'icon' => intval( $row['icon'] ) ,
			'body_length' => intval( $row['body_length'] ) ,
			'poster_uid' => intval( $row['uid'] ) ,
			'poster_uname' => $myts->makeTboxData4Show($row['uname']),
			'post_time' => intval( $row['post_time'] ) ,
			'post_time_formatted' => formatTimestamp( $row['post_time'] , 'm' ) ,
			'votes_avg' => $row['votes_count'] ? $row['votes_sum'] / (double)$row['votes_count'] : 0 ,
		) + $row ;
	}

}

$xoopsOption['template_main'] = $mydirname.'_main_search.html' ;
include XOOPS_ROOT_PATH."/header.php" ;

$xoopsTpl->assign(
	array(
		'mydirname' => $mydirname ,
		'mod_url' => XOOPS_URL.'/modules/'.$mydirname ,
		'mod_imageurl' => XOOPS_URL.'/modules/'.$mydirname.'/'.$xoopsModuleConfig['images_dir'] ,
		'mod_config' => $xoopsModuleConfig ,
		'hits_count' => intval( @$hits_count ) ,
		'keyword' => @$keyword4disp ,
		'andor_options' => array( 'or' => _MD_D3FORUM_LABEL_SEARCHOR , 'and' => _MD_D3FORUM_LABEL_SEARCHAND ) ,
		'andor_selected' => empty( $andor_selected ) ? 'or' : $andor_selected ,
		'target_options' => array( 'subject' => _MD_D3FORUM_SUBJECT , 'body' => _MD_D3FORUM_BODY , 'both' => _MD_D3FORUM_LABEL_TARGETBOTH ) ,
		'target_selected' => empty( $target_selected ) ? 'both' : $target_selected ,
		'sortby_options' => array(
			'p.post_time desc' => _MD_D3FORUM_ON ,
			't.topic_title' => _MD_D3FORUM_TOPICTITLE ,
			'f.forum_id' => _MD_D3FORUM_FORUM ,
			'u.uname' => _MD_D3FORUM_POSTER ,
		) ,
		'sortby_selected' => empty( $sortby_selected ) ? 'p.post_time desc' : $sortby_selected ,
		'uname' => @$uname4disp ,
		'show_results' => ! empty( $_GET['submit'] ) ? true : false ,
		'results' => $results4assign ,
		'forum_jumpbox_options' => d3forum_make_jumpbox_options( $mydirname , $whr_read4cat , $whr_read4forum , @$forum_id ) ,
		'xoops_module_header' => "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".str_replace('{mod_url}',XOOPS_URL.'/modules/'.$mydirname,$xoopsModuleConfig['css_uri'])."\" />" . $xoopsTpl->get_template_vars( "xoops_module_header" ) ,
		'xoops_pagetitle' => _MD_D3FORUM_TITLE_SEARCH ,
		'xoops_breadcrumbs' => array_merge( $xoops_breadcrumbs , array( array( 'name' => _MD_D3FORUM_TITLE_SEARCH ) ) ) ,
	)
) ;

include XOOPS_ROOT_PATH.'/footer.php';

?>