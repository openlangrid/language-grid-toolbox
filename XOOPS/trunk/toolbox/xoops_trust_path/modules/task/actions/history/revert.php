<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$id = @$_POST['id'];
if ($id < 1) {
	exit;
}

$historyId = @$_POST['history_id'];
if ($historyId < 1) {
	exit;
}

$history = TaskHistory::findById($GLOBALS['xoopsDB'], $historyId);
if($history->isOwner($GLOBALS['userId'])) {
	$history->revert();
}

redirect_header(XOOPS_MODULE_URL . '/' . $mytrustdirname . "/history/?id={$id}");
?>
