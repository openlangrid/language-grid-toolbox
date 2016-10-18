<?php
require_once(dirname(__FILE__).'/LangridAccess_TranslationManager.class.php');

class LangridAccess_TranslationManager_MultiSentence extends LangridAccess_TranslationManager {

	const SEPARATOR_IF_END_WITH_TERMINATOR = '*%$*';
	const SEPARATOR_IF_NOT_END_WITH_TERMINATOR = '*$%*';

	/**
	 * <#if locale="ja">
	 * 複数行翻訳
	 * </#if>
	 */
	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $setId, $dictId, $SourceTextJoinStrategy = SourceTextJoinStrategyType::Normal) {
		$resultArray = array();
		$distOpt = array('status' => Null, 'message' => Null);
		$lastVo = null;

		switch ($SourceTextJoinStrategy) {
			case SourceTextJoinStrategyType::Punctuation :
				die('"Punctuation" is not supported by the binding text. "Customized" please use.');
				break;
			case SourceTextJoinStrategyType::Customized :
				mb_regex_encoding( 'UTF-8');
				if ($sourceLang === 'ja' || $sourceLang === 'zh') {
					$terminatorsInSourceLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInSourceLang = array('.','?','!');
				}
				if ($targetLang === 'ja' || $targetLang === 'zh') {
					$terminatorsInTargetLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInTargetLang = array('.','?','!');
				}

				$mergedContents = $this->mergeSentences($sourceArray, $terminatorsInSourceLang);
				$endWithTerminator = $mergedContents['endWithTerminator'];
				$result = parent::translate($sourceLang, $targetLang, $mergedContents['source'], $setId, $dictId);

				if ($result != null && $result['status'] != 'OK') {
					return $result;
				}
				$distOpt['status'] = $result['status'];
				$distOpt['message'] = $result['message'];

				$lastVo = $result['contents'];
				$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
				$resultArray = array_merge($resultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInTargetLang) . ']', $lastVo->result));

				if (count($resultArray) > 1) {
					array_pop($resultArray);
				}

				for ($i = 0; $i < count($sourceArray); $i++) {
					if ($endWithTerminator[$i] == false) {
						$resultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInTargetLang) . ']$', "", trim($resultArray[$i]));
					}
				}

				if (count($resultArray) !== count($sourceArray)) {
					// 分割文字が翻訳されているという判断
					$distOpt = array('status' => 'Warning', 'message' => 'Disappeared delimiter string.');
				}

				break;
			case SourceTextJoinStrategyType::Normal :
			default:
				foreach ($sourceArray as $source) {
					$result = parent::translate($sourceLang, $targetLang, $source, $setId, $dictId);
					if ($result != null && $result['status'] != 'OK') {
						return $result;
					}
					$distOpt['status'] = $result['status'];
					$distOpt['message'] = $result['message'];
					$lastVo = $result['contents'];
					$resultArray[] = $lastVo->result;
				}
				break;
		}

		$lastVo->result = $resultArray;
		return $this->getResponsePayload($lastVo, $distOpt['status'], $distOpt['message']);
	}

	/**
	 * <#if locale="ja">
	 * 複数行折返翻訳
	 * </#if>
	 */
	public function multisentenceBackTranslate($sourceLang, $intermediatetLang, $sourceArray, $setId, $dictId, $SourceTextJoinStrategy = SourceTextJoinStrategyType::Normal) {
		$intermediateResultArray = array();
		$targetResultArray = array();
		$distOpt = array('status' => Null, 'message' => Null);
		$lastVo = null;

		switch ($SourceTextJoinStrategy) {
			case SourceTextJoinStrategyType::Punctuation :
				die('"Punctuation" is not supported by the binding text. "Customized" please use.');
				break;
			case SourceTextJoinStrategyType::Customized :
				mb_regex_encoding( 'UTF-8');
				if ($sourceLang === 'ja' || $sourceLang === 'zh') {
					$terminatorsInSourceLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInSourceLang = array('.','?','!');
				}
				if ($intermediatetLang === 'ja' || $intermediatetLang === 'zh') {
					$terminatorsInIntermediateLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInIntermediateLang = array('.','?','!');
				}

				$mergedContents = $this->mergeSentences($sourceArray, $terminatorsInSourceLang);
				$endWithTerminator = $mergedContents['endWithTerminator'];

				$result = parent::backTranslate($sourceLang, $intermediatetLang, $mergedContents['source'], $setId, $dictId);

				if ($result != null && $result['status'] != 'OK') {
					return $result;
				}
				$distOpt['status'] = $result['status'];
				$distOpt['message'] = $result['message'];

				$lastVo = $result['contents'];
				$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
				$intermediateResultArray = array_merge($intermediateResultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInIntermediateLang) . ']*', $lastVo->intermediateResult));
				$targetResultArray = array_merge($targetResultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInSourceLang) . ']*', $lastVo->targetResult));

				if (count($intermediateResultArray) > 1) {
					array_pop($intermediateResultArray);
				}
				if (count($targetResultArray) > 1) {
					array_pop($targetResultArray);
				}

				for ($i = 0; $i < count($sourceArray); $i++) {
					if ($endWithTerminator[$i] == false) {
						$intermediateResultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInIntermediateLang) . ']$', "", trim($intermediateResultArray[$i]));
						$targetResultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInSourceLang) . ']$', "", trim($targetResultArray[$i]));
					}
				}

				if (count($intermediateResultArray) !== count($sourceArray) || count($targetResultArray) !== count($sourceArray)) {
					// 分割文字が翻訳されているという判断
					$distOpt = array('status' => 'Warning', 'message' => 'Disappeared delimiter string.');
				}

				break;
			case SourceTextJoinStrategyType::Normal :
			default:
				if (is_array($sourceArray) === false) {
					$sourceArray = array($sourceArray);
				}
				foreach ($sourceArray as $source) {
					$result = parent::backTranslate($sourceLang, $intermediatetLang, $source, $setId, $dictId);
					if ($result != null && $result['status'] != 'OK') {
						return $result;
					}
					$distOpt['status'] = $result['status'];
					$distOpt['message'] = $result['message'];
					$lastVo = $result['contents'];
					$intermediateResultArray[] = $lastVo->intermediateResult;
					$targetResultArray[] = $lastVo->targetResult;
				}
				break;
		}

		$lastVo->intermediateResult = $intermediateResultArray;
		$lastVo->targetResult = $targetResultArray;
		return $this->getResponsePayload($lastVo, $distOpt['status'], $distOpt['message']);
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


/**
 * <#if locale="ja">
 * 翻訳原文結合戦略
 * </#if>
 */
class SourceTextJoinStrategyType {
	const Normal = 0;		// 配列分Loopする（文字列連結しない）
	const Punctuation = 1;	// 句読点(?)
	const Customized = 2;	// 独自記号(?)
}
?>