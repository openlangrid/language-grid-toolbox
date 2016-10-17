<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * マルチホップ辞書連携折り返し翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultiSentenceRichCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch 
	extends MultiSentenceCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {
		
	function __construct() {
		$wsdl = 'RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
		$this->_client = new LangridSoapClient($wsdl);
		$this->translator = 'NICTJServer';
	}
}
?>