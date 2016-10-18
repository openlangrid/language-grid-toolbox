<?php
require_once dirname(__FILE__).'/manager/language-manager.php';
require_once dirname(__FILE__).'/shop_answer_content.php';

class ShopAnswer {

	private $answerObject;

	private $qaAnswerId;
	private $qaQuestionId;

	private $mainExpression;
	private $subExpression;
	private $mainLanguage;
	private $subLanguage;
	private $shopAnswerId;	// shop_answer_id
	private $answerAvailable;// dispaly flag
	private $contents;

	static private $prefixedTableName;

	protected function __construct($answerObject) {
		$this -> answerObject = $answerObject;

		if (isset($answerObject['id'])) {
			$this -> qaAnswerId = $answerObject['id'];// qa_answer_id
		}
		if (isset($answerObject['qa_question_id'])) {
			$this -> qaQuestionId = $answerObject['qa_question_id'];// qa_question_id
		}

		if (isset($answerObject['mainExpression'])) {
			$this -> mainExpression = $answerObject['mainExpression'];
		}
		if (isset($answerObject['mainLanguage'])) {
			$this->mainLanguage = $answerObject['mainLanguage'];
		}
		if (isset($answerObject['subLanguage'])) {
			$this->subLanguage = $answerObject['subLanguage'];
		}
		if (isset($answerObject['subExpression'])) {
			$this -> subExpression = $answerObject['subExpression'];
		}
		if (isset($answerObject['qa_answer_available'])) {
			$this -> answerAvailable = $answerObject['qa_answer_available'];
		}
		if (isset($answerObject['shop_answer_id'])) {
			$this -> shopAnswerId = $answerObject['shop_answer_id'];
		}
	}

	/**
	 * Returns prefixed table name.
	 * @param $xoopsDB
	 * @param string $tableName
	 * @return string
	 */
	protected static function actualTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB -> prefix($GLOBALS['mydirname'] . '_' . $tableName);
	}

	/**
	 * Set shopAnswerId.
	 * @param int $shopAnswerId
	 * @return void
	 */
	public function setShopAnswerId($shopAnswerId) {
		$this -> shopAnswerId = $shopAnswerId;
	}

	/**
	 * Returns shopAnswerId.
	 * @return int
	 */
	public function getShopAnswerId() {
		return $this -> shopAnswerId;
	}

	/**
	 * Returns qaQuestionId.
	 * @return int
	 */
	public function getQaQuestionId() {
		return $this -> qaQuestionId;
	}

	/**
	 * Set answer available value(display flag).
	 * @param int $answerAvailable
	 * @return void
	 */
	public function setAnswerAvailable($answerAvailable) {
		$this -> answerAvailable = $answerAvailable;
	}

	/**
	 * Returns answer available value(display flag).
	 * @return int
	 */
	public function getAnswerAvailable() {
		return $this -> answerAvailable;
	}

	/**
	 * Returns answer text(main expression)
	 * @return string
	 */
	public function getMainExpression() {
		return $this -> mainExpression;
	}

	/**
	 * Returns answer text(sub expression)
	 * @return string
	 */
	public function getSubExpression() {
		return $this -> subExpression;
	}

	/**
	 * Set contents(image/Google Map URL).
	 * @param object ShopAnswerContent instances
	 * @return void
	 */
	public function setContents($contents) {
		$this -> contents = $contents;
	}

	/**
	 * Returns contents(image/Google Map URL).
	 * @return array
	 */
	public function getContents() {
		return $this -> contents;
	}

	public function getBodyForSelectedLanguage() {
		return CommonUtil::getLanguageNameByTag($languageManager->getSelectedLanguage());
	}

	public function getSelectedLanguage() {
		return $this->mainLanguage;
	}

	public function getAnswer() {
		if ($this -> answerObject) {
			return $this -> answerObject;
		}
		return null;
	}

	public function getQaAnswerId() {
		return $this -> qaAnswerId;
	}

	/**
	 * Caches and returns array contains ShopAnswerContent instances represent image or Google Map.
	 * @return array
	 */
	private function cacheContents() {
		if (!is_array($this->getContents())) {
			$this->setContents(ShopAnswerContent::findAllByAnswerId($this -> shopAnswerId, 'ASC'));
		}
		return $this->getContents();
	}

	/**
	 * Returns first content(image/Google Map)
	 * @return ShopAnswerContent object of ShopAnswerContent or null
	 */
	public function getFirstContent() {
		$contents = $this->cacheContents();
		return isset($contents[0]) ? $contents[0] : null;
	}

	/**
	 * Returns second content(image/Google Map)
	 * @return ShopAnswerContent object of ShopAnswerContent or null
	 */
	public function getSecondContent() {
		$contents = $this->cacheContents();
		return isset($contents[1]) ? $contents[1] : null;
	}

	/**
	 * Check this answer has contents.
	 * @return boolean
	 */
	public function hasContent() {
		$result = false;
		if ($this -> shopAnswerId) {
			$shopAnswerContents = ShopAnswerContent::findAllByAnswerId($this -> shopAnswerId, 'DESC');
			foreach ($shopAnswerContents as $shopAnswerContent) {
				$result = true;
				break;
			}
		}
		return $result;
	}

	/**
	 * Check this answer has first content.
	 * @return boolean
	 */
	public function hasFirstContent() {
		$result = false;
		if ($this -> shopAnswerId) {
			$shopAnswerContents = ShopAnswerContent::findAllByAnswerId($this -> shopAnswerId, 'ASC');
			if (count($shopAnswerContents) >= 1) {
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * Check this answer has second content.
	 * @return boolean
	 */
	public function hasSecondContent() {
		$result = false;
		if ($this -> shopAnswerId) {
			$shopAnswerContents = ShopAnswerContent::findAllByAnswerId($this -> shopAnswerId, 'DESC');
			if (count($shopAnswerContents) >= 2) {
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * Returns available property value
	 * @return boolean
	 */
	public function isAvailable() {
		$returnBln = true;

		if ($this ->answerAvailable == 1 ) {
			$returnBln = false;
		}
		return $returnBln;
	}

	static public function createFromAnswer($answer) {
		return new ShopAnswer($answer);
	}

	/**
	 * Compare arugument,and update shop_answers table with
	 * matching qa_answer_id.
	 * @param array ShopAnswer instace for comparing
	 * @return int of update count
	 */
	public function compareAndUpdateAnswers($answersForComarison) {
		$xoopsDB =& Database::getInstance();
		$result = 0;

		$qaAnswerId   = $this -> getQaAnswerId();
		$newAvailable = $this -> getAnswerAvailable();

		foreach ($answersForComarison as $answerForComparison) {

			$preAvailable = $answerForComparison -> getAnswerAvailable();

			// compare qa_answer_id with each other
			if (strcmp($qaAnswerId, $answerForComparison -> getQaAnswerId()) == 0) {

				if ($preAvailable == 0 && $newAvailable == 1) {

					if (self::isSelectableById($xoopsDB, $qaAnswerId) == false) {
						$result &= $this -> insertShopAnswer($xoopsDB, $newAvailable);
					} else {
						$result &= $this -> updateShopAnswer($xoopsDB, $newAvailable);
					}
				} else if ($preAvailable == 0 && $newAvailable == 0) {

					if (self::isSelectableById($xoopsDB, $qaAnswerId) == false) {
						$result &= $this -> insertShopAnswer($xoopsDB, $newAvailable);
					}
				} else if ($preAvailable == 1 && $newAvailable == 0) {
					$result &= $this -> updateShopAnswer($xoopsDB, $newAvailable);
				} else if ($preAvailable == 1 && $newAvailable == 1) {
					// do nothing.
				}
			}
		}
		return $result;
	}

	/**
	 * Find only shop_question table data by questionId.
	 * @param object $xoopsDB
	 * @param int $questionId
	 * @return boolean if table exists then returns true
	 */
	static public function isSelectableById($xoopsDB, $qaAnswerId) {
		$sql  = " SELECT ";
		$sql .= "   * ";
		$sql .= " FROM ";
		$sql .=     self::actualTableName("shop_answers");
		$sql .= " WHERE ";
		$sql .= "   qa_answer_id = '%s' ";

		$selectSql = sprintf($sql, mysql_real_escape_string($qaAnswerId));

		if ($resultSet = $xoopsDB -> query($selectSql)) {
			$record = $xoopsDB -> fetchArray($resultSet);
			return $record != null ? true : false;
		}
	}
	
	public function select($xoopsDB, $qaAnswerId) {
		$sql  = " SELECT ";
		$sql .= "   * ";
		$sql .= " FROM ";
		$sql .=     self::actualTableName("shop_answers");
		$sql .= " WHERE ";
		$sql .= "   qa_answer_id = '%s' ";

		$selectSql = sprintf($sql, mysql_real_escape_string($qaAnswerId));
		$record;
		if ($resultSet = $xoopsDB -> query($selectSql)) {
			$record = $xoopsDB -> fetchArray($resultSet);
		}
		return $record;
	}

	/**
	 * Insert data to shop_answers table.
	 * @param object $xoopsDB
	 * @param boolean 0 or 1
	 * @return boolean
	 */
	public function insertShopAnswer($xoopsDB, $availableFlag = 0) {
		if ($this -> qaQuestionId && $this -> qaAnswerId) {
			$sql  = " INSERT INTO ";
			$sql .=        self::actualTableName("shop_answers ");
			$sql .= "      (qa_question_id, qa_answer_id, available) ";
			$sql .= " VALUES ";
			$sql .= "       ('%d', '%s', '%d')";

			$insertSql = sprintf($sql,
									mysql_real_escape_string($this -> qaQuestionId),
									mysql_real_escape_string($this -> qaAnswerId),
									mysql_real_escape_string($availableFlag)
									);

			$result = $xoopsDB -> queryF($insertSql);
			return $result;
		}
	}

	/**
	 * Update data to shop_answers table.
	 * @param $xoopsDB
	 * @param boolean 0 or 1
	 * @return boolean
	 */
	private function updateShopAnswer($xoopsDB, $availableFlag = 0) {
		if ($this -> qaAnswerId) {
			$sql  = " UPDATE ";
			$sql .=        self::actualTableName("shop_answers ");
			$sql .= " SET ";
			$sql .= "      available = '%d' ";
			$sql .= " WHERE ";
			$sql .= "      qa_answer_id = '%s' ";

			$updateSql = sprintf($sql,
									mysql_real_escape_string($availableFlag),
									mysql_real_escape_string($this -> qaAnswerId)
									);

			$result = $xoopsDB -> queryF($updateSql);
			return $result;
		}
	}

	/**
	 * delete content
	 * @return boolean
	 */
	public function deleteContent() {
		$result = 0;
		if ($this -> qaAnswerId) {
			$shopAnswerContents = ShopAnswerContent::findAllByAnswerId($this -> qaAnswerId);
			foreach ($shopAnswerContent as $shopAnswerContent) {
				$result &= $shopAnswerContent -> delete();
			}
		}
		return result;
	}

	/**
	 * insert a content
	 * @return boolean
	 */
	public function insertContent() {
		$result = 0;
		if ($this -> contents) {
			foreach ($this -> contents as $content) {
				$shopAnswerContent = ShopAnswerContent::createFromParams($content);
				$result &= $shopAnswerContent -> insert();
			}
		}
		return $result;
	}
}

?>