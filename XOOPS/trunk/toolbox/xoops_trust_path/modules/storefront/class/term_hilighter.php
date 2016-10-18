<?php
class TermHighlighter {

	const MAIN = 0;
	const SUB = 1;

	/**
	 * @var BilingualManager
	 */
	protected $languageManager;

	/**
	 * resource Name (glossary name)
	 * @var string
	 */
	protected $resourceName;
	
	/**
	 * @var ShopAnswer
	 */
	protected $answer;

	/**
	 * @var array
	 */
	protected $usedTerms = array();

	/**
	 * Constructor.
	 * @param BilingualManager $languageManager
	 * @param ShopAnswer $answer
	 */
	public function __construct(BilingualManager $languageManager, $resourceName, ShopAnswer $answer) {
		$this->languageManager = $languageManager;
		$this->resourceName = $resourceName;
		$this->answer = $answer;
	}

	/**
	 * @param string $description
	 * @param string $workingLanguage
	 * @return string
	 */
	protected function highlight($description, $workingLanguage) {
		$str = htmlspecialchars($description);

		$termList = TermList::findAll($this->languageManager, $this->resourceName);
		$terms = $termList->getTermsIn($workingLanguage);

		foreach ($terms as $term) {
			/* @var $term Term */

			$tmp = $str;
			$str = preg_replace("/({$term->getTerm()})/i", '<a href="#" class="term_'.$term->getRecordId().'_'.$workingLanguage.' termLink">$1</a>', $str);
			if ($str != $tmp) {
				$this->usedTerms[$term->getLanguage()][] = $term;
			}
		}

		return $str;
	}

	/**
	 * @return string
	 */
	public function getMainExpression() {
		return $this->highlight($this->answer->getMainExpression(), $this->languageManager->getSelectedLanguage());
	}

	/**
	 * @return string
	 */
	public function getSubExpression() {
		return $this->highlight($this->answer->getSubExpression(), $this->languageManager->getSelectedSubLanguage());
	}

	/**
	 * @return array
	 */
	public function getUsedTerms() {
		return $this->usedTerms;
	}
}
