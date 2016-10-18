<?php

// api
require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');

require_once dirname(__FILE__).'/manager/language-manager.php';
require_once dirname(__FILE__).'/shop_answer.php';


class ShopQuestion {

	private $resourceName;
	private $lang;
	private $cache = array();

	private $qaRecord;
	private $questionAvailable;// dispaly flag
	private $hasDisplayAns; // Boolean flag what be True if include available answer.

	static private $prefixedTableName;

	public function __construct($qaRecord, $lang) {

		$this->hasDisplayAns = False;
		$this->lang = $lang;

		$mainLanguage = $lang->getSelectedLanguage();
		$subLanguage = $lang->getSelectedSubLanguage();

		if ($qaRecord) {
			$this -> qaRecord = $qaRecord;

			$shopAnswers = array();
			foreach ($this -> qaRecord -> answers as $answer) {

				if ($answer instanceof ShopAnswer) {
					return;
				}

				$mainExpression = null;
				$subExpression  = null;
				$id = $answer -> id;

				foreach ($answer -> expression as $resource) {

					// Main language setting
					if (strcmp($resource -> language, $mainLanguage) == 0) {
						$mainExpression = $resource -> expression;
					}

					// Sub language setting
					if (strcmp($resource -> language, $subLanguage) == 0) {
						$subExpression  = $resource -> expression;
					}
				}

				$shopAnswer = ShopAnswer::createFromAnswer(array("mainExpression" => $mainExpression,
																 "subExpression"  => $subExpression,
																 "mainLanguage" => $mainLanguage,
																 "subLanguage" => $subLanguage,
																 "id" => $id,));

				if ((!$this->hasDisplayAns) && $shopAnswer->isAvailable()){
					$this->hasDisplayAns = true;
				}

				array_push($shopAnswers, $shopAnswer);
			}

			// changing property, from answers to showpAnswer instances
			$this -> qaRecord -> answers = $shopAnswers;
		}
	}

	/**
	 * Returns prefixed table name.
	 * @param $xoopsDB
	 * @param string $tableName
	 * @return string
	 */
	static public function actualTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix($GLOBALS['mydirname'] . '_' . $tableName);
	}

	/**
	 * Returns questionId(qaQuetionId).
	 * @return int
	 */
	public function getId() {
		if ($this -> qaRecord) {
			return $this -> qaRecord -> id;
		}
		return null;
	}

	public function setResourceName($resourceName) {
		$this -> resourceName = $resourceName;
	}

	public function getResourceName() {
		return $this -> resourceName;
	}

	/**
	 * Returns question available value(display flag).
	 * @return int
	 */
	public function getQuestionAvailable() {
		return $this -> questionAvailable;
	}

	/**
	 * Set question available value(display flag).
	 * @param int $questionAvailable
	 * @return void
	 */
	public function setQuestionAvailable($questionAvailable) {
		$this -> questionAvailable = $questionAvailable;
	}

	/**
	 * Returns question.
	 * @return string
	 */
	public function getQuestion() {

		if ($this -> qaRecord -> question) {
			foreach ($this -> qaRecord -> question as $resource) {
				if (strcmp($resource -> language, $this->getSelectedLanguage()) == 0) {
					return $resource -> expression;
				}
			}
		}
		return null;
	}

	/**
	 * Returns question(main expression).
	 * @return string
	 */
	public function getMainExpression() {

		if ($this -> qaRecord -> question) {
			foreach ($this -> qaRecord -> question as $resource) {
				if (strcmp($resource -> language, $this->getSelectedLanguage()) == 0) {
					return $resource -> expression;
				}
			}
		}
		return null;
	}


	/**
	 * Returns question(sub expression).
	 * @return string
	 */
	public function getSubExpression() {

		if ($this -> qaRecord -> question) {
			foreach ($this -> qaRecord -> question as $resource) {

				if (strcmp($resource -> language, $this->getSelectedSubLanguage()) == 0) {

					return $resource -> expression;
				}
			}
		}
		return null;
	}

	/**
	 * Returns categoryId
	 * @return int
	 */
	public function getCategoryIds() {
		if ($this -> qaRecord) {
			return $this -> qaRecord -> categoryIds;
		}
		return null;
	}

	/**
	 * Returns language list
	 * @return array of language code string
	 */
	public function getLanguageList() {
		$languageMap = CommonUtil::getLanguageNameMap();
		$languageList = array();
		if ($this -> qaRecord -> question) {
			foreach ($this -> qaRecord -> question as $resource) {
				if ($resource -> language) {

					$languageName = $languageMap[$resource -> language];
					array_push($languageList, array($resource -> language, $languageName));
				}
			}
		}
		return $languageList;
	}

	protected function getCategoriesInternal() {
		if (@$this->cache['ShopCategories']) {
			return $this->cache['ShopCategories'];
		}
		$categories = array();

		if (is_array(@$this -> qaRecord -> categoryIds)) {
			foreach ($this -> qaRecord -> categoryIds as $categoryId) {
				$categories[] = ShopCategory::findById($categoryId, $this->lang);
			}
		}

		$this->cache['ShopCategories'] = $categories;
		return $categories;
	}

	/**
	 * Returns category name(main expression) by $categoryId.
	 * @return array of string
	 */
	public function getCategoryNamesByMainExpression() {
		$names = array();

		foreach ($this->getCategoriesInternal() as $category) {
			/* @var $category ShopCategory */
			if ($category) {
				$names[] = $category -> getMainExpression();
			}
		}
		return count($names) > 0 ? implode(", ", $names) : "--";
	}

	/**
	 * Returns category name(sub expression) by $categoryId.
	 * @return array of string
	 */
	public function getCategoryNamesBySubExpression() {
		$names = array();
		foreach ($this->getCategoriesInternal() as $category) {
			/* @var $category ShopCategory */
			if ($category) {
				$names[] = $category -> getSubExpression();
			}
		}
		return count($names) > 0 ? implode(", ", $names) : "--";
	}

	private function getNameForLanguage($languageCode) {
		return $selectedLanguageName = CommonUtil::getLanguageNameByTag($languageCode);
	}

	public function getNameForSelectedLanguage() {
		return $this -> getNameForLanguage($this -> getSelectedLanguage());
	}

	public function getSelectedLanguage() {
		return $this->lang->getSelectedLanguage();
	}

	public function getSelectedSubLanguage() {
		return $this->lang->getSelectedsubLanguage();
	}

	/**
	 * Returns ShopAnswer instances array.
	 * @return array of ShopAnswer instances
	 */
	public function getAnswers() {
		if ($this -> qaRecord -> answers) {
			return $this -> qaRecord -> answers;
		}
		return array();
	}

	/**
	 * Returns ShopAnswer instances count.
	 * @return count of ShopAnswer instances
	 */
	public function countAnswers() {
		if ($this -> qaRecord -> answers) {
			return count($this -> qaRecord -> answers);
		}
		return 0;
	}

	/**
	 * Returns Available shopAnswer instances count.
	 * @return count of ShopAnswer instances
	 */
	public function countAvailableAnswers() {
		$counter = 0;
		if ($this -> qaRecord -> answers) {
			foreach ($this -> qaRecord -> answers as $answer) {
				$counter += $answer -> isAvailable() ? 1 : 0;
			}
		}
		return $counter;
	}

	/**
	 * Returns ShopAnswer instances(display flag is true) array .
	 * @return array of ShopAnswer instances
	 */
	public function getAnswersByAvailable() {
		$results = array();
		foreach ($this -> getAnswers() as $answer) {
			if ($answer -> isAvailable()) {
				array_push($results, $answer);
			}
		}
		return $results;
	}

	/**
	 * Returns first element of ShopAnswer instances.
	 * @return object of ShopAnswer
	 */
	public function getFirstAnswer() {
		if ($this -> qaRecord -> answers) {
			foreach ($this -> qaRecord -> answers as $shopAnswer) {
				return $shopAnswer;
			}
		}
		return null;
	}

	public function isAvailable() {

		$returnBln = true;

		if ($this ->questionAvailable == 1 ) {
			$returnBln = false;
		}
		return $returnBln;
	}

	public function hasAnswer(){
		return $this->hasDisplayAns;

	}

	/**
	 * Find only shop_question table data by questionId.
	 * @param object $xoopsDB
	 * @param int $questionId
	 * @return boolean if table exists then returns true
	 */
	static public function isSelectableById($xoopsDB, $questionId) {
		$sql  = " SELECT ";
		$sql .= "   * ";
		$sql .= " FROM ";
		$sql .=     self::actualTableName("shop_questions");
		$sql .= " WHERE ";
		$sql .= "   qa_question_id = '%d' ";

		$selectSql = sprintf($sql, mysql_real_escape_string($questionId));

		if ($resultSet = $xoopsDB -> query($selectSql)) {
			$record = $xoopsDB -> fetchArray($resultSet);
			return $record != null ? true : false;
		}
	}

	public function select($xoopsDB, $questionId) {
		$sql  = " SELECT ";
		$sql .= "   * ";
		$sql .= " FROM ";
		$sql .=     self::actualTableName("shop_questions");
		$sql .= " WHERE ";
		$sql .= "   qa_question_id = '%d' ";
		$sql .= " ORDER BY ";
		$sql .= "   qa_question_id DESC ";

		$selectSql = sprintf($sql, mysql_real_escape_string($questionId));
		$record;
		if ($resultSet = $xoopsDB -> query($selectSql)) {
			$record = $xoopsDB -> fetchArray($resultSet);
		}
		return $record;
	}



	/**
	 * Insert data to shop_questions table.
	 * @param object $xoopsDB
	 * @param boolean 0 or 1
	 * @return boolean
	 */
	public function insertShopQuestion($xoopsDB, $availableFlag = 0) {
		if ($this -> qaRecord -> id) {
			$sql  = " INSERT INTO ";
			$sql .=        self::actualTableName("shop_questions");
			$sql .= "      (qa_question_id, available) ";
			$sql .= " VALUES ";
			$sql .= "       ('%d', '%d')";

			$insertSql = sprintf($sql,
									mysql_real_escape_string($this -> qaRecord -> id),
									mysql_real_escape_string($availableFlag)
									);

			$result = $xoopsDB -> queryF($insertSql);
			return $result;
		}
	}

	/**
	 * Update data to shop_questions table.
	 * @param object $xoopsDB
	 * @param int qaQuestionId
	 * @param boolean 0 or 1
	 * @return boolean
	 */
	private function updateShopQuestion($xoopsDB, $qaQuestionId, $newAvailableFlag = 0) {
		if ($this -> qaRecord -> id) {
			$sql  = " UPDATE ";
			$sql .=        self::actualTableName("shop_questions");
			$sql .= " SET ";
			$sql .= "      available = '%d' ";
			$sql .= " WHERE ";
			$sql .= "      qa_question_id = '%d' ";

			$updateSql = sprintf($sql,
									mysql_real_escape_string($newAvailableFlag),
									mysql_real_escape_string($qaQuestionId)
									);

			$result = $xoopsDB -> queryF($updateSql);
			return $result;
		}
	}

	/**
	 * Compare arugument,and update shop_questions table with
	 * matching qa_question_id.
     *
	 * @param array $shopQuestions holding current database value for comparing
	 * @return int of update count
	 */
	public function compareAndUpdateQuestions($shopQuestionsForComparison) {
		$xoopsDB =& Database::getInstance();
		$result = 0;

		// update questions
		foreach ($shopQuestionsForComparison as $shopQuestion) {

			$newAvailable = $this -> getQuestionAvailable();
			$qaQuestionId = $this -> qaRecord -> id;
			$answers      = $this -> qaRecord -> answers;// array of ShopAnswer instance

			$preAvailable = $shopQuestion -> getQuestionAvailable();

			// compare qa_question_id with each other
			if (strcmp($qaQuestionId, $shopQuestion -> getId()) == 0) {

				if ($preAvailable == 0 && $newAvailable == 1) {

					if (self::isSelectableById($xoopsDB, $qaQuestionId) == false) {
						$result &= $this -> insertShopQuestion($xoopsDB, $newAvailable);
					} else {
						$result &= $this -> updateShopQuestion($xoopsDB, $qaQuestionId, $newAvailable);
					}
				} else if ($preAvailable == 0 && $newAvailable == 0) {

					if (self::isSelectableById($xoopsDB, $qaQuestionId) == false) {
						$result &= $this -> insertShopQuestion($xoopsDB, $newAvailable);
					}
				} else if ($preAvailable == 1 && $newAvailable == 0) {
					$result &= $this -> updateShopQuestion($xoopsDB, $qaQuestionId, $newAvailable);
				} else if ($preAvailable == 1 && $newAvailable == 1) {
					// do nothing.
				}

				// update answers
				if ($answers) {
					foreach ($answers as $answer) {
						$result &=
							$answer -> compareAndUpdateAnswers($shopQuestion -> getAnswers());
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Returns all categories.
	 * @param string $resourceName
	 * @return unknown_type
	 */
	public function getAllCategories($resourceName) {
		$shopCategories = array();
		$qaClient = new QAClient();
		$response = $qaClient -> getAllCategories($resourceName);
			if ($response && $response['status'] == 'OK') {
			foreach ($response['contents'] as $record) {
				array_push($shopCategories, $record);
			}
		}
		return $shopCategories;
	}
}

class ShopQuestionFactory {

	// paging size limit
	const LIMIT = 15;

	/**
	 * @var IBilingualManager
	 */
	private $languageManager;

	/**
	 * @param IBilingualManager $languageManager
	 * @return ShopQuestionFactory
	 */
	public function __construct($languageManager = null) {
		$this->languageManager = $languageManager ? $languageManager : new BilingualManager();
	}

	protected function createShopQuestion($qaRecord) {
		return new ShopQuestion($qaRecord,
					$this->languageManager);
	}

	public function createFromQARecord($QARecord) {
		return $this->createShopQuestion($QARecord);
	}

	/**
	 * Finds questions by resourceName.
	 * @param string $resourceName
	 * @return array of ShopQuestion instances
	 */
	public function findAll($resourceName) {
		$qaClient = new QAClient();

		$response = $qaClient -> getAllRecords($resourceName);


		$shopQuestions = array();
		if ($response && $response['status'] == 'OK') {
			foreach ($response['contents'] as $record) {
				$newShopQuestion = $this->createShopQuestion($record);
				$newShopQuestion -> setResourceName($resourceName);
				array_push($shopQuestions, $newShopQuestion);
			}
		}

		// merge shop_answer_id and display/non-display flag
		$qaQuestionIds = array();
		$qaAnswerIds   = array();
		foreach($shopQuestions as $shopQuestion) {
			array_push($qaQuestionIds, $shopQuestion -> getId());

			$shopAnswers = $shopQuestion -> getAnswers();
			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}
		}

		$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
		$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);
		$shopQuestions = $this->mergeQ($shopQuestions, $questions);
		$shopQuestions = $this->mergeA($shopQuestions, $answers);

		return $shopQuestions;
	}

	/**
	 * Find quesiton by questionId.
	 * @param $questionId
	 * @return object of ShopQuestion instance
	 */
	public function findById($questionId) {
		$qaClient = new QAClient();
		$response = $qaClient -> getRecord('unused', $questionId);

		$shopQuestion = null;

		if ($response && $response['status'] == 'OK') {

			$shopQuestion = $this->createShopQuestion($response['contents']);

			// merge shop_answer_id and display/non-display flag
			$qaQuestionIds = array();
			$qaAnswerIds   = array();

			array_push($qaQuestionIds, $shopQuestion -> getId());
			$shopAnswers = $shopQuestion -> getAnswers();

			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}

			$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
			$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);

			$shopQuestions = $this->mergeQ(array($shopQuestion), $questions);
			$shopQuestions = $this->mergeA(array($shopQuestion), $answers);
		}
		return $shopQuestion;
	}

	/**
	 * Find questions by categoryId.
	 * @param unknown_type $categoryId
	 * @param unknown_type $condtions
	 * @return array of ShopQuestion instances
	 */
	public function findAllByCategoryId($categoryId) {
		$qaClient = new QAClient();
		$response = $qaClient -> getRecordsByCategory('unsed', $categoryId);

		$shopQuestions = array();
		if ($response && $response['status'] == 'OK') {
			foreach ($response['contents'] as $record) {
				array_push($shopQuestions, $this->createShopQuestion($record));
			}
		}

		// merge shop_answer_id and display/non-display flag
		$qaQuestionIds = array();
		$qaAnswerIds   = array();
		foreach($shopQuestions as $shopQuestion) {
			array_push($qaQuestionIds, $shopQuestion -> getId());

			$shopAnswers = $shopQuestion -> getAnswers();
			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}
		}

		$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
		$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);
		$shopQuestions = $this->mergeQ($shopQuestions, $questions);
		$shopQuestions = $this->mergeA($shopQuestions, $answers);

		return $shopQuestions;
	}

	/**
	 * Find available information (display flag value) by $qaQuestionIds.
	 * @param array $qaQuestionIds
	 * @return array of Question instance including dispaly flag information
	 */
	static public function findAvailableByQaQuestionId($qaQuestionIds) {

		$questions = array();

		if (is_null($qaQuestionIds)) {
			return $qaQuestionIds;
		}

		$condition = implode(",", $qaQuestionIds);
		$condition = mysql_real_escape_string($condition);

		$sql  = " SELECT ";
		$sql .= "   shop_question_id, ";
		$sql .= "   qa_question_id, ";
		$sql .= "   available AS qa_question_available ";
		$sql .= " FROM ";
		$sql .=     ShopQuestion::actualTableName("shop_questions");
		$sql .= " WHERE ";
		$sql .= "   qa_question_id IN ({$condition}) ";

		$xoopsDB =& Database::getInstance();
		if($resultSet = $xoopsDB -> query($sql)) {
			while($record = $xoopsDB -> fetchArray($resultSet)) {
				array_push($questions, new Question($record));
			}
		}

		return $questions;
	}

	/**
	 * Find available information (display flag value) by $qaAnswerIds.
	 * @param array $qaAnswerIds
	 * @return array of Answer instance including dispaly flag information
	 */
	static public function findAvailableByQaAnswerId($qaAnswerIds) {

		$answers = array();
		if (is_null($qaAnswerIds)) {
			return $answers;
		}

		for ($i = 0; $i  < count($qaAnswerIds); $i++) {
			$escapedValue = mysql_real_escape_string($qaAnswerIds[$i]);
			$qaAnswerIds[$i] = "'" . $escapedValue ."'";
		}

		$condition = implode(",", $qaAnswerIds);

		$sql  = " SELECT ";
		$sql .= "   shop_answer_id, ";
		$sql .= "   qa_question_id, ";
		$sql .= "   qa_answer_id, ";
		$sql .= "   available AS qa_answer_available ";
		$sql .= " FROM ";
		$sql .=     ShopQuestion::actualTableName("shop_answers");
		$sql .= " WHERE ";
		$sql .= "   qa_answer_id IN ({$condition}) ";

		$xoopsDB =& Database::getInstance();
		if($resultSet = $xoopsDB -> query($sql)) {
			while($record = $xoopsDB -> fetchArray($resultSet)) {
				array_push($answers, new Answer($record));
			}
		}

		return $answers;
	}

	/**
	 * Find all questions with $offset.
	 * @param int $categoryId
	 * @param int $offset
	 * @param int $limit
	 * @return array of ShopQuestion instance
	 */
	public function findWithOffset($categoryId, $offset, $limit = self::LIMIT) {
		$results = array();

		$shopQuestions = $this->findAllByCategoryId($categoryId);

		if (count($shopQuestions) <= $offset + $limit) {
			$end = count($shopQuestions);
		} else {
			$end = $offset + $limit;
		}

		for ($i = $offset; $i < $end; $i++) {
			array_push($results, $shopQuestions[$i]);
		}
		return $results;
	}

	/**
	 * Find all questions with $offset.
	 * @param int $categoryId
	 * @param int $offset
	 * @param int $limit
	 * @return array of ShopQuestion instance
	 */
	public function findByResorceNameWithOffset($resourceName, $offset, $limit = self::LIMIT) {
		$results = array();

		$shopQuestions = $this->findAll($resourceName);

		if (count($shopQuestions) <= $offset + $limit) {
			$end = count($shopQuestions);
		} else {
			$end = $offset + $limit;
		}

		for ($i = $offset; $i < $end; $i++) {
			array_push($results, $shopQuestions[$i]);
		}

		return $results;
	}

	/**
	 * Find quetions count pulled by offset and limit.
	 * @param int $categoryId
	 * @return int
	 */
	public function countAllByCategoryId($categoryId) {
		$results = array();

		$shopQuestions = $this->findAllByCategoryId($categoryId);

		if (count($shopQuestions) <= $offset + $limit) {
			$end = count($shopQuestions);
		} else {
			$end = $offset + $limit;
		}

		for ($i = $offset; $i < $end; $i++) {
			array_push($results, $shopQuestions[$i]);
		}
		return count($results);
	}

	/**
	 * Find quetions count pulled by offset and limit.
	 * @param int $resourceName
	 * @return int
	 */
	public function countAllByResourceName($resourceName) {
		$results = array();

		$shopQuestions = $this->findAll($resourceName);

		if (count($shopQuestions) <= $offset + $limit) {
			$end = count($shopQuestions);
		} else {
			$end = $offset + $limit;
		}

		for ($i = $offset; $i < $end; $i++) {
			array_push($results, $shopQuestions[$i]);
		}
		return count($results);
	}

	/**
	 * Search questions by $keyword
	 * @param string keyword for searching
	 * @return array of ShopQuestion instance
	 */
	public function searchQuestion(
				$resourceName, $keyword, $categoryIds, $sort, $orderBy, $offset, $limit = self::LIMIT) {
		$shopQuestions = array();
		$qaClient = new QAClient();
		$response = $qaClient -> searchRecord($resourceName,
											  $keyword,
											  $this->languageManager->getSelectedLanguage(),
											  'partial',                // $matchingMethod "complete" or "prefix" or "partial" or "suffix"
											  $categoryIds,
											  'question',				// $scope "qa" or "question" or "answer"
											  $sort ,			// $sortOrder "asec" or "desc"
											  $orderBy ,// $orderBy "creationDate" or "updateDate"
											  $offset,
											  $limit);

		if ($response && $response['status'] == 'OK') {
			foreach ($response['contents'] as $record) {
				array_push($shopQuestions, $this->createShopQuestion($record));
			}
		}

		// merge shop_answer_id and display/non-display flag
		$qaQuestionIds = array();
		$qaAnswerIds   = array();
		foreach($shopQuestions as $shopQuestion) {
			array_push($qaQuestionIds, $shopQuestion -> getId());

			$shopAnswers = $shopQuestion -> getAnswers();
			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}
		}

		$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
		$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);
		$shopQuestions = $this->mergeQ($shopQuestions, $questions);
		$shopQuestions = $this->mergeA($shopQuestions, $answers);

		return $shopQuestions;
	}

/**
	 * Search questions by $keyword no offset, no limit
	 * Use for count all Datas.
	 * @param string keyword for searching
	 * @return array of ShopQuestion instance
	 */
	public function searchQuestionRangeAll($resourceName, $keyword, $categoryIds) {

		$shopQuestions = array();

		$qaClient = new QAClient();
		$response = $qaClient -> searchRecord($resourceName,
											  $keyword,
											  $this->languageManager->getSelectedLanguage(),
											  'partial',                // $matchingMethod "complete" or "prefix" or "partial" or "suffix"
											  $categoryIds,
											  'question'				// $scope "qa" or "question" or "answer"
											  );

		if ($response && $response['status'] == 'OK') {
			foreach ($response['contents'] as $record) {
				array_push($shopQuestions, $this->createShopQuestion($record));
			}
		}

		// merge shop_answer_id and display/non-display flag
		$qaQuestionIds = array();
		$qaAnswerIds   = array();
		foreach($shopQuestions as $shopQuestion) {
			array_push($qaQuestionIds, $shopQuestion -> getId());

			$shopAnswers = $shopQuestion -> getAnswers();
			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}
		}

		$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
		$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);
		$shopQuestions = $this->mergeQ($shopQuestions, $questions);
		$shopQuestions = $this->mergeA($shopQuestions, $answers);

		return $shopQuestions;

	}

	public function findSortQuestionWithCategory($resourceName,$categoryId,$sort,$orderBy,$offset = 0,$limit = self::LIMIT){

		$qaClient = new QAClient();

		$shopQuestions = array();

		$response = $qaClient -> getAllRecords($resourceName,$sort,$orderBy);

		if ($response && $response['status'] == 'OK') {
			foreach($response['contents'] as $record){

				if(is_int(array_search($categoryId,$record->categoryIds))){

						array_push($shopQuestions,$this->createShopQuestion($record));

					}
			}
		}

		$shopQuestions = ShopQuestionFactory::margeAlaivableFlag($shopQuestions);
		return array_slice($shopQuestions,$offset,$limit);

	}

	/**
	 * Merge ShopQuestion instance with display flag with($available).
	 * @param array $shopQuestions (main object)
	 * @param array $questions (append object)
	 * @return array of $shopQuestions
	 */
	public function mergeQ($shopQuestions, $questions) {
		$xoopsDB =& Database::getInstance();

		foreach ($shopQuestions as $shopQuestion) {
			$isLinked = false;

			foreach ($questions as $question) {
				if (strcmp($shopQuestion -> getId(), $question -> getQaQuestionId()) == 0) {
					$shopQuestion -> setQuestionAvailable($question -> getAvailable());
					$isLinked = true;
				}
			}

			if ($isLinked == false) {
				// Insert a new shop_questions record, so that acquiring new shopQuestionId.
				$param;
				$param -> id = $shopQuestion -> getId();// set qaQuestionId
				$param -> answers = null;
				$prepareShopQuestion = $this->createFromQARecord($param);
				$result = $prepareShopQuestion -> insertShopQuestion($xoopsDB, 0);
				if ($result) {
					$newRecord = $prepareShopQuestion -> select($xoopsDB, 0);
					$shopQuestion -> setQuestionAvailable($newRecord['available']);
				}
			}
		}
		return $shopQuestions;
	}

	/**
	 * Merge ShopAnswer instance with shopAnswerId and display flag($available).
	 * @param array $shopQuestions array of ShopQuestion instance(main object)
	 * @param array $answers array of Answer instance(append object)
	 * @return array of $shopQuestions
	 */
	public function mergeA($shopQuestions, $answers) {
		$xoopsDB =& Database::getInstance();

		foreach ($shopQuestions as $shopQuestion) {
			$shopAnswers = $shopQuestion -> getAnswers();

			foreach ($shopAnswers as $shopAnswer) {
				$isLinked = false;

				foreach ($answers as $answer) {

					if (strcmp($shopAnswer -> getQaAnswerId(), $answer -> getQaAnswerId()) == 0) {

						$shopAnswer -> setShopAnswerId($answer -> getShopAnswerId());
						$shopAnswer -> setAnswerAvailable($answer -> getAvailable());

						// set contents(image/google map)
						$shopAnswer -> setContents(ShopAnswerContent::findAllByAnswerId($answer -> getShopAnswerId()));
						$isLinked = true;
					}
				}

				if ($isLinked == false) {
					// Insert a new shop_answers record, so that acquiring new shopAnswerId.
					$prepareShopAnswer
						= ShopAnswer::createFromAnswer(array ("id" => $shopAnswer -> getQaAnswerId(),// set qaAnswerId
					                                          "qa_question_id" => $shopQuestion -> getId()));// set qaQuestionId
					$result = $prepareShopAnswer -> insertShopAnswer($xoopsDB , 0);
					if ($result) {
						$newRecord = $prepareShopAnswer -> select($xoopsDB, $shopAnswer -> getQaAnswerId());
						$shopAnswer -> setShopAnswerId($newRecord['shop_answer_id']);
						$shopAnswer -> setAnswerAvailable($newRecord['available']);
					}
				}
			}
		}
		return $shopQuestions;
	}

	public function margeAlaivableFlag($shopQuestions){
		// merge shop_answer_id and display/non-display flag
		$qaQuestionIds = array();
		$qaAnswerIds   = array();
		foreach($shopQuestions as $shopQuestion) {
			array_push($qaQuestionIds, $shopQuestion -> getId());
			$shopAnswers = $shopQuestion -> getAnswers();
			foreach ($shopAnswers as $shopAnswer) {
				array_push($qaAnswerIds, $shopAnswer -> getQaAnswerId());
			}
		}

		$questions = self::findAvailableByQaQuestionId($qaQuestionIds);
		$answers   = self::findAvailableByQaAnswerId($qaAnswerIds);

		$shopQuestions = $this->mergeQ($shopQuestions, $questions);
		$shopQuestions = $this->mergeA($shopQuestions, $answers);
		return $shopQuestions;
	}
}

/**
 * Inner class for merging ShopQuestion with available flag.
 *
 *
 */
class Question {

	private $resourceId;
	private $qaQuestionId;
	private $available;

	// array of $Answer(ShopAnswer instancef)
	private $answers = array();

	public function __construct($record) {
		if (isset($record['resource_id'])) {
			$this -> qaQuestionId = $record['resource_id'];
		}
		if (isset($record['qa_question_id'])) {
			$this -> qaQuestionId = $record['qa_question_id'];
		}
		if (isset($record['qa_question_available'])) {
			$this -> available = $record['qa_question_available'];
		}
		if (isset($record['qa_answer_id'])) {
			array_push($this -> answers, new Answer($record));
		}
	}

	public function getQaQuestionId() {
		return $this -> qaQuestionId;
	}

	public function getAvailable() {
		return $this -> available;
	}

	/**
	 * Apppend answer to question
	 * @param $record
	 * @return void
	 */
	public function append($record) {
		array_push($this -> answers, new Answer($record));
	}
}

/**
 * Inner class for merging ShopAnswer with available flag.
 *
 */
class Answer {
	private $shopAnswerId;
	private $qaAnswerId;
	private $available;

	public function __construct($answer) {
		if (isset($answer['shop_answer_id'])) {
			$this -> shopAnswerId = $answer['shop_answer_id'];
		}
		if (isset($answer['qa_answer_id'])) {
			$this -> qaAnswerId = $answer['qa_answer_id'];
		}
		if (isset($answer['qa_answer_available'])) {
			$this -> available = $answer['qa_answer_available'];
		}
	}

	public function getShopAnswerId() {
		return $this -> shopAnswerId;
	}

	public function getQaAnswerId() {
		return $this -> qaAnswerId;
	}

	public function getAvailable() {
		return $this -> available;
	}
}

?>