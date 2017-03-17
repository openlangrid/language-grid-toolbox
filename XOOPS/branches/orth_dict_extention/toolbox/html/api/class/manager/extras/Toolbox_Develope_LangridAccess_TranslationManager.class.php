<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

require_once(dirname(__FILE__).'/../Toolbox_LangridAccess_TranslationManager.class.php');

class Toolbox_Develope_LangridAccess_TranslationManager extends Toolbox_LangridAccess_TranslationManager {

	const SEPARATOR_IF_END_WITH_TERMINATOR = '*%$*';
	const SEPARATOR_IF_NOT_END_WITH_TERMINATOR = '*$%*';

	/**
	 * <#if lang="ja">
	 * 複数行翻訳
	 * </#if>
	 */
	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy = Toolbox_Develope_SourceTextJoinStrategyType::Normal) {
		$resultArray = array();
		$lastVo = null;

		switch ($SourceTextJoinStrategy) {
			case Toolbox_Develope_SourceTextJoinStrategyType::Punctuation :

				die('未実装');

				break;
			case Toolbox_Develope_SourceTextJoinStrategyType::Customized :
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
				$result = parent::translate($sourceLang, $targetLang, $mergedContents['source'], $bindingSetName);

				if ($result != null && $result['status'] != 'OK') {
					// TODO: １回でもErrorになったら、即終了。
					return $result;
				}

				$lastVo = $result['contents'][0];
				$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
				$resultArray = array_merge($resultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInTargetLang) . ']', $lastVo->result));
				// 配列末尾の空要素を削除
				if (end($resultArray) == '') {
					array_pop($resultArray);
				}
				reset($resultArray);

//$f = fopen(XOOPS_TRUST_PATH.'/tmp/translation-log-'.date('Y-m-d').'.txt', 'a');
//fwrite($f, sprintf('-start %s2%s %s', $sourceLang, $targetLang, date(DATE_ATOM)).PHP_EOL);
//fwrite($f, sprintf('sourceArray::count=%s, targetArray::count=%s', count($sourceArray), count($resultArray)).PHP_EOL);
//fwrite($f, sprintf('source::strlen=%s, source::mb_strlen=%s, target::strlen=%s, target::mb_strlen=%s', strlen($mergedContents['source']), mb_strlen($mergedContents['source']), strlen($lastVo->result), mb_strlen($lastVo->result)).PHP_EOL);
//fwrite($f, 'SOURCE_TEXT='.$mergedContents['source'].PHP_EOL);
//fwrite($f, 'TARGET_TEXT='.$lastVo->result.PHP_EOL);
//if (count($sourceArray) != count($resultArray)) {
//	$num = count($sourceArray) > count($resultArray) ? count($sourceArray) : count($resultArray);
//	fwrite($f, '--arrays details--'.PHP_EOL);
//	fwrite($f, "no\tsource\ttarget".PHP_EOL);
//	for ($i = 0; $i < $num; $i++) {
//		fwrite($f, sprintf("%s\t%s\t%s", $i, isset($sourceArray[$i])? $sourceArray[$i]: '(null)', isset($resultArray[$i])? $resultArray[$i]: '(null)').PHP_EOL);
//	}
//}
//fwrite($f, '-end-----------------------------------------------------------'.PHP_EOL);
//fclose($f);


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
//				if (count($sourceArray) != count($resultArray)) {
//					$resultArray = array_merge($resultArray, $sourceArray);
//				}

				break;
			case Toolbox_Develope_SourceTextJoinStrategyType::Normal :
			default:
				foreach ($sourceArray as $source) {
					$result = parent::translate($sourceLang, $targetLang, $source, $bindingSetName);
					if ($result != null && $result['status'] != 'OK') {
						// TODO: １回でもErrorになったら、即終了。
						return $result;
					}
					$lastVo = $result['contents'][0];
					$resultArray[] = $lastVo->result;
				}
				break;
		}

		$lastVo->result = $resultArray;
		return $this->getResponsePayload($lastVo);
	}

	/**
	 * <#if lang="ja">
	 * 複数行折返翻訳
	 * </#if>
	 */
	public function multisentenceBackTranslate($sourceLang, $intermediatetLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy = Toolbox_Develope_SourceTextJoinStrategyType::Normal) {
		$intermediateResultArray = array();
		$targetResultArray = array();
		$lastVo = null;

		switch ($SourceTextJoinStrategy) {
			case Toolbox_Develope_SourceTextJoinStrategyType::Punctuation :

				die('未実装');

				break;
			case Toolbox_Develope_SourceTextJoinStrategyType::Customized :
				mb_regex_encoding( 'UTF-8');
				// 終端記号の定義
				if ($sourceLang === 'ja' || strpos($sourceLang, 'zh') === 0) {
					$terminatorsInSourceLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInSourceLang = array('.','?','!');
				}
				if ($intermediatetLang === 'ja' || strpos($intermediatetLang, 'zh') === 0) {
					$terminatorsInIntermediateLang = array('。','．','.','？','！','?','!');
				} else {
					$terminatorsInIntermediateLang = array('.','?','!');
				}

				$mergedContents = $this->mergeSentences($sourceArray, $terminatorsInSourceLang);
				$endWithTerminator = $mergedContents['endWithTerminator'];

				$result = parent::backTranslate($sourceLang, $intermediatetLang, $mergedContents['source'], $bindingSetName);

				if ($result != null && $result['status'] != 'OK') {
					// TODO: １回でもErrorになったら、即終了。
					return $result;
				}

				$lastVo = $result['contents'][0];
				$regexpOfSeparator = '('.preg_quote(self::SEPARATOR_IF_NOT_END_WITH_TERMINATOR).'|'.preg_quote(self::SEPARATOR_IF_END_WITH_TERMINATOR).')';
				$intermediateResultArray = array_merge($intermediateResultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInIntermediateLang) . ']*', $lastVo->intermediateResult));
				$targetResultArray = array_merge($targetResultArray,
					mb_split($regexpOfSeparator . '[' . implode("", $terminatorsInSourceLang) . ']*', $lastVo->targetResult));
				// 配列末尾の空要素を削除
				array_pop($intermediateResultArray);
				array_pop($targetResultArray);
				// 結合時に追加した終端記号の除去
				for ($i = 0; $i < count($sourceArray); $i++) {
					if ($endWithTerminator[$i] == false && isset($intermediateResultArray[$i]) == true) {
						$intermediateResultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInIntermediateLang) . ']$', "", trim($intermediateResultArray[$i]));
//						$targetResultArray[$i] =
//							mb_ereg_replace('[' . implode("", $terminatorsInSourceLang) . ']$', "", trim($targetResultArray[$i]));
					}
					if ($endWithTerminator[$i] == false && isset($targetResultArray[$i]) == true) {
//						$intermediateResultArray[$i] =
//							mb_ereg_replace('[' . implode("", $terminatorsInIntermediateLang) . ']$', "", trim($intermediateResultArray[$i]));
						$targetResultArray[$i] =
							mb_ereg_replace('[' . implode("", $terminatorsInSourceLang) . ']$', "", trim($targetResultArray[$i]));
					}
				}
				break;
			case Toolbox_Develope_SourceTextJoinStrategyType::Normal :
			default:
				if (is_array($sourceArray) === false) {
					$sourceArray = array($sourceArray);
				}
				foreach ($sourceArray as $source) {
					$result = parent::backTranslate($sourceLang, $intermediatetLang, $source, $bindingSetName);
					if ($result != null && $result['status'] != 'OK') {
						// TODO: １回でもErrorになったら、即終了。
						return $result;
					}
					$lastVo = $result['contents'][0];
					$intermediateResultArray[] = $lastVo->intermediateResult;
					$targetResultArray[] = $lastVo->targetResult;
				}
				break;
		}

		$lastVo->intermediateResult = $intermediateResultArray;
		$lastVo->targetResult = $targetResultArray;
//debugLog('### ANSWER ### '.print_r($lastVo));
		return $this->getResponsePayload($lastVo);
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
 * <#if lang="ja">
 * 翻訳原文結合戦略
 * </#if>
 */
class Toolbox_Develope_SourceTextJoinStrategyType {
	const Normal = 0;		// 配列分Loopする（文字列連結しない）
	const Punctuation = 1;	// 句読点(?)
	const Customized = 2;	// 独自記号(?)
}
?>