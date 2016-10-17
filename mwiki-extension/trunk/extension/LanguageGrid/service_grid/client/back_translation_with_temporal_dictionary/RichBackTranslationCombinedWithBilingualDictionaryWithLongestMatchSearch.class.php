<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');

class RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch extends BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch {

	function __construct() {
		$wsdl = 'RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
		$this->_client = new LangridSoapClient($wsdl);
	}
}
?>