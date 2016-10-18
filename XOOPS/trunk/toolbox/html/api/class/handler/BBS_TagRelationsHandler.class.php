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

class BBS_TagRelationObject extends XoopsSimpleObject {

	function BBS_TagRelationObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tag_set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('tag_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('post_id', XOBJ_DTYPE_INT, 0, false);
	}
}

class BBS_TagRelationsHandler extends XoopsObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "id";
	var $mClass = "BBS_TagRelationObject";
	var $mModName = "";

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_tag_relations");
		$this->mModName = $moduleName;
	}

	public function findByPostId($postId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('post_id', $postId));
		return $this->find($c);
	}

	public function findByTagId($tagId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('tag_id', $tagId));
		return $this->find($c);
	}

	protected function find($criteria) {
		$objects = parent::getObjects($criteria);
		if ($objects == null || is_array($objects) === false) {
			return false;
		}
		return $objects;
	}

	public function deleteByPostId($postId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('post_id', $postId));
		return parent::deleteAll($c, true);
	}
}
?>