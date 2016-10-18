<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$id = @$_GET['id'];
if (!$id) {
	exit;
}

$xoopsDB = $GLOBALS['xoopsDB'];
$task = Task::findById($xoopsDB, $id);

// pager
$options = array(
	'page' => @$_GET['page'],
	'perPage' => @$_GET['perPage']
);
$historyList = HistoryList::findByTaskId(
		$xoopsDB, $task->getId(), $options);

$itemCount = "{$historyList->getMinNumber()} - {$historyList->getMaxNumber()} of {$historyList->countAll()}";

$perPages = array(
	'5' => 5,
	'10' => 10,
	'20' => 20,
	'50' => 50,
	'0' => _MD_TASK_ALL
);

$xoopsTpl->assign(array(
	'id' => $id,
	'task' => $task,
	'itemCount' => $itemCount,
	'perPages' => $perPages,
	'pager' => $historyList
));
