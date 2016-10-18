<?php
class TermList {

	/**
	 * Contains Term instances.
	 * @var array
	 */
	protected $terms;

	/**
	 * Constructor.
	 * @param array $terms
	 */
	public function __construct(array $terms) {
		$this->terms = $terms;
	}

	/**
	 * Finds all glossary records.
	 * @param BilingualManager $languageManager
	 * @param string $resourceName
	 * @return TermList
	 */
	public static function findAll(BilingualManager $languageManager, $resourceName) {
		$glossaries = GlossaryList::findSelectedDefaultGlossaryDictionaries($resourceName);
		$glossaryClient = new GlossaryClient();
		$terms = array(
			$languageManager->getSelectedLanguage() => array(),
			$languageManager->getSelectedSubLanguage() => array()
		);
		$langs = array_keys($terms);

		// for each glossaries
		foreach ($glossaries as $glossary) {
			$response = $glossaryClient->getAllRecords($glossary);
			$records = $response['contents'];

			// for each glossary records
			foreach ($records as $record) {
				/* @var $record ToolboxVO_Glossary_GlossaryRecord */

				// for each languages of terms in glossary record
				foreach ($record->term as $term) {
					/* @var $term ToolboxVO_Resource_Expression */

					$lang = $term->language;
					if (!in_array($lang, $langs)) {
						continue;
					}

					$termExpression = htmlspecialchars($term->expression);
					if (!$termExpression) {
						continue 2;
					}

					// search definitions
					$definitions = array();
					foreach ($record->definition as $definitionObject) {
						/* @var $definitionObject ToolboxVO_QA_Answer */

						// for each definitions
						foreach ($definitionObject->expression as $definitionsForEachLanguages) {
							/* @var $definitionsForEachLanguages ToolboxVO_Resource_Expression */

							if ($definitionsForEachLanguages->language != $lang) {
								continue;
							}

							$expression = htmlspecialchars($definitionsForEachLanguages->expression);
							if (strlen($expression) < 1) {
								continue 2;
							}
							$definitions[] = $expression;
						}
					}

					self::addTerm($terms[$lang], array(
						'recordId' => $record->id,
						'language' => $term->language,
						'term' => $termExpression,
						'definitions' => $definitions
					));
				}
			}
		}

		return new TermList($terms);
	}

	/**
	 * @param array $terms
	 * @param array $params
	 * @return void
	 */
	protected static function addTerm(array &$terms, array $params) {
		$found = false;

		foreach ($terms as &$term) {
			/* @var $term Term */

			if ($term->is($params['term'])) {
				$term->addDefinitions($params['definitions']);
				$found = true;
				break;
			}
		}

		if (!$found) {
			$terms[] = Term::createFromParams($params);
		}
	}

	public function getTermsIn($language) {
		return isset($this->terms[$language]) ? $this->terms[$language] : $this->terms['en'];
	}
}
