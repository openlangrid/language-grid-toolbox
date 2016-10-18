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

require_once dirname(__FILE__) . '/language-manager.php';

class LanguageManagerWithoutCookie extends LanguageManagerBase {

	private $supportedLanguages;
	private $mainLang;
	
	public function __construct($lang, $resourceName = null) {
		$this->supportedLanguages = $resourceName ? CommonUtil::getLanguageListFromResource($resourceName) :
			$this->getAllLanguages();
		$this->mainLang = $lang;
	}
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedLanguage() {
		if (!$this->isSupportedLanguage($this->mainLang)) {
			$langs = $this->getSupportedLanguages();
			$this->mainLang = $langs[0];
		}
		return $this->mainLang;
	}
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedLanguage($lang) {
		if ($this->isSupportedLanguage($lang)) {
			$this->mainLang = $lang;
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

class BilingualManagerWithoutCookie extends LanguageManagerWithoutCookie implements IBilingualManager {
	
	private $subLang;
	
	public function __construct($main, $sub, $resourceName = null) {
		parent::__construct($main, $resourceName);
		$this->subLang = $sub;
	}
	
	/**
	 * gets selected language tag.
	 * @return string
	 */
	public function getSelectedSubLanguage() {
		if (!$this->isSupportedLanguage($this->subLang)) {
			$langs = $this->getSupportedLanguages();
			$this->subLang = $langs[$langs[1] ? 1 : 0];
		}
		return $this->subLang;
	}
	
	/**
	 * sets selected language tag.
	 * @param string $lang
	 * @return null
	 */
	public function setSelectedSubLanguage($lang) {
		if ($this->isSupportedLanguage($lang)) {
			$this->subLang = $lang;
		}
	}
}

?>