<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/task_history.php';

class HistoryList {

	private $xoopsDB;

	/**
	 * Contains TaskHistory instances.
	 * @var array
	 */
	private $histories = array();

	private $page;
	private $perPage;

	/**
	 * Constructor.
	 * @param object $xoopsDB
	 * @param array $histories TaskHistory instances.
	 * @param int $page
	 * @param int $perPage
	 */
	private function __construct($xoopsDB, array $histories, $page, $perPage) {
		$this->xoopsDB = $xoopsDB;
		$this->histories = $histories;
		$this->page = $page;
		$this->perPage = $perPage;
	}

	/**
	 * Returns TaskHistory instances.
	 * @param object $xoopsDB
	 * @param array $options [optional]
	 * @return HistoryList
	 */
	static public function findAll($xoopsDB, array $options = array()) {
		$page = (int)@$options['page'];
		if ($page < 1) {
			$page = 1;
		}

		$perPage = @$options['perPage'];
		if (is_null($perPage)) {
			$perPage = 10;
		}

		$tasks = Task::findAll($xoopsDB, $options);
		return new TaskList($xoopsDB, $tasks, $page, $perPage);
	}

	/**
	 *
	 * @param object $xoopsDB
	 * @param int $id Task's ID.
	 * @param array $options
	 * @return HistoryList
	 */
	public static function findByTaskId($xoopsDB, $id, array $options = array()) {
		$page = @$options['page'];
		if ($page < 1) {
			$page = 1;
		}

		$perPage = @$options['perPage'];
		if (is_null($perPage)) {
			$perPage = 10;
		}

		$options['wheres']['task_id'] = $id;

		$options['orderby'] = 'create_date DESC';

		$histories = TaskHistory::findByTaskId($xoopsDB, $options);
		return new HistoryList($xoopsDB, $histories, $page, $perPage);
	}

	/**
	 * Counts not deleted task records.
	 * @return int
	 */
	public function countAll() {
		return count($this->histories);
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
	 * @return int
	 */
	private function countInCurrentPage() {
		return count($this->getHistoryList());
	}

	/**
	 * Returns TaskHistory instances.
	 * @return array
	 */
	public function getHistoryList() {
		$offset = self::calculateOffset($this->page, $this->perPage);
		
			$length = $this->perPage;
		if (!$length) {
			$length = null;
		}
		
		return array_slice($this->histories, $offset, $length);
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
	 * @return
	 */
	public function getLastPage() {
		if ($this->perPage == 0) {
			return 1;
		} else {
			return ceil($this->countAll() / $this->perPage);
		}
	}

	public function hasPreviousPage() {
		return $this->getCurrentPage() > 1;
	}

	public function getPreviousPageNo() {
		return $this->getCurrentPage() - 1;
	}


	public function hasNextPage() {
		return $this->getCurrentPage() < $this->getLastPage();
	}

	public function getNextPageNo() {
		return $this->getCurrentPage() + 1;
	}
}
