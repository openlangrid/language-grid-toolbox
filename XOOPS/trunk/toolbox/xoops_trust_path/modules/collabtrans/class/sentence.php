<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

class Sentence {
	const STATUS_NOT_WORKING = "not_working";
	const STATUS_WORKING = "working";
	const STATUS_WORK_OUT = "work_out";
	
	private $statuses = array(
		Sentence::STATUS_NOT_WORKING, 
		Sentence::STATUS_WORKING, 
		Sentence::STATUS_WORK_OUT
	);
	
	private $source;
	private $target;
	private $status;
	
	public function __construct($source, $target, $status = Sentence::STATUS_NOT_WORKING) {
		$this -> source = $source.'';
		$this -> target = $target.'';
		$this -> setStatus($status);
	}
	
	public function setStatus($status) {
		if(@in_array($status, $this -> statuses)) {
			$this -> status = $status.'';			
		} else {
			$this -> status = Sentence::STATUS_NOT_WORKING;			
		}
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
	
	public function toXML() {
		$source = htmlspecialchars($this -> source);
		$target = htmlspecialchars($this -> target);
		return <<<XML
		<sentence work_status="{$this -> status}">
			<source>{$source}</source>
			<target>{$target}</target>
		</sentence>

XML;
	}
	
	public function toJson() {
		return json_encode(array(
			"source" => $this -> getSource(),
			"target" => $this -> getTarget(),
			"status" => $this -> getStatus()
		));
	}
	
	public function createFromXML($xml) {
		$result = array();
		$root = new SimpleXMLElement($xml);
		if($root -> sentences && count($root -> sentences) == 1) {
			foreach($root -> sentences -> sentence as $sentence) {
				$new = new Sentence(
					$sentence -> source,
					$sentence -> target,
					$sentence['work_status']
				);
				array_push($result , $new);
			}
		}
		return $result;
	}
	
	public function createFromParams($sentenceParams = array()) {
		$results =array();
		foreach($sentenceParams as $id => $sentence) {
			$new = new Sentence(
				unescape_magic_quote($sentence['source']), 
				unescape_magic_quote($sentence['target']), 
				unescape_magic_quote($sentence['work_status'])
			);
			array_push($results, $new);
		}
		return $results;
	}
	
	static $statusLabels;
	static public function getStatusLabel($status) {
		
		if(!self::$statusLabels) {
			self::$statusLabels = array(
				self::STATUS_NOT_WORKING => CT_LABEL_STATUS_NOT_WORKING,
				self::STATUS_WORKING => CT_LABEL_STATUS_WORKING,
				self::STATUS_WORK_OUT => CT_LABEL_STATUS_WORKED_OUT
			);
		}
		$labels = self::$statusLabels;
		if(@array_key_exists($status, self::$statusLabels)) {
			return $labels[$status];
		} else {
			return $labels[self::STATUS_NOT_WORKING];	
		}
	}
}
?>
