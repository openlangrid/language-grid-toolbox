<?php
require_once(dirname(__FILE__).'/../../config/ServiceGridConfig.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
class LangridSoapClient extends SoapClient {

	protected $wsdl;

    function LangridSoapClient($wsdl, $options = array(), $force = false) {
		if (!$force) {
			$_options = array_merge(ServiceGridConfig::getSoapClientInitialParameters(), $options);
		} else {
			$_options = $options;
		}
		$this->wsdl = $wsdl;
    	$_wsdl = ServiceGridConfig::getWsdlUrl($wsdl);
		debugLog($_wsdl);
		parent::SoapClient($_wsdl, $_options);
    }

    function setBindingTree($bindingtreeText) {
		debugLog("### binding tree text start ###");
		debugLog(print_r(json_decode($bindingtreeText), 1));
		debugLog("### binding tree text end ###");
		
    	$header = new SoapHeader('http://langrid.nict.go.jp/process/binding/tree', 'binding', $bindingtreeText);
    	parent::__setSoapHeaders($header);
    }

    function invokeService($operation, $parameters) {
	    $dist = call_user_func_array(array($this, $operation), $parameters);
		$callTree = $this->_getCallTreeBySoapResponse();
		$calledService = $this->_getCalledService();

		if (is_soap_fault($dist)) {
			$payload = array(
				'status' => 'ERROR',
				'message' => $dist->faultstring,
				'contents' => array(),
				'callTree' => $callTree
			);
		} else {
			$payload = array(
				'status' => 'OK',
				'message' => @$calledService['X-LanguageGrid-ServiceName'].' is successed.',
				'contents' => $dist,
//				'calledService' => $calledService,
				'callTree' => $callTree,
				'LicenseInformation' => $this->_makeLicenseInformation($calledService, $callTree)
			);
		}

    	return $payload;
    }

	/**
	 * <#if lang="ja">
	 * SOAPレスポンスヘッダよりCallTreeを収拾.
	 * </#if>
	 */
    protected function _getCallTreeBySoapResponse() {
		$soap = $this->__getLastResponse();
		$soap = str_replace(':', '_', $soap);

		$dom = new DOMDocument;
		$dom->loadXml($soap);
		$s = simplexml_import_dom($dom);

		$callTree = (string)$s->soapenv_Header->ns1_calltree;
		$callTree = json_decode(str_replace('_', ':', $callTree), false);
		return $callTree;
    }

	/**
	 * <#if lang="ja">
	 * HTTPレスポンスヘッダより実行サービス情報を収拾.
	 * X-LanguageGrid-ServiceName
	 * X-LanguageGrid-ServiceCopyright
	 * X-LanguageGrid-ServiceLicense
	 * </#if>
	 */
    protected function _getCalledService() {
		$h = explode(PHP_EOL, $this->__getLastResponseHeaders());
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
    	$licenseies[$this->wsdl] = array(
			'serviceName' => @$calledService['X-LanguageGrid-ServiceName'],
			'serviceCopyright' => @$calledService['X-LanguageGrid-ServiceCopyright'],
			'serviceLicense' => @$calledService['X-LanguageGrid-ServiceLicense'],
			'lastAccessDate' => @date('D, j M Y G:i:s +0900')
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
}
?>