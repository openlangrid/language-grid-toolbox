<?php

require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
require_once(dirname(__FILE__).'/../../handler/GetUserDictionaryContents.class.php');
/**
 * <#if locale="en">
 * Translator combined with dictionary using longest match
 * The extension for MediaWiki uses this type of translation only
 * <#elseif locale="ja">
 * 辞書連携最長一致検索翻訳器（Wikiの翻訳では、これしか使わない。）
 * </#if>
 */
class BindingTranslatorClient extends ServiceClient implements ITranslatorClient {

	protected $translationExecObject = null;
	protected $bindingStr = null;
	protected $bindingSetName = "";

	public function __construct($translationExecObject, $bindingSetName) {
		$this->translationExecObject = $translationExecObject;
		parent::__construct('wsdl/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
		$this->bindingSetName = $bindingSetName;
	}

	public function translate($source) {
		$parameters = array(
				'sourceLang' => $this->translationExecObject->get('source_lang'),
				'targetLang' => $this->translationExecObject->get('target_lang'),
				'source' => $source,
				'dictTargetLang' => $this->translationExecObject->get('target_lang'),
				'temporalDict' => $this->_getTemporalDict($source, $this->bindingSetName)
		);
		$res = parent::call('translate', $parameters, $this->translationExecObject);
		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array(
					'targetLanguage' => $this->translationExecObject->get('target_lang'),
					'targetText' =>$res
				);

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Translation successed.';
			$result['contents'] = array(
					'targetLanguage' => $this->translationExecObject->get('target_lang'),
					'targetText' => $res,
				);
		}

		$result['licenseInformation'] = $this->getLicenses();
		return $result;
	}

	protected function getLicenses() {
		$licenseArray = array();
		$licenseArray = $this->getLicense();
//		$licenseArray = array_merge($licenseArray, $this->getLocalDictionaryLicense());
		$licenseArray = array_merge($licenseArray, $this->getLocalTranslationLicense($this->translationExecObject));
		$licenseArray = array_merge($licenseArray, $this->getCombineLicense());
		return $licenseArray;
	}

	protected function getLicense() {
		$infoArray = array();
		return $infoArray;
		$serviceId = $this->getServiceId();
		require_once(dirname(__FILE__).'/../manager/ServiceManagerClient.class.php');
		$manager = new ServiceManagerClient();
		$self = (array)$manager->getServiceProfile($serviceId);

		$infoArray[$serviceId] = array(
			'serviceName' => $self['serviceName'],
			'serviceCopyright' => $self['copyrightInfo'],
			'serviceLicense' => $self['licenseInfo'],
			'lastAccessDate' => date('D, j M Y G:i:s +0900')
		);

		return $infoArray;
	}

	protected function getCombineLicense() {
		$infoArray = array();
		if (!is_array($this->callTree)) {
			return array();
		}
		foreach ($this->callTree as $obj) {
			$this->_parseTree($obj, &$infoArray);
		}

		return $infoArray;
	}

	protected function _parseTree($obj, &$result) {
		$obj = (array)$obj;
		if ($obj['faultCode'] == '' && count($obj['children']) == 0) {
			$serviceId = $obj['serviceId'];
			$result[$serviceId] = array(
				'serviceName' => $obj['serviceName'],
				'serviceCopyright' => $obj['serviceCopyright'],
				'serviceLicense' => $obj['serviceLicense'],
				'lastAccessDate' => date('D, j M Y G:i:s +0900')
			);
		}
		foreach ($obj['children'] as $child) {
			$this->_parseTree($child, &$result);
		}
	}

	protected function makeBindingHeader($execObj){
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$bindNodes = array();

		$morphologicalAnalyzer = '';
		$bindingDictionaryArray = array();

		foreach ($execObj->getBinds() as $bindObj) {
			switch ( $bindObj->get('bind_type') ) {
				case '0':
					$translator = $bindObj->get('bind_value');
					break;
				case '1':
				case '2':
					$bindingDictionaryArray[] = $bindObj->get('bind_value');
					break;
				case '3':
					// no bind.
					break;
				case '9':
					$morphologicalAnalyzer = $bindObj->get('bind_value');
					break;
				default:
					break;
			}
		}

		$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $morphologicalAnalyzer);

		$dicts = array();
 		foreach ($bindingDictionaryArray as $bindingDictionary) {
 			if (!empty($bindingDictionary) && strcmp($bindingDictionary, 'AbstractBilingualDictionaryWithLongestMatchSearch') != 0)
 				$dicts[] = $bindingDictionary;
 		}

		switch (count($dicts)) {
			case 0:		// Bind for No Dictionary
				$bindNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, 'AbstractBilingualDictionaryWithLongestMatchSearch');
				break;
			case 1:		// Bind for One Dictionary
				$bindNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, $dicts[0]);
				break;
			default:	// Bind for Any Dictionaries
				$dictionaryBinding = array();
				foreach ($dicts as $bindingDictionary) {
					if (!empty($bindingDictionary)) {
						$idx = count($dictionaryBinding) + 1;
						$dictionaryBinding[] =
							sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.$idx, $bindingDictionary);
					}
				}
				$bindNodes[] = sprintf($bindTemp, implode(',', $dictionaryBinding), 'BilingualDictionaryWithLongestMatchSearchPL'
				, $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
				break;
		}

		//* Header info for binding Translator service.
		if (!$translator) {
			$translator = $execObj->get('service_id');
		}
		$bindNodes[] = sprintf($bindTemp, '', 'TranslationPL', $translator);

		$bindingStr = '['.implode(',', $bindNodes).']';

		$this->bindingStr = $bindingStr;
		return $bindingStr;
	}

	 protected function _getTemporalDict($sourceText, $bindingSetName) {
	 	$sourceLang = $this->translationExecObject->get('source_lang');
	 	$targetLang = $this->translationExecObject->get('target_lang');
		$userDictClass = new GetUserDictionaryContents();
		$response = $userDictClass->getUserDictionaryContents($bindingSetName, $sourceLang, $targetLang, $sourceText);
		if ($response == null || count($response) == 0) {
			// <#if locale="ja">TODO:20100302複合サービスの挙動が変わった？空Arrayを返さないといけないようだ。</#if>
			return array(array());
		}
		return $response;
 	}

	public function getServiceId() {
		return $this->getGridId() . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
	}

	public function getSoapBindings() {
		return $this->bindingStr;
	}

}

class BindingTranslatorClient_MultiHop implements ITranslatorClient {
	private $translationExecObjects;
	private $binds = array();
	private $bindingSetName = "";

	public function __construct($translationExecObjects, $bindingSetName) {
		$this->translationExecObjects = $translationExecObjects;
		$this->bindingSetName = $bindingSetName;
	}
	public function translate($source) {
		$lastResult = null;
		$stack = '';
		foreach ($this->translationExecObjects as $execObj) {
			$runner = new BindingTranslatorClient($execObj, $this->bindingSetName);
			if ($lastResult == null) {
				$lastResult = $runner->translate($source);
			} else {
				$lastResult = $runner->translate($lastResult['contents']['targetText']['contents']);
			}
			$this->binds[] = $runner->getSoapBindings();
			$stack .= '\n'.$execObj->get('source_lang').'2'.$execObj->get('target_lang').$lastResult['contents']['targetText']['contents'];
		}

		$lastResult['message'] .= $stack;

		return $lastResult;
	}
	public function getServiceId() {
		return 'MultiHop@TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
	}
	public function getSoapBindings() {
		return implode('@@@', $this->binds);
	}

}
?>