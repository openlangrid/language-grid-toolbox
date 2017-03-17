<?php

require_once(dirname(__FILE__).'/handler/TranslationSetHandler.class.php');
require_once(dirname(__FILE__).'/handler/TranslationPathHandler.class.php');
require_once(dirname(__FILE__).'/handler/TranslationExecHandler.class.php');
require_once(dirname(__FILE__).'/handler/TranslationBindHandler.class.php');
require_once(dirname(__FILE__).'/handler/LangridServicesHandler.class.php');
require_once(dirname(__FILE__).'/handler/DefaultDictionaryBindHandler.class.php');
require_once(dirname(__FILE__).'/handler/DefaultDictionarySettingHandler.class.php');
require_once(dirname(__FILE__).'/validate/ServiceSettingValidate.class.php');

class TranslationServiceSetting {

	protected $m_setHandler = null;
	protected $m_pathHandler = null;
	protected $m_execHandler = null;
	protected $m_bindHandler = null;
	protected $m_langridServiceHandler = null;
	protected $m_defaultDictionaryBindHandler = null;
	protected $m_defaultDictionarySettingHandler = null;

	/**
	 * Constructor method.
	 */
	function TranslationServiceSetting() {
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;

		// create db handler
		$this->m_setHandler = new TranslationSetHandler($db);
		$this->m_pathHandler = new TranslationPathHandler($db);
		$this->m_execHandler = new TranslationExecHandler($db);
		$this->m_bindHandler = new TranslationBindHandler($db);
		$this->m_langridServiceHandler = new LangridServicesHandler($db);
		$this->m_defaultDictionaryBindHandler = new DefaultDictionaryBindHandler($db);
		$this->m_defaultDictionarySettingHandler = new DefaultDictionarySettingHandler($db);
	}

	function getSetHandler() {
		return $this->m_setHandler;
	}
	function getPathHandler() {
		return $this->m_pathHandler;
	}
	function getExecHandler() {
		return $this->m_execHandler;
	}
	function getBindHandler() {
		return $this->m_bindHandler;
	}
	function getLangridServiceHandler() {
		return $this->m_langridServiceHandler;
	}

	/**
	 * getServiceSettings
	 *
	 * @param $userId
	 * @param $setId
	 * @param [$sourceLang]
	 * @param [$targetLang]
	 * @return array()
	 */
	function getServiceSettings($userId, $setId, $sourceLang = null, $targetLang = null) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$mCriteriaComp = new CriteriaCompo();
		$mCriteriaComp->add(new Criteria('user_id', $userId));
		$mCriteriaComp->add(new Criteria('set_id', $setId));
		if (!empty($sourceLang)) {
			$mCriteriaComp->add(new Criteria('source_lang', $sourceLang));
		}
		if (!empty($targetLang)) {
			$mCriteriaComp->add(new Criteria('target_lang', $targetLang));
		}

		$objects =& $this->m_pathHandler->getObjects($mCriteriaComp);

		if ($objects == null || count($objects) == 0) {
			return null;
		}

		return $objects;
	}

	/**
	 * loadServiceSetting
	 *
	 * @param $pathId
	 * @return pathexecbind
	 */
	function loadServiceSetting($pathId) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);

		return $this->m_pathHandler->get($pathId);
	}

	/**
	 * addTranslationPath
	 *
	 * @return TranslationPathObject
	 */
	function addTranslationPath($userId, $setId, $sourceLang, $targetLang, $revsPathId = null, $pathName = null) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);
		ServiceSettingValidate::validateRequireStringOrInteger($sourceLang);
		ServiceSettingValidate::validateRequireStringOrInteger($targetLang);

		$translationPath =& $this->m_pathHandler->create(true);
		$translationPath->set('user_id', $userId);
		$translationPath->set('set_id', $setId);
		$translationPath->set('source_lang', $sourceLang);
		$translationPath->set('target_lang', $targetLang);
		if (!empty($pathName)) {
			$translationPath->set('path_name', $pathName);
		}
		if (!empty($revsPathId)) {
			$translationPath->set('revs_path_id', $revsPathId);
		}
		if ($this->m_pathHandler->insert($translationPath, true)) {
			return $translationPath;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/**
	 * addTranslationExec
	 *
	 * @param $pathId 翻訳パスID
	 * @return TranslationExecObject
	 */
	function addTranslationExec($pathId, $sourceLang, $targetLang, $serviceId, $serviceType,$DictionaryFlag, $execOrder = null) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);
		ServiceSettingValidate::validateRequireStringOrInteger($sourceLang);
		ServiceSettingValidate::validateRequireStringOrInteger($targetLang);
		ServiceSettingValidate::validateRequireStringOrInteger($serviceId);

		$execId = 1;
		$order = 1;
		$currentExecs = $this->m_execHandler->getExecObjects($pathId);
		if ($currentExecs != null) {
			$execId = count($currentExecs) + 1;
			$order = $currentExecs[count($currentExecs)-1]->get('exec_order') + 1;
		}
		if ($execOrder != null) {
			$order = $execOrder;
		}

		$translationExec =& $this->m_execHandler->create(true);
		$translationExec->set('path_id', $pathId);
		$translationExec->set('exec_id', $execId);
		$translationExec->set('exec_order', $order);
		$translationExec->set('source_lang', $sourceLang);
		$translationExec->set('target_lang', $targetLang);
		$translationExec->set('service_id', $serviceId);
		$translationExec->set('service_type', $serviceType);
		$translationExec->set('dictionary_flag', $DictionaryFlag);

		if ($this->m_execHandler->insert($translationExec, true)) {
			return $translationExec;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/**
	 * addTranslationBind
	 *
	 * @param $pathId
	 * @param $execId
	 * @return TranslationBindObject
	 */
	function addTranslationBind($pathId, $execId, $type, $bind) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);
		ServiceSettingValidate::validateRequireStringOrInteger($execId);
		ServiceSettingValidate::validateRequireStringOrInteger($type);
		ServiceSettingValidate::validateRequireStringOrInteger($bind);


		$bindId = 1;
		$currentBinds = $this->m_bindHandler->getBindObjects($pathId, $execId);
		if ($currentBinds != null) {
			$bindId = count($currentBinds) + 1;
		}

		$translationBind =& $this->m_bindHandler->create(true);
		$translationBind->set('path_id', $pathId);
		$translationBind->set('exec_id', $execId);
		$translationBind->set('bind_id', $bindId);
		$translationBind->set('bind_type', $type);
		$translationBind->set('bind_value', $bind);

		if ($this->m_bindHandler->insert($translationBind, true)) {
			return $translationBind;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/**
	 * linkReverse
	 */
	function linkReverse($pathId1, $pathId2) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId1);
		ServiceSettingValidate::validateRequireStringOrInteger($pathId2);

		$pathObj1 =& $this->m_pathHandler->get($pathId1);
		$pathObj2 =& $this->m_pathHandler->get($pathId2);
		if ($pathObj1 == null || $pathObj2 == null) {
			die('TranslationPathObject is not found.');
		}

		$pathObj1->set('revs_path_id', $pathObj2->get('path_id'));
		$pathObj2->set('revs_path_id', $pathObj1->get('path_id'));

		if (!$this->m_pathHandler->insert($pathObj1, true)) {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
		if (!$this->m_pathHandler->insert($pathObj2, true)) {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}

		return true;
	}

	/**
	 * removeTranslationPath
	 */
	function removeTranslationPath($pathId) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);


		$translationPath =& $this->m_pathHandler->get($pathId);
		if ($translationPath != null) {
			$userId = $translationPath->get('user_id');
			$setId = $translationPath->get('set_id');

			foreach ($translationPath->getExecs() as $exec) {
				foreach ($exec->getBinds() as $bind) {
					$this->m_bindHandler->delete($bind, true);
				}
				$this->m_execHandler->delete($exec, true);
			}
			$this->m_pathHandler->delete($translationPath, true);

			$mCriteriaComp = new CriteriaCompo();
			$mCriteriaComp->add(new Criteria('set_id', $setId));

			$objects =& $this->m_pathHandler->getObjects($mCriteriaComp);
			if ($objects == null || count($objects) == 0) {
				$this->removeTranslationSet($setId);
				$this->removeDefaultDictionary($userId,$setId);
			}
		}
		return true;
	}

	/**
	 * removeTranslationExec
	 */
	function removeTranslationExec($pathId, $execId) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);
		ServiceSettingValidate::validateRequireStringOrInteger($execId);

		$compsit = array('path_id'=>$pathId, 'exec_id'=>$execId);
		$translationExec =& $this->m_execHandler->get($compsit);
		if ($translationExec != null) {
			foreach ($translationExec->getBinds() as $bind) {
				$this->m_bindHandler->delete($bind, true);
			}
			$this->m_execHandler->delete($translationExec, true);
		}
		return true;
	}

	/**
	 * removeTranslationBind
	 */
	function removeTranslationBind($pathId, $execId, $bindId) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);
		ServiceSettingValidate::validateRequireStringOrInteger($execId);
		ServiceSettingValidate::validateRequireStringOrInteger($bindId);

		$compsit = array('path_id'=>$pathId, 'exec_id'=>$execId, 'bind_id'=>$bindId);
		$translationBind =& $this->m_bindHandler->get($compsit);
		if ($translationBind != null) {
			$this->m_bindHandler->delete($translationBind, true);
		}
		return true;
	}

	function removeTranslationSet($setId) {
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$mc = new CriteriaCompo();
		$mc->add(new Criteria('set_id', $setId));
		$objects =& $this->m_setHandler->getObjects($mc);
		if(is_array($objects)){
			foreach($objects as $Obj){
				$this->m_setHandler->delete($Obj, true);
			}
		}
		return true;
	}

	/**
	 * @param $translationPathObject
	 * @param $bdeep
	 * @return bool
	 */
	function update($translationPathObject, $bdeep = true) {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');

		if ($translationPathObject != null) {
			if ($bdeep) {
				foreach ($translationPathObject->getExecs() as $exec) {
					foreach ($exec->getBinds() as $bind) {
						$bind->set('update_user_id', $uid);
						$bind->set('update_time', time());
						$this->m_bindHandler->insert($bind, true);
					}
					$exec->set('update_user_id', $uid);
					$exec->set('update_time', time());
					$this->m_execHandler->insert($exec, true);
				}
			}
			$translationPathObject->set('update_user_id', $uid);
			$translationPathObject->set('update_time', time());
			$this->m_pathHandler->insert($translationPathObject, true);
		}
		return true;
	}

	function updateLocalTranslation($oldTranslationEndpointUrl, $newTranslationEndpointUrl, $langaugePaths) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('service_type', 1));
		$criteriaCompo->add(new Criteria('service_id', $oldTranslationEndpointUrl));
		$translationExecs =& $this->m_execHandler->getObjects($criteriaCompo);
		$langaugePaths = explode(',', $langaugePaths);
		if ($translationExecs != null) {
			foreach ($translationExecs as $translationExec) {
				if (!in_array(
				$translationExec->get('source_lang')
				.'2'.$translationExec->get('target_lang')
				, $langaugePaths)
				) {
					$this->removeTranslationPath($translationExec->get('path_id'));
				} else {
					$translationExec->set('service_id', $newTranslationEndpointUrl);
					$this->m_execHandler->insert($translationExec);
				}
			}
		}
		return true;
	}

	function removeLocalTranslation($translationEndpointUrl) {
		$criteria = new Criteria('service_id', $translationEndpointUrl);
		$translationExecs =& $this->m_execHandler->getObjects($criteria);
		if ($translationExecs != null) {
			foreach ($translationExecs as $translationExec) {
				$this->removeTranslationPath($translationExec->get('path_id'));
			}
		}
		return true;
	}

	function updateLocalDictionary($oldDictionaryEndpointUrl, $newDictionaryEndpointUrl) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('bind_type', 2));
		$criteriaCompo->add(new Criteria('bind_value', $oldDictionaryEndpointUrl));

		$binds =& $this->m_bindHandler->getObjects($criteriaCompo);
		if ($binds != null) {
			foreach ($binds as $bind) {
				$bind->set('bind_value', $newDictionaryEndpointUrl);
				$this->m_bindHandler->insert($bind);
			}
		}
		$binds =& $this->m_defaultDictionaryBindHandler->getObjects($criteriaCompo);
		if ($binds != null) {
			foreach ($binds as $bind) {
				$bind->set('bind_value', $newDictionaryEndpointUrl);
				$this->m_defaultDictionaryBindHandler->insert($bind);
			}
		}
		return true;
	}

	function removeLocalDictionary($dictionaryEndpointUrl) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('bind_type', 2));
		$criteriaCompo->add(new Criteria('bind_value', $dictionaryEndpointUrl));
		$this->m_bindHandler->deleteAll($criteriaCompo);
		$this->m_defaultDictionaryBindHandler->deleteAll($criteriaCompo);
		return true;
	}

	function removeTemporalDictionary($dictionaryName) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('bind_type', 3));
		$criteriaCompo->add(new Criteria('bind_value', $dictionaryName));
		$this->m_bindHandler->deleteAll($criteriaCompo);
		$this->m_defaultDictionaryBindHandler->deleteAll($criteriaCompo);
		return true;
	}

	function loadDefaultDictionary($userId,$setId){
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$mCriteriaComp = new CriteriaCompo();
		$mCriteriaComp->add(new Criteria('user_id', $userId));
		$mCriteriaComp->add(new Criteria('set_id', $setId));
		$objects =& $this->m_defaultDictionarySettingHandler->getObjects($mCriteriaComp);
		if ($objects == null || count($objects) == 0) {
			return null;
		}else{
			return $objects[0];
		}

	}

	function removeDefaultDictionary($userId,$setId) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$DefaultSetting =& $this->loadDefaultDictionary($userId,$setId);
		if ($DefaultSetting != null) {
			foreach ($DefaultSetting->getBinds() as $bind) {
				$this->m_defaultDictionaryBindHandler->delete($bind, true);
			}
			$this->m_defaultDictionarySettingHandler->delete($DefaultSetting, true);
		}
		return true;
	}

	function removeDefaultDictionaryBind($settingId) {
		ServiceSettingValidate::validateRequireStringOrInteger($settingId);

		$DefaultSetting =& $this->m_defaultDictionarySettingHandler->get($settingId);
		if ($DefaultSetting != null) {
			foreach ($DefaultSetting->getBinds() as $bind) {
				$this->m_defaultDictionaryBindHandler->delete($bind, true);
			}
		}
		return true;
	}

	function addDefaultDictionarySetting($userId, $setId) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$DefaultDictionarySetting =& $this->m_defaultDictionarySettingHandler->create(true);
		$DefaultDictionarySetting->set('user_id', $userId);
		$DefaultDictionarySetting->set('set_id', $setId);
		if ($this->m_defaultDictionarySettingHandler->insert($DefaultDictionarySetting, true)) {
			return $DefaultDictionarySetting;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	function addDefaultDictionaryBind($settingId, $type, $bind) {
		ServiceSettingValidate::validateRequireStringOrInteger($settingId);
		ServiceSettingValidate::validateRequireStringOrInteger($type);
		ServiceSettingValidate::validateRequireStringOrInteger($bind);

		$bindId = 1;
		$currentBinds = $this->m_defaultDictionaryBindHandler->getBindObjects($settingId);
		if ($currentBinds != null) {
			$bindId = count($currentBinds) + 1;
		}

		$DefaultDictionaryBind =& $this->m_defaultDictionaryBindHandler->create(true);
		$DefaultDictionaryBind->set('setting_id', $settingId);
		$DefaultDictionaryBind->set('bind_id', $bindId);
		$DefaultDictionaryBind->set('bind_type', $type);
		$DefaultDictionaryBind->set('bind_value', $bind);

		if ($this->m_defaultDictionaryBindHandler->insert($DefaultDictionaryBind, true)) {
			return $DefaultDictionaryBind;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	function updateDefaultDict4TranslationPath($userId,$setId){
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);

		$DefaultDict = $this->loadDefaultDictionary($userId,$setId);
		$dicts = array(1=>array(),2=>array(),3=>array());
		$binds = $DefaultDict->getBinds();
		if(is_array($binds)){
			foreach($binds as $bind){
				$btype = $bind->get('bind_type');
				if($btype != 9){
					$dicts[$btype][] = $bind->get('bind_value');
				}
			}
		}

		$userIds = array();
		$userIds[] = $userId;
		if($userId == 1){
			$mCriteriaComp = new CriteriaCompo();
			$mCriteriaComp->add(new Criteria('user_id', $userId,'!='));
			$mCriteriaComp->add(new Criteria('set_id', $setId));

			$objects =& $this->m_pathHandler->getObjects($mCriteriaComp);
			if(is_array($objects)){
				foreach($objects as $path){
					$mUid = $path->get('user_id');
					$myDefDict = $this->loadDefaultDictionary($mUid,$setId);
					if($myDefDict == null){
						$userIds[] = $mUid;
					}
				}
			}
		}

		foreach($userIds as $updateid){
			$mCriteriaComp = new CriteriaCompo();
			$mCriteriaComp->add(new Criteria('user_id', $updateid));
			$mCriteriaComp->add(new Criteria('set_id', $setId));

			$objects =& $this->m_pathHandler->getObjects($mCriteriaComp);
			if(is_array($objects)){
				foreach($objects as $path){
					$execs = $path->getExecs();
					if(is_array($execs)){
						foreach($execs as $exec){
							$dflg = $exec->get('dictionary_flag');
							if(intval($dflg) == 1 || intval($dflg) == 0){
								$binds = $exec->getBinds();
								if(is_array($binds)){
									foreach($binds as $bind){
										$btype = $bind->get('bind_type');
										if($btype == 1 || $btype == 2 || $btype == 3){
											$this->m_bindHandler->delete($bind, true);
										}
									}
								}

								if(count($dicts[1]) == 0 && count($dicts[2]) == 0 && count($dicts[3]) == 0){
									$exec->set('dictionary_flag', 0);
									$exec->set('update_user_id', $userId);
									$bind->set('update_time', time());
									$this->m_execHandler->insert($exec, true);
								}else{
									foreach($dicts as $btype => $dict_array){
										foreach($dict_array as $dict){
											$this->addTranslationBind($path->get('path_id'),$exec->get('exec_id'),$btype,$dict);
										}
									}
									$exec->set('dictionary_flag', 1);
									$exec->set('update_user_id', $userId);
									$bind->set('update_time', time());
									$this->m_execHandler->insert($exec, true);
								}
							}
						}
					}
				}
			}
		}
	}


    function getSetIdByRequestModule() {
		$root =& XCube_Root::getSingleton();

		$setObj = null;
		$uid = 0;
		$modName = '';

		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		if (!$root->mContext->mModule == null) {
			$modName = $root->mContext->mModule->mXoopsModule->get('dirname');
		}

		$setName = '';
		$isShared = false;
		switch ( $modName ) {
			case 'forum':
			case 'showroombbs':
			case 'communication':
			case 'showroomcom':
				$setName = 'SITE';
				$isShared = true;
				break;
			case 'langrid':
			case 'document':
			case 'webtrans':
			case 'web_translation':
			case 'web_creation':
				$setName = 'USER';
				break;
			default:
				$setName = 'SITE';
				$isShared = true;
				break;

//			case 'forum':
//			case 'showroombbs':
//				$setName = 'BBS';
//				$isShared = true;
//				break;
//			case 'document':
//				$setName = 'TEXT_TRANSLATION';
//				break;
//			case 'langrid':
//				$setName = 'TEXT_TRANSLATION';
//				break;
//			case 'webtrans':
//			case 'web_translation':
//			case 'web_creation':
//				$setName = 'WEB_TRANSLATION';
//				break;
//			case 'communication':
//			case 'showroomcom':
//				$setName = 'COMMUNICATION';
//				break;
//			default:
//				$setName = 'ALL';
//				$isShared = true;
//				break;
		}

		$mc = new CriteriaCompo();
		$mc->add(new Criteria('set_name', $setName));
		if (!$isShared) {
			$mc->add(new Criteria('user_id', $uid));
		}
		$objects =& $this->m_setHandler->getObjects($mc);
		if (($objects == null || count($objects) == 0) && $uid != '1') {
			$mc = new CriteriaCompo();
			$mc->add(new Criteria('set_name', $setName));
			$mc->add(new Criteria('user_id', '1'));
			$objects =& $this->m_setHandler->getObjects($mc);
		}
		if ($objects != null && count($objects) > 0) {
			//$setId = $objects[0]->get('set_id');
			$setObj = $objects[0];
		} else {
			//die('Translation Set is not found.|'.__FILE__.'('.__LINE__.')');
		}
		return $setObj;
	}

	function addTranslationSet($userId,$setName,$sharedFlag = 0) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setName);

		$translationSet =& $this->m_setHandler->create(true);
		$translationSet->set('user_id', $userId);
		$translationSet->set('set_name', $setName);
		if(is_numeric($sharedFlag)){
			$translationSet->set('shared_flag', $sharedFlag);
		}
		if ($this->m_setHandler->insert($translationSet, true)) {
			return $translationSet;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	function getSetIdByName($UserID,$setName){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('user_id', $UserID));
		$mc->add(new Criteria('set_name', $setName));
		$SetObjs =& $this->m_setHandler->getObjects($mc);
		if($SetObjs == null || count($SetObjs) == 0){
			$SetID = null;
		}else{
			$SetID = $SetObjs[0]->get('set_id');
		}

		return $SetID;
	}

	function getAndCreateSetIdByName($UserID,$setName){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('user_id', $UserID));
		$mc->add(new Criteria('set_name', $setName));
		$SetObjs =& $this->m_setHandler->getObjects($mc);
		if($SetObjs == null || count($SetObjs) == 0){
			$SetID = "";
			$mc = new CriteriaCompo();
			$mc->add(new Criteria('set_name', $setName));
			$objects =& $this->m_setHandler->getObjects($mc);
			foreach($objects as $SetObj){
				if($SetObj->get('shared_flag') == 1){
					$SetID = $SetObj->get('set_id');
					break;
				}
			}
			if($SetID == ""){
				$SetObj = null;
				$SetObj = $this->addTranslationSet($UserID,$setName,0);
				if($SetObj != null){
					$SetID = $SetObj->get('set_id');
				}
			}
		}else{
			$SetID = $SetObjs[0]->get('set_id');
		}

		return $SetID;
	}

}
?>