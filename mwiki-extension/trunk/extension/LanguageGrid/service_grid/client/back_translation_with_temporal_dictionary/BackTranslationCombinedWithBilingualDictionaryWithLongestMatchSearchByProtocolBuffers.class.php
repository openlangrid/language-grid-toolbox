<?php

require_once(dirname(__FILE__).'/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
require_once(dirname(__FILE__).'/../common/LangridPbClient.class.php');
require_once(dirname(__FILE__).'/../common/pbmessage/BackTranslationWithTemporalDictionaryMessage.class.php');
require_once(dirname(__FILE__).'/../../config/ServiceGridConfig.class.php');
/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 折り返し辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author jun koyama
 *
 */
class BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers extends BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch {

//	protected $_client = null;
	protected $translator = null;
	protected $morphologicalAnalyzer = "DefaultMorphologicalAnalysis";
	protected $bilingualDictionaries = array();
	
	function __construct() {
	}

	function _translate() {
		$options = $this->context->getOptions();
		$serviceFullQualifiedName = 'backtranslationwithtemporaldictionary';			
		$methodResponders = array('backTranslate' => Message_BackTranslationWithTemporalDictionary_TranslateResponse::name());
		$this->_client = new LangridPbClient($serviceFullQualifiedName, $methodResponders, $options);
		$a = $this->context->getServiceIds();
		$b = $this->context->getReverseServiceIds();
		error_log('### Service ID 1 ###'.print_r($a, true));
		error_log('### Service ID 2 ###'.print_r($b, true));
		$this->forwardServiceId = $a[0];
		$this->backwardServiceId = $b[0];
		
		$this->_makeBinding();
		
		$requestMessage = new Message_BackTranslationWithTemporalDictionary_TranslateRequest();
		$requestMessage->sourceLang = $this->context->getSourceLang();
		$requestMessage->intermediateLang = $this->context->getIntermediateLang();
		$requestMessage->source = $this->context->getSource();
		$requestMessage->temporalDictionary = $this->getTemporalDict();
		$requestMessage->dictionaryTargetLang = $this->context->getIntermediateLang();
		$pbResponse = $this->_client->invokeService('backTranslate', array($requestMessage));
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
