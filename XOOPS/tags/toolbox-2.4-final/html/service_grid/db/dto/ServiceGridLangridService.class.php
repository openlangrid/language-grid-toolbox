<?php
/* $Id: $ */

class ServiceGridLangridService{
	private $id;
	private $serviceId;
	private $allowedAppProvision;
	private $miscBasicPasswd;

	public function getId() {
		return $this->id;
	}
	public function getServiceId() {
	public function getAllowedAppProvision() {
		return $this->allowedAppProvision;
	}
	public function getMiscBasicUserid() {
		return $this->miscBasicUserid;
	}
		return $this->miscBasicPasswd;
	}

	public function setId($id) {
		$this->id = $id;
	}
	public function setAllowedAppProvision($allowedAppProvision) {
		$this->allowedAppProvision = $allowedAppProvision;
	}
	public function setMiscBasicUserid($basicUserId) {
		$this->miscBasicUserid = $basicUserId;
	}
	public function setMiscBasicPasswd($basicPasswd) {
		$this->miscBasicPasswd = $basicPasswd;
	}
?>