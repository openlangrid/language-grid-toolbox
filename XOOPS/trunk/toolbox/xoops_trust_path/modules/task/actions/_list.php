<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

if (!is_array(@$_GET['params'])) {
	$_GET['params'] = array();
}

// wheres
$wheres = $_GET['params'];

foreach ($wheres as $key => $value) {
	$param = trim($value);
	if (empty($param)) {
		unset($wheres[$key]);
	}
}

if (isset($wheres['name'])) {
	$value = $wheres['name'];
	switch (@$_GET['searchMethod']) {
		case 0:
			// part
			$value = "%{$value}%";
			break;
		case 1:
			// prefix
			$value = "{$value}%";
			break;
		case 2:
			// suffix
			$value = "%{$value}";
			break;
		case 3:
			// nothing to do
			break;
		default:
			exit;
	}

	$wheres['name'] = array(
		'symbol' => 'like',
		'value' => $value
	);
}
// status
$achievement = trim(@$_GET['achievement']);
$work = @$_GET['work'];
$symbolMap = CommonUtil::getSymbolMapCache();
$symbol = @$symbolMap[@$_GET['symbol']];
if (is_numeric($achievement) && is_numeric($work) && !is_null($symbol)) {
	$ary = array(
		'symbol' => $symbol,
		'value' => $achievement
	);

	switch ($work) {
		case 0:
			$wheres['smoothing_achievement'] = $ary;
			break;
		case 1:
			$wheres['check_achievement'] = $ary;
			break;
	}
}

$orderby = urldecode(@$_GET['orderby']);
if (!$orderby) {
	$orderby = '12 DESC';
}

// pager (TaskList)
$options = array(
	'wheres' => $wheres,
	'page' => @$_GET['page'],
	'perPage' => @$_GET['perPage'],
	'orderby' => $orderby
);

$taskList = TaskList::findWithAdvancedSerch($options);
$tasks = $taskList->getTasks();

$itemCount = $taskList->getMinNumber() . ' - ' . $taskList->getMaxNumber() . ' of ' . $taskList->countAll();

$perPages = array(
	'5' => 5,
	'10' => 10,
	'20' => 20,
	'50' => 50,
	'0' => _MD_TASK_ALL
);

// pull down menu
$pullDownMenus = array();
foreach ($tasks as $task) {
	$tmp = array();
	$tmp[] = '<a href="' . XOOPS_MODULE_URL . '/' . $mytrustdirname . '/?action=edit&id=' . $task->getId() . '">' . _MD_TASK_EDIT . '</a>' . PHP_EOL;
	$tmp[] = '<a href="' . XOOPS_MODULE_URL . '/' . $mytrustdirname . '/history/?id=' . $task->getId() . '">' . _MD_TASK_HISTORY . '</a>' . PHP_EOL;
	if ($task->isOwner($GLOBALS['userId'])|| getLoginUserUid() == 1) {
		$tmp[] = '<a href="' . XOOPS_MODULE_URL . '/' . $mytrustdirname . '/?action=delete&id=' . $task->getId() . '" class="deleteLink">' . _MD_TASK_DELETE . '</a>' . PHP_EOL;
	}
	$pullDownMenus[] = $tmp;
}

$listUrl = "{$xoopsTpl->get_template_vars('mod_url')}/?action=_list";

// query string
$hash = $_GET;
foreach ($hash['params'] as $key => $value) {
	$hash["params[{$key}]"] = $value;
}
$queryString = CommonUtil::toQueryString($hash, array('params', 'action', 'page', 'perPage', 'orderby'));
if (strlen($queryString) > 0) {
	$listUrl .= "&{$queryString}";
}

//sort order
$sortOrders = array(
	'ASC' => '&uarr;',
	'DESC' => '&darr;'
);

$orderArray = explode(" ",$orderby);

$sortKey = strtoupper($orderArray[0]);
$order = $orderArray[1];
$titles = array(
	'2' => array('label' =>_MD_TASK_NAME, 'sortOrder' => $order, 'arrow' => '' ),
	'3' => array('label' =>_MD_TASK_SOURCE_LANG, 'sortOrder' => $order, 'arrow' => '' ),
	'4' => array('label' =>_MD_TASK_TARGET_LANG, 'sortOrder' => $order, 'arrow' => '' ),
	'5' => array('label' =>_MD_TASK_SMOOTHING, 'sortOrder' => $order, 'arrow' => '' ),
	'8' => array('label' =>_MD_TASK_CHECK, 'sortOrder' => $order, 'arrow' => '' ),
	'11' => array('label' =>_MD_TASK_CREATOR, 'sortOrder' => $order, 'arrow' => '' ),
	'12' => array('label' =>_MD_TASK_UPDATE_DATE, 'sortOrder' => $order, 'arrow' => '' ),
);

$titles[$sortKey]['arrow'] = $sortOrders[$order];
$titles[$sortKey]['sortOrder'] = ($titles[$sortKey]['sortOrder'] == 'ASC')?'DESC':'ASC';

$xoopsTpl->assign(array(
	'listUrl' => $listUrl,
	'tasks' => $tasks,
	'pullDownMenus' => $pullDownMenus,
	'pager' => $taskList,
	'itemCount' => $itemCount,
	'perPages' => $perPages,
	'titles' => $titles,
	'orderby' => urlencode($orderby)
));
