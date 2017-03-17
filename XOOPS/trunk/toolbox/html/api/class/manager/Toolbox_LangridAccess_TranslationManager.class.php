<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/Toolbox_LangridAccess_AbstractManager.class.php');

class Toolbox_LangridAccess_TranslationManager extends Toolbox_LangridAccess_AbstractManager {

	public function translate($sourceLang, $targetLang, $source, $bindingSetName) {
		$noSource = false;
		$preSource = "";
		if(!isset($source) || trim($source) == "" || $source == null){
			$preSource = $source;
			$source = ".";
			$noSource = true;
		}

		$pathObj = $this->_getPathObj($sourceLang, $targetLang, $bindingSetName);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}
		$last = null;
		$src = $source;
		foreach ($pathObj->getExecs() as $execObj) {
			$client = new BindingTranslatorClient($execObj);
			$last = $client->translate($src);
			if ($last != null && $last['status'] == 'OK') {
				$src = $last['contents']['targetText']['contents'];
			} else {
				return $this->getErrorResponsePayload(isset($last['message']) ? $last['message'] : 'server error.');
			}
		}

		$vo = new ToolboxVO_LangridAccess_TranslationResult();
		$vo->result = $last['contents']['targetText']['contents'];
		if($noSource){$vo->result = $preSource;}
		$vo->multihopTranslationBinding = $this->TranslationPath2ResponseVO($pathObj);

		$licenseAry = array();
		foreach ($last['licenseInformation'] as $license) {
			$licenseAry[] = $this->LicenseObject2ResponseVO($license);
		}
		$vo->translationInvocationInfo = $licenseAry;

		return $this->getResponsePayload(array($vo));
	}

	public function backTranslate($sourceLang, $intermediatetLang, $source, $bindingSetName) {
		$noSource = false;
		$preSource = "";
		if(!isset($source) || trim($source) == "" || $source == null){
			$preSource = $source;
			$source = ".";
			$noSource = true;
		}

		$pathObj = $this->_getPathObj($sourceLang, $intermediatetLang, $bindingSetName);
		if ($pathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.');
		}
		$backPathObj = $this->_getPathObj($intermediatetLang, $sourceLang, $bindingSetName);
		if ($backPathObj == null) {
			return $this->getErrorResponsePayload('Translation path is not found.(for back.)');
		}

		$pathVo = $this->TranslationPath2ResponseVO($pathObj);
		$backPathVo = $this->TranslationPath2ResponseVO($backPathObj);

		$factory = new BackTranslationClientFactory();
		$translator = $factory->createClient($pathObj, $backPathObj);

		$translateResult = $translator->translate($source);

		if ($translateResult['status'] != 'OK') {
			return $this->getErrorResponsePayload($translateResult['message']);
		} else {
			$dist = (array)$translateResult['contents']['targetText']['contents'];
			$vo = new ToolboxVO_LangridAccess_BackTranslationResult();
			$vo->intermediateResult = $dist['intermediate'];
			$vo->targetResult = $dist['target'];
			if($noSource){
				$vo->intermediateResult = $preSource;
				$vo->targetResult = $preSource;
			}
			$vo->multihopTranslationBinding = array($pathVo, $backPathVo);

			$licenseAry = array();
			foreach ($translateResult['licenseInformation'] as $id => $license) {
				$licenseAry[$id] = $this->LicenseObject2ResponseVO($license);
			}
			$vo->translationInvocationInfo = $licenseAry;
			$vo->serviceclient = $translator->getServiceId();
			return $this->getResponsePayload(array($vo));
		}
	}

	protected function LicenseObject2ResponseVO($license) {
		$vo = new ToolboxVO_LangridAccess_InvocationInfo();
		$vo->serviceName = $license['serviceName'];
		$vo->copyright = $license['serviceCopyright'];
		$vo->license = $license['serviceLicense'];
		$vo->errorMessage = '';

		return $vo;
	}

	private function _loadServiceClass() {
		$file = XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingTranslatorClient.class.php';
		if (!file_exists($file)) {
			die('BindingTranslatorClient file not found.');
		}
		require_once($file);
	}

	private function _getPathObj($sourceLang, $targetLang, $bindingSetName) {
		$this->_loadServiceClass();
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$setObj = $this->getBindingSetObjectByName($bindingSetName);
		if ($setObj == null) {
			die('BindingSet is not found.');
		}
		$objects =& $this->ServiceSetting->getServiceSettings($setObj->get('user_id'), $setObj->get('set_id'), $sourceLang, $targetLang);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0];
	}
}

/**
 * <#if lang="ja">
 * 最適な折返しサービスを選択するファクトリクラス
 * </#if>
 */
class BackTranslationClientFactory {
	function BackTranslationClientFactory() {
	}

	/**
	 * <#if lang="ja">
	 * 折返し翻訳クライアントクラスを生成する。
	 * @param $forwardPathObject 順方向の翻訳パス
	 * @param $backPathObject    逆方向の翻訳パス
	 * </#if>
	 */
	function createClient($forwardPathObject, $backPathObject) {
		$forwardExecs = $forwardPathObject->getExecs();
		$backExecs = $backPathObject->getExecs();

		$translator = null;

		if (count($forwardExecs) == 1 && count($backExecs) == 1) {
			// 往復双方とも1pass
			switch ($this->_checkSameDictionaries($forwardExecs[0], $backExecs[0])) {
				case "1":	// new
					require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingBackTranslatorClient.class.php');
					$translator = new BindingBackTranslatorClient($forwardExecs[0], $backExecs[0]);
					break;
				case "0":	// SERVICETYPE:BACKTRANSLATION
					require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BackTranslatorClient.class.php');
					$translator = new BackTranslatorClient($forwardExecs[0], $backExecs[0]);
					break;
				default:
					require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/CycleBackTranslator.class.php');
					$translator = new CycleBackTranslator($forwardPathObject, $backPathObject);
					break;
			}
		} else {
			require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/CycleBackTranslator.class.php');
			$translator = new CycleBackTranslator($forwardPathObject, $backPathObject);
		}

		return $translator;
	}

	/**
	 * @return 1:同じ辞書 -1:違う辞書 0:辞書なし
	 */
	private function _checkSameDictionaries($fexec, $bexec) {
		$fbinddicts = array();
		$bbinddicts = array();
		foreach ($fexec->getBinds() as $bind) {
			if ($bind->get('bind_type') != '9') {
				$fbinddicts[] = $bind->get('bind_value');
			}
		}
		foreach ($bexec->getBinds() as $bind) {
			if ($bind->get('bind_type') != '9') {
				$bbinddicts[] = $bind->get('bind_value');
			}
		}
		if (count($fbinddicts) != count($bbinddicts)) {
			return -1;
		}
		if (count($fbinddicts) == 0 && count($bbinddicts) == 0) {
			return 0;
		}
		sort($fbinddicts);
		sort($bbinddicts);
		$diff = array_diff($fbinddicts, $bbinddicts);
		if (count($diff) == 0) {
			return 1;
		} else {
			return -1;
		}
	}
}
?>