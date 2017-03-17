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

class CommunityResourceObject extends XoopsSimpleObject {

	var $languages = null;
	var $m_languagesLoadedFlag = false;
	var $contentsCount = null;
	var $m_contentsCountLoadedFlag = false;
	var $contents = null;
	var $m_contentsLoadedFlag = false;
	var $permission = null;
	var $m_permissionLoadedFlag = false;

	var $readPermission = false;
	var $editPermission = false;

	function CommunityResourceObject() {
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('type_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('dictionary_name', XOBJ_DTYPE_STRING, true, 255);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('update_date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('deploy_flag', XOBJ_DTYPE_STRING, '0', true, 1);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}

	function getLanguages() {
		return $this->languages;
	}

	function getContentsCount() {
		return $this->contentsCount;
	}

	function getContents() {
		return $this->contents;
	}

	function getPermission() {
		return $this->permission;
	}

//	function _loadLanguages() {
//		require_once(dirname(__FILE__).'/CommunityResourceContentsHandler.class.php');
//		$handler =& new CommunityResourceContentsHandler($GLOBALS['xoopsDB']);
//		$this->languages =& $handler->getLanguages($this->get('user_dictionary_id'));
//		$this->m_languagesLoadedFlag = true;
//	}

	function _loadOuterMetaInformations() {
		require_once(dirname(__FILE__).'/CommunityResourceContentsHandler.class.php');
		$handler =& new CommunityResourceContentsHandler($GLOBALS['xoopsDB']);

		$this->languages =& $handler->getLanguages($this->get('user_dictionary_id'));
		$this->m_languagesLoadedFlag = true;

		$this->contentsCount = $handler->getContentsCount($this->get('user_dictionary_id'));
		$this->m_contentsCountLoadedFlag = true;
	}

	function _loadContents() {
		require_once(dirname(__FILE__).'/CommunityResourceContentsHandler.class.php');
		$handler =& new CommunityResourceContentsHandler($GLOBALS['xoopsDB']);
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('user_dictionary_id', $this->get('user_dictionary_id')));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$this->contents =& $handler->getObjects($mCriteria);

		$this->m_contentsLoadedFlag = true;
	}

	function _loadPermission() {
		require_once(dirname(__FILE__).'/CommunityResourcePermissionHandler.class.php');
		$handler =& new CommunityResourcePermissionHandler($GLOBALS['xoopsDB']);
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('user_dictionary_id', $this->get('user_dictionary_id')));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $handler->getObjects($mCriteria);
		if (count($objects)) {
			$this->permission =& $objects[0];
		} else {
			$this->permission = null;
		}

		$this->m_permissionLoadedFlag = true;
	}

	function toResponseConvert() {
		if (!$this->m_permissionLoadedFlag) {
			$this->_loadPermission();
		}
		if (!$this->m_languagesLoadedFlag) {
			$this->_loadOuterMetaInformations();
		}

		$res = array(
			'name' => $this->get('dictionary_name'),
			'languages' => $this->languages
		);

		$read = array('permissionType' => 'user', 'user' => $this->get('user_id'));
		$edit = array('permissionType' => 'user', 'user' => $this->get('user_id'));
		if ($this->permission != null && $this->permission->get('view') == '1') {
			$read = array('permissionType' => 'all');
		}
		if ($this->permission != null && $this->permission->get('edit') == '1') {
			$edit = array('permissionType' => 'all');
		}
		$res['readPermission'] = $read;
		$res['editPermission'] = $edit;
		return $res;
	}
}

class CommunityResourceHandler extends XoopsObjectGenericHandler {

	var $mTable = "user_dictionary";
	var $mPrimary = "user_dictionary_id";
	var $mClass = "CommunityResourceObject";

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			foreach (array_keys($objects) as $key) {
				$objects[$key]->_loadOuterMetaInformations();
				$objects[$key]->_loadContents();
				$objects[$key]->_loadPermission();
			}
		}

		return $objects;
	}

	function &getList($criteria = null, $limit = null, $start = null, $id_as_key = false, $hasPermission = true, $hasContents = false) {
		if($limit == null || intval($limit)== 0){$limit = 99999999;}
		$objects =& parent::getObjects($criteria, $limit, $start);

		if (count($objects)) {
			foreach (array_keys($objects) as $key) {
				$objects[$key]->_loadOuterMetaInformations();
				if ($hasPermission) {
					$objects[$key]->_loadPermission();
				}
				if ($hasContents) {
					$objects[$key]->_loadContents();
				}
			}
		}

		return $objects;
	}

	public function search($criteria, $offset = null, $limit = null) {
		$contentsTable = $this->db->prefix('user_dictionary_contents');

		$sql = "";
		$sql .= " SELECT ";
		$sql .= "     DISTINCT `user_dictionary_id` ";
		$sql .= " FROM ";
		$sql .= "     `".$this->mTable."` ";
		$sql .= "     LEFT JOIN ";
		$sql .= "     ( ";
		$sql .= "     SELECT ";
		$sql .= "         `user_dictionary_id`, ";
		$sql .= "         language ";
		$sql .= "     FROM ";
		$sql .= "         `".$contentsTable."` ";
		$sql .= "     WHERE ";
		$sql .= "         `row`= '0' AND ";
		$sql .= "         `delete_flag` = '0' ";
		$sql .= "     ) AS C USING(`user_dictionary_id`) ";

		if($criteria !== null && is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);

			if (trim($where)) {
				$sql .= " WHERE " . $where;
			}
		}

		$result = $this->db->query($sql, $limit, $offset);

		$ids = array();

		if ($result) {
			while($row = $this->db->fetchArray($result)) {
				$ids[] = $row['user_dictionary_id'];
			}
		}

		if (count($ids) == 0) {
			return null;
		}

		$mCriteria =& new CriteriaCompo();
		foreach ($ids as $id) {
			$mCriteria->add(new Criteria('user_dictionary_id', $id), 'OR');
		}

		return $this->getList($mCriteria);
	}


//	function _insert(&$obj) {
//		$sql = parent::_insert($obj);
//		echo '<pre>'.$sql.'</pre>';
//		return $sql;
//	}
}
?>