<?php
require_once(dirname(__FILE__).'/ExampleBasedTranslator.interface.php');
require_once(dirname(__FILE__).'/../translation/AtomicTranslation.class.php');
require_once(dirname(__FILE__).'/../common/util/SoapValueCreation.class.php');

class ExampleBasedTranslatorImpl extends AtomicTranslation implements ExampleBasedTranslator {

	/**
	 * <#if location="ja">
	 *
	 * </#if>
	 */
    function __construct($serviceId) {
    	parent::__construct($serviceId);
		$this->createClient($serviceId);
    }

	/**
	 * <#if location="ja">
	 *
	 * </#if>
	 */
    public function _translate() {
		//$endpoint = $this->_client->__getLocation();
		// TODO:context情報から、tokenを求める（langrid_config_ebmt_learning）
		// TODO:WSDLで指定してあるデフォルトのエンドポイントの取得方法を調査（__setLocationはあるが、__getLocationがない）
		$endpoint = 'http://langrid.nict.go.jp/service_manager/invoker/KyotoEBMT-nlparser_KNP_EDICT?token=1df746e368e793e9302e41e3c646d2d1';
		$this->_client->__setLocation($endpoint);
		return parent::_translate();
    }

	/**
	 * <#if location="ja">
	 * 新たな用例対訳集合を作成し、対応するトークンを返す。
	 * </#if>
	 */
	public function createToken($sourceLang, $targetLang) {
		$soapResponse = $this->_client->invokeService('createToken', array($sourceLang, $targetLang));
		return $soapResponse;
	}

	/**
	 * <#if location="ja">
	 * 指定したトークンで特定される用例対訳集合を破棄する。
	 * </#if>
	 */
	public function destroyToken($token) {
		return $this->_client->invokeService('destroyToken', array($token));
	}

	/**
	 * <#if location="ja">
	 * 指定したトークンで特定される用例対訳集合に、用例対訳を追加する。
	 * </#if>
	 */
	public function addParallelText($token, $sourceLang, $targetLang, $parallelTexts) {
		$soapResponse = $this->_client->invokeService('addParallelText', array(
			$token,
			$sourceLang,
			$targetLang,
			SoapValueCreation::createParallelTexts($parallelTexts)
		));
		return $soapResponse;
	}

	/**
	 * <#if location="ja">
	 * 指定したトークンに対応する用例対訳集合から、条件に一致する用例対訳を削除する。
	 * </#if>
	 */
	public function removeParallelText($token, $sourceLang, $targetLang, $headWord, $matchingMethod) {
		$soapResponse = $this->_client->invokeService('removeParallelText', array(
			$token,
			$sourceLang,
			$targetLang,
			$headWord,
			$matchingMethod
		));
		return $soapResponse;
	}

	/**
	 * <#if location="ja">
	 * 指定したトークンに対応する用例対訳集合から、条件に一致する用例対訳を検索する。
	 * </#if>
	 */
	public function searchParallelText($token, $sourceLang, $targetLang, $headWord, $matchingMethod) {
		$soapResponse = $this->_client->invokeService('searchParallelText', array(
			$token,
			$sourceLang,
			$targetLang,
			$headWord,
			$matchingMethod
		));
		return $soapResponse;
	}

	/**
	 * <#if location="ja">
	 * 指定したトークンに対応するよう例対訳集合の状態を返す。
	 * </#if>
	 */
	public function getStatus($token) {
		return $this->_client->invokeService('getStatus', array($token));
	}
}
?>