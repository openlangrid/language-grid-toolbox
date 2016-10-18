<?php
class DictionaryUrlList {
	private function __construct() {
	}

	public static function main() {
		global $wgTitle;
		return $wgTitle->getFullURL('action=edit&pagedict');
	}

	public static function selectLanguage() {
		global $wgTitle;
		return $wgTitle->getFullURL('action=edit&pagedict&selectlanguage');
	}

	public static function download() {
		global $wgTitle;
		return $wgTitle->getFullURL('action=edit&pagedict&download');
	}

	public static function upload() {
		global $wgTitle;
		return $wgTitle->getFullURL('action=edit&pagedict&upload');
	}
}

?>
