<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

class BackTranslation extends AbstractServiceGridClient {

	protected $_client = null;

	protected $forwardServiceId = null;
	protected $backwardServiceId = null;
	protected $context = null;

	public function setContext($context) {
		parent::setContext($context);
		$this->createClient($this->getGridId() . ':BackTranslation');
	}

    public function _translate() {
		// Translator
		$a = $this->context->getServiceIds();
		$b = $this->context->getReverseServiceIds();
		$this->forwardServiceId = $a[0];
		$this->backwardServiceId = $b[0];

		$binds = $this->context->getBindings();
		$rBinds = $this->context->getReverseBindings();

		foreach ($binds as $bind) {
			if ($bind->getBindType == '0') {
				$this->forwardServiceId = $bind->getBindValue();
			}
		}

		foreach ($rBinds as $rBind) {
			if ($rBind->getBindType == '0') {
				$this->backwardServiceId = $rBind->getBindValue();
			}
		}

		$this->_makeBinding();
    	
		$soapResponse = $this->_client->invokeService('backTranslate', array(
			$this->context->getSourceLang(),
			$this->context->getIntermediateLang(),
			$this->context->getSource()
		));
				
		debugLog(implode(',', array(
			$this->context->getSourceLang(),
			$this->context->getIntermediateLang(),
			$this->context->getSource()
		)));
		
		return $soapResponse;
    }


	protected function _makeBinding() {
		$binding = '[{"children":[],"invocationName":"ForwardTranslationPL","serviceId":"%s"},{"children":[],"invocationName":"BackwardTranslationPL","serviceId":"%s"}]';
		
		debugLog(sprintf($binding, $this->forwardServiceId, $this->backwardServiceId));
		
		$this->_client->setBindingTree(sprintf($binding, $this->forwardServiceId, $this->backwardServiceId));
	}
}
?>