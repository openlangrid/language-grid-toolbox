<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/TranslatorClientFactory.class.php';

class CTTranslatorClientFactory extends TranslatorClientFactory {

	public function &getCTInstance() {
		static $__singleton__;
		if (!isset($__singleton__)) {
			$__singleton__ = new CTTranslatorClientFactory();
		}
		return $__singleton__;
	}

	public function createClientWithSetId($sourceLang, $targetLang,$setId, $pathId = null) {
		
		$pathObj = $this -> getPathObject($sourceLang, $targetLang,$setId, $pathId);

		if (is_null($pathObj)) {
			// translation path not found. to NullTranslator created.
			require_once(dirname(__FILE__).'/NullTranslatorClient.class.php');
			$dist = new NullTranslatorClient($sourceLang, $targetLang);
			return $dist;
		}
		
		if (count($pathObj->getExecs()) == 1) {
			// 1-pass
			$execObj = $pathObj->getExecs();
//			if (count($execObj[0]->getBinds()) == 0) {
			if ($this->isNoBinding($pathObj->getExecs())) {
				// Atomic
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient($execObj[0]);
			} else {
				// Compsit
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient($execObj[0]);
			}
		} else {
			// N-pass
			if ($this->isNoBinding($pathObj->getExecs())) {
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient_MultiHop($pathObj->getExecs());
			} else {
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient_MultiHop($pathObj->getExecs());
			}
		}
		
		return $dist;
	}
	
	private function isNoBinding($execs) {
		foreach ($execs as $exec) {
			foreach ($exec->getBinds() as $bind) {
				if ($bind->get('bind_type') != 9) {
					return false;
				}
			}
		}
		return true;
	}
	
	protected function getPathObject($sourceLang, $targetLang,$setId, $pathId) {
		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
		if (!file_exists($file)) {die('TranslationServiceSetting file not found.');}
		require_once($file);

		$setting =& new TranslationServiceSetting();
		$translationPathAry =& $setting->getServiceSettings(getLoginUserUID(), $setId, $sourceLang, $targetLang);
		
		if ($translationPathAry == null || count($translationPathAry) == 0) {
			return null;
		}
		
		if(!is_null($pathId)) {
			foreach($translationPathAry as $path) {
				if($path -> mVars['path_id']['value'] == $pathId) {
					return $path;					
				}
			}
		}
		
		return $translationPathAry[0];
	}

}
