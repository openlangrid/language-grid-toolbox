<?php

require_once dirname(__FILE__).'/../workflow/MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php';

class BestChoiceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch
	extends MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch
{

	public function setContext($context) {
		parent::setContext($context);
		$this->createClient('BestTranslationSelection');
	}
	
	/**
	 * EBMTであるかどうか
	 * @param <type> $serviceId
	 * @return <type>
	 */
	protected function isEbmt($serviceId) {
		return ($serviceId == 'kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
	}

    /**
     * EBMTがあるかどうか
     */
    protected function hasEbmt() {
		return $this->context->isEbmt();
    }

    /**
     * 最良選択であるかどうか
     */
    protected function isBestChoice() {
		return $this->context->isBestChoice();
	}

	/**
	 * 辞書を持ってるかどうか
	 * @return <type>
	 */
	protected function hasDictionary() {
		$set = $this->context->getBindings();
		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		$binds = $execs[0]->getTranslationBinds();
		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 1:
				case 2:
					return true;

			}
		}
		return false;
	}

	/**
	 * EBMTトークンを返す
	 * @param <type> $ebmt
	 * @param <type> $parallelText
	 * @param <type> $sourceLang
	 * @param <type> $targetLang
	 * @return <type>
	 */
	protected function getEbmtToken($ebmt, $parallelText) {
		$sourceLang = $this->context->getSourceLang();
		$targetLang = $this->context->getTargetLang();
		$adapter = DaoAdapter::getAdapter();
		$dao = $adapter->getServiceGridEbmtLearningDaoImpl();
		$result = $dao->queryForSearchByName($ebmt, $parallelText, $sourceLang, $targetLang);

		if (!$result) {
			$result = $dao->queryForSearchByName($ebmt, $parallelText, $targetLang, $sourceLang);
		}
		
		if ($result) {
			return $result->getToken();
		} else {
			debugLog("### token not found ### ".$ebmt.':'.$parallelText);
			return '';
		}
	}

	/**
	 * もしEBMTならトークンをつけて返す
	 * @param <type> $translator
	 * @param <type> $parallelTexts
	 */
	protected function doFilterServiceId($translator, $parallelTexts) {
		if ($this->isEbmt($translator)) {
			$tokens = array();
			foreach ($parallelTexts as $p) {
				$token = $this->getEbmtToken($translator, $p);
				if ($token) {
					$tokens[] = $token;
				}
			}
			if (!empty($tokens)) {
				$tokens = array_unique($tokens);
				$translator .= '?token='.implode(',', $tokens);
			}
		}

		return $translator;
	}

	protected function _makeBinding() {
        if ($this->isBestChoice()) {
            // Best Choice
			if (!$this->hasDictionary()) {
				// 辞書なし
	            $this->_makeBindingBestChoiceNoDictionary();
			} else if ($this->hasEbmt()) {
				// 辞書ありEBMTあり
	            $this->_makeBindingBestChoiceWithEbmt();
			} else {
				// 辞書ありEBMTなし
	            $this->_makeBindingBestChoice();
			}
        } else {
            // Atomic EBMT
            $this->_makeBindingAtomicEbmt();
        }
	}

	protected function _makeBindingAtomicEbmt() {
		$set = $this->context->getBindings();

		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		$binds = $execs[0]->getTranslationBinds();

		$parallelTexts = array();
		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 0: // Translator
					$translator = $bind->getBindValue();
					break;
				case 4: // Parallel
					$parallelTexts[] = $bind->getBindValue();
					break;
				case 5: // Translation Template
					$parallelTexts[] = $bind->getBindValue();
					break;
			}
		}

		if (!isset($translator)) {
			$translator = $execs[0]->getServiceId();
		}

		$translator = $this->doFilterServiceId($translator, $parallelTexts);
		$this->createClient($translator);
	}

	protected function _makeBindingBestChoiceNoDictionary() {
		$bindNodes = array();

		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$set = $this->context->getBindings();

		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();

		$dictCount = 0;
		$dictionaries = array();
		$binds = $execs[0]->getTranslationBinds();
		$morphologicalanalyzer = null;
		$similarityCalculation = null;

		$translators = array();
		$parallelTexts = array();

		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 0: //Translation
					$translators[] = $bind->getBindValue();
					break;
				case 4: // Parallel
					$parallelTexts[] = $bind->getBindValue();
					break;
				case 5: // Translation Template
					$parallelTexts[] = $bind->getBindValue();
					break;
				case 6: //SimilarityCalcuration
					$similarityCalculation = $bind->getBindValue();
					break;
			}
		}

		if (empty($translators)) {
			$translators[] = $execs[0]->getServiceId();
		}

		for ($i = 0; $i < count($translators); $i++) {
			$bindNodes[] = sprintf($bindTemp, '', 'SimilarityCalculationForTranslationPL'.($i+1), $similarityCalculation);
			$translator = $this->doFilterServiceId($translators[$i], $parallelTexts);
			$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL'.($i+1), $translator);
		}

		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}

	protected function _makeBindingBestChoice() {
		$bindNodes = array();
		$childNodes = array();

		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$set = $this->context->getBindings();

		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();

		$dictCount = 0;
		$dictionaries = array();
		$binds = $execs[0]->getTranslationBinds();
		$morphologicalanalyzer = null;
		$similarityCalculation = null;

		$translators = array();

		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 0: //Translation
					$translators[] = $bind->getBindValue();
					break;
				case 1: //Global
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 2: //Local
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 6: //SimilarityCalcuration
					$similarityCalculation = $bind->getBindValue();
					break;
				case 9: //Morphological Analyzer
					$morphologicalanalyzer = $bind->getBindValue();
					break;
			}
		}

		if (empty($translators)) {
			$translators[] = $execs[0]->getServiceId();
		}

		switch ($dictCount) {
			case 0:		// Bind for No Dictionary
				$childNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, 'AbstractBilingualDictionaryWithLongestMatchSearch');
				break;
			case 1:		// Bind for One Dictionary
				$childNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, $dictionaries[0]);
				break;
			default:	// Bind for Any Dictionaries
				$dictionaryBinding = array();
				foreach ($dictionaries as $dictionary) {
					if (!empty($dictionary)) {
						$idx = count($dictionaryBinding) + 1;
						$dictionaryBinding[] =
							sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.$idx, $dictionary);
					}
				}
				$childNodes[] = sprintf($bindTemp, implode(',', $dictionaryBinding), 'BilingualDictionaryWithLongestMatchSearchPL'
					, $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
				break;
		}

		$childNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $this->getEndpoint($morphologicalanalyzer));
		$childNodes[] = $bindTemp;
		$childTmp = implode(',', $childNodes);

		for ($i = 0; $i < count($translators); $i++) {
			$childlen = sprintf($childTmp, '', 'TranslationPL', $translators[$i]);
			$bindNodes[] = sprintf($bindTemp, $childlen, 'ForwardTranslationWithTemporalDictionaryPL'.($i+1), $this->getGridId() . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
			$bindNodes[] = sprintf($bindTemp, '', 'SimilarityCalculationForTranslationWithTemporalDictionaryPL'.($i+1), $similarityCalculation);
		}

		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}

	protected function _makeBindingBestChoiceWithEbmt() {
		$bindNodes = array();
		$childNodes = array();

		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$set = $this->context->getBindings();

		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();

		$dictCount = 0;
		$dictionaries = array();
		$binds = $execs[0]->getTranslationBinds();
		$morphologicalanalyzer = null;
		$similarityCalculation = null;

		$translators = array();
		$parallelTexts = array();

		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 0: //Translation
					$translators[] = $bind->getBindValue();
					break;
				case 1: //Global
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 2: //Local
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 4: //ParallelText
					$parallelTexts[] = $bind->getBindValue();
					break;
				case 5: //TranslationTemplate
					$parallelTexts[] = $bind->getBindValue();
					break;
				case 6: //SimilarityCalcuration
					$similarityCalculation = $bind->getBindValue();
					break;
				case 9: //Morphological Analyzer
					$morphologicalanalyzer = $bind->getBindValue();
					break;
			}
		}

		if (empty($translators)) {
			$translators[] = $execs[0]->getServiceId();
		}

		switch ($dictCount) {
			case 0:		// Bind for No Dictionary
				$childNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, 'AbstractBilingualDictionaryWithLongestMatchSearch');
				break;
			case 1:		// Bind for One Dictionary
				$childNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, $dictionaries[0]);
				break;
			default:	// Bind for Any Dictionaries
				$dictionaryBinding = array();
				foreach ($dictionaries as $dictionary) {
					if (!empty($dictionary)) {
						$idx = count($dictionaryBinding) + 1;
						$dictionaryBinding[] =
							sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.$idx, $dictionary);
					}
				}
				$childNodes[] = sprintf($bindTemp, implode(',', $dictionaryBinding), 'BilingualDictionaryWithLongestMatchSearchPL'
				, $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
				break;
		}

		$serviceIds = array();
		foreach ($translators as $t) {
			if ($this->isEbmt($t)) {
				foreach ($parallelTexts as $p) {
					$token = $this->getEbmtToken($t, $p);
					$serviceIds[] = $t.'?token='.$token;
				}
			} else {
				$serviceIds[] = $t;
			}
		}

		$childNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $this->getEndpoint($morphologicalanalyzer));
		$childNodes[] = $bindTemp;
		$childTmp = implode(',', $childNodes);

		$tc = 1;
		$pc = 1;
		for ($i = 0; $i < count($translators); $i++) {
			if (!$this->isEbmt($translators[$i])) {
				// EBMTでない
				$childlen = sprintf($childTmp, '', 'TranslationPL', $translators[$i]);
				$bindNodes[] = sprintf($bindTemp, $childlen, 'ForwardTranslationWithTemporalDictionaryPL'.($tc), $this->getGridId() . ':TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
				$bindNodes[] = sprintf($bindTemp, '', 'SimilarityCalculationForTranslationWithTemporalDictionaryPL'.($tc), $similarityCalculation);
				$tc++;
			} else {
				// EBMTである
				$tokens = array();
				foreach ($parallelTexts as $p) {
					$token = $this->getEbmtToken($translators[$i], $p);
					if ($token) {
						$tokens[] = $token;
					}
				}
				$serviceId = $translators[$i];
				if (!empty($tokens)) {
					$tokens = array_unique($tokens);
					$serviceId .= '?token='.implode(',', $tokens);
				}
				$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL'.($pc), $serviceId);
				$bindNodes[] = sprintf($bindTemp, '', 'SimilarityCalculationForTranslationPL'.($pc), $similarityCalculation);
				$pc++;
			}
		}

		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}
}
?>