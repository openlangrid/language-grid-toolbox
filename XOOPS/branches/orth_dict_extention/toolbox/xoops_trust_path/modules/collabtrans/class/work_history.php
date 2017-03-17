<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/abstract_model.php';
require_once dirname(__FILE__).'/work_document.php';
require_once dirname(__FILE__).'/common_util.php';
require_once dirname(__FILE__).'/user.php';

class WorkHistory {
	
	protected $searchOption;
	
	private $source;
	private $target;
	private $status;
	private $status_before;
	private $loginId;
	private $create_date;
	
	public function __construct($source, $target, $status, $loginId, $create_date, $status_before) {
		$this -> source = $source;
		$this -> target = $target;
		$this -> status = $status;
		$this -> loginId = $loginId;
		$this -> create_date = $create_date;
		$this -> status_before = $status_before;
	}
	
	public function getSource() {
		return $this -> source;
	}
	
	public function getTarget() {
		return $this -> target;
	}
	
	public function getStatus() {
		return $this -> status;
	}
	
	public function getStatusAsLabel() {
		return Sentence::getStatusLabel($this -> getStatus());
	}
	
	public function getStatusBefore() {
		return $this -> status_before;
	}
	
	public function getStatusBeforeAsLabel() {
		return Sentence::getStatusLabel($this -> getStatusBefore());
	}
	
	public function getLoginId() {
		return $this -> loginId;
	}
	
	public function getCreateDate() {
		return $this -> create_date;
	}
	
	public function isInTerm($date) {
		return $this -> getCreateDate() > $date;
	}
	
	public function isOwner($loginId) {
		return $this -> loginId == $loginId;
	}
	
	public function getCreateDateAsFormatString() {
		return CommonUtil::formatTimestamp($this -> getCreateDate(), _MD_TR_DTFMT_YMDHI);
	}
	
	public function toXML() {
		$source = htmlspecialchars($this -> source);
		$target = htmlspecialchars($this -> target);
		return <<<XML
		<history>
			<source>{$source}</source>
			<target>{$target}</target>
			<status>{$this -> status}</status>
			<status_before>{$this -> status_before}</status_before>
			<loginId>{$this -> loginId}</loginId>
			<create_date>{$this -> create_date}</create_date>
		</history>

XML;
	}
	
	static public function createFromParams($historyParams = array(), $options = array()) {
		$results =array();
		foreach($historyParams as $id => $history) {
			
			$new = new WorkHistory(
				unescape_magic_quote($history['source']), 
				unescape_magic_quote($history['target']), 
				unescape_magic_quote($history['status']), 
				$history['loginId'], 
				strtotime($history['create_date']),
				unescape_magic_quote($history['status_before'])
			);
			
			if(self::isTargetHistory($new, $options)) {
				array_push($results, $new);
			}
		}
		return $results;
	}
	
	static public function isTargetHistory($history, $conditions) {
		$target = false;
		if(!@$conditions['create_date'] || 
			$history -> isInTerm(strtotime($conditions['create_date']))) {
			$target = true;
		}
		
		if(@$conditions['creator'] == 'user') {
			$target &= $history -> isOwner(getCurrentUserLoginId());
		}
		return $target;
	}
	
	static public function createFromXML($xml) {
		$result = array();
		$root = new SimpleXMLElement($xml);
		if($root -> histories && count($root -> histories) == 1) {
			foreach($root -> histories -> history as $history) {
				$new = new WorkHistory(
					$history -> source,
					$history -> target,
					$history -> status,
					$history -> loginId,
					intval($history -> create_date),
					$history -> status_before
				);
				array_push($result , $new);
			}
		}
		return $result;
	}
}
?>
