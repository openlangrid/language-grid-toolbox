<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/task_list.php';

echo '<p>---------------------------- Test findAll() ---------------------</p>' . PHP_EOL;

$task1 = Task::createFromParams($xoopsDB, array(
	'name' => 'Test for TaskList 1',
	'topic_id' => 1,
	'document_id' => 1,
	'source_lang' => 'ja',
	'target_lang' => 'en',
	'smoothing_achievement' => 10,
	'smoothing_limit_date' => '2010/02/28 10:00:00',
	'smoothing_worker' => 'Koizumi',
	'check_achievement' => 20,
	'check_limit_date' => '2010/02/28 19:00:00',
	'check_worker' => 'Junichiro',
	'creator' => 1,
));
$task1->insert();

$task2 = Task::createFromParams($xoopsDB, array(
	'name' => 'Test for TaskList 2',
	'topic_id' => 1,
	'document_id' => 1,
	'source_lang' => 'fr',
	'target_lang' => 'ko',
	'smoothing_achievement' => 35,
	'smoothing_limit_date' => '2010/02/28 12:00:00',
	'smoothing_worker' => 'Aso',
	'check_achievement' => 45,
	'check_limit_date' => '2010/02/28 13:00:00',
	'check_worker' => 'Taro',
	'creator' => 2,
));
$task2->insert();

$taskList = TaskList::findAll($xoopsDB, array(
	'wheres' => array(),
	'page' => 1,
	'perPage' => 1
));

$count = count($taskList);
if ($count != 1) {
	throw new Exception("expected <1> but was <{$count}>.");
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">findAll() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test countAll() ---------------------</p>' . PHP_EOL;

$tableName = $GLOBALS['xoopsDB']->prefix($mytrustdirname . '_' . Task::tableName);
$sql = "SELECT COUNT(id) FROM {$tableName} WHERE delete_flag = false";
var_dump($sql);
$result = $GLOBALS['xoopsDB']->query($sql);
$record = $GLOBALS['xoopsDB']->fetchRow($result);
$expectedCount = $record[0];
$actualCount = TaskList::countAll($GLOBALS['xoopsDB']);

if ($actualCount != $expectedCount) {
	throw new Exception("expected <{$expectedCount}> but was <{$actualCount}>.");
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">countAll() OK</p>' . PHP_EOL;
?>
