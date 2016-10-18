<?php

class LgUtil {

	private function __construct() {}

	public static function getRootPath() {
		global $wgServer, $wgScriptPath;
		return $wgServer.$wgScriptPath;
	}

	public static function getExtRootPath() {
		$wikiRootPath = self::getRootPath();
		return $wikiRootPath.'/extensions/LanguageGrid';
	}

	public static function getExtDictionaryRootPath() {
		$extRootPath = self::getExtRootPath();
		return $extRootPath.'/service_grid/dictionary';
	}
}
?>
