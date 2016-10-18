<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../AbstractDao.class.php');

class LangridServiceObject extends AbstractDaoObject {

	function LangridServiceObject() {
		$this->initVar('service_id');
		$this->initVar('service_type');
		$this->initVar('service_name');
		$this->initVar('endpoint_url');
		$this->initVar('supported_languages_paths');
		$this->initVar('organization');
		$this->initVar('copyright');
		$this->initVar('license');
		$this->initVar('description');
		$this->initVar('registered_date');
		$this->initVar('updated_date');
		$this->initVar('create_date');
		$this->initVar('edit_date');
		$this->initVar('delete_flag');
	}
}

class LangridServicesDaoImpl extends AbstractDao implements ServiceGridLangridServicesDAO {

	var $mTable = 'langrid_services';
	var $mPrimary = "service_id";
	var $mClass = "LangridServiceObject";

	/**
	 * Get all records from table
	 */
	public function queryAll() {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	/**
	 * サービスIDを指定して検索
	 */
	public function queryByServiceId($serviceId) {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$wheres['service_id'] = $serviceId;
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	/**
	 * サービスタイプを指定して検索
	 */
	public function queryByServiceType($serviceType) {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$wheres['service_type'] = $serviceType;
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertLangridServiceObject($object);
		}
		return $contents;
	}
	/*
	 * エンドポイントを指定して検索
	 * @param $endpoint
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByEndPoint($endpoint, $serviceType = '') {
		return null;
	}

	/*
	 * サービスタイプを指定して検索
	 * @param $serviceType
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryFindByServiceTypes($serviceTypes) {
		return null;
	}
	
	 /** サービスIDを指定して検索
	 * @param $serviceId
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByServiceId($serviceId, $serviceType = '') {
		return null;
	}
	
	private function convertLangridServiceObject($langridServiceObject) {
		$langridService = new ServiceGridLangridService();
		$langridService->setServiceId($langridServiceObject->get('service_id'));
		$langridService->setServiceType($langridServiceObject->get('service_type'));
		$langridService->setServiceName($langridServiceObject->get('service_name'));
		$langridService->setEndpointUrl($langridServiceObject->get('endpoint_url'));
		$langridService->setSupportedLanguagesPaths($langridServiceObject->get('supported_languages_paths'));
		$langridService->setOrganization($langridServiceObject->get('organization'));
		$langridService->setCopyright($langridServiceObject->get('copyright'));
		$langridService->setLicense($langridServiceObject->get('license'));
		$langridService->setDescription($langridServiceObject->get('description'));
		$langridService->setRegisteredDate($langridServiceObject->get('registered_date'));
		$langridService->setUpdatedDate($langridServiceObject->get('updated_date'));
		$langridService->setCreateDate($langridServiceObject->get('create_date'));
		$langridService->setEditDate($langridServiceObject->get('edit_date'));
		$langridService->setDeleteFlag($langridServiceObject->get('delete_flag'));
		return $langridService;
	}

}
?>
