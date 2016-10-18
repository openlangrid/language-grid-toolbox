<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/api/class/client/DictionaryClient.class.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/ParallelTextClient.class.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/GlossaryClient.class.php';
require_once dirname(__FILE__).'/language_resource.php';

class SimpleToolItem {
	
	protected $record;
	protected $searchOptions;
	protected $name;
		
	public function __construct($record, $options, $name) {
		$this -> record = $record;
		$this -> searchOptions = $options;
		$this -> name = $name;
	}
	
	public function getId() {
		return $this -> record -> id;
	}
	
	public function getExpressions() {
		return $this -> record-> expressions; 
	}
	
	public function getExpressionForLang($lang) {
		foreach($this -> record-> expressions as $exp) {
			if($exp -> language == $lang) return $exp -> expression;
		}
		return "";
	}
	
	public function getExpressionForTargetLang() {
		return $this -> getExpressionForLang($this -> searchOptions['targetLang']);
	}
	
	public function getExpressionForSourceLang() {
		return $this -> getExpressionForLang($this -> searchOptions['sourceLang']);
	}
	
	public function getName() {
		return $this -> name;
	}
		
	static public function acualTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$tableName;
	}
}

class DictionaryItem extends SimpleToolItem {
	const DICTIONARY_TYPE = 0;
	
	static public function findAll($options = array()){
		$results = array();
		
		$client = new DictionaryClient();
		foreach(Dictionary::findAllByLang($options["sourceLang"], $options["targetLang"]) as $dictionary) {
			$records = $client -> searchRecord(
					$dictionary->getName(), $options["keyword"], 
					$options["sourceLang"], $options["method"]);
			
			foreach($records['contents'] as $r) {
				$results[] = new DictionaryItem($r, $options, $dictionary->getName());
			}
		}
		
		return $results;
	}
}

class ParallelTextItem extends SimpleToolItem {
	const DICTIONARY_TYPE = 1;
	
	static public function findAll($options = array()){
		$results = array();
		
		$client = new ParallelTextClient();
		foreach(ParallelText::findAllByLang($options["sourceLang"], $options["targetLang"]) as $dictionary) {
			$records = $client -> searchRecord(
					$dictionary->getName(), $options["keyword"], 
					$options["sourceLang"], $options["method"]);
			
			foreach($records['contents'] as $r) {
				$results[] = new ParallelTextItem($r, $options, $dictionary->getName());
			}
		}
		
		return $results;
	}
	
}


class GlossaryItem extends SimpleToolItem {
	const DICTIONARY_TYPE = 3;
	
	/*** record definition *****************
	 * term[]
	 * 		language
	 * 		expression
	 * definition[][]
	 * 		expression
	 * 			language
	 * 			expression
	 * categoryIds
	 * creationDate
	 * updateDate
	 */
	
	protected function getTermForLang($lang) {
		foreach($this -> record-> term as $exp) {
			if($exp -> language == $lang) return $exp -> expression;
		}
		return ""; 
	}
	
	public function getTermForSourceLang() {
		return $this -> getTermForLang($this -> searchOptions['sourceLang']);
	}
	
	public function getTermForTargetLang() {
		return $this -> getTermForLang($this -> searchOptions['targetLang']);
	}
	
	public function getDefinitionForLang($index, $lang) {
		foreach($this -> record-> definition[$index] -> expression as $exp) {
			if($exp -> language == $lang) return $exp -> expression;
		}
		return "";
	}
	
	// return array{source => StringValue, target => StringValue}
	public function getDifinitionPairs() {
		$results = array();
		foreach($this -> record-> definition as $def) {
			$row = array();
			foreach($def -> expression as $exp) {
				if($exp -> language == $this -> searchOptions['sourceLang'])
					$row['source'] = $exp -> expression;
				if($exp -> language == $this -> searchOptions['targetLang']) 
					$row['target'] = $exp -> expression;
			}
			$results[] = $row;
		}
		return $results;
	}
	
	public function addDefinition($term, $difinition) {
		$client = new GlossaryClient();
		$difs = $this -> buildDifinitionsForUpdate($difinition);
		return $client -> updateRecord(
			$this -> getName(),
			$this -> getId(),
			$term,
			$difs, 
			$categoryIds = null
		);
	}
	
	protected function buildDifinitionsForUpdate($difinition) {
		$results = array();
		foreach($this -> record-> definition as $def) {
			$olddif = new ToolboxVO_Glossary_Definition();
			$olddif->expression = $def->expression;
			$results[] = $olddif;
		}
		$results[] = $difinition;
		return $results;
	}
	
	static public function findAll($options = array()){
		$results = array();
		
		$client = new GlossaryClient();
		foreach(Glossary::findAllByLang($options["sourceLang"], $options["targetLang"]) as $dictionary) {
			$records = $client -> searchRecord(
					$dictionary->getName(), $options["keyword"], 
					$options["sourceLang"], $options["method"]);

			if($records['status'] == 'OK') {
				foreach($records['contents'] as $r) {
					$results[] = new GlossaryItem($r, $options, $dictionary->getName());
				}
			}
		}
		
		return $results;
	}
	
	static public function find($options = array()) {
		$results = self::findAll($options);
		if(@!$options['name']) {
			foreach($results as $r) {
				if($r->getName() == $options['name']) return $r; 
			}
		} else {
			return count($results) > 0 ? $results[0] : null;	
		}
		
	}
}

?>
