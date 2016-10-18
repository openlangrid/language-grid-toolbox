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

require_once dirname(__FILE__).'/main_functions.php' ;
require_once dirname(__FILE__).'/common_functions.php' ;
require_once dirname(dirname(__FILE__)).'/class/d3forum.textsanitizer.php' ;
$myts =& D3forumTextSanitizer::getInstance() ;
$db =& Database::getInstance();

// GET $uid
$uid = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
$isadmin = $uid > 0 ? $xoopsUser->isAdmin() : false ;

// post orders (default post_time desc)
$postorder = isset( $_COOKIE[$mydirname.'_postorder'] ) ? intval( $_COOKIE[$mydirname.'_postorder'] ) : 2 ;

// icon meanings
$d3forum_icon_meanings = explode( '|' , $xoopsModuleConfig['icon_meanings'] ) ;

// get this user's permissions as perm array
$category_permissions = d3forum_get_category_permissions_of_current_user( $mydirname ) ;
$whr_read4cat = 'c.`cat_id` IN (' . implode( "," , array_keys( $category_permissions ) ) . ')' ;
$forum_permissions = d3forum_get_forum_permissions_of_current_user( $mydirname ) ;
$whr_read4forum = 'f.`forum_id` IN (' . implode( "," , array_keys( $forum_permissions ) ) . ')' ;

// init xoops_breadcrumbs
if( is_object( $xoopsModule ) ) {
	$xoops_breadcrumbs[0] = array( 'url' => XOOPS_URL.'/modules/'.$mydirname.'/index.php' , 'name' => $xoopsModule->getVar( 'name' ) ) ;
} else {
	$xoops_breadcrumbs = array() ;
}

?>