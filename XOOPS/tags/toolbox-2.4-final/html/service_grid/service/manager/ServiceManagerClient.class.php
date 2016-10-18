<?php
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
require_once(dirname(__FILE__).'/UserManagerClient.class.php');
/**
 * <#if locale="en">
 * Client class of Language Grid Service Manager
 * <#elseif locale="ja">
 * 言語グリッドサービスマネージャクライアント
 * </#if>
 */
class ServiceManagerClient extends ServiceClient {
	public function __construct() {
		parent::__construct('services/ServiceManagement?wsdl');
	}

	public function loadServices($serviceType) {
		$parameters = array(
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

		$res = parent::call('searchServices', $parameters);
		//getServiceProfile
		if ($res['status'] != 'OK') {
			return $res;
		}
		$elements = $res['contents']->elements;
		$UserManager =& new UserManagerClient();

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
			$UserProfile = $UserManager->loadUserProfile($service->ownerUserId);
			$data['organization'] = $UserProfile->organization;

			$ServiceProfile = array();
			$ServiceProfile = $this->getServiceProfile($service->serviceId);
			$data['copyright'] = $ServiceProfile->copyrightInfo;
			$data['license'] = $ServiceProfile->licenseInfo;
			$data['description'] = $service->serviceDescription;

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

	public function getServiceProfile($serviceId) {
		$parameters = array('serviceId' => $serviceId);

		$res = parent::call('getServiceProfile', $parameters);

		//getServiceProfile
		if ($res['status'] != 'OK') {
			return array();
		}else{
			return $res['contents'];
		}
	}

	public function loadTranslator() {
		return $this->loadServices('TRANSLATION');
	}

	public function loadDictionary() {
		return $this->loadServices('BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH');
	}

	public function loadAnalyzer() {
		return $this->loadServices('MORPHOLOGICALANALYSIS');
	}

	protected function makeBindingHeader($parameters){
		return '';
	}
}
?>