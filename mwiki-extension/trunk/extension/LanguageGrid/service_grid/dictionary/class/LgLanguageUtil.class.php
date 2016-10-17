<?php

class LgLanguageUtil {
	
	private function __construct() {}

	public static function getLanguageNameByCode($code) {
	}
	
	public static function languageSort($a, $b) {
		$a = getLangridLanguageName($a);
		$b = getLangridLanguageName($b);

		return strcasecmp($a, $b);
	}
}
?>
