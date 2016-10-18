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
require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');

class BindingBackTranslatorClient extends BindingTranslatorClient {

	protected $translationExecObject;
	protected $backExecObject;

	public function BindingBackTranslatorClient($forwardExecObject, $backExecObject) {
		$this->translationExecObject = $forwardExecObject;
		$this->backExecObject = $backExecObject;

//		$this->forwardExecObject->set('service_id',
//			$this->_callUserHookFunction_replaceTranslatorServiceId($this->forwardExecObject->get('service_id')));
//		$this->backExecObject->set('service_id',
//			$this->_callUserHookFunction_replaceTranslatorServiceId($this->backExecObject->get('service_id')));

		parent::ServiceClient('wsdl/' . $this->getGridId() . ':BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}

	public function translate($source) {

		$parameters = array(
				'sourceLang' => $this->translationExecObject->get('source_lang'),
				'intermediateLang' => $this->translationExecObject->get('target_lang'),
				'source' => $source,
				'dictTargetLang' => $this->translationExecObject->get('target_lang'),
				'temporalDict' => $this->_getTemporalDict($source)
		);

		$bindParams = array('forward'=>$this->translationExecObject, 'back'=>$this->backExecObject);

		$res = parent::call('backTranslate', $parameters, $bindParams);

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

	protected function makeBindingHeader($bindParameters){
		//print_r($bindParameters);die();
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$execObj = $bindParameters['forward'];
		$backObj = $bindParameters['back'];

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
		
		$fServiceId = $this->convertServiceId($execObj->get('service_id'));
		$bServiceId = $this->convertServiceId($backObj->get('service_id'));

		$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL', $fServiceId);
		$bindNodes[] = sprintf($bindTemp, '', 'BackwardTranslationPL', $bServiceId);

		$bindingStr = '['.implode(',', $bindNodes).']';

		$this->bindingStr = $bindingStr;
		return $bindingStr;
	}

	public function getServiceId() {
		return $this->getGridId() . ':BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
	}

	public function getSoapBindings() {
		return $this->bindingStr;
	}
}
?>