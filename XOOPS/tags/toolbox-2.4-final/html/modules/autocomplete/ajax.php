<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: ajax.php 3573 2010-03-30 10:40:59Z yoshimura $ */

require_once(dirname(__FILE__).'/class/AutoComplete.php');
require_once(dirname(__FILE__).'/class/AutoCompleteSetting.php');
// Redirect to top page if user don't sign in.
$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

if (isset($_GET['ajax'])) {
	// Ajax
	$ajax = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $_GET['ajax'] );
	$file = dirname(__FILE__).'/ajax/'.$ajax.'.php';
	if(file_exists($file)) {
		include $file;
	}
}

die();
?>