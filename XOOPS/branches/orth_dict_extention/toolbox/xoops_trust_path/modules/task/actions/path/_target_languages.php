<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__) . '/../../../collabtrans/class/translation_path.php';

$lang = null;
$tags = null;

if(isset($_GET['sourceLang'])) {
	$tags = TranslationPath::getTargetLangs(getLoginUserUid(), null);
	$lang = $_GET['sourceLang'];
} elseif(isset($_GET['targetLang'])) {
	$tags = TranslationPath::getSourceLangs(getLoginUserUid());
	$lang = $_GET['targetLang'];
} else {
	exit;
}

$key = array_search($lang, $tags);
if ($key !== false) {
	unset($tags[$key]);
}

$xoopsTpl -> assign(array(
	'languageTags' => $tags,
	'langMap' => CommonUtil::getLanguageNameMap()
));
