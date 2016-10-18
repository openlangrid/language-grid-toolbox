<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to set
// translation paths.
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

//require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_ObjectGenericHandler.class.php');

class LangridServiceXoopsObject extends XoopsSimpleObject {

	function LangridServiceXoopsObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('service_id', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('allowed_app_provision', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('endpoint_url', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('supported_languages_paths', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('organization', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('copyright', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('license', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('description', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('registered_date', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('updated_date', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('misc_basic_userid', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('misc_basic_passwd', XOBJ_DTYPE_STRING, '', false);
	}
}
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridLangridServicesDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridLangridService.class.php');

class LangridServicesDaoImpl extends XoopsObjectGenericHandler implements ServiceGridLangridServicesDAO {

	var $mTable = 'langrid_services';
	var $mPrimary = "id";
	var $mClass = "LangridServiceXoopsObject";

	function queryAll() {
		return "implements test!!";
	}

	/*
	 * サービスIDを指定して検索
	 * @param $serviceId
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByServiceId($serviceId, $allowedAppProvision, $serviceType = '') {
		$c = new CriteriaCompo();
		$c->add(new Criteria('service_id', $serviceId));
		//$c->add(new Criteria('delete_flag', '0'));
		if ($allowedAppProvision) {
			$c->add(new Criteria('allowed_app_provision', $allowedAppProvision));
		}
		if ($serviceType) {
			$c->add(new Criteria('service_type', $serviceType));
		}
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

	/*
	 * エンドポイントを指定して検索
	 * @param $endpoint
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 */
	function queryGetByEndPoint($endpoint, $allowedAppProvision, $serviceType = '') {
		$c = new CriteriaCompo();
		$c->add(new Criteria('endpoint_url', $endpoint));
		//$c->add(new Criteria('delete_flag', '0'));
		if ($allowedAppProvision) {
			$c->add(new Criteria('allowed_app_provision', $allowedAppProvision));
		}
		if ($serviceType) {
			$c->add(new Criteria('service_type', $serviceType));
		}
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

//	/*
//	 * サービスタイプを指定して検索
//	 * @param $serviceTypes
//	 * @return ServiceGridLangridService objects in array.
//	 */
//	function queryFindByServiceTypes($serviceTypes) {
//		$c = new CriteriaCompo();
//		if (is_array($serviceTypes)) {
//			$sc = new CriteriaCompo();
//			foreach ($serviceTypes as $v) {
//				$sc->add(new Criteria('service_type', $v), 'OR');
//			}
//			$c->add($sc);
//		} else {
//			$c->add(new Criteria('service_type', $serviceTypes));
//		}
//		//$c->add(new Criteria('delete_flag', '0'));
//		$objects = parent::getObjects($c);
//		return $this->objects2objects($objects);
//	}

	/*
	 * <#if locale="ja">
	 * サービスタイプと管理形態を指定して検索
	 * @param String $serviceType サービスタイプ
	 * @param String|Array $allowedAppProvisions 管理形態（CLIENT_CONTROL | SERVER_CONTROL | IMPORTED）
	 * @return ServiceGridLangridService objects in array.
	 * </#if>
	 */
	function queryFindServicesByTypeAndProvisions($serviceType, $allowedAppProvisions) {
		$c = new CriteriaCompo();
		if ($serviceType) {
			$c->add(new Criteria('service_type', $serviceType));
		}
		if (is_array($allowedAppProvisions)) {
			$sc = new CriteriaCompo();
			foreach ($allowedAppProvisions as $v) {
				$sc->add(new Criteria('allowed_app_provision', $v), 'OR');
			}
			$c->add($sc);
		} else {
			$c->add(new Criteria('allowed_app_provision', $allowedAppProvisions));
		}
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}


	/*
	 * (non-interface-method)
	 * サービスタイプを指定して物理削除
	 */
	public function deleteByServiceType($serviceType, $allowedAppProvision) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('service_type', $serviceType));
		$c->add(new Criteria('allowed_app_provision', $allowedAppProvision));
		return parent::deleteAll($c, true);
	}

	/*
	 * (non-interface-method)
	 * サービスIDを指定して物理削除
	 */
	public function deleteByServiceId($serviceId, $allowedAppProvision, $serviceType = '') {
		$c = new CriteriaCompo();
		$c->add(new Criteria('service_id', $serviceId));
		if ($allowedAppProvision) {
			$c->add(new Criteria('allowed_app_provision', $allowedAppProvision));
		}
		if ($serviceType) {
			$c->add(new Criteria('service_type', $serviceType));
		}
		return parent::deleteAll($c, true);
	}

	/*
	 * (non-interface-method)
	 * 新規登録
	 */
	public function insert($langridServiceObject) {
		$xObj = parent::create(true);
		$xObj->set('service_id', $langridServiceObject->getServiceId());
		$xObj->set('service_type', $langridServiceObject->getServiceType());
		$xObj->set('allowed_app_provision', $langridServiceObject->getAllowedAppProvision());
		$xObj->set('service_name', $this->sqlescape($langridServiceObject->getServiceName()));
		$xObj->set('endpoint_url', $this->sqlescape($langridServiceObject->getEndpointUrl()));
		$xObj->set('supported_languages_paths', $langridServiceObject->getSupportedLanguagesPaths());
		$xObj->set('organization', $this->sqlescape($langridServiceObject->getOrganization()));
		$xObj->set('copyright', $this->sqlescape($langridServiceObject->getCopyright()));
		$xObj->set('license', $this->sqlescape($langridServiceObject->getLicense()));
		$xObj->set('description', $this->sqlescape($langridServiceObject->getDescription()));
		$xObj->set('registered_date', $langridServiceObject->getRegisteredDate());
		$xObj->set('updated_date', $langridServiceObject->getUpdatedDate());
		$xObj->set('misc_basic_userid', $this->sqlescape($langridServiceObject->getMiscBasicUserid()));
		$xObj->set('misc_basic_passwd', $this->sqlescape($langridServiceObject->getMiscBasicPasswd()));
		if (parent::insert($xObj, true)) {
			return $this->convertLangridServiceXoopsObject($xObj);
		}
		throw new Exception($this->db->error());
//		return false;
	}

	/*
	 * (non-interface-method)
	 * 更新
	 */
	public function update($langridServiceObject) {
		$xObj = parent::get($langridServiceObject->getId());
		if (!$xObj) {
			throw new Exception('data is not found.');
		}
		$xObj->set('service_id', $langridServiceObject->getServiceId());
		$xObj->set('service_type', $langridServiceObject->getServiceType());
		$xObj->set('allowed_app_provision', $langridServiceObject->getAllowedAppProvision());
		$xObj->set('service_name', $this->sqlescape($langridServiceObject->getServiceName()));
		$xObj->set('endpoint_url', $this->sqlescape($langridServiceObject->getEndpointUrl()));
		$xObj->set('supported_languages_paths', $langridServiceObject->getSupportedLanguagesPaths());
		$xObj->set('organization', $this->sqlescape($langridServiceObject->getOrganization()));
		$xObj->set('copyright', $this->sqlescape($langridServiceObject->getCopyright()));
		$xObj->set('license', $this->sqlescape($langridServiceObject->getLicense()));
		$xObj->set('description', $this->sqlescape($langridServiceObject->getDescription()));
		$xObj->set('registered_date', $langridServiceObject->getRegisteredDate());
		$xObj->set('updated_date', $langridServiceObject->getUpdatedDate());
		$xObj->set('misc_basic_userid', $this->sqlescape($langridServiceObject->getMiscBasicUserid()));
		$xObj->set('misc_basic_passwd', $this->sqlescape($langridServiceObject->getMiscBasicPasswd()));
		if (parent::insert($xObj, true)) {
			return $this->convertLangridServiceXoopsObject($xObj);
		}
		throw new Exception($this->db->error());
//		return false;
	}

	/*
	 * XoopsのオブジェクトからVOに変換する。
	 */
	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertLangridServiceXoopsObject($object);
		}
		return $contents;
	}

	/*
	 * XoopsのオブジェクトからVOに変換する。
	 */
	private function convertLangridServiceXoopsObject($langridServiceObject) {
		$langridService = new ServiceGridLangridService();
		$langridService->setId($langridServiceObject->get('id'));
		$langridService->setServiceId($langridServiceObject->get('service_id'));
		$langridService->setServiceType($langridServiceObject->get('service_type'));
		$langridService->setAllowedAppProvision($langridServiceObject->get('allowed_app_provision'));
		$langridService->setServiceName($langridServiceObject->get('service_name'));
		$langridService->setEndpointUrl($langridServiceObject->get('endpoint_url'));
		$langridService->setSupportedLanguagesPaths($langridServiceObject->get('supported_languages_paths'));
		$langridService->setOrganization($langridServiceObject->get('organization'));
		$langridService->setCopyright($langridServiceObject->get('copyright'));
		$langridService->setLicense($langridServiceObject->get('license'));
		$langridService->setDescription($langridServiceObject->get('description'));
		$langridService->setRegisteredDate($langridServiceObject->get('registered_date'));
		$langridService->setUpdatedDate($langridServiceObject->get('updated_date'));
		$langridService->setMiscBasicUserid($langridServiceObject->get('misc_basic_userid'));
		$langridService->setMiscBasicPasswd($langridServiceObject->get('misc_basic_passwd'));
		return $langridService;
	}

	private function sqlescape($str) {
		return $str;
//		return mysql_real_escape_string($str);
	}
}
?>