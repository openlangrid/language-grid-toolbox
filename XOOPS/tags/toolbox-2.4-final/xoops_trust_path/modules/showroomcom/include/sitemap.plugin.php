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

function b_sitemap_d3forum( $mydirname )
{
	$db =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$ret = array();

	include_once dirname(__FILE__).'/common_functions.php' ;

	$whr_forum = 'forum_id IN ('.implode(',',d3forum_get_forums_can_read( $mydirname )).')' ;

	$sql = "SELECT forum_id,forum_title FROM ".$db->prefix($mydirname."_forums")." WHERE ($whr_forum)" ;
	$result = $db->query($sql);

	while( list( $forum_id , $forum_title ) = $db->fetchRow( $result ) ) {
		$ret["parent"][] = array(
			"id" => intval( $forum_id ) ,
			"title" => $myts->makeTboxData4Show( $forum_title ) ,
			"url" => "index.php?forum_id=".intval( $forum_id ) ,
		) ;
	}

	return $ret;
}

?>