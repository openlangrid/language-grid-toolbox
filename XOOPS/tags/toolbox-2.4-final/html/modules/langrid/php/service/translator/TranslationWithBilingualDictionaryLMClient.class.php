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
require_once(dirname(__FILE__).'/../../../class/UserDictionaryClass.php');

class TranslationWithBilingualDictionaryLMClient extends ServiceClient {

	protected $setting = null;
	protected $bindingStr = null;

	public function __construct($setting) {
		$this->setting = $setting;
		parent::__construct('wsdl/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}

	public function translate($source) {
		$bindParameters = array(
			'sourceLang' => $this->setting['sourceLang'],
			'globalDictionaryIds' => $this->setting['globalDictionaryIds'],
			'userDictionaryIds' => $this->setting['userDictionaryIds'],
			'translatorService1' => $this->setting['translatorService1']
		);

		$userDictObject = $this->_getTemporalDict($this->setting['sourceLang'], $this->setting['targetLang'], $this->setting['userDictionaryIds']);

		$parameters = array(
				'sourceLang' => $this->setting['sourceLang'],
				'targetLang' => $this->setting['targetLang'],
				'source' => $source,
				'dictTargetLang' => $this->setting['targetLang'],
				'temporalDict' => $userDictObject);

		$res = parent::call('translate', $parameters, $bindParameters);

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
		}

		$licenseArray = $this->getLicense();
		$licenseArray = array_merge($licenseArray, $this->getCombineLicense());

		$result['licenseInformation'] = $licenseArray;

//		$html = '<div class="license"><h3></h3>';
//		foreach ($licenseArray as $item) {
//			$html .= '<div class="box"><ul>';
//
//			$html .= '<li class="label">ServiceName</li>';
//			$html .= '<li class="value">'.$item['serviceName'].'</li>';
//			$html .= '<li class="label">Copyright</li>';
//			$html .= '<li class="value">'.$item['serviceCopyright'].'</li>';
//			$html .= '<li class="label">License Information</li>';
//			$html .= '<li class="value">'.$item['serviceLicense'].'</li>';
//
//			$html .= '</ul></div>';
//		}
//		$html .= '</div>';
//		$html .= '<style>';
//		$html .= 'div.license {text-align: left; border:1px solid #ccc; width: 200px;}';
//		$html .= 'div.license div.box {border-bottom: 2px dotted #ccc;}';
//		$html .= 'div.license div.box .label {border-left: 5px solid #ff0}';
//		$html .= 'div.license div.box .value {margin-left: 20px;}';
//		$html .= '</style>';
//
//		$result['licenseInformationHtml'] = $html;
		return $result;
	}

	protected function getLicense() {
		$infoArray = array();
		$serviceId = $this->getGridId() . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
		require_once(dirname(__FILE__).'/../manager/ServiceManagerClient.class.php');
		$manager =& new ServiceManagerClient();
		$self = (array)$manager->getServiceProfile($serviceId);

		//print_r($selfLicense);die();
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
		foreach ($this->callTree as $obj) {
			$this->_parseTree($obj, &$infoArray);
		}
		return $infoArray;
	}

	protected function _parseTree($obj, &$result) {
		$obj = (array)$obj;
		if ($obj['faultCode'] == '') {
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

	protected function makeBindingHeader($parameters){
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

        debugLog("aaaa");
        throw new Exception("YOU FIRED!!");

		$bindNodes = array();

		//* Header info for binding morphological analyzer service.
		$morphologicalAnalyzer = '';
		foreach ($parameters->getBinds() as $bindObj) {
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

		//* Header info for binding Bilingual Dictionary service.
		$globalDictIds = explode(',', $parameters['globalDictionaryIds']);

		$glovalDictBinds = array();

		// Todo:Test bind local dictionary
		$local = XOOPS_URL.'/modules/dictionary/services/invoker/billingualdictionary.php?serviceId=%s';
		$db = Database::getInstance();
		$tbl = $db->prefix('user_dictionary');
		$userDictIds = explode(',', $parameters['userDictionaryIds']);
		foreach ($userDictIds as $userDictId) {
			$sql = 'select * from '.$tbl.' where `dictionary_name` = \'%s\' and `delete_flag` = \'0\'';
			if ($rs = $db->query(sprintf($sql, $userDictId))) {
				if ($row = $db->fetchArray($rs)) {
					if ($row['deploy_flag'] == '1') {
						array_unshift($globalDictIds, sprintf($local, str_replace(' ', '_', $userDictId)));
					}
				}
			}
		}

		foreach ($globalDictIds as $globalDictId) {
			if (!empty($globalDictId)) {
				$idx = count($glovalDictBinds) + 1;
				$glovalDictBinds[] =
					sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.$idx, $globalDictId);
			}
		}
		if (count($glovalDictBinds) > 0) {
			$bindNodes[] = sprintf($bindTemp, implode(',', $glovalDictBinds), 'BilingualDictionaryWithLongestMatchSearchPL', $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
		} else {
			$bindNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL', $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
		}

		//* Header info for binding Translator service.

		$bk = array();
		$bk[] = sprintf($bindTemp, '', 'TranslationPL', $parameters['translatorService1']);
		$bk[] = sprintf($bindTemp, '', 'BackupTranslationPL', $parameters['translatorService1']);
		$bindNodes[] = sprintf($bindTemp, implode(',', $bk), 'TranslationPL', $this->getGridId() . ':TranslationWithBackup');

//		$bindNodes[] = sprintf($bindTemp, '', 'TranslationPL', $parameters['translatorService1']);

		$bindingStr = '['.implode(',', $bindNodes).']';

		$this->bindingStr = $bindingStr;
		return $bindingStr;
	}

	 protected function _getTemporalDict($src, $tgt, $userDictionaryIds) {
	 	$idArray = explode(',', $userDictionaryIds);
		$userDictClass =& new UserDictionaryClass();
		$response = array();
		foreach ($idArray as $name) {
			$val = $userDictClass->getUserDictionaryContents($name, $src, $tgt);
			if ($val) {
				$response = array_merge($response, $val);
			}
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
?>