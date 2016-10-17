<?php

class ServiceGridTranslationOption {
	private $optionId;
	private $setId;
	private $userId;
	private $liteFlag;
	private $richFlag;
	private $createUserId;
	private $updateUserId;
	private $createTime;
	private $updateTime;

	public function getOptionId() {
		return $this->optionId;
	}
	public function getSetId() {
		return $this->setId;
	}
	public function getUserid() {
		return $this->userId;
	}
	public function getLiteFlag() {
		return $this->liteFlag;
	}
	public function getRichFlag() {
		return $this->richFlag;
	}
	public function getCreateUserId() {
		return $this->createUserId;
	}
	public function getUpdateUserId() {
		return $this->updateUserId;
	}
	public function getCreateTime() {
		return $this->createTime;
	}
	public function getUpdateTime() {
		return $this->updateTime;
	}
	public function setOptionId($optionId) {
		$this->optionId = $optionId;
	}
	public function setSetId($setId) {
		$this->setId = $setId;
	}
	public function setUserId($userId) {
		$this->userId = $userId;
	}
	public function setLiteFlag($liteFlag) {
		$this->liteFlag = $liteFlag;
	}
	public function setRichFlag($richFlag) {
		$this->richFlag = $richFlag;
	}
	public function setCreateUserId($createUserId) {
		$this->createUserId = $createUserId;
	}
	public function setUpdateUserId($updateUserId) {
		$this->updateUserId = $updateUserId;
	}
	public function setCreateTime($createTime) {
		$this->createTime = $createTime;
	}
	public function setUpdateTime($updateTime) {
		$this->updateTime = $updateTime;
	}
}
?>