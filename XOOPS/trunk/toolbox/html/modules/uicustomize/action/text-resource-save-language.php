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
/**
 * $Id: text-resource-save-language.php 3662 2010-06-16 02:22:17Z yoshimura $
 */
//error_reporting(E_ALL);
require_once(dirname(__FILE__).'/../class/fileio/CubeUtil_Hack.class.php');
require_once(dirname(__FILE__).'/../class/manager/UICustomizeTextResourceFilesManager.php');

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$languages = @$_POST['languages'];
	if ($languages == null || !is_array($languages)) {
		throw new Exception(_MI_UIC_ERROR_NO_LANGUAGE);
	}
	$c = new CubeUtil_Hack();
	$nowlangs = $c->getUISupportLanguages();
	foreach ($nowlangs as $l) {
		if (!in_array($l, $languages)) {
			// for delete.
			$c->removeUISupportLanguage($l);
		}
	}
	foreach ($languages as $l) {
		if (!in_array($l, $nowlangs)) {
			// for add
			$info = save_language_information_filter($l);
			$c->addUISupportLanguage($l, $info['name'], $info['dir']);
		}
	}

} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8;');
echo json_encode($result);

function save_language_information_filter($lang) {
	$dir_adhocs = UICustomizeTextResourceFilesManager::getAdhocDirnameLists();

	$ret = array(
		'name' => '',
		'dir' => ''
	);

	include XOOPS_ROOT_PATH . '/modules/langrid/include/Languages.php';
	$ret['name'] = $LANGRID_LANGUAGE_ARRAY[$lang];
	if (array_key_exists($lang, $dir_adhocs)) {
		$ret['dir'] = $dir_adhocs[$lang];
	} else {
		$ret['dir'] = $lang;
	}
	return $ret;
}
?>