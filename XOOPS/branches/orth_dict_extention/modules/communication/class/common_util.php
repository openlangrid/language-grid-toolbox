<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

class CommonUtil {
	
	static public function toQueryString($hash) {
		$tmpAry = array();
		$keys = array_keys($hash);
		sort($keys);
		foreach($keys as $key) {
			array_push($tmpAry, "{$key}={$hash[$key]}");
		}
		return join("&", $tmpAry);
	}
	
	static public function underscore($str) {
		return strtolower(preg_replace('/([a-z])([A-Z])/', "$1_$2", $str));
	}
	
	static $languageMapCache;
	
	static public function getLanguageNameMap() {
		if(!self::$languageMapCache) {
			include XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
			self::$languageMapCache = $LANGRID_LANGUAGE_ARRAY;
		}
		return self::$languageMapCache;
	}
	
	static function toLanguageAsName($lang) {
		$map = self::getLanguageNameMap();
		return $map[$lang]; 
	}
	
	static function toLangugePair($lang) {
		$map = self::getLanguageNameMap();
		return array($lang, $map[$lang]);
	}
	
	static function toLangugePairs($langs) {
		$results = array();
		foreach($langs as $lang) $results[] = self::toLangugePair($lang);
		return $results;
	}

	/*
	 * Function to display formatted times in user timezone
	 */
	static function formatTimestamp($time, $format="l", $timeoffset="") {
	    global $xoopsConfig, $xoopsUser;
	    $usertimestamp = xoops_getUserTimestamp($time, $timeoffset);
	    return _formatTimeStamp($usertimestamp, $format);
	}
	
	static public function prefixedTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		$myModuleDir = realpath(dirname(__FILE__) . '/../');
		$myModuleName = basename($myModuleDir);
		return "{$xoopsDB->prefix}_{$myModuleName}_{$tableName}";
	}
}
?>