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

header('Content-Type: application/json; charset=utf-8;');

$servId = $_POST['serviceId'];
$flg = $_POST['flg'];
$mode = $_POST['mode'];
$userId = $xoopsUser->getVar('uid');

global $xoopsDB;

$tbl = $xoopsDB->prefix('langrid_services');
if ($mode == 'TRANS') {
	$sql = 'UPDATE '.$tbl.' SET now_active = \'off\' WHERE service_type = \'TRANSLATION\';';
	if( ! $xoopsDB->queryf($sql)) {
		die('SQLError.');
	}
}
if ($mode == 'DICT-ALL') {
	$sql = 'UPDATE '.$tbl.' SET now_active = \''.$flg.'\' WHERE service_type = \'DICTIONARY\';';
	if( ! $xoopsDB->queryf($sql)) {
		die('SQLError2-1.');
	}
} else if ($mode == 'USERDICT') {
	$val = explode('_', $servId);
	$sql = 'UPDATE '.$xoopsDB->prefix('user_dictionary').' SET now_active = \''.$flg.'\' WHERE user_dictionary_id = \''.$val[2].'\'';
	if( ! $xoopsDB->queryf($sql)) {
		die('SQLError2-2.');
	}
} else {
	$sql = 'UPDATE '.$tbl.' SET now_active = \''.$flg.'\' WHERE service_id = \''.$servId.'\';';
	if( ! $xoopsDB->queryf($sql)) {
		die('SQLError2.');
	}
}

$sql = 'SELECT * FROM '.$tbl.' WHERE now_active = \'on\' AND service_type = \'%s\';';
if ( ! $rs = $xoopsDB->query(sprintf($sql, 'TRANSLATION'))) {
	die('SQLError3');
}

$translationRow = $xoopsDB->fetchArray($rs);

if ( ! $rs2 = $xoopsDB->query(sprintf($sql, 'DICTIONARY'))) {
	die('SQLError4');
}

$dictIds = array();
while ($row = $xoopsDB->fetchArray($rs2)) {
	$dictIds[] = $row['service_id'];
}

$langTexts = $translationRow['supported_languages_paths'];
$paths = str_replace('"', '', $langTexts);
$pathArray = explode(',', $paths);

$sql = 'DELETE FROM '.$xoopsDB->prefix('translation_config').' WHERE user_id = \''.$userId.'\'';
if ( ! $xoopsDB->queryf($sql)) {
	die('SQLError5');
	die();
}

$ss = '';
for ($i = 0; $i < count($dictIds); $i++) {
	$ss .= ', dict_service_id_'.($i+1).' = \''.$dictIds[$i].'\'';
}

$sql = 'INSERT INTO '.$xoopsDB->prefix('translation_config').' SET user_id = \'%s\', source_lang = \'%s\', target_lang = \'%s\', translation_service_id = \'%s\''.$ss;
for ($i = 0; $i < count($pathArray); $i++) {
	$pair = explode('2', $pathArray[$i]);
//	echo $pair[0].':'.$pair[1].'<br />';
	$query = sprintf($sql, $userId, $pair[0], $pair[1], $translationRow['service_id']);
	if ( ! $xoopsDB->queryf($query)) {
		die('SQLError6');
	}
}

echo json_encode(array('status'=>'OK'));
?>