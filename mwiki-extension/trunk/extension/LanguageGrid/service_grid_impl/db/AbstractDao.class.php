<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridDefaultDictionaryBindDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridDefaultDictionarySettingDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridLangridServicesDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridLangridServicesConfigDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridTranslationBindDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridTranslationExecDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridTranslationPathDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridTranslationSetDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridTranslationOptionDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridUserDictionaryDAO.interface.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dao/ServiceGridUserDictionaryContentsDAO.interface.php');

require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridDefaultDictionaryBind.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridDefaultDictionarySetting.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridLangridService.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridTranslationBind.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridTranslationExec.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridTranslationPath.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridTranslationSet.class.php');
require_once(dirname(__FILE__).'/../../service_grid/db/dto/ServiceGridTranslationOption.class.php');


/**
 * <#if locale="en">
 * Data storage class
 * Keep one record of data in database table as variable
 * <#elseif locale="ja">
 * データ格納用クラス
 * データベーステーブルの１レコード分データを変数として保持する
 * </#if>
 */
abstract class AbstractDaoObject {
	/**
	 * <#if locale="en">
	 * Array variable for data storage
	 * <#elseif locale="ja">
	 * データ格納用の配列変数
	 * </#if>
	 */
	protected $mVar = array();
	protected $mIsNew = false;

	/**
	 * <#if locale="en">
	 * Constructor
	 * <#elseif locale="ja">
	 * コンストラクタ
	 * </#if>
	 */
	function __construct() {
	}

	function initVar($key) {
		$this->mVar[$key] = '';
	}

	/**
	 * <#if locale="en">
	 * Set data
	 * @param $key Field name
	 * @param $val Value
	 * <#elseif locale="ja">
	 * データをセット
	 * @param $key フィールド名
	 * @param $val 値
	 * </#if>
	 */
	function set($key, $value) {
		if (isset($this->mVar[$key])) {
			$this->mVar[$key] = $value;
		}
	}

	/**
	 * <#if locale="en">
	 * Get data
	 * @param $key Field name
	 * <#elseif locale="ja">
	 * データを取得
	 * @param $key フィールド名
	 * </#if>
	 */
	function get($key) {
		if (isset($this->mVar[$key])) {
			return $this->mVar[$key];
		}
		return null;
	}

	/**
	 * <#if locale="en">
	 * Judge whether the field name specified in the parameter is the defined field or not
	 * <#elseif locale="ja">
	 * 引数で指定されたフィールド名が、定義されたものであるか否か
	 * </#if>
	 */
	function isField($key) {
		return isset($this->mVar[$key]);
	}

	function getVars() {
		return $this->mVar;
	}

	function isNew() {
		return $this->mIsNew;
	}
	function setIsNew($flg) {
		$this->mIsNew = $flg;
	}
}

/**
 * <#if locale="en">
 * Data access class
 * Provide basic access to database table
 * <#elseif locale="ja">
 * データアクセスクラス
 * データベーステーブルに対して基本的なアクセスを提供する
 * </#if>
 */
abstract class AbstractDao {
	protected $db = null;

	function __construct($db) {
		$this->db = $db;
		$this->mTable = $this->db->tableName($this->mTable);
	}

	/**
	 * <#if locale="en">
	 *
	 * <#elseif locale="ja">
	 * PK指定で一本釣り
	 * </#if>
	 */
	function get($pk) {
		$_sql = 'SELECT * FROM '.$this->mTable.' WHERE `%s` = \'%s\'';
		$sql = sprintf($_sql, $this->sqlEscape($this->mPrimary), $this->sqlEscape($pk));
		$objects =& $this->selectList($sql);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0];
	}

	/**
	 * <#if locale="en">
	 * Search
	 *   $wheres -> field, VALUR -> value, AND condition
	 * <#elseif locale="ja">
	 * 検索
	 *   $wheres のKEYにフィールド、VALURに値で、すべてAND条件でSELECT文を発行
	 * </#if>
	 */
	function search($wheres, $order = null) {
		$obj = new $this->mClass;
		$_sql = 'SELECT * FROM '.$this->mTable;
		$where = array();
		foreach ($wheres as $key => $val) {
			if ($obj->isField($key)) {
				$where[] = sprintf('`%s` = \'%s\'', $key, $this->sqlEscape($val));
			}
		}
		if (count($where) > 0) {
			$sql = $_sql . ' WHERE ' . implode(' AND ', $where);
		} else {
			$sql = $_sql;
		}

		if ($order) {
			if (is_array($order)) {
	/*
	 * <#if locale="en">
	 * Not yet implemented
	 * <#elseif locale="ja">
	 * ちょっと待って。。。
	 * </#if>
	 */
			} else {
				$sql .= ' ORDER BY '.$order;
			}
		}

		return $this->selectList($sql);
	}

	/**
	 * <#if locale="en">
	 * INSERT
	 *   Return auto_increment of PK if $isNew is true
	 * <#elseif locale="ja">
	 * INSERT文を発行
	 *   $isNewがtrueの場合（で処理が成功した場合）、PKのauto_incrementを返す
	 * </#if>
	 */
	function insert($array, $isNew = false) {
		$sql = 'INSERT INTO '.$this->mTable.' SET '.$this->array2Query($array);
		$rs = $this->db->query($sql);
		if (!$rs) {
			die('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}
		if ($isNew) {
			return $this->db->insertId();
		} else {
			return true;
		}
	}

	function update($array) {
		$sql = 'UPDATE '.$this->mTable.' SET '.$this->array2Query($array);
		$sql .= ' WHERE '.$this->mPrimary.' = '.$this->sqlEscape($array[$this->mPrimary]);

		$rs = $this->db->query($sql);
		if (!$rs) {
			die('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		return true;
	}

	/**
	 * <#if locale="en">
	 * DELETE
	 * <#elseif locale="ja">
	 * DELETE文を発行
	 * </#if>
	 */
	function delete($array) {
//		$pk = $object->get($this->mPrimary);
//		if ($pk == null || $pk == '') {
//			return false;
//		}
		$sql = 'DELETE FROM '.$this->mTable.' WHERE '.$this->array2Query($array, ' and ');
//		$sql = sprintf($_sql, $this->Primary, $this->sqlEscape($pk));

		return $this->db->query($sql);
	}

	/**
	 * <#if locale="en">
	 * Change associative array into SQL format
	 * <#elseif locale="ja">
	 * 連想配列ArrayをSQL形式に変換
	 * </#if>
	 */
	protected function array2Query($ary, $querySep = ', ') {
		$token = array();
		foreach ($ary as $key => $val) {
			$token[] = sprintf('`%s` = \'%s\'', $key, $this->sqlEscape($val));
		}
		$query = implode($querySep, $token);
		return $query;
	}

	/**
	 * <#if locale="en">
	 * SQL escape the strings
	 * <#elseif locale="ja">
	 * 文字列をSQLエスケープ
	 * </#if>
	 */
	protected function sqlEscape($string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string);
	}

	/**
	 * <#if locale="en">
	 * Use SELECT and return the result as Array
	 * <#elseif locale="ja">
	 * SELECT文を発行して、結果をArrayで返す
	 * </#if>
	 */
	protected function selectList($sql) {
		$objects = array();
		$rs =& $this->db->query($sql);
		while ($row = $this->db->fetchRow($rs)) {
			$vo = new $this->mClass;
			foreach ($row as $key => $val) {
				$vo->set($key, $val);
			}
			$objects[] = $vo;
		}
		return $objects;
	}

	/**
	 * <#if locale="en">
	 * Generate the data storage class interface
	 * <#elseif locale="ja">
	 * データ格納クラスのインスタンスを生成
	 * </#if>
	 */
	function create($isNew = true) {
		$obj = new $this->mClass();
		$obj->setIsNew(true);
		return $obj;
	}
}
/**
 * <#if locale="en">
 * Data access class (support composite key)
 * <#elseif locale="ja">
 * データアクセスクラス（複合キー対応版）
 * </#if>
 */
abstract class AbstractDaoComposite extends AbstractDao {
	function get($compsite) {
		$_sql = 'SELECT * FROM '.$this->mTable.' WHERE ';
		$where = array();
		foreach ($this->mPrimaryAry as $pk) {
			$val = isset($compsite[$pk]) ? $compsite[$pk] : '';
			$where[] = sprintf('`%s` = %s', $pk, $this->sqlEscape($val));
		}
		$sql = $_sql . implode(' AND ', $where);
		$objects =& $this->selectList($sql);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0];
	}

	function delete($object) {
		$_sql = 'DELETE FROM '.$this->mTable.' WHERE ';
		$where = array();
		foreach ($this->mPrimaryAry as $pk) {
			$val = $object[$pk];
			$where[] = sprintf('`%s` = %s', $pk, $this->sqlEscape($val));
		}
		$sql = $_sql . implode(' AND ', $where);
		return $this->db->query($sql);
	}

	function update($array) {
		$_sql = 'UPDATE '.$this->mTable.' SET '.$this->array2Query($array).' WHERE ';
		$where = array();
		foreach ($this->mPrimaryAry as $pk) {
//			$val = $object->get($pk);
			$val = $array[$pk];
			$where[] = sprintf('`%s` = %s', $pk, $this->sqlEscape($val));
		}
		$sql = $_sql . implode(' AND ', $where);
		$rs = $this->db->query($sql);
		if (!$rs) {
			die('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		return true;
	}
}
?>
