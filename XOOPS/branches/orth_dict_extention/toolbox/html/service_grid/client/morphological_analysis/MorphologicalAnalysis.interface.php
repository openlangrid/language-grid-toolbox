<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

interface MorphologicalAnalysis extends LanguageGrid {
	public function analyze($language, $text);
}
?>
