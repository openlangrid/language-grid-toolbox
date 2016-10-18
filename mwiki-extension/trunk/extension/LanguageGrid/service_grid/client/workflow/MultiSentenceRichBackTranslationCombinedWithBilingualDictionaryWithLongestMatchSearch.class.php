<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 複数行辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultiSentenceRichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch
	extends MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {

	function __construct() {
		$wsdl = 'http://landev.nict.go.jp/langrid-composite-service-1.2/services/RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl';
//		$wsdl = 'http://landev.nict.go.jp/langrid-composite-service-1.2/services/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl';
		$this->_client = new LangridSoapClient($wsdl);
	}
}
?>