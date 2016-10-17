<?php
class ServiceGridTranslationBind {
	private $pathId;
	private $execId;
	private $bindId;
	private $bindType;
	private $bindValue;
	private $createUserId;
	private $updateUserId;
	private $createTime;
	private $updateTime;
	public function setPathId($pathId) {
		$this->pathId = $pathId;
	}
	public function getPathId() {
		return $this->pathId;
	}
	public function setExecId($execId) {
		$this->execId = $execId;
	}
	public function getExecId() {
		return $this->execId;
	}
	public function setBindId($bindId) {
		$this->bindId = $bindId;
	}
	public function getBindId() {
		return $this->bindId;
	}
	public function setBindType($bindType) {
		$this->bindType = $bindType;
	}
	public function getBindType() {
		return $this->bindType;
	}
	public function setBindValue($bindValue) {
		$this->bindValue = $bindValue;
	}
	public function getBindValue() {
		return $this->bindValue;
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
}
?>