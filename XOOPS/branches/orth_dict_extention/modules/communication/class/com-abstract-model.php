<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/common_util.php';

abstract class Com_AbstractModel {
	
	protected $params = array();
	protected $record = array();
	
	protected function _get($key) {
		$value = @$this -> record[$key];
		if(!$value) $value = @$this -> params[$key];
		return $value ? $value : "";
	}
	
	public function prefixedTableName() {
		return CommonUtil::prefixedTableName($this -> getTableName());
	}
	
	static public function find($class_name , $options = array()) {
		$tmpobj = new $class_name();
		$sql  = "
			SELECT
			  *
			FROM
			  {$tmpobj->prefixedTableName()}
		";
		$conditions = self::buildConditions($options['where']);
		if($conditions) {
			$sql .= "
			WHERE
				{$conditions}
			";
		}

		$xoopsDB =& Database::getInstance();
		if($result = $xoopsDB -> query($sql)) {
			$record = $xoopsDB -> fetchArray($result);
			if($record) return new $class_name($record);
		}
		return null;
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
		return join($conditions, ' AND ');
	}
	
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
	
	private function valuesForColumns($columns) {
		$values = array();
		$xoopsDB =& Database::getInstance();
		foreach ($columns as $column) {
			if (isset($this->params[$column])) {
				$values[] = $xoopsDB -> quoteString($this->params[$column]);
			} else {
				if($column == 'create_date' || $column == 'update_date') {
					$values[] = 'NOW()';
				} else if($column == "content_marker_created") {
					$values[] = 'NOW()';
				}else {
					$values[] = 'NULL';
				}
			}
		}
		return $values;
	}
	
	public function delete($xoopsDB, $conditions = array()) {
		if(count($conditions) == 0) return;
		$conditions = self::buildConditions($conditions);
		$sql = "
			DELETE FROM
			  {$this->prefixedTableName()}
			WHERE
			  {$conditions}
		";
		return $xoopsDB->queryF($sql);
	}
	
	static public function truncate($class_name) {
		$tmpobj = new $class_name();
		$sql = 'truncate '.$tmpobj -> prefixedTableName();
		$xoopsDB =& Database::getInstance();
		return $xoopsDB ->queryF($sql);
	}
	
	abstract protected function getTableName();
	abstract protected function getColumnsOnInsert();
	
}
?>