<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: index.php 3662 2010-06-16 02:22:17Z yoshimura $ */

error_reporting(0);
//ini_set('display_errors', 'off');

require_once dirname(__FILE__).'/../../mainfile.php';
require_once dirname(__FILE__).'/config.php';

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

require_once(dirname(__FILE__).'/page/PageFactory.php');
$invokePage = PageFactory::createPage(isset($_GET['page']) ? $_GET['page'] : '');
$invokePage->execute();

include(XOOPS_ROOT_PATH.'/header.php');
include(XOOPS_ROOT_PATH.'/footer.php');
?>