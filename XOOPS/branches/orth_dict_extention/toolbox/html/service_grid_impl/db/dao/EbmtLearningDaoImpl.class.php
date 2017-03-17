<?php

/* $Id: EbmtLearningDaoImpl.class.php 4953 2010-12-22 12:26:56Z kitajima $ */

class EbmtLearningXoopsObject extends XoopsSimpleObject {

	function EbmtLearningXoopsObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('token', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('ebmt_service', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('user_dictionary_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('source_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('target_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('status', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('create_time', XOBJ_DTYPE_INT, 0, false);
	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridEbmtLearningDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridEbmtLearning.class.php');

class EbmtLearningDaoImpl extends XoopsObjectGenericHandler implements ServiceGridEbmtLearningDao{

	var $mTable = 'langrid_config_ebmt_learning';
	var $mPrimary = "id";
	var $mClass = "EbmtLearningXoopsObject";

	public function searchToken($searchCondition) {

	}

	public function queryForSearchByName($ebmtService, $userDictionaryName, $sourceLang, $targetLang) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('ebmt_service', $ebmtService));
		$c->add(new Criteria('user_dictionary_name', $userDictionaryName));
		$c->add(new Criteria('source_lang', $sourceLang));
		$c->add(new Criteria('target_lang', $targetLang));
		$objects = parent::getObjects($c);
		if ($objects != null && count($objects) > 0) {
			return $this->convertX2D($objects[0]);
		}
//		throw new Exception('Not found.');
		return false;
	}

	public function queryForSearch($ebmtService, $userDictionaryId, $sourceLang, $targetLang) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('ebmt_service', $ebmtService));
		$c->add(new Criteria('user_dictionary_id', $userDictionaryId));
		$c->add(new Criteria('source_lang', $sourceLang));
		$c->add(new Criteria('target_lang', $targetLang));
		$objects = parent::getObjects($c);
		if ($objects != null && count($objects) > 0) {
			return $this->convertX2D($objects[0]);
		}
//		throw new Exception('Not found.');
		return false;
	}

	// 学習予約されたリストを返す.
	public function queryFindByLearningTargets() {
		$c = new CriteriaCompo();
		$c->add(new Criteria('status', 'NEW'));
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

	public function insert($dto) {
		$xobj = parent::create(true);
		$xobj->set('token', $dto->getToken());
		$xobj->set('ebmt_service', $dto->getEbmtService());
		$xobj->set('user_dictionary_id', $dto->getUserDictionaryId());
		$xobj->set('user_dictionary_name', $dto->getUserDictionaryName());
		$xobj->set('source_lang', $dto->getSourceLang());
		$xobj->set('target_lang', $dto->getTargetLang());
		$xobj->set('status', $dto->getStatus());
		$xobj->set('create_time', time());
		if (parent::insert($xobj, true)) {
			return $this->convertX2D($xobj);
		}
		return false;
	}

	public function update($dto) {
		$xobj = parent::get($dto->getId());
		if (!$xobj) {
			throw new Exception('Not found.');
		}
		$xobj->set('token', $dto->getToken());
		$xobj->set('ebmt_service', $dto->getEbmtService());
		$xobj->set('user_dictionary_id', $dto->getUserDictionaryId());
		$xobj->set('user_dictionary_name', $dto->getUserDictionaryName());
		$xobj->set('source_lang', $dto->getSourceLang());
		$xobj->set('target_lang', $dto->getTargetLang());
		$xobj->set('status', $dto->getStatus());
		$xobj->set('create_time', time());
		if (parent::insert($xobj, true)) {
			return $this->convertX2D($xobj);
		}
		return false;
	}

	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertX2D($object);
		}
		return $contents;
	}

	private function convertX2D($xoopsObject) {
		$dto = new ServiceGridEbmtLearning();
		$dto->setId($xoopsObject->get('id'));
		$dto->setToken($xoopsObject->get('token'));
		$dto->setEbmtService($xoopsObject->get('ebmt_service'));
		$dto->setUserDictionaryId($xoopsObject->get('user_dictionary_id'));
		$dto->setUserDictionaryName($xoopsObject->get('user_dictionary_name'));
		$dto->setSourceLang($xoopsObject->get('source_lang'));
		$dto->setTargetLang($xoopsObject->get('target_lang'));
		$dto->setStatus($xoopsObject->get('status'));
		$dto->setCreateTime($xoopsObject->get('create_time'));
		return $dto;
	}
}
?>