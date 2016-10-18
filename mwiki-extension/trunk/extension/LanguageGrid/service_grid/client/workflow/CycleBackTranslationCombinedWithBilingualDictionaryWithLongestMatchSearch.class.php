<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * マルチホップ辞書連携折り返し翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class CycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch 
	extends MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {
        
	function __construct() {
		parent::__construct();
	}

    function _translate() {
		$set = $this->context->getBindings();
		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		$intermediate = $this->context->getSource();
		$licenseInformationArray = array();
		$response = array();
        $object = new stdClass;
        $result = $this->invokeService($execs, $intermediate);
        if ($result['status'] != 'OK') {
        	return $result;
		}
		$licenseInformationArray = array_merge($licenseInformationArray, $result['LicenseInformation']);
		$object->intermediate = $result['contents'];
		$set = $this->context->getReverseBindings();
		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		$result2 = $this->invokeService($execs, $result['contents']);
		if ($result2['status'] != 'OK') {
			return $result2;
		}
		$licenseInformationArray = array_merge($licenseInformationArray, $result2['LicenseInformation']);
		$object->target = $result2['contents'];
		$response['contents'] = $object;       
		$response['LicenseInformation'] = $licenseInformationArray;
        return $response;
	}
	
	protected function invokeService($translationExecs, $source) {
		$result;$licenseInformationArray = array();
		foreach ($translationExecs as $exec) {
            $this->_makeBinding($exec);
            $sourceLang = $exec->getSourceLang();
            $targetLang = $exec->getTargetLang();
            $result = $this->_client->invokeService('translate',
                                                    array($sourceLang,
                                                          $targetLang,
                                                          $source,
                                                          $this->_getTemporalDict($source, $sourceLang, $targetLang),
                                                          $targetLang
                                                    ));
            if ($result['status'] != 'OK') {
            	return $result;
            }
            $source = $result['contents'];
            $licenseInformationArray = array_merge($licenseInformationArray, $result['LicenseInformation']);
		}
		$result['LicenseInformation'] = $licenseInformationArray;
		return $result;
	}
}
?>