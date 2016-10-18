<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/../translation_with_temporal_dictionary/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 複数行辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch
	extends TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {

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

		$sourceLang = $this->context->getSourceLang();
		$targetLang = $this->context->getTargetLang();
		$sourceArray = $this->context->getSourceArray();

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
					// TODO: １回でもErrorになったら、即終了。
					return $lastResponse;
				}

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
				break;
			case SourceTextJoinStrategyType::Normal :
			default:
				foreach ($sourceArray as $source) {
					$this->context->setSource($source);
					$lastResponse = parent::_translate();
					if ($lastResponse != null && $lastResponse['status'] != 'OK') {
						// TODO: １回でもErrorになったら、即終了。
						return $lastResponse;
					}
					$resultArray[] = $lastResponse['contents'];
				}
				break;
		}
		foreach ($resultArray as $res) {
			if (!empty($res)) {
				$results[] = $res;
			}
		}
		$resultObj->result = $results;
		$lastResponse['contents'] = $resultObj;
//		$lastResponse['contents'] = $resultArray;
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