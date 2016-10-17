<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');

class BackTranslation extends AbstractServiceGridClient {

	protected $_client = null;

	protected $forwardServiceId = null;
	protected $backwardServiceId = null;
	protected $context = null;
	
    public function __construct() {
		$wsdl = 'BackTranslation';
		$this->_client = new LangridSoapClient($wsdl);
    }

    public function _translate() {
		$a = $this->context->getServiceIds();
		$b = $this->context->getReverseServiceIds();
		
		$this->forwardServiceId = $a[0];
		$this->backwardServiceId = $b[0];

    	$this->_makeBinding();
    	
		$soapResponse = $this->_client->invokeService('backTranslate', array(
			$this->context->getSourceLang(),
			$this->context->getIntermediateLang(),
			$this->context->getSource()
		));
		return $soapResponse;
    }


	protected function _makeBinding() {
		$binding = '[{"children":[],"invocationName":"ForwardTranslationPL","serviceId":"%s"},{"children":[],"invocationName":"BackwardTranslationPL","serviceId":"%s"}]';
		
		debugLog(sprintf($binding, $this->forwardServiceId, $this->backwardServiceId));
		
		$this->_client->setBindingTree(sprintf($binding, $this->forwardServiceId, $this->backwardServiceId));
	}
}
?>