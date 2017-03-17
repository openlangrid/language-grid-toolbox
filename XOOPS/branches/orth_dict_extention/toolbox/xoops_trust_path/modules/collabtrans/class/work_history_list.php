<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/pager.php';
require_once dirname(__FILE__).'/work_history.php';

class WorkHistoryList extends Pager {
	const DEFALT_LIMIT = 5;
	
	private $sum;
	
	public $workDocumentId;
	
	private $searchOptions = array();
	
	public function __construct($entities, $offset, $limit, $searchOptions) {
		parent::__construct($entities, $offset, $limit);
		
		if(!is_null($searchOptions)) {
			$this -> searchOptions = $searchOptions;
			$this -> workDocumentId = $searchOptions['work_document_id'];
		}
	}
	
	public function getSum() {
		if(!$sum) {
			if(count($this -> searchOptions) > 0) {
				$sum = count(self::findAll(array('where' => $this -> searchOptions), 0, -1) -> getEntities());				
			} else {
				$sum = count(self::findAll(array(), 0, -1) -> getEntities());	
			}
		}
		return $sum;
	}
	
	protected function addParamater($param = array()) {
		if(count($this -> searchOptions) > 0) {
			return parent::addParamater($this -> searchOptions);
		} else {
			return parent::addParamater();			
		}		
	}
	
	public function getWorkHistories() {
		return $this -> getEntities();
	}
	
	public function hasSearchKeys() {
		foreach(array("creator", "create_date") as $key) {
			if(array_key_exists($key, $this -> searchOptions)) return true;
		}
		return false;
	}
	
	static public function getWorkHistoriesForPage($options = array()) {
		$limit = (@$options['limit']) ? $options['limit'] : self::DEFALT_LIMIT;
		$offset = (@$options['page']) ? ($options['page'] - 1) * $limit : 0;
		
		$options['where'] = self::optionsToConditions($options);
		return self::findAll($options, $offset, $limit);
	}
	
	static public function findAll($options = array(), $offset = 0, $limit = self::DEFALT_LIMIT) {
		$offset = $offset ? $offset : 0;
		$limit = $limit ? $limit : self::DEFALT_LIMIT;
		
		$result = new WorkHistoryList(WorkHistory::findAll($options), $offset,  $limit, @$options['where']);

		return $result;
	}
	
	static public function optionsToConditions($options) {
		
		$wheres = array();
		if(@$options['work_document_id']) $wheres['work_document_id'] = $options['work_document_id'];
		
		if(@$options['creator']) $wheres['creator'] = getLoginUserUID();
		
		if(@$options['create_date']) {
			$wheres['create_date'] = array('>', $options['create_date']);
		}
		return $wheres;
	}
}
?>
