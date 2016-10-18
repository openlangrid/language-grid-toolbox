<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once XOOPS_TRUST_PATH.'/modules/collabtrans/class/abstract_model.php';
require_once XOOPS_TRUST_PATH.'/modules/collabtrans/class/user.php';

class Forum extends AbstractModel {

	public function __construct($params) {
		$params['id'] = $params['forum_id'];
		unset($params['forum_id']);

		$params['creator'] = $params['uid'];
		unset($params['uid']);

		parent::__construct($params);
	}

	public static function findById($id, $lang = 'en') {
		$db = $GLOBALS['xoopsDB'];
		$forumsTable = $db->prefix('communication_forums');
		$forumsBodyTable = $db->prefix('communication_forums_body');

		$sql  = '';
		$sql .= ' SELECT * FROM '.$forumsTable.' ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$forumsBodyTable.' WHERE `language_code` = \''.$lang.'\') AS T1 USING (forum_id) ';
		$sql .= ' WHERE forum_id = '.intval($id).' AND delete_flag = 0 ';

		$result = $db->query($sql);

		$forum = null;
		if ($row = $db->fetchArray($result)) {
			$forum = new Forum($row);
		}

		return $forum;
	}

	public function getName() {
		return $this->_get('title');
	}

	protected function getTableName() {
	}

	protected function getColumnsOnInsert() {
		return array();
	}

	public function getPostsCount() {
		return $this->record['forum_posts_count'];
	}

	public function getLastPostedDate() {
		return $this->record['forum_last_post_time'];
	}

	public function getLastPostedDateAsString() {
		return date(_MD_TR_DTFMT_YMDHI, $this->getLastPostedDate());
	}
}
