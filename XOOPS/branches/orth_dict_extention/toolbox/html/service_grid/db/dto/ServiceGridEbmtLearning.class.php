<?php

/* $Id: ServiceGridEbmtLearning.class.php 4766 2010-11-17 09:05:35Z yoshimura $ */

class ServiceGridEbmtLearning {

	private $id;
	private $token;
	private $ebmtService;
	private $userDictionaryId;
	private $userDictionaryName;
	private $sourceLang;
	private $targetLang;
	private $status;
	private $createTime;

    function ServiceGridEbmtLearning() {
    }

    public function getId() {
    	return $this->id;
    }
    public function getToken() {
    	return $this->token;
    }
    public function getEbmtService() {
    	return $this->ebmtService;
    }
    public function getUserDictionaryId() {
    	return $this->userDictionaryId;
    }
    public function getUserDictionaryName() {
    	return $this->userDictionaryName;
    }
    public function getSourceLang() {
    	return $this->sourceLang;
    }
    public function getTargetLang() {
    	return $this->targetLang;
    }
    public function getStatus() {
    	return $this->status;
    }
    public function getCreateTime() {
    	return $this->createTime;
    }

    public function setId($id) {
    	$this->id = $id;
    }
    public function setToken($token) {
    	$this->token = $token;
    }
    public function setEbmtService($ebmtService) {
    	$this->ebmtService = $ebmtService;
    }
    public function setUserDictionaryId($userDictionaryId) {
    	$this->userDictionaryId = $userDictionaryId;
    }
    public function setUserDictionaryName($userDictionaryName) {
    	$this->userDictionaryName = $userDictionaryName;
    }
    public function setSourceLang($sourceLang) {
    	$this->sourceLang = $sourceLang;
    }
    public function setTargetLang($targetLang) {
    	$this->targetLang = $targetLang;
    }
    public function setStatus($status) {
    	$this->status = $status;
    }
    public function setCreateTime($createTime) {
    	$this->createTime = $createTime;
    }
}
?>