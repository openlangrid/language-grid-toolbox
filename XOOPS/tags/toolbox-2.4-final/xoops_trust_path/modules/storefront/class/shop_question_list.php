<?php
require_once dirname(__FILE__).'/shop_question.php';
require_once dirname(__FILE__).'/pager.php';

class ShopQuestionList extends Pager {

	const DEFAULT_LIMIT = 5;

	private $sum;
	private $searchKey;
	private $keyWord;

	private $languageManager;
	private $categoryId;
	private $resourceName;

	private $tmplimit;

	private $useToShopQuestion;

	const CATEGORY_ID   = 'categoryId';
	const RESOURCE_NAME = 'resourceName';

	// element of list is ShopQuestion
	//private $questionList;

	static protected function getShopQuestionFactory($languageManager = null) {
		return new ShopQuestionFactory($languageManager);
	}

	/**
	 * Constructor
	 * @param array array of ShopQuestion instance
	 * @return void
	 */
	protected function __construct($questionList, $offset = 0, $limit = self::DEFAULT_LIMIT, $languageManager = null) {
		parent::__construct($questionList, $offset, $limit);
		$this->languageManager = $languageManager;
	}

	static public function createFromQuestion($offset, $limit, $params=array(), $languageManager = null) {

		$shopQuestionList;
		$factory = self::getShopQuestionFactory($languageManager);
		if(@$params[self::CATEGORY_ID]) {
			$shopQuestions = $factory->findWithOffset($params[self::CATEGORY_ID], $offset, $limit);
			$shopQuestionList = new ShopQuestionList($shopQuestions, $offset, $limit, $languageManager);

			$shopQuestionList -> setSearchKey(self::CATEGORY_ID);
			$shopQuestionList -> setCategoryId($params[self::CATEGORY_ID]);
		} else if(@$params[self::RESOURCE_NAME]) {
			$shopQuestions = $factory->findByResorceNameWithOffset($params[self::RESOURCE_NAME], $offset, $limit);
			$shopQuestionList = new ShopQuestionList($shopQuestions, $offset, $limit, $languageManager);

			$shopQuestionList -> setSearchKey(self::RESOURCE_NAME);
			$shopQuestionList -> setResourceName($params[self::RESOURCE_NAME]);
		}

		return $shopQuestionList;
	}

	static public function createFromQuestionList($questionList, $languageManager = null) {
		return new ShopQuestionList($questionList, 0, self::DEFAULT_LIMIT, $languageManager);
	}

	/**
	 * Find questions by $resourceName,$offset, $limit.
	 * @param string $categoryId
	 * @param int $offset
	 * @param int $limit
	 * @return object of ShopQuestionList
	 */
	static public function findByCategoryIdWithOffset($categoryId, $offset, $limit, $languageManager = null) {
		return self::createFromQuestion($offset, $limit, array(self::CATEGORY_ID => $categoryId), $languageManager);
	}

	/**
	 * Find questions by $resourceName,$offset, $limit.
	 * @param string $resourceName
	 * @param int $offset
	 * @param int $limit
	 * @return object of ShopQuestionList
	 */
	static public function findByResourceNameWithOffset($resourceName, $offset, $limit, $languageManager = null) {
		return self::createFromQuestion($offset, $limit,  array(self::RESOURCE_NAME => $resourceName), $languageManager);
	}

	static public function findAllByResourceName($resourceName, $languageManager = null){

		$shopQuestions = self::getShopQuestionFactory($languageManager)->findAll($resourceName);
		$shopQuestionList = new ShopQuestionList($shopQuestions, 0, 99999, $languageManager);

		$shopQuestionList->setSearchKey(self::RESOURCE_NAME);
		$shopQuestionList->setResourceName($resourceName);

		return $shopQuestionList;
	}


	/**
	 * Find categories with keyword.
	 * @param string $shopCategoryList
	 * @param int $keyword
	 * @return object of ShopCategoryList
	 */
	static public function seachCategoriesWithKeyWord($resourceName,$keyWord,$caId,$sort,$orderBy,$offset = 0, $limit = self::DEFAULT_LIMIT, $languageManager = null){

	  if($offset < 0) {
	    $offset = 0;
	    $limit = 999999; // means no limit in this world. WT*. see Toolbox_QARecordReadManager.
	  }
		$returnShopQuestionList;
		If(isset($keyWord) && $keyWord<>""){
			$shopQuestions = ShopQuestionList::extractAvailableArray(self::getShopQuestionFactory($languageManager)->searchQuestion($resourceName,$keyWord,array($caId),$sort,$orderBy,$offset,$limit));
			$returnShopQuestionList = new ShopQuestionList($shopQuestions, $offset, $limit, $languageManager);

		}else{

			If((isset($sort) && $sort<>"") && (isset($orderBy) && $orderBy <> "" )){

				$shopFactory = self::getShopQuestionFactory($languageManager);
				$shopQuestions = ShopQuestionList::extractAvailableArray(
					$shopFactory->findSortQuestionWithCategory($resourceName,$caId,$sort,$orderBy,$offset,$limit));

				$returnShopQuestionList = new ShopQuestionList($shopQuestions, $offset, $limit, $languageManager);

			}else{

				$shopQuestions = ShopQuestionList::extractAvailableArray(self::getShopQuestionFactory($languageManager)->findWithOffset($caId, $offset, $limit));
				$returnShopQuestionList = new ShopQuestionList($shopQuestions, $offset, $limit, $languageManager);

			}
		}

		$returnShopQuestionList->setResourceName($resourceName);
		$returnShopQuestionList->setCategoryId($caId);
		$returnShopQuestionList->setSearchKey(self::CATEGORY_ID);
		$returnShopQuestionList->setLimit($limit);
		$returnShopQuestionList->setKeyWord($keyWord);

		$returnShopQuestionList->setUseToShopQuestionFlg();

		return $returnShopQuestionList;

	}

	/**
	 * Remove UnavailableShopQuestion.
	 * @param array $shopQuestions
	 * @return array of ShopQuestion
	 */
	static private function extractAvailableArray($shopQuestions){

		$returnArray = array();

		foreach($shopQuestions as $shopQuestion){

			if ($shopQuestion->isAvailable()){

				array_push($returnArray,$shopQuestion);

			}

		}

		return $returnArray;

	}

	/**
	 * Update shop_questions table and shop_answers table with update values.
	 *
	 * [Usage]
	 * // case of multi question update
	 * foreach ($QARecords as $QARecord) {
	 *		$shopQuestion = self::getShopQuestionFactory()->createFromQARecord($QARecord);
	 *		$shopQuestion -> update();
	 * }
	 *
	 * @param string $resourceName
	 * @return int of update count
	 */
	public function update($resourceName) {
		$result = 0;
		if ($this -> entities) {

			// get values from database for comparison
			// TODO set resourceName as argument of findAll function
			$shopQuestionsForComparison = self::getShopQuestionFactory($this->languageManager)->findAll($resourceName);

			// compare user input value with database value
			// and insert/update shop_questions, shop_answers table record.
			foreach ($this -> entities as $shopQuestion) {
				$result &= $shopQuestion -> compareAndUpdateQuestions($shopQuestionsForComparison);
			}
		}
		return $result;
	}

	public function getSearchKey() {
		return $this -> searchKey;
	}

	public function setSearchKey($searchKey) {
		$this -> searchKey = $searchKey;
	}

	public function getQuestions() {
		return $this -> getEntities();
	}

	public function setCategoryId ($categoryId) {
		$this -> categoryId = $categoryId;
	}

	public function getLanguageList() {
		if ($this -> entities) {
			foreach ($this -> entities as $shopQuestion) {
				return $shopQuestion -> getLanguageList();
			}
		}
		return array();
	}

	public function setResourceName($resourceName) {
		$this -> resourceName = $resourceName;
	}

	public function getCategoryId () {
		return $this -> categoryId;
	}

	public function getResourceName() {
		return $this -> resourceName;
	}

	public function getLimit() {
		$returnLimit = self::DEFAULT_LIMIT;

		if (isset($this->tmplimit)){

			$returnLimit = $this->tmplimit;

		}

		return $returnLimit;
	}

	public function setLimit($limit){
		$this->tmplimit = $limit;
	}

	public function setKeyWord($keyWord){
		$this-> keyWord = $keyWord;
	}

	public function getKeyWord(){
		return $this-> keyWord;
	}

	private function setUseToShopQuestionFlg(){
		$this->useToShopQuestion = true;
	}

	private function getUseToShoQuestionFlg(){
		return $this->useToShopQuestion;
	}

	public function getSum() {
		if(!$this -> sum) {
			if (strcmp($this -> getSearchKey(), self::CATEGORY_ID) == 0) {
				$keyWord = $this->getKeyWord();
				if (isset($keyWord) && $keyWord <> "") {
					$shopQuestions = self::getShopQuestionFactory($this->languageManager)->searchQuestionRangeAll($this->getResourceName(),$this->getKeyWord(),array($this->getCategoryId()));

					if ($this->getUseToShoQuestionFlg()){
						$shopQuestions = $this->extractAvailableArray($shopQuestions);
					}

					$this -> sum = count($shopQuestions);

				}else{
					$shopQuestions = self::getShopQuestionFactory($this->languageManager)->findAllByCategoryId($this -> getCategoryId());

					if($this->getUseToShoQuestionFlg()){
						$shopQuestions = $this->extractAvailableArray($shopQuestions);
					}

					$this -> sum = count($shopQuestions);
				}

			} elseif (strcmp($this -> getSearchKey(), self::RESOURCE_NAME) == 0) {
				$this -> sum = count(self::getShopQuestionFactory($this->languageManager)->findAll($this -> getResourceName()));
			}
		}
		return $this -> sum;
	}
}
