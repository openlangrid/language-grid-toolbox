<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once(dirname(__FILE__).'/Toolbox_CompositeKeyGenericHandler.class.php');

class CommunityResourceContentsObject extends XoopsSimpleObject {

	function CommunityResourceContentsObject() {
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('row', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('contents', XOBJ_DTYPE_STRING, true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}

	function getExpression() {
		return array('language' => $this->get('language'), 'exp' => $this->get('contents'));
	}
}

class CommunityResourceContentsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "user_dictionary_contents";
	var $mPrimary = "user_dictionary_id";
	var $mClass = "CommunityResourceContentsObject";
	var $mPrimaryAry = array('user_dictionary_id', 'language', 'row');

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		if(!is_numeric($limit) || $limit == 0){
			$limit = "9999999";
		}
	
		$ret = array();
		$dictionaryTable = $this->db->prefix('user_dictionary');
		
		$sql  = "SELECT * FROM `" . $this->mTable . '`';
		$sql .= " WHERE user_dictionary_id in (select user_dictionary_id from ".$dictionaryTable." where delete_flag = 0) ";
		if($criteria !== null && is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);
			
			if (trim($where)) {
				$sql .= " AND " . $where;
			}
			
			$sorts = array();
			foreach ($criteria->getSorts() as $sort) {
				$sorts[] = '`' . $sort['sort'] . '` ' . $sort['order']; 
			}
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . implode(',', $sorts);
			}
			
			if ($limit === null) {
				$limit = $criteria->getLimit();
			}
			
			if ($start === null) {
				$start = $criteria->getStart();
			}
		}
		else {
			if ($limit === null) {
				$limit = 0;
			}
			
			if ($start === null) {
				$start = 0;
			}
		}
		
		$result = $this->db->query($sql,$limit,$start);

		if (!$result) {
			return $ret;
		}

		while($row = $this->db->fetchArray($result)) {
			$obj =& new $this->mClass();
			$obj->assignVars($row);
			$obj->unsetNew();
			
			if ($id_as_key)	{
				$ret[$obj->get($this->mPrimary)] =& $obj;
			}
			else {
				$ret[]=&$obj;
			}
		
			unset($obj);
		}
	
		return $ret;


//		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

//		if (count($objects)) {
//			foreach (array_keys($objects) as $key) {
//				$objects[$key]->_loadContents();
//			}
//		}

//		return $objects;
	}

	function &getLanguages($userDictionaryId) {
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('user_dictionary_id', $userDictionaryId));
		$mCriteria->add(new Criteria('row', '0'));
		$mCriteria->add(new Criteria('delete_flag', '0'));

		$objects =& parent::getObjects($mCriteria);

		$languages = array();
		if (count($objects)) {
			foreach (array_keys($objects) as $key) {
				$languages[] = $objects[$key]->get('language');
			}
		}
		return $languages;
	}

	function getContentsCount($userDictionaryId) {
		$sql = '';
		$sql .= 'select count(*) as c ';
		$sql .= 'from ';
		$sql .= '(select row from '.$this->mTable;
		$sql .= '   where user_dictionary_id = \''.$userDictionaryId.'\'';
		$sql .= '     and delete_flag = \'0\'';
		$sql .= '   group by row';
		$sql .= '   having row > 0) as T';
		return parent::_getCount($sql);
	}

	function getCurrentMaxRow($userDictionaryId) {
		$sql = 'select max(row) as c from '.$this->mTable.' where user_dictionary_id = \''.$userDictionaryId.'\'';
		return parent::_getCount($sql);
	}

	function getObjectsByRows($userDictionaryId, $rows) {
		$sql = 'select * from '.$this->mTable.' where '
			.' user_dictionary_id = \''.$userDictionaryId.'\' and '
			.' delete_flag = \'0\' and '
			.' row IN ('.implode(',', $rows).')';

		$result = $this->db->query($sql);

		if (!$result) {
			return $ret;
		}

		while($row = $this->db->fetchArray($result)) {
			$obj =& new $this->mClass();
			$obj->assignVars($row);
			$obj->unsetNew();

			$ret[]=&$obj;

			unset($obj);
		}

		return $ret;
	}

	function allRealDeleteContents($userDictionaryId) {
		$sql = 'DELETE FROM '.$this->mTable.' WHERE `user_dictionary_id` = \''.$userDictionaryId.'\' AND `row` > 0';
		return $this->db->queryf($sql);
	}

	function realDeleteContentsByRow($userDictionaryId, $row) {
		$resultFlag = true;
		$sql = 'DELETE FROM '.$this->mTable.' WHERE `user_dictionary_id` = \''.$userDictionaryId.'\' AND `row` = \''.$row.'\'';
		if (!$this->db->queryf($sql)) {
			$resultFlag = false;
		}
		// ここからは連番を振り直すための処理
//		$sql = 'SELECT `row` FROM '.$this->mTable.' WHERE `user_dictionary_id` = \''.$userDictionaryId.'\' GROUP BY `row` ASC';
//		$count = 1;
//		$result = $this->db->queryf($sql);
//		if ($result) {
//			while ($row = $this->db->fetchArray($result)) {
//				if ($row['row'] == 0) {
//					continue;
//				}
//				$sql = 'UPDATE '.$this->mTable.' SET `row` = \''.$count.'\' WHERE `user_dictionary_id` = \''.$userDictionaryId.'\' AND `row` = \''.$row['row'].'\'';
//				$count++;
//				if (!$this->db->queryf($sql)) {
//					$resultFlag = false;
//				}
//			}
//		} else {
//			$resultFlag = false;
//		}
		// ここまでは連番を振り直すための処理
		return $resultFlag;
	}

// TODO:Toolbox_MultiGenericHandler.class.php
//	/**
//	 * @Overwride
//	 */
//	function _update(&$obj) {
//		$set_lists=array();
//		//$where = "";
//		$where_lists=array();
//
//		$arr = $this->_makeVars4sql($obj);
//
//		foreach ($arr as $_name => $_value) {
//			//if ($_name == $this->mPrimary) {
//			if (in_array($_name, $this->mPrimaryAry)) {
//				//$where = "`${_name}`=${_value}";
//				$where_lists[] = "`${_name}`=${_value}";
//			}
//			else {
//				$set_lists[] = "`${_name}`=${_value}";
//			}
//		}
//
//		$sql = @sprintf("UPDATE `" . $this->mTable . "` SET %s WHERE %s", implode(",",$set_lists), implode(" and ",$where_lists));
//
//		return $sql;
//	}
}
?>