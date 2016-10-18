<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');


/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * マルチホップ辞書連携マルチホップ翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch 
	extends MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {

	const SEPARATOR_IF_END_WITH_TERMINATOR = "*%$*";
	const SEPARATOR_IF_NOT_END_WITH_TERMINATOR = "*$%*";
		
	function __construct() {
		parent::__construct();
	}
    function _translate() {
		$set = $this->context->getBindings();
		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		$intermediate = $this->context->getSourceArray();
		$licenseInformationArray = array();
		$response = array();
        $result = $this->invokeService($execs, $intermediate);
        if ($result['status'] != 'OK') {
        	return $result;
		}
        return $result;
	}
	
	protected function invokeService($translationExecs, $sourceArray) {
		$lastResponse = null;
		$result;$licenseInformationArray = array();
		foreach ($translationExecs as $exec) {
			$resultArray = array();
			$this->_makeBinding($exec);
            $sourceLang = $exec->getSourceLang();
            $targetLang = $exec->getTargetLang();
			switch ($this->context->getSourceTextJoinStrategyType()) {
				case SourceTextJoinStrategyType::Customized :
					mb_regex_encoding( 'UTF-8');
					if ($sourceLang === 'ja' || strpos($sourceLang, 'zh') === 0) {
						$terminatorsInSourceLang = array('。','．','.','？','！','?','!');
					} else {
						$terminatorsInSourceLang = array('.','?','!');
					}
					if ($targetLang === 'ja' || strpos($targetLang, 'zh') === 0) {
						$terminatorsInTargetLang = array('。','．','.','？','！','?','!');
					} else {
						$terminatorsInTargetLang = array('.','?','!');
					}
					$mergedContents = $this->mergeSentences($sourceArray, $terminatorsInSourceLang);
					$endWithTerminator = $mergedContents['endWithTerminator'];
            		$lastResponse = $this->_client->invokeService('translate',
                                                    array($sourceLang,
                                                          $targetLang,
                                                          $mergedContents['source'],
                                                          $this->_getTemporalDict($mergedContents['source'], $sourceLang, $targetLang),
                                                          $targetLang
                                                    ));
					if ($lastResponse != null && $lastResponse['status'] != 'OK') {
						return $lastResponse;
					}
					$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
					$resultArray = array_merge($resultArray,
						mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInTargetLang) . ']', $lastResponse['contents']));
					if (end($resultArray) == '') {
						array_pop($resultArray);
					}
					reset($resultArray);
					for ($i = 0; $i < count($sourceArray); $i++) {
						if ($endWithTerminator[$i] == false && isset($resultArray[$i]) == true) {
							$resultArray[$i] =
								mb_ereg_replace('[' . implode("", $terminatorsInTargetLang) . ']$', "", trim($resultArray[$i]));
						}
						if (isset($resultArray[$i]) === false) {
							$resultArray[$i] = '';
						}
					}
					break;
				case SourceTextJoinStrategyType::Normal :
				default:
					foreach ($sourceArray as $source) {
            			$lastResponse = $this->_client->invokeService('translate',
                                                    array($sourceLang,
                                                          $targetLang,
                                                          $source,
                                                          $this->_getTemporalDict($source, $sourceLang, $targetLang),
                                                          $targetLang
                                                    ));
												if ($lastResponse != null && $lastResponse['status'] != 'OK') {
							// TODO: １回でもErrorになったら、即終了。
							return $lastResponse;
						}
						$resultArray[] = $lastResponse['contents'];
					}
					break;
			}
			$sourceArray = $resultArray;
            $licenseInformationArray = array_merge($licenseInformationArray, $lastResponse['LicenseInformation']);
		}
		$result['status'] = 'OK';
		$result['contents'] = $sourceArray;
		$result['LicenseInformation'] = $licenseInformationArray;
		return $result;
	}
	
	private function mergeSentences($sentences, $terminators) {
		$sourceNum = 0;
		$sourceBuffer = "";
		$endWithTerminator = array();
		foreach($sentences as $sentence) {
			$sourceBuffer .= trim($sentence);
			$endWithTerminator[$sourceNum] = mb_ereg_match('.*[' . implode("", $terminators) . ']$', trim($sentence));
			if ( ! $endWithTerminator[$sourceNum]) {
				$sourceBuffer .= $terminators[0] . ' ' . self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR . $terminators[0] . ' ';
			} else {
				$sourceBuffer .= ' ' . self::SEPARATOR_IF_END_WITH_TERMINATOR . $terminators[0] . ' ';
			}

			$sourceNum++;
			if ($sourceNum == count($sentences)){
				break;
			}
		}
		$merged = array(
			'source' => $sourceBuffer
			, 'endWithTerminator' => $endWithTerminator
		);
		return $merged;
	}
	
}
?>