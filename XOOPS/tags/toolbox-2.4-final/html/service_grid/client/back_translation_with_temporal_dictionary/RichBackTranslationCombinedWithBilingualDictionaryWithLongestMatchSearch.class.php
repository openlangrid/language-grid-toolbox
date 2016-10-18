<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

class RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch extends BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch {

	public function setContext($context) {
		parent::setContext($context);
		$this->createClient($this->getGridId()
		. ':RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}
}
?>