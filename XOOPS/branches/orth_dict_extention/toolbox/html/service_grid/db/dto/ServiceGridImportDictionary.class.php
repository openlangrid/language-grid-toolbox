<?php

class ServiceGridImportDictionary {
	private $id;
	private $userDictionaryId;
	private $bindType;
	private $bindValue;
	private $createDate;

	public function getId() {
		return $this->id;
	}
	public function getUserDictionaryId() {
		return $this->userDictionaryId;
	}
	public function getBindType() {
		return $this->bindType;
	}
	public function getBindValue() {
		return $this->bindValue;
	}
	public function getCreateDate() {
		return $this->createDate;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function setUserDictionaryId($userDictionaryId) {
		$this->userDictionaryId = $userDictionaryId;
	}
	public function setBindType($bindType) {
		$this->bindType = $bindType;
	}
	public function setBindValue($bindValue) {
		$this->bindValue = $bindValue;
	}
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}
}
?>