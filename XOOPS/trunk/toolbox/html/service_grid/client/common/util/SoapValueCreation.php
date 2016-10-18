<?php
class SoapValueCreation {
	function SoapValueCreation() {

	}

	/**
	 * <#if lang="ja">
	 * TODO: 引数の形式は、***とりあえず*** array(array([headWord], [targetWord]), array(...), ...)としておく。
	 * </#if>
	 */
	static function createTemporalDictionaries($temporalDictionaryArray) {
		$typeName = 'Translation';
		$typeNamespace = 'http://langrid.nict.go.jp/ws_1_2/bilingualdictionary/';

		$list = array();
		foreach ($temporalDictionaryArray as $pair) {
			$var = new Langrid_SOAPVar_Translation($pair[0], array($pair[1]));
			$list[] = new SoapVar($var, SOAP_ENC_OBJECT, $typeName, $typeNamespace);
		}
		return $list;
	}
}

/**
 * <#if lang="ja">
 * テンポラル辞書の１レコードを表現
 * </#if>
 */
class Langrid_SOAPVar_Translation {
	function Langrid_SOAPVar_Translation($headWord, $targetWords) {
		$this->headWord = $headWord;
		$this->targetWords = $targetWords;
	}
}

?>