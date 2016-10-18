<?php
require_once("HTTP/Request.php");

require_once(dirname(__FILE__).'/../../config/ServiceGridConfig.class.php');
require_once(dirname(__FILE__).'/pbmessage/HTTPRpcMessage.class.php');
require_once(dirname(__FILE__).'/pbmessage/CommonMessage.class.php');
require_once(dirname(__FILE__).'/pbmessage/BilingualDictionaryMessage.class.php');
require_once(dirname(__FILE__).'/pbmessage/TranslationWithTemporalDictionaryMessage.class.php');

class LangridPbClient extends HTTP_Request{
	protected $headers;
	
	protected $serviceFullQualifiedName = '';
	protected $methodResponders;
	
	function LangridPbClient($serviceFullQualifiedName, $methodResponders, $options = array()) {
		$this->serviceFullQualifiedName = $serviceFullQualifiedName;
		$this->methodResponders = $methodResponders;
		
		$_options = array_merge(ServiceGridConfig::getPbClientInitialParameters(), $options);
		$_endpoint = ServiceGridConfig::getPbUrl($serviceFullQualifiedName);
	
		parent::HTTP_Request($_endpoint, $_options);
	}
	
	function setBindingTree($bindingtreeText) {
		$header = new Message_Common_Header();
		$header->name = 'http://langrid.nict.go.jp/process/binding/tree';
		$header->value = $bindingtreeText;
		$this->headers = array($header);
	}
	
	function invokeService($operation, $parameters) {
		$res = call_user_func_array(array($this, $operation), $parameters);
		$callTree = $this->_getCallTreeByResponseMessage($res);
		$calledService = $this->_getCalledService();
		
		if($this->_isPbFault($res)) {
			$payload = array(
				'status' => 'ERROR',
				'message' => $res->fault->faultString,
				'contents' => array(),
				'callTree' => $callTree
			);
		} else {
			$payload = array(
				'status' => 'OK',
				'message' => @$calledService['X-LanguageGrid-ServiceName'].' is successed.',
				'contents' => $res->result,
				'callTree' => $callTree,
				'LicenseInformation' => $this->_makeLicenseInformation($calledService, $callTree)
			);
		}
		
		return $payload;
	}
	
	protected function _getCallTreeByResponseMessage(PhpBuf_Message_Abstract $response) {
		$callTree = (string)$response->headers[0]->value;
		$callTree = json_decode($callTree, false);
		return $callTree;
	}
	
	/**
	 * HTTPレスポンスヘッダより実行サービス情報を収集
	 *（しかし、現状のレスポンスには必要なヘッダが含まれていない）
	 */
	protected function _getCalledService() {
		$h = explode(PHP_EOL, $this->getResponseHeader());
		$headers = array();
		foreach ($h as $line) {
			$tokens = explode(':', $line);
			if (count($tokens) == 2) {
				$headers[$tokens[0]] = $tokens[1];
			}
		}
		return $headers;
	}
	
	protected function _makeLicenseInformation($calledService, $callTree) {
		$licenseies = array();
		$licenseies[$this->serviceFullQualifiedName] = array(
										 'serviceName' => $calledService['X-LanguageGrid-ServiceName'],
										 'serviceCopyright' => $calledService['X-LanguageGrid-ServiceCopyright'],
										 'serviceLicense' => $calledService['X-LanguageGrid-ServiceLicense'],
										 'lastAccessDate' => date('D, j M Y G:i:s +0900')
		);
		$this->_parseTree($callTree, $licenseies);
		return $licenseies;
	}
	
	protected function _parseTree($obj, &$result) {
		if (empty($obj)) {
			return;
		}
		if (is_array($obj)) {
			foreach ($obj as $o) {
				$this->_parseTree($o, &$result);
			}
		} else {
			$obj = (array)$obj;
			if ($obj['faultCode'] == '' && count($obj['children']) == 0) {
				$serviceId = $obj['serviceId'];
				$result[$serviceId] = array(
								'serviceName' => $obj['serviceName'],
								'serviceCopyright' => $obj['serviceCopyright'],
								'serviceLicense' => $obj['serviceLicense'],
								'lastAccessDate' => date('D, j M Y G:i:s +0900')
				);
			}
			if (isset($obj['children']) && is_array($obj['children'])) {
				foreach ($obj['children'] as $child) {
					$this->_parseTree($child, &$result);
				}
			}
		}
	}
	
	protected function _isPbFault(PhpBuf_Message_Abstract $response) {
		return isset($response->fault);
	}
	
	protected function _callRPC($serviceName, $methodName, PhpBuf_Message_Abstract $requestMessage, $responderClassName) {
		$rpcHeaderWriter = new PhpBuf_IO_Writer;
		$rpcHeaderRequest = new HTTPRPC_Message_Request;
		$rpcHeaderRequest->serviceName = $serviceName . "." . $methodName;
		$rpcHeaderRequest->write($rpcHeaderWriter);
		
		$rpcPayloadWriter = new PhpBuf_IO_Writer;
		if(isset($this->headers)) {
			$requestMessage->headers = $this->headers;
		}
		$requestMessage->write($rpcPayloadWriter);
		
		$this->setBody(trim($rpcHeaderWriter->getData() . $rpcPayloadWriter->getData()));
		$this->sendRequest();
		
		$instance = new $responderClassName;
		$instance->read(new PhpBuf_IO_Reader($this->getResponseBody()));
		return $instance;
	}
	
	protected function _callMethod($methodName, PhpBuf_Message_Abstract $requestMessage) {
		$responderClassName = $this->methodResponders[$methodName];
		return $this->_callRPC($this->serviceFullQualifiedName, $methodName, $requestMessage, $responderClassName);
	}
	
	function __call($methodName, array $args = array()) {
		return $this->_callMethod($methodName, $args[0]);
	}
}
