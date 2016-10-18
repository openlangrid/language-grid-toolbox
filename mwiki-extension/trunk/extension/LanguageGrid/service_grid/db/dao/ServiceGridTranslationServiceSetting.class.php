<?php
require_once(dirname(__FILE__).'/../adapter/DaoAdapter.class.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationSetDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationPathDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationExecDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationBindDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridTranslationOptionDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridLangridServicesDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridDefaultDictionaryBindDAO.interface.php');
require_once(dirname(__FILE__).'/ServiceGridDefaultDictionarySettingDAO.interface.php');
require_once(dirname(__FILE__).'/../validate/ServiceGridServiceSettingValidate.class.php');

require_once(dirname(__FILE__).'/../dto/ServiceGridTranslationSet.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridTranslationPath.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridTranslationExec.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridTranslationBind.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridLangridService.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridDefaultDictionaryBind.class.php');
require_once(dirname(__FILE__).'/../dto/ServiceGridDefaultDictionarySetting.class.php');

class ServiceGridTranslationServiceSetting {

	protected $translationSet = null;
	protected $translationPath = null;
	protected $translationExec = null;
	protected $translationBind = null;
	protected $langridService = null;
	protected $defaultDictionaryBind = null;
	protected $defaultDictionarySetting = null;

	/**
	 * Constructor method.
	 */
	function __construct() {
		// get adapter
		$adapter = DaoAdapter::getAdapter();

		// create data access objects
		$this->translationSet = $adapter->getTranslationSetDao();
		$this->translationPath = $adapter->getTranslationPathDao();
		$this->translationExec = $adapter->getTranslationExecDao();
		$this->translationBind = $adapter->getTranslationBindDao();
		$this->langridService = $adapter->getLangridServicesDao();
		$this->defaultDictionaryBind = $adapter->getDefaultDictionaryBindDao();
		$this->defaultDictionarySetting = $adapter->getDefaultDictionarySettingDao();
	}

	// Getters for DAO
	public function getTranslationSetDao() {
		return $this->translationSet;
	}
	public function getTranslationPathDao() {
		return $this->translationPath;
	}
	public function getTranslationExecDao() {
		return $this->translationExec;
	}
	public function getTranslationBindDao() {
		return $this->translationBind;
	}
	public function getLangridServiceDao() {
		return $this->langridService;
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
	public function getServiceSettings($userId, $setId, $sourceLang = null, $targetLang = null) {
		// Validate
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($setId);
		// Setオブジェクト取得
		$translationSetObj = $this->getTranslationSetBySetId($setId);
		// Pathオブジェクト取得
		$translationPathObjs = $this->getTranslationPathsBySet($translationSetObj, $sourceLang, $targetLang);
		// Execオブジェクト取得
		foreach ($translationPathObjs as $translationPathObj) {
			$this->getTranslationExecsByPath($translationPathObj);
			$translationExecObjs = $translationPathObj->getTranslationExecs();
			// Bindオブジェクト取得
			foreach ($translationExecObjs as $translationExecObj) {
				$this->getTranslationBindsByExec($translationExecObj);
			}
		}
		return $translationSetObj;
	}

	/**
	 *
	 * @param $userId
	 */
	public function getTranslationSetByUserId($userId) {
		return $this->translationSet->queryByUserId($userId);
	}
	/**
	 * Get Translarion Set By Set ID
	 * @param unknown_type $userId
	 * @param unknown_type $setId
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 */
	public function getTranslationSetBySetId($setId) {
		$set = $this->translationSet->queryBySetId($setId);
		return $this->getServiceSettings($set->getUserId(), $set->getSetId(), null, null);
	}

	/**
	 * Get Translation Set By Set Name
	 * @param String $setName
	 * @param String $sourceLang
	 * @param String $targetLang
	 * @param String $userId
	 */
	public function getTranslationSetBySetName($setName, $sourceLang = null, $targetLang = null) {
		$set = $this->translationSet->queryBySetName($setName);
		return $this->getServiceSettings($set->getUserId(), $set->getSetId(), $sourceLang, $targetLang);
	}

	/**
	 * Get Translation Path By Set Object
	 * @param unknown_type $translationSetObj
	 */
	public function getTranslationPathsBySet($translationSetObj, $sourceLang = null, $targetLang = null) {
		$translationPathObjs = $this->translationPath->queryBySetId($translationSetObj->getUserId(), $translationSetObj->getSetId(), $sourceLang, $targetLang);
		$translationSetObj->setTranslationPaths($translationPathObjs);
		return $translationPathObjs;
	}

	/**
	 * Get Translation Exec By Path Object
	 * @param unknown_type $translationPathObj
	 */
	public function getTranslationExecsByPath($translationPathObj) {
		$translationExecObjs = $this->translationExec->queryByPathId($translationPathObj->getPathId());
		$translationPathObj->setTranslationExecs($translationExecObjs);
		return $translationExecObjs;
	}

	/**
	 * Get Translation Bind By Exec Object
	 * @param unknown_type $translationExecObj
	 */
	public function getTranslationBindsByExec($translationExecObj) {
		$translationBindObjs = $this->translationBind->queryByExecObject($translationExecObj);
		$translationExecObj->setTranslationBinds($translationBindObjs);
		return $translationExecObj;
	}

	/**
	 * loadServiceSetting
	 *
	 * @param $pathId
	 * @return pathexecbind
	 */
	function loadServiceSetting($pathId) {
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($pathId);
		$translationPathObj = $this->translationPath->queryByPathId($pathId);
		$this->getTranslationExecsByPath($translationPathObj);
		$translationExecObjs = $translationPathObj->getTranslationExecs();
		// Bindオブジェクト取得
		foreach ($translationExecObjs as $translationExecObj) {
			$this->getTranslationBindsByExec($translationExecObj);
		}
		return $translationPathObj;
	}

	/**
	 * addTranslationPath
	 *
	 * @return TranslationPathObject
	 */
	function addTranslationPath($userId, $setId, $sourceLang, $targetLang, $revsPathId = null, $pathName = null) {
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($setId);
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($sourceLang);
		ServiceGridServiceSettingValidate::validateRequireStringOrInteger($targetLang);
		$translationPathObj = new ServiceGridTranslationPath();
		$translationPathObj->setUserId($userId);
		$translationPathObj->setSetId($setId);
		$translationPathObj->setSourceLang($sourceLang);
		$translationPathObj->setTargetLang($targetLang);
		$translationPathObj->setPathName($pathName);
		$translationPathObj->setRevsPathId($revsPathId);
		return $this->translationPath->insert($translationPathObj);
	}
}
?>