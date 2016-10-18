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

// get task
$task = Task::findById($GLOBALS['xoopsDB'], $id);

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

$xoopsTpl->assign(array(
	'task' => $task,
	'languageList' => $languageList,
	'selectedLanguage' => $lang,
	'categoryList' => $categoryList,
	'forumDialogTitle' => sprintf(_MD_TASK_FORUM_DIALOG_TITLE, $task->getName())
));
