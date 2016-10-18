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
require_once(dirname(__FILE__).'/../class/UserDictionaryClass.php');
require_once(dirname(__FILE__).'/../class/DefaultDictionariesClass.php');
require_once(dirname(__FILE__).'/../include/Functions.php');

global $xoopsUser;

header('Content-Type: application/json; charset=utf-8;');

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit();
}


$contents = array();

$langridClass =& new LangridServicesClass();

$supportLangs = $langridClass->getTranslatorAllSupportLanguagePairs();
//$supportLangs = $langridClass->getAllSupportLanguagePairs();
$contents['supportLangs'] = $supportLangs;


$translationServices = $langridClass->getTranslators();
//foreach ($translationServices as &$item) {
//	$item['langPare'] = languagePair($item['supported_languages_paths']);
//}

$localtranslationServices = $langridClass->getLocalTranslators();
foreach ($localtranslationServices as &$item) {
//	$item['langPare'] = languagePair($item['supported_languages_paths']);
	$translationServices[] = $item;
}
$contents['translationServices'] = $translationServices;

$AnalyzerServices = $langridClass->getAnalyses();
$contents['analyzeServices'] = $AnalyzerServices;

$dictionaryServices = $langridClass->getDictionarys();
//foreach ($dictionaryServices as &$item) {
//	$item['langPare'] = languagePair($item['supported_languages_paths']);
//}

$localdictionaryServices = $langridClass->getLocalDictionarys();
foreach ($localdictionaryServices as &$item) {
//	$item['langPare'] = languagePair($item['supported_languages_paths']);
	$dictionaryServices[] = $item;
}

$userDictCtrl =& new UserDictionaryClass();
$userDicts = $userDictCtrl->getUserDictionarys();
foreach ($userDicts as &$userDict) {
	$userDict['service_id'] = $userDict['name'];
	$userDict['service_name'] = $userDict['name'];
	$userDict['service_type'] = 'USER_DICTIONARY';
	$dictionaryServices[] = $userDict;
	if($userDict['deployFlag']){
		$userDict['service_id'] = $userDict['name'];
		$userDict['service_name'] = $userDict['name'];
		$userDict['service_type'] = 'IMPORTED_DICTIONARY';
		$dictionaryServices[] = $userDict;
	}
}
usort($dictionaryServices,"mycmp");
function mycmp($a,$b){
	return strcasecmp($a['service_name'],$b['service_name']);
}
//array_push($dictionaryServices, $userDicts);

$contents['dictionaryServices'] = $dictionaryServices;

$uid = $xoopsUser->getVar('uid');

$defDicts =& new DefaultDictionariesSetting();
$contents['DefaultDicts'] = $defDicts->searchByUserId($uid);

/*
$pathSetting =& new TranslationPathSettingClass();
$setting = $pathSetting->searchByUserIdByTop($uid);
$contents['setting'] = mergeSetting($setting);
*/
$pathSetting =& new PathSettingWapperClass();
$setting = $pathSetting->searchByUserIdByTop($uid);
$contents['setting'] = $setting;

echo json_encode(array('status'=>'OK', 'contents'=> $contents));
?>