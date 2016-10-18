<?php

class LgTranslationManager {

	public function __construct() {
	}

	public function languageSort($a, $b) {
		$a = getLangridLanguageName($a);
		$b = getLangridLanguageName($b);

		return strcasecmp($a, $b);
	}
}
?>
