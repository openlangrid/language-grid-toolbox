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

//require_once dirname(__FILE__).'/factory/xoops-factory.php';
require_once dirname(__FILE__).'/factory/xoops-factory.php';

/**
 * まだいろいろ思案中。。
 * @license LGPL
 * @author kitajima
 * @version 0.1a 
 */
class Toolbox {

	const PLATFORM = 'XOOPS';
//	const PLATFORM = 'MediaWiki';

	private static $factory;
	private static $users;
	public static $classes;

	/**
	 * Simulate import like Java.
	 * @param String $class
	 * @return void
	 */
	public static function import($class) {
		if (!isset(self::$classes)) {
			self::initClasses();
		}
		if (array_key_exists($class, self::$classes)) {
			require_once self::$classes[$class];
		}
	}

	/**
	 * @return Factory
	 */
	public static function factory() {
		if (!isset(self::$factory)) {
			switch (self::PLATFORM) {
			case 'XOOPS':
				self::$factory = XOOPSFactory::getInstance();
				break;
			case 'MediaWiki':
				self::$factory = MediaWikiFactory::getInstance();
				break;
			}
		}

		return self::$factory;
	}

	/**
	 *
	 * @return unknown_type
	 */
	public static function getDBInstance() {
		$factory = self::factory();
		return $factory->createDB();
	}

	/**
	 *
	 */
	public static function DB() {
		return self::getDBInstance();
	}

	/**
	 * @param int $timestamp Timestamp
	 * @return String Formted Date
	 */
	public static function date($timestamp = null) {
		if ($timestamp == null) {
			$timestamp = time();
		}

		return 'フォーマットされた日付';
	}

	public static function getCurrentUser() {
		$factory = self::factory();
		return $factory->getCurrentUser();
	}

	/**
	 * @return UserEntity[] Users
	 */
	public static function getAllUsers() {
		if (!isset(self::$users)) {
			$factory = self::factory();
			$daoFactory = $factory->createDAOFactory();
			$userDAO = $daoFactory->createUserDAO();
			self::$users = $userDAO->getAllUsers();
		}

		return self::$users;
	}

	/**
	 * @return void
	 */
	private static function initClasses() {
		$db = dirname(__FILE__).'/database';
		$exception = dirname(__FILE__).'/exception';
		$entity = dirname(__FILE__).'/entity';
		self::$classes = array(
			'toolbox.database.dao.GenericDAO' => $db.'/dao/generic-dao.php',
			'toolbox.exception.SQLException' => $exception.'/sql-exception.php',
			'toolbox.entity.LanguageEntity' => $entity.'/language-entity.php',
			'toolbox.entity.UserEntity' => $entity.'/user-entity.php'
		);
	}
}
?>