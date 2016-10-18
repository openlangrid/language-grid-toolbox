<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/task.php';

class TaskList {

	private $xoopsDB;

	/**
	 * Contains Task instances.
	 * @var array
	 */
	private $tasks;

	private $page = 1;
	private $perPage = 10;

	/**
	 * Constructor.
	 * @param object $xoopsDB
	 * @param array $tasks Task instances.
	 * @param int $page
	 * @param int $perPage
	 */
	private function __construct($xoopsDB, array $tasks, $page, $perPage) {
		$this->xoopsDB = $xoopsDB;
		$this->tasks = $tasks;
		$this->page = $page;
		$this->perPage = $perPage;
	}

	/**
	 * Returns Task instances.
	 * @param object $xoopsDB
	 * @param array $options [optional]
	 * @return TaskList
	 */
	static public function findAll($xoopsDB, array $options = array()) {
		$page = (int)@$options['page'];
		if (!$page) {
			$page = 1;
		}

		$perPage = @$options['perPage'];
		if (is_null($perPage)) {
			// set default value
			$perPage = 10;
		}

		$tasks = Task::findAll($xoopsDB, $options);
		return new TaskList($xoopsDB, $tasks, $page, $perPage);
	}

	public static function findWithAdvancedSerch(array $options) {
		$page = (int)@$options['page'];
		if (!$page) {
			$page = 1;
		}

		$perPage = @$options['perPage'];
		if (is_null($perPage)) {
			// set default value
			$perPage = 10;
		}

		$tasks = Task::findByOptions($options);
		return new TaskList($GLOBALS['xoopsDB'], $tasks, $page, $perPage);
	}

	/**
	 * Counts not deleted task records.
	 * @return int
	 */
	public function countAll() {
		return count($this->tasks);
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
		return count($this->getTasks());
	}

	/**
	 * Returns Task instances.
	 * @return array
	 */
	public function getTasks() {
		$offset = self::calculateOffset($this->page, $this->perPage);

		$length = $this->perPage;
		if (!$length) {
			$length = null;
		}

		return array_slice($this->tasks, $offset, $length);
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

	public function getPreviousPage() {
		return $this->page - 1;
	}

	public function getNextPage() {
		return $this->page + 1;
	}
}
?>
