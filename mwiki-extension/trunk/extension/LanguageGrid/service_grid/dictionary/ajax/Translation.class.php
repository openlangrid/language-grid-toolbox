<?php

require_once dirname(__FILE__).'/../class/TranslatorAdapter.class.php';

class Translation extends LanguageGridAjaxRunner {

	function dispatch($action, $params) {
		$params = $this->getParameters($params);

		$sourceLang = $params['sourceLanguage'];
		$targetLang = $params['targetLanguage'];
		$source = $params['source'];
		$title = Title::makeTitle(0, $params['title']);

		$translator = new TranslatorAdapter($title);
		return $translator->translate($sourceLang, $targetLang, $source);
	}
}
?>
