<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

/* $Id: UserManagement.class.php 4654 2010-10-28 06:37:35Z yoshimura $ */

class UserManagement implements LanguageGrid {

	protected $_client = null;

	function __construct() {
		$wsdlUrl = ServiceGridConfig::getServiceGridContextUrl() . 'services/UserManagement?wsdl';
		$this->_client = new LangridSoapClient($wsdlUrl);
	}

	public function invoke() {
		return "UnSupported.";
	}

	public function getUserProfile($ownerUserId) {
		$parameters = array('userId' => $ownerUserId);

		$soapResponse = $this->_client->invokeService('getUserProfile', $parameters);
		if ($soapResponse['status'] != 'OK') {
			return "";
		}else{
			return $soapResponse['contents'];
		}
	}
}
?>
