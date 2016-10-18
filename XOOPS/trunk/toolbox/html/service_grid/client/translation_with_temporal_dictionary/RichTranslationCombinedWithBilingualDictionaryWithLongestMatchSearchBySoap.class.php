<?php

require_once(dirname(__FILE__).'/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * Rich用辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author jun koyama
 *
 */
class RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearchBySoap extends TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch {

	public function setContext($context) {
		parent::setContext($context);
		$this->createClient($this->getGridId() . ':RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}
}
?>
