<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
define('_LEGACY_PREVENT_EXEC_COMMON_', 1);
require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__).'/../exception/UnsupportedLanguagePairException.php');
$root =& XCube_Root::getSingleton();
$root->mController->executeCommonSubset();
class DictionaryDAO{
	private $db;
	private $typeId;
	private $dicId;
	protected $tableName;

	public function __construct($dictionaryName, $typeId){
		$this->db = Database::getInstance();
		if (!function_exists('mysql_set_charset')) {
			mysql_query("set names utf8");
		} else {
			mysql_set_charset('utf8');
		}
		$this->typeId = $typeId;
		$this->tableName = $this->db->prefix('user_dictionary_contents');
		$this->dicId = $this->getDicId($dictionaryName, $typeId);
		if(!isset($this->dicId)){
			throw new Exception("Dictionary '" . $dictionaryName . "' is not found.");
		}
	}

	public function getDBInstance(){
		return $this->db;
	}

	public function searchSameRowTargetWords($headWordRows, $targetLang, $offset, $count){
		$sql = '(';
		$i = 0;
		foreach($headWordRows as $row => $contents){
			$sql .= "(user_dictionary_id = ". intval($this->dicId). " and row = ". intval($row). ")";
			$i++;
			if($i >= count($headWordRows)){
				break;
			}
			$sql .= " or ";
		}
		$sql .= ')';

		$results = $this->db->query(
			'select row, contents from ' . $this->tableName . ' '
				. 'where' . ' '
					. $sql . ' '
			 		. 'and ' . 'delete_flag = 0' . ' '
			 		. 'and ' . "language = '" . $this->escape($targetLang) . "' "
			 		. 'limit ' . intval($offset) . ', ' . intval($count)
		);

		$targetRows = array();
		while($result = $this->db->fetchRow($results)){
			$targetRows[$result[0]] = array($result[1]);
		}

		return $targetRows;
	}

	public function searchSourceWords($headLang, $headWord, $matchingMethod, $offset, $count){
		$result = $this->db->query($this->makeSelectOriginalCententsSQL(
			$this->dicId, $headLang, $headWord, $matchingMethod, $offset, $count
		));
		$origs = array();
		while($row = $this->db->fetchRow($result)){
			$origs[$row[1]] = $row[2];
		}
		return $origs;
	}

	public function getUpdateDate(){
		$dicTableName = $this->db->prefix('user_dictionary');
		return $this->db->query(
			'select update_date from ' . $dicTableName . ' '
			. 'where user_dictionary_id=' . intval($this->dicId) . ' '
			. 'and delete_flag=0'
		);
	}

	public function getLanguages(){
		$sql = '
			SELECT	DISTINCT language
			FROM	'.$this->tableName.'
			WHERE	user_dictionary_id = \'%d\'
			AND		delete_flag = \'0\'
		';
		$sql = sprintf($sql, intval($this->dicId));
		return $this->db->query($sql);
	}

	private function getDicId($dictionaryName, $typeId){
		$dicTableName = $this->db->prefix('user_dictionary');
		$sql = '
			SELECT	`user_dictionary_id`
			FROM	'.$dicTableName.'
			WHERE	`dictionary_name` = \'%s\'
			AND		`delete_flag` = \'0\'
			AND		`type_id` = %d
		';
		$sql = sprintf($sql, $this->escape($dictionaryName), intval($typeId));
		$result = $this->db->query($sql);
		$row = $this->db->fetchRow($result);
		return $row[0];
	}

	private function makeSelectOriginalCententsSQL(
		$dictionaryId, $headLang, $value, $matchingMethod, $offset, $count)
	{
		$sql  = '
			SELECT	`user_dictionary_id`, `row`, `contents`
			FROM	'.$this->tableName.'
			WHERE	`user_dictionary_id` = \'%d\'
			AND		`language` = \'%s\'
			AND		`delete_flag` = \'0\'
			AND		`row` > 0
		';
		switch ($matchingMethod) {
		case 'regexp':
			$sql .= ' AND `contents` regexp \'%s\'';
			break;
		case 'partial':
			$sql .= ' AND `contents` LIKE \'%%%s%%\'';
			break;
		case 'prefix':
			$sql .= ' AND `contents` LIKE \'%s%%\'';
			break;
		case 'suffix':
			$sql .= ' AND `contents` LIKE \'%%%s\'';
			break;
		default:
		case 'complete':
			$sql .= ' AND `contents` = \'%s\'';
			break;
		}
		$sql .= ' LIMIT %d, %d';

		$sql = sprintf($sql, intval($dictionaryId), $this->escape($headLang)
						, $this->escape($value), intval($offset)
						, intval($count));

		return $sql;
	}
	private function escape($str) {
		if (get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		$str = mysql_real_escape_string($str);
		return $str;
	}
}
?>
