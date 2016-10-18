<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_TextTranslationManager.class.php';
require_once XOOPS_ROOT_PATH.'/api/ITextTranslationClient.interface.php';
require_once dirname(__FILE__).'/CTLangrid-client.php';
require_once dirname(__FILE__).'/translation_path.php';


class TranslationManager extends Toolbox_TextTranslationManager {
	
	// override
	public function __construct() {
		parent::__construct();
		$this->worker = new CTLangridClient();
	}
	
	public function getSetIdByName($bindingSetName, $userId=null) {
		$handler =& $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByName($bindingSetName, $userId);
		return $object == null ? null : $object->get('set_id');
	}
	
	public function getDefaultSetIdByName($bindingSetName) {
		$handler =& $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByNameWithDefualtSet($bindingSetName);
		return $object == null ? null : $object->get('set_id');
	}
	
	
	public function translateByDefault($sourceLang, $targetLang, $source, $bindingSetName) {
		$setId = $this->getSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this -> translateAsAdmin($sourceLang, $targetLang, $source, $bindingSetName);
		} else {
			return $this -> translateByUserDefault($sourceLang, $targetLang, $source, $bindingSetName);		
		}
	}
	
	
	public function translateByUserDefault($sourceLang, $targetLang, $source, $bindingSetName) {
		$defPath = DefaultTranslationPath::find(getLoginUserUID(), $sourceLang, $targetLang);
		if($defPath) {
			$this->worker->setPathObj( $this -> getPathObj(getLoginUserUID(), $sourceLang, $targetLang, $defPath));
			return $this -> translateExec($sourceLang, $targetLang, $source, $defPath);
		}
	}
	
	public function translateAsAdmin($sourceLang, $targetLang, $source, $bindingSetName) {
		$defPath = DefaultTranslationPath::find(TranslationPath::ADMIN_UID, $sourceLang, $targetLang);
		if($defPath) {
			$this->worker->setPathObj( $this -> getPathObj(TranslationPath::ADMIN_UID, $sourceLang, $targetLang, $defPath));
			return $this -> translateExec($sourceLang, $targetLang, $source, $defPath);
		}
	}
	
	public function translateByPath($sourceLang, $targetLang, $source, $path, $uid=null) {
		$this->worker->setPathObj( $this -> getPathObj($uid, $sourceLang, $targetLang, $path));
		return $this -> translateExec($sourceLang, $targetLang, $source, $path);		
	}
	
	protected function translateExec($sourceLang, $targetLang, $source, $path) {
		$this->worker->setParams(
			$this -> getWorkerParameter($path->getSetId(), $sourceLang, $targetLang, $path->getPathId())
		);
		
		$res = $this->worker->translate(!$source ? "." : $source);
		if ($res != null && $res['status'] == 'OK') {

			$result = new ToolboxVO_TextTranslation_TranslationResult();
			$result->result = $res['contents']['targetText']['contents'];
			$result->multihopTranslationBinding = $this->TranslationPath2ResponseVO($this->worker->getPathObj());
			$result->translationInvocationInfo = $this -> createLicenseObjects($res['licenseInformation']);
			
			if(!isset($source) || trim($source) == "" || $source == null){
				$result->result = $source;
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

	
	protected function getWorkerParameter($setId, $sourceLang, $targetLang, $pathId = null) {
		$params = array(
			"targetLang" => $targetLang,
			"sourceLang" => $sourceLang,
			"setId" => $setId
		);

		if($pathId) {
			$params['pathId'] = $pathId;
		}
		return $params;
	}
	
	protected function getPathObj($uid, $sourceLang, $targetLang, $transPath) {
		$objects =& $this->ServiceSetting->getServiceSettings($uid, $transPath->getSetId(), $sourceLang, $targetLang);
		if(!is_null($objects)) {
			foreach($objects as $path) {
				if($path -> mVars['path_id']['value'] == $transPath->getPathId()) return $path;
			}
			
			return $objects[0];
		}
	}
	
	protected function createLicenseObjects($licenseInformaion) {
		$licenseAry = array();
		foreach ($licenseInformaion as $license) {
			$licenseAry[] = $this -> LicenseObject2ResponseVO($license);
		}
		return $licenseAry;
	}
	
//	public function backTranslateByDefault($sourceLang, $targetLang, $source, $bindingSetName) {
//		$setId = $this->getBindingSetIdByName($bindingSetName);
//		if ($setId == null) {
//			return $this->getErrorResponsePayload('Translation path is not found.');
//		}
//		return $this -> translateBySetId($sourceLang, $targetLang, $source, $setId);
//	}
//	
//	public function backTranslateBySetId($sourceLang, $targetLang, $source, $setId) {
//		
//		$this -> setWorkerParameter($setId, $sourceLang, $targetLang);
//		
//		$noSource = false;
//		$preSource = "";
//		if(!isset($source) || trim($source) == "" || $source == null){
//			$preSource = $source;
//			$source = ".";
//			$noSource = true;
//		}
//		
//		$res = $this->worker->translate($source, true);
//		if ($res != null && $res['status'] == 'OK') {
//			$result = new ToolboxVO_TextTranslation_BackTranslationResult();
//			$result->intermediateResult = $res['back-translation']['contents']['targetText']['contents'];
//			$result->targetResult = $res['contents']['targetText']['contents'];
//			if($noSource){
//				$result->intermediateResult = $preSource;
//				$result->targetResult = $preSource;
//			}
//			return $this->getResponsePayload(array($result));
//		} else {
//			if ($res == null) {
//				return $this->getErrorResponsePayload('Language Grid translator service response is null.(think time out.)');
//			} else {
//				return $this->getErrorResponsePayload($res['message']);
//			}
//		}
//	}

}
?>
