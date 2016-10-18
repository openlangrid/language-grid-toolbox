<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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

require_once(dirname(__FILE__).'/../../../mainfile.php');
require_once(dirname(__FILE__).'/../class/TranslationPathSettingClass.php');
global $xoopsUser;

header('Content-Type: application/json; charset=utf-8;');

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit();
}

$servId = $_POST['serviceId'];
$flg = $_POST['flg'];
$mode = $_POST['mode'];
$userId = $xoopsUser->getVar('uid');

$settingUser = new TranslationPathSettingClass();

if ($mode == 'TRANS') {
	$settingUser->saveTranslator($userId, $servId);
} else if ($mode == 'DICT') {
	$settingUser->saveDictionary($userId, $servId, $flg, 'GLOBAL');
} else if ($mode == 'USERDICT') {
	$settingUser->saveDictionary($userId, $servId, $flg, 'USER');
}

echo json_encode(array('status'=>'OK'));
?>