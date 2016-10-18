<?php
/**
 * The wrapper of ToolboxVO_Glossary_GlossaryRecord.
 * Contains a term and its definition(s).
 */
class Term {

	/**
	 * @var array
	 */
	protected $record;

	/**
	 * Constructor.
	 * @param array $params
	 */
	protected function __construct(array $record) {
		$this->record = $record;
	}

	/**
	 * Creates and returns a Term instance.
	 * @param array $params
	 * @return Term
	 */
	public static function createFromParams(array $params) {
		return new Term($params);
	}

	public function addDefinitions(array $definitions) {
		$this->record['definitions'] = array_merge($this->getDefinitions(), $definitions);
	}

	/**
	 * Checkes if represents given string.
	 * @param string $term
	 * @return bool
	 */
	public function is($term) {
		$is = false;

		if ($this->getTerm() == $term) {
			$is = true;
		}

		return $is;
	}

	public function getRecordId() {
		return $this->record['recordId'];
	}

	public function getTerm() {
		return $this->record['term'];
	}

	public function getLanguage() {
		return $this->record['language'];
	}

	/**
	 * @return array
	 */
	public function getDefinitions() {
		return $this->record['definitions'];
	}
}
