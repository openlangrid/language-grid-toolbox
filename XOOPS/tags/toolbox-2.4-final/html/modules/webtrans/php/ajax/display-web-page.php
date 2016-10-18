<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009  NICT Language Grid Project
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
//error_reporting(0);
require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__).'/../class/DisplayCacheManagerClass.php');
global $xoopsUser;

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	header('Content-Type: application/json; charset=utf-8;');
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit();
}

$cacheManager = new DisplayCacheManagerClass();

$displayKey = @$_GET['display_key'];

if ($displayKey) {
	header('Content-Type: text/html; charset=utf-8;');
	echo $cacheManager->getCacheContents($displayKey);
} else {
	header('Content-Type: application/json; charset=utf-8;');

	$htmlText = $_POST['contents'];

	$displayKey = $cacheManager->setCacheContents($htmlText, $userId);
	if ($displayKey) {
		echo json_encode(array('display_key' => $displayKey));
	} else {
		echo json_encode(array('status'=>'SAVEERROR'));
	}
}
?>