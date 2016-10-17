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
	public function __construct() {
		$wsdl = 'RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
		$this->_client = new LangridSoapClient($wsdl);
		$this->translator = 'GoogleTranslate';
	}
}
?>
