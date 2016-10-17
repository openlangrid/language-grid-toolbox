<?php

require_once(MYEXTPATH.'/service_grid/dictionary/class/ImportPageDictionaryAdapter.class.php');

class LgDictionaryManager {

	public function __construct() {
	}

	public function getImportedDictionaries() {
		$a = new ImportPageDictionaryAdapter();
		return $a->load();
	}
}
?>
