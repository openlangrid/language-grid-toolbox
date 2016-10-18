<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
require_once(dirname(__FILE__).'/../../../class/UserDictionaryClass.php');
class BindingTranslatorClient extends ServiceClient implements ITranslatorClient {

	protected $translationExecObject = null;
	protected $bindingStr = null;

	public function BindingTranslatorClient($translationExecObject) {
		$this->translationExecObject = $translationExecObject;
		$this->translationExecObject->set('service_id',
			$this->_callUserHookFunction_replaceTranslatorServiceId($this->translationExecObject->get('service_id')));
		parent::ServiceClient('wsdl/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}

	public function translate($source) {

		$parameters = array(
				'sourceLang' => $this->translationExecObject->get('source_lang'),
				'targetLang' => $this->translationExecObject->get('target_lang'),
				'source' => $source,
				'dictTargetLang' => $this->translationExecObject->get('target_lang'),
				'temporalDict' => $this->_getTemporalDict($source)
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
		$licenseArray = array_merge($licenseArray, $this->getLocalDictionaryLicense());
		$licenseArray = array_merge($licenseArray, $this->getLocalTranslationLicense($this->translationExecObject));
		$licenseArray = array_merge($licenseArray, $this->getCombineLicense());
		return $licenseArray;
	}

	protected function getLicense() {
		$infoArray = array();
		return $infoArray;
		$serviceId = $this->getGridId() . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
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

	/**
	 * @param bindObj
	 */
	protected function getLocalDictionaryLicense() {
		$license = array();
		foreach ($this->translationExecObject->getBinds() as $bind) {
			if ($bind->get('bind_type') == '2') {
				require_once dirname(__FILE__).'/../../../class/LangridServicesClass.php';
				$langridServices = new LangridServicesClass();
				$results = $langridServices->searchLocalDictionaryByEndpoint($bind->get('bind_value'));
				if (count($results) > 0) {
					foreach ($results as $key => $result) {
						$license[$key] = array(
							'serviceName' => isset($result['service_name']) ? $result['service_name'] : '',
							'serviceCopyright' => isset($result['copyright']) ? $result['copyright'] : '',
							'serviceLicense' => isset($result['license']) ? $result['license'] : '',
							'lastAccessDate' => date('D, j M Y G:i:s +0900')
						);
					}
				}
			}
		}
		return $license;
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

		$translator = $this->convertServiceId($execObj->get('service_id'));
		
		$bindNodes[] = sprintf($bindTemp, '', 'TranslationPL', $translator);

		$bindingStr = '['.implode(',', $bindNodes).']';

		$this->bindingStr = $bindingStr;
		return $bindingStr;
	}

	protected function _callUserHookFunction_replaceTranslatorServiceId($serviceId) {
		// call user hook function
		$pinfo = pathinfo(__FILE__);
		$hookfile = $pinfo['dirname'].'/hooks/TranslatorClient.hook.'.$pinfo['extension'];
		if (file_exists($hookfile)) {
			require_once($hookfile);
			$hookclass = 'TranslatorClient_Hook';
			if (class_exists($hookclass)) {
				$hook = new $hookclass;
				$hookfunc = 'replaceTranslatorServiceId';
				if (method_exists($hook, $hookfunc)) {
					call_user_method($hookfunc, $hook, &$serviceId);
				}
			}
		}
		return $serviceId;
	}

	 protected function _getTemporalDict($sourceText) {
//		echoTime("テンポラル辞書データ取得全体の開始");
	 	$sourceLang = $this->translationExecObject->get('source_lang');
	 	$targetLang = $this->translationExecObject->get('target_lang');
		$userDictClass = new UserDictionaryClass();
		$response = array();
		foreach ($this->translationExecObject->getBinds() as $bindObj) {
			if ($bindObj->get('bind_type') == '3') {
				$val = $userDictClass->getUserDictionaryContents($bindObj->get('bind_value'), $sourceLang, $targetLang, $sourceText);
				if ($val) {
					$response = array_merge($response, $val);
				}
			}
		}
//		echoTime("テンポラル辞書データ取得全体の終了");
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

	public function __construct($translationExecObjects) {
		$this->translationExecObjects = $translationExecObjects;
	}
	public function translate($source) {
		$lastResult = null;
		$stack = '';
		foreach ($this->translationExecObjects as $execObj) {
			$runner = new BindingTranslatorClient($execObj);
			if ($lastResult == null) {
				$lastResult = $runner->translate($source);
			} else {
//				print_r( $lastResult['contents']['targetText']);die();
				$lastResult = $runner->translate($lastResult['contents']['targetText']['contents']);
			}
			$this->binds[] = $runner->getSoapBindings();
			//$stack[$execObj->get('source_lang').'2'.$execObj->get('target_lang')] = $lastResult['contents']['targetText']['contents'];
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