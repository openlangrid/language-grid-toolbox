<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__) . '/DictionaryService.php');
require_once(dirname(__FILE__) . '/../model/Translation.php');
require_once(dirname(__FILE__) . '/../model/TranslationWithPosition.php');
require_once(dirname(__FILE__) . '/../model/LanguagePair.php');
require_once(dirname(__FILE__) . '/util/TranslationSortComparator.class.php');
require_once('SOAP/Type/dateTime.php');

class BillingualDictionaryService extends DictionaryService {
	public function __construct($dictionaryName, $typeId){
		parent::__construct($dictionaryName, $typeId);
		$this->__dispatch_map = array();
		// search
		$this->__dispatch_map['search'] = array(
			'in' => array(
				'headLang' => 'string'
				, 'targetLang' => 'string'
				, 'headWord' => 'string'
				, 'matchingMethod' => 'string'
				)
			, 'out' => array(
				'searchReturn' => '{urn:BilingualDictionaryService}translationArray')
			);
		$this->__typedef['stringArray'] = array(
			array('item' => 'string')
			);
		$this->__typedef['Translation'] = array(
			'headWord' => 'string'
			, 'targetWords' => '{urn:BilingualDictionaryService}stringArray'
			);
		$this->__typedef['translationArray'] = array(
			array('item' => '{urn:BilingualDictionaryService}Translation')
			);
		// getSupportedMatchingMethod
		$this->__dispatch_map['getSupportedMatchingMethods'] = array(
			'in' =>	array()
			, 'out' => array(
				'getSupportedMatchingMethodsReturn' => '{urn:BilingualDictionaryService}stringArray')
		);
		// getSupportedLanguagePairs
		$this->__dispatch_map['getSupportedLanguagePairs'] = array(
			'in' =>	array()
			, 'out' => array(
				'getSupportedLanguagePairsReturn' => '{urn:BilingualDictionaryService}languagePairArray')
		);
		$this->__typedef['LanguagePair'] = array(
			'first' => 'string'
			, 'second' => 'string'
			);
		$this->__typedef['languagePairArray'] = array(
			array('item' => '{urn:BilingualDictionaryService}LanguagePair')
			);
		// getLastUpdate
		$this->__dispatch_map['getLastUpdate'] = array(
			'in' =>	array()
			, 'out' => array(
				'getLastUpdateReturn' => 'dateTime')
		);
		// searchLongestMatchingTerms
		$this->__dispatch_map['searchLongestMatchingTerms'] = array(
			'in' => array(
				'headLang' => 'string'
				, 'targetLang' => 'string'
				, 'morphemes' => '{urn:BilingualDictionaryService}ArrayOfMorphem'
				)
			, 'out' => array(
				'searchLongestMatchingTermsReturn' => '{urn:BilingualDictionaryService}ArrayOfTranslationWithPosition')
			);
		$this->__typedef['Morphem'] = array(
			'lemma' => 'string'
			, 'partOfSpeech' => 'string'
			, 'word' => 'string'
			);
		$this->__typedef['ArrayOfMorphem'] = array(
			array('item' => '{urn:BilingualDictionaryService}Morphem')
			);
		$this->__typedef['TranslationWithPosition'] = array(
			'numberOfMorphemes' => 'int'
			, 'startIndex' => 'int'
			, 'translation' => '{urn:BilingualDictionaryService}Translation'
			);
		$this->__typedef['ArrayOfTranslationWithPosition'] = array(
			array('item' => '{urn:BilingualDictionaryService}TranslationWithPosition')
			);
	}

	public function search($headLang, $targetLang, $headWord, $matchingMethod) {
		$pv = new ParameterValidator();

		$ipeCheckParameters = array(
			'headLang',
			'targetLang',
			'headWord',
			'matchingMethod'
		);

		foreach ($ipeCheckParameters as $parameter) {
			if(!$pv->validateNull(${$parameter}) || !$pv->validateLanguageCode(${$parameter})){
				$ipe = new InvalidParameterException(null);
				$ipe->setSoapMessage($parameter, $headLang, php_uname("n"));
				throw $ipe;
			}
		}

		if (!$this->isSupportedLanguagePair($headLang, $targetLang)) {
			$ulpe = new UnsupportedLanguagePairException(null);
			$ulpe->setSoapMessage('languagePair', $headLang.':'.$targetLang, php_uname("n"));
			throw $ulpe;
		}

		$headLang = $this->doClean($headLang);
		$targetLang = $this->doClean($targetLang);
		$headWord = $this->doClean($headWord);
		$matchingMethod = $this->doClean($matchingMethod);

		$result = $this->doSearch($headLang, $targetLang, $headWord, $matchingMethod);
		$translations = array();
		foreach($result as $value ){
			$translation = new Translation($value['source'], $value['target']);
			$translations[] = new SOAP_Value('searchReturn', 'searchReturn', $translation);
		}

		return new SOAP_Value('searchReturn', 'searchReturn', $translations);
	}

	public function searchLongestMatchingTerms($headLang, $targetLang, $morphemes) {
		$pv = new ParameterValidator();

		$ipeCheckParameters = array(
			'headLang',
			'targetLang'
		);
		foreach ($ipeCheckParameters as $parameter) {
			if(!$pv->validateNull(${$parameter}) || !$pv->validateLanguageCode(${$parameter})){
				$ipe = new InvalidParameterException(null);
				$ipe->setSoapMessage($parameter, $headLang, php_uname("n"));
				throw $ipe;
			}
		}
		if (!$this->isSupportedLanguagePair($headLang, $targetLang)) {
			$ulpe = new UnsupportedLanguagePairException(null);
			$ulpe->setSoapMessage('languagePair', $headLang.':'.$targetLang, php_uname("n"));
			throw $ulpe;
		}

		$headLang = $this->doClean($headLang);
		$targetLang = $this->doClean($targetLang);
		$matchingMethod = 'prefix';
		$positionArray = array();

		for ($i = 0; $i < count($morphemes); $i++) {
			$m = $morphemes[$i];

			$translations = $this->doSearch($headLang, $targetLang, $m->word, $matchingMethod);
			if ($translations === null || !is_array($translations) || count($translations) == 0) {
				continue;
			}
			usort($translations, array('TranslationSortComparator', 'comparator_object'));

			for ($j = 0; $j < count($translations); $j++) {
				$translation = $translations[$j];
				$headWord = $translation['source'];

				if (strtolower($m->word) == strtolower($headWord)) {
					$positionArray[] = $this->makeTranslationWithPosition($headWord, $translation['target'], 1, $i);
					break;
				}

				$sentence = $m->word;
				$hit = false;
				for ($k = $i + 1; $k < count($morphemes); $k++) {
					$sentence = $sentence . $this->getWordSeparator($headLang) . $morphemes[$k]->word;
					if (strtolower($sentence) == strtolower($headWord)) {
						$positionArray[] = $this->makeTranslationWithPosition($headWord, $translation['target'], $k - $i + 1, $i);
						$i = $k;
						$hit = true;
						break;
					}
				}
				if ($hit) {
					break;
				}
			}
		}

		if (count($positionArray) == 0) {
			return new SOAP_Value('searchLongestMatchingTermsReturn', 'searchLongestMatchingTermsReturn', '');
		}
		return new SOAP_Value('searchLongestMatchingTermsReturn', 'searchLongestMatchingTermsReturn', $positionArray);

/*
		$startIdx = 0;
		$result = null;
		$positionArray = array();
		foreach ($morphemes as $morphem) {
			$headWord = $morphem->word;

			$result = $this->doSearch($headLang, $targetLang, $headWord, $matchingMethod);

			usort($result, array('TranslationSortComparator', 'comparator_object'));

			print_r($result);die();

			if (count($result) > 0) {
				foreach($result as $value ){
					$translation = new Translation($headWord, $value['target']);
					$position = new TranslationWithPosition(1, $startIdx, $translation);
					$positionArray[] = new SOAP_Value('TranslationWithPosition', 'TranslationWithPosition', $position);
				}
			}
			$startIdx = $startIdx + 1;
		}

		if (count($positionArray) == 0) {
//			$pos = new TranslationWithPosition(0, 0, '');
//			$positionArray[] = new SOAP_Value('TranslationWithPosition', 'TranslationWithPosition', $pos);
			return new SOAP_Value('searchLongestMatchingTermsReturn', 'searchLongestMatchingTermsReturn', '');
		}

		return new SOAP_Value('searchLongestMatchingTermsReturn', 'searchLongestMatchingTermsReturn', $positionArray);

//		$translation = new Translation('Kyoto Arashiyama Misora Hibari-kan', array('好き好きヒバリ'));
//		$translationSoap = new SOAP_Value('Translation', 'Translation', $translation);
//		$position = new TranslationWithPosition(4, 3, $translation);
//		$positionSoap = new SOAP_Value('TranslationWithPosition', 'TranslationWithPosition', $position);
//		return new SOAP_Value('searchLongestMatchingTermsReturn', 'searchLongestMatchingTermsReturn', $positionSoap);

*/
	}

	public function getSupportedMatchingMethods(){
		$pv = new ParameterValidator();
		return $pv->getMatchingMethods();
	}

	public function getSupportedLanguagePairs(){
		$dao = $this->getDao();
		$result = $dao->getLanguages();
		$db = $dao->getDBInstance();
		$resultSet = array();
		while($row = $db->fetchRow($result)){
			$resultSet[] = $row[0];
		}
		$pairs = array();
		for($i = 0; $i < count($resultSet) - 1; $i++){
			for($j = $i+1; $j < count($resultSet); $j++){
				$pairs[] = new LanguagePair($resultSet[$i], $resultSet[$j]);
			}
		}
		return $pairs;
	}

	public function getLastUpdate() {
		$dao = $this->getDao();
		$result = $dao->getUpdateDate();
		$db = $dao->getDBInstance();
		$row = $db->fetchRow($result);
		$date = new SOAP_Type_dateTime((int)$row[0]);
//		file_put_contents('dump.txt', "\n".var_export($date->toSoap(), true), FILE_APPEND);
		return $date->toSoap();
//		return $date;
	}

	protected function isSupportedLanguagePair($sourceLanguage, $targetLanguage) {
		$dao = $this->getDao();
		$db = $dao->getDBInstance();
		$result = $dao->getLanguages();
		$supportedLanguages = array();
		while ($row = $db->fetchRow($result)) {
			$supportedLanguages[] = $row[0];
		}
		if (in_array($sourceLanguage, $supportedLanguages) && in_array($targetLanguage, $supportedLanguages)) {
			return true;
		}
		return false;
	}

	private function getWordSeparator($lang) {
		$s = ' ';
		switch (strtolower($lang)) {
			case 'ja':
			case 'zh':
				$s = '';
				break;
			default:
				break;
		}
		return $s;
	}

	private function makeTranslationWithPosition($headWord, $target, $numberOfMorphemes, $startIndex) {
		$t = new Translation($headWord, $target);
		$position = new TranslationWithPosition($numberOfMorphemes, $startIndex, $t);
		return new SOAP_Value('TranslationWithPosition', 'TranslationWithPosition', $position);

	}
}
?>
