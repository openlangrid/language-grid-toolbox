<?php
require_once dirname(__FILE__).'/shop_question.php';
require_once dirname(__FILE__).'/pager.php';

class ShopAnswerList extends Pager {

	const CATEGORY_ID   = 'categoryId';
	const RESOURCE_NAME = 'resourceName';

	private $sum;

	/**
	 * Constructor
	 * @param array $answers contains ShopAnswer instances
	 * @param int $page [optional]
	 */
	protected function __construct(array $answers, $page = 1) {
		parent::__construct($answers, $page - 1, 1);
	}

	/**
	 * Finds answers and Returns ShopAnswerList.
	 * @param ShopQuestion $question
	 * @param int $page
	 * @return ShopAnswerList
	 */
	public static function findByQuestion(ShopQuestion $question, $page = 1) {
		$answers = $question->getAnswers();
		if (!is_array($answers)) {
			$answers = array();
		}

		$count = count($answers);
		if (!is_numeric($page) || !$page) {
			$page = 1;
		} elseif ($page > $count) {
			$page = $count;
		}

		// exclude not available
		foreach ($answers as $key => $answer) {
			if (!$answer->isAvailable()) {
				unset($answers[$key]);
			}
		}
		return new ShopAnswerList(array_merge($answers), $page);
	}

	/**
	 * @return ShopAnswer
	 */
	public function getCurrentAnswer() {
		if (!$this->getSum()) {
			return null;
		}

		$answers = $this->getEntities();
		return $answers[$this->page - 1];
	}

	/**
	 * @return int the number of containing answers
	 */
	public function getSum() {
		if(is_null($this -> sum)) {
			$this->sum = count($this->getEntities());
		}
		return $this -> sum;
	}
}
