<?php
require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
require_once dirname(__FILE__).'/manager/language-manager.php';
require_once dirname(__FILE__).'/shop_question.php';
require_once dirname(__FILE__).'/shop_question_list.php';

class ShopCategory {

	/**
	 * ID.
	 * @var int
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $record;

	/**
	 * @var BilingualManager
	 */
	protected $lang;

	/**
	 * Constructor.
	 * @param array $record
	 */
	protected function __construct(array $record) {
		$this->id = $record['id'];
		$this -> record = $record;
	}

	public static function findById($id, $manager) {
		$qaClient = new QAClient();
		$response = $qaClient->getCategory('dummy', $id);
		return self::createFromQACategory($response['contents'], $manager);
	}

	public static function createFromQACategory(ToolboxVO_QA_QACategory $category, BilingualManager $manager) {
		if (!($category instanceof ToolboxVO_QA_QACategory)) {
			return null;
		}

		$mainExpression = null;
		$subExpression  = null;

		$mainLanguage = $manager->getSelectedLanguage();
		$subLanguage  = $manager->getSelectedSubLanguage();

		foreach ($category -> name as $resource) {

			/* @var $resource ToolboxVO_Resource_Expression */

			if ($resource -> language == $mainLanguage) {
				$mainExpression = $resource -> expression;
			}
			if ($resource -> language == $subLanguage) {
				$subExpression  = $resource -> expression;
			}
		}

		return self::createFromParams(array(
			"id" => $category -> id,
			"mainExpression" => $mainExpression,
			"subExpression"  => $subExpression,
			"mainLanguage"   => $mainLanguage,
			"subLanguage"    => $subLanguege
		));
	}

	/**
	 * Creates and returns ShopCategory instances.
	 * @param array $params
	 * @return ShopCategory
	 */
	public static function createFromParams(array $params) {
		return new ShopCategory($params);
	}

	/**
	 * Returns this instance's ID.
	 * @return int
	 */
	public function getId() {
		return $this -> record['id'];
	}

	public function getMainExpression() {
		return $this -> record['mainExpression'];
	}

	public function getSubExpression() {
		return $this -> record['subExpression'];
	}
}
