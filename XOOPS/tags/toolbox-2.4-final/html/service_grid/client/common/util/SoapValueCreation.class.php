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

	static function createParallelTexts($parallelTextsArray) {
		$typeName = 'ParallelText';
		$typeNamespace = 'http://langrid.nict.go.jp/ws_1_2/paralleltext/';

		$list = array();
		foreach ($parallelTextsArray as $parallelText) {
			$var = new Langrid_SOAPVar_ParallelText($parallelText[0], $parallelText[1]);
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

class Langrid_SOAPVar_ParallelText {
	function Langrid_SOAPVar_ParallelText($source, $target) {
		$this->source = $source;
		$this->target = $target;
	}
}

?>