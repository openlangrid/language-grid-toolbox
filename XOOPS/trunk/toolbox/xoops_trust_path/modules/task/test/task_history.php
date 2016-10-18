<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';

require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/task_history.php';

echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;

$taskHistory = TaskHistory::createFromParams($xoopsDB, array(
	'task_id' => 1,
	'topic_id' => 1,
	'document_id' => 1,
	'smoothing_achievement' => 0,
	'smoothing_limit_date' => '2010/02/28 19:00:00',
	'smoothing_worker' => 'Smoothing Worker',
	'check_achievement' => 0,
	'check_limit_date' => '2010/03/01 23:00:00',
	'check_worker' => 'Check Worker',
	'creator' => 1
));
if (!($taskHistory instanceof TaskHistory)) {
	throw new Exception('Not True.');
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">createFromParams() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test insert() and findById() ----------------</p>' . PHP_EOL;

if (!$taskHistory -> insert()) {
	throw new Exception('Failed to insert. '.$xoopsDB->error());
}
$id = $taskHistory -> getId();
$taskHistory2 = TaskHistory::findById($xoopsDB, $id);

if (!($taskHistory2 instanceof TaskHistory)) {
	throw new Exception('$taskHistory2 is not a TaskHistory.');
}
if ($id != $taskHistory2->getId()) {
	throw new Exception("Invalid ID. Expected was <{$id}> but was <{$taskHistory2->getId()}>.");
}
if ($taskHistory2->getSmoothingAchievement() != 0) {
	throw new Exception("Invalid smoothing_achievement. Expected was <0> but was <{$taskHistory2->getSmoothingAchievement()}>.");
}
if ($taskHistory2->getSmoothingLimitDate() != '2010/02/28') {
	throw new Exception("Invalid smoothing_limit_date. Expected was <2010/02/28> but was <{$taskHistory2->getSmoothingLimitDate()}>.");
}
if ($taskHistory2->getSmoothingLimitTime() != '19:00') {
	throw new Exception("Invalid smoothing_limit_time. Expected was <19:00> but was <{$taskHistory2->getSmoothingLimitTime()}>.");
}
if ($taskHistory2->getSmoothingWorker() != 'Smoothing Worker') {
	throw new Exception("Invalid smoothing_worker. Expected was <Smoothing Worker> but was <{$taskHistory2->getSmoothingWorker()}>.");
}
if ($taskHistory2->getCheckAchievement() != 0) {
	throw new Exception("Invalid check_achievement. Expected was <0> but was <{$taskHistory2->getCheckAchievement()}>.");
}
if ($taskHistory2->getCheckLimitDate() != '2010/03/01') {
	throw new Exception("Invalid check_limit_date. Expected was <2010/03/01> but was <{$taskHistory2->getCheckLimitDate()}>.");
}
if ($taskHistory2->getCheckLimitTime() != '23:00') {
	throw new Exception("Invalid check_limit_time. Expected was <23:00> but was <{$taskHistory2->getCheckLimitTime()}>.");
}
if ($taskHistory2->getCheckWorker() != 'Check Worker') {
	throw new Exception("Invalid check_worker. Expected was <Check Worker> but was <{$taskHistory2->getCheckWorker()}>.");
}
if ($taskHistory2->getCreator() != 1) {
	throw new Excepion("Invalid creator. Expected was <1> but was <{$taskHistory2->getCreator()}>.");
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">insert() and findById() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test isOwner() ----------------</p>' . PHP_EOL;

if (!$taskHistory2->isOwner(1)) {
	throw new Exception('Invalid return value. Expected was false but was true.');
}
if ($taskHistory2->isOwner(2)) {
	throw new Exception('Invalid return value. Expected was true but was false.');
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">isOwner() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test setAttributes() and update() -----------</p>' . PHP_EOL;

$taskHistory2->setAttributes(array(
	'smoothing_achievement' => 60,
	'smoothing_limit_date' => '2010/03/03 12:00:00',
	'smoothing_worker' => 'Smoothing Worker2',
	'check_achievement' => 10,
	'check_limit_date' => '2010/03/07 18:00:00',
	'check_worker' => 'Check Worker2',
	'modifier' => 3
));
if (!$taskHistory2->update()) {
	throw new Exception('Failed to update.');
}

$taskHistory3 = TaskHistory::findById($xoopsDB, $id);
if (!($taskHistory3 instanceof TaskHistory)) {
	throw new Exception('$taskHistory3 is not a TaskHistory.');
}
if ($id != $taskHistory3->getId()) {
	throw new Exception("Invalid ID. Expected was <{$id}> but was <{$taskHistory3->getId()}>.");
}
if ($taskHistory3->getSmoothingAchievement() != 60) {
	throw new Exception("Invalid smoothing_achievement. Expected was <60> but was <{$taskHistory3->getSmoothingAchievement()}>.");
}
if ($taskHistory3->getSmoothingLimitDate() != '2010/03/03') {
	throw new Exception("Invalid smoothing_limit_date. Expected was <2010/03/03> but was <{$taskHistory3->getSmoothingLimitDate()}>.");
}
if ($taskHistory3->getSmoothingLimitTime() != '12:00') {
	throw new Exception("Invalid smoothing_limit_time. Expected was <12:00> but was <{$taskHistory3->getSmoothingLimitTime()}>.");
}
if ($taskHistory3->getSmoothingWorker() != 'Smoothing Worker2') {
	throw new Exception("Invalid smoothing_worker. Expected was <Smoothing Worker2> but was <{$taskHistory3->getSmoothingWorker()}>.");
}
if ($taskHistory3->getCheckAchievement() != 10) {
	throw new Exception("Invalid check_achievement. Expected was <10> but was <{$taskHistory3->getCheckAchievement()}>.");
}
if ($taskHistory3->getCheckLimitDate() != '2010/03/07') {
	throw new Exception("Invalid check_limit_date. Expected was <2010/03/07> but was <{$taskHistory3->getCheckLimitDate()}>.");
}
if ($taskHistory3->getCheckLimitTime() != '18:00') {
	throw new Exception("Invalid check_limit_time. Expected was <18:00> but was <{$taskHistory3->getCheckLimitTime()}>.");
}
if ($taskHistory3->getCheckWorker() != 'Check Worker2') {
	throw new Exception("Invalid check_worker. Expected was <Check Worker2> but was <{$taskHistory3->getCheckWorker()}>.");
}
if ($taskHistory3->getModifier() != 3) {
	throw new Excepion("Invalid creator. Expected was <3> but was <{$taskHistory3->getModifier()}>.");
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">setAttributes() and update() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test setParam() -----------------------------</p>' . PHP_EOL;

$taskHistory3->setParam(array(
	'id' => 20,
	'smoothing_achievement' => 80,
	'smoothing_limit_date' => '2010/01/15 11:00:00',
	'smoothing_worker' => 'setParam',
	'check_achievement' => 30,
	'check_limit_date' => '2010/01/16 12:00:00',
	'check_worker' => 'Param setter',
	'modifier' => 13
));
if ($taskHistory3->update() != 1) {
	throw new Exception('Updated more 1 rows.');
}

$taskHistory4 = TaskHistory::findById($xoopsDB, $id);
if (!($taskHistory4 instanceof TaskHistory)) {
	throw new Exception('$taskHistory4 is not a TaskHistory.');
}
if ($id != $taskHistory4->getId()) {
	throw new Exception("Invalid ID. Expected was <{$id}> but was <{$taskHistory4->getId()}>.");
}
if ($taskHistory4->getSmoothingAchievement() != 80) {
	throw new Exception("Invalid smoothing_achievement. Expected was <80> but was <{$taskHistory4->getSmoothingAchievement()}>.");
}
if ($taskHistory4->getSmoothingLimitDate() != '2010/01/15') {
	throw new Exception("Invalid smoothing_limit_date. Expected was <2010/01/15> but was <{$taskHistory4->getSmoothingLimitDate()}>.");
}
if ($taskHistory4->getSmoothingLimitTime() != '11:00') {
	throw new Exception("Invalid smoothing_limit_time. Expected was <11:00> but was <{$taskHistory4->getSmoothingLimitTime()}>.");
}
if ($taskHistory4->getSmoothingWorker() != 'setParam') {
	throw new Exception("Invalid smoothing_worker. Expected was <setParam> but was <{$taskHistory4->getSmoothingWorker()}>.");
}
if ($taskHistory4->getCheckAchievement() != 30) {
	throw new Exception("Invalid check_achievement. Expected was <30> but was <{$taskHistory4->getCheckAchievement()}>.");
}
if ($taskHistory4->getCheckLimitDate() != '2010/01/16') {
	throw new Exception("Invalid check_limit_date. Expected was <2010/01/16> but was <{$taskHistory4->getCheckLimitDate()}>.");
}
if ($taskHistory4->getCheckLimitTime() != '12:00') {
	throw new Exception("Invalid check_limit_time. Expected was <12:00> but was <{$taskHistory4->getCheckLimitTime()}>.");
}
if ($taskHistory4->getCheckWorker() != 'Param setter') {
	throw new Exception("Invalid check_worker. Expected was <Param setter> but was <{$taskHistory4->getCheckWorker()}>.");
}
if ($taskHistory4->getModifier() != 13) {
	throw new Excepion("Invalid creator. Expected was <13> but was <{$taskHistory4->getModifier()}>.");
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">setParam() OK</p>' . PHP_EOL;

echo '<p>---------------------------- Test findAll() -----------------------------</p>' . PHP_EOL;

$taskHistorys = TaskHistory::findAll($xoopsDB);

$pdo = new PDO('mysql:host='.XOOPS_DB_HOST.';dbname='.XOOPS_DB_NAME, XOOPS_DB_USER, XOOPS_DB_PASS);
$sql = 'SELECT COUNT(id) FROM ' . $xoopsDB->prefix($mytrustdirname.'_histories') . ' WHERE delete_flag = false';
var_dump($sql);
$statement = $pdo->prepare($sql);
$statement->execute();
$count = $statement->fetchColumn();

if (count($taskHistorys) != $count) {
	throw new Exception('count($taskHistorys):' . count($taskHistorys) . ' != $count:' . $count);
}

foreach ($taskHistorys as $history) {
	if (!($history instanceof TaskHistory)) {
		throw new Exception('findAll() returned an array contains instances that are not TaskHistory.');
	}
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">findAll() OK</p>' . PHP_EOL;
?>
