<?php
require_once dirname(__FILE__).'/shop_category.php';
require_once dirname(__FILE__).'/pager.php';

/**
 * Contains ShopCategory instances.
 */
class ShopCategoryList extends Pager {

	const DEFALT_LIMIT = 14;

	/**
	 * @var int
	 */
	protected $sum;

	/**
	 * Contains some ShopCategory instances.
	 * @var array
	 */
	protected $categoriesCache;

	/**
	 * Constructor.
	 * @param array $categories contains ShopCategory instances
	 * @param array $options [optional]
	 */
	protected function __construct(array $categories, array $options) {
		// offset
		$page = @$options['page'];
		if (!$page) {
			$page = 1;
		}
		$offset = ($page - 1) * self::DEFALT_LIMIT;

		parent::__construct($categories, $offset, self::DEFALT_LIMIT);
	}

	/**
	 * Find categories with $resourceName.
	 * @param string $resourceName
	 * @param IBilingualManager $manager
	 * @return array
	 */
	protected static function findByResourceNameInternal($resourceName, IBilingualManager $manager) {
		$qaClient = new QAClient();
		$response = $qaClient -> getAllCategories($resourceName);
		$records = $response['contents'];

		if (!is_array($records)) {
			$records = array();
		}

		$results = array();
		foreach ($records as $record) {
			/* @var $record ToolboxVO_QA_QACategory */

			$results[] = ShopCategory::createFromQACategory($record, $manager);
		}

		return $results;
	}

	/**
	 * Finds and returns categories.
	 * @param string $resourceName
	 * @param IBilingualManager $manager
	 * @param array $options [optional]
	 * @return ShopCategoryList
	 */
	public static function findByResourceName($resourceName, IBilingualManager $manager, array $options = array()) {
		$categories = self::findByResourceNameInternal($resourceName, $manager);
		return new ShopCategoryList($categories, $options);
	}

	/**
	 * Finds and returns categories.
	 * @param string $resourceName
	 * @param string $keyword
	 * @param IBilingualManager $manager
	 * @param array $options [optional]
	 * @return ShopCategoryList
	 */
	public static function findByResourceNameAndKeyWord(
			$resourceName, $keyword, IBilingualManager $manager, array $options = array()) {

		$categories = self::findByResourceNameInternal($resourceName, $manager);

		$results = array();
		foreach ($categories as $category) {
			/* @var $category ToolboxVO_QA_QACategory */
			$position = stripos($category -> getMainExpression(), $keyword);
			if ($position !== false) {
				$results[] = $category;
			}
		}

		return new ShopCategoryList($results, $options);
	}

	/**
	 * @return array contains ShopCategory instances.
	 */
	public function getEntitiesInCurrentPage() {
		if (!$this->categoriesCache) {
			$limit = self::DEFALT_LIMIT;
			$offset = ($this->getCurrentPage() - 1) * $limit;
			$this->categoriesCache = array_slice($this->getEntities(), $offset, $limit);
		}
		return $this->categoriesCache;
	}

	/**
	 * Override.
	 * Returns the number of containing ShopCategory instances.
	 * @return int
	 */
	public function getSum() {
		if (!$this -> sum) {
			$this->sum = count($this->getEntities());
		}

		return $this->sum;
	}
}
