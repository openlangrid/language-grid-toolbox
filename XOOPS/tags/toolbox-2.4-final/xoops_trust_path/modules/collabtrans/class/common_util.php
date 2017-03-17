<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

class CommonUtil {

	static public function toQueryString($hash, array $ignored = array()) {
		$tmpAry = array();
		$keys = array_keys($hash);
		sort($keys);
		foreach($keys as $key) {
			if (!in_array($key, $ignored)) {
				array_push($tmpAry, "{$key}={$hash[$key]}");
			}
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
		return $map[strval($lang)];
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

	static $achievementMapCache;

	static public function getAchievementMap() {
		if (!self::$achievementMapCache) {
			$map = array();

			$map['0'] = _MD_TASK_ACHIEVEMENT_0;
			for ($i = 10; $i <= 90; $i += 10) {
				$map["{$i}"] = $i . '%';
			}
			$map['100'] = _MD_TASK_ACHIEVEMENT_100;

			self::$achievementMapCache = $map;
		}
		return self::$achievementMapCache;
	}

	static $timeMapCache;

	static public function getTimeMapCache() {
		if (!self::$timeMapCache) {
			$map = array();
			for ($i = 0; $i <= 23; $i++) {
				$map[] = sprintf('%02d:00', $i);
			}
			self::$timeMapCache = $map;
		}
		return self::$timeMapCache;
	}

	static $symbolMapCache;

	static public function getSymbolMapCache() {
		if (!self::$symbolMapCache) {
			$map = array('>=', '=', '<=');
			self::$symbolMapCache = $map;
		}
		return self::$symbolMapCache;
	}

	static $searchMethodsCache;

	static public function getSearchMethods() {
		if (!self::$searchMethodsCache) {
			$map = array(_MD_TASK_PART_SEARCH, _MD_TASK_PREFIX_SEARCH, _MD_TASK_SUFFIX_SEARCH, _MD_TASK_EXACT_SEARCH);
			self::$searchMethodsCache = $map;
		}
		return self::$searchMethodsCache;
	}
}
?>
