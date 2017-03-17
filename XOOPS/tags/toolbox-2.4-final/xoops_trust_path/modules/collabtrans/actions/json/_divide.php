<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$renderOption['type'] = 'json';	
require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php');
require_once dirname(__FILE__).'/../../class/text-processor.php';

$sourceLang = $_POST['sourceLang'];
$sourceText = $_POST['sourceText'];

$responses= array();
$sentences = preprocessOriginal($sourceLang, $sourceText);
$sentences = htmlspecialchars($sentences);
while($sentences != ''){
	$parsed = get_first_sentence($sourceLang, $sentences);
	$responses[]  = $parsed['first'];
	$sentences = $parsed['remain'];
}

echo json_encode($responses);
?>