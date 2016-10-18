<?php
$folder = null;
if(@$_GET['parentId']) {
	$folder = Folder::findById($_GET['parentId']);
} else {
	$folder = Folder::getRoot();
}

$xoopsTpl->assign('current', $folder);
