<?php
/* $Id: $ */

class ServiceGridLangridService{
	private $id;
	private $serviceId;	private $serviceType;
	private $allowedAppProvision;	private $serviceName;	private $endpointUrl;	private $supportedLanguagesPaths;	private $organization;	private $copyright;	private $license;	private $description;	private $registeredDate;	private $updatedDate;	private $miscBasicUserid;
	private $miscBasicPasswd;

	public function getId() {
		return $this->id;
	}
	public function getServiceId() {		return $this->serviceId;	}	public function getServiceType() {		return $this->serviceType;	}
	public function getAllowedAppProvision() {
		return $this->allowedAppProvision;
	}	public function getServiceName() {		return $this->serviceName;	}	public function getEndpointUrl() {		return $this->endpointUrl;	}	public function getSupportedLanguagesPaths() {		return $this->supportedLanguagesPaths;	}	public function getOrganization() {		return $this->organization;	}	public function getCopyright() {		return $this->copyright;	}	public function getLicense() {		return $this->license;	}	public function getDescription() {		return $this->description;	}	public function getRegisteredDate() {		return $this->registeredDate;	}	public function getUpdatedDate() {		return $this->updatedDate;	}
	public function getMiscBasicUserid() {
		return $this->miscBasicUserid;
	}	public function getMiscBasicPasswd() {
		return $this->miscBasicPasswd;
	}

	public function setId($id) {
		$this->id = $id;
	}	public function setServiceId($serviceId) {		$this->serviceId = $serviceId;	}	public function setServiceType($serviceType) {		$this->serviceType = $serviceType;	}
	public function setAllowedAppProvision($allowedAppProvision) {
		$this->allowedAppProvision = $allowedAppProvision;
	}	public function setServiceName($serviceName) {		$this->serviceName = $serviceName;	}	public function setEndpointUrl($endpointUrl) {		$this->endpointUrl = $endpointUrl;	}	public function setSupportedLanguagesPaths($supportedLanguagesPaths) {		$this->supportedLanguagesPaths = $supportedLanguagesPaths;	}	public function setOrganization($organization) {		$this->organization = $organization;	}	public function setCopyright($copyright) {		$this->copyright = $copyright;	}	public function setLicense($license) {		$this->license = $license;	}	public function setDescription($description) {		$this->description = $description;	}	public function setRegisteredDate($registeredDate) {		$this->registeredDate = $registeredDate;	}	public function setUpdatedDate($updatedDate) {		$this->updatedDate = $updatedDate;	}
	public function setMiscBasicUserid($basicUserId) {
		$this->miscBasicUserid = $basicUserId;
	}
	public function setMiscBasicPasswd($basicPasswd) {
		$this->miscBasicPasswd = $basicPasswd;
	}}
?>