<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$params = $_POST['params'];

// validation
$required = array(
	'name',
	'source_lang',
	'target_lang',
	'smoothing_worker',
	'check_worker',
);
foreach ($required as $key) {
	if (trim($params[$key]) == '') {
		exit;
	}
}

foreach (array('smoothing_date', 'check_date') as $key) {
	list($year, $month, $day) = explode('/', $_POST[$key]);
	if (!checkdate($month, $day, $year)) {
		exit;
	}
}

foreach (array('smoothing_time', 'check_time') as $key) {
	if (preg_match('/^\d{2}:\d{2}$/', $_POST[$key]) != 1) {
		exit;
	}
}

$smoothingDate = sprintf('%s %s:00', $_POST['smoothing_date'], $_POST['smoothing_time']);
$checkDate = sprintf('%s %s:00', $_POST['check_date'], $_POST['check_time']);
if (!($smoothingDate <= $checkDate)) {
	exit;
}

if (@$params['file_id'] < 1) {
	exit;
}

$params['smoothing_limit_date'] = $smoothingDate;
$params['check_limit_date'] = $checkDate;
$params['creator'] = $service->getUserId();

$task = Task::createFromParams($GLOBALS['xoopsDB'], $params);
$task -> insert();

redirect_header(XOOPS_MODULE_URL.'/'.$mytrustdirname.'/');
?>
