<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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

Toolbox::import('toolbox.exception.SQLException');
Toolbox::import('toolbox.database.dao.GenericDAO');
Toolbox::import('toolbox.entity.UserEntity');

class MessageDAO extends GenericDAO {
	public function __construct($db) {
		parent::__construct($db);
	}

	public function getMessagesByTopicID($topicId, $offset, $limit) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$userTable = $this->db->prefix('users');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');

		$groupId = $this->root->mContext->mXoopsUser->getGroups();
		$selectedLanguage = $this->languageManager->getSelectedLanguage();

		$sql  = '';
		$sql .= ' SELECT post_id, uid, uname, language_code, description, poster_ip, post_original_language, post_time, post_order, delete_flag  FROM ('.$postsTable;
		$sql .= ' LEFT JOIN (SELECT post_id, language_code, description FROM '.$postsBodyTable.' WHERE `language_code` = \''.$selectedLanguage.'\') AS T1 USING (post_id)) ';
		$sql .= ' LEFT JOIN (SELECT uid, uname FROM '.$userTable.') AS T2 USING (uid) ';
		$sql .= ' WHERE topic_id = '.intval($topicId).' ';
		$sql .= ' ORDER BY post_order ASC';
		$sql .= ' LIMIT '.intval($offset).', '.intval($limit);

		$result = $this->db->query($sql);
		if (!$result) {
			die($sql);
		}

		$posts = array();
		require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
		$i = 0;
		while ($row = $this->db->fetchArray($result)) {
			$posts[$i] = array(
				'id' => $row['post_id'],
				'user' => array(
					'id' => $row['uid'],
					'name' => $row['uname']
				),
				'permission' => array(
					'delete' => false,
					'edit' => false,
					'modify' => false
				),
				'language' => array(
					'code' => $row['post_original_language'],
					'name' => $LANGRID_LANGUAGE_ARRAY[$row['post_original_language']]
				),
				'message' => $row['description'],
				'ip' => $row['poster_ip'],
				'date' => formatTimestamp($row['post_time'], 'm'),
				'time' => $row['post_time'],
				'order' => $row['post_order']
			);
			if ($row['delete_flag']) {
				$posts[$i]['permission'] = array(
					'delete' => false,
					'edit' => false,
					'modify' => false,
					'reply' => false
				);
			} else {
				if ($row['post_original_language'] != $this->languageManager->getSelectedLanguage()) {
					$posts[$i]['permission']['modify'] = true;
				}
				if ($this->root->mContext->mXoopsUser->isAdmin() || $this->root->mContext->mXoopsUser->get('uid') == $row['uid']) {
					if (!$posts[$i]['permission']['modify']) {
						$posts[$i]['permission']['edit'] = true;
					}
					$posts[$i]['permission']['delete'] = true;
				}
			}
			$i++;
		}
		return $posts;
	}
}

?>