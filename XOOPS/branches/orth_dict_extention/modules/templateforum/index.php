<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once('../../mainfile.php');
require_once(XOOPS_ROOT_PATH.'/modules/toolbox/toolbox.php');
require_once(dirname(__FILE__).'/config.php');

if (! defined('XOOPS_COOKIE_PATH')) {
    define('XOOPS_COOKIE_PATH', preg_replace( '?http://[^/]+(/.*)$?' , '$1' , XOOPS_URL ));
}

$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
$mytrustdirname = basename(dirname(__FILE__));
require(XOOPS_ROOT_PATH.'/modules/'.$mytrustdirname.'/main.php');
?>