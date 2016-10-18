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

require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');

class Toolbox_TextTranslationManager extends Toolbox_AbstractManager {

	protected $worker;
	/** This class is DB Wrapper */
	protected $ServiceSetting = null;

	public function __construct() {
		parent::__construct();
		$file = XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php';
		if (file_exists($file)) {
			require_once($file);
			if (class_exists("LangridClient")) {
				$this->worker =& new LangridClient();
			} else {
				die("LangridClient class is not found.");
			}
		} else {
			die($file." is not found.");
		}
		$TBoxLGACP = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
		if (!file_exists($TBoxLGACP)) {
			die('DB Access Handler Class is not found.');
		}
		require_once($TBoxLGACP);
		$this->ServiceSetting =& new TranslationServiceSetting();
	}

	public function translate($sourceLang, $targetLang, $source, $bindingSetName) {
		$noSource = false;
		$preSource = "";
		if(!isset($source) || trim($source) == "" || $source == null){
			$preSource = $source;
			$source = ".";
			$noSource = true;
		}

		$setId = $this->getBindingSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}
		$pathObj = $this->_getPathObj($sourceLang, $targetLang, $setId);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}

		$this->worker->setSetId($setId);
		$this->worker->setSourceLanguage($sourceLang);
		$this->worker->setTargetLanguage($targetLang);
		$res = $this->worker->translate($source);

		if ($res != null && $res['status'] == 'OK') {
			$result = new ToolboxVO_TextTranslation_TranslationResult();
			$result->result = $res['contents']['targetText']['contents'];
			if($noSource){$result->result = $preSource;}
			$result->multihopTranslationBinding = $this->TranslationPath2ResponseVO($pathObj);
			$licenseAry = array();
			foreach ($res['licenseInformation'] as $license) {
				$licenseAry[] = $this->LicenseObject2ResponseVO($license);
			}
			$result->translationInvocationInfo = $licenseAry;
			return $this->getResponsePayload(array($result));
			//return $this->getResponsePayload($res);
		} else {
			if ($res == null) {
				return $this->getErrorResponsePayload('Language Grid translator service response is null.(think time out.)');
			} else {
				return $this->getErrorResponsePayload($res['message']);
			}
		}
	}

	public function backTranslate($sourceLang, $targetLang, $source, $bindingSetName) {
		$noSource = false;
		$preSource = "";
		if(!isset($source) || trim($source) == "" || $source == null){
			$preSource = $source;
			$source = ".";
			$noSource = true;
		}
		
		$setId = $this->getBindingSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}

		$this->worker->setSetId($setId);
		$this->worker->setSourceLanguage($sourceLang);
		$this->worker->setTargetLanguage($targetLang);
		$res = $this->worker->translate($source, true);

		if ($res != null && $res['status'] == 'OK') {
			$result = new ToolboxVO_TextTranslation_BackTranslationResult();
			$result->intermediateResult = $res['back-translation']['contents']['targetText']['contents'];
			$result->targetResult = $res['contents']['targetText']['contents'];
			if($noSource){
				$result->intermediateResult = $preSource;
				$result->targetResult = $preSource;
			}
			return $this->getResponsePayload(array($result));
		} else {
			if ($res == null) {
				return $this->getErrorResponsePayload('Language Grid translator service response is null.(think time out.)');
			} else {
				return $this->getErrorResponsePayload($res['message']);
			}
		}
	}
	
	protected function getBindingSetIdByName($bindingSetName) {
		$handler =& $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByName($bindingSetName);
		return $object == null ? null : $object->get('set_id');
	}
	
	private function _getPathObj($sourceLang, $targetLang, $setId) {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$objects =& $this->ServiceSetting->getServiceSettings($uid, $setId, $sourceLang, $targetLang);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0];
	}
	
	protected function LicenseObject2ResponseVO($license) {
		$vo = new ToolboxVO_TextTranslation_InvocationInfo();
		$vo->serviceName = $license['serviceName'];
		$vo->copyright = $license['serviceCopyright'];
		$vo->license = $license['serviceLicense'];
		$vo->errorMessage = '';

		return $vo;
	}

	protected function TranslationPath2ResponseVO($pathObj) {
		$multihopTranslationBinding =& new ToolboxVO_TextTranslation_MultihopTranslationBinding();
		$multihopTranslationBinding->id = $pathObj->get('path_id');

		$translationBindingAry = array();
		$langPathAry = array();
		$langPathAry[] = $pathObj->get('source_lang');
		$execObjAry =& $pathObj->getExecs();
		foreach ($execObjAry as $execObj) {
			$langPathAry[] = $execObj->get('target_lang');
			$translationBinding =& new ToolboxVO_TextTranslation_TranslationBinding();
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

	protected function _convertEndPoint2LocalName($endPoint) {
		require_once(XOOPS_ROOT_PATH.'/api/class/handler/CommunityResourceHandler.class.php');
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		$dictionaryHandler =& new CommunityResourceHandler($db);
		$serviceHandler =& $this->ServiceSetting->getLangridServiceHandler();

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('endpoint_url', $endPoint));
		$mc->add(new Criteria('service_type', 'IMPORTED_DICTIONARY'));
		$mc->add(new Criteria('delete_flag', '0'));
		$obj =& $serviceHandler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $obj[0]->get('service_name');
		} else {
			if (preg_match("{^".XOOPS_URL.".*?serviceId=([^=]+)$}", $endPoint, $match)) {
				$sName = $match[1];
				$_name = str_replace('_', ' ', $sName);
				$mc =& new CriteriaCompo();
				$mc->add(new Criteria('dictionary_name', $_name));
				$mc->add(new Criteria('type_id', '0'));
				$mc->add(new Criteria('deploy_flag', '1'));
				$mc->add(new Criteria('delete_flag', '0'));
				$obj =& $dictionaryHandler->getObjects($mc);
				if ($obj != null && count($obj) > 0) {
					return $_name;
				}
			}
		}
		return null;
	}
}
?>