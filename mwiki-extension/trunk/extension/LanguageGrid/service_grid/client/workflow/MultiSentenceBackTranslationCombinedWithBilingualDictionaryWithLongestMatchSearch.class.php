<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/../back_translation_with_temporal_dictionary/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 複数行辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch
	extends BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers  {

	function __construct() {
		parent::__construct();
	}

	const SEPARATOR_IF_END_WITH_TERMINATOR = "*%$*";
	const SEPARATOR_IF_NOT_END_WITH_TERMINATOR = "*$%*";

	/**
	 * <#if locale="ja">
	 * 複数行翻訳
	 * </#if>
	 */
	public function _translate() {
		$lastResponse = null;
		$resultArray = array();
		$licenseInformationArray = array();
		$sourceLang = $this->context->getSourceLang();
		$targetLang = $this->context->getIntermediateLang();
		$sourceArray = $this->context->getSourceArray();
		$resultObj = new stdClass();
		
		switch ($this->context->getSourceTextJoinStrategyType()) {
			case SourceTextJoinStrategyType::Customized :
				mb_regex_encoding( 'UTF-8');
				// 終端記号の定義
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

				//$result = parent::translate($sourceLang, $targetLang, $mergedContents['source'], $bindingSetName);
				$this->context->setSource($mergedContents['source']);
				$lastResponse = parent::_translate();
				if ($lastResponse != null && $lastResponse['status'] != 'OK') {
					return $lastResponse;
				}
				$licenseInformationArray = array_merge($licenseInformationArray, $lastResponse['LicenseInformation']);
				
				$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
				$resultArray = array_merge($resultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInTargetLang) . ']', $lastResponse['contents']));
				// 配列末尾の空要素を削除
				if (end($resultArray) == '') {
					array_pop($resultArray);
				}
				reset($resultArray);
				// 結合時に追加した終端記号の除去
				for ($i = 0; $i < count($sourceArray); $i++) {
					if ($endWithTerminator[$i] == false && isset($resultArray[$i]) == true) {
						$resultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInTargetLang) . ']$', "", trim($resultArray[$i]));
					}
					if (isset($resultArray[$i]) === false) {
						$resultArray[$i] = '';
					}
				}
				$resultObj = $resultArray;
				break;
			case SourceTextJoinStrategyType::Normal :
			default:
				foreach ($sourceArray as $source) {
					$this->context->setSource($source);
					if (empty($source)) {
						continue;
					}
					$lastResponse = parent::_translate();
					if ($lastResponse != null && $lastResponse['status'] != 'OK') {
						return $lastResponse;
					}
					$resultArray[] = $lastResponse['contents'];
					$licenseInformationArray = array_merge($licenseInformationArray, $lastResponse['LicenseInformation']);
				}
				$intermediates = array();
				$targets = array();
				foreach ($resultArray as $res) {
					$intermediates[] = $res->intermediate;
					$targets[] = $res->target;
				}
				$resultObj->intermediate = $intermediates;
				$resultObj->target = $targets;
				break;
		}
		$lastResponse['contents'] = $resultObj;
		$lastResponse['LicenseInformation'] = $licenseInformationArray;
		try {
			$log = "";
			foreach ($resultArray as $ret) {
				$log = $log.$ret->intermediate;
			}
			$s = '';
			foreach ($sourceArray as $tmp) {
				$s = $s.$tmp;
			}
		$this->context->debugLog($this->context->getSourceLang(),
				$targetLang	, 
				$s, $log);
		} catch (Exception $e) {
			var_dump($e->getMessage()); die();
		}
		return $lastResponse;
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