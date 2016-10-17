<?php

require_once(dirname(__FILE__).'/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author jun
 *
 */
class TranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers extends TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch {

	protected $_client = null;
	protected $translator = null;
	protected $morphologicalAnalyzer = "DefaultMorphologicalAnalysis";
	protected $bilingualDictionaries = array();
//	protected $context;
	
	function __construct() {
	}

	function _translate() {
		$options = $this->context->getOptions();
		$serviceFullQualifiedName = 'translationwithtemporaldictionary';
		$methodResponders = array('translate' => Message_TranslationWithTemporalDictionary_TranslateResponse::name());
		$this->_client = new LangridPbClient($serviceFullQualifiedName, $methodResponders, $options);
		$this->translator = 'GoogleTranslate';
		$this->_makeBinding();
		
		$requestMessage = new Message_TranslationWithTemporalDictionary_TranslateRequest();
		$requestMessage->sourceLang = $this->context->getSourceLang();
		$requestMessage->targetLang = $this->context->getTargetLang();
		$requestMessage->source = $this->context->getSource();
		$requestMessage->temporalDictionary = $this->getTemporalDict();
		// $requestMessage->dictionaryTargetLang = $this->context->getDictTargetLang();
		$requestMessage->dictionaryTargetLang = $this->context->getTargetLang();

		$pbResponse = $this->_client->invokeService('translate', array($requestMessage));

		return $pbResponse;
	}

	private function getTemporalDict() {
		$dicts = $this->context->getTemporalDict();

		$return = array();

		foreach ($dicts as $dict) {
			$d = new Message_BilingualDictionary_Translation();
			$d->headWord = $dict->enc_value->headWord;
			$d->targetWords = $dict->enc_value->targetWords;
			$return[] = $d;
		}

		return $return;
	}
}
?>
