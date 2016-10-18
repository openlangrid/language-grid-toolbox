<?php
//  ------------------------------------------------------------------------ //
// This is a Language Grid Toolbox module. This module extends
// the BBS module for real-time discussions.
// Copyright (C) 2010 Graduate School of Informatics, Kyoto University.
// All Rights Reserved.
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

class LanguageManager {

	private $selectedLanguage;
	private $languageTags;

	public function __construct() {
		if (isset($_COOKIE["selectedLanguage"])
				&& $this->isSupportedLanguageTag($_COOKIE["selectedLanguage"])) {

			$this->selectedLanguage = $_COOKIE["selectedLanguage"];
		} else {
			$this->selectedLanguage = 'en';
		}
	}

	public function isSupportedLanguageTag($tag) {
		if (!$this->languageTags) {
			$this->languageTags = array_keys(CommonUtil::getLanguageNameMap());
		}
		return in_array($tag, $this->languageTags);
	}

	public function getSelectedLanguage() {
		return $this->selectedLanguage;
	}
}
