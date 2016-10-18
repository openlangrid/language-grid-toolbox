<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

interface ExampleBasedTranslator extends LanguageGrid {

	public function createToken($sourceLang, $targetLang);

	public function destroyToken($token);

	public function addParallelText($token, $sourceLang, $targetLang, $parallelTexts);

	public function removeParallelText($token, $sourceLang, $targetLang, $headWord, $matchingMethod);

	public function searchParallelText($token, $sourceLang, $targetLang, $headWord, $matchingMethod);

	public function getStatus($token);
}
?>
