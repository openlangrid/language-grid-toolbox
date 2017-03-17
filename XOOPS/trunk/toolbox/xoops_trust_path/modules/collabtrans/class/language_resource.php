<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php';
require_once dirname(__FILE__).'/common_util.php';

class LanguageResource {

	private $record;

	public function __construct($record) {
		$this -> record = $record;
	}

	public function getName() {
		return $this -> record -> name;
	}

	public function hasLanguage($lang) {
		return in_array($lang, $this -> record -> languages);
	}

	public function getLanguages($expect = null) {
		$results = array();
		foreach($this -> record -> languages as $lang) {
			if(!$expect || $expect != $lang) $results[] = $lang;
		}
		return $results;
	}

	public function getLanguagesPair($expect = null) {
		$results = array();
		$map = CommonUtil::getLanguageNameMap();
		foreach($this -> getLanguages($expect) as $lang) {
			$results[] = self::getLanguagePair($lang);
		}
		return $results;
	}

	static public function getLanguagePair($lang) {
		$map = CommonUtil::getLanguageNameMap();
		return array($lang, $map[$lang]);
	}

	static public function findAll($options = array()) {
		$results = array();
		$client = new ResourceClient();
		$response = $client -> getAllLanguageResources($options['type']);
		foreach($response['contents'] as $resource) {
			if(self::isInclude($resource, $options)) {
				$results[] = new LanguageResource($resource);
			}
		}
		return $results;
	}

	static protected function isInclude($resourceRecord, $options) {
		$flag = true;
		$flag &= self::canRead($resourceRecord -> readPermission);
		if($options['language']) {
			$flag &= in_array($options['language'], $resourceRecord -> languages);
		}
		return $flag;
	}

	static protected function canRead($permission) {
		return $permission -> type == 'PUBLIC' ||
			   ($permission -> type == 'USER' && $permission -> userId == getLoginUserUID());
	}

	static public function findByName($dictionaryName, $sourceLang=null) {
		$client = new ResourceClient();
		$response = $client -> getLanguageResource($dictionaryName);
		return new LanguageResource($response['contents']);
	}
}

class Dictionary extends LanguageResource {
	const DICTIONARY_TYPE = 'DICTIONARY';

	static public function findAll($options = array()) {
		$options['type'] = self::DICTIONARY_TYPE;
		return parent::findAll($options);
	}
	
	static public function findAllByLang($sourceLang, $targetLang) {
		$results = array();
		foreach(self::findAll() as $resource) {
			if($resource->hasLanguage($sourceLang)
				&& $resource->hasLanguage($targetLang)) 
				$results[] = $resource;
		}
		return $results;
	}

	static public function getDictionaryNames($lang) {
		$results = array();
		foreach(self::findAll(array("language" => $lang)) as $resource) {
			$results[] = $resource -> getName();
		}
		return $results;
	}
}

class ParallelText extends LanguageResource {
	const DICTIONARY_TYPE = 'PARALLELTEXT';

	static public function findAll($options = array()) {
		$options['type'] = self::DICTIONARY_TYPE;
		return parent::findAll($options);
	}
	
	static public function findAllByLang($sourceLang, $targetLang) {
		$results = array();
		foreach(self::findAll() as $resource) {
			if($resource->hasLanguage($sourceLang)
				&& $resource->hasLanguage($targetLang)) 
				$results[] = $resource;
		}
		return $results;
	}

	static public function getDictionaryNames($lang) {
		$results = array();
		foreach(self::findAll(array("language" => $lang)) as $resource) {
			$results[] = $resource -> getName();
		}
		return $results;
	}
}

class Glossary extends LanguageResource {
	const DICTIONARY_TYPE = 'GLOSSARY';

	static public function findAll($options = array()) {
		$options['type'] = self::DICTIONARY_TYPE;
		return parent::findAll($options);
	}
	
	static public function findAllByLang($sourceLang, $targetLang) {
		$results = array();
		foreach(self::findAll() as $resource) {
			if($resource->hasLanguage($sourceLang)
				&& $resource->hasLanguage($targetLang)) 
				$results[] = $resource;
		}
		return $results;
	}

	static public function getDictionaryNames($lang) {
		$results = array();
		foreach(self::findAll(array("language" => $lang)) as $resource) {
			$results[] = $resource -> getName();
		}
		return $results;
	}
}
