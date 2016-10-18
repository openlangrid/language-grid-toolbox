<?php

define('SERVICE_GRID_USER_ID', 'SERVICE_GRID_ID');
define('SERVICE_GRID_PASSWORD', 'SERVICE_GRID_PASSWORD');
define('SERIVCE_GRID_SERVICE_CONTEXT_WSDL_PATH', 'wsdl/');
define('SERVICE_GRID_SERVICE_CONTEXT_RICH_WSDL_PATH', 'RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl');
define('SERVICE_GRID_SERVICE_CONTEXT_RICH_BACK_WSDL_PATH', 'RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_URL', '');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_PATH', 'invoker/');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_TRANSLATION_WITH_TEMPORAL_DICTIONARY', 'translationwithtemporaldictionary');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_BACKTRANSLATION_WITH_TEMPORAL_DICTIONARY', 'backtranslationwithtemporaldictionary');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_RICHTRANSLATION_WITH_TEMPORAL_DICTIONARY', 'richtranslationwithtemporaldictionary');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_RICHBACKTRANSLATION_WITH_TEMPORAL_DICTIONARY', 'richbacktranslationwithtemporaldictionary');
define('SERVICE_GRID_SERVICE_CONTEXT_URL', '');
define('SERVICE_GRID_DEBUG_LOG', false);
class ServiceGridConfig {
	const TRANSLATION_TYPE_RICH = 'rich';
	const TRANSLATION_TYPE_LITE = 'lite';
	const TRANSLATION_TYPE_NORMAL = 'normal';
	const TRANSLATION_TYPE_DUAL = 'dual';
	const SKIP_TAG_BEGIN="<skip_translation>";
	const SKIP_TAG_END="</skip_translation>";

	/**
	 * <#if lang="ja">
	 * SoapClientの初期化に渡すパラメータ
	 * </#if>
	 */
	public static function getSoapClientInitialParameters() {
		if (defined('SERVICE_GRID_USER_ID')) {
			$params = array(
				'login' => SERVICE_GRID_USER_ID,
				'password' => SERVICE_GRID_PASSWORD,
				'exceptions' => false,
				'trace'=>true,
				'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
			);
			if (defined('SERVICE_GRID_PROXY_HOST') && defined('SERVICE_GRID_PROXY_PORT')) {
				if (SERVICE_GRID_PROXY_HOST != '') {
					$params[ 'proxy_host' ] = SERVICE_GRID_PROXY_HOST;
				}
				if (SERVICE_GRID_PROXY_PORT != '') {
					$params[ 'proxy_port' ] = SERVICE_GRID_PROXY_PORT;
				}
			}
			return $params;
		} else {
			$proxyHost = $moduleConfig['proxy_host'];
			$proxyPort = $moduleConfig['proxy_port'];
			if ($proxyHost != '') {
				$params[ 'proxy_host' ] = $proxyHost;
			}
			if ($proxyPort != '') {
				$params[ 'proxy_port' ] = $proxyPort;
			}
			return $params;
		}
	}

	public static function getPbClientInitialParameters() {
	  if (defined('SERVICE_GRID_PROXY_HOST') && defined('SERVICE_GRID_PROXY_PORT')) {
			$params = array(
				'method' => 'POST',
				'user' => SERVICE_GRID_USER_ID,
				'pass' => SERVICE_GRID_PASSWORD,
				'proxy_host' => SERVICE_GRID_PROXY_HOST,
				'proxy_port' => SERVICE_GRID_PROXY_PORT
			);
			return $params;
		} else {
			$params = array(
				'method' => 'POST',
				'user' => SERVICE_GRID_USER_ID,
				'pass' => SERVICE_GRID_PASSWORD,
			);
			return $params;
		}
	}

	/**
	 * <#if lang="ja">
	 * 言語グリッドのコンテキストを設定する関数
	 * </#if>
	 */
	public static function getWsdlUrl($name) {
		if (empty($name)) {
			return $name;
		}
		if (strpos($name, 'http') === 0) {
			return $name;
		}

		$url = ServiceGridConfig::getServiceGridContextUrl();
		return $url . SERIVCE_GRID_SERVICE_CONTEXT_WSDL_PATH . $name;
	}

	/*
	 * (non-php-comment.)
	 * サービスグリッドのコンテキストURLを返す。
	 */
	public static function getServiceGridContextUrl() {
		return SERVICE_GRID_SERVICE_CONTEXT_URL;
	}
	public static function getRichBackWsdlUrl() {
		return SERVICE_GRID_SERVICE_CONTEXT_RICH_BACK_WSDL_PATH;
	}
	public static function getRichWsdlUrl() {
		return SERVICE_GRID_SERVICE_CONTEXT_RICH_WSDL_PATH;
	}
	public static function getPbUrl($name, $options = array()) {
		if (empty($name)) {
			return $name;
		}
		if (strpos($name, 'http') === 0) {
			return $name;
	  	}
  		if (isset($options['type']) && 
			($options['type'] == ServiceGridConfig::TRANSLATION_TYPE_RICH || $options['type'] == ServiceGridConfig::TRANSLATION_TYPE_DUAL)) {
	  		$name = 'rich'.$name;
	  	}
		switch ($name) {
		case SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_TRANSLATION_WITH_TEMPORAL_DICTIONARY:
			$name = "TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch";
			break;
		case SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_BACKTRANSLATION_WITH_TEMPORAL_DICTIONARY:
			$name = "BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch";
			break;
		case SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_RICHTRANSLATION_WITH_TEMPORAL_DICTIONARY:
			$name = "RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch";
			break;
		case SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_RICHBACKTRANSLATION_WITH_TEMPORAL_DICTIONARY:
			$name = "RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch";
			break;
		default:
			$name = "TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch";
		}
		if (defined('SERVICE_GRID_SERVICE_CONTEXT_PB_URL') && defined('SERVICE_GRID_SERVICE_CONTEXT_PB_PATH')) {
			return SERVICE_GRID_SERVICE_CONTEXT_PB_URL.SERVICE_GRID_SERVICE_CONTEXT_PB_PATH.$name;
		} else {
			die("Service grid service context URL has not been set.".__FILE__."::".__METHOD__."(".__LINE__.")");
		}
	}
	public static function isDebugLog() {
		return SERVICE_GRID_DEBUG_LOG;
	}
}
?>
