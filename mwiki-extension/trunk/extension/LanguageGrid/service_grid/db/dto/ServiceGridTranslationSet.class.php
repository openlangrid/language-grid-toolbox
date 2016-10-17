<?php
class ServiceGridTranslationSet{
	private $setId;	private $setName;	private $userId;	private $sharedFlag;	private $createUserId;	private $updateUserId;	private $createTime;	private $updateTime;	private $translationPaths;
	public function getSetId() {		return $this->setId;	}	public function getSetName() {		return $this->setName;	}	public function getUserId() {		return $this->userId;	}	public function getSharedFlag() {		return $this->sharedFlag;	}	public function getCreateUserId() {		return $this->createUserId;	}	public function getUpdateUserId() {		return $this->updateUserId;	}	public function getCreateTime() {		return $this->createTime;	}	public function getUpdateTime() {		return $this->updateTime;	}	public function setSetId($setId) {		$this->setId = $setId;	}	public function setSetName($setName) {		$this->setName = $setName;	}	public function setUserId($userId) {		$this->userId = $userId;	}	public function setSharedFlag($sharedFlag) {		$this->sharedFlag = $sharedFlag;	}	public function setCreateUserId($createUserId) {		$this->createUserId = $createUserId;	}	public function setUpdateUserId($updateUserId) {		$this->updateUserId = $updateUserId;	}	public function setCreateTime($createTime) {		$this->createTime = $createTime;	}	public function setUpdateTime($updateTime) {		$this->updateTime = $updateTime;	}
	public function getTranslationPaths() {
		return $this->translationPaths;
	}
	public function setTranslationPaths($translationPaths) {
		$this->translationPaths = $translationPaths;
	}
}
?>