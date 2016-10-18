<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/UserManagement.class.php');

/* $Id: ServiceManagement.class.php 6254 2012-01-23 05:33:54Z infonic $ */

class ServiceManagement implements LanguageGrid {

	protected $_client = null;

	public function __construct() {
		$wsdlUrl = ServiceGridConfig::getServiceGridContextUrl() . 'services/ServiceManagement?wsdl';
		$this->_client = new LangridSoapClient($wsdlUrl);
	}

	public function invoke() {
		return "UnSupported.";
	}

	public function searchServices($serviceType, $allowedAppProvision) {
		$params = array(
			'startIndex' => 0,
			'maxCount' => 100,
			'conditions' => array(
				array('fieldName' => 'serviceType', 'matchingValue' => $serviceType, 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'instanceType', 'matchingValue' => 'EXTERNAL', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'active', 'matchingValue' => 'true', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'allowedAppProvision', 'matchingValue' => $allowedAppProvision, 'matchingMethod' => 'IN')
			),
			'orders' => array(
				array('fieldName' => 'serviceName', 'direction' => 'ASCENDANT'),
			),
			'scope' => 'ACCESSIBLE'
		);

		if ($this->hasCompactAPI()) {
			$soapResponse = $this->_client->invokeService('searchServicesWithCompactLanguageExpression', $params);
		} else {
			$soapResponse = $this->_client->invokeService('searchServices', $params);
		}

		return $this->makeResponse($soapResponse);
	}

	public function getEBMT($allowedAppProvision) {
		$params = array(
			'startIndex' => 0,
			'maxCount' => 100,
			'conditions' => array(
//				array('fieldName' => 'serviceType', 'matchingValue' => '', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'instanceType', 'matchingValue' => 'EXTERNAL', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'active', 'matchingValue' => 'true', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'allowedAppProvision', 'matchingValue' => $allowedAppProvision, 'matchingMethod' => 'IN')
			),
			'orders' => array(
				array('fieldName' => 'serviceName', 'direction' => 'ASCENDANT'),
			),
			'scope' => 'ACCESSIBLE'
		);

		$soapResponse = $this->_client->invokeService('searchServices', $params);

		return $this->makeResponse($soapResponse);
	}

	public function getServiceProfile($serviceId) {
		$parameters = array('serviceId' => $serviceId);

		$soapResponse = $this->_client->invokeService('getServiceProfile', $parameters);
		if ($soapResponse['status'] != 'OK') {
			return array();
		}else{
			return $soapResponse['contents'];
		}
	}

	protected function makeResponse($soapRowResponse) {
		if ($soapRowResponse['status'] != 'OK') {
			return $soapRowResponse;
		}
		$elements = $soapRowResponse['contents']->elements;
		$UserManager = new UserManagement();

		$services = array();
		foreach ($elements as $service) {
			$data = array();

			$id = $service->endpointUrl;
			$data['id'] = $id;
			$data['serviceId'] = $service->serviceId;
			$data['name'] = $service->serviceName;
			$data['endpointUrl'] = $service->endpointUrl;
			$data['supportedLanguages'] = $service->supportedLanguages;
			$data['url'] = '';
			$data['isLanguageGridService'] = 1;
			$serviceId = $service->serviceId;

			$UserProfile = array();
			$UserProfile = $UserManager->getUserProfile($service->ownerUserId);
			$data['organization'] = $UserProfile->organization;

			$ServiceProfile = array();
			$ServiceProfile = $this->getServiceProfile($service->serviceId);
			$data['copyright'] = $ServiceProfile->copyrightInfo;
			$data['license'] = $ServiceProfile->licenseInfo;
			$data['description'] = $service->serviceDescription;
			$data['registeredDate'] = $this->_formatSoapDateTime($service->registeredDate);
			$data['updatedDate'] = $this->_formatSoapDateTime($service->updatedDate);

			//$service->active;
			//$service->instanceType;
			//$service->ownerUserId;
			//$service->registeredDate;
			//$service->serviceDescription;
			//$service->updatedDate;

			if (isset($data['supportedLanguages'])) {
				$aryLanguages = $data['supportedLanguages'];
				if (stripos($serviceId, 'abstract') === 0 && count($aryLanguages) == 1) {
					$langs = $aryLanguages[0]->languages;
					$bNotStar = false;
					foreach ($langs as $lang) {
						if ($lang != '*') {
							$bNotStar = true;
							break;
						}
					}
					if (!$bNotStar) {
						continue;
					}
				}

				$pathSet =& $this->createLanguagePathSet($aryLanguages);
				$data['path'] =& $pathSet['path'];
				$data['supportedLanguages'] = $pathSet['supportedLanguages'];
			}
			$this->services[$id] = $data;
			$services[] =& $this->services[$id];
		}
		$result = array('status' => 'OK', 'message' => 'No Error', 'contents' => $services);

		return $result;

	}

	const NAME_COMPACT_API = 'ServiceEntryWithCompactLanguageExpressionSearchResult searchServicesWithCompactLanguageExpression';
	private $hasCompact = null;
	public function hasCompactAPI() {
		if ($this->hasCompact !== false || ! $this->hasCompact !== true) {
			$this->hasCompact = false;
			$funcs = $this->_client->__getFunctions();
			foreach ($funcs as $func) {
				if(strpos($func, ServiceManagement::NAME_COMPACT_API, 0) === 0) {
					$this->hasCompact = true;
					break;
				}
			}
		}
		return $this->hasCompact;
	}

	public function createLanguagePathSet($arrayLanguages) {
		$aryPath = array();
		$aryLangs = array();

		if ($this->hasCompactAPI()) {
			// for API searchServicesWithCompactLanguageExpression
			foreach ($arrayLanguages as $languages) {
				switch($languages->type) {
				case 'PAIR':
					$result = $this->processSimplePair($languages->languages);
					break;
				case 'PAIR_COMBINATION':
					$result = $this->processPairCombination($languages->languages);
					break;
				case 'LANG_LIST':
					$result = $this->processLangList($languages->languages);
					break;
				default:
					$result = $this->processSimplePair($languages->languages);
					break;
				}
				if (! count($aryLangs)) {
					$aryPath = $result['path'];
					$aryLangs = $result['supportedLanguages'];
				} else {
					$aryPath = array_merge($aryPath, $result['path']);
					$aryLangs = array_merge($aryLangs, $result['supportedLanguages']);
				}
			}
		} else {
			// for API searchServices
			foreach ($arrayLanguages as $languages) {
				$langs =& $languages->languages;
				$aryLangs[] = $langs;
				$aryPath[] = implode('2', $langs);
			}
		}

		$data['path'] = &$aryPath;
		$data['supportedLanguages'] = &$aryLangs;
		return $data;
	}

	protected function processSimplePair($langs) {
		return array('path' => array(implode('2', $langs)), 'supportedLanguages' => array($langs));
	}

	protected function processLangList($langs) {
		$l = array();
		$c = count($langs);
		for ($i = 0; $i < $c; $i++) {
			$l[] = $langs[$i];
		}
		$ret = array('path' => $l, 'supportedLanguages' => $l);
		return $ret;
	}

	protected function processPairCombination($langs) {
		$l = array();
		$p = array();
		$c = count($langs);
		for ($i = 0; $i < $c; $i++) {
			$lang1 = $langs[$i];
			for ($j = $i + 1; $j < $c; $j++) {
				$lang2 = $langs[$j];
				$l[] = array($lang1, $lang2);
				$p[] = $lang1 . '2' . $lang2;
				$l[] = array($lang2, $lang1);
				$p[] = $lang2 . '2' . $lang1;
			}
		}
		$ret = array('path' => $p, 'supportedLanguages' => $l);
		return $ret;
	}

	private function _formatSoapDateTime($datetimeString) {
		if (($timestamp = strtotime($datetimeString)) === false) {
			return $datetimeString;
		} else {
			return date(DATE_ATOM, $timestamp);
		}
	}
}
?>