<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once dirname(__FILE__).'/LangridServicesClass.php';
require_once XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
error_reporting(0);
/**
 * @author kitajima
 */
class ImportedServices {
	private $dao;

	public function __construct() {
		$this->dao = new LangridServicesClass();
	}

	/**
	 * @throws SQLException
	 * @throws IllegalArgumentException
	 */
	public function addService($serviceName, $serviceType
		, $endpointUrl, $languages, $provider, $copyright, $license) {
		$langridServiceTO = new LangridServiceTO();
		$langridServiceTO->serviceName = $serviceName;
		$langridServiceTO->serviceType = $serviceType;
		$langridServiceTO->endpointUrl = $endpointUrl;
		$langridServiceTO->supportedLanguagePaths = implode(',', $languages);
		$langridServiceTO->organization = $provider;
		$langridServiceTO->copyright = $copyright;
		$langridServiceTO->license = $license;
		$langridServiceTO->description = '';

		if ($this->dao->isExistsServiceName($serviceName)) {

			throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE, $serviceName));
		} else if ($this->dao->isExistsEndpointUrl($endpointUrl)) {

			//throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE, $endpointUrl));
		}

		$service = $this->dao->create($langridServiceTO);

		return array(
			'id' => $service->serviceId
			, 'name' => $service->serviceName
			, 'type' => $service->serviceType
			, 'endpointUrl' => $service->endpointUrl
			, 'provider' => $service->organization
			, 'copyright' => $service->copyright
			, 'license' => $service->license
			, 'languagePaths' => $this->getLanguagesFromLanguagePaths(
					explode(',', $service->supportedLanguagePaths), $service->serviceType)
			, 'languages' => $this->getLanguagesFromLanguagePaths(
					explode(',', $service->supportedLanguagePaths), $service->serviceType)
			, 'registrationDate' => $service->createDate
		);
	}

	/**
	 * @throws SQLException
	 * @throws IllegalArgumentException
	 */
	public function editService($serviceId, $endpointUrl, $languages, $provider, $copyright, $license) {
		$langridServiceTO = new LangridServiceTO();
		$langridServiceTO->serviceId = $serviceId;
		$langridServiceTO->endpointUrl = $endpointUrl;
		$langridServiceTO->supportedLanguagePaths = implode(',', $languages);
		$langridServiceTO->organization = $provider;
		$langridServiceTO->copyright = $copyright;
		$langridServiceTO->license = $license;
		$langridServiceTO->description = '';

		if ($this->dao->isExistsEndpointUrl($endpointUrl, $serviceId)) {
			//throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE, $endpointUrl));
		}

		$oldService = $this->dao->find($serviceId);
		$service = $this->dao->update($langridServiceTO);

		$tss = new TranslationServiceSetting();

		switch ($service->serviceType) {
		case EnumLangridServiceType::$IMPORTED_TRANSLATOR;
			$tss->updateLocalTranslation($oldService->endpointUrl, $service->endpointUrl, $service->supportedLanguagePaths);
			break;
		case EnumLangridServiceType::$IMPORTED_DICTIONARY;
			$tss->updateLocalDictionary($oldService->endpointUrl, $service->endpointUrl);
			break;
		}

		return array(
			'id' => $service->serviceId
			, 'name' => $service->serviceName
			, 'type' => $service->serviceType
			, 'endpointUrl' => $service->endpointUrl
			, 'provider' => $service->organization
			, 'copyright' => $service->copyright
			, 'license' => $service->license
			, 'languagePaths' => $this->getLanguagesFromLanguagePaths(
					explode(',', $service->supportedLanguagePaths), $service->serviceType)
			, 'languages' => $this->getLanguagesFromLanguagePaths(
					explode(',', $service->supportedLanguagePaths), $service->serviceType)
			, 'registrationDate' => $service->createDate
		);
	}

	/**
	 * @throws SQLException
	 */
	public function loadServices() {
		$services = $this->dao->getAllImportedServices();
		$return = array();
		foreach ($services as $service) {
			$return[] = array(
				'id' => $service->serviceId
				, 'name' => $service->serviceName
				, 'type' => $service->serviceType
				, 'endpointUrl' => $service->endpointUrl
				, 'provider' => $service->organization
				, 'copyright' => $service->copyright
				, 'license' => $service->license
				, 'languagePaths' => $this->getLanguagesFromLanguagePaths(
						explode(',', $service->supportedLanguagePaths), $service->serviceType)
				, 'languages' => $this->getLanguagesFromLanguagePaths(
						explode(',', $service->supportedLanguagePaths), $service->serviceType)
				, 'registrationDate' => $service->createDate
			);
		}
		return $return;
	}

	/**
	 * @param String $serviceId
	 * @throws SQLException
	 * @return void
	 */
	public function removeService($serviceId) {

		$service = $this->dao->find($serviceId);

		$this->dao->remove($serviceId);

		$tss = new TranslationServiceSetting();

		switch ($service->serviceType) {
		case EnumLangridServiceType::$IMPORTED_DICTIONARY:
			$tss->removeLocalDictionary($service->endpointUrl);
			break;
		case EnumLangridServiceType::$IMPORTED_TRANSLATOR;
			$tss->removeLocalTranslation($service->endpointUrl);
			break;
		}

		return;
	}

	public function getSupportedLanguages() {
		if (!isset($this->LANGRID_LANGUAGE_ARRAY)) {
			require dirname(__FILE__).'/../include/Languages.php';
			$this->LANGRID_LANGUAGE_ARRAY = $LANGRID_LANGUAGE_ARRAY;
		}
		$languages = array();
		foreach ($this->LANGRID_LANGUAGE_ARRAY as $code => $name) {
			$languages[] = array(
				'code' => $code
				, 'name' => $name
			);
		}
		return $languages;
	}

	private function getLanguagesFromLanguagePaths($languagePaths, $serviceType) {
		if (!isset($this->LANGRID_LANGUAGE_ARRAY)) {
			require dirname(__FILE__).'/../include/Languages.php';
			$this->LANGRID_LANGUAGE_ARRAY = $LANGRID_LANGUAGE_ARRAY;
		}
		$return = array();
		if ($serviceType == EnumLangridServiceType::$IMPORTED_DICTIONARY) {
			$used = array();
			foreach ($languagePaths as $languagePath) {
				$languages = explode('2', $languagePath);
				foreach ($languages as $languageCode) {
					if (!$languageCode || in_array($languageCode, $used)) {
						continue;
					}
					$used[] = $languageCode;
					$return[] = array(
						'code' => $languageCode
						, 'name' => $this->LANGRID_LANGUAGE_ARRAY[$languageCode]
					);
				}
			}
		} else if ($serviceType == EnumLangridServiceType::$IMPORTED_TRANSLATOR) {
			$paths = array();
			foreach ($languagePaths as $languagePath) {
				if (in_array($languagePath, $paths)) {
					continue;
				}
				$bidirectional = false;
				$languageCodes = explode('2', $languagePath);
				if (in_array($languageCodes[1].'2'.$languageCodes[0], $languagePaths)) {
					$paths[] = $languageCodes[1].'2'.$languageCodes[0];
					$bidirectional = true;
				}
				$return[] = array(
					'from' => array(
						'code' => $languageCodes[0]
						, 'name' => $this->LANGRID_LANGUAGE_ARRAY[$languageCodes[0]]
					),
					'bidirectional' => $bidirectional,
					'to' => array(
						'code' => $languageCodes[1]
						, 'name' => $this->LANGRID_LANGUAGE_ARRAY[$languageCodes[1]]
					)
				);
			}
		}
		return $return;
	}
}
?>