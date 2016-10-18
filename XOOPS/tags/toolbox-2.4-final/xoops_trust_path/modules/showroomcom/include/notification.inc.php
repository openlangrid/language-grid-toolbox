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

function d3forum_notify_iteminfo( $mydirname , $category , $item_id )
{
	$db =& Database::getInstance() ;

	$module_handler =& xoops_gethandler( 'module' ) ;
	$module =& $module_handler->getByDirname( $mydirname ) ;

	if( $category == 'global' ) {
		$item['name'] = '';
		$item['url'] = '';
		return $item ;
	}

	if( $category == 'category' ) {
		// Assume we have a valid cat_id
		$sql = 'SELECT cat_title FROM ' . $db->prefix($mydirname.'_categories') . ' WHERE cat_id='.$item_id ;
		$result = $db->query($sql);
		$result_array = $db->fetchArray($result);
		$item['name'] = $result_array['cat_title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/index.php?cat_id='.$item_id ;
		return $item ;
	}

	if( $category == 'forum' ) {
		// Assume we have a valid forum_id
		$sql = 'SELECT forum_title FROM ' . $db->prefix($mydirname.'_forums') . ' WHERE forum_id='.$item_id ;
		$result = $db->query($sql);
		$result_array = $db->fetchArray($result);
		$item['name'] = $result_array['forum_title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/index.php?forum_id='.$item_id ;
		return $item ;
	}

	if( $category == 'topic' ) {
		// Assume we have a valid topid_id
		$sql = 'SELECT topic_title FROM ' . $db->prefix($mydirname.'_topics') . ' WHERE topic_id='.$item_id ;
		$result = $db->query($sql);
		$result_array = $db->fetchArray($result);
		$item['name'] = $result_array['topic_title'];
		$item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/index.php?topic_id='.$item_id ;
		return $item ;
	}
}

?>