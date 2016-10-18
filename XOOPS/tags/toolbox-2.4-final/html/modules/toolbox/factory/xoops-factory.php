<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
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
require_once dirname(__FILE__).'/abstract-factory.php';
require_once dirname(__FILE__).'/../database/db.php';
require_once dirname(__FILE__).'/../database/dao/factory/xoops-dao-factory.php';
require_once dirname(__FILE__).'/../entity/user-entity.php';

/**
 *
 * @author kitajima
 *
 */
class XOOPSFactory extends AbstractFactory {

	private static $db;
	private static $daoFactory;

	public static function getInstance() {
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className();
		}

		return self::$instance;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/toolbox/factory/AbstractFactory#getCurrentUser()
	 */
	public function getCurrentUser() {
		$root = XCube_Root::getSingleton();
		$userEntitiy = new UserEntity();
		$userEntitiy->setId($root->mContext->mXoopsUser->get('uid'));
		return $userEntitiy;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/toolbox/factory/AbstractFactory#createDAOFactory()
	 */
	public function createDAOFactory() {
		if (!isset(self::$daoFactory)) {
			self::$daoFactory = new XOOPSDAOFactory($this->createDB());
		}
		return self::$daoFactory;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/toolbox/factory/AbstractFactory#createDB()
	 */
	public function createDB() {
		if (!isset(self::$db)) {
			$dsn = XOOPS_DB_TYPE.':dbname='.XOOPS_DB_NAME.';host='.XOOPS_DB_HOST;
			$username = XOOPS_DB_USER;
			$password = XOOPS_DB_PASS;
			$prefix = XOOPS_DB_PREFIX.'_';
			self::$db = new DB($dsn, $username, $password);
			self::$db->setTablePrefix($prefix);
		}
		return self::$db;
	}
}
?>