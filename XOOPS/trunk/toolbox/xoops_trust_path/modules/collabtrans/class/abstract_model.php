<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
//  ------------------------------------------------------------------------ //
require_once dirname(__FILE__).'/validation_error.php';
require_once dirname(__FILE__).'/user.php';

abstract class AbstractModel {

	protected $params = array();
	protected $record = array();
	protected $id;
	protected $owner;

	protected function __construct($record = null) {
		if($record) {
			$this -> record = $record;
			$this -> id = $record['id'];
			$uid = $record['creator'];
			$this -> owner = new User($uid);
		}
	}

	protected function setParam(array $params) {
		$this -> params = $params;
	}

	public function prefixedTableName() {
		return self::acualTableName($this -> getTableName());
	}

	protected function _get($key) {
		$value = @$this -> record[$key];
		if(!$value) $value = @$this -> params[$key];
		return $value ? $value : "";
	}

	public function getId() {
		return $this -> id;
	}

	public function getUID() {
		return $this -> owner -> getId();
	}

	public function getUserName() {
		return $this -> owner -> getName();
	}

	/**
	 * Inserts a new record.
	 * @return void
	 */
	public function insert($xoopsDB) {

		$clause = implode(', ', $this -> getColumnsOnInsert());
		$valuesClause = implode(', ', $this -> valuesForColumns($this -> getColumnsOnInsert()));

		$sql = "
			INSERT INTO
			  {$this->prefixedTableName()}
			(
			  {$clause}
			)
			VALUES (
			  {$valuesClause}
			)
		";

		$bool = $xoopsDB -> queryF($sql);
		$obj = $this -> findById(intval($xoopsDB -> getInsertId()));
		$this -> __construct($obj -> record);
		return $bool;
	}

	public function delete($xoopsDB, $logical = true) {
		if($logical) {
			$this -> deleteLogical($xoopsDB);
		} else {
			$this -> deletePhisycal($xoopsDB);
		}
		return $xoopsDB->getAffectedRows();
	}

	private function deleteLogical($xoopsDB) {
		$sql = "
			UPDATE
			  {$this->prefixedTableName()}
			SET
			  update_date = NOW(),
			  delete_flag = true
			WHERE
			  id = %d
			AND
			  delete_flag = false
		";
		$sql = sprintf($sql, $this->id);
		$xoopsDB->queryF($sql);
	}

	private function deletePhisycal($xoopsDB) {
		$sql = "
			DELETE FROM
			  {$this->prefixedTableName()}
			WHERE
			  id = %d
		";
		$sql = sprintf($sql, $this->id);
		$xoopsDB->queryF($sql);
	}

	private function valuesForColumns($columns) {
		$values = array();

		$xoopsDB =& Database::getInstance();
		foreach ($columns as $column) {
			if (isset($this->params[$column])) {
				$values[] = $xoopsDB -> quoteString($this->params[$column]);
			} else {
				if($column == 'create_date' || $column == 'update_date') {
					$values[] = 'NOW()';
				} else {
					$values[] = 'NULL';
				}
			}
		}
		return $values;
	}

	static public function createFromParams($class_name, array $params) {
		$entity = new $class_name();
		$entity->setParam($params);
		return $entity;
	}

	static public function findById($class_name, $id) {
		if (!is_numeric($id)) {
			throw new ValidationError('ID must be numeric value.');
		}

		$tmpobj = new $class_name();
		$sql  = "
			SELECT
			  *
			FROM
			  {$tmpobj->prefixedTableName()}
			WHERE
			  id = %d
			AND
			  delete_flag = false
		";
		$sql = sprintf($sql, (int)$id);
		$xoopsDB =& Database::getInstance();
		if($result = $xoopsDB -> query($sql)) {
			$record = $xoopsDB -> fetchArray($result);
			return new $class_name($record);
		}

		return null;
	}

	static public function findFirst($class_name, $options = null) {
		$results = self::findAll($class_name, $options);
		if(count($results) > 0)	return $results[0];
		return null;
	}

	static public function findAll($class_name, $options = null) {
		$results = array();
		$tmpobj = new $class_name();
		$conditions = self::buildConditions(@$options['where']);
		$sql = "
			SELECT
			  *
			FROM
				{$tmpobj->prefixedTableName()}
			WHERE
				{$conditions}
		";
		if(@$options['order'] && count($options['order']) > 0) {
			$sql .= " ORDER BY ".join(',', $options['order']);
		}

		if(@$options['limit'] && $options['limit'] > 0) {
			$sql .= " LIMIT ".$options['limit'];
		}

		if(@$options['offset']) {
			$sql .= " OFFSET ".$options['offset'];
		}

		$xoopsDB =& Database::getInstance();
		if($resultSet = $xoopsDB->query($sql)) {
			while($record = $xoopsDB->fetchArray($resultSet)) {
				array_push($results, new $class_name($record));
			}
		}
		return $results;
	}

	static public function buildConditions($where) {
		$xoopsDB =& Database::getInstance();
		$conditions = array();
		if(!is_null($where)) {
			foreach($where as $key => $value) {
				if(!$value) continue;

				$key = mysql_real_escape_string($key);
				if(is_array($value)) {
					$val = $xoopsDB->quoteString($value[1]);
					$sym = $value[0];
					$conditions[] = "{$key} {$sym} {$val}";
				} else {
					$conditions[] = "{$key} = {$xoopsDB->quoteString($value)}";
				}
			}
		}
		$conditions[] =  "delete_flag = false";
		return join($conditions, ' AND ');
	}

	static public function truncate($class_name) {
		$tmpobj = new $class_name();
		$sql = 'truncate '.$tmpobj -> prefixedTableName();
		$xoopsDB =& Database::getInstance();
		return $xoopsDB ->queryF($sql);
	}

	/**
	 * Returns prefixed table name.
	 * @param $xoopsDB
	 * @param string $tableName
	 * @return string
	 */
	static public function acualTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		$myModuleDir = realpath(dirname(__FILE__) . '/../');
		$myModuleName = basename($myModuleDir);
		return "{$xoopsDB->prefix}_{$myModuleName}_{$tableName}";
	}

	abstract protected function getTableName();
	abstract protected function getColumnsOnInsert();

	protected function getColumnsOnUpdate() {
		return array();
	}

}
?>