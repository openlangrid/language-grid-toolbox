<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a store
// staff to show Q&As to foreign customers at the counter.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$test = @$_GET['test'];
if (!$test) {
	exit;
}
require_once 'test_util.php';
require_once XOOPS_TRUST_PATH."/modules/storefront/test/{$test}.php";
