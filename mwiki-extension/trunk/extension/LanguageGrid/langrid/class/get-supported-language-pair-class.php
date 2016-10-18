<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
class GetSupportedLanguagePair {
	//private $TranslationPathSettingClass = null;

	private $serviceSetting = null;

	function __construct() {
		//$this->TranslationPathSettingClass =& new PathSettingWapperClass();
		$this->serviceSetting =& new TranslationServiceSetting();
	}

 	function getLanguagePair() {
 		$setObj =& $this->serviceSetting->getSetIdByRequestModule();
 		if ($setObj == null) {
 			die('translation service set is not found. '.__FILE__);
 		}
 		$objects =& $this->serviceSetting->getServiceSettings($setObj->get('user_id'), $setObj->get('set_id'));
 		if ($objects == null || count($objects) == 0) {
 			return array();
 		}

		$return = array();
		foreach ($objects as $path) {
			$return[] = array($path->get('source_lang'), $path->get('target_lang'));
		}
 		return $return;
 	}

 	function getLanguageNamePair() {
 		$ret = array();
 		$pairs = $this->getLanguagePair();
		foreach ($pairs as $pair) {
			$ret[] = array(array('code' => $pair[0], 'name' => getLanguageName($pair[0]))
					,	array('code' => $pair[1], 'name' => getLanguageName($pair[1])));
		}
		return $ret;
 	}
}
?>
