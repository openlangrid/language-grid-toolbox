<?php
class Validator {
	
	// @return errorMessage
	static public function validateForCreate($params) {
		if ($params['dictionaryName'] == '') {
			return _MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY;
		}
		
		if (!self::isValidDictionaryName($params['dictionaryName'])) {
			return _MI_DICTIONARY_ERROR_DICTIONARY_NAME_INVALID;
		}
		
		$params['supportedLanguages'] = array_unique($params['supportedLanguages']);
		if (count($params['supportedLanguages']) < 2) {
			return _MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES;
		}
		
		if(!isAllSupportedLanguage($params['supportedLanguages'], $params['dictionaryTypeId'])) {
			return 'Error: invalid language';
		}
		return null;
	}
	
	// @return isValid
	static public function validateForUpload($params) {
		
		$languages = $params['valueToSave'][0];
		if(!isAllSupportedLanguage($languages, $params['typeId'])) {
			return 'Error: invalid language';
		}
		
		$whiteList = array(
			'text/plain',
			'application/octet-stream'
		);
		if (!in_array($params['mimeType'], $whiteList)) {
			return _MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID;
		}
		
		return null;
	}
	

	/**
	 *
	 * @param $dictionaryName
	 * @return bool
	 */
	static protected function isValidDictionaryName($dictionaryName) {
		return strlen($dictionaryName) >= 4
				&& preg_match("/^.*[a-zA-Z].*$/", $dictionaryName)
				&& preg_match("/^([a-zA-Z0-9-]*(\.| ))*[a-zA-Z0-9-]*$/", $dictionaryName);
//		return preg_match("/^([a-zA-Z0-9_-]*(\.| ))*[a-zA-Z0-9-_]*$/", $dictionaryName);
//		^([a-zA-Z0-9]*[\. _-])*[a-zA-Z0-9]+$
	}
	
}
