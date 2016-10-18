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

class BBS_TopicAccessObject extends XoopsSimpleObject {

	function BBS_TopicAccessObject() {
		$this->initVar('topic_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('groupid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('all', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('can_post', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('can_edit', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('can_delete', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
	}
}

class BBS_TopicAccessHandler extends XoopsObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "topic_id";
	var $mClass = "BBS_TopicAccessObject";
	
	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_topic_access");
	}
}
?>