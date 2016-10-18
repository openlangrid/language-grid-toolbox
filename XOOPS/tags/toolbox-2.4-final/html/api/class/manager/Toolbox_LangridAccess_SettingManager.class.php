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
require_once(XOOPS_ROOT_PATH.'/modules/langrid/include/Functions.php');

class Toolbox_LangridAccess_SettingManager extends Toolbox_LangridAccess_AbstractManager {

	/**
	 * getAllBindingSets
	 *
	 * @param $bindingType [translation]
	 */
	public function getAllBindingSets($bindingType = null) {
		switch(strtoupper($bindingType)){
			case "TRANSLATION":
				break;
			default:
				break;
		}

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
		$handler = $this->ServiceSetting->getSetHandler();
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('set_name', $bindingSetName));
		$mc->add(new Criteria('user_id', $uid));
		$obj =& $handler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $this->getErrorResponsePayload('The name is already in use.');
		}

		$object =& $handler->create(true);
		$object->set('set_name', $bindingSetName);
		$object->set('user_id', $uid);
		$object->set('shared_flag', '0');
		if (!$handler->insert($object, true)) {
			return $this->getErrorResponsePayload('SQL Error');
		}
		return $this->getResponsePayload($this->TranslationSetObject2ResponseVO($object));
	}

	/**
	 * deleteBindingSet
	 */
	public function deleteBindingSet($bindingSetName) {
		$handler = $this->ServiceSetting->getSetHandler();
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('set_name', $bindingSetName));
		$mc->add(new Criteria('user_id', $uid));
		$objects =& $handler->getObjects($mc);
		foreach ($objects as $obj) {
			$handler->delete($obj, true);
		}
		return $this->getResponsePayload(true);
		//return true;
	}

	/**
	 * getTranslationPaths
	 */
	public function getTranslationPaths($bindingSetName) {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$setId = $this->getBindingSetIdByName($bindingSetName);
		if($setId == null){
			return $this->getErrorResponsePayload('Not found.');
		}

		$objects =& $this->ServiceSetting->getServiceSettings($uid, $setId);
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
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$setId = $this->getBindingSetIdByName($bindingSetName);
		if ($setId == null) {
			return $this->getErrorResponsePayload('BindingSetName is not found.');
		}

		$sourceLang = $languagePath[0];
		$targetLang = $languagePath[count($languagePath) - 1];
		$pathObj =& $this->ServiceSetting->addTranslationPath($uid, $setId, $sourceLang, $targetLang);

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
	 * @param $withLanguageName bool - 結果に言語表示名を含めるか否か.
	 * @see /html/modules/langrid/class/get-supported-language-pair-class.php互換性を維持
	 */
	public function getSupportedTranslationPathLanguagePairs($bindingSetName, $withLanguageName = false) {
		//$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		//$setId = $this->getBindingSetIdByName($bindingSetName);
		$set = $this->getBindingSetObjectByName($bindingSetName);
		if ($set == null) {
			return $this->getErrorResponsePayload('BindingSetObject is not found.');
		}
		$setId = $set->get('set_id');
		$uid = $set->get('user_id');
		if ($setId == null) {
			return $this->getErrorResponsePayload('BindingSetName is not found.');
		}

		$objects =& $this->ServiceSetting->getServiceSettings($uid, $setId);
		if ($objects == null) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($objects as $object) {
			$ret[] = array($object->get('source_lang'), $object->get('target_lang'));
		}
		if ($withLanguageName) {
			$withNames = array();
			foreach ($ret as $pair) {
				$withNames[] = array(array('code' => $pair[0], 'name' => getLanguageName($pair[0]))
						,	array('code' => $pair[1], 'name' => getLanguageName($pair[1])));
			}
			return $this->getResponsePayload($withNames);
		} else {
			return $this->getResponsePayload($ret);
		}
	}

	public function getAllLanguageServices($type = null) {
		$typeVal = $this->_getAllLanguageServices_typeValidate($type);
		if ($typeVal == null) {
			//return $this->getErrorResponsePayload('Not found.');
		}

		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/handler/LangridServicesHandler.class.php';
		if (!file_exists($file)) {
			return $this->getErrorResponsePayload('LangridServicesHandler.class.php file not found.');
		}
		require_once($file);
		$handler =& new LangridServicesHandler($this->db);
		$mc =& new CriteriaCompo();
		if ($typeVal != null) {
			$mc->add(new Criteria('service_type', $typeVal));
		}
		$mc->add(new Criteria('delete_flag', '0'));
		$objects =& $handler->getObjects($mc);
		if ($objects == null || count($objects) == 0) {
			return $this->getErrorResponsePayload('Not found.');
		}
		$ret = array();
		foreach ($objects as $object) {
			$vo = new ToolboxVO_LangridAccess_LanguageService();
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
				$path = new ToolboxVO_LangridAccess_LanguagePath();
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