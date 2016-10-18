<?php

require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');
/**
 * <#if locale="en">
 * Entry point for export
 * This process opens a file open dialog by receving a HTTP request and operating content type of the response.
 * <#elseif locale="ja">
 * エクスポートのエントリポイント
 * 通常のHTTPリクエストを受信し、レスポンスのコンテンツタイプを操作することで、
 * ブラウザのファイル保存ダイアログを開かせる
 * </#if>
 */
global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;
$idUtil =& new LanguageGridArticleIdUtil();
$dictId = $idUtil->getDictionaryIdByPageTitle($idUtil->getTitleDbKey());
$dbHandler =& new UserDictionaryDbHandler();
$contents =& $dbHandler->doDownload($dictId);
$return = array();
if (count($contents)) {
	foreach ($contents[0] as $key => $cell) {
		if ($key != 'row') {
			$return[] = $key."\t";
		}
	}
	$return[] = "\n";
}

foreach($contents as $row) {
	foreach($row as $key => $cell) {
		if ($key != 'row') {
			$return[] = $cell."\t";
		}
	}
	$return[] = "\n";
}

$utf16LEcontent = chr(255).chr(254).mb_convert_encoding(implode('', $return), "UTF-16LE", "UTF-8");

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=".$wgTitle->getDBkey());
echo $utf16LEcontent;

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
