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

function b_waiting_d3forum( $mydirname )
{
	$db =& Database::getInstance();
	$ret = array() ;

	$sql = "SELECT COUNT(*),MIN(post_id) FROM ".$db->prefix($mydirname."_posts")." WHERE approval=0" ;
	if( $result = $db->query($sql) ) {
		list( $waiting_count , $post_id ) = $db->fetchRow( $result ) ;
		$ret = array(
			'adminlink' => XOOPS_URL.'/modules/'.$mydirname.'/index.php?post_id='.intval($post_id) ,
			'pendingnum' => intval( $waiting_count ) ,
			'lang_linkname' => _PI_WAITING_WAITINGS ,
		) ;
	}
	
	return $ret ;
}

?>