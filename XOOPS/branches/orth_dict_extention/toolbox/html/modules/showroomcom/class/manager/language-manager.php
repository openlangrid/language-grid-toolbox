<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once XOOPS_ROOT_PATH.'/modules/langrid/class/get-supported-language-pair-class.php';

class LanguageManager {

	private $selectedLanguage;
	private $toLanguages = array();
	private $allLanguages = array();
	private $allLanguagePair;

	public function __construct() {
		$languagePair = new GetSupportedLanguagePair();
		$this->allLanguagePair = $languagePair->getLanguagePair();

		$this->allLanguages = array();
		foreach ($this->allLanguagePair as $value) {
			$this->allLanguages[] = $value[0];
		}
		$this->allLanguages = array_unique($this->allLanguages);

		if (isset($_COOKIE["selectedLanguage"])
			&& $this->isSupportedLanguageTag($_COOKIE["selectedLanguage"])
		) {
			$this->selectedLanguage = $_COOKIE["selectedLanguage"];
		} else {
			if (array_search('en', $this->sourceLanguageTag) !== false) {
				$this->selectedLanguage = 'en';
			} else {
				$this->selectedLanguage = $this->allLanguages[0];
			}
		}

		if (isset($_GET["lang"]) && $this->isSupportedLanguageTag($_GET["lang"])) {
//			foreach ($this->allLanguagePair as $pair) {
//				if (@array_search($_GET["lang"], $pair) !== false) {
			$this->selectedLanguage = $_GET["lang"];
//					break;
//				}
//			}
		}
		setcookie('selectedLanguage', '', time()-60*60, '');
		setcookie('selectedLanguage', '', time()-60*60, '/');

		setcookie('selectedLanguage', $this->selectedLanguage, time()+60*60*24*30, XOOPS_COOKIE_PATH);

		foreach ($this->allLanguagePair as $value) {
			if ($value[0] == $this->selectedLanguage) {
				$this->toLanguages[] = $value[1];
			}
//			if (array_search($value[0], $this->allLanguages) === false) {
//				$this->allLanguages[] = $value[0];
//			}
		}
//		var_dump('-----------start-------------');
//		var_dump($this->allLanguagePair);
//		var_dump($this->allLanguages);
//		var_dump($this->toLanguages);
//		var_dump('------------end------------');
	}
	public function getSelectedLanguage() {
		return $this->selectedLanguage;
	}
	public function getToLanguages() {
		return $this->toLanguages;
	}
	public function getToLanguagesBySourceLanguageTag($sourceLanguageTag) {
		$toLanguages = array();
		foreach ($this->allLanguagePair as $value) {
			if ($value[0] == $sourceLanguageTag) {
				$toLanguages[] = $value[1];
			}
//			if (array_search($value[0], $this->allLanguages) === false) {
//				$this->allLanguages[] = $value[0];
//			}
		}
		return $toLanguages;
	}
	public function getAllLanguages() {
		return $this->allLanguages;
	}
	public function getAllLanguagePair() {
		return $this->allLanguagePair;
	}
	public function isSupportedLanguageTag($tag) {
		return (array_search($tag, $this->allLanguages) !== false);
	}
	public function isLanguageTag($tag) {
		return ($this->getNameByTag($tag) != $tag);
	}
	public function getNameByTag($tag) {
		$languageTagArray = array(
			'sq' => 'Albanian',
			'ar' => 'Arabic',
			'bg' => 'Bulgarian',
			'ca' => 'Catalan',
			'zh-CN' => 'Chinese(CN)',
			'hr' => 'Croatian',
			'cs' => 'Czech',
			'da' => 'Danish',
			'nl' => 'Dutch',
			'en' => 'English',
			'et' => 'Estonian',
			'fi' => 'Finnish',
			'fr' => 'French',
			'gl' => 'Galician',
			'de' => 'German',
			'el' => 'Greek',
			'iw' => 'Hebrew',
			'hi' => 'Hindi',
			'hu' => 'Hungarian',
			'id' => 'Indonesian',
			'it' => 'Italian',
			'ja' => 'Japanese',
			'ko' => 'Korean',
			'lv' => 'Latvian',
			'lt' => 'Lithuanian',
			'mt' => 'Maltese',
			'no' => 'Norwegian',
			'pl' => 'Polish',
			'pt' => 'Portuguese',
			'ro' => 'Romanian',
			'ru' => 'Russian',
			'sr' => 'Serbian',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'es' => 'Spanish',
			'sv' => 'Swedish',
			'th' => 'Thai',
			'tr' => 'Turkish',
			'uk' => 'Ukrainian',
			'vi' => 'Vietnamese',
			'zh' => 'Chinese',
			'zh-TW' => 'Chinese(TW)',
			'tl' => 'Tagalog'
		);
		if (isset($languageTagArray[$tag])) {
			return $languageTagArray[$tag];
		} else {
			return $tag;
		}
	}
}
?>