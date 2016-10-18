<?php
/* /html/modules/langrid/include/refreshLangridService.phpのリプレイス */
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
/* $Id: GlobalServicesLoader.class.php 5146 2011-01-25 13:24:19Z Masaaki Kamiya $ */

require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

require_once(XOOPS_ROOT_PATH.'/service_grid/client/msic/ServiceManagement.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');

/* $Id: GlobalServicesLoader.class.php 5146 2011-01-25 13:24:19Z Masaaki Kamiya $ */

class GlobalServicesLoader {

	private $mCachedServiceTypes = array(
		'TRANSLATION',
		'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH',
		'MORPHOLOGICALANALYSIS',
		'TEXTTOSPEECH',
		'SIMILARITYCALCULATION'
	);

	private $mAppProvisions = array(
		'CLIENT_CONTROL',
		'SERVER_CONTROL'
	);

	private $mServiceManagement = null;
	private $mLangridServiceDao = null;

    public function __construct() {
    	$this->mServiceManagement = new ServiceManagement();
		$this->mLangridServiceDao = DaoAdapter::getAdapter()->getLangridServicesDao();
    }

    public function refresh() {
		debugLog("refresh start.");

		try {
			foreach ($this->mCachedServiceTypes as $type) {
				foreach ($this->mAppProvisions as $provision) {
					if (!$this->mLangridServiceDao->deleteByServiceType($type, $provision)) {
						throw new GlobalServiceLoader_DeleteException($type);
					}
					$managerDist = $this->mServiceManagement->searchServices($type, $provision);
					if ($managerDist == null || $managerDist['status'] != 'OK') {
						throw new GlobalServiceLoader_SearchGlobalServiceException($type, $provision);
					}
					debugLog(count($managerDist['contents']).' '.$type. 'services of '.$provision.'.');
					foreach ($managerDist['contents'] as $aService) {
						$langridService = $this->createObject($aService);
						$langridService->setServiceType($type);
						$langridService->setAllowedAppProvision($provision);
						$this->mLangridServiceDao->insert($langridService);
					}
				}
			}
		} catch (Exception $e) {
			debugLog(print_r($e, true));
		}

		$this->addEbmt('CLIENT_CONTROL');
		$this->addEbmt('SERVER_CONTROL');

		debugLog('refresh end.');
    }

	private function addEbmt($provision) {
    	$obj = new ServiceGridLangridService();
		$obj->setServiceId('kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		$obj->setServiceType('TRANSLATION');
		$obj->setAllowedAppProvision($provision);
		$obj->setServiceName('KyotoEBMT (nlparser, KNP, EDICT)');
		$obj->setEndpointUrl('http://langrid.nict.go.jp/service_manager/wsdl/kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		$obj->setSupportedLanguagesPaths('en2ja,ja2en');
		$obj->setOrganization('Kurohashi Laboratory, Department of Intelligence Science and Technology, Graduate School of Informatics, Kyoto University');
		$obj->setCopyright('KyotoEBMT: Kyoto University, nlparser: Brown University, KNP: Kyoto University, EDICT: The Electronic Dictionary Research and Development Group');
		$obj->setLicense('');
		$obj->setDescription('KyotoEBMT is a example based machine translator. This resource consists of nlparser and KNP for a parser, and EDICT for a bilingual dictionary.');
		$obj->setRegisteredDate('2010/08/25');
		$obj->setUpdatedDate('2010/08/25');
		$res = $this->mLangridServiceDao->insert($obj);

		debugLog("### EBMT add	 ###");
		debugLog(print_r($res, 1));
	}

    private function createObject($aService) {
    	$obj = new ServiceGridLangridService();
		$obj->setServiceId($aService['serviceId']);
//		$obj->setServiceType('');
//		$obj->setAllowedAppProvision('');
		$obj->setServiceName($aService['name']);
		$obj->setEndpointUrl($aService['endpointUrl']);
		$obj->setSupportedLanguagesPaths(implode(',', $aService['path']));
		$obj->setOrganization($aService['organization']);
		$obj->setCopyright($aService['copyright']);
		$obj->setLicense($aService['license']);
		$obj->setDescription($aService['description']);
		$obj->setRegisteredDate($aService['registeredDate']);
		$obj->setUpdatedDate($aService['updatedDate']);
    	return $obj;
    }
}

class GlobalServiceLoader_DeleteException extends Exception {
	public function __construct($type) {
		parent::__construct("Failed to delete service type is " . $type);
	}
}
class GlobalServiceLoader_SearchGlobalServiceException extends Exception {
	public function __construct($type, $provision) {
		parent::__construct("Failed to search service type is " . $type . " allowedAppProvision is " . $provision);
	}
}
?>