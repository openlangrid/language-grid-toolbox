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

$task = Task::findById($GLOBALS['xoopsDB'], $id);
if($task -> isOwner($GLOBALS['userId']) || getLoginUserUid() == 1) {
	$task -> delete();
}

redirect_header(XOOPS_MODULE_URL.'/'.$mytrustdirname.'/');
?>
