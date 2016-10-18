<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';

require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/task.php';

echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;

$task = Task::createFromParams($xoopsDB, array(
	'name' => 'ユニットテスト',
	'topic_id' => 1,
	'file_id' => 1,
	'source_lang' => 'ja',
	'target_lang' => 'en',
	'smoothing_achievement' => 20,
	'smoothing_limit_date' => '2010/01/01 10:00',
	'smoothing_worker' => 'worker',
	'check_achievement' => 10,
	'check_limit_date' => '2010/01/02 19:00',
	'check_worker' => 'checker',
	'creator' => 1
));
if (!($task instanceof Task)) {
	throw new Exception('Not True.');
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">createFromParams() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test insert() and findById() ----------------</p>' . PHP_EOL;

if (($historyId = $task -> insert()) < 1) {
	throw new Exception('Failed to insert.'.$xoopsDB->error());
}

$id = $task -> getId();
$task2 = Task::findById($xoopsDB, $id);

if (!($task2 instanceof Task)) {
	throw new Exception('$task2 is not a Task.');
}
if ($id != $task2->getId()) {
	throw new Exception("Invalid ID. ".__LINE__);
}
if ($task2->getName() != 'ユニットテスト') {
	throw new Exception(__LINE__);
}
if ($task2->getSourceLang() != 'ja') {
	throw new Exception(__LINE__);
}
if ($task2->getTargetLang() != 'en') {
	throw new Exception(__LINE__);
}
if ($task2->getCreator() != 1) {
	throw new Exception(__LINE__);
}

$taskHistory = TaskHistory::findById($xoopsDB, $historyId);
if ($taskHistory->getSmoothingAchievement() != 0) {
	throw new Exception(__LINE__);
}
if ($taskHistory->getSmoothingLimitDate() != '2010/01/01') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getSmoothingLimitTime() != '10:00') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getSmoothingWorker() != 'worker') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getCheckAchievement() != 0) {
	throw new Exception(__LINE__);
}
if ($taskHistory->getCheckLimitDate() != '2010/01/02') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getCheckLimitTime() != '19:00') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getCheckWorker() != 'checker') {
	throw new Exception(__LINE__);
}
if ($taskHistory->getCreator() != 1) {
	throw new Exception();
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">insert() and findById() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test isOwner() ----------------</p>' . PHP_EOL;

if (!$task2->isOwner(1)) {
	throw new Exception(__LINE__);
}
if ($task2->isOwner(2)) {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">isOwner() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test setAttributes() and update() -----------</p>' . PHP_EOL;

$task2->setAttributes(array(
	'name' => 'test update()',
	'source_lang' => 'French',
	'target_lang' => 'Spanish',
	'modifier' => 3
));
if (!$task2->update()) {
	throw new Exception(__LINE__);
}

$task3 = Task::findById($xoopsDB, $id);
if (!($task3 instanceof Task)) {
	throw new Exception(__LINE__);
}
if ($id != $task3->getId()) {
	throw new Exception(__LINE__);
}
if ($task3->getName() != 'test update()') {
	throw new Exception(__LINE__);
}
if ($task3->getSourceLang() != 'French') {
	throw new Exception();
}
if ($task3->getTargetLang() != 'Spanish') {
	throw new Exception(__LINE__);
}
if ($task3->getModifier() != 3) {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">setAttributes() and update() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test setParam() -----------------------------</p>' . PHP_EOL;

$task3->setParam($param = array(
	'id' => 20,
	'name' => 'test setParam()',
	'source_lang' => 'Chinese',
	'target_lang' => 'Korean',
	'modifier' => 13
));
if (!$task3->update()) {
	throw new Exception(__LINE__);
}
$task4 = Task::findById($xoopsDB, $id);

if (!($task4 instanceof Task)) {
	throw new Exception(__LINE__);
}
if ($id != $task4->getId()) {
	throw new Exception(__LINE__);
}
if ($task4->getName() != 'test setParam()') {
	throw new Exception(__LINE__);
}
if ($task4->getSourceLang() != 'Chinese') {
	throw new Exception(__LINE__);
}
if ($task4->getTargetLang() != 'Korean') {
	throw new Exception(__LINE__);
}
if ($task4->getModifier() != 13) {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">setParam() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test delete() -----------------------------</p>' . PHP_EOL;

$affected = $task4->delete();
if ($affected != 1) {
	throw new Exception(__LINE__);
}
$nullTask = Task::findById($xoopsDB, $id);
if (!is_null($nullTask)) {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">delete() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test findAll() -----------------------------</p>' . PHP_EOL;

$tasks = Task::findAll($xoopsDB);

$tableName = $xoopsDB->prefix($mytrustdirname.'_tasks');
$sql = "SELECT COUNT(id) FROM {$tableName} WHERE delete_flag = false";
var_dump($sql);
$result = $xoopsDB->query($sql);
$record = $xoopsDB->fetchRow($result);
$count = $record[0];
if (count($tasks) != $count) {
	throw new Exception(__LINE__);
}

foreach ($tasks as $task) {
	if (!($task instanceof Task)) {
		throw new Exception(__LINE__);
	}
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">findAll() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test SQL Injection -----------------------------</p>' . PHP_EOL;

$task10 = Task::createFromParams($xoopsDB, array(
	'name' => ")",
	'topic_id' => 1,
	'file_id' => 1,
	'source_lang' => 'ja',
	'target_lang' => 'en',
	'smoothing_worker' => 'worker',
	'check_worker' => 'checker',
	'creator' => 1
));
$task10->insert();

$options = array(
	'wheres' => array(
		'source_lang' => "OR TRUE"
	)
);
$tasks = Task::findAll($xoopsDB, $options);

$sql = "SELECT COUNT(id) FROM {$tableName} WHERE delete_flag = false AND source_lang = 'OR TRUE'";
$result = $xoopsDB->query($sql);
$record = $xoopsDB->fetchRow($result);
$count = $record[0];
if (count($tasks) != $count) {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SQL Injection failed (OK)</p>' . PHP_EOL;
?>
