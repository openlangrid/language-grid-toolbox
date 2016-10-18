<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');

class UserManagement implements LanguageGrid {

	protected $_client = null;

	function __construct() {
		$this->_client = new LangridSoapClient("http://langrid.nict.go.jp/langrid-1.2/services/UserManagement?wsdl");
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
