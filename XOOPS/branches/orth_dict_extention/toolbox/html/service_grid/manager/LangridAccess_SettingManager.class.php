<?php

require_once(dirname(__FILE__).'/LangridAccess_AbstractManager.class.php');
/**
 * <#if locale="en">
 * Translation setting manager class
 * <#elseif locale="ja">
 * 翻訳設定マネージャクラス
 * </#if>
 */
class LangridAccess_SettingManager extends LangridAccess_AbstractManager {

	/**
	 * getAllBindingSets
	 *
	 * @param $bindingType [translation]
	 */
	public function getAllBindingSets($bindingType) {
		$handler = $this->ServiceSetting->getSetHandler();
		$objects = $handler->getAllByUid();

		if ($objects == null || count($objects) == 0) {
			return $this->getErrorResponsePayload('Not found.');
		}

		$bindingSetArray = array();
		foreach ($objects as $object) {
			$bindingSetArray[] = $this->TranslationSetObject2ResponseVO($object);
		}

		return $this->getResponsePayload($bindingSetArray);
	}

	/**
	 * getBindingSet
	 *
	 * @param $bindingSetName
	 */
	public function getBindingSet($bindingSetName) {
		$handler = $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByName($bindingSetName);
		if ($object == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		return $this->getResponsePayload($this->TranslationSetObject2ResponseVO($object));
	}

	/**
	 * createBindingset
	 */
	public function createBindingSet($bindingSetName, $type, $bShared) {

		$object = $this->ServiceSetting->addTranslationSet($this->uid, $bindingSetName, $bShared);

		return $this->getResponsePayload($this->TranslationSetObject2ResponseVO($object));
	}

	/**
	 * deleteBindingSet
	 */
	public function deleteBindingSet($bindingSetName) {
	}

	/**
	 * getTranslationPaths
	 */
	public function getTranslationPaths($bindingSetName) {
		$setId = $this->getBindingSetIdByName($bindingSetName);

		$objects =& $this->ServiceSetting->getServiceSettings($this->uid, $setId);
		if ($objects == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($objects as $object) {
			$ret[] = $this->TranslationPath2ResponseVO($object);
		}
		return $this->getResponsePayload($ret);
	}

	/**
	 * getTranslationPath
	 */
	public function getTranslationPath($bindingSetName, $id) {
		$object =& $this->ServiceSetting->loadServiceSetting($id);
		if ($object == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		return $this->getResponsePayload($this->TranslationPath2ResponseVO($object));
	}

	/**
	 * updateTranslaitonPath
	 */
	public function updateTranslationPath($bindingSetName, $id, $translationBindings) {
		$pathObj =& $this->ServiceSetting->loadServiceSetting($id);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Not found.');
		}

		// TranslationEXEC以下をDelete
		foreach ($pathObj->getExecs() as $execObj) {
			$this->ServiceSetting->removeTranslationExec($pathObj->get('path_id'), $execObj->get('exec_id'));
		}

		$this->entryTranslationBindings($id, $translationBindings);

		$firstLang = $translationBindings[0]->sourceLang;
		$lastLang = $translationBindings[count($translationBindings) - 1]->targetLang;
		$pathObj->set('source_lang', $firstLang);
		$pathObj->set('target_lang', $lastLang);

		$this->ServiceSetting->update($pathObj, false);

		return true;
	}

	/**
	 * addTranslationPath
	 */
	public function addTranslationPath($bindingSetName, $languagePath, $translationBindings) {
		//$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$setId = $this->getBindingSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this->getErrorResponsePayload('BindingSetName is not found.');
		}

		$sourceLang = $languagePath[0];
		$targetLang = $languagePath[count($languagePath) - 1];
		$pathObj =& $this->ServiceSetting->addTranslationPath($this->uid, $setId, $sourceLang, $targetLang);

		$this->entryTranslationBindings($pathObj->get('path_id'), $translationBindings);

		return $this->getTranslationPath($bindingSetName, $pathObj->get('path_id'));
	}

	/**
	 * removeTranslationPath
	 */
	public function removeTranslationPath($bindingSetName, $id) {
		return $this->ServiceSetting->removeTranslationPath($id);
	}

	/**
	 * getSupportedTranslationPathLanguagePairs
	 */
	public function getSupportedTranslationPathLanguagePairs($bindingSetName) {
		//$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$setId = $this->getBindingSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this->getErrorResponsePayload('BindingSetName is not found.');
		}

		$objects =& $this->ServiceSetting->getServiceSettings($this->uid, $setId);
		if ($objects == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($objects as $object) {
			$ret[] = array($object->get('source_lang'), $object->get('target_lang'));
		}
		return $this->getResponsePayload($ret);
	}

	public function getAllLanguageServices($type) {
		$typeVal = $this->_getAllLanguageServices_typeValidate($type);
		if ($typeVal == null) {
			return $this->getErrorResponsePayload('Not found.');
		}

		$handler = $this->ServiceSetting->getLangridServiceHandler();
		$params = array();
		$params['service_type'] = $typeVal;
		$params['delete_flag'] = '0';
		$objects =& $handler->search($params);

		if ($objects == null || count($objects) == 0) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($objects as $object) {
			$vo = new VO_LangridAccess_LanguageService();
			$vo->serviceId = $object->get('service_id');
			$vo->type = $object->get('service_type');
			$vo->serviceName = $object->get('service_name');
			$vo->description = $object->get('description');
			$vo->license = $object->get('license');
			$vo->endpintUrl = $object->get('endpoint_url');
			$vo->registrationDate = $object->get('registered_date');
			$vo->lastUpdate = $object->get('updated_date');

			$pairAry = explode(',', $object->get('supported_languages_paths'));
			$paths = array();
			foreach ($pairAry as $pair) {
				$pathAry = explode('2', $pair);
				$path = new VO_LangridAccess_LanguagePath();
				$path->languages = $pathAry;
				$paths[] = $path;
			}
			$vo->supportedLanguages = $paths;
			$ret[] = $vo;
		}
		return $this->getResponsePayload($ret);
	}

	private function _getAllLanguageServices_typeValidate($type) {
		if ($type == null || empty($type)) {
			return null;
		}
		$ret = null;
		switch ( strtolower($type) ) {
			case 'translation':
				$ret = 'TRANSLATION';
				break;
			case 'dictionary':
				$ret = 'DICTIONARY';
				break;
			case 'paralleltext':
				$ret = null;
				break;
			case 'morphological_analyzer':
				$ret = 'ANALYZER';
				break;
			default:
				break;
		}
		return $ret;
	}
}
?>