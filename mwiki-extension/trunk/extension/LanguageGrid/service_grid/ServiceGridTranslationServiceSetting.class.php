<?php
require_once(dirname(__FILE__).'/db/adapter/DaoAdapter.class.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridTranslationSetDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridTranslationPathDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridTranslationExecDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridTranslationBindDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridTranslationOptionDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridLangridServicesDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridDefaultDictionaryBindDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridDefaultDictionarySettingDAO.interface.php');
require_once(dirname(__FILE__).'/db/dao/ServiceGridUserDictionaryContentsDAO.interface.php');
require_once(dirname(__FILE__).'/db/validate/ServiceGridServiceSettingValidate.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridTranslationSet.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridTranslationPath.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridTranslationExec.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridTranslationBind.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridLangridService.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridDefaultDictionaryBind.class.php');
require_once(dirname(__FILE__).'/db/dto/ServiceGridDefaultDictionarySetting.class.php');

class ServiceGridTranslationServiceSetting {
	protected $translationSet = null;
	protected $translationPath = null;
	protected $translationExec = null;
	protected $translationBind = null;
	protected $langridService = null;
	protected $defaultDictionaryBind = null;
	protected $defaultDictionarySetting = null;
	protected $userDictionary = null;
	protected $userDictionaryContents = null;
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
		$this->userDictionary = $adapter->getUserDictionaryDao();
		$this->userDictionaryContents = $adapter->getUserDictionaryContentsDao();
	}
	// Getters for DAO start
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
	public function getUserDictionaryDao() {
		return $this->userDictionary;
	}
	public function getUserDictionaryContentsDao() {
		return $this->userDictionaryContents;
	}
	// Getters for DAO end
	
	public function getServiceSettings($translationSetObj, $sourceLang = null, $targetLang = null) {
		// get path objs
		$translationPathObjs = $this->getTranslationPathsBySet($translationSetObj, $sourceLang, $targetLang);
		foreach ($translationPathObjs as $translationPathObj) {
			// get exec objs
			$this->getTranslationExecsByPath($translationPathObj);
			$translationExecObjs = $translationPathObj->getTranslationExecs();
			// get Bind Objs
			foreach ($translationExecObjs as $translationExecObj) {
				$this->getTranslationBindsByExec($translationExecObj);
			}
		}
		return $translationSetObj;
	}
	public function getTranslationSetByUserId($userId) {
		return $this->translationSet->queryByUserId($userId);
	}
	public function getTranslationSet($setId, $setName, $sourceLang = null, $targetLang = null) {
		if ($setId) {
			return $this->getTranslationSetBySetId($setId, $sourceLang, $targetLang);
		} else {
			return $this->getTranslationSetBySetName($setName, $sourceLang, $targetLang);
		}
	}
	public function getTranslationSetBySetId($setId, $sourceLang = null, $targetLang = null) {
		$sets = $this->translationSet->queryBySetId($setId);
		return $this->getServiceSettings($sets[0], $sourceLang, $targetLang);
	}
	public function getTranslationSetBySetName($setName, $sourceLang = null, $targetLang = null) {
		$sets = $this->translationSet->queryBySetName($setName);
		return $this->getServiceSettings($sets[0], $sourceLang, $targetLang);
	}
	public function getTranslationPathsBySet($translationSetObj, $sourceLang = null, $targetLang = null) {
		$translationPathObjs = $this->translationPath->queryBySetId($translationSetObj->getUserId(), $translationSetObj->getSetId(), $sourceLang, $targetLang);
		$translationSetObj->setTranslationPaths($translationPathObjs);
		return $translationPathObjs;
	}
	public function getTranslationExecsByPath($translationPathObj) {
		$translationExecObjs = $this->translationExec->queryByPathId($translationPathObj->getPathId());
		$translationPathObj->setTranslationExecs($translationExecObjs);
		return $translationExecObjs;
	}
	public function getTranslationBindsByExec($translationExecObj) {
		$translationBindObjs = $this->translationBind->queryByExecObject($translationExecObj);
		$translationExecObj->setTranslationBinds($translationBindObjs);
		return $translationExecObj;
	}
	public function getTemporalDictionaryContents($userDictionaryIds, $sourceLang, $targetLang, $sourceText = "") {
		$result = array();
		foreach ($userDictionaryIds as $userDictionaryId) {
			$contents = $this->userDictionaryContents->getUserDictionaryContents($userDictionaryId, $sourceLang, $targetLang, $sourceText);
			foreach($contents as $content) {
				$result[] = $content;
			}
		}
		return $result;
	}
	public function getTemporalDictionaryIdByName($dictName) {
		$result = array();
		$id = $this->userDictionary->getUserDictionaryIdByName($dictName);
		return $id;
	}
	public function loadServiceSetting($pathId) {
		ServiceSettingValidate::validateRequireStringOrInteger($pathId);
		$translationPathObj = $this->translationPath->queryByPathId($pathId);
		$this->getTranslationExecsByPath($translationPathObj);
		$translationExecObjs = $translationPathObj->getTranslationExecs();
		// get bind objs
		foreach ($translationExecObjs as $translationExecObj) {
			$this->getTranslationBindsByExec($translationExecObj);
		}
		return $translationPathObj;
	}
	function addTranslationPath($userId, $setId, $sourceLang, $targetLang, $revsPathId = null, $pathName = null) {
		ServiceSettingValidate::validateRequireStringOrInteger($userId);
		ServiceSettingValidate::validateRequireStringOrInteger($setId);
		ServiceSettingValidate::validateRequireStringOrInteger($sourceLang);
		ServiceSettingValidate::validateRequireStringOrInteger($targetLang);
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
