<?php
require_once(dirname(__FILE__).'/BilingualDictionary.interface.php');

class BilingualDictionaryCrossSearch implements BilingualDictionary {

	protected $_client = null;
	protected $services = array();

	function BilingualDictionaryCrossSearch($services) {
		$this->_client = new LangridSoapClient('BilingualDictionaryCrossSearch');
		$this->services = $services;
	}

	function search($headLang, $targetLang, $headWord, $matchingMethod) {
		$this->_makeBinding();
		$soapResponse = $this->_client->invokeService('search', array($headLang, $targetLang, $headWord, $matchingMethod));
		return $soapResponse;
	}

	function setBilingualDictionaries($services) {
		$this->services = $services;
	}

	protected function _makeBinding() {
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';
		$bindArray = array();

		$num = 1;
		foreach ($this->services as $id) {
			$bindArray[] = sprintf($bindTemp, '', 'BilingualDictionaryPL'.$num++, $id);
		}

		$binding = '['.implode(',', $bindArray).']';
		$this->_client->setBindingTree($binding);
	}
}
?>
