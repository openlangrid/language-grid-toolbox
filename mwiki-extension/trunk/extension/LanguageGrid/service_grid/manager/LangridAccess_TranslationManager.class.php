<?php

require_once(dirname(__FILE__).'/LangridAccess_AbstractManager.class.php');
/**
 * <#if locale="en">
 * Translation invocation manager class
 * <#elseif locale="ja">
 * 翻訳実行マネージャクラス
 * </#if>
 */
class LangridAccess_TranslationManager extends LangridAccess_AbstractManager {

	public function translate($sourceLang, $targetLang, $source, $setId, $dictId) {

		$pathObj = $this->_getPathObj($sourceLang, $targetLang, $setId);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found. set_id = '.$setId);
		}
		$last = null;
		$execObjs =& $pathObj->getExecs();
		$client = null;
		if (count($execObjs) == 1) {
			$client = new BindingTranslatorClient($execObjs[0], $dictId);
		} else {
			$client = new BindingTranslatorClient_MultiHop($execObjs, $dictId);
		}

		if ($client != null) {
			$last = $client->translate($source);
		} else {
			return $this->getErrorResponsePayload('Translation client is not found.');
		}

		if ($last == null || isset($last['status']) === false) {
			return $this->getErrorResponsePayload('Translation result is empty.');
		}
		if ($last['status'] != 'OK') {
			return $this->getErrorResponsePayload($last['message']);
		}

		$vo = new VO_LangridAccess_TranslationResult();
		$vo->result = $last['contents']['targetText']['contents'];

		$vo->multihopTranslationBinding = $this->TranslationPath2ResponseVO($pathObj);

		$licenseAry = array();
		foreach ($last['licenseInformation'] as $license) {
			$licenseAry[] = $this->LicenseObject2ResponseVO($license);
		}
		$vo->translationInvocationInfo = $licenseAry;
		$vo->set_id = $setId;
		$vo->page_dict_id = $dictId;

		return $this->getResponsePayload($vo);
	}

	public function backTranslate($sourceLang, $intermediatetLang, $source, $setId, $dictId) {
		$pathObj = $this->_getPathObj($sourceLang, $intermediatetLang, $setId);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}
		$backPathObj = $this->_getPathObj($intermediatetLang, $sourceLang, $setId);
		if ($backPathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.(for back.)');
		}

		$pathVo = $this->TranslationPath2ResponseVO($pathObj);
		$backPathVo = $this->TranslationPath2ResponseVO($backPathObj);

		$last = null;

		$execObjs = $pathObj->getExecs();
		$client = null;
		if (count($execObjs) == 1) {
			$client = new BindingTranslatorClient($execObjs[0], $dictId);
		} else {
			$client = new BindingTranslatorClient_MultiHop($execObjs, $dictId);
		}

		if ($client != null) {
			$last = $client->translate($source);
		} else {
			return $this->getErrorResponsePayload('Translation client is not found.');
		}

		if ($last == null || isset($last['status']) === false) {
			return $this->getErrorResponsePayload('Translation result is empty.');
		}
		if ($last['status'] != 'OK') {
			return $this->getErrorResponsePayload($last['message']);
		}

		$nextSource = $last['contents']['targetText']['contents'];

		$backLast = null;

		$execObjs = $backPathObj->getExecs();
		if (count($execObjs) == 1) {
			$client = new BindingTranslatorClient($execObjs[0], $dictId);
		} else {
			$client = new BindingTranslatorClient_MultiHop($execObjs, $dictId);
		}

		if ($client != null) {
			$backLast = $client->translate($nextSource);
		} else {
			return $this->getErrorResponsePayload('Back-Translation client is not found.');
		}

		if ($backLast == null || isset($backLast['status']) === false) {
			return $this->getErrorResponsePayload('Translation result is empty.');
		}
		if ($backLast['status'] != 'OK') {
			return $this->getErrorResponsePayload($backLast['message']);
		}


		$l = array_merge($last['licenseInformation'], $backLast['licenseInformation']);
		$licenseAry = array();
		foreach ($l as $license) {
			$licenseAry[] = $this->LicenseObject2ResponseVO($license);
		}

		$vo = new VO_LangridAccess_BackTranslationResult();

		$vo->intermediateResult = $nextSource;
		$vo->targetResult = $backLast['contents']['targetText']['contents'];
		$vo->multihopTranslationBinding = array($pathVo, $backPathVo);
		$vo->translationInvocationInfo = $licenseAry;
		$vo->set_id = $setId;
		$vo->page_dict_id = $dictId;

		return $this->getResponsePayload($vo);
	}

	protected function LicenseObject2ResponseVO($license) {
		$vo = new VO_LangridAccess_InvocationInfo();
		$vo->serviceName = $license['serviceName'];
		$vo->copyright = $license['serviceCopyright'];
		$vo->license = $license['serviceLicense'];
		$vo->errorMessage = '';

		return $vo;
	}

	private function _loadServiceClass() {
		global $IP;
		$file = dirname(__FILE__).'/../service/translator/BindingTranslatorClient.class.php';
		if (!file_exists($file)) {
			die('BindingTranslatorClient file not found.');
		}
		require_once($file);
	}

	private function _getPathObj($sourceLang, $targetLang, $articleId) {
		$this->_loadServiceClass();
		$objects =& $this->ServiceSetting->getServiceSettings($this->uid, $articleId, $sourceLang, $targetLang);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0];
	}
}
?>