<?php

class ServiceGridTranslationPath{
	private $pathId;	private $pathName;	private $userId;	private $setId;	private $sourceLang;	private $targetLang;	private $revsPathId;	private $createUserId;	private $updateUserId;	private $createTime;	private $updateTime;	private $translationExecs;
	public function getPathId() {		return $this->pathId;	}	public function getPathName() {		return $this->pathName;	}	public function getUserId() {		return $this->userId;	}	public function getSetId() {		return $this->setId;	}	public function getSourceLang() {		return $this->sourceLang;	}	public function getTargetLang() {		return $this->targetLang;	}	public function getRevsPathId() {		return $this->revsPathId;	}	public function getCreateUserId() {		return $this->createUserId;	}	public function getUpdateUserId() {		return $this->updateUserId;	}	public function getCreateTime() {		return $this->createTime;	}	public function getUpdateTime() {		return $this->updateTime;	}	public function setPathId($pathId) {		$this->pathId = $pathId;	}	public function setPathName($pathName) {		$this->pathName = $pathName;	}	public function setUserId($userId) {		$this->userId = $userId;	}	public function setSetId($setId) {		$this->setId = $setId;	}	public function setSourceLang($sourceLang) {		$this->sourceLang = $sourceLang;	}	public function setTargetLang($targetLang) {		$this->targetLang = $targetLang;	}	public function setRevsPathId($revsPathId) {		$this->revsPathId = $revsPathId;	}	public function setCreateUserId($createUserId) {		$this->createUserId = $createUserId;	}	public function setUpdateUserId($updateUserId) {		$this->updateUserId = $updateUserId;	}	public function setCreateTime($createTime) {		$this->createTime = $createTime;	}	public function setUpdateTime($updateTime) {		$this->updateTime = $updateTime;	}
	public function getTranslationExecs() {
		return $this->translationExecs;
	}
	public function setTranslationExecs($translationExecs) {
		$this->translationExecs = $translationExecs;
	}
}
?>