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
require_once(dirname(__FILE__).'/TranslationWithBilingualDictionaryLMClient.class.php');
require_once(dirname(__FILE__).'/../../../class/UserDictionaryClass.php');
class TranslationWithBilingualDictionaryLM_NPassClient extends TranslationWithBilingualDictionaryLMClient {

//	private $setting = null;
//
//	public function __construct($setting) {
//		$this->setting = $setting;
//		$wsdl = 'http://langrid.nict.go.jp/langrid-1.2/wsdl/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
//		parent::__construct($wsdl);
//	}

	private $combineLicenseArray = array();

	public function translate($source) {

		$res = null;

		$res1pass = null;
		$res2pass = null;

		$userDictObject = $this->_getTemporalDict($this->setting['userDictionaryIds'], $this->setting['sourceLang'], $this->setting['targetLang']);
		$globalDict = $this->setting['globalDictionaryIds'];
		$userDict = $this->setting['userDictionaryIds'];

		if ($this->setting['translatorService3']) {
			$res1pass = $this->__runner(
				$this->setting['sourceLang'],
				$this->setting['interLang1'],
				$this->setting['translatorService1'],
				$globalDict, $userDict, $userDictObject, $source);

			if ($res1pass['status'] != 'OK') {
				return $res1pass;
			}
			$res1pass['language'] = $this->setting['interLang1'];
			$interSource = $res1pass['contents'];

			$res2pass = $this->__runner(
				$this->setting['interLang1'],
				$this->setting['interLang2'],
				$this->setting['translatorService2'],
				$globalDict, $userDict, $userDictObject, $interSource);
			if ($res2pass['status'] != 'OK') {
				return $res2pass;
			}
			$res2pass['language'] = $this->setting['interLang2'];
			$interSource = $res2pass['contents'];

			$res = $this->__runner(
				$this->setting['interLang2'],
				$this->setting['targetLang'],
				$this->setting['translatorService3'],
				$globalDict, $userDict, $userDictObject, $interSource);

		} else {
			$res1pass = $this->__runner(
				$this->setting['sourceLang'],
				$this->setting['interLang1'],
				$this->setting['translatorService1'],
				$globalDict, $userDict, $userDictObject, $source);
			if ($res1pass['status'] != 'OK') {
				return $res1pass;
			}
			$res1pass['language'] = $this->setting['interLang1'];
			$interSource = $res1pass['contents'];

			$res = $this->__runner(
				$this->setting['interLang1'],
				$this->setting['targetLang'],
				$this->setting['translatorService2'],
				$globalDict, $userDict, $userDictObject, $interSource);
		}

		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array(
					'targetLanguage' => $this->setting['targetLang'],
					'targetText' =>$res
				);

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Translation successed.';
			$result['contents'] = array(
					'targetLanguage' => $this->setting['targetLang'],
					'targetText' => $res,
				);
			if ($res1pass != null) {
				$result['inter-translate1'] = $res1pass;
			}
			if ($res2pass != null) {
				$result['inter-translate2'] = $res2pass;
			}
		}

		$licenseArray = $this->getLicense();
		$licenseArray = array_merge($licenseArray, $this->combineLicenseArray);

		$result['licenseInformation'] = $licenseArray;

		return $result;
	}

	private function __runner($src, $tgt, $service, $globalDict, $userDict, $userDictData, $source) {
		$bindParameters = array(
			'sourceLang' => $src,
			'globalDictionaryIds' => $globalDict,
			'userDictionaryIds' => $userDict,
			'translatorService1' => $service
		);
		$parameters = array(
				'sourceLang' => $src,
				'targetLang' => $tgt,
				'source' => $source,
				'dictTargetLang' => $tgt,
				'temporalDict' => $userDictData);

		$res = parent::call('translate', $parameters, $bindParameters);

		$this->combineLicenseArray = array_merge($this->combineLicenseArray, $this->getCombineLicense());

		return $res;
	}
//
//	protected function makeBindingHeader($parameters){
//		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';
//
//		$bindNodes = array();
//
//
//		//* Header info for binding morphological analyzer service.
//		$morphologicalAnalyzer = '';
//		if($parameters['sourceLang'] == "ja"){
//			$morphologicalAnalyzer = "Mecab";
//		}else if($from == "ko"){
//			$morphologicalAnalyzer = "Klt";
//		}else if($from == "zh"){
//			$morphologicalAnalyzer = "ICTCLAS";
//		}else {
//			$morphologicalAnalyzer = "TreeTagger";
//		}
//		$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $morphologicalAnalyzer);
//
//		//* Header info for binding Bilingual Dictionary service.
//		$globalDictIds = explode(',', $parameters['globalDictionaryIds']);
//		$glovalDictBinds = array();
//		for ($i = 0; $i < count($globalDictIds); $i++) {
//			$glovalDictBinds[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.($i+1), $globalDictIds[$i]);
//		}
//		$bindNodes[] = sprintf($bindTemp, implode(',', $glovalDictBinds), 'BilingualDictionaryWithLongestMatchSearchPL', 'kyotou.langrid:BilingualDictionaryWithLongestMatchCrossSearch');
//
//		//* Header info for binding Translator service.
//		$bindNodes[] = sprintf($bindTemp, '', 'TranslationPL', $parameters['translatorService1']);
//
//		$bindingStr = '['.implode(',', $bindNodes).']';
//
//		return $bindingStr;
//	}
//
//	 protected function _getTemporalDict($src, $tgt, $userDictionaryIds) {
//	 	$idArray = explode(',', $userDictionaryIds);
//		$userDictClass =& new UserDictionaryClass();
//		$response = array();
//		foreach ($idArray as $name) {
//			$val = $userDictClass->getUserDictionaryContents($name, $src, $tgt);
//			if ($val) {
//				$response = array_merge($response, $val);
//			}
//		}
//		return $response;
// 	}
}
?>