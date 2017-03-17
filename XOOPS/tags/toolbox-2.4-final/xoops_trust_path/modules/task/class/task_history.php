<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'task_base.php';

class TaskHistory extends TaskBase {

	const tableName = 'histories';

	/**
	 * @var File
	 */
	private $file;

	/**
	 * @var Forum
	 */
	private $forum;

	/**
	 * Constructor.
	 * @param $xoopsDB
	 * @param array $record
	 */
	protected function __construct($xoopsDB, array $record) {
		parent::__construct($xoopsDB, self::tableName, $record);

		$this->columnsOnInsert = array(
			'task_id',
			'file_id',
			'forum_id',
			'smoothing_achievement',
			'smoothing_limit_date',
			'smoothing_worker',
			'check_achievement',
			'check_limit_date',
			'check_worker',
			'update_summary',
			'creator'
		);

		$this->columnsOnUpdate = array(
			'file_id',
			'smoothing_achievement',
			'smoothing_limit_date',
			'smoothing_worker',
			'check_achievement',
			'check_limit_date',
			'check_worker',
			'update_summary',
			'modifier'
		);
	}

	/**
	 * Returns File instance.
	 * @return File
	 */
	public function getFile() {
		if (!$this->file) {
			$this->file = File::findById($this->record['file_id']);
		}
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getFileId() {
		if ($file = $this->getFile()) {
			return $file->getId();
		}
		return '';
	}

	/**
	 * Finds a record identified by given ID from database.
	 * @param $xoopsDB
	 * @param int $id
	 * @return TaskHistory
	 * @throws ValidationError if $id is not numeric.
	 */
	public static function findById($xoopsDB, $id) {
		$record = parent::findByIdBase($xoopsDB, $id, self::tableName);
		if (!is_null($record)) {
			return new TaskHistory($xoopsDB, $record);
		} else {
			return null;
		}
	}

	/**
	 * Finds all records from database.
	 * @param $xoopsDB
	 * @param array $options [optional] criteria
	 * @return array
	 */
	public static function findAll($xoopsDB, array $options = array()) {
		return parent::findAllBase($xoopsDB, __CLASS__, self::tableName, $options);
	}

	public static function findLatest($xoopsDB, $taskId) {
		self::validate(array('task_id' => $taskId));

		$historyTableName = self::acualTableName($xoopsDB, self::tableName);
		$taskTableName = self::acualTableName($xoopsDB, Task::tableName);

		$sql = "
			SELECT
			  *
			FROM
			  {$historyTableName}
			WHERE
			  task_id = %d
			AND
			  delete_flag = false
			ORDER BY
			  create_date DESC
			LIMIT
			  1
		";
		$sql = sprintf($sql, (int)$taskId);

		$history = null;
		if ($result = $xoopsDB->query($sql)) {
			$history = new TaskHistory($xoopsDB, $xoopsDB->fetchArray($result));
		}
		return $history;
	}

	/**
	 *
	 * @param object $xoopsDB
	 * @param array $options [optional] criteria
	 * @return array contains TaskHistory instances.
	 */
	public static function findByTaskId($xoopsDB, array $options = array()) {
		return TaskHistory::findAll($xoopsDB, $options);
	}

	/**
	 * Creates TaskHistory instance.
	 * @param $xoopsDB
	 * @param array $params Set to instance.
	 * @return TaskHistory
	 * @throws ValidationError
	 */
	public static function createFromParams($xoopsDB, array $params) {
		self::validate($params);
		return self::createFromParamsBase($xoopsDB, __CLASS__, $params);
	}

	/**
	 * Overridden. Validates parameters.
	 * @param array $params Parameters.
	 * @return void
	 * @throws ValidationError
	 */
	protected static function validate(array $params) {
		if(false) {
			throw new ValidationError();
		}
	}

	public static function actualTableName() {
		$xoopsDB = $GLOBALS['xoopsDB'];
		return parent::acualTableName($xoopsDB, self::tableName);
	}

	public function update() {
		$this->setParam(array_merge($this->record, $this->params));
		return $this->insert();
	}

	public function revert() {
		$new = self::createFromParams($this->db, $this->record);
		$new->insert();
	}

	/**
	 * @return boolean
	 */
	public function hasForumId() {
		$id = $this->getForumId();
		return is_numeric($id) && $id > 0;
	}

	/**
	 * @return int
	 */
	public function getForumId() {
		return $this->record['forum_id'];
	}

	/**
	 * @return Forum
	 */
	public function getForum() {
		if (!($this->forum instanceof Forum)) {
			$this->forum = Forum::findById($this->getForumId());
		}
		return $this->forum;
	}

	/**
	 * Returns smoothing_achievement.
	 * @return string
	 */
	public function getSmoothingAchievement() {
		return $this->record['smoothing_achievement'];
	}

	/**
	 * @return string
	 */
	public function isSmoothingFinished() {
		return $this->getSmoothingAchievement() == 100;
	}
	
	private function getSmoothingLimitDateInternal() {
		return strtotime($this->record['smoothing_limit_date']);
	}

	/**
	 * @return string
	 */
	public function getSmoothingLimitDate() {
		return CommonUtil::formatTimestamp($this->getSmoothingLimitDateInternal(), _MD_TASK_DATE_FORMAT);
	}

	/**
	 * @return string
	 */
	public function isSmoothingLimitExceeded() {
		return (! $this->isSmoothingFinished()) && date('Ymd', $this->getSmoothingLimitDateInternal()) < date('Ymd');
	}

	/**
	 * @return string
	 */
	public function isSmoothingLimitWarning() {
		return (! $this->isSmoothingFinished()) && date('Ymd', $this->getSmoothingLimitDateInternal()) < date('Ymd', time() + ConfigManager::getWarningThresholdDate() * 60 * 60 * 24);
	}

	/**
	 * @return string
	 */
	public function getSmoothingLimitTime() {
		return CommonUtil::formatTimestamp($this->getSmoothingLimitDateInternal(), _MD_TASK_TIME_FORMAT);
	}

	/**
	 * Returns smoothing_worker.
	 * @return string
	 */
	public function getSmoothingWorker() {
		return $this->record['smoothing_worker'];
	}

	/**
	 * Returns check_achievement.
	 * @return string
	 */
	public function getCheckAchievement() {
		return $this->record['check_achievement'];
	}

	/**
	 * @return string
	 */
	public function isCheckFinished() {
		return $this->getCheckAchievement() == 100;
	}
	
	/**
	 * @return int timestamp
	 */
	private function getCheckLimitDateInternal() {
		return strtotime($this->record['check_limit_date']);
	}

	/**
	 * @return string
	 */
	public function getCheckLimitDate() {
		return CommonUtil::formatTimestamp($this->getCheckLimitDateInternal(), _MD_TASK_DATE_FORMAT);
	}

	/**
	 * @return string
	 */
	public function isCheckLimitExceeded() {
		return (! $this->isCheckFinished()) && date('Ymd', $this->getCheckLimitDateInternal()) < date('Ymd');
	}

	/**
	 * @return string
	 */
	public function isCheckLimitWarning() {
		return (! $this->isCheckFinished()) && date('Ymd', $this->getCheckLimitDateInternal()) < date('Ymd', time() + ConfigManager::getWarningThresholdDate() * 60 * 60 * 24);
	}

	/**
	 * @return string
	 */
	public function getCheckLimitTime() {
		return CommonUtil::formatTimestamp($this->getCheckLimitDateInternal(), _MD_TASK_TIME_FORMAT);
	}

	/**
	 * Returns check_worker.
	 * @return string
	 */
	public function getCheckWorker() {
		return $this->record['check_worker'];
	}

	/**
	 * Returns update_summary.
	 * @return string
	 */
	public function getUpdateSummary() {
		return $this->record['update_summary'];
	}
}
