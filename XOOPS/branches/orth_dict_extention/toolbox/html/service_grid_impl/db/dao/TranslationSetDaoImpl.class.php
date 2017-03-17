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
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
require_once (XOOPS_ROOT_PATH.'/core/XCube_Root.class.php');
class TranslationSetXoopsObject extends XoopsSimpleObject {

	function TranslationSetXoopsObject() {
		$this->initVar('set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('set_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('shared_flag', XOBJ_DTYPE_STRING, '0', false);

		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		$this->initVar('create_user_id', XOBJ_DTYPE_INT, $uid, false);
		$this->initVar('update_user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('create_time', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridTranslationSetDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationSet.class.php');
require_once(dirname(__FILE__).'/TranslationPathDaoImpl.class.php');
class TranslationSetDaoImpl extends XoopsObjectGenericHandler implements ServiceGridTranslationSetDAO {

	private $_translationPathDaoImpl;



	var $mTable = 'translation_set';
	var $mPrimary = "set_id";
	var $mClass = "TranslationSetXoopsObject";

	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->_translationPathDaoImpl = new TranslationPathDaoImpl($db);
	}

	/**
	 * User IDをキーにレコードを検索します。
	 * @param Integer $userId
	 */
	function queryByUserId($userId) {
		if (isset($userId)) {
			$uid = $userId;
		} else {
			$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		}
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('user_id', $uid), 'OR');
		$mc->add(new Criteria('shared_flag', '1'), 'OR');
		$object =& parent::getObjects($mc);
		return $this->convertSetObject($object);
	}

	function queryBySetId($setId) {
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('set_id', $setId), 'OR');
		$object =& parent::getObjects($mc);
		return array($this->convertSetObject($object[0]));
	}

	/**
	 * Set Nameをキーにレコードを取得します。
	 * @param $name
	 * @param $sourceLang
	 * @param $targetLang
	 * @param $userId
	 */
	function queryBySetName($name, $userId = null) {
		if ($userId == null) {
			$root =& XCube_Root::getSingleton();
			if ($root->mContext->mXoopsUser) {
				$uid = $root->mContext->mXoopsUser->get('uid');
			} else if (!empty($_SESSION['xoopsUserId'])) {
				$uid = $_SESSION['xoopsUserId'];
			} else {
				$uid = 1;
			}
		} else {
			$uid = $userId;
		}

		$mc = new CriteriaCompo();
		$mc->add(new Criteria('user_id', $uid));
		$mc->add(new Criteria('set_name', $name));
		$objects =& parent::getObjects($mc);
		if ($objects != null && count($objects) > 0) {
			$result = $this->convertSetObject($objects[0]);
//			$this->getTranslationPaths($result);
			return array($result);
		}
		if ($objects == null || count($objects) < 1) {
			debugLog('SITE Setting!');
			$uid = 1; //サイト共通
			$mc = new CriteriaCompo();
			$mc->add(new Criteria('user_id', $uid));
			$mc->add(new Criteria('set_name', $name));
			$objects =& parent::getObjects($mc);
			if ($objects != null && count($objects) > 0) {
				$result = $this->convertSetObject($objects[0]);
				return array($result);
			}
		}
		return null;
	}

	function getByNameWithDefualtSet($name) {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$obj = $this->queryBySetName($name, $uid);
		if ($obj == null && $uid != '1') {
			$obj = $this->queryBySetName($name, '1');
		}
		return $obj;
	}


	/**
	 * Setテーブルに紐付くPathレコードをロードします。
	 * @param unknown_type $translationSetObj ServiceGridTranslationSet
	 */
	public function getTranslationPaths($translationSetObj) {
		$translationPaths =
			$this->_translationPathDaoImpl->queryBySetId(
				$translationSetObj->getUserId()
				, $translationSetObj->getSetId());
		$translationSetObj->setTranslationPaths($translationPaths);
		return $translationPaths;
	}

	/**
	 * 新規レコード追加
	 * @param $setName
	 * @param $userId
	 * @param $setId [optional] - 省略した場合は、auto_increment
	 */
	public function insertNew($setName, $userId, $setId = null, $sharedFlag = null) {
		$obj = parent::create(true);
		$obj->set('set_name', $setName);
		$obj->set('user_id', $userId);
		if ($setId) {
			$obj->set('set_id', $setId);
		}
		if ($sharedFlag) {
			$obj->set('shared_flag', $sharedFlag);
		}
		$obj->set('create_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('create_time', time());

		if (parent::insert($obj, true)) {
			return $this->convertSetObject($obj);
		}
		return false;
	}

	public function findByBindingSetNameAndUserId($bindName, $userId) {
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('user_id', $userId));
		$mc->add(new Criteria('set_name', $bindName));
		$objects =& parent::getObjects($mc);
		if ($objects != null && count($objects) > 0) {
			$result = $this->convertSetObject($objects[0]);
			return array($result);
		}
		return false;
	}

	/**
	 * XoopsのオブジェクトからVOに変換する。
	 * @param unknown_type $translationSetObj
	 * @return ServiceGridTranslationSet
	 */
	private function convertSetObject($translationSetObj) {
		$translationSet = new ServiceGridTranslationSet();
		$translationSet->setSetId($translationSetObj->get('set_id'));
		$translationSet->setSetName($translationSetObj->get('set_name'));
		$translationSet->setUserId($translationSetObj->get('user_id'));
		$translationSet->setSharedFlag($translationSetObj->get('shared_flag'));
		$translationSet->setCreateUserId($translationSetObj->get('create_user_id'));
		$translationSet->setUpdateUserId($translationSetObj->get('update_user_id'));
		$translationSet->setCreateTime($translationSetObj->get('create_time'));
		$translationSet->setUpdateTime($translationSetObj->get('update_time'));
		return $translationSet;
	}
}
?>