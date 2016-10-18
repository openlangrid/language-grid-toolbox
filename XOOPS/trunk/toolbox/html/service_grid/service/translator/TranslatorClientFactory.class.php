<?php

class TranslatorClientFactory {
	/**
	 * This is a Singleton.
	 */
	public function &getInstance() {
		static $_singleton_;
		if (!isset($_singleton_)) {
			$_singleton_ = new TranslatorClientFactory();
		}
		return $_singleton_;
	}
	public function createClient($sourceLang, $targetLang) {

	}

}
?>