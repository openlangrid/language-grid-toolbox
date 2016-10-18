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
/** $Id: index.php 3620 2010-04-13 05:12:35Z yoshimura $ */

error_reporting(E_ALL);
//include(dirname(__FILE__).'/header.php');
require('../../mainfile.php');

$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] );
if( file_exists(dirname(__FILE__).'/'.$page.'.php')) {
	include dirname(__FILE__).'/'.$page.'.php';
} else {
	die();
}

include(XOOPS_ROOT_PATH.'/header.php');
include(XOOPS_ROOT_PATH.'/footer.php');
//require_once(dirname(__FILE__)."/footer.php");
?>