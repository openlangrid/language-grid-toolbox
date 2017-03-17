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

$forumId = @$_GET['forumId'];
if ($forumId < 1) {
	exit;
}

$task = Task::findById($GLOBALS['xoopsDB'], $id);
if (!$task->associateWithForum($forumId)) {
	exit('Failed to associate.');
}

redirect_header(XOOPS_MODULE_URL."/communication/?forumId={$forumId}");
