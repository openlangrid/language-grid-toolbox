<?php
require_once(dirname(__FILE__).'/ServiceGridContext.class.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationServiceSetting.class.php');
require_once(dirname(__FILE__).'/client/common/util/SourceTextJoinStrategyType.class.php');
require_once(dirname(__FILE__).'/client/common/MetaTranslator.class.php');
require_once(dirname(__FILE__).'/client/common/validator/SkipTranslation_Validator.class.php');
/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * Service Gridへアクセスするラッパ
 * </#if>
 * @author Jun Koyama
 */
class ServiceGridClient {
	protected $options = null;
 	function __construct($options = array()) {
 		$this->options = $options;
 	}
	public function metaTranslate($transFunc, $sourceLang, $targetLang, $source, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array()) {
		$param = array($sourceLang , $targetLang, $source, $translationBindingSetId, $translationBindingSetName, $temporalDictIds);
		$mt = new MetaTranslator($sourceLang);
		$translationParam = array($sourceLang , $targetLang, $source, $translationBindingSetId, $translationBindingSetName);
		$parameterOrder = array(0, 1, 2);
		$result = $mt->metaTranslate($transFunc, $translationParam, $parameterOrder, true, true, null, null);
		return $result;
	}
 	public function translate($sourceLang, $targetLang, $source, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array()) {
		$context = $this->createServiceGridContext($sourceLang, $targetLang, null, $source, null, $translationBindingSetId, $translationBindingSetName, $temporalDictIds);
		$translator = $context->getTranslator();
        if (is_null($translator)) {
            return null;
        }
		$validate = $this->validateSkipTags(array($source));
		if ($validate === false) {
			return $this->getWarningResponsePayload('Invalid tag format.');
		}
		$result = $translator->invoke();
		return $result;
	}
	public function backTranslate($sourceLang, $intermediateLang, $source, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array()) {
		$context = $this->createServiceGridContext($sourceLang, null, $intermediateLang, $source, null, $translationBindingSetId, $translationBindingSetName, $temporalDictIds);
		$translator = $context->getTranslator();
		$validate = $this->validateSkipTags(array($source));
		if ($validate === false) {
			return $this->getWarningResponsePayload('Invalid tag format.');
		}
		$result = $translator->invoke();
		return $result;
	}
	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array(), $sourceTextJoinStrategy = SourceTextJoinStrategyType::Normal) {
		$context = $this->createServiceGridContext($sourceLang, $targetLang, null, null, $sourceArray, $translationBindingSetId, $translationBindingSetName, $temporalDictIds, $sourceTextJoinStrategy);
		$translator = $context->getTranslator();
		$validate = $this->validateSkipTags($sourceArray);
		if ($validate === false) {
			return $this->getWarningResponsePayload('Invalid tag format.');
		}
		$result = $translator->invoke();
		return $result;
	}
	public function multisentenceBackTranslate($sourceLang, $intermediateLang, $sourceArray, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array(), $SourceTextJoinStrategy = SourceTextJoinStrategyType::Normal) {
		$context = $this->createServiceGridContext($sourceLang, null, $intermediateLang, null, $sourceArray, $translationBindingSetId, $translationBindingSetName, $temporalDictIds);
		$translator = $context->getTranslator();
		$validate = $this->validateSkipTags($sourceArray);
		if ($validate === false) {
			return $this->getWarningResponsePayload('Invalid tag format.');
		}
		$result = $translator->invoke();
		return $result;
	}
	public function getSupportedTranslationPathLanguagePairs($bindingSetId) {
		if ($bindingSetId == 0) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$settings = new ServiceGridTranslationServiceSetting();
		$set = $settings->getTranslationSetBySetId($bindingSetId);
		if ($set == null) {
			return $this->getErrorResponsePayload('BindingSetName is not found.');
		}
		$paths = $set->getTranslationPaths();
		if ($paths == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($paths as $path) {
			$ret[] = array($path->getSourceLang(), $path->getTargetLang());
		}
		return $this->getResponsePayload($ret);
	}
	protected function getResponsePayload($contents=array(), $status='OK', $message='NoError') {
		$response = array('status'=>$status, 'message'=>$message, 'contents'=>$contents);
		return $response;
	}
	protected function getErrorResponsePayload($message, $status='Error') {
		$response = array('status'=>$status, 'message'=>$message, 'contents'=>'');
		return $response;
	}
	protected function getAccessUser() {
		return $this->uid = 0;
	}
	protected function getWarningResponsePayload($message, $status='WARNING') {
		$contents = new stdClass();
		$contents->intermediate = '';
		$contents->target = '';
		$response = array('status'=>$status, 'message'=>$message, 'contents'=>$contents, 'LicenseInformation'=>array());
		return $response;
	}
	
	protected function validateSkipTags($sources) {
		foreach ($sources as $s) {
			if (SkipTranslation_Validator::validSkipTag($s) === false) {
				return false;
			}
		}
		return true;
	}
	protected function createServiceGridContext(
		$sourceLang, $targetLang, $intermediateLang, $source, $sourceArray,
		$translationBindingSetId = null, $translationBindingSetName = null,
		$temporalDictIds = array(), $sourceTextJoinStrategy = SourceTextJoinStrategyType::Normal) {
		$context = new ServiceGridContext();
		$context->setSourceLang($sourceLang);
		$context->setTargetLang($targetLang);
		$context->setIntermediateLang($intermediateLang);
		$context->setSource($source);
		$context->setSourceArray($sourceArray);
		$context->setTranslationBindingId($translationBindingSetId);
		$context->setTranslationBindingName($translationBindingSetName);
		$context->setTemporalDictIds($temporalDictIds);
		$context->setSourceTextJoinStrategyType($sourceTextJoinStrategy);
		$context->setOptions($this->options);
		return $context;
	}
}
?>
