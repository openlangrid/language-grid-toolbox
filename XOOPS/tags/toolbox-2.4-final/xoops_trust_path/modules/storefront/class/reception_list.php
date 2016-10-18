<?php

require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');

class ReceptionList {

	/**
	 * @var ToolboxVO_Resource_LanguageResource
	 */
	protected $receptions;

	protected $sortOrders = array(
		'ASC' => 1,
		'DSC' => -1,
	);

	protected $sortKeys = array(
		'NAME' => 1,
		'LANGUAGE' => 1,
		'CREATOR' => 1,
		'LAST_UPDATE' => 1,
		'ENTRIES' => 1,
	);

	protected $nextSortOrder = array(
		'ASC' => 'DSC',
		'DSC' => 'ASC',
	);

	private $sortKey = 'NAME';
	private $sortOrder = 'ASC';
	private $page = 1;
	private $perPage = 10;

	/**
	 * returns ResourceClient client interface.
	 * @return a instance of ResourceClient
	 */
	protected static function getResourceClient() {
		return new ResourceClient();
	}

	/**
	 * Find receptions from ResourceManager by specified opts. this function returns a instance of ReceptionList that contains Reception instances.
	 * @param array $opts
	 * @return ReceptionList
	 */
	public static function findAll() {
		$cli = self::getResourceClient();
		$ret = $cli->getAllLanguageResources('QA');
		return new ReceptionList($ret['contents']);
	}

	/**
	 * Constructor.
	 * @param array(ToolboxVO_Resource_LanguageResource) $receptions
	 * @return ReceptionList
	 */
	private function __construct($receptions = null) {
		$this->receptions = $receptions;
	}

	/**
	 * returns part of all receptions.
	 * @return array(ToolboxVO_Resource_LanguageResource)
	 */
	public function getReceptions($opts) {
		$page = @$opts['page'];
		if (is_numeric($page) && $page > 0) {
			$this->page = (int)$page;
		}

		$perPage = @$opts['perPage'];
		if (is_numeric($perPage) && $perPage >= 0) {
			$this->perPage = (int)$perPage;
		}

		$order = @$opts['sortOrder'];
		if (@$this->sortOrders[$order]) {
			$this->sortOrder = $order;
		}

		$key = @$opts['sortKey'];
		if (@$this->sortKeys[$key]) {
			$this->sortKey = $key;
		}

		if ($page > $this->getLastPage()) {
			$this->page = $this->getLastPage();
		}

		$offset = self::calculateOffset($this->page, $this->perPage);

		$recs = array();
		foreach ($this->receptions as $rec) {
			$recs[] = new Reception($rec);
		}

		usort($recs, array($this, "cmpBy_{$this->sortKey}"));

		return array_slice($recs, $offset, $this->perPage == 0 ? null : $this->perPage);
	}

	/**
	 * @param int $page
	 * @param int $perPage
	 * @return int
	 */
	static private function calculateOffset($page, $perPage) {
		return ($page - 1) * $perPage;
	}

	/**
	 * returns count of all records.
	 * @return int
	 */
	public function countAll() {
		return count($this->receptions);
	}

	/**
	 * @return int
	 */
	private function countInCurrentPage() {
		return count($this->getReceptions(array()));
	}

	/**
	 * Returns offset to display.
	 * @return int
	 */
	public function getMinNumber() {
		if ($this->countInCurrentPage() < 1) {
			return 0;
		} else {
			return self::calculateOffset($this->page, $this->perPage) + 1;
		}
	}

	/**
	 * @return int
	 */
	public function getMaxNumber() {
		return self::calculateOffset($this->page, $this->perPage) + $this->countInCurrentPage();
	}

	/**
	 * @return int
	 */
	public function getPerPage() {
		return $this->perPage;
	}

	/**
	 * Returns displaying page number.
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->page;
	}

	/**
	 * Returns the last page number.
	 * @return int
	 */
	public function getLastPage() {
		if ($this->perPage == 0) {
			return 1;
		} else {
			return ceil($this->countAll() / $this->perPage);
		}
	}

	/**
	 * returns number of previous page.
	 * @return int
	 */
	public function getPreviousPage() {
		return $this->page - 1;
	}

	/**
	 * returns number of next page.
	 * @return int
	 */
	public function getNextPage() {
		return $this->page + 1;
	}

	/**
	 * returns key of sort.
	 * @return string
	 */
	public function getSortKey() {
		return $this->sortKey;
	}

	/**
	 * returns sort order.
	 * @return string
	 */
	public function getSortOrder() {
		return $this->sortOrder;
	}

	/**
	 * returns next sort order for this class.
	 * @return string
	 */
	public function getNextSortOrder() {
		return $this->nextSortOrder[$this->sortOrder];
	}


	/**
	 * compare by name. for callback function.
	 * @param $a left value
	 * @param $b right value
	 * @return int
	 */
	protected function cmpBy_NAME($a, $b) {
		return strcasecmp($a->getName(), $b->getName()) * $this->sortOrders[$this->sortOrder];
	}

	/**
	 * compare by language(translated). for callback function.
	 * @param $a left value
	 * @param $b right value
	 * @return int
	 */
	protected function cmpBy_LANGUAGE($a, $b) {
		return strcasecmp($a->getLanguageCollectionString(), $b->getLanguageCollectionString())
				* $this->sortOrders[$this->sortOrder];
	}

	/**
	 * compare by creator. for callback function.
	 * @param $a left value
	 * @param $b right value
	 * @return int
	 */
	protected function cmpBy_CREATOR($a, $b) {
		return strcasecmp($a->getCreator(), $b->getCreator()) * $this->sortOrders[$this->sortOrder];
	}

	/**
	 * compare by last update date. for callback function.
	 * @param $a left value
	 * @param $b right value
	 * @return int
	 */
	protected function cmpBy_LAST_UPDATE($a, $b) {
		if ($a->getLastUpdate() == $b->getLastUpdate()) {
			return 0;
		}
		return (($a->getLastUpdate() < $b->getLastUpdate())? -1 : 1) * $this->sortOrders[$this->sortOrder];
	}

	/**
	 * compare by count of entry. for callback function.
	 * @param $a left value
	 * @param $b right value
	 * @return int
	 */
	protected function cmpBy_ENTRIES($a, $b) {
		if ($a->getEntryCount() == $b->getEntryCount()) {
			return 0;
		}
		return (($a->getEntryCount() < $b->getEntryCount())? -1 : 1) * $this->sortOrders[$this->sortOrder];
	}
}

?>