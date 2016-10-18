<?php

require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');
/**
 * <#if locale="en">
 * Import
 * <#elseif locale="ja">
 * インポート
 * </#if>
 */

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

$idUtil = new LanguageGridArticleIdUtil();
$dictId = $idUtil->getDictionaryIdByPageTitle($_POST['title_db_key']);

$dbHandler = new UserDictionaryDbHandler();

$tmpFilePath = $_FILES['dictfile']['tmp_name'];
$mimeType = $_FILES['dictfile']['type'];
$res = $dbHandler->doUpload($dictId, $tmpFilePath, $mimeType);

$return = array();
$return[] = '<html>';
$return[] = '<head>';
$return[] = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
$return[] = '</head>';
$return[] = '<body>';
$return[] = '<script language="JavaScript" type="text/javascript">';
$return[] = 'with(window.parent) {';
if ($res != '') {
	$return[] = 'alert("'.$res.'")';
} else {
	$return[] = 'uploadDictionary.hidePane();';
	$return[] = 'dictionaryMain._doRefresh()';
}
$return[] = '}';
$return[] = '</script>';
$return[] = '</body>';
$return[] = '</html>';

echo implode("\n", $return);
die();
/*
 * <#if locale="en">
 * Terminate HTTP session without generating MediaWiki contents for a respoinse
 * This process opens a file open dialog by receving a HTTP request and operating content type of the response.
 * <#elseif locale="ja">
 * MediaWikiのレスポンス用コンテンツの生成をしないで、HTTPセッションを強制的に終了させる。
 * </#if>
 */
 ?>
