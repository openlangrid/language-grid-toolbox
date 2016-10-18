<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Playground. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

require_once dirname(__FILE__).'/../common_util.php';


class LanguageCookieManager {
	
	private $cookieId;
	private $value;
	private $isStored = false;
	
	/**
	 * constructor
	 * @param string $cookieId cookie id
	 * @return LanguageCookieManager
	 */
	public function __construct($cookieId) {
		$this->cookieId = $cookieId;
		if (isset($_COOKIE[$this->cookieId])) {
			$this->value = $_COOKIE[$this->cookieId];
			$this->isStored = true;
		}
	}
	
	/**
	 * gets value of cookie
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * sets value of cookie. this method does NOT set cookie. call storeCookie method after calling this method.
	 * @param string $value
	 * @return null 
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * store value in cookie
	 * @return unknown_type
	 */
	public function storeCookie() {
		$this->isStored = true;
		$_COOKIE[$this->cookieId] = $this->value;
		setcookie($this->cookieId, $this->value, time()+60*60*24*30, XOOPS_COOKIE_PATH . '/modules/' . $GLOBALS['mytrustdirname']);
	}
	
	/**
	 * returns true when cookie has value for cookie id.
	 * @return unknown_type
	 */
	public function isStored() {
		return $this->isStored;
	}
}

interface ILanguageManager {
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedLanguage();
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedLanguage($lang);
	
	/**
	 * gets supported languages in resource.
	 * @return unknown_type
	 */
	public function getSupportedLanguages();
	
	/**
	 * validates specified language tag could be used for resource.
	 * @param string $lang
	 * @return bool
	 */
	public function isSupportedLanguage($lang);
}

interface IBilingualManager extends ILanguageManager {
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedSubLanguage();
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedSubLanguage($lang);
}

abstract class LanguageManagerBase implements ILanguageManager {
	/**
	 * validates specified language tag could be used for resource.
	 * @param string $lang
	 * @return bool
	 */
	public function isSupportedLanguage($lang) {
		if (in_array($lang, $this->getSupportedLanguages())) {
			return true;
		}
		
		return false;
	}
	
	private static $allLanguages;
	/**
	 * gets all of supported languages for langrid.
	 * @return unknown_type
	 */
	protected static function getAllLanguages() {
		if (!isset(self::$allLanguages)) {
			$langKeys = array_keys(CommonUtil::getLanguageNameMap());
			self::$allLanguages = array();
			foreach ($langKeys as $key) {
				self::$allLanguages[] = $key;
			}
		}
		return self::$allLanguages;
	}
}

/**
 * manages current selected language in cookie. it will be used on QA manager.
 * @author kinoshita
 *
 */
class LanguageManager extends LanguageManagerBase {
	
	private $supportedLanguages;
	private $selectedLanguage;
	
	/**
	 * constructor 
	 * @param string $resourceName a resource name that displaied on store front or customize view. 
	 * @return LanguageManager
	 */
	public function __construct($resourceName = null) {
		$this->supportedLanguages = $resourceName ? CommonUtil::getLanguageListFromResource($resourceName) :
			$this->getAllLanguages();
		$this->selectedLanguage = new LanguageCookieManager("selectedLanguage");
	}
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedLanguage() {
		if (!$this->isSupportedLanguage($this->selectedLanguage->getValue())) {
			$langs = $this->getSupportedLanguages();
			$this->setSelectedLanguage($langs[0]);
		}
		return $this->selectedLanguage->getValue();
	}
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedLanguage($lang) {
		if ($this->isSupportedLanguage($lang)) {
			$this->selectedLanguage->setValue($lang);
			$this->selectedLanguage->storeCookie();
		}
	}
	
	/**
	 * gets supported languages in resource.
	 * @return unknown_type
	 */
	public function getSupportedLanguages() {
		return $this->supportedLanguages;
	}
}

class BilingualManager extends LanguageManager implements IBilingualManager {
	
	private $selectedSubLanguage;
	
	public function __construct($resourceName = null) {
		parent::__construct($resourceName);
		$this->selectedSubLanguage = new LanguageCookieManager("selectedSubLanguage");
	}
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedSubLanguage() {
		if (!$this->isSupportedLanguage($this->selectedSubLanguage->getValue())) {
			$langs = $this->getSupportedLanguages();
			$this->setSelectedSubLanguage($langs[$langs[1] ? 1 : 0]);
		}
		return $this->selectedSubLanguage->getValue();
	}
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedSubLanguage($lang) {
		if ($this->isSupportedLanguage($lang)) {
			$this->selectedSubLanguage->setValue($lang);
			$this->selectedSubLanguage->storeCookie();
		}
	}
}

?>