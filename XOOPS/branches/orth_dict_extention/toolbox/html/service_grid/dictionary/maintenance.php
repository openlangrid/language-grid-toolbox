<?php

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

$lang = $_POST['lang'];

if ($lang == null || is_array($lang) == false || count($lang) < 2) {
	$wgOut->redirect($wgTitle->getFullURL('action=edit&pagedict'));
}

/**
 * 
 * TODO: Implement process for the case it already exists
 * 
 */

$idUtil = new LanguageGridArticleIdUtil();
$dictId = $idUtil->getDictionaryIdByPageTitle($idUtil->getTitleDbKey());
$dbHandler = new UserDictionaryDbHandler();
if ($dbHandler->getUserDictionary($dictId) == null) {
	$dbHandler->create($idUtil->getTitleDbKey(), $lang);
} else {
	$dbHandler->updateLanguage($dictId, $lang);
}

$wgOut->redirect($wgTitle->getFullURL('action=edit&pagedict'));


?>
