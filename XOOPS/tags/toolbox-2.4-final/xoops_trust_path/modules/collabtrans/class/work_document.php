<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/api/IFileSharingClient.interface.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';
require_once dirname(__FILE__).'/sentence.php';
require_once dirname(__FILE__).'/work_history.php';
require_once dirname(__FILE__).'/common_util.php';


class WorkDocument {

	const PERM_ALL_WRITEABLE = 0;
	const PERM_ALL_READABLE = 1;
	const PERM_PRIVATE = 2;

	private $histories = array();

	private $sentences = array();

	private $params;

	private $file = null;

	public function save() {
		return $this -> insert();
	}

	protected function delete() {
		$client = new FileSharingClient();
		return $client -> deleteFile($this -> file -> getId());
	}

	protected function insert() {
		if($this -> validateOnInsert()) {
			die();
		}

		$path = $this -> outPutTmpFile();

		$filename = $this -> params["fileName"];
		if(!ereg("\.xml$", $filename)) {
			$filename .= '.xml';
		}
		
		// store temporary file to DB
		$client = new FileSharingClient();
		$response = $client -> addFile(
			$path,
			$filename,
			$this -> params["description"],
			$this -> params["parentId"],
			$this -> createReadPermission(),
			$this -> createWritePermission(),
			true
		);

		if($response['status'] == 'OK') {
			$this -> file = File::findById($response['contents'] -> id);
		}
		
		unlink($path);

		return $response['status'] == 'OK';
	}

	protected function outPutTmpFile() {
		// out put temporary file
		$dir = XOOPS_TRUST_PATH.'/tmp';
		$path = $dir.'/'.mt_rand()."_".time().'_collabtrans.xml';
		$fp = fopen($path, "w");
		fwrite($fp, $this -> toXML());
		fclose($fp);

		return $path;
	}

	protected function createReadPermission() {
		$perm = new ToolboxVO_FileSharing_Permission();	
		$perm -> userId = getCurrentUserLoginId();
		$perm -> type = $this -> params["readPermission"];
		return $perm;
	}

	protected function createWritePermission() {
		$perm = new ToolboxVO_FileSharing_Permission();
		$perm -> userId = getCurrentUserLoginId();
		$perm -> type = $this -> params["writePermission"];
		return $perm;
	}

	public function validateOnInsert() {
		if(!@$this -> params["parentId"]) {
			$this -> params["parentId"] = Folder::ROOT_ID;
		}

		if(!@$this -> params["sourceLang"] || !@$this -> params["targetLang"]) {
			return true;
		}

		if(!@$this -> params["fileName"]) {
			return true;
		}
		
		$files = File::findAll(array("cid" => $this -> params["parentId"]));
		foreach($files as $f) {
			if($f->getName() == $this -> params["fileName"] && !$f->canWrite()) {
				return true;
			}
		}

		return false;
	}

	public function getSourceLanguage() {
		return $this -> params['sourceLang'];
	}

	public function getSourceLanguageAsName() {
		return CommonUtil::toLanguageAsName($this->getSourceLanguage());
	}

	public function getTargetLanguage() {
		return $this -> params['targetLang'];
	}

	public function getTargetLanguageAsName() {
		return CommonUtil::toLanguageAsName($this->getTargetLanguage());
	}

	public function getHistories() {
		if(!$this -> histories && $this -> id) {
			$this -> histories = WorkHistory::findAllByDocumentId($this -> id);
		}
		return $this -> histories;
	}
	
	public function getHistoriesReverse() {
		if(!$this -> histories && $this -> id) {
			$this -> histories = WorkHistory::findAllByDocumentId($this -> id);
		}
		
		return $this -> histories;
	}

	public function getHistoriesCount() {
		return count($this -> getHistories());
	}

	public function getSentences() {
		return $this -> sentences;
	}

	public function getFileId() {
		return $this -> file -> getId();
	}

	public function getSourceAsText($escape = false) {
		$text = "";
		foreach($this -> sentences as $sentence)
			$text .= $sentence -> getSource()."\n";
		return $text;
	}

	public function getTargetAsText($escape = false) {
		$text = "";
		foreach($this -> sentences as $sentence)
			$text .= $sentence -> getTarget()."\n";
		return $text;
	}

	public function toXML() {
		$tmp = '<?xml version="1.0 encoding="UTF-8" standalone="yes"?>';

		$xml = <<<XML
<root>
	<sourceLang>{$this->getSourceLanguage()}</sourceLang>
	<targetLang>{$this->getTargetLanguage()}</targetLang>
	<sentences>

XML;
		foreach($this -> sentences as $sentence) {
			$xml .= $sentence -> toXML();
		}

		$xml .= <<<XML
	</sentences>
	<histories>

XML;

		foreach($this -> histories as $history) {
			$xml .= $history -> toXML();
		}

		$xml .= <<<XML
	</histories>
</root>

XML;
		return $xml;
	}

	protected function parseXML($xml) {
		$root = new SimpleXMLElement($xml);
		$this -> params['sourceLang'] = $root -> sourceLang;
		$this -> params['targetLang'] = $root -> targetLang;
	}

	static public function createFromParams(array $params) {
		$document = new WorkDocument();
		$document -> params = $params;
		
		if(@$params['sentence']) {
			$document -> sentences = Sentence::createFromParams($params['sentence']);
		}
		if(@$params['history']) {
			$document -> histories = WorkHistory::createFromParams($params['history']);
		}

		return $document;
	}

	static public function createFromXML($xml) {
		$document = new WorkDocument();
		$document -> parseXML($xml);
		$document -> sentences = Sentence::createFromXML($xml);
		$document -> histories = WorkHistory::createFromXML($xml);

		return $document;
	}

	static public function createFromFile($file) {
		if (!file_exists($file->getAbsolutePath())) {
			throw new Exception("{$file->getAbsolutePath()} does not exist.");
		}

		$xml = file_get_contents($file -> getAbsolutePath());
		$document = self::createFromXML($xml);
		$document -> file = $file;
		return $document;
	}
}
?>
