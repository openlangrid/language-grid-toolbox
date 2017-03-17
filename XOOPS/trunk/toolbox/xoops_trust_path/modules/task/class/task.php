<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'task_base.php';
require_once dirname(__FILE__) . '/task_history.php';

class Task extends TaskBase {

	const tableName = 'tasks';

	/**
	 * Constructor.
	 * @param $xoopsDB
	 * @param array $record
	 */
	protected function __construct($xoopsDB, array $record) {
		parent::__construct($xoopsDB, self::tableName, $record);

		$this->columnsOnInsert = array(
			'name',
			'source_lang',
			'target_lang',
			'creator'
		);
		$this->columnsOnUpdate = array(
			'name',
			'source_lang',
			'target_lang',
			'modifier'
		);
	}

	/**
	 * Inserts a new record.
	 * @return int task history's ID.
	 */
	public function insert() {
		parent::insert();

		$this->params['task_id'] = $this->id;
		$this->params['smoothing_achievement'] = 0;
		$this->params['check_achievement'] = 0;

		$history = TaskHistory::createFromParams($this->db, $this->params);
		$history->insert();
		return $history->getId();
	}

	/**
	 * Deletes a record logically identified by ID.
	 * @return int The number of affected records.
	 */
	public function delete() {
		$sql = "
			UPDATE
			  {$this->prefixedTableName}
			SET
			  modifier = %d,
			  update_date = NOW(),
			  delete_flag = true
			WHERE
			  id = %d
			AND
			  delete_flag = false
		";
		$sql = sprintf($sql, $this->getModifier(), $this->id);

		$this->db->queryF($sql);
		return $this->db->getAffectedRows();
	}

	/**
	 * Finds a record identified by given ID from database.
	 * @param $xoopsDB
	 * @param int $id
	 * @return Task
	 * @throws ValidationError if $id is not numeric.
	 */
	public static function findById($xoopsDB, $id) {
		$record = parent::findByIdBase($xoopsDB, $id, self::tableName);
		if (!is_null($record)) {
			return new Task($xoopsDB, $record);
		} else {
			return null;
		}
	}

	public static function findByFileId($id) {
		$file = File::findById($id);
		return $file->getTask();
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

	public static function findByOptions(array $options) {
		$xoopsDB = $GLOBALS['xoopsDB'];

		$taskTable = Task::actualTableName();
		$historyTable = TaskHistory::actualTableName();
		$orderby = mysql_real_escape_string($options['orderby']);

		$sql = "
			select
			  task.id,
			  task.name,
			  task.source_lang,
			  task.target_lang,
			  history.smoothing_achievement,
			  history.smoothing_limit_date,
			  history.smoothing_worker,
			  history.check_achievement,
			  history.check_limit_date,
			  history.check_worker,
			  task.creator,
			  history.create_date
			from {$taskTable} task
			  inner join {$historyTable} history on task.id = history.task_id
			  inner join (select max(create_date) as latest, task_id from {$historyTable} group by task_id) as l
			  on task.id = l.task_id and history.create_date = l.latest
			WHERE
			  task.delete_flag = false
			  %s
			ORDER BY {$orderby}
		";

		$wheres = @$options['wheres'];
		if (!is_array($wheres)) {
			$wheres = array();
		}

		$sql = sprintf($sql,
			self::buildConditions($wheres)
		);

		$results = $xoopsDB->query($sql);
		$tasks = array();
$q=$xoopsDB->logger->queries;
//var_dump($q[count($q)-1]);exit;
		while ($record = $xoopsDB->fetchArray($results)) {
			$tasks[] = new Task($xoopsDB, $record);
		}

		return $tasks;
	}

	protected static function buildConditions(array $conditions) {
		$xoopsDB = $GLOBALS['xoopsDB'];
		$ary = array();

		foreach ($conditions as $column => $tmp) {
			$condition = 'AND ' . mysql_real_escape_string($column) . ' ';

			if (is_string($tmp) || is_numeric($tmp)) {
				$condition .= "= {$xoopsDB->quoteString($tmp)}";
			} elseif (is_array($tmp)) {
				$symbol = @$tmp['symbol'];
				$value = @$tmp['value'];
				if (is_null($symbol) || is_null($tmp)) {
					continue;
				}

				$symbolMap = CommonUtil::getSymbolMapCache();
				$symbolMap[] = 'like';
				if (in_array($symbol, $symbolMap)) {
					$condition .= mysql_real_escape_string($symbol) . " {$xoopsDB->quoteString($value)}";
				}
			}

			$ary[] = $condition;
		}

		return implode(' ', $ary);
	}

	/**
	 * Creates Task instance.
	 * @param $xoopsDB
	 * @param array $params Set to instance.
	 * @return Task
	 * @throws ValidationError
	 */
	public static function createFromParams($xoopsDB, array $params) {
		self::validate($params);
		$task = new Task($xoopsDB, array());
		$task->setParam($params);
		return $task;
	}

	/**
	 * Validates parameters.
	 * @param array $params Parameters.
	 * @return void
	 * @throws ValidationError
	 */
	protected static function validate(array $params) {
		$error = null;

		if (!empty($params['id']) && !is_numeric($params['id'])) {
			$error = new ValidationError('id must be numeric.', 0, $error);
		}

		if (!is_null($error)) {
			throw $error;
		}
	}

	public static function actualTableName() {
		$xoopsDB = $GLOBALS['xoopsDB'];
		return parent::acualTableName($xoopsDB, self::tableName);
	}

	/**
	 * @param int $forumId
	 * @return bool
	 */
	public function associateWithForum($forumId) {
		$latest = $this->getLatestTaskHistory();
		$latest->setParam(array(
			'forum_id' => $forumId
		));
		return !!$latest->update();
	}

	/**
	 * @return TaskHistory
	 */
	public function getLatestTaskHistory() {
		return TaskHistory::findLatest($this->db, $this->id);
	}

	/**
	 *
	 * @param int $page
	 * @param int $perPage
	 * @return HistoryList
	 */
	public function getHistories($page, $perPage) {
		return HistoryList::findByTaskId($this->db, $this->id, $page, $perPage);
	}

	/**
	 * Returns task name.
	 * @return string
	 */
	public function getName() {
		return $this->record['name'];
	}

	/**
	 * Returns source language.
	 * @return string
	 */
	public function getSourceLang() {
		return $this->record['source_lang'];
	}

	public function getSourceLangAsString() {
		$map = CommonUtil::getLanguageNameMap();
		return $map[$this->getSourceLang()];
	}

	/**
	 * Returns target language.
	 * @return string
	 */
	public function getTargetLang() {
		return $this->record['target_lang'];
	}

	public function getTargetLangAsString() {
		$map = CommonUtil::getLanguageNameMap();
		return $map[$this->getTargetLang()];
	}
}
