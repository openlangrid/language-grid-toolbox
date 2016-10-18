<?php
require_once(dirname(__FILE__).'/../adapter/DaoAdapter.class.php');

class TranslationPathDbHandler {
	protected $db = null;
	protected $m_SetDao = null;
	protected $m_PathDao = null;
	protected $m_ExecDao = null;
	protected $m_BindDao = null;
	protected $m_DefaultDictionaryBindDao = null;
	protected $m_DefaultDictionarySettingDao = null;
	protected $m_LangridServiceDao = null;
    public function __construct() {
		// Set Adapter
		$adapter = DaoAdapter::getAdapter();
    	$this->m_SetDao = $adapter->getTranslationSetDao();
    	$this->m_PathDao = $adapter->getTranslationPathDao();
    	$this->m_ExecDao = $adapter->getTranslationExecDao();
    	$this->m_BindDao = $adapter->getTranslationBindDao();
		$this->m_DefaultDictionaryBindDao = $adapter->getDefaultDictionaryBindDao();
		$this->m_DefaultDictionarySettingDao = $adapter->getDefaultDictionarySettingDao();
    	$this->m_LangridServiceDao = $adapter->getLangridServicesDao();
    }
	public function getSetDao() {
		return $this->m_SetDao;
	}
	public function getPathDao() {
		return $this->m_PathDao;
	}
	public function getExecDao() {
		return $this->m_ExecDao;
	}
	public function getBindDao() {
		return $this->m_BindDao;
	}
	public function getDefaultDictionaryBindDao() {
		return $this->m_DefaultDictionaryBindDao;
	}
	public function getDefaultDictionarySettingDao() {
		return $this->m_DefaultDictionarySettingDao;
	}
	public function getLangridServiceDao() {
		return $this->m_LangridServiceDao;
	}
	/** @OK
 	 * <#if locale="en">
	 * Return translation setting
	 * 　Return translation setting which matches the conditions spedified in the parameters
 	 * <#elseif locale="ja">
	 * 翻訳設定を返す
	 * 　引数で指定された条件に合致する翻訳設定を返します。
	 * </#if>
	 *
	 * @param $userId
	 * @param $setId
	 * @param [$sourceLang]
	 * @param [$targetLang]
	 * @return array()
	 */
	function getServiceSettings($userId, $setId, $sourceLang = null, $targetLang = null) {
		$params = array();
		$params['user_id'] = $userId;
		$params['set_id'] = $setId;
		if ($sourceLang != null) {
			$params['source_lang'] = $sourceLang;
		}
		if ($targetLang != null) {
			$params['target_lang'] = $targetLang;
		}
		$objects =& $this->m_PathDao->search($params);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects;
	}

	/** @OK
 	 * <#if locale="en">
	 * Return translation setting, its related execution setting and binding setting
 	 * <#elseif locale="ja">
	 * 翻訳設定とそれに関連する実行設定、バインド設定を返す
	 * </#if>
	 *
	 * @param $pathId
	 * @return pathexecbind
	 */
	function loadServiceSetting($pathId) {
		return $this->m_PathDao->get($pathId);
	}

	/** @OK
 	 * <#if locale="en">
	 * Register translation path
 	 * <#elseif locale="ja">
	 * 翻訳パスを登録
	 * </#if>
	 *
	 * @return TranslationPathObject
	 */
	function addTranslationPath($userId, $setId, $sourceLang, $targetLang, $revsPathId = null, $pathName = null) {
		$translationPath =& $this->m_PathDao->create(true);
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
		if ($this->m_PathDao->insert($translationPath, true)) {
			return $translationPath;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/** @OK
 	 * <#if locale="en">
	 * Register translation execution setting
	 *
	 * @param $pathId Translation path ID
 	 * <#elseif locale="ja">
	 * 翻訳実行設定を登録
	 *
	 * @param $pathId 翻訳パスID
	 * </#if>
	 * @return TranslationExecObject
	 */
	function addTranslationExec($pathId, $sourceLang, $targetLang, $serviceId, $serviceType,$DictionaryFlag, $execOrder = null) {

		$execId = 1;
		$order = 1;
		$currentExecs = $this->m_ExecDao->getExecObjects($pathId);
		if ($currentExecs != null) {
			$execId = count($currentExecs) + 1;
			$order = $currentExecs[count($currentExecs)-1]->get('exec_order') + 1;
		}
		if ($execOrder != null) {
			$order = $execOrder;
		}

		$translationExec =& $this->m_ExecDao->create(true);
		$translationExec->set('path_id', $pathId);
		$translationExec->set('exec_id', $execId);
		$translationExec->set('exec_order', $order);
		$translationExec->set('source_lang', $sourceLang);
		$translationExec->set('target_lang', $targetLang);
		$translationExec->set('service_id', $serviceId);
		$translationExec->set('service_type', $serviceType);
		$translationExec->set('dictionary_flag', $DictionaryFlag);

		if ($this->m_ExecDao->insert($translationExec, true)) {
			return $translationExec;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/** @OK
 	 * <#if locale="en">
	 * Register translation binding setting
 	 * <#elseif locale="ja">
	 * 翻訳バインド設定を登録
	 * </#if>
	 *
	 * @param $pathId
	 * @param $execId
	 * @return TranslationBindObject
	 */
	function addTranslationBind($pathId, $execId, $type, $bind) {
		$bindId = 1;
		$currentBinds = $this->m_BindDao->getBindObjects($pathId, $execId);
		if ($currentBinds != null) {
			$bindId = count($currentBinds) + 1;
		}

		$translationBind =& $this->m_BindDao->create(true);
		$translationBind->set('path_id', $pathId);
		$translationBind->set('exec_id', $execId);
		$translationBind->set('bind_id', $bindId);
		$translationBind->set('bind_type', $type);
		$translationBind->set('bind_value', $bind);

		if ($this->m_BindDao->insert($translationBind, true)) {
			return $translationBind;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/**
 	 * <#if locale="en">
	 * Relate two translation settings as a back translation path
 	 * <#elseif locale="ja">
	 * ２個の翻訳設定を折返しに関連付ける
	 * </#if>
	 */
	function linkReverse($pathId1, $pathId2) {
		$pathObj1 =& $this->m_PathDao->get($pathId1);
		$pathObj2 =& $this->m_PathDao->get($pathId2);
		if ($pathObj1 == null || $pathObj2 == null) {
			die('TranslationPathObject is not found.');
		}

		$pathObj1->set('revs_path_id', $pathObj2->get('path_id'));
		$pathObj2->set('revs_path_id', $pathObj1->get('path_id'));

		if (!$this->m_PathDao->update($pathObj1, true)) {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
		if (!$this->m_PathDao->update($pathObj2, true)) {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}

		return true;
	}

	/**
 	 * <#if locale="en">
	 * Physically delete translation path and its related records
 	 * <#elseif locale="ja">
	 * 翻訳パス（とそれに関連する）レコードを物理削除
	 * </#if>
	 */
	function removeTranslationPath($pathId) {
		$translationPath =& $this->m_PathDao->get($pathId);
		if ($translationPath != null) {
//			$userId = $translationPath->get('user_id');
//			$setId = $translationPath->get('set_id');

			foreach ($translationPath->getExecs() as $exec) {
				foreach ($exec->getBinds() as $bind) {
					$this->m_BindDao->delete($bind, true);
				}
				$this->m_ExecDao->delete($exec, true);
			}
			$this->m_PathDao->delete($translationPath, true);
		}
		return true;
	}

	/**
 	 * <#if locale="en">
	 * Physically delete translation execution setting and its related records
 	 * <#elseif locale="ja">
	 * 翻訳実行設定（とそれに関連する）レコードを物理削除
	 * </#if>
	 */
	function removeTranslationExec($pathId, $execId) {
		$compsit = array('path_id'=>$pathId, 'exec_id'=>$execId);
		$translationExec =& $this->m_ExecDao->get($compsit);
		if ($translationExec != null) {
			foreach ($translationExec->getBinds() as $bind) {
				$this->m_BindDao->delete($bind, true);
			}
			$this->m_ExecDao->delete($translationExec, true);
		}
		return true;
	}

	/**
 	 * <#if locale="en">
	 * Physically delete translation binding setting records
 	 * <#elseif locale="ja">
	 * 翻訳バインド設定レコードを物理削除
	 * </#if>
	 */
	function removeTranslationBind($pathId, $execId, $bindId) {
		$compsit = array('path_id'=>$pathId, 'exec_id'=>$execId, 'bind_id'=>$bindId);
		$translationBind =& $this->m_BindDao->get($compsit);
		if ($translationBind != null) {
			$this->m_BindDao->delete($translationBind, true);
		}
		return true;
	}

	function removeTranslationSet($setId) {
		$translationSet =& $this->m_SetDao->get($serId);
		if ($translationSet != null) {
			$this->m_SetDao->delete($translationSet, true);
		}
		return true;
	}

	/**
 	 * <#if locale="en">
	 * Save translation setting and included information in DB
	 *
	 * @param $translationPathObject
	 * @param $bdeep Update included object
 	 * <#elseif locale="ja">
	 * 翻訳設定（とそれが内包する情報）をDB保存
	 *
	 * @param $translationPathObject
	 * @param $bdeep 内包するオブジェクトの更新を行う
	 * </#if>
	 * @return bool
	 */
	function update($translationPathObject, $bdeep = true) {
		//$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');

		if ($translationPathObject != null) {
			$translationPathObject->set('update_time', time());
			$this->m_PathDao->update($translationPathObject, true);
		}
		return true;
	}

	// use by langrid/class/DefaultDictionariesClass.php::saveDafaultDictionary
	function loadDefaultDictionary($userId,$setId){
		$params = array(
			'user_id' => $userId,
			'set_id' => $setId
		);
//		$objects =& $this->m_DefaultDictionarySettingDao->getObjects($mCriteriaComp);
		$objects =& $this->m_DefaultDictionarySettingDao->search($params);
		if ($objects == null || count($objects) == 0) {
			return null;
		}else{
			return $objects[0];
		}
	}

	/**
 	 * <#if locale="en">
	 * Physically delete default dictionary and its related records
 	 * <#elseif locale="ja">
	 * デフォルト辞書（とそれに関連する）レコードを物理削除
	 * </#if>
	 */
	function removeDefaultDictionary($userId,$setId) {
	}

	// use by langrid/class/DefaultDictionariesClass.php::saveDafaultDictionary
	function removeDefaultDictionaryBind($settingId) {
		$DefaultSetting =& $this->m_DefaultDictionarySettingDao->get($settingId);
		if ($DefaultSetting != null) {
			foreach ($DefaultSetting->getBinds() as $bind) {
				$this->m_DefaultDictionaryBindDao->delete($bind, true);
			}
		}
		return true;
	}

	// use by langrid/class/DefaultDictionariesClass.php::saveDafaultDictionary
	function addDefaultDictionarySetting($userId, $setId) {
		$DefaultDictionarySetting =& $this->m_DefaultDictionarySettingDao->create(true);
		$DefaultDictionarySetting->set('user_id', $userId);
		$DefaultDictionarySetting->set('set_id', $setId);
		if ($this->m_DefaultDictionarySettingDao->insert($DefaultDictionarySetting, true)) {
			return $DefaultDictionarySetting;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	// use by langrid/class/DefaultDictionariesClass.php::saveDafaultDictionary
	function addDefaultDictionaryBind($settingId, $type, $bind) {
		$bindId = 1;
		$currentBinds = $this->m_DefaultDictionaryBindDao->getBindObjects($settingId);
		if ($currentBinds != null) {
			$bindId = count($currentBinds) + 1;
		}

		$DefaultDictionaryBind =& $this->m_DefaultDictionaryBindDao->create(true);
		$DefaultDictionaryBind->set('setting_id', $settingId);
		$DefaultDictionaryBind->set('bind_id', $bindId);
		$DefaultDictionaryBind->set('bind_type', $type);
		$DefaultDictionaryBind->set('bind_value', $bind);

		if ($this->m_DefaultDictionaryBindDao->insert($DefaultDictionaryBind, true)) {
			return $DefaultDictionaryBind;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	function updateDefaultDict4TranslationPath($userId,$setId){
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
		$objects =& $this->m_PathDao->search(array('set_id' => $setId));
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
										$this->m_BindDao->delete($bind, true);
									}
								}
							}

							if(count($dicts[1]) == 0 && count($dicts[2]) == 0 && count($dicts[3]) == 0){
								$exec->set('dictionary_flag', 0);
								$exec->set('update_user_id', $userId);
								$bind->set('update_time', time());
								$this->m_ExecDao->insert($exec, true);
							}else{
								foreach($dicts as $btype => $dict_array){
									foreach($dict_array as $dict){
										$this->addTranslationBind($path->get('path_id'),$exec->get('exec_id'),$btype,$dict);
									}
								}
								$exec->set('dictionary_flag', 1);
								$exec->set('update_user_id', $userId);
								$bind->set('update_time', time());
								$this->m_ExecDao->insert($exec, true);
							}
						}
					}
				}
			}
		}
	}


    /**
      * <#if locale="en">
      * Get SetID based on requested module and login user
      * <#elseif locale="ja">
      * リクエストのあったモジュールとログインユーザを基に、SetIDを取得
      * </#if>
      */
    function getSetIdByRequestModule() {
    	return "1";
    	/*
         * <#if locale="en">
         * TODO: This function is for automatic judgement of SetID based on Xoops access basic module. It might be no longer required when moving to MediaWiki. However it is currently remained to avoid some unexpected problems.
         * <#elseif locale="ja">
	 	 * TODO:この関数は、Xoopsのアクセス基モジュールによって、セットIDを自動判別するためのもので、MediaWiki移行に伴い、不要となりましたが、関数が無くなるとそれはそれで、おかしくなる可能性があるので、のこしております。
         * </#if>
	 */
	}

	function checkTranslationSetIdByPageSetting($articleId) {
		$setObj =& $this->m_SetDao->get($articleId);
		if ($setObj == null) {
			$newObj =& $this->m_SetDao->create(true);
			$newObj->set('set_id', $articleId);
			$newObj->set('set_name', 'Page');
			$newObj->set('user_id', '0');
			$newObj->set('shared_flag', '0');
			$this->m_SetDao->insert($newObj, true);
			return $newObj;
		} else {
			return $setObj;
		}
		return false;
	}

	/**
     * <#if locale="en">
	 * Register specified translation set
     * <#elseif locale="ja">
	 * 指定条件の翻訳セットを登録する。
     * </#if>
	 *  use by API
	 */
	function addTranslationSet($userId,$setName,$sharedFlag = 0) {

		$objects =& $this->m_SetDao->search(array('set_name' => $setName));
		if ($objects != null && count($objects) > 0) {
			return $objects[0];
		}

		$translationSet =& $this->m_SetDao->create(true);
		$translationSet->set('user_id', $userId);
		$translationSet->set('set_name', $setName);
		if(is_numeric($sharedFlag)){
			$translationSet->set('shared_flag', $sharedFlag);
		}
		if ($this->m_SetDao->insert($translationSet, true)) {
			return $translationSet;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}
}
?>
