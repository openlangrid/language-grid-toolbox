<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
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

require_once(dirname(__FILE__).'/Toolbox_LangridAccess_AbstractManager.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/ServiceGridClient.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
class Toolbox_ServiceGridClientAdapter extends Toolbox_LangridAccess_AbstractManager {

	public function translate($sourceLang, $targetLang, $source, $bindingSetName, $options = array()) {
		$client = new ServiceGridClient($options);
		$result = $client->translate($sourceLang, $targetLang, $source, null, $bindingSetName);
		if ($result) {
			$vo = new ToolboxVO_LangridAccess_TranslationResult();
			$vo->result = $result['contents'];
//			$vo->multihopTranslationBinding = $this->TranslationPath2ResponseVO($pathObj);

			$licenseAry = array();
			foreach ($result['LicenseInformation'] as $license) {
				$licenseAry[] = $this->LicenseObject2ResponseVO($license);
			}
			$vo->translationInvocationInfo = $licenseAry;
			$status = $result['status'];
			if (empty($status)) {
				$status = 'OK';
			}
			return $this->getResponsePayload(array($vo), $status);
		}
		return $this->getErrorResponsePayload('Translation error.');
	}
	public function metaTranslate($sourceLang, $targetLang, $source, $bindingSetName, $options = array()) {
		$client = new ServiceGridClient($options);
		$func = array($client, 'translate');
		$result = $client->metaTranslate($func, $sourceLang, $targetLang, $source, null, $bindingSetName);
		if ($result) {
			$vo = new ToolboxVO_LangridAccess_TranslationResult();
			$vo->result = $result['contents'];
			$licenseAry = array();
			foreach ($result['LicenseInformation'] as $license) {
				$licenseAry[] = $this->LicenseObject2ResponseVO($license);
			}
			$vo->translationInvocationInfo = $licenseAry;

			return $this->getResponsePayload(array($vo));
		}
		return $this->getErrorResponsePayload('Translation error.');
	}
	public function backTranslate($sourceLang, $intermediateLang, $source, $bindingSetName, $options = array()) {
		$client = new ServiceGridClient($options);
		$result = $client->backTranslate($sourceLang, $intermediateLang, $source, null, $bindingSetName);
		if ($result) {
			$vo = new ToolboxVO_LangridAccess_BackTranslationResult();
			$vo->intermediateResult = $result['contents']->intermediate;
			$vo->targetResult = $result['contents']->target;
			$licenseAry = array();
			foreach ($result['LicenseInformation'] as $license) {
				$licenseAry[] = $this->LicenseObject2ResponseVO($license);
			}
			$vo->translationInvocationInfo = $licenseAry;
			$status = $result['status'];
			if (empty($status)) {
				$status = 'OK';
			}
//			debugLog('backTranslate::'.print_r($vo, true));
			return $this->getResponsePayload(array($vo), $status);
		}
		return $this->getErrorResponsePayload('Translation error.');
	}

	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $bindingSetName, $sourceTextJoinStrategy, $options = array()) {
		$client = new ServiceGridClient($options);
		$result = $client->multisentenceTranslate($sourceLang, $targetLang, $sourceArray, null, $bindingSetName, array(), $sourceTextJoinStrategy);

		if ($result) {
			$vo = new ToolboxVO_LangridAccess_TranslationResult();
			$vo->result = $result['contents'];
//			$vo->intermediateResult = $result['contents']->intermediate;
//			$vo->targetResult = $result['contents']->target;
			$licenseAry = array();
			foreach ($result['LicenseInformation'] as $license) {
				$licenseAry[] = $this->LicenseObject2ResponseVO($license);
			}
			$vo->translationInvocationInfo = $licenseAry;
			$status = $result['status'];
			if (empty($status)) {
				$status = 'OK';
			}
			return $this->getResponsePayload(array($vo), $status);
		}
		return $this->getErrorResponsePayload('Translation error.');
	}

	public function multisentenceBackTranslate($sourceLang, $intermediateLang, $sourceArray, $bindingSetName, $sourceTextJoinStrategy, $options = array()) {
		$client = new ServiceGridClient($options);
		$result = $client->multisentenceBackTranslate($sourceLang, $intermediateLang, $sourceArray, null, $bindingSetName, array(), $sourceTextJoinStrategy);
		if ($result) {
			$vo = new ToolboxVO_LangridAccess_BackTranslationResult();
			if (isset($result['contents']->intermediate)) {
				$vo->intermediateResult = $result['contents']->intermediate;
			} else {
				$vo->intermediateResult = array_pad(array(), count($sourceArray), '');
			}
			if (isset($result['contents']->target)) {
				$vo->targetResult = $result['contents']->target;
			} else {
				$vo->targetResult = array_pad(array(), count($sourceArray), '');
			}
			$vo->multihopTranslationBinding = array();

			$licenseAry = array();
			if (isset($result['LicenseInformation'])) {
				foreach ($result['LicenseInformation'] as $license) {
					$licenseAry[$license['serviceName']] = $this->LicenseObject2ResponseVO($license);
				}
			}
			$vo->translationInvocationInfo = $licenseAry;
			$status = '';
			if (empty($result['status'])) {
				$status = 'OK';
			} else {
				$status = $result['status'];
			}
			if (empty($status)) {
				$status = 'OK';
			}
			// MARK Array->Object Change
			return $this->getResponsePayload($vo, $status);
		}
		return $this->getErrorResponsePayload('Translation error.');
	}

	protected function TranslationPath2ResponseVO($pathObj) {
		$setting = new ServiceGridServiceSetting();
		$multihopTranslationBinding = new ToolboxVO_LangridAccess_MultihopTranslationBinding();
		$multihopTranslationBinding->id = $pathObj->get('path_id');

		$translationBindingAry = array();
		$langPathAry = array();
		$langPathAry[] = $pathObj->get('source_lang');
		$execObjAry =& $pathObj->getExecs();
		foreach ($execObjAry as $execObj) {
			$langPathAry[] = $execObj->get('target_lang');
			$translationBinding = new ToolboxVO_LangridAccess_TranslationBinding();
			$translationBinding->sourceLang = $execObj->get('source_lang');
			$translationBinding->targetLang = $execObj->get('target_lang');
			$translationBinding->translationServiceId = $execObj->get('service_id');

			$morAnaId = '';
			$globalDict = array();
			$localDict = array();
			$tempDict = array();
			$bindObjAry =& $execObj->getBinds();
			foreach ($bindObjAry as $bindObj) {
				$value = $bindObj->get('bind_value');
				switch ( $bindObj->get('bind_type') ) {
					case '1':
						$globalDict[] = $value;
						break;
					case '2':
						$name = $this->_convertEndPoint2LocalName($value);
						if ($name != null) {
							$localDict[] = $name;
						}
						break;
					case '3':
						$tempDict[] = $value;
						break;
					case '9':
						$morAnaId = $value;
						break;
					default:
						break;
				}
			}
			$translationBinding->morphologicalAnalysisServiceId = $morAnaId;
			$translationBinding->globalDictionaryServiceIds = $globalDict;
			$translationBinding->localDictionaryServiceIds = $localDict;
			$translationBinding->temporalDictionaryNames = $tempDict;

			$translationBindingAry[] = $translationBinding;
		}

		$multihopTranslationBinding->path = $langPathAry;
		$multihopTranslationBinding->translationBindings = $translationBindingAry;

		return $multihopTranslationBinding;
	}

	protected function LicenseObject2ResponseVO($license) {
		$vo = new ToolboxVO_LangridAccess_InvocationInfo();
		$vo->serviceName = $license['serviceName'];
		$vo->copyright = $license['serviceCopyright'];
		$vo->license = $license['serviceLicense'];
		$vo->errorMessage = '';

		return $vo;
	}
}
?>