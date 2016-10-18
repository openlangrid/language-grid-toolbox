<?php
// get language code
require_once dirname(__FILE__).'/../../class/common_util.php';

$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];

$languageNameMap  = commonUtil::getLanguageNameMap();
$languageCodeList = commonUtil::getLanguageListFromResource($resourceName);

$xoopsTpl->assign('resourceName', $resourceName);
$xoopsTpl->assign('languageNameMap', $languageNameMap);
$xoopsTpl->assign('languageCodeList', $languageCodeList);
?>