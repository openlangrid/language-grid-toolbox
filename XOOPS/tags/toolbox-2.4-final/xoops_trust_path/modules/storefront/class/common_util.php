<?php
class CommonUtil {
	
	static public function createWithParams() {
		return new CommonUtil();
	}

	static public function toQueryString($hash, array $ignored = array()) {
		$tmpAry = array();
		$keys = array_keys($hash);
		sort($keys);
		foreach($keys as $key) {
			if (!in_array($key, $ignored)) {
				array_push($tmpAry, "{$key}={$hash[$key]}");
			}
		}
		return join("&", $tmpAry);
	}

	/**
	 * returns ResourceClient client interface.
	 * @return a instance of ResourceClient
	 */
	protected static function getResourceClient() {
		return new ResourceClient();
	}
	
	
	/**
	 * Returns ShopAnswer instances with qaQuestionId and qaAnswerId.
	 * @param int qaQuestionId
	 * @param object Questions and Answers
	 * @return array of ShopAnswer instance
	 */
	function getShopAnswers($qaQuestionId, $qaQuestionAndAnswer, $languageManager) {
	
		$shopAnswers = array();
	
		// get parameters for answer update
		foreach($qaQuestionAndAnswer as $_qaQuestionId => $answers) {
			
			if (strcmp($qaQuestionId, $_qaQuestionId) == 0 && is_array($answers)) {
				
				foreach ($answers as $qaAnswerId => $available) {
					
					$shopAnswer = ShopAnswer::createFromAnswer(array( 'id' => $qaAnswerId,	// qa_answer_id
																	  'qa_question_id' => $qaQuestionId,	// qa_question_id
																  	  'qa_answer_available' => $available,
																  	  'shop_answer_id' => null));
					array_push($shopAnswers, $shopAnswer);
				}
			}
		 }
		 return $shopAnswers;
	}
	
	/**
	 * language tag => language name (English) map cache
	 * @var array
	 */
	static $languageMapCache;
	
	/**
	 * returns a hash of language tag and language name (English)
	 * @return array
	 */
	static public function getLanguageNameMap() {
		if(!self::$languageMapCache) {
			require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
			self::$languageMapCache = $LANGRID_LANGUAGE_ARRAY;
		}
		return self::$languageMapCache;
	}
	
	/**
	 * get language name of tag in English.
	 * @param string $tag
	 * @return string
	 */
	static public function getLanguageNameByTag($tag) {
		$languageMap = self::getLanguageNameMap();
		return $languageMap[$tag] ? $languageMap[$tag] : $tag;
	}
	
	/**
	 * language tag => language name (localized) map cache
	 * @var array
	 */
	static $localizedLanguageMapCache;
	
	/**
	 * returns a hash of language tag and language name (localized)
	 * @return array
	 */
	static public function getLocalizedLanguageNameMap() {
		if(!self::$localizedLanguageMapCache) {
			self::$localizedLanguageMapCache = self::getLanguageNameMap();
			require dirname(__FILE__) . '/../include/localized_languages.php';
			foreach ($LOCALIZED_LANGUAGE_ARRAY as $key => $value) {
				self::$localizedLanguageMapCache[$key] = $value;
			}
		}
		return self::$localizedLanguageMapCache;
	}
	
	/**
	 * get language name of tag in English.
	 * @param string $tag
	 * @return string
	 */
	static public function getLocalizedLanguageNameByTag($tag) {
		$languageMap = self::getLocalizedLanguageNameMap();
		return $languageMap[$tag] ? $languageMap[$tag] : $tag;
	}
	
	/*
	 * Function to display formatted times in user timezone
	 */
	static function formatTimestamp($time, $format="l", $timeoffset="") {
	    global $xoopsConfig, $xoopsUser;
	    $usertimestamp = xoops_getUserTimestamp($time, $timeoffset);
	    return _formatTimeStamp($usertimestamp, $format);
	}
	
	/**
	 * returns list of languages by resource name.
	 * @param string $name
	 * @return array
	 */
	static function getLanguageListFromResource($name) {
		$cli = self::getResourceClient();
		$resources = $cli->getLanguageResource($name);
		foreach ($resources as $resource) {
			if (is_object($resource)){
				if ($resource->type == 'QA') {
					return $resource->languages;
				}
			}
		}
		return array();
	}
	
	static public function prefixedTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		$myModuleDir = realpath(dirname(__FILE__) . '/../');
		$myModuleName = basename($myModuleDir);
		return "{$xoopsDB->prefix}_{$myModuleName}_{$tableName}";
	}
}
?>