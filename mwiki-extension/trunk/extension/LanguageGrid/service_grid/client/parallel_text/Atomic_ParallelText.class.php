<?php
require_once(dirname(__FILE__).'/ParallelText.interface.php');

class Atomic_ParallelText implements ParallelText {

	protected $_client = null;

	function __construct($serviceId) {
		$this->_client = new LangridSoapClient($serviceId);
	}

	function search($headLang, $targetLang, $headWord, $matchingMethod) {
		$soapResponse = $this->_client->invokeService('search', array($headLang, $targetLang, $headWord, $matchingMethod));
		return $soapResponse;
	}
}
?>
