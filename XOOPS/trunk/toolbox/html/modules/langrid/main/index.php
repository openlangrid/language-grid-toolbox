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

$xoopsOption['template_main'] = 'langridmain.html';

function httpAutoLink($text){
	return preg_replace("/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+/)",
				 "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>" , $text);
}

//require_once(dirname(__FILE__).'/../class/search-service-config.php');
require_once(dirname(__FILE__).'/../include/refreshLangridService.php');
require_once(dirname(__FILE__).'/../include/Functions.php');
//require_once(dirname(__FILE__).'/../class/TranslationPathSettingClass.php');
require_once(dirname(__FILE__).'/../class/LangridServicesClass.php');
require_once(dirname(__FILE__).'/../class/UserDictionaryClass.php');

global $xoopsDB, $xoopsUser;

if (!$xoopsUser) {
	redirect_header(XOOPS_URL);
}
//$actives = getServiceNowActiveByUser($xoopsUser->getVar('uid'));
$services = array();
$cGDict = array();
$cUDict = array();
$udicts = array();

/*
$langridServices = new LangridServicesClass();
$settingUser = new TranslationPathSettingClass();
$userDictCtrl = new UserDictionaryClass();

$currentTranslator = $settingUser->searchSelectedTranslatorByUserId($xoopsUser->getVar('uid'));
$currentDictionarys = $settingUser->searchSelectedDictionarysByUserId($xoopsUser->getVar('uid'));
if (!is_null($currentDictionarys['bind_global_dict_ids'])) {
	$cGDict = explode(',', $currentDictionarys['bind_global_dict_ids']);
}
if (!is_null($currentDictionarys['bind_user_dict_ids'])) {
	$cUDict = explode(',', $currentDictionarys['bind_user_dict_ids']);
}

$translationServices = $langridServices->getTranslators();
$localTranslationServices = $langridServices->getLocalTranslators();

$globalDictionarys = $langridServices->getDictionarys();

$userDictionarys = $userDictCtrl->getUserDictionarys();

foreach ($translationServices as $item) {

	$item['langPair'] = languagePair($item['supported_languages_paths']);

	if ($item['service_id'] == $currentTranslator) {
		$item['now_active'] = 'on';
	} else {
		$item['now_active'] = 'off';
	}
	$services[] = $item;
}
foreach ($localTranslationServices as $item) {

	$item['langPair'] = languagePair($item['supported_languages_paths']);

	if ($item['service_id'] == $currentTranslator) {
		$item['now_active'] = 'on';
	} else {
		$item['now_active'] = 'off';
	}
	$services[] = $item;
}


foreach ($globalDictionarys as $item) {

	$item['langPair'] = languagePair($item['supported_languages_paths']);

	if (in_array($item['service_id'], $cGDict)) {
		$item['now_active'] = 'on';
	} else {
		$item['now_active'] = 'off';
	}
	$services[] = $item;
}
foreach ($userDictionarys as $item) {
	$ary1 = $item['supportedLanguages'];
	$ary2 = $item['supportedLanguages'];
	$paths = array();
	foreach ($ary1 as $lang1) {
		foreach ($ary2 as $lang2) {
			if ($lang1 != $lang2) {
				$paths[] = $lang1.'2'.$lang2;
			}
		}
	}
	$langPair = implode(',', $paths);
	$item['langPair'] = languagePair($langPair);
	$item['service_id'] = $item['name'];
	$item['dictionary_name'] = $item['name'];
	if (in_array($item['name'], $cUDict)) {
		$item['now_active'] = 'on';
	} else {
		$item['now_active'] = 'off';
	}
	$udicts[] = $item;
}

$xoopsTpl->assign('rs', $services);
$xoopsTpl->assign('udicts', $udicts);
*/


//global $xoopsUser, $xoopsUserIsAdmin;
//if ($xoopsUserIsAdmin != '') {
//	$xoopsTpl->assign('xoopsUserIsAdmin', true);
//} else {
//	$xoopsTpl->assign('xoopsUserIsAdmin', false);
//}
$xoopsTpl->assign('xoopsUserIsAdmin', true);
?>