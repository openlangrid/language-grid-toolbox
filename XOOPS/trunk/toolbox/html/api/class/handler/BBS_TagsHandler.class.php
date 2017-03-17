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

class BBS_TagObject extends XoopsSimpleObject {

	public $mExpressions = null;
	public $mExpressionsLoaded = false;

	function BBS_TagObject() {
		$this->initVar('tag_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tag_set_id', XOBJ_DTYPE_INT, 0, false);
	}

	function _loadExpressions($modName) {
		$handler =& $this->_getExpressionsHandler($modName);
		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('tag_id', $this->get('tag_id')));
		$this->mExpressions =& $handler->getObjects($mCriteria);
		if ($this->mExpressions) {
			$this->mExpressionsLoaded = true;
		}
	}

	private function _getExpressionsHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_TagExpressionsHandler.class.php');
		$handler = new BBS_TagExpressionsHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}
}

class BBS_TagsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "";
	var $mModName = "";
	var $mPrimary = "tag_id";
	var $mClass = "BBS_TagObject";
	var $mPrimaryAry = array('tag_id', 'tag_set_id');

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_tags");
		$this->mModName = $moduleName;
	}

	function &get($tagSetId, $tagId) {
		$object =& parent::get(array('tag_set_id'=>$tagSetId, 'tag_id'=>$tagId));
		if ($object) {
			$object->_loadExpressions($this->mModName);
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects = parent::getObjects($criteria, $limit, $start, $id_as_key);
		foreach ($objects as $object) {
			$object->_loadExpressions($this->mModName);
		}
		return $objects;
	}

//	function insert(&$obj, $force = false) {
//		parent::insert(&$obj, $force);
//		if ($obj->isNew()) {
////			$obj->setVar($this->mPrimary, $this->db->getInsertId());
//			$obj->set('tag_id', $this->db->getInsertId());
//		}
//	}

}
?>