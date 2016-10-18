<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/UserManagement.php');

class ServiceManagement implements LanguageGrid {

	protected $_client = null;

	function __consutruct() {
		$this->_client = new LangridSoapClient("http://langrid.nict.go.jp/langrid-1.2/services/ServiceManagement?wsdl");
	}

	public function invoke() {
		return "UnSupported.";
	}
	public function searchTranslator() {

	}

	public function searchServices($serviceType) {
		$params = array(
			'startIndex' => 0,
			'maxCount' => 100,
			'conditions' => array(
				array('fieldName' => 'serviceType', 'matchingValue' => $serviceType, 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'instanceType', 'matchingValue' => 'EXTERNAL', 'matchingMethod' => 'COMPLETE'),
				array('fieldName' => 'active', 'matchingValue' => 'true', 'matchingMethod' => 'COMPLETE'),
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
		$UserManager =& new UserManagement();

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
							$bNotStat = true;
							break;
						}
					}
					if (!$bNotStar) {
						continue;
					}
				}

				$aryPath =Array();
				$aryLangs = Array();
				foreach ($aryLanguages as $languages) {
					$langs = $languages->languages;
					$aryLangs[] = $langs;
					$aryPath[] = implode('2', $langs);
				}
				$data['path'] = $aryPath;
				$data['supportedLanguages'] = $aryLangs;
			}
			$this->services[$id] = $data;
			$services[] =& $this->services[$id];
		}
		$result = array('status' => 'OK', 'message' => 'No Error', 'contents' => $services);

		return $result;

	}
}
?>
