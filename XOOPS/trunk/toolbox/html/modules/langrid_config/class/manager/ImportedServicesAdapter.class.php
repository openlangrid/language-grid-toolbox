<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: ImportedServicesAdapter.class.php 4676 2010-11-04 07:40:26Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');

class ImportedServicesAdapter {

	private $mLangridServiceDao = null;

	public function __construct() {
		$this->mLangridServiceDao = DaoAdapter::getAdapter()->getLangridServicesDao();
	}

	/**
	 * @throws SQLException
	 * @throws IllegalArgumentException
	 */
	public function addService($serviceName, $serviceType
		, $endpointUrl, $languages, $provider, $copyright, $license, $basicUserid, $basicPasswd) {

		if ($this->isExistsServiceName($serviceName)) {
			throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE, $serviceName));
		} else if ($this->isExistsEndpointUrl($endpointUrl)) {
			// TODO:エンドポイントURLの重複を許す。
			//throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE, $endpointUrl));
		}

		$dto = new ServiceGridLangridService();

		$serviceId = md5(time().$serviceName.$endpointUrl);

		$dto->setServiceId($serviceId);
		$dto->setServiceName($serviceName);
		$dto->setServiceType($serviceType);
		$dto->setAllowedAppProvision('IMPORTED');
		$dto->setEndpointUrl($endpointUrl);
		$dto->setSupportedLanguagesPaths(implode(',', $languages));
		$dto->setOrganization($provider);
		$dto->setCopyright($copyright);
		$dto->setLicense($license);
		$dto->setDescription('');
		$dto->setMiscBasicUserid($basicUserid);
		$dto->setMiscBasicPasswd($basicPasswd);

		$d = date(DATE_ATOM, time());
		$dto->setRegisteredDate($d);
		$dto->setUpdatedDate($d);

		$dist = $this->mLangridServiceDao->insert($dto);
		if (!$dist) {
			throw new Exception('Invalid import service.');
		}

		return $this->format($dist);
	}

	/**
	 * @throws SQLException
	 * @throws IllegalArgumentException
	 */
	public function editService($serviceId, $endpointUrl, $languages, $provider, $copyright, $license, $basicUserid, $basicPasswd) {
		$dist = $this->mLangridServiceDao->queryGetByServiceId($serviceId, 'IMPORTED');
		if ($dist == null || count($dist) == 0) {
			throw new Exception(_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES);
		} else if ($this->isExistsEndpointUrl($endpointUrl)) {
			//throw new Exception(sprintf(_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE, $endpointUrl));
		}

		$dto = $dist[0];
		$dto->setEndpointUrl($endpointUrl);
		$dto->setSupportedLanguagesPaths(implode(',', $languages));
		$dto->setOrganization($provider);
		$dto->setCopyright($copyright);
		$dto->setLicense($license);
		$dto->setDescription('');
		$dto->setMiscBasicUserid($basicUserid);
		$dto->setMiscBasicPasswd($basicPasswd);

		$d = date(DATE_ATOM, time());
		$dto->setUpdatedDate($d);

		$dist = $this->mLangridServiceDao->update($dto);
		if (!$dist) {
			throw new Exception('Invalid import service.');
		}

		return $this->format($dist);
	}

	/**
	 * @throws SQLException
	 */
	public function loadServices() {
		$services = $this->mLangridServiceDao->queryFindServicesByTypeAndProvisions(null, 'IMPORTED');
		$result = array();
		foreach ($services as $service) {
			$result[] = $this->format($service);
		}
		return $result;
	}

	/**
	 * @param String $serviceId
	 * @throws SQLException
	 * @return void
	 */
	public function removeService($serviceId) {
		$dist = $this->mLangridServiceDao->queryGetByServiceId($serviceId, 'IMPORTED');
		if ($dist == null || count($dist) == 0) {
			//throw new Exception(_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES);
			return;
		}
		$this->mLangridServiceDao->deleteByServiceId($serviceId, 'IMPORTED');
		return;
	}

	/*
	 * <#if locale="ja">
	 * 全サポート言語
	 * @see /modules/langrid/include/Languages.php
	 * <#/if>
	 */
	public function getSupportedLanguages() {
		if (!isset($this->LANGRID_LANGUAGE_ARRAY)) {
			require XOOPS_ROOT_PATH.'/modules/langrid_config/include/Languages.php';
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

	/*
	 * <#if locale="ja">
	 * LANGRID_SERVICES::language_pathsをサービスタイプごとにフォーマット
	 * <#/if>
	 */
	private function getLanguagesFromLanguagePaths($languagePaths, $serviceType) {
		if (!isset($this->LANGRID_LANGUAGE_ARRAY)) {
			require XOOPS_ROOT_PATH.'/modules/langrid_config/include/Languages.php';
			$this->LANGRID_LANGUAGE_ARRAY = $LANGRID_LANGUAGE_ARRAY;
		}
		$return = array();
		if ($serviceType == 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH') {
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
		} else if ($serviceType == 'TRANSLATION') {
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
		} else if ($serviceType == 'MORPHOLOGICALANALYSIS') {
			$used = array();
			foreach ($languagePaths as $languageCode) {
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
		return $return;
	}

	/*
	 * <#if locale="ja">
	 * データフォーマット
	 * <#/if>
	 */
	private function format($dto) {
		return array(
			'id' => $dto->getServiceId()
			, 'name' => $dto->getServiceName()
			, 'type' => $dto->getServiceType()
			, 'endpointUrl' => $dto->getEndpointUrl()
			, 'provider' => $dto->getOrganization()
			, 'copyright' => $dto->getCopyright()
			, 'license' => $dto->getLicense()
			, 'basicUserid' => $dto->getMiscBasicUserid()
			, 'basicPasswd' => $dto->getMiscBasicPasswd()
			, 'languagePaths' => $this->getLanguagesFromLanguagePaths(
					explode(',', $dto->getSupportedLanguagesPaths()), $dto->getServiceType())
			, 'languages' => $this->getLanguagesFromLanguagePaths(
					explode(',', $dto->getSupportedLanguagesPaths()), $dto->getServiceType())
			, 'registrationDate' => $this->_formatDateTime($dto->getUpdatedDate())
		);
	}

	/*
	 * <#if locale="ja">
	 * 日付をフォーマット
	 * <#/if>
	 */
	private function _formatDateTime($datetimeString) {
		if (($timestamp = strtotime($datetimeString)) === false) {
			return $datetimeString;
		} else {
			return date('Y-m-d H:i', $timestamp);
		}
	}

	/*
	 * <#if locale="ja">
	 * LANGRID_SERVICESテーブルをSERVICE_NAMEで検索
	 * <#/if>
	 */
	private function isExistsServiceName($serviceName) {
		$dist = false;
		$adapter = DaoAdapter::getAdapter();
		$db = $adapter->getDataBase();
		$sql = 'select count(*) as cnt from %s where `service_name` = \'%s\' and `allowed_app_provision` = \'%s\'';
		$result = $db->queryF(sprintf($sql, $db->prefix('langrid_services'), $serviceName, 'IMPORTED'));
		if ($result) {
			if ($row = $db->fetchArray($result)) {
				if ($row['cnt'] > 0) {
					$dist = true;
				}
			}
		}
		return $dist;
	}

	/*
	 * <#if locale="ja">
	 * LANGRID_SERVICESテーブルをENDPOINT_URLで検索
	 * <#/if>
	 */
	private function isExistsEndpointUrl($endpointUrl) {
		$dist = false;
		$adapter = DaoAdapter::getAdapter();
		$db = $adapter->getDataBase();
		$sql = 'select count(*) as cnt from %s where `endpoint_url` = \'%s\' and `allowed_app_provision` = \'%s\'';
		$result = $db->queryF(sprintf($sql, $db->prefix('langrid_services'), $endpointUrl, 'IMPORTED'));
		if ($result) {
			if ($row = $db->fetchArray($result)) {
				if ($row['cnt'] > 0) {
					$dist = true;
				}
			}
		}
		return $dist;
	}
}
?>