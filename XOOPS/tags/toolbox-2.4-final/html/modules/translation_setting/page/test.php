<?php
require_once(dirname(__FILE__).'/../php/class/TranslationServiceSetting.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/manager/ServiceManagerClient.class.php');

//$client =& new ServiceManagerClient();
//print_r($client->loadServices('BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH'));

$serviceSetting =& new TranslationServiceSetting();
//
//echo '<pre>';
//echo '<h2>getServiceSettings(uid=1, type=BBS)</h2>';
//print_r($serviceSetting->getServiceSettings('1', 'bbs'));
//echo '<hr>';
//echo '</pre>';
//
////print_r($serviceSetting->loadServiceSetting('1'));
//
$path = $serviceSetting->addTranslationPath('1', '1', 'ja', 'en');

$exec = $serviceSetting->addTranslationExec($path->get('path_id'), 'ja', 'en', 'NICTJServer');
$bind = $serviceSetting->addTranslationBind($path->get('path_id'), $exec->get('exec_id'), '1', 'Lsd');
$bind = $serviceSetting->addTranslationBind($path->get('path_id'), $exec->get('exec_id'), '1', 'KyoToT');
////
//
////$exec = $serviceSetting->addTranslationExec($path->get('path_id'), 'ja', 'ko', 'NICTJServer');
////$exec = $serviceSetting->addTranslationExec($path->get('path_id'), 'ja', 'ko', 'NICTJServer');
////$exec = $serviceSetting->addTranslationExec($path->get('path_id'), 'ja', 'ko', 'NICTJServer');
//
////print_r($serviceSetting->linkReverse('11', '12'));
//
////print_r($serviceSetting->removeTranslationPath('10'));
//
////print_r($exec);
//
////$setting =& $serviceSetting->loadServiceSetting($path->get('path_id'));
////
////$setting->set('path_name', 'test');
////
////foreach ($setting->getExecs() as $exec) {
////	$exec->set('exec_order', 10);
////	foreach ($exec->getBinds() as $bind) {
////		$bind->set('bind_type', 2);
////	}
////}
////
////echo $serviceSetting->update($setting);
////
////print_r($setting);
//
////print_r($serviceSetting->removeTranslationBind('16', '1', '1'));


//updateLocalTranslation
//removeLocalTranslation
//removeLocalDictionary
//removeTemporalDictionary
?>
