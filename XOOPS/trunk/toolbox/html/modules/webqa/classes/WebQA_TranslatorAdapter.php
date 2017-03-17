<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
interface IWebQA_TranslatorAdapter {
	
	/**
	 * @param String $sourceLang
	 * @param String $targetLang
	 * @param String(String[]) $text
	 */
	public static function translate($sourceLang, $targetLang, $text);
}

class WebQA_TranslatorAdapter implements IWebQA_TranslatorAdapter {
	
	static $bindingSetName = 'SITE';
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 * @param unknown_type $text
	 */
	public static function translate($sourceLang, $targetLang, $text) {
		if (!self::hasTranslationPath($sourceLang, $targetLang)) {
			return null;
		}
		
		if (is_array($text)) {
			return self::translateArray($sourceLang, $targetLang, $text);
		}
		
		if (preg_match('/^[\n\r\t\s　]*$/u', $text)) {
			return '';
		}

		return self::doTranslate($sourceLang, $targetLang, $text);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 * @param unknown_type $textArray
	 */
	private static function translateArray($sourceLang, $targetLang, $textArray) {
		$return = array();
		
		foreach ($textArray as $text) {
			$return[] = self::translate($sourceLang, $targetLang, $text);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 * @param unknown_type $text
	 */
	private static function doTranslate($sourceLang, $targetLang, $text) {
		require_once XOOPS_ROOT_PATH.'/api/class/client/extras/Develope_LangridAccessClient.class.php';
		
		$client = new Develope_LangridAccessClient();
		
		$response = $client->multisentenceTranslate(
			$sourceLang,
			$targetLang,
			array($text),
			self::$bindingSetName,
			Toolbox_Develope_SourceTextJoinStrategyType::Normal
		);
		
		if (strtoupper($response['status']) != 'OK') {
			return '';
		}
		
		return $response['contents'][0]->result[0];
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 */
	private static function hasTranslationPath($sourceLang, $targetLang) {
		require_once XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php';
		
		$client = new LangridAccessClient();
		$res = $client->getSupportedTranslationLanguagePairs(self::$bindingSetName);
		
		if (strtoupper($res['status']) != 'OK') return false;
		
		foreach ($res['contents'] as $pair) {
			if ($pair[0] == $sourceLang && $pair[1] == $targetLang) return true; 
		}
		
		return false;
	}
}
?>