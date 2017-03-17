<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/abstract_model.php';
require_once dirname(__FILE__).'/CTTranslation_manager.php';
require_once XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/handler/TranslationPathHandler.class.php';
require_once XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
require_once dirname(__FILE__).'/common_util.php';

class TranslationPath {
	
	const TRANSLATION_SET_NAME = 'COLLABTRANS';
	
	const ADMIN_UID = 1;
	
	private $pathId;
	private $soruceLang;
	private $targetLang;
	
	private $pathRecord;
	
	function __construct($record = null) {
		$this -> pathRecord = $record;
		$this -> pathId = $this -> getPathId();
	}
	
	public function getPathId() {
		return $this -> pathRecord -> mVars['path_id']['value'];
	}
	
	public function getSetId() {
		return $this -> pathRecord -> mVars['set_id']['value'];
	}
	
	public function getSourceLang() {
		return $this -> pathRecord -> mVars['source_lang']['value'];
	}
	
	public function getSourceLanguageAsName() {
		return CommonUtil::toLanguageAsName($this->getSourceLang());
	}
	
	public function getTargetLang() {
		return $this -> pathRecord -> mVars['target_lang']['value'];
	}
	
	public function getTargetLanguageAsName() {
		return CommonUtil::toLanguageAsName($this->getTargetLang());
	}
	
	public function getExecs() {
		$results = array();
		foreach($this -> pathRecord->getExecs() as $exe) {
			array_push($results, array(
				CommonUtil::toLanguageAsName($exe->mVars['target_lang']['value']),
				$exe->mVars['service_id']['value']
			));
		}
		return $results;
	}
	
	static public function findById($pathId) {
		$pathHandler = new TranslationPathHandler(Database::getInstance());
		$pathRecord = $pathHandler -> get($pathId);
		return $pathRecord ? new TranslationPath($pathRecord) : null;
	}
	
	static public function findAll($uid, $source_lang = null, $target_lang = null) {
		$results = array();
		$manager = new TranslationManager();
		$setId = $manager -> getDefaultSetIdByName(self::TRANSLATION_SET_NAME);
		if(is_null($setId)) return $results;
		
		$setting = new TranslationServiceSetting();		
		$pathRecords = $setting -> getServiceSettings($uid, $setId, $source_lang, $target_lang);
		if(!is_null($pathRecords)) {
			foreach($pathRecords as $pathRecord) {
				array_push($results, new TranslationPath($pathRecord));
			}
		}
		
		return $results;
	}
	
	static public function findAllForUidOrDefaultPathes($uid, $source_lang=null, $target_lang=null) {
		$results = self::findAll($uid, $source_lang, $target_lang);
		return count($results) > 0 ? $results : self::findAll(self::ADMIN_UID, $source_lang, $target_lang);
	}
	
	static public function findDefault($uid, $source_lang, $target_lang) {
		$default = DefaultTranslationPath::find($uid, $source_lang, $target_lang);
		if($default) {
			return $default;
		} else {
			$pathes = self::findAllForUidOrDefaultPathes($uid, $source_lang, $target_lang);
			return count($pathes) > 0 ? $pathes[0] : null;
		}
	}
	
	static public function getSourceLangs($uid) {
		$results = array();
		$pathes = self::findAllForUidOrDefaultPathes($uid);
		if(!is_null($pathes)) {
			foreach($pathes as $path) {
				$lang = $path -> getSourceLang();
				if(!@in_array($lang, $results)) array_push($results, $lang);
			}
		}
		return $results;
	}
	
	static public function getTargetLangs($uid, $source_lang) {
		$results = array();
		$pathes = self::findAllForUidOrDefaultPathes($uid, $source_lang);
		if(!is_null($pathes)) {
			foreach($pathes as $path) {
				$lang = $path -> getTargetLang();
				if(!@in_array($lang, $results)) array_push($results, $lang);
			}
		}
		return $results;
	}
}

class DefaultTranslationPath extends AbstractModel{
	
	const TABLE_NAME = 'default_translation_path';
	
	function __construct($record = null) {
		parent::__construct($record);
	}
	
	// override
	public function getTableName() {
		return self::TABLE_NAME;
	}
	
	// override
	protected function getColumnsOnInsert() {
		return array('path_id', 'source_lang', 'target_lang', 'creator', 'create_date');
	}
	
	// override
	public function insert() {
		$xoopsDB =& Database::getInstance();
		
		$this -> deleteDefaultPath($xoopsDB,
			getLoginUserUid(),
			$this -> params['source_lang'],
			$this -> params['target_lang'] 
		);
		
		$result = parent::insert($xoopsDB);
		
		return $result;
	}
	
	protected function deleteDefaultPath($xoopsDB, $uid, $sourceLang, $targetLang) {
		foreach(self::findAll($uid, $sourceLang, $targetLang) as $path) {
			$path -> delete($xoopsDB, false);
		}
	}
	
	public function getPathId() {
		return $this -> _get('path_id'); 		
	}
	
	private $translationPath = null;
	public function getTranslationPath() {
		if(!$translationPath) $translationPath = TranslationPath::findById($this -> record['path_id']);
		return $translationPath;
	}
	
	public function getSourceLang() {
		return $this -> _get('source_lang');
	}
	
	public function getTargetLang() {
		return $this -> _get('target_lang');
	}
	
	// override
	static public function createFromParams(array $params) {
		return parent::createFromParams(get_class(), $params);
	}
	
	// override
	static public function findById($id) {
		return parent::findById(get_class(), $id);
	}
	
	static public function findAll($uid, $sourceLang, $targetLang) {
		return parent::findAll(get_class(), array(
			"where" => array(
				"source_lang" => $sourceLang,
				"target_lang" => $targetLang,
				"creator"	  => $uid
			)
		));
	}
	
	static public function findAllExistPath($uid, $sourceLang, $targetLang) {
		$results = array();
		foreach(self::findAll($uid, $sourceLang, $targetLang) as $r) {
			if($r -> getTranslationPath()) $resutls[] = $r;
		}
		return $results;
	}
	
	static public function find($uid, $sourceLang, $targetLang) {
		$results = self::findAllExistPath($uid, $sourceLang, $targetLang);
		if(count($results) > 0) {
			return $results[0]->getTranslationPath();
		} else {
			$results = TranslationPath::findAll($uid, $sourceLang, $targetLang);
			return count($results) > 0 ? $results[0] : null;
		}
	}
	
	static public function findUserOrAdmin($sourceLang, $targetLang) {
		$result = self::find(getLoginUserUid(), $sourceLang, $targetLang);
		if(!$result) $result = self::find(TranslationPath::ADMIN_UID, $sourceLang, $targetLang);
		return $result;
	}
	
	static public function craeteFromTranslationPath($translationPath) {
		
		return self::createFromParams(array(
			'path_id'     => $translationPath -> getPathId(),
			'source_lang' => $translationPath -> getSourceLang(),
			'target_lang' => $translationPath -> getTargetLang(),
			'creator'     => getLoginUserUid()  
		));
		
	}
}
?>
