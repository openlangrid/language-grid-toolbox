<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
die("This file is deprecated. ".__FILE__);
//require_once('SOAP/Value.php');
///**
// * <#if locale="en">
// * Page Dictionary (temporal dictionary) access class
// * <#elseif locale="ja">
// * ページ辞書（テンポラル辞書）アクセスクラス
// * </#if>
// */
class UserDictionaryClass {
	function __construct() {
	}
//	function getUserDictionarys() {
//		if (file_exists(XOOPS_ROOT_PATH.'/service_grid/dictionary/php/lib/user-dictionary-controller.php')) {
//			require_once(XOOPS_ROOT_PATH.'/service_grid/dictionary/php/lib/user-dictionary-controller.php');
//			$controller =& new UserDictionaryController();
//			$result = $controller->load();
//			if (isset($result['contents'])) {
//				$contents = $result['contents'];
//				$return = array();
//				foreach ($contents as $content) {
//					if ($content['typeId'] == '0') {
//						$return[] = $content;
//					}
//				}
//				return $return;
//			}
//		}
//		return null;
//	}
//
//	function getUserDictionaryContents($dictionaryName, $sourceLang, $targetLang, $sourceText = "") {
//		if (file_exists(XOOPS_ROOT_PATH.'/service_grid/dictionary/php/lib/user-dictionary-controller.php')) {
//			require_once(XOOPS_ROOT_PATH.'/service_grid/dictionary/php/lib/user-dictionary-controller.php');
//			$controller =& new UserDictionaryController();
//			$dictionaryInfos = $controller->load();
//			$result = $controller->read(array('id' => $this->getUserDictionaryIdByName($dictionaryInfos['contents'], $dictionaryName)));
//			if ($result['status'] != 'OK') {
//				return null;
//			}
//			$dataArray = $result['contents']['dictionary'];
//			$langSets = array_shift($dataArray);
//			if ($langSets == null) {
//				return null;
//			}
//			if ($langKeys = array_flip($langSets)) {
//				if (array_key_exists($sourceLang, $langKeys) && array_key_exists($targetLang, $langKeys)) {
//					$srcKey = $langKeys[$sourceLang];
//					$tgtKey = $langKeys[$targetLang];
//
//					$userDictArray = array();
//					foreach ($dataArray as $data) {
//						$srcWord = $data[$srcKey];
//						$tgtWord = $data[$tgtKey];
//						if (!empty($srcWord) && !empty($tgtWord) && mb_stripos($sourceText, $srcWord) !== false) {
////						if (!empty($srcWord) && !empty($tgtWord)) {
//							$userDictArray[] = $this->makeSOAP_Value($srcWord, $tgtWord);
//						}
////						$userDictArray[] = $this->makeSOAP_Value($data[$srcKey], $data[$tgtKey]);
//					}
//					return $userDictArray;
//				}
//			}
//		}
//		return null;
//	}
//
//	private function makeSOAP_Value($headWord, $targetWord) {
//		$headWord = $this->_htmlEscape($headWord);
//		$targetWord = $this->_htmlEscape($targetWord);
//
//		return new SOAP_Value(
//			'Translation',
//			'',
//			array(
//				'headWord' => $headWord,
//				'targetWords' => array($targetWord),
//			)
//		);
//	}
//
//	private function getUserDictionaryIdByName($dictInfo, $name) {
//		foreach ($dictInfo as $dict) {
////			if ($dict['name'] == $name && $dict['deployFlag'] === false) {
//			if ($dict['name'] == $name) {
//				return $dict['id'];
//			}
//		}
//		return null;
//	}
//
//	private function _htmlEscape($str) {
////		return $str;
//		return htmlspecialchars($str, ENT_COMPAT);
//	}
}
?>