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
ini_set('max_execution_time', '-1');

$module_handler= & xoops_gethandler('module');
$psModule = $module_handler->getByDirname('langrid');
$mid = $psModule->mid();
$config_handler =& xoops_gethandler('config');
$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $mid);

$isUpdateService = $xoopsModuleConfig['is_update_service'];
$lastUpdateTime = $xoopsModuleConfig['last_update_time'];
$interval = $xoopsModuleConfig['update_interval'];

if ($isUpdateService && $lastUpdateTime + $interval < time()) {
	$file = XOOPS_ROOT_PATH.'/modules/langrid_config/class/manager/GlobalServicesLoader.class.php';
	if (file_exists($file)) {
		require_once $file;
		$globalLoader = new GlobalServicesLoader();
		$globalLoader->refresh();
	}
	$sql = 'UPDATE %s SET conf_value = \'%s\' WHERE conf_modid = \'%s\' AND conf_name = \'%s\';';
	$xoopsDB->queryf(sprintf($sql, $xoopsDB->prefix('config'), time(), $mid, 'last_update_time'));
}


//define('LAST_UPDATE_TIME', 'last_update_time');
//define('UPDATE_INTERVAL', 'update_interval');
//
////require_once(dirname(__FILE__).'/../php/admin/load-langrid-services.php');
//require_once(dirname(__FILE__).'/../php/service/manager/ServiceManagerClient.class.php');
//global $xoopsDB, $xoopsModule;
//
//$module_handler= & xoops_gethandler('module');
//$psModule = $module_handler->getByDirname('langrid');
//$config_handler =& xoops_gethandler('config');
//$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $psModule->mid());
//
//if ($xoopsModuleConfig['is_update_service'] != '1') {
//	return;
//}
//
////$mid = $xoopsModule->getVar('mid');
//$mid = $psModule->mid();
//
//$lastUpdateTime = $xoopsModuleConfig[LAST_UPDATE_TIME];
//$interval = $xoopsModuleConfig[UPDATE_INTERVAL];
//
//if ($lastUpdateTime + $interval < time()) {
////	$sql = 'update '.$xoopsDB->prefix('config').' set conf_value = \''.time().'\' where conf_modid = \''.$mid.'\' and conf_name = \''.LAST_UPDATE_TIME.'\';';
////	$xoopsDB->queryf($sql);
//} else {
//	return;
//}
//
////$serviceManager =& LoadLangridServices::getInstance();
////$translationServises = $serviceManager->getServices('translation');
////$dictServises = $serviceManager->getServices('billingualdictlong');
//$serviceManager =& new ServiceManagerClient();
//$translationServises = $serviceManager->loadTranslator();
//$dictServises = $serviceManager->loadDictionary();
//$AnalysisServises = $serviceManager->loadAnalyzer();
//$textToSpeechServises = $serviceManager->loadTextToSpeech();
//
//
//if ($translationServises['status'] != 'OK') {
//	die('LangridCore:refreshLangridService#Error('.__LINE__.')<br />'.$translationServises['message']);
//	print_r($translationServises);
//}
//if ($dictServises['status'] != 'OK') {
//	die('LangridCore:refreshLangridService#Error('.__LINE__.')<br />'.$dictServises['message']);
//	print_r($dictServises);
//}
//if ($AnalysisServises['status'] != 'OK') {
//	die('LangridCore:refreshLangridService#Error('.__LINE__.')<br />'.$AnalysisServises['message']);
//	print_r($AnalysisServises);
//}
//if ($textToSpeechServises['status'] != 'OK') {
//	die('LangridCore:refreshLangridService#Error('.__LINE__.')<br />'.$textToSpeechServises['message']);
//	print_r($textToSpeechServises);
//}
//
//$sql = 'SELECT * FROM '.$xoopsDB->prefix('langrid_services').' WHERE now_active = \'on\';';
//if ( ! $rs = $xoopsDB->query($sql)) {
//	die('SQLError:'.__LINE__.'['.$s.']');
//}
//$nowActives = array();
//while ($row = $xoopsDB->fetchArray($rs)) {
//	$nowActives[] = $row['service_id'];
//}
//$sql = 'DELETE FROM '.$xoopsDB->prefix('langrid_services').' WHERE `service_type` NOT IN (\'IMPORTED_DICTIONARY\', \'IMPORTED_TRANSLATION\');';
//if ( ! $rs = $xoopsDB->queryf($sql)) {
//	die('SQLError:'.__LINE__.'['.$s.']');
//}
//
//$sql1 = '';$sql2 = '';
//$sql1 .= 'service_id, ';                 $sql2 .= '\'%s\', ';
//$sql1 .= 'service_type, ';               $sql2 .= '\'%s\', ';
//$sql1 .= 'service_name, ';               $sql2 .= '\'%s\', ';
//$sql1 .= 'endpoint_url, ';               $sql2 .= '\'%s\', ';
//$sql1 .= 'supported_languages_paths, ';  $sql2 .= '\'%s\', ';
//$sql1 .= 'now_active, ';                 $sql2 .= '\'%s\', ';
//$sql1 .= 'organization, ';               $sql2 .= '\'%s\', ';
//$sql1 .= 'copyright, ';                  $sql2 .= '\'%s\', ';
//$sql1 .= 'license,';                     $sql2 .= '\'%s\',';
//$sql1 .= 'description ';                 $sql2 .= '\'%s\' ';
//
//$sql  = 'INSERT INTO '.$xoopsDB->prefix('langrid_services').'';
//$sql .= ' ('.$sql1.') VALUES ('.$sql2.');';
//
//foreach ($translationServises['contents'] as $service) {
//	reset($nowActives);
//	$atv = 'off';
//	if (in_array($service['serviceId'], $nowActives)) {
//		$atv = 'on';
//	}
//	$paths = implode(',', $service['path']);
//	$s = sprintf($sql,
//		$service['serviceId'],
//		'TRANSLATION',
//		$service['name'],
//		$service['endpointUrl'],
//		$paths,
//		$atv,
//		addslashes($service['organization']),
//		addslashes($service['copyright']),
//		addslashes($service['license']),
//		addslashes($service['description']));
//	if ( ! $rs = $xoopsDB->queryf($s)) {
//		die('SQLError:'.__LINE__.'['.$s.']');
//	}
//}
//foreach ($dictServises['contents'] as $service) {
//	reset($nowActives);
//	$atv = 'off';
//	if (in_array($service['serviceId'], $nowActives)) {
//		$atv = 'on';
//	}
//	$paths = implode(',', $service['path']);
//	//$s = sprintf($sql, $service['serviceId'], 'DICTIONARY', $service['name'], $service['endpointUrl'], $paths, $atv);
//	$s = sprintf($sql,
//		$service['serviceId'],
//		'DICTIONARY',
//		$service['name'],
//		$service['endpointUrl'],
//		$paths,
//		$atv,
//		addslashes($service['organization']),
//		addslashes($service['copyright']),
//		addslashes($service['license']),
//		addslashes($service['description']));
//	if ( ! $rs = $xoopsDB->queryf($s)) {
//		die('SQLError:'.__LINE__.'['.$s.']');
//	}
//}
//foreach ($AnalysisServises['contents'] as $service) {
//	reset($nowActives);
//	$atv = 'off';
//	if (in_array($service['serviceId'], $nowActives)) {
//		$atv = 'on';
//	}
//	$paths = implode(',', $service['path']);
//	$s = sprintf($sql,
//		$service['serviceId'],
//		'ANALYZER',
//		$service['name'],
//		$service['endpointUrl'],
//		$paths,
//		$atv,
//		addslashes($service['organization']),
//		addslashes($service['copyright']),
//		addslashes($service['license']),
//		addslashes($service['description']));
//	if ( ! $rs = $xoopsDB->queryf($s)) {
//		die('SQLError:'.__LINE__.'['.$s.']');
//	}
//}
//
//foreach ($textToSpeechServises['contents'] as $service) {
//	reset($nowActives);
//	$atv = 'off';
//	if (in_array($service['serviceId'], $nowActives)) {
//		$atv = 'on';
//	}
//	$paths = implode(',', $service['path']);
//	$s = sprintf($sql,
//		$service['serviceId'],
//		'TEXTTOSPEECH',
//		$service['name'],
//		$service['endpointUrl'],
//		$paths,
//		$atv,
//		addslashes($service['organization']),
//		addslashes($service['copyright']),
//		addslashes($service['license']),
//		addslashes($service['description']));
//	if ( ! $rs = $xoopsDB->queryf($s)) {
//		die('SQLError:'.__LINE__.'['.$s.']');
//	}
//}
//
//
//$sql = '';
////$sql .= 'select * from '.$xoopsDB->prefix('translation_path_setting').' where delete_flag = \'0\';';
////if ( ! $rs = $xoopsDB->query($sql)) {
////	die('SQLError:'.__LINE__.'[]'.$sql.']');
////}
////while ($row = $xoopsDB->fetchArray($rs)) {
////	$ref = '';
////	$ary = explode(',', $row['bind_global_dict_ids']);
////	foreach ($ary as $id) {
////		if (hasService($id)) {
////			$ref .= $id . ',';
////		}
////	}
////	$ref = substr($ref, 0, -1);
////
////	$sql = '';
////	$sql .= 'update '.$xoopsDB->prefix('translation_path_setting').' set bind_global_dict_ids = \''.$ref.'\', edit_date = now() where id = '.$row['id'].'';
////	if (! $xoopsDB->queryf($sql)) {
////		die('SQLError:'.'['.$sql.']');
////	}
////}
//
//$sql = 'update '.$xoopsDB->prefix('config').' set conf_value = \''.time().'\' where conf_modid = \''.$mid.'\' and conf_name = \''.LAST_UPDATE_TIME.'\';';
//$xoopsDB->queryf($sql);
//
//function hasService($serviceId) {
//	global $xoopsDB;
//	$sid = '\''.mysql_real_escape_string($serviceId).'\'';
//	$sql = '';
//	$sql .= 'select count(*) AS CNT from '.$xoopsDB->prefix('langrid_services').' where service_id = '.$sid;
//	if ($rs = $xoopsDB->query($sql)) {
//		$row = $xoopsDB->fetchArray($rs);
//		if ($row['CNT'] > 0) {
//			return true;
//		}
//	}
//	return false;
//}
?>