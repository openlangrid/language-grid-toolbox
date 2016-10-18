<?php
require_once(dirname(__FILE__).'/BilingualDictionary.interface.php');

class Atomic_BilingualDictionary implements BilingualDictionary {

	protected $_client = null;

	function Atomic_BilingualDictionary($serviceId) {
		$this->_client = new LangridSoapClient($serviceId);
	}

	function search($headLang, $targetLang, $headWord, $matchingMethod) {
		$soapResponse = $this->_client->invokeService('search', array($headLang, $targetLang, $headWord, $matchingMethod));
		return $soapResponse;
	}
}
?>
