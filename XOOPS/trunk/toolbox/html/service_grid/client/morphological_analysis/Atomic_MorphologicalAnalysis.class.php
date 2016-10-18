<?php
require_once(dirname(__FILE__).'/MorphologicalAnalysis.interface.php');

class Atomic_MorphologicalAnalysis implements MorphologicalAnalysis {

	protected $_client = null;

	function Atomic_MorphologicalAnalysis($serviceId) {
		$this->_client = new LangridSoapClient($serviceId);
	}

	function analyze($language, $text) {
		$soapResponse = $this->_client->invokeService('analyze', array($language, $text));
		return $soapResponse;
	}
}
?>
