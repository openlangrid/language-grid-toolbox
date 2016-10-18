<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
/* $Id: PostedNoticeConfig.handler.php 4540 2010-10-07 04:01:57Z uehara $ */

/*
 *
 */
class PostedNoticeConfigObject extends XoopsSimpleObject {

	function PostedNoticeConfigObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', false, 16);
		$this->initVar('notice_type', XOBJ_DTYPE_INT, 0, false);
	}
}

/*
 *
 */
class PostedNoticeConfigHandler extends XoopsObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "id";
	var $mClass = "PostedNoticeConfigObject";

	public function __construct($db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_posted_notice_config");
	}

	public function findByUserId($uid) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('user_id', $uid));
		$objects = parent::getObjects($c);
		if ($objects != null && is_array($objects) && count($objects) > 0) {
			return $objects[0];
		}
		return false;
	}

	public function save($uid, $type, $language) {
		$obj = $this->findByUserId($uid);
		if ($obj === false) {
			$obj = parent::create(true);
		}
		$obj->set('user_id', $uid);
		$obj->set('language_code', $language);
		$obj->set('notice_type', $type);

		return parent::insert($obj, true);
	}

	public function getSendToUserByEachTime() {
		return $this->getSendToUsers('1');
	}

	public function getSendToUserByOnceDay() {
		return $this->getSendToUsers('2');
	}

	private function getSendToUsers($noticeType) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('notice_type', $noticeType));
		$objects = parent::getObjects($c);
		if ($objects != null && is_array($objects) && count($objects) >0) {
			return $objects;
		}
		return false;
	}
}

?>
