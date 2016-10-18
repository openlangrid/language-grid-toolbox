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
//require_once(dirname(__FILE__).'/../class/TranslationPathSettingClass.php');
require_once(dirname(__FILE__).'/../class/PathSettingWrapperClass.php');
require_once(dirname(__FILE__).'/../class/LangridServicesClass.php');
global $xoopsUser;

header('Content-Type: application/json; charset=utf-8;');

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit();
}

$mode = '';
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
}
if ($mode != 'ALL') {
	die('Modes are different for ALL(UserSetting).');
}

$data = null;
if (isset($_POST['data']) && is_array($_POST['data'])) {
	$data = $_POST['data'];
}

if ($data == null) {
	die('Data is null.');
}

$uid = $xoopsUser->getVar('uid');

//print_r($data);
$postData = array();
foreach ($data as $row) {
	$tokens = explode('&', $row);
	$post = array();
	foreach ($tokens as $token) {
		$keyval = explode('=', $token);
		$post[$keyval[0]] = $keyval[1];
	}
	$post['id'] = urldecode($post['id']);
	for($i=1;$i<=3;$i++){
		$post['global_dict_'.$i] = urldecode($post['global_dict_'.$i]);
		$post['local_dict_'.$i] = urldecode($post['local_dict_'.$i]);
		$post['temp_dict_'.$i] = urldecode($post['temp_dict_'.$i]);
	}
	for($i=1;$i<=4;$i++){
		$post['morph_analyzer'.$i] = urldecode($post['morph_analyzer'.$i]);
	}
	$postData[] = $post;
}

//$langridClass = new LangridServicesClass();
$pathSetting = new PathSettingWapperClass();

$contents = array();
foreach ($postData as $data) {
	$afterIds = '';
	$ids = explode(',', $data['id']);
	if (count($ids) == 1) {
		$afterIds = $pathSetting->saveUserSetting($uid, $data);
		if ($data['flow1'] == 'both') {
			$data2 = postDateRevs($data);
			$data2['id'] = '';
			$tmp = $pathSetting->saveUserSetting($uid, $data2);
			if ($tmp) {
				$pathSetting->linkTranslation($afterIds,$tmp);
				$afterIds .= ','.$tmp;
			}
		}
	} else {
		$data1 = $data;
		$data1['id'] = $ids[0];
		$afterIds = $pathSetting->saveUserSetting($uid, $data1);
		$data2 = postDateRevs($data);
		$data2['id'] = $ids[1];
		if ($data2['flow1'] == 'left') {
			$data2['isDelete'] = 'yes';
		}
		$tmp = $pathSetting->saveUserSetting($uid, $data2);
		if ($tmp) {
			$pathSetting->linkTranslation($afterIds,$tmp);
			$afterIds .= ','.$tmp;
		}
	}
	$contents[$data['index']] = $afterIds;
}

echo json_encode(array('status'=>'OK', 'contents'=> $contents));
?>