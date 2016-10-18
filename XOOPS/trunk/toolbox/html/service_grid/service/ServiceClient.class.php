<?php
//die("This file is deprecated. ".__FILE__);

require_once('SOAP/Client.php');
/**
 * <#if locale="en">
 * Superclass of Web service client class
 * <#elseif locale="ja">
 * Webサービスクライアントの基底クラス
 * </#if>
 */
abstract class ServiceClient extends SOAP_Client {

	protected $callTree = null;

	protected $encoding = 'UTF-8';
	protected $timeout = 30;
	protected $trace = true;

	protected $moduleConfig = null;

	public function __construct($service) {
		// get module config
		$this->moduleConfig = $this->_getXoopsModuleConfig();
		$option = array(
			'user' => $this->moduleConfig['langrid_id'],
			'pass' => $this->moduleConfig['langrid_pass'],
			'encoding' => $this->encoding,
			'timeout' => $this->timeout
		);
		$proxyHost = $this->moduleConfig['proxy_host'];
		$proxyPort = $this->moduleConfig['proxy_port'];
		if ($proxyHost != '') {
			$option[ 'proxy_host' ] = $proxyHost;
		}
		if ($proxyPort != '') {
			$option[ 'proxy_port' ] = $proxyPort;
		}

		$wsdl = $this->moduleConfig['core_node_url'].$service;
		parent::__construct($wsdl, true, false, $option);
	}

	public function call($operation, $parameters, $bindParameters = array()) {
		parent::setTrace($this->trace);

		$response = array('status' => 'OK', 'message' => 'soap request successed.', 'contents' => array());

		// Setting for SOAP Header
		$bind = $this->makeBindingHeader($bindParameters);
		if (!empty($bind)) {
			$this->addHeader(new SOAP_Header('{http://langrid.nict.go.jp/process/binding/tree}binding', '', $bind));
		}

		// Invoke!!
		$res =& parent::call($operation, $parameters);

		$this->callTree = json_decode($this->headersIn['calltree']);

		if (!PEAR::isError($res)) {
			$response
				= array('status' => 'OK',
						'contents' => &$res);
	    }
	    else {
	    	$response
	    		= array('status' => 'ERROR',
						'message' => print_r($this->fault->userinfo, true),
	    				'contents' => $this->_makeErrorMessage());
	    }

		return $response;
	}

	abstract protected function makeBindingHeader($parameters);

	private function _makeErrorMessage() {

		$faultObj =& $this->fault->getFault();
		$message = $faultObj->faultstring;

//		$message = $this->fault->message;
		$userinfo = (array)$this->fault->userinfo;
		// If the client calls the service
		if ($this->callTree == null) {
			$serviceId = '';
			$serviceName = '';
		}
		// If the service is called in a composite service
		else {
			// service ID
			$serviceId = $this->callTree[0]->serviceId;
			// service name
			$serviceName = $this->callTree[0]->serviceName;
		}
		// Messages with no parameter
		if (preg_match('/jp.go.nict.langrid.service_1_2.AccessLimitExceededException/', $message)) {
			return sprintf(_MD_LANGRID_ERROR_ACCESS_LIMIT_EXCEEDED_EXCEPTION, $serviceId, $serviceName);
		}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.NoAccessPermissionException/', $message)) {
			return sprintf(_MD_LANGRID_ERROR_NO_ACCESS_PERMISSION_EXCEPTION, $serviceId, $serviceName);
		}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.NoValidEndpointsException/', $message)) {
			return sprintf(_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION, $serviceId, $serviceName);
		}
		// Messages with one parameter
		else if (preg_match('/jp.go.nict.langrid.service_1_2.InvalidParameterException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.InvalidParameterException'];
			$parametername = $exception->parameterName;

			return sprintf(_MD_LANGRID_ERROR_INVALID_PARAMETER_EXCEPTION, $serviceId, $serviceName, $parameterName);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.ServiceNotActiveException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.ServiceNotActiveException'];
			$serviceId = $exception->serviceId;

			return sprintf(_MD_LANGRID_ERROR_SERVICE_NOT_ACTIVE_EXCEPTION, $serviceId);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.ServiceNotFoundException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.ServiceNotFoundException'];
			$serviceId = $exception->serviceId;

			return sprintf(_MD_LANGRID_ERROR_SERVICE_NOT_FOUND_EXCEPTION, $serviceId);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.ServiceAlreadyExistsException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.ServiceAlreadyExistsException'];
			$serviceId = $exception->serviceId;

			return sprintf(_MD_LANGRID_ERROR_SERVICE_ALREADY_EXISTS_EXCEPTION, $serviceId);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.NoValidEndpointsException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.NoValidEndpointsException'];
			$serviceId = $exception->serviceId;
			$nodeId = $exception->nodeId;

			return sprintf(_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION, $serviceId, $nodeId);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.ServiceConfigurationException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.ServiceConfigurationException'];
			$nodeId = $exception->nodeId;
			return sprintf(_MD_LANGRID_ERROR_SERVICE_CONFIGURATION_EXCEPTION, $nodeId);
    	}
		else if (preg_match('/jp.go.nict.langrid.service_1_2.UnknownException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.UnknownException'];
			$nodeId = $exception->nodeId;

			return sprintf(_MD_LANGRID_ERROR_UNKNOWN_EXCEPTION, $nodeId);
    	}
    	else if (preg_match('/jp.go.nict.langrid.service_1_2.UnsupportedLanguageException/', $message)) {
    		$exception = $userinfo['jp.go.nict.langrid.service_1_2.UnsupportedLanguageException'];
			$language = $exception->language;

			return sprintf(_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_EXCEPTION, $language, $serviceId, $serviceName);
		}

    	// Messages with two parameters
		else if (preg_match('/jp.go.nict.langrid.service_1_2.UnsupportedLanguagePairException/', $message)) {
			$exception = $userinfo['jp.go.nict.langrid.service_1_2.UnsupportedLanguagePairException'];
			@$language1 = $exception->languagePair->first;
			if ($language1 == '') {
				$language1 = $exception->language1;
			}
			@$language2 = $exception->languagePair->second;
			if ($language2 == '') {
				$language2 = $exception->language2;
			}
			return sprintf(_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_PAIR_EXCEPTION, $language1, $language2, $serviceId, $serviceName);
		}
    	else if (preg_match('/jp.go.nict.langrid.service_1_2.ProcessFailedException/', $message)) {
    		$exception = $userinfo['jp.go.nict.langrid.service_1_2.ProcessFailedException'];
			$description = $exception->description;

			return sprintf(_MD_LANGRID_ERROR_PROCESS_FAILED_EXCEPTION, $description);
		} else {
			return $message;
		}
	}

	// load to langrid module config.
	private function _getXoopsModuleConfig() {
		$config = array();

		$config['core_node_url'] = LG_ACCESS_CORE_NODE;
		$config['langrid_id'] = LG_ACCESS_LANGRID_USER;
		$config['langrid_pass'] = LG_ACCESS_LANGRID_PASS;
		if (defined('LG_ACCESS_PROXY_HOST')) {
			$config['proxy_host'] = LG_ACCESS_PROXY_HOST;
		} else {
			$config['proxy_host'] = '';
		}
		if (defined('LG_ACCESS_PROXY_PORT')) {
			$config['proxy_port'] = LG_ACCESS_PROXY_PORT;
		} else {
			$config['proxy_port'] = '';
		}

		return $config;
	}

	/**
	 * <#if locale="en">
	 * This method returns license information of a local service (translation service)
	 * <#elseif locale="ja">
	 * ローカルサービス（翻訳機）のライセンス情報を返す
	 * </#if>
	 */
	protected function getLocalTranslationLicense($exec) {
		$license = array();
		$serviceType = $exec->get('service_type');
		if ($serviceType == 1) {
			$serviceId = $exec->get('service_id');
			require_once dirname(__FILE__).'/../../class/LangridServicesClass.php';
			$langridServices = new LangridServicesClass();
			$result = $langridServices->searchLocalTranslatorsByEndpoint($serviceId);
			if (isset($result[0])) {
				$license[$result[0]['service_id']] = array(
					'serviceName' => $result[0]['service_name'],
					'serviceCopyright' => $result[0]['copyright'],
					'serviceLicense' => $result[0]['license'],
					'lastAccessDate' => date('D, j M Y G:i:s +0900')
				);
			}
		}
		return $license;
	}

    public function getGridId() {
        return $this->moduleConfig['core_node_grid_id'];
    }
}
?>