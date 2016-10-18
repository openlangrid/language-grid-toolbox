<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

interface BilingualDictionary extends LanguageGrid {
	public function search($headLang, $targetLang, $headWord, $matchingMethod);
}
?>
