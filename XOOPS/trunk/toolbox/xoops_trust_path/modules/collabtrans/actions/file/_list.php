<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
if(@$_GET['parentId']) {
	$xoopsTpl->assign('current', Folder::findById($_GET['parentId']));
	$folder = Folder::findById($_GET['parentId']);

	if ($folder) {
		$xoopsTpl->assign('parentFolder', $folder);			
	} else {
		$xoopsTpl->assign('parentFolder', Folder::getRoot());
	}
	$xoopsTpl->assign('userInfo', new ServiceInfo());
} else {
	$xoopsTpl->assign('current', Folder::getRoot());
	$folder = Folder::getRoot();

	if ($folder) {
		$xoopsTpl->assign('parentFolder', $folder);
	}
	$xoopsTpl->assign('userInfo', new ServiceInfo());	
}
?>