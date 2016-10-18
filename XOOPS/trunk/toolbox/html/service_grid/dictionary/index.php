<?php

require_once(dirname(__FILE__).'/class/LgTemplate.class.php');
require_once(dirname(__FILE__).'/class/LgSettingManager.class.php');
require_once(dirname(__FILE__).'/class/LgDictionaryManager.class.php');
require_once(dirname(__FILE__).'/class/DictionaryUrlList.class.php');
require_once(dirname(__FILE__).'/class/LgDictionaryIndexController.class.php');
require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

$controller = new LgDictionaryIndexController($wgRequest, dirname(__FILE__));
$path = $controller->getPath();
require_once($path);

return;
?>
