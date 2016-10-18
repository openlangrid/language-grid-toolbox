<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

interface TemplateParallelText extends LanguageGrid {
	public function getCategoryNames($categoryId, $languages);

	public function listTemplateCategories($language);

	public function searchTemplates($language, $text, $matchingMethod, $categoryIds);

	public function getTemplatesByTemplateId($language, $templateIds);

	public function generateSentence($language, $templateId, $boundChoiceParameters, $boundRangeParameters);
}
?>
