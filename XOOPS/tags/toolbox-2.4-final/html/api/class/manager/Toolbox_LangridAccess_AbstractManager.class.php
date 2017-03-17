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

abstract class Toolbox_LangridAccess_AbstractManager extends Toolbox_AbstractManager {

	/** This class is DB Wrapper */
	protected $ServiceSetting = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		$TBoxLGACP = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
		if (!file_exists($TBoxLGACP)) {
			die('DB Access Handler Class is not found.');
		}
		require_once($TBoxLGACP);
		$this->ServiceSetting =& new TranslationServiceSetting();
	}

	protected function getBindingSetIdByName($bindingSetName) {
		$handler =& $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByName($bindingSetName);
		return $object == null ? null : $object->get('set_id');
	}

	protected function getBindingSetObjectByName($bindingSetName) {
		$handler =& $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByNameWithDefualtSet($bindingSetName);
		return $object;
	}

	protected function TranslationSetObject2ResponseVO($object) {
		$bindingSet =& new ToolboxVO_LangridAccess_BindingSet();
		$bindingSet->name = $object->get('set_name');
		$bindingSet->bindingType = 'translation';
		$bindingSet->setType = $object->get('shared_flag') ? 'shared' : 'personal';
		return $bindingSet;
	}

	protected function TranslationPath2ResponseVO($pathObj) {
		$multihopTranslationBinding =& new ToolboxVO_LangridAccess_MultihopTranslationBinding();
		$multihopTranslationBinding->id = $pathObj->get('path_id');

		$translationBindingAry = array();
		$langPathAry = array();
		$langPathAry[] = $pathObj->get('source_lang');
		$execObjAry =& $pathObj->getExecs();
		foreach ($execObjAry as $execObj) {
			$langPathAry[] = $execObj->get('target_lang');
			$translationBinding =& new ToolboxVO_LangridAccess_TranslationBinding();
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

	protected function entryTranslationBindings($pathId, $translationBindings) {
		foreach ($translationBindings as $binding) {
			$sourceLang = $binding->sourceLang;
			$targetLang = $binding->targetLang;
			$serviceId = $binding->translationServiceId;

			$serviceType = $this->_isLangridTranslationService($serviceId) == true ? '0' : '1';
			$dictFlag = (
						count($binding->globalDictionaryServiceIds) +
						count($binding->localDictionaryServiceIds) +
						count($binding->temporalDictionaryNames)
						) == 0 ? '0' : '2';

			$execObj =& $this->ServiceSetting->addTranslationExec($pathId, $sourceLang, $targetLang, $serviceId, $serviceType, $dictFlag);

			$execId = $execObj->get('exec_id');

			$this->ServiceSetting->addTranslationBind($pathId, $execId, '9', $binding->morphologicalAnalysisServiceId);

			foreach ($binding->globalDictionaryServiceIds as $dict) {
				$this->ServiceSetting->addTranslationBind($pathId, $execId, '1', $dict);
			}
			foreach ($binding->localDictionaryServiceIds as $dict) {
				$endPoint = $this->_convertLocalName2EndPoint($dict);
				if ($endPoint != null) {
					$this->ServiceSetting->addTranslationBind($pathId, $execId, '2', $endPoint);
				}
			}
			foreach ($binding->temporalDictionaryNames as $dict) {
				$this->ServiceSetting->addTranslationBind($pathId, $execId, '3', $dict);
			}
		}
	}

	protected function _isLangridTranslationService($serviceId) {
		$dist =& $this->_getLangridService($serviceId, 'TRANSLATION');
		if ($dist == null) {
			return false;
		}
		return true;
	}

	protected function _getLangridService($serviceId, $type) {
		$handler =& $this->ServiceSetting->getLangridServiceHandler();
		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('service_id', $serviceId));
		$mc->add(new Criteria('service_type', $type));
		$mc->add(new Criteria('delete_flag', '0'));
		$obj =& $handler->getObjects($mc);
		if ($obj == null || count($obj) == 0) {
			return null;
		}
		return $obj[0];
	}

	protected function _convertLocalName2EndPoint($name) {
		require_once(XOOPS_ROOT_PATH.'/api/class/handler/CommunityResourceHandler.class.php');
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		$dictionaryHandler =& new CommunityResourceHandler($db);
		$serviceHandler =& $this->ServiceSetting->getLangridServiceHandler();

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('service_name', $name));
		$mc->add(new Criteria('service_type', 'IMPORTED_DICTIONARY'));
		$mc->add(new Criteria('delete_flag', '0'));
		$obj =& $serviceHandler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $obj[0]->get('endpoint_url');
		} else {
			$mc =& new CriteriaCompo();
			$mc->add(new Criteria('dictionary_name', $name));
			$mc->add(new Criteria('type_id', '0'));
			$mc->add(new Criteria('deploy_flag', '1'));
			$mc->add(new Criteria('delete_flag', '0'));
			$obj =& $dictionaryHandler->getObjects($mc);
			if ($obj != null && count($obj) > 0) {
				$_name = str_replace(' ', '_', $name);
				return XOOPS_URL.'/modules/dictionary/services/invoker/billingualdictionary.php?serviceId='.$_name;
			}
		}
		return null;
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