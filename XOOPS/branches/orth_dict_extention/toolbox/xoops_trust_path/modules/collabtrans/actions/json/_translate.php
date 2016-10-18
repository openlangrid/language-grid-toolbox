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
$targetLang = $_POST['targetLang'];
$sourceText = $_POST['sourceText'];
$backTranslate = @$_POST['backTranslate'];

$result = null;

$sourceLines = split("\n", $sourceText);
$results = array();
foreach ($sourceLines as $line) {
	$line = unescape_magic_quote($line);

	if($backTranslate) {
		$manager = new TranslationManager();
		$result = $manager -> translateByDefault($targetLang, $sourceLang, $line, TRANSLATION_SET_NAME);
	} else {
		$manager = new TranslationManager();
		$result = $manager -> translateByDefault($sourceLang, $targetLang, $line, TRANSLATION_SET_NAME);
	}
	$results[] = $result['contents'][0] -> result;
}

$response = array(
	"status" => "OK",
	"content" => join("\n", $results)
);

//header('Content-Type: application/json; charset=utf-8;');

echo json_encode($response);

function validate() {

}

function divide($sourceLang, $sourceText) {
	$contents= array();
	$sentences = preprocessOriginal($sourceLang, $sourceText);
	while($sentences != ''){
		$parsed = get_first_sentence($sourceLang, $sentences);
		$contents[]  = $parsed['first'];
		$sentences = $parsed['remain'];
	}
	return $contents;
}
?>