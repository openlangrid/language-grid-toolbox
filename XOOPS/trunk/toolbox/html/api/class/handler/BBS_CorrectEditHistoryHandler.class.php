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

class BBS_CorrectEditHistoryObject extends XoopsSimpleObject {

	function BBS_CorrectEditHistoryObject() {
		$this->initVar('bbs_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bbs_item_type_cd', XOBJ_DTYPE_STRING, '', true, 2);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 16);
		$this->initVar('history_count', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('proc_type_cd', XOBJ_DTYPE_STRING, '', false, 1);
		$this->initVar('bbs_text', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('create_date', XOBJ_DTYPE_INT, false);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', false, 1);
	}
}

class BBS_CorrectEditHistoryHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "";
	var $mPrimary = "cat_id";
	var $mClass = "BBS_CorrectEditHistoryObject";
	var $mPrimaryAry = array("bbs_id","bbs_item_type_cd","language_code","history_count");
	
	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_bbs_correct_edit_history");
	}
	
//
//
//	function registCategoryCreateLog($categoryBody) {
//		$bbsId = $categoryBody->get('cat_id');
//		$languageCode = $categoryBody->get('language_code');
//		registCreateLog($bbsId, "01", $languageCode, $categoryBody->get('title'));
//		registCreateLog($bbsId, "02", $languageCode, $categoryBody->get('description'));
//	}
//	function registForumCreateLog($forumBody) {
//		$bbsId = $forumBody->get('forum_id');
//		$languageCode = $forumBody->get('language_code');
//		registCreateLog($bbsId, "03", $languageCode, $forumBody->get('title'));
//		registCreateLog($bbsId, "04", $languageCode, $forumBody->get('description'));
//	}
//
//	function registCategoryEditLog($categoryBody) {
//
//	}
//
//	function registCategoryModifyLog($categoryBody) {
//
//	}
//
//	function registCategoryDeleteLog($categoryBody) {
//
//	}
//
//	function registCreateLog($bbsId, $bbsItemTypeCode, $languageCode, $bbsText) {
//		return regist($bbsId, $bbsItemTypeCode, $languageCode, "1", $bbsText);
//	}
//	function registModifyLog($bbsId, $bbsItemTypeCode, $languageCode, $bbsText) {
//		return regist($bbsId, $bbsItemTypeCode, $languageCode, "2", $bbsText);
//	}
//	function registEditLog($bbsId, $bbsItemTypeCode, $languageCode, $bbsText) {
//		return regist($bbsId, $bbsItemTypeCode, $languageCode, "3", $bbsText);
//	}
//	function registDeleteLog($bbsId, $bbsItemTypeCode, $languageCode, $bbsText) {
//		return regist($bbsId, $bbsItemTypeCode, $languageCode, "4", $bbsText);
//	}
//
//	function regist($bbsId, $bbsItemTypeCode, $languageCode, $processTypeCode, $bbsText) {
//		$root =& XCube_Root::getSingleton();
//		$userId = $root->mContext->mXoopsUser->get('uid');
//
//		$sql  = '';
//		$sql .= ' INSERT INTO ';
//		$sql .=       $this->tableName;
//		$sql .= '    (`bbs_id`, `bbs_item_type_cd`, `language_code`, `history_count` ';
//		$sql .= '     ,`proc_type_cd`, `bbs_text`, `user_id`, `create_date`, `delete_flag`) ';
//		$sql .= '   SELECT ';
//		$sql .= '      \'%d\', \'%s\', \'%s\', COALESCE(MAX(`history_count`), 0)+1, \'%s\' ';
//		$sql .= '     , \'%s\', \'%s\', \'%d\', \'0\' ';
//		$sql .= '   FROM ';
//		$sql .= 		$this->tableName;
//		$sql .= '   WHERE ';
//		$sql .= '       `bbs_id` = \'%d\' ';
//		$sql .= '   AND `bbs_item_type_cd` = \'%s\' ';
//		$sql .= '   AND `language_code` = \'%s\' ';
//		$sql .= '   AND `delete_flag` = \'0\' ';
//
//		$sql = sprintf($sql, intval($bbsId), $this->escape($bbsItemTypeCode)
//						, $this->escape($languageCode), $this->escape($processTypeCode)
//						, $this->escape($bbsText), $this->escape($userId), time()
//						, intval($bbsId), $this->escape($bbsItemTypeCode)
//						, $this->escape($languageCode));
//		$result = $this->db->queryf($sql);
//		return (bool)$result;
//	}
}
?>