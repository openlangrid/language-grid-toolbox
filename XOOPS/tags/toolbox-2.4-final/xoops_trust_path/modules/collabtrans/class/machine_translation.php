<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/CTTranslation_manager.php';

class MachineTranslation {
	
	protected $record;
	protected $translationPath;
	
	protected function __construct($record, $translationPath) {
		$this -> record = $record;
		$this -> translationPath = $translationPath;
	}
	
	public function getTargetText() {
		return $this -> record -> result;
	}
	
	public function getPathId() {
		return $this -> translationPath -> getPathId();
	}
	
	public function isDefault() {
		$defPath = DefaultTranslationPath::findUserOrAdmin(
						$this -> translationPath -> getSourceLang(), 
						$this -> translationPath -> getTargetLang());
		return $defPath -> getPathId() == $this -> getPathId();
	}
	
	static public function translateAll($sourceLang, $targetLang, $sourceText) {
		$results = array();
		
		$pathes = TranslationPath::findAll(getLoginUserUID(), $sourceLang, $targetLang);
		$uid = getLoginUserUID();
		if(count($pathes) == 0) {
			$pathes = TranslationPath::findAll(TranslationPath::ADMIN_UID, $sourceLang, $targetLang);
			$uid = TranslationPath::ADMIN_UID;
		}
		$manager = new TranslationManager();
		foreach($pathes as $path) {
			if($sourceText) {
				$response = $manager -> translateByPath($sourceLang, $targetLang,	$sourceText, $path,$uid);
				$mt = new MachineTranslation($response['contents'][0], $path);
				array_push($results, $mt);
			} else {
				$mt = new MachineTranslation('', $path);
				array_push($results, $mt);
			}
		}
		
		return $results;
	}
}
?>
