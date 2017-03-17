<?php

require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 翻訳サービスのみ使用するクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class AtomicTranslation extends AbstractServiceGridClient {

	protected $_client = null;
	protected $_serviceId;
	
    public function __construct($serviceId) {
		$this->_serviceId = $serviceId;
		debugLog('AtomicTranslation::serviceID:'.$this->_serviceId);
    }

	public function setContext($context) {
		parent::setContext($context);
		$this->createClient($this->_serviceId);
	}
    
    public function _translate() {
		$soapResponse = $this->_client->invokeService('translate', array(
			$this->context->getSourceLang(),
			$this->context->getTargetLang(), 
			$this->context->getSource()
		));
		
		return $soapResponse;
    }
}
?>