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

class BBS_TagSetExpressionObject extends XoopsSimpleObject {

	function BBS_TagSetExpressionObject() {
		$this->initVar('tag_set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('expression', XOBJ_DTYPE_STRING, '', true);
	}
}

class BBS_TagSetExpressionsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "";
	var $mModName = "";
	var $mPrimary = "";
	var $mClass = "BBS_TagSetExpressionObject";
	var $mPrimaryAry = array('tag_set_id', 'language_code');

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_tag_set_expressions");
		$this->mModName = $moduleName;
	}
}
?>