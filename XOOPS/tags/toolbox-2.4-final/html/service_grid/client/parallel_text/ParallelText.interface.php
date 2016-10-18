<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

interface ParallelText extends LanguageGrid {
	public function search($headLang, $targetLang, $source, $matchingMethod);
}
?>
