<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$renderOption['type'] = 'noheader';
include(dirname(__FILE__). '/../include_header_resources.php');
include(dirname(__FILE__)).'/_list.php';
include(dirname(__FILE__)).'/_new.php';


$xoopsTpl -> assign('langs', CommonUtil::toLangugePairs(array(
	$_GET['sourceLang'], $_GET['targetLang']
)));
?>