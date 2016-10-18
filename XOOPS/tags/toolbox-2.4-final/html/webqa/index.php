<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once('../mainfile.php');

setPearPath();
define('APP_ROOT_DIR', dirname(__FILE__));
define('APP_ROOT_URL', XOOPS_URL.'/webqa/');
define('APP_COOKIE_PATH', XOOPS_COOKIE_PATH);
define('APP_BACKEND_URL', XOOPS_URL.'/modules/webqa/public/');

require_once('fw/loader.php');

$controller = new Controller();
$controller->execute();


function webqa_log($val) {
	error_log(print_r($val, 1), 3, dirname(__FILE__).'/log/'.time().'.log');
}

function setPearPath() {
	$path = dirname(__FILE__).'/vendor/lib/PEAR';
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

function getXoopsRootUrl() {
	$url  = 'http://';
	$url .=  $_SERVER["SERVER_NAME"];
	$url .= $_SERVER["SCRIPT_NAME"];
	$url  = str_replace('/webqa/index.php', '', $url);
	return $url;
}

?>
