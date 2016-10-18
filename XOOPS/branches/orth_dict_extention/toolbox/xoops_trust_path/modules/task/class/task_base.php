<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__) . '/../../collabtrans/class/validation_error.php';

abstract class TaskBase {

	/**
	 * @var XoopsMySQLDatabase
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $prefixedTableName;

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $record;

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @var array
	 */
	protected $columnsOnInsert;

	/**
	 * @var array
	 */
	protected $columnsOnUpdate;

	/**
	 * Constructor.
	 * @param array $record
	 */
	protected function __construct($db, $tableName, array $record) {
		$this->db = $db;
		$this->record = $record;
		$this->prefixedTableName = self::acualTableName($this->db, $tableName);

		if (isset($this->record['id'])) {
			$this->id = $this->record['id'];
		}
	}

	/**
	 * Sets parameters.
	 * @param array $params
	 * @return void
	 */
	public function setParam(array $params) {
		unset($params['id']);
		$this->validate($params);
		$this -> params = $params;
	}

	/**
	 * Merges this instance's params and $params.
	 * @param array $params
	 * @return void
	 */
	public function setAttributes(array $params) {
		$this->validate($params);
		$this->params = array_merge($this->params, $params);
	}

	/**
	 * Check if this record's ID of creator equals to $targetUserId
	 * @param int $targetUserId
	 * @return bool
	 */
	public function isOwner($targetUserId) {
		return $this->record['creator'] == $targetUserId;
	}

	/**
	 * Inserts a new record.
	 * @return bool
	 */
	public function insert() {
		$clause = implode(', ', $this->columnsOnInsert) . ', create_date';

		// values clause
		$values = array();
		foreach ($this->columnsOnInsert as $column) {
			if (isset($this->params[$column])) {
				$values[] = $this->db->quoteString(unescape_magic_quote($this->params[$column]));
			} else {
				$values[] = 'NULL';
			}
		}
		$values[] = 'NOW()';
		$valuesClause = implode(', ', $values);

		$sql = "
			INSERT INTO
			  {$this->prefixedTableName}
			(
			  {$clause}
			)
			VALUES (
			  {$valuesClause}
			)
		";

		$bool = $this->db->queryF($sql);
		$this->id = $this->db->getInsertId();
		return $bool;
	}

	/**
	 * Update a record mapped to this instance.
	 * @return int the numbers of affected rows.
	 */
	public function update() {
		$params = array_merge($this->record, $this->params);

		$set = array();
		foreach ($this->columnsOnUpdate as $column) {
			if (isset($params[$column])) {
				$set[] = "{$column} = " . $this->db->quoteString($params[$column]);
			} else {
				$set[] = "{$column} = NULL";
			}
		}
		$set[] = 'update_date = NOW()';
		$setClause = implode(', ', $set);

		$sql = "
			UPDATE
			  {$this->prefixedTableName}
			SET
			  {$setClause}
			WHERE
			  id = %d
			AND
			  delete_flag = false
		";
		$sql = sprintf($sql, $this->id);

		$affected = 0;
		if ($this->db->queryF($sql)) {
			$affected = $this->db->getAffectedRows();
		}
		return $affected;
	}

	/**
	 * Finds a record identified by given ID from database.
	 * @param $xoopsDB
	 * @param int $id
	 * @param string $tableName not prefixed table name.
	 * @return array
	 * @throws ValidationError if $id is not numeric.
	 */
	protected static function findByIdBase($xoopsDB, $id, $tableName) {
		if (!is_numeric($id)) {
			throw new ValidationError('ID must be numeric value.');
		}

		$table = self::acualTableName($xoopsDB, $tableName);
		$sql  = "
			SELECT
			  *
			FROM
			  {$table}
			WHERE
			  id = %d
			AND
			  delete_flag = false
		";
		$sql = sprintf($sql, (int)$id);

		if (($result = $xoopsDB->query($sql)) && ($record = $xoopsDB->fetchArray($result))) {
			return $record;
		} else {
			return null;
		}
	}

	/**
	 * Finds all records from database.
	 * @param $xoopsDB
	 * @param string $className
	 * @param string $tableName not prefixed table name.
	 * @param array $options criteria
	 * @return array null was returned if no records found.
	 */
	protected static function findAllBase(
			$xoopsDB, $className, $tableName, array $options) {

		$table = self::acualTableName($xoopsDB, $tableName);

		$sql = "
			SELECT
			  *
			FROM
			  {$table}
			WHERE
			  delete_flag = false
		";

		// where clause
		if (@$options['wheres']) {
			while ($pair = each($options['wheres'])) {
				$sql .= " AND " . $pair['key'] . " = " . $pair['value'];
			}
		}

		// order by clause
		if (is_string(@$options['orderby'])) {
			$sql .= " ORDER BY {$options['orderby']}";
		}

		if ($result = $xoopsDB->query($sql)) {
			$entities = array();
			while ($record = $xoopsDB->fetchArray($result)) {
				$entities[] = new $className($xoopsDB, $record);
			}
			return $entities;
		} else {
			return null;
		}
	}

	/**
	 * Creates instance.
	 * @param XoopsMySQLDatabase $db
	 * @param string $className
	 * @param array $params Set to instance.
	 * @return TranslationPath
	 * @throws ValidationError
	 */
	protected static function createFromParamsBase(XoopsMySQLDatabase $db, $className, array $params) {
		$entity = new $className($db, array());
		$entity->setParam($params);
		return $entity;
	}

	abstract protected static function validate(array $params);

	/**
	 * Returns prefixed table name.
	 * @param $xoopsDB
	 * @param string $tableName
	 * @return string
	 */
	protected static function acualTableName($xoopsDB, $tableName) {
		return $xoopsDB->prefix($GLOBALS['mydirname'] . '_' . $tableName);
	}

	/**
	 * Returns ID.
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int timestamp
	 */
	private function getCreateDateInternal() {
		return strtotime($this->record['create_date']);
	}

	/**
	 * @return string
	 */
	public function getCreateDate() {
		return CommonUtil::formatTimestamp($this->getCreateDateInternal(), _MD_TASK_DATE_FORMAT);
	}

	/**
	 * @return string
	 */
	public function getCreateTime() {
		return CommonUtil::formatTimestamp($this->getCreateDateInternal(), _MD_TASK_TIME_FORMAT);
	}

	/**
	 * Returns creator.
	 * @return int
	 */
	protected function getCreatorInternal() {
		return $this->record['creator'];
	}

	public function getCreator() {
		$handler = new XoopsUserHandler($this->db);
		$creator = $handler->get($this->getCreatorInternal());
		if (strlen($creator->get('name')) > 0) {
			return $creator->get('name');
		} else {
			return $creator->get('uname');
		}
	}

	/**
	 * Returns modifier.
	 * @return int
	 */
	public function getModifier() {
		return $this->record['modifier'];
	}
}
