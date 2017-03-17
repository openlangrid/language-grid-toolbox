<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid. This allows users to set
// translation paths.
// Copyright (C) 2009-2010  NICT Language Grid Project
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
abstract class DaoAdapter {
	// アプリケーション名
	const APP_NAME = 'Xoops';
//	const APP_NAME = 'MediaWiki';

	// クラス名
//	protected $serviceGridDefaultDictionaryDaoImpl = 'DefaultDictionaryDaoImpl';
	protected $serviceGridDefaultDictionaryBindDaoImpl = 'DefaultDictionaryBindDaoImpl';
	protected $serviceGridDefaultDictionarySettingDaoImpl = 'DefaultDictionarySettingDaoImpl';
	protected $serviceGridImportDictionaryDaoImpl = 'ImportDictionaryDaoImpl';
	protected $serviceGridLangridServicesDaoImpl = 'LangridServicesDaoImpl';
	protected $serviceGridLangridServicesConfigDaoImpl = 'LangridServicesConfigDaoImpl';
	protected $serviceGridTranslationBindDoaImpl = 'TranslationBindDaoImpl';
	protected $serviceGridTranslationExecDaoImpl = 'TranslationExecDaoImpl';
	protected $serviceGridTranslationPathDaoImpl = 'TranslationPathDaoImpl';
	protected $serviceGridTranslationSetDaoImpl = 'TranslationSetDaoImpl';
	protected $serviceGridUserDictionaryDaoImpl = 'UserDictionaryDaoImpl';
	protected $serviceGridUserDictionaryContentsDaoImpl = 'UserDictionaryContentsDaoImpl';
	protected $serviceGridLogDaoImpl = 'ServiceGridLogDaoImpl';
	protected $serviceGridEbmtLearningDaoImpl = 'EbmtLearningDaoImpl';
	// DAO実装クラスへのパス
	protected $serviceGridDaoPath = '../../dao/';

	public static function getAdapter() {
		if (strcmp(self::APP_NAME, 'Xoops') == 0) {
			return new ToolboxAdapter();
		} else if (strcmp(self::APP_NAME, 'MediaWiki') == 0) {
			return new MediaWikiAdapter();
		} else {
			return null;
		}
	}

	// DAOインターフェースを実装したクラスを返す。

	public function getDefaultDictionaryBindDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridDefaultDictionaryBindDaoImpl.'.class.php');
		return new $this->serviceGridDefaultDictionaryBindDaoImpl($this->getDataBase());
	}

	public function getDefaultDictionarySettingDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridDefaultDictionarySettingDaoImpl.'.class.php');
		return new $this->serviceGridDefaultDictionarySettingDaoImpl($this->getDataBase());
	}

	public function getImportDictionaryDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridImportDictionaryDaoImpl.'.class.php');
		return new $this->serviceGridImportDictionaryDaoImpl($this->getDataBase());
	}

	public function getLangridServicesDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridLangridServicesDaoImpl.'.class.php');
		return new $this->serviceGridLangridServicesDaoImpl($this->getDataBase());
	}

	public function getLangridServicesConfigDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridLangridServicesConfigDaoImpl.'.class.php');
		return new $this->serviceGridLangridServicesConfigDaoImpl($this->getDataBase());
	}

	public function getTranslationBindDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridTranslationBindDoaImpl.'.class.php');
		return new $this->serviceGridTranslationBindDoaImpl($this->getDataBase());
	}

	public function getTranslationExecDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridTranslationExecDaoImpl.'.class.php');
		return new $this->serviceGridTranslationExecDaoImpl($this->getDataBase());
	}

	public function getTranslationPathDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridTranslationPathDaoImpl.'.class.php');
		return new $this->serviceGridTranslationPathDaoImpl($this->getDataBase());
	}

	public function getTranslationSetDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridTranslationSetDaoImpl.'.class.php');
		return new $this->serviceGridTranslationSetDaoImpl($this->getDataBase());
	}
	public function getUserDictionaryDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridUserDictionaryDaoImpl.'.class.php');
		return new $this->serviceGridUserDictionaryDaoImpl($this->getDataBase());
	}
	public function getUserDictionaryContentsDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridUserDictionaryContentsDaoImpl.'.class.php');
		return new $this->serviceGridUserDictionaryContentsDaoImpl($this->getDataBase());
	}
	public function getServiceGridLogDao() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridLogDaoImpl.'.class.php');
		return new $this->serviceGridLogDaoImpl($this->getDataBase());
	}
	public function getServiceGridEbmtLearningDaoImpl() {
		require_once(dirname(__FILE__).$this->serviceGridDaoPath.$this->serviceGridEbmtLearningDaoImpl.'.class.php');
		return new $this->serviceGridEbmtLearningDaoImpl($this->getDataBase());
	}

	/**
	 * Database参照を返す。
	 */
	abstract function getDataBase();

	abstract function getUserId();
}

/**
 * Adapter class for Toolbox.
 * @author jun koyama
 *
 */
class ToolboxAdapter extends DaoAdapter {
	protected $serviceGridDaoPath = '/../../../service_grid_impl/db/dao/';
	function getDataBase() {
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		return $db;
	}

	function getUserId() {
		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		return $uid;
	}
}

/**
 * Adapter class for MediaWiki
 * @author jun koyama
 *
 */
class MediaWikiAdapter extends DaoAdapter {
	protected $serviceGridDaoPath = '/../../../service_grid_impl/db/dao/';
	function getDataBase() {
		return wfGetDB(DB_MASTER);
	}

	function getUserId() {
		return '0';
	}
}
?>
