<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$id = @$_GET['id'];
if ($id < 1) {
	exit;
}

require_once dirname(__FILE__).'/_list.php';

// get categories
$categoryManager = new CategoryManager($lang);

$categories = $categoryManager->getCategories();
$categoryList = array();

foreach ($categories as $category) {
	$categoryList["{$category->getId()}"] = $category->getName();
}

// languages
$languageTags = TranslationPath::getSourceLangs(getLoginUserUid());
$languageMap = CommonUtil::getLanguageNameMap();
$languageList = array();

foreach ($languageTags as $tag) {
	if (isset($languageMap[$tag])) {
		$languageList[$tag] = $languageMap[$tag];
	}
}

// labels
$labels = array(
	'forumDialogTitle' => sprintf(_MD_TASK_FORUM_DIALOG_TITLE, $task->getName()),
);

$xoopsTpl->assign(array(
	'languageList' => $languageList,
	'selectedLanguage' => $lang,
	'categoryList' => $categoryList,
	'labels' => $labels
));
