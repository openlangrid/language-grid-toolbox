<?php
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

//define('SERVICE_GRID_SERVICE_CONTEXT_URL', 'http://langrid.nict.go.jp/langrid-1.2/');
//define('SERVICE_GRID_SERVICE_CONTEXT_URL', 'http://landev.nict.go.jp/langrid-2.0/');
define('SERIVCE_GRID_SERVICE_CONTEXT_WSDL_PATH', 'wsdl/');
define('SERVICE_GRID_SERVICE_CONTEXT_RICH_WSDL_PATH', 'RichTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl');
define('SERVICE_GRID_SERVICE_CONTEXT_RICH_BACK_WSDL_PATH', 'RichBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch?wsdl');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_URL', 'http://langrid.nict.go.jp/langrid-composite-service-1.2/');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_PATH', 'pbServices/');
define('SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_TRANSLATION_WITH_TEMPORAL_DICTIONARY', 'translationwithtemporaldictionary');
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
			$moduleConfig = ServiceGridConfig::getXoopsModuleConfig();
			$params = array(
				'login' => $moduleConfig['langrid_id'],
				'password' => $moduleConfig['langrid_pass'],
				'exceptions' => false,
				'trace'=>true,
				'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
			);
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
//		if (defined('SERVICE_GRID_SERVICE_CONTEXT_URL')) {
//			return SERVICE_GRID_SERVICE_CONTEXT_URL.SERIVCE_GRID_SERVICE_CONTEXT_WSDL_PATH.$name;
//		} else {
//			$moduleConfig = ServiceGridConfig::getXoopsModuleConfig();
////debugLog('CORE NODE:'.$moduleConfig['core_node_url']);
//			return $moduleConfig['core_node_url'].SERIVCE_GRID_SERVICE_CONTEXT_WSDL_PATH.$name;
////			die("Service grid service context URL has not been set.".__FILE__."::".__METHOD__."(".__LINE__.")");
//		}
	}

	/*
	 * (non-php-comment.)
	 * サービスグリッドのコンテキストURLを返す。
	 */
	public static function getServiceGridContextUrl() {
		if (defined('SERVICE_GRID_SERVICE_CONTEXT_URL')) {
			return SERVICE_GRID_SERVICE_CONTEXT_URL;
		} else {
			$moduleConfig = ServiceGridConfig::getXoopsModuleConfig();
			return $moduleConfig['core_node_url'];
		}
	}

    public static function getGridId() {
        $moduleConfig = ServiceGridConfig::getXoopsModuleConfig();
        return $moduleConfig['core_node_grid_id'];
    }

	public static function getRichBackWsdlUrl() {
        $gridId = ServiceGridConfig::getGridId();
		return $gridId . ':' . SERVICE_GRID_SERVICE_CONTEXT_RICH_BACK_WSDL_PATH;
	}
	public static function getRichWsdlUrl() {	
        $gridId = ServiceGridConfig::getGridId();
		return $gridId . ':' . SERVICE_GRID_SERVICE_CONTEXT_RICH_WSDL_PATH;
	}
	public static function getPbUrl($name) {
		if (empty($name)) {
			return $name;
		}
		if (strpos($name, 'http') === 0) {
			return $name;
	  	}
		switch ($name) {
		case SERVICE_GRID_SERVICE_CONTEXT_PB_SERVICE_NAME_TRANSLATION_WITH_TEMPORAL_DICTIONARY:
            $gridId = ServiceGridConfig::getGridId();
            $name = $gridId . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
			break;
		default:
			return $name;
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
		// load to langrid module config.
	public static function getXoopsModuleConfig() {
		$module_handler= & xoops_gethandler('module');
		$psModule = $module_handler->getByDirname('langrid');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0, $psModule->mid());

		if ($config == null) {
			die('Failed to retrieve config.['.__FILE__.'('.__LINE__.')]');
		}
		return $config;
	}
}
?>
