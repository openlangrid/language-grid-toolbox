<?php
class ServiceGridLog {	
	private $logId;
	private $sourceLang;
	private $targetLang;
	private $source;
	private $result;
	private $executedTime;
	private $serviceName;
	private $url;
	private $executedUser;
	
	public function getLogId() {
		return $this->logId;
	}
	public function getSourceLang() {
		return $this->sourceLang;
	}
	public function getTargetLang() {
		return $this->targetLang;	
	}
	public function getSource() {
		return $this->source;	
	}
	public function getResult() {
		return $this->result;
	}
	public function getExecutedTime() {
		return $this->executedTime;
	}
	public function getServiceName() {
		return $this->serviceTime;
	}
	public function getUrl() {
		return $this->url;
	}
	public function getExecutedUser() {
		return $this->executedUser;
	}
	public function setLogId($logId) {
		$this->logId = $logId;
	}
	public function setSourceLang($sourceLang) {
		$this->sourceLang = $sourceLang;
	}
	public function setTargetLang($targetLang) {
		$this->targetLang = $targetLang;
	}
	public function setSource($source) {
		$this->source = $source;
	}
	public function setResult($result) {
		$this->result = $result;
	}
	public function setExecutedTime($executedTime) {
		$this->executedTime = $executedTime;
	}
	public function setServiceName($serviceName) {
		$this->serviceName = $serviceName;
	}
	public function setUrl($url) {
		$this->url = $url;
	}
	public function setExecutedUser($executedUser) {
		$this->executedUser = $executedUser;
	}
}
?>