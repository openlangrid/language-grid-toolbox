<?php
abstract class Pager {

	const DEFALT_LIMIT = 5;

	protected $page;

	protected $limit;

	protected $entities;

	/**
	 * Constructor.
	 * @param array $entities
	 * @param int $offset
	 * @param int $limit
	 */
	protected function __construct(array $entities, $offset, $limit) {
		$this -> entities = $entities;
		$this -> page = floor($offset / $limit) + 1;
		$this -> limit = $limit;
	}

	public function getEntities() {
		return $this -> entities;
	}

	public function getCurrentPage() {
		return $this -> page;
	}

	public function getFrom() {
		return ($this -> page - 1) * $this -> limit + 1;
	}

	public function getTo() {
		return $this -> getFrom() - 1 + count($this -> entities);
	}

	public function getFirstPageNo() {
		return 1;
	}

	public function getLastPageNo() {
		$result = intval(($this -> getSum() / $this -> limit));
		if(($this -> getSum() % $this -> limit) > 0) $result += 1;
		return $result;
	}

	public function getPageNoList() {
		$results = array();
		for($i = 1; $i <= $this -> getLastPageNo(); $i++)
			array_push($results, $i);
		return $results;
	}

	public function getPageNoListWithOutFirstAndLast() {
		$results = $this -> getPageNoList();
		array_shift($results);
		array_pop($results);
		return $results;
	}

	public function hasNextPage() {
		return $this -> getLastPageNo() > $this -> getCurrentPage();
	}

	public function hasPrevPage() {
		return $this -> getCurrentPage() > 1;
	}

	public function getNextPageNo() {
		return $this -> getCurrentPage() + 1;
	}

	public function getPrevPageNo() {
		return $this -> getCurrentPage() - 1;
	}

	public function getNextPageQueryString() {
		return $this -> getQueryString( array("page" => $this->getNextPageNo() ));
	}

	public function getPrevPageQueryString() {
		return $this -> getQueryString( array("page" => $this->getPrevPageNo() ));
	}

	public function getPageQueryString($pageNo) {
		return $this -> getQueryString( array("page" => $pageNo ));
	}

	public function getNextPageLink($rootUrl) {
		return $this -> linkOrLabel($this->hasNextPage(), STF_LINK_NEXT, $rootUrl, $this -> getNextPageQueryString());
	}

	public function getPrevPageLink($rootUrl) {
		return $this -> linkOrLabel($this->hasPrevPage(), STF_LINK_PREV, $rootUrl, $this -> getPrevPageQueryString());
	}

	public function getCurrentPageUrl($rootUrl) {
		$query = $this -> getQueryString( array("page" => $this -> getCurrentPage() ));
		return "{$rootUrl}&{$query}";
	}

	public function getFirstPageLink($rootUrl) {
		return $this -> getPageLink($rootUrl, 1);
	}

	public function getLastPageLink($rootUrl) {
		return $this -> getPageLink($rootUrl, $this->getLastPageNo());
	}

	public function getPageLink($rootUrl, $pageNo) {
		return $this -> linkOrLabel($this->getCurrentPage() != $pageNo, $pageNo, $rootUrl, $this->getPageQueryString($pageNo));
	}

	public function getPerPageLinks($rootUrl, $numbers = array()) {
		$results = array();
		foreach($numbers as $num => $label) {
			$params = array('page' => 1, 'perPage' => $num);
			$results[] = $this -> perPageLinkOrLabel($this -> limit != intval($num), $label, $rootUrl, CommonUtil::toQueryString($params));
		}
		return implode('&nbsp;', $results);
	}

	protected function linkOrLabel($linkFlg, $label, $rootUrl, $query) {
		return $linkFlg ? "<a class='pageLink' href='{$rootUrl}&{$query}'>{$label}</a>" : "<span>{$label}</span>";
	}

    protected function perPageLinkOrLabel($linkFlg, $label, $rootUrl, $query) {
        return $linkFlg ? "<a class='perPageLink' href='{$rootUrl}&{$query}'>{$label}</a>" : "<span>{$label}</span>";
    }

	public function getQueryString($params = array()) {
		if(!isset($params['page'])) {
			$params['page'] = $this -> page;
		}
		$params['perPage'] = $this -> limit;

		if($this -> limit != self::DEFALT_LIMIT) {
			//$params['limit'] = $this -> limit;
		}

		$params = array_merge($params, $this -> addParamater());
		return CommonUtil::toQueryString($params);
	}

	protected function addParamater($params = array()) {
		return $params;
	}

	abstract public function getSum();
}
?>
