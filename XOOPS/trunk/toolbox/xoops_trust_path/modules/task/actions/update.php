<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

//$xoopsOption['template_main'] = $mytrustdirname . '_' . 'edit.html';

$taskId = @$_POST['id'];
if ($taskId < 1) {
	exit;

}

$params = $_POST['params'];

// validation
$required = array(
	'smoothing_achievement',
	'smoothing_worker',
	'check_achievement',
	'check_worker',
);
foreach ($required as $key) {
	if (trim($params[$key]) == '') {
		exit;
	}
}

$smoothingDate = $_POST['smoothing_date'];
$smoothingTime = $_POST['smoothing_time'];
$smoothingTimestamp = strtotime("{$smoothingDate} {$smoothingTime}:00");

if (!$smoothingTimestamp) {
	exit;
}

$checkDate = $_POST['check_date'];
$checkTime = $_POST['check_time'];
$checkTimestamp = strtotime("{$checkDate} {$checkTime}:00");

if (!$checkTimestamp) {
	exit;
}

if ($smoothingTimestamp > $checkTimestamp) {
	exit;
}

$params['smoothing_limit_date'] = date('Y/m/d H:i:00', $smoothingTimestamp);
$params['check_limit_date'] = date('Y/m/d H:i:00', $checkTimestamp);

// document's ID
if (@$params['file_id'] < 1) {
	exit;
}

// forum's ID
$forumId = @$params['forum_id'];
if ($forumId) {
	if(!is_numeric($forumId) || $forumId < 1) exit;
}

$params['task_id'] = $taskId;
$params['creator'] = $service->getUserId();

// add history
$history = TaskHistory::createFromParams($GLOBALS['xoopsDB'], $params);
$history->insert();

redirect_header(XOOPS_MODULE_URL . '/' . $mytrustdirname . '/');
