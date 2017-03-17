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
require_once(dirname(__FILE__).'/Toolbox_ObjectGenericHandler.class.php');

class BBS_TagSetObject extends XoopsSimpleObject {

	public $mExpressions = null;
	public $mExpressionsLoaded = false;

	public $mTags = null;
	public $mTagsLoaded = false;

	function BBS_TagSetObject() {
		$this->initVar('tag_set_id', XOBJ_DTYPE_INT, 0, false);
	}

	function _loadExpressions($modName) {
		$handler =& $this->_getExpressionsHandler($modName);
		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('tag_set_id', $this->get('tag_set_id')));
		$this->mExpressions =& $handler->getObjects($mCriteria);
		if ($this->mExpressions) {
			$this->mExpressionsLoaded = true;
		}
	}

	function _loadTags($modName) {
		$handler =& $this->_getTagsHandler($modName);
		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('tag_set_id', $this->get('tag_set_id')));
		$this->mTags =& $handler->getObjects($mCriteria);
		if ($this->mTags) {
			$this->mTagsLoaded = true;
		}
	}

	private function _getExpressionsHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_TagSetExpressionsHandler.class.php');
		$handler = new BBS_TagSetExpressionsHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}

	private function _getTagsHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_TagsHandler.class.php');
		$handler = new BBS_TagsHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}
}

class BBS_TagSetsHandler extends XoopsObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "tag_set_id";
	var $mClass = "BBS_TagSetObject";
	var $mModName = "";

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_tag_sets");
		$this->mModName = $moduleName;
	}

	function &get($tagSetId) {
		$object =& parent::get($tagSetId);
		if ($object) {
			$object->_loadExpressions($this->mModName);
			$object->_loadTags($this->mModName);
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects = parent::getObjects($criteria, $limit, $start, $id_as_key);
		foreach ($objects as $object) {
			$object->_loadExpressions($this->mModName);
			$object->_loadTags($this->mModName);
		}
		return $objects;
	}
}
?>